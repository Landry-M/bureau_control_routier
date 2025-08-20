<?php

namespace Control;

use Model\Db;
use ORM;
use Exception;

class SearchController extends Db {
    private string $dbName = 'control_routier';

    public function search() {
        try {
            $pdo = $this->getConnexion();
            $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
            // Dedicated plate-only search: if `plate` provided, force table vehicule_plaque
            $plate = isset($_GET['plate']) ? trim((string)$_GET['plate']) : '';
            // Backend pagination controls
            $perType = isset($_GET['per_type']) ? max(1, min(100, (int)$_GET['per_type'])) : 10; // default 10
            $filterType = isset($_GET['type']) ? preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_GET['type']) : '';
            if ($plate !== '') {
                $q = $plate;
                $filterType = 'vehicule_plaque';
            }
            $page = isset($_GET['page']) ? max(0, (int)$_GET['page']) : 0;
            $results = [];
            if ($q !== '') {
                $like = '%' . $q . '%';
                // Fetch tables in current schema
                $tablesStmt = $pdo->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE='BASE TABLE'");
                $tablesStmt->execute([$this->dbName]);
                $tables = $tablesStmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];

                foreach ($tables as $table) {
                    // Skip some technical/sensitive tables
                    if (in_array($table, ['migrations', 'activites', 'users'])) { continue; }
                    // If a specific type is requested, skip others
                    if ($filterType !== '' && $table !== $filterType) { continue; }

                    // Find primary key column
                    $pkStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_KEY='PRI' ORDER BY ORDINAL_POSITION LIMIT 1");
                    $pkStmt->execute([$this->dbName, $table]);
                    $pk = $pkStmt->fetchColumn();
                    if (!$pk) { continue; }

                    // Get text-like columns
                    $colsStmt = $pdo->prepare("SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND DATA_TYPE IN ('varchar','char','text','mediumtext','longtext')");
                    $colsStmt->execute([$this->dbName, $table]);
                    $cols = $colsStmt->fetchAll(\PDO::FETCH_ASSOC);
                    if (!$cols) { continue; }

                    $colNames = array_column($cols, 'COLUMN_NAME');
                    // Build WHERE col LIKE ? OR ...
                    $where = implode(' OR ', array_map(function($c){ return "`$c` LIKE ?"; }, $colNames));
                    // LIMIT/OFFSET: only apply OFFSET when filtering by a single type (to avoid mixing offsets across types)
                    $limit = (int)$perType;
                    $offset = ($filterType !== '' ? (int)$page * $limit : 0);
                    $sql = "SELECT `$pk` AS id, '$table' AS type, CONCAT_WS(' ', " . implode(', ', array_map(function($c){ return "`$c`"; }, array_slice($colNames, 0, 4))) . ") AS title FROM `$table` WHERE $where LIMIT $limit" . ($offset > 0 ? " OFFSET $offset" : "");
                    $stmt = $pdo->prepare($sql);
                    // bind likes for each column
                    $params = array_fill(0, count($colNames), $like);
                    try {
                        $stmt->execute($params);
                        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                        foreach ($rows as $r) {
                            $results[] = [
                                'type' => $r['type'],
                                'id' => $r['id'],
                                'title' => $r['title'] ?: $r['type'] . ' #' . $r['id'],
                            ];
                        }
                    } catch (\Throwable $e) {
                        // Ignore errors per table to keep global search resilient
                        error_log('[Search] table ' . $table . ' error: ' . $e->getMessage());
                    }
                }
            }

            // Render view
            $query = $q; // for view
            // Expose pagination parameters to the view for potential UI usage
            $per_type = $perType;
            $type = $filterType;
            $page_index = $page;
            // If searching plates specifically, expose candidates and related contraventions
            $vehiculeRecord = null; // selected/first record
            $vehiculeContraventions = []; // of selected/first record
            $vehiculeCandidates = []; // all matching by exact plate (case-insensitive)
            $vehiculeContraventionsById = []; // map id => list
            $vehiculeOwnersById = []; // map id => ['pid'=>..., 'proprietaire'=>...]
            $vehiculePk = null; // primary key column name for vehicule_plaque
            if ($type === 'vehicule_plaque' && !empty($results)) {
                try {
                    $searchedPlate = isset($_GET['plate']) ? trim((string)$_GET['plate']) : '';
                    $searchedPlateUpper = strtoupper($searchedPlate);
                    // Determine PK of table
                    $pkStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_KEY='PRI' ORDER BY ORDINAL_POSITION LIMIT 1");
                    $pkStmt->execute([$this->dbName, 'vehicule_plaque']);
                    $pk = $pkStmt->fetchColumn();
                    if (!$pk || !is_string($pk) || $pk === '') { $pk = 'id'; }
                    $vehiculePk = $pk;
                    if ($pk) {
                        // Load candidates matching exact plate (case-insensitive). If none, fallback to IDs from $results
                        $candStmt = $pdo->prepare("SELECT * FROM `vehicule_plaque` WHERE UPPER(`plaque`) = :plaque ORDER BY `$pk` ASC");
                        $candStmt->execute([':plaque' => $searchedPlateUpper]);
                        $vehiculeCandidates = $candStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                        if (!$vehiculeCandidates) {
                            // Fallback: get by the IDs returned in results (limited)
                            $ids = array_values(array_unique(array_map(function($r){ return (int)($r['id'] ?? 0); }, $results)));
                            if ($ids) {
                                $in = implode(',', array_fill(0, count($ids), '?'));
                                $stmt = $pdo->prepare("SELECT * FROM `vehicule_plaque` WHERE `$pk` IN ($in) ORDER BY `$pk` ASC");
                                $stmt->execute($ids);
                                $vehiculeCandidates = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                            }
                        }
                        if ($vehiculeCandidates) {
                            $vehiculeRecord = $vehiculeCandidates[0];
                            // Load contraventions for all candidates in one pass
                            $candIds = array_map(function($r) use ($pk){ return (string)($r[$pk] ?? ''); }, $vehiculeCandidates);
                            $candIds = array_values(array_filter($candIds, function($v){ return $v !== ''; }));
                            if ($candIds) {
                                $cvs = ORM::for_table('contraventions')
                                    ->where('type_dossier', 'vehicule_plaque')
                                    ->where_in('dossier_id', $candIds)
                                    ->order_by_desc('date_infraction')
                                    ->find_array() ?: [];
                                // group by dossier_id
                                foreach ($candIds as $idKey) { $vehiculeContraventionsById[$idKey] = []; }
                                foreach ($cvs as $cv) {
                                    $did = (string)($cv['dossier_id'] ?? '');
                                    if ($did !== '') { $vehiculeContraventionsById[$did][] = $cv; }
                                }
                                $firstId = (string)($vehiculeRecord[$pk] ?? '');
                                $vehiculeContraventions = $vehiculeContraventionsById[$firstId] ?? [];

                                // Load current owner for each candidate (latest association) using MAX(id)
                                try {
                                    $in = implode(',', array_fill(0, count($candIds), '?'));
                                    $sql = "SELECT pv.vehicule_plaque_id AS vid, p.id AS pid, p.nom AS proprietaire
                                            FROM particulier_vehicule pv
                                            JOIN (
                                                SELECT vehicule_plaque_id, MAX(id) AS max_id
                                                FROM particulier_vehicule
                                                WHERE vehicule_plaque_id IN ($in)
                                                GROUP BY vehicule_plaque_id
                                            ) t ON t.max_id = pv.id
                                            JOIN particuliers p ON p.id = pv.particulier_id";
                                    $stmt = ORM::get_db()->prepare($sql);
                                    $stmt->execute($candIds);
                                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                                    foreach ($rows as $row) {
                                        $vid = (string)($row['vid'] ?? '');
                                        if ($vid !== '') {
                                            $vehiculeOwnersById[$vid] = [
                                                'pid' => $row['pid'] ?? null,
                                                'proprietaire' => $row['proprietaire'] ?? null,
                                            ];
                                        }
                                    }
                                } catch (\Throwable $e) {
                                    // keep existing partial map; do not wipe out
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    error_log('[Search] load vehicule candidates failed: ' . $e->getMessage());
                    $vehiculeRecord = null;
                    $vehiculeContraventions = [];
                    $vehiculeCandidates = [];
                    $vehiculeContraventionsById = [];
                    $vehiculeOwnersById = [];
                }
            }
            include 'views/search_results.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur recherche: ' . $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function detail() {
        try {
            $pdo = $this->getConnexion();
            $type = isset($_GET['type']) ? preg_replace('/[^a-zA-Z0-9_]/', '', (string)$_GET['type']) : '';
            $id = isset($_GET['id']) ? (string)$_GET['id'] : '';
            if ($type === '' || $id === '') {
                $_SESSION['error'] = 'Paramètres manquants pour le détail.';
                header('Location: /search');
                return;
            }
            // Validate table exists
            $tblStmt = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=? AND TABLE_NAME=?");
            $tblStmt->execute([$this->dbName, $type]);
            if (!$tblStmt->fetchColumn()) {
                $_SESSION['error'] = 'Table inconnue.';
                header('Location: /search');
                return;
            }
            // Find PK
            $pkStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_KEY='PRI' ORDER BY ORDINAL_POSITION LIMIT 1");
            $pkStmt->execute([$this->dbName, $type]);
            $pk = $pkStmt->fetchColumn();
            if (!$pk) {
                $_SESSION['error'] = 'Clé primaire introuvable.';
                header('Location: /search');
                return;
            }
            // Fetch row
            $sql = "SELECT * FROM `$type` WHERE `$pk` = :id LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) {
                $_SESSION['error'] = 'Enregistrement non trouvé.';
                header('Location: /search');
                return;
            }

            $record = $row;
            // Si accident, charger aussi les témoins liés
            if ($type === 'accidents' && isset($row[$pk])) {
                try {
                    $temStmt = $pdo->prepare("SELECT * FROM `temoins` WHERE `id_accident` = :aid ORDER BY `id` ASC");
                    $temStmt->execute([':aid' => $id]);
                    $record['temoins'] = $temStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                } catch (\Throwable $e) {
                    // Ne pas bloquer la page détail en cas d'erreur témoin
                    error_log('[Search detail] temoins load error: ' . $e->getMessage());
                    $record['temoins'] = [];
                }
            }
            // Charger les contraventions liées pour certains types de dossiers (ID primaire)
            $contraventions = [];
            try {
                $typesAvecCv = ['conducteur_vehicule','vehicules','particuliers','entreprises'];
                if (in_array($type, $typesAvecCv, true)) {
                    $contraventions = ORM::for_table('contraventions')
                        ->where('type_dossier', $type)
                        ->where('dossier_id', (string)$id)
                        ->order_by_desc('date_infraction')
                        ->find_array() ?: [];
                }
            } catch (\Throwable $e) {
                error_log('[Search detail] contraventions load error: ' . $e->getMessage());
                $contraventions = [];
            }
            $table = $type;
            include 'views/search_detail.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur détail: ' . $e->getMessage();
            header('Location: /search');
        }
    }
}

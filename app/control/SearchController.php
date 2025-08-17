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
            $results = [];
            if ($q !== '') {
                $like = '%' . $q . '%';
                // Fetch tables in current schema
                $tablesStmt = $pdo->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE='BASE TABLE'");
                $tablesStmt->execute([$this->dbName]);
                $tables = $tablesStmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];

                foreach ($tables as $table) {
                    // Skip some technical tables if needed
                    if (in_array($table, ['migrations'])) { continue; }

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
                    $sql = "SELECT `$pk` AS id, '$table' AS type, CONCAT_WS(' ', " . implode(', ', array_map(function($c){ return "`$c`"; }, array_slice($colNames, 0, 4))) . ") AS title FROM `$table` WHERE $where LIMIT 5";
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
            $table = $type;
            include 'views/search_detail.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur détail: ' . $e->getMessage();
            header('Location: /search');
        }
    }
}

<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;

class ArrestationController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function create(array $data)
    {
        $this->getConnexion();
        $particulierId = (int)($data['particulier_id'] ?? 0);
        $motif = trim((string)($data['motif'] ?? ''));
        $lieu = trim((string)($data['lieu'] ?? ''));
        $dateArrestation = trim((string)($data['date_arrestation'] ?? ''));
        $dateSortiePrison = trim((string)($data['date_sortie_prison'] ?? ''));
        if ($particulierId <= 0 || $motif === '') {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }
        // Normaliser la date
        $dateNorm = null;
        if ($dateArrestation !== '') {
            $ts = strtotime($dateArrestation);
            if ($ts !== false) { $dateNorm = date('Y-m-d H:i:s', $ts); }
        }
        $dateSortieNorm = null;
        if ($dateSortiePrison !== '') {
            $ts2 = strtotime($dateSortiePrison);
            if ($ts2 !== false) { $dateSortieNorm = date('Y-m-d H:i:s', $ts2); }
        }
        $now = date('Y-m-d H:i:s');

        $row = ORM::for_table('arrestations')->create();
        $row->particulier_id = $particulierId;
        $row->motif = $motif;
        $row->lieu = $lieu !== '' ? $lieu : null;
        $row->date_arrestation = $dateNorm;
        $row->date_sortie_prison = $dateSortieNorm;
        $row->created_by = $_SESSION['user']['username'] ?? null;
        $row->created_at = $now;
        $row->updated_at = $now;
        $row->save();

        $id = (int)$row->id;
        // Log creation
        $this->activityLogger->logCreate(
            $_SESSION['user']['username'] ?? null,
            'arrestations',
            $id,
            [ 'particulier_id' => $particulierId ]
        );
        return ['ok' => true, 'id' => $id];
    }

    public function listByParticulier(int $particulierId)
    {
        $this->getConnexion();
        $pid = (int)$particulierId;
        if ($pid <= 0) return [];
        $rows = ORM::for_table('arrestations')
            ->where('particulier_id', $pid)
            ->order_by_desc('id')
            ->find_array();
        $this->activityLogger->logView(
            $_SESSION['user']['username'] ?? null,
            'arrestations_particulier',
            [ 'particulier_id' => $pid, 'results' => count($rows) ]
        );
        return $rows ?: [];
    }

    /**
     * Libérer une arrestation par ID: fixe date_sortie_prison (now par défaut)
     */
    public function releaseById(int $id, ?string $dateSortie = null)
    {
        $this->getConnexion();
        $aid = (int)$id;
        if ($aid <= 0) return ['ok' => false, 'error' => 'ID invalide'];
        $row = ORM::for_table('arrestations')->find_one($aid);
        if (!$row) return ['ok' => false, 'error' => 'Arrestation introuvable'];
        // Normaliser date sortie
        $dateNorm = null;
        if ($dateSortie && trim($dateSortie) !== '') {
            $ts = strtotime($dateSortie);
            if ($ts !== false) { $dateNorm = date('Y-m-d H:i:s', $ts); }
        }
        if ($dateNorm === null) { $dateNorm = date('Y-m-d H:i:s'); }
        $row->date_sortie_prison = $dateNorm;
        $row->updated_at = date('Y-m-d H:i:s');
        $row->save();
        // Log
        $this->activityLogger->logUpdate(
            $_SESSION['user']['username'] ?? null,
            'arrestations',
            (int)$row->id,
            null,
            [ 'action' => 'liberer', 'date_sortie_prison' => $dateNorm ]
        );
        return ['ok' => true, 'id' => (int)$row->id, 'date_sortie_prison' => $dateNorm];
    }

    /**
     * Libérer le particulier: fixe date_sortie_prison sur la dernière arrestation active (sans date de sortie)
     */
    public function releaseLatestActiveByParticulier(int $particulierId, ?string $dateSortie = null)
    {
        $this->getConnexion();
        $pid = (int)$particulierId;
        if ($pid <= 0) return ['ok' => false, 'error' => 'Particulier invalide'];
        $row = ORM::for_table('arrestations')
            ->where('particulier_id', $pid)
            ->where_null('date_sortie_prison')
            ->order_by_desc('id')
            ->find_one();
        if (!$row) return ['ok' => false, 'error' => 'Aucune arrestation active trouvée'];
        // Normaliser date sortie
        $dateNorm = null;
        if ($dateSortie && trim($dateSortie) !== '') {
            $ts = strtotime($dateSortie);
            if ($ts !== false) { $dateNorm = date('Y-m-d H:i:s', $ts); }
        }
        if ($dateNorm === null) { $dateNorm = date('Y-m-d H:i:s'); }
        $row->date_sortie_prison = $dateNorm;
        $row->updated_at = date('Y-m-d H:i:s');
        $row->save();
        // Log
        $this->activityLogger->logUpdate(
            $_SESSION['user']['username'] ?? null,
            'arrestations',
            (int)$row->id,
            null,
            [ 'action' => 'liberer_particulier', 'particulier_id' => $pid, 'date_sortie_prison' => $dateNorm ]
        );
        return ['ok' => true, 'id' => (int)$row->id, 'date_sortie_prison' => $dateNorm];
    }
}

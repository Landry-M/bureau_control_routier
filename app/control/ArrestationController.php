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
        if ($particulierId <= 0 || $motif === '') {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }
        // Normaliser la date
        $dateNorm = null;
        if ($dateArrestation !== '') {
            $ts = strtotime($dateArrestation);
            if ($ts !== false) { $dateNorm = date('Y-m-d H:i:s', $ts); }
        }
        $now = date('Y-m-d H:i:s');

        $row = ORM::for_table('arrestations')->create();
        $row->particulier_id = $particulierId;
        $row->motif = $motif;
        $row->lieu = $lieu !== '' ? $lieu : null;
        $row->date_arrestation = $dateNorm;
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
}

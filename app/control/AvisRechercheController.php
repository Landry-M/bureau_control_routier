<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;

class AvisRechercheController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function create(array $data)
    {
        $this->getConnexion();
        $cibleType = trim((string)($data['cible_type'] ?? ''));
        $cibleId = (int)($data['cible_id'] ?? 0);
        $motif = trim((string)($data['motif'] ?? ''));
        $niveau = trim((string)($data['niveau'] ?? 'moyen'));
        if ($cibleType === '' || $cibleId <= 0 || $motif === '') {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }
        $now = date('Y-m-d H:i:s');
        $row = ORM::for_table('avis_recherche')->create();
        $row->cible_type = $cibleType; // ex: particulier
        $row->cible_id = $cibleId;
        $row->motif = $motif;
        $row->niveau = $niveau; // faible | moyen | eleve
        $row->statut = 'actif';
        $row->created_by = $_SESSION['user']['username'] ?? null;
        $row->created_at = $now;
        $row->updated_at = $now;
        $row->save();

        $id = (int)$row->id;
        // Log creation
        $this->activityLogger->logCreate(
            $_SESSION['user']['username'] ?? null,
            'avis_recherche',
            $id,
            [
                'cible_type' => $cibleType,
                'cible_id' => $cibleId,
                'niveau' => $niveau
            ]
        );
        return ['ok' => true, 'id' => $id];
    }

    public function listByParticulier(int $particulierId)
    {
        $this->getConnexion();
        $pid = (int)$particulierId;
        if ($pid <= 0) return [];
        $rows = ORM::for_table('avis_recherche')
            ->where('cible_type', 'particulier')
            ->where('cible_id', $pid)
            ->order_by_desc('id')
            ->find_array();
        $this->activityLogger->logView(
            $_SESSION['user']['username'] ?? null,
            'avis_recherche_particulier',
            [ 'particulier_id' => $pid, 'results' => count($rows) ]
        );
        return $rows ?: [];
    }

    public function close(int $id)
    {
        $this->getConnexion();
        $rid = (int)$id;
        if ($rid <= 0) return ['ok' => false, 'error' => 'ID invalide'];
        $row = ORM::for_table('avis_recherche')->find_one($rid);
        if (!$row) return ['ok' => false, 'error' => 'Avis introuvable'];
        $old = [ 'statut' => $row->statut ];
        $row->statut = 'clos';
        $row->updated_at = date('Y-m-d H:i:s');
        $row->save();
        $this->activityLogger->logUpdate(
            $_SESSION['user']['username'] ?? null,
            'avis_recherche',
            $rid,
            $old,
            [ 'statut' => 'clos' ]
        );
        return ['ok' => true, 'id' => $rid];
    }
}

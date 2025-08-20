<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;

class PermisTemporaireController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    private function generateNumero(): string
    {
        $date = date('Ymd');
        $rand = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return "PT-{$date}-{$rand}";
    }

    public function create(array $data)
    {
        $this->getConnexion();
        $cibleType = trim((string)($data['cible_type'] ?? 'particulier'));
        $cibleId = (int)($data['cible_id'] ?? 0);
        $motif = trim((string)($data['motif'] ?? ''));
        $dateDebut = trim((string)($data['date_debut'] ?? date('Y-m-d')));
        $dateFin = trim((string)($data['date_fin'] ?? ''));
        // Si date_fin manquante, la définir par défaut à +7 jours après date_debut
        if ($dateFin === '') {
            $base = $dateDebut !== '' ? $dateDebut : date('Y-m-d');
            $dateFin = date('Y-m-d', strtotime($base . ' +7 days'));
        }

        if ($cibleType === '' || $cibleId <= 0 || $dateDebut === '' || $dateFin === '') {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }

        $now = date('Y-m-d H:i:s');
        // Permettre un numéro fourni (ex: plaque temporaire saisie par l'agent), sinon auto-générer
        $numero = trim((string)($data['numero'] ?? ''));
        if ($numero === '') {
            $numero = $this->generateNumero();
        }

        $row = ORM::for_table('permis_temporaire')->create();
        $row->cible_type = $cibleType;
        $row->cible_id = $cibleId;
        $row->numero = $numero;
        $row->motif = $motif;
        $row->date_debut = $dateDebut;
        $row->date_fin = $dateFin;
        $row->statut = 'actif';
        $row->created_by = $_SESSION['user']['username'] ?? null;
        $row->created_at = $now;
        $row->updated_at = $now;
        $row->save();

        $id = (int)$row->id;
        $this->activityLogger->logCreate(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire',
            $id,
            [
                'cible_type' => $cibleType,
                'cible_id' => $cibleId,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin
            ]
        );

        return ['ok' => true, 'id' => $id, 'numero' => $numero];
    }

    public function listByParticulier(int $particulierId)
    {
        $this->getConnexion();
        $pid = (int)$particulierId;
        if ($pid <= 0) return [];
        $rows = ORM::for_table('permis_temporaire')
            ->where('cible_type', 'particulier')
            ->where('cible_id', $pid)
            ->order_by_desc('id')
            ->find_array();
        $this->activityLogger->logView(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire_particulier',
            ['particulier_id' => $pid, 'results' => count($rows)]
        );
        return $rows ?: [];
    }

    public function listByVehicule(int $vehiculeId)
    {
        $this->getConnexion();
        $vid = (int)$vehiculeId;
        if ($vid <= 0) return [];
        $rows = ORM::for_table('permis_temporaire')
            ->where('cible_type', 'vehicule_plaque')
            ->where('cible_id', $vid)
            ->order_by_desc('id')
            ->find_array();
        $this->activityLogger->logView(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire_vehicule',
            ['vehicule_id' => $vid, 'results' => is_array($rows) ? count($rows) : 0]
        );
        return $rows ?: [];
    }

    public function close(int $id)
    {
        $this->getConnexion();
        $rid = (int)$id;
        if ($rid <= 0) return ['ok' => false, 'error' => 'ID invalide'];
        $row = ORM::for_table('permis_temporaire')->find_one($rid);
        if (!$row) return ['ok' => false, 'error' => 'Permis introuvable'];
        $old = ['statut' => $row->statut];
        $row->statut = 'clos';
        $row->updated_at = date('Y-m-d H:i:s');
        $row->save();
        $this->activityLogger->logUpdate(
            $_SESSION['user']['username'] ?? null,
            'permis_temporaire',
            $rid,
            $old,
            ['statut' => 'clos']
        );
        return ['ok' => true, 'id' => $rid];
    }
}

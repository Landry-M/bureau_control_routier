<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;

class ParticulierVehiculeController extends Db
{
    private ActivityLogger $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    /**
     * Crée une association entre un particulier et un véhicule (propriétaire par défaut)
     * @param array $data expects: particulier_id, vehicule_plaque_id, notes (optional)
     * @return array JSON-safe array
     */
    public function createAssociation(array $data): array
    {
        $this->getConnexion();
        $pid = (int)($data['particulier_id'] ?? 0);
        $vid = (int)($data['vehicule_plaque_id'] ?? 0);
        $notes = trim((string)($data['notes'] ?? ''));
        if ($pid <= 0 || $vid <= 0) {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }

        // Vérifier doublon
        $exists = ORM::for_table('particulier_vehicule')
            ->where('particulier_id', $pid)
            ->where('vehicule_plaque_id', $vid)
            ->find_one();
        if ($exists) {
            return ['ok' => true, 'dup' => true, 'id' => (int)$exists->id];
        }

        $assoc = ORM::for_table('particulier_vehicule')->create();
        $assoc->particulier_id = $pid;
        $assoc->vehicule_plaque_id = $vid;
        $assoc->date_assoc = date('Y-m-d H:i:s');
        $assoc->notes = ($notes !== '') ? $notes : null;
        $assoc->created_at = date('Y-m-d H:i:s');
        $assoc->created_by = $_SESSION['username'] ?? null;
        $assoc->save();

        // Log activité
        try {
            $this->activityLogger->logCreate(
                $_SESSION['username'] ?? null,
                'particulier_vehicule',
                $assoc->id(),
                [
                    'particulier_id' => $pid,
                    'vehicule_plaque_id' => $vid,
                    'role' => 'proprietaire'
                ]
            );
        } catch (\Throwable $e) {
            // non bloquant
        }

        return ['ok' => true, 'id' => (int)$assoc->id];
    }

    /**
     * Liste des véhicules associés à un particulier (optionnel)
     */
    public function listByParticulier(int $particulierId): array
    {
        $this->getConnexion();
        if ($particulierId <= 0) return [];
        // Join simple pour renvoyer quelques infos véhicule
        $sql = "SELECT pv.*, vp.plaque, vp.marque, vp.modele, vp.couleur, vp.annee
                FROM particulier_vehicule pv
                JOIN vehicule_plaque vp ON vp.id = pv.vehicule_plaque_id
                WHERE pv.particulier_id = :pid
                ORDER BY pv.id DESC";
        $stmt = ORM::get_db()->prepare($sql);
        $stmt->execute([':pid' => $particulierId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}

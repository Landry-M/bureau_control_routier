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

    /**
     * Transférer la propriété d'un véhicule à un particulier.
     * Supprime les associations existantes pour ce véhicule puis en crée une nouvelle.
     * @param int $vehiculeId
     * @param int $nouveauParticulierId
     * @param string|null $motif
     * @return array
     */
    public function transferOwnership(int $vehiculeId, int $nouveauParticulierId, ?string $motif = null): array
    {
        $this->getConnexion();
        if ($vehiculeId <= 0 || $nouveauParticulierId <= 0) {
            return ['ok' => false, 'error' => 'Paramètres invalides'];
        }
        $db = ORM::get_db();
        try {
            // Vérifier l'existence du véhicule et du particulier
            $veh = ORM::for_table('vehicule_plaque')->find_one($vehiculeId);
            if (!$veh) { return ['ok'=>false, 'error'=>'Véhicule introuvable']; }
            $part = ORM::for_table('particuliers')->find_one($nouveauParticulierId);
            if (!$part) { return ['ok'=>false, 'error'=>'Particulier introuvable']; }
            $db->beginTransaction();
            // Supprimer anciennes associations
            $del = $db->prepare('DELETE FROM particulier_vehicule WHERE vehicule_plaque_id = :vid');
            $del->execute([':vid' => $vehiculeId]);
            // Créer la nouvelle association
            $assoc = ORM::for_table('particulier_vehicule')->create();
            $assoc->particulier_id = $nouveauParticulierId;
            $assoc->vehicule_plaque_id = $vehiculeId;
            $assoc->date_assoc = date('Y-m-d H:i:s');
            $note = 'Transfert de propriété';
            if ($motif) { $note .= ' - Motif: ' . $motif; }
            $assoc->notes = $note;
            $assoc->created_at = date('Y-m-d H:i:s');
            $assoc->created_by = $_SESSION['username'] ?? null;
            $assoc->save();
            $newId = (int)$assoc->id();
            $db->commit();
            // Log activité
            try {
                $this->activityLogger->logUpdate(
                    $_SESSION['username'] ?? null,
                    'particulier_vehicule',
                    $newId,
                    null,
                    [
                        'action' => 'transfer_ownership',
                        'vehicule_plaque_id' => $vehiculeId,
                        'nouveau_particulier_id' => $nouveauParticulierId,
                        'motif' => $motif
                    ]
                );
            } catch (\Throwable $e) { /* non bloquant */ }
            return ['ok' => true, 'id' => $newId];
        } catch (\Throwable $e) {
            try { $db->rollBack(); } catch (\Throwable $e2) {}
            error_log('[ParticulierVehiculeController] transferOwnership error: ' . $e->getMessage());
            return ['ok' => false, 'error' => 'Erreur lors du transfert'];
        }
    }
}

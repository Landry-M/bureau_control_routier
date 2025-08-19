<?php

namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;

class ContraventionController extends Db
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }
    public function updatePayed($id, $payed)
    {
        $this->getConnexion();
        $id = (int)$id;
        $val = ($payed === '1' || $payed === 1 || $payed === true || $payed === 'true') ? 1 : 0;

        $cv = ORM::for_table('contraventions')->find_one($id);
        if (!$cv) {
            return ['ok' => false, 'error' => 'Contravention introuvable'];
        }
        $oldValue = $cv->payed;
        $cv->set('payed', $val);
        $cv->save();
        
        // Logger la mise à jour du statut de paiement
        $this->activityLogger->logUpdate(
            $_SESSION['username'] ?? null,
            'contraventions',
            $id,
            ['payed' => $oldValue],
            ['payed' => $val]
        );
        
        return ['ok' => true, 'id' => $id, 'payed' => $val];
    }

    public function getByParticulierId($id)
    {
        $this->getConnexion();
        $pid = (int)$id;
        if ($pid <= 0) {
            return [];
        }
        $rows = ORM::for_table('contraventions')
            ->where('type_dossier', 'particuliers')
            ->where('dossier_id', $pid)
            ->order_by_desc('id')
            ->find_array();
        
        // Logger la consultation des contraventions par particulier
        $this->activityLogger->logView(
            $_SESSION['username'] ?? null,
            'contraventions_particulier',
            "Consultation des contraventions du particulier ID: {$pid} (" . count($rows) . " résultats)"
        );
        
        return $rows ?: [];
    }

    public function getByDossierIdAndType($dossierId, $type)
    {
        $this->getConnexion();
        $did = (string)$dossierId;
        $typ = (string)$type;
        if ($did === '' || $typ === '') { return []; }
        $rows = ORM::for_table('contraventions')
            ->where('type_dossier', $typ)
            ->where('dossier_id', $did)
            ->order_by_desc('id')
            ->find_array();
        
        // Logger la consultation des contraventions par dossier
        $this->activityLogger->logView(
            $_SESSION['username'] ?? null,
            'contraventions_dossier',
            "Consultation des contraventions du dossier {$typ} ID: {$did} (" . count($rows) . " résultats)"
        );
        
        return $rows ?: [];
    }

    // Migration: remapper dossier_id vers les IDs primaires selon le type de dossier
    public function migrateDossierIdToPrimary()
    {
        $this->getConnexion();
        $summary = [
            'conducteur_vehicule' => ['checked' => 0, 'updated' => 0],
            'particuliers' => ['checked' => 0, 'updated' => 0],
            'entreprises' => ['checked' => 0, 'updated' => 0],
        ];

        // Helper pour effectuer la migration générique
        $migrate = function(string $type, string $table, string $legacyCol) use (&$summary) {
            // Récupérer les contraventions de ce type
            $cvs = ORM::for_table('contraventions')->where('type_dossier', $type)->find_array();
            $summary[$type]['checked'] = count($cvs);
            if (!$cvs) return;

            // Collecter les valeurs legacy distinctes qui ne sont pas numériques (supposition ancienne)
            $values = [];
            foreach ($cvs as $cv) {
                $did = $cv['dossier_id'] ?? null;
                if ($did === null || $did === '') continue;
                $values[(string)$did] = true;
            }
            $keys = array_keys($values);
            if (!$keys) return;

            // Charger la table cible pour map legacy->id
            $rows = ORM::for_table($table)->where_in($legacyCol, $keys)->find_array();
            if (!$rows) return;
            $map = [];
            foreach ($rows as $r) {
                if (isset($r['id']) && isset($r[$legacyCol]) && $r[$legacyCol] !== '') {
                    $map[(string)$r[$legacyCol]] = (string)$r['id'];
                }
            }
            if (!$map) return;

            // Mettre à jour les contraventions correspondantes
            foreach ($cvs as $cv) {
                $old = (string)($cv['dossier_id'] ?? '');
                if ($old === '' || !isset($map[$old])) continue;
                if ($old === (string)$map[$old]) continue; // déjà ID
                $obj = ORM::for_table('contraventions')->find_one((int)$cv['id']);
                if ($obj) {
                    $obj->set('dossier_id', $map[$old]);
                    $obj->save();
                    $summary[$type]['updated']++;
                }
            }
        };

        // Types connus à migrer
        $migrate('conducteur_vehicule', 'conducteur_vehicule', 'numero_permis');
        $migrate('particuliers', 'particuliers', 'numero_national');
        $migrate('entreprises', 'entreprises', 'rccm');

        return ['ok' => true, 'summary' => $summary];
    }
}

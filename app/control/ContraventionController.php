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
}

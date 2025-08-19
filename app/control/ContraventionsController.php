<?php


namespace Control;

use Model\Db;
use Model\ActivityLogger;
use ORM;
use Exception;  

class ContraventionsController extends Db{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    public function create($data)
    {
        $this->getConnexion();
        $contravention = ORM::for_table('contraventions')
            ->create();
        
        $contravention->dossier_id = $data['dossier_id'];
        $contravention->type_dossier = $data['type_dossier'];
        $contravention->date_infraction = $data['date_infraction'];
        $contravention->lieu = $data['lieu'];
        $contravention->type_infraction = $data['type_infraction'];
        $contravention->description = $data['description'];
        $contravention->reference_loi = $data['reference_loi'];
        $contravention->amende = $data['amende'];
        $contravention->payed = $data['payed'];
        
        if ($contravention->save()) {
            // Log creation
            try {
                $this->activityLogger->logCreate(
                    $_SESSION['username'] ?? null,
                    'contraventions',
                    $contravention->id(),
                    [
                        'type_dossier' => $data['type_dossier'] ?? null,
                        'dossier_id' => $data['dossier_id'] ?? null,
                        'type_infraction' => $data['type_infraction'] ?? null,
                        'amende' => $data['amende'] ?? null
                    ]
                );
            } catch (\Throwable $e) {
                // non bloquant
            }
            $result['state'] = true;
            $result['message'] = 'Contravention enregistré avec succès';
            $result['data'] = $contravention->id;
            return $result;
        }else{
            $result['state'] = false;
            $result['message'] = 'Erreur lors de l\'enregistrement';
            return $result;
        }
    }
}
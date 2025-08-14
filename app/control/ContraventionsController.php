<?php


namespace Control;

use Model\Db;
use ORM;
use Exception;  

class ContraventionsController extends Db{

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
<?php

namespace Control;

use Model\Db;
use ORM;
use Exception;  

class EntrepriseController extends Db
{
    public function create($data)
    {
        $this->getConnexion();
        $entreprise = ORM::for_table('entreprises')
            ->create();
        $entreprise->designation = $data['designation'];
        $entreprise->marque_vehicule = $data['marque_vehicule'];
        $entreprise->plaque_vehicule = $data['plaque_vehicule'];
        $entreprise->siege_social = $data['siege_social'];
        $entreprise->gsm = $data['contact_telephone'];
        $entreprise->email = $data['contact_email'];
        $entreprise->personne_contact = $data['contact_personne'];
        $entreprise->rccm = $data['numero_siret'];
        $entreprise->secteur = $data['secteur_activite'];
        $entreprise->observations = $data['observations'];
        
        if($entreprise->save() ) {
            $result['state'] = true;
            $result['data'] = $entreprise;
            $result['message'] = "L'entreprise ". $data['designation']." a été enregistrée avec succès";
        }else{
            $result['state'] = false;
            $result['message'] = 'Erreur lors de l\'enregistrement de l\'entreprise';
        }

        return $result;
    }
}

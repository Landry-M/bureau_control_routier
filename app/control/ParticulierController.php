<?php

namespace Control;

use Exception;
use Model\Db;
use ORM;

class ParticulierController extends Db{

    public function create($data){

        $this->getConnexion();

        $particulier = ORM::for_table('particuliers')
            ->create();
        $particulier->nom = $data['nom'];
        $particulier->adresse = $data['adresse'];
        $particulier->profession = $data['profession'];
        $particulier->date_naissance = $data['date_naissance'];
        $particulier->genre = $data['genre'];
        $particulier->numero_national = $data['numero_national'];
        $particulier->gsm = $data['telephone'];
        $particulier->email = $data['email'];
        $particulier->lieu_naissance = $data['lieu_naissance'];
        $particulier->nationalite = $data['nationalite'];
        $particulier->etat_civil = $data['etat_civil'];
        $particulier->personne_contact = $data['personne_contact'];
        $particulier->observations = $data['observations'];
        
        if($particulier->save()){
            $result['state'] = true;
            $result['message'] = $data['nom'].' a été enregistré avec succès';
            $result['id'] = $particulier->id;
            $result['data'] = $particulier;
            return $result;
        }else{
            $result['state'] = false;
            $result['message'] = 'Erreur lors de l\'enregistrement du particulier';
            return $result;
        }


    }   
}

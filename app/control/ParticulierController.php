<?php

namespace Control;

use Exception;
use Model\Db;
use Model\ActivityLogger;
use ORM;

class ParticulierController extends Db{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

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
            // Logger la création du particulier
            $this->activityLogger->logCreate(
                $_SESSION['username'] ?? null,
                'particuliers',
                $particulier->id(),
                ['nom' => $data['nom'], 'numero_national' => $data['numero_national']]
            );
            
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

    /**
     * Récupérer la liste des particuliers (plus récents d'abord)
     * @return array
     */
    public function listAll(): array
    {
        try {
            $this->getConnexion();
            $rows = ORM::for_table('particuliers')
                ->order_by_desc('id')
                ->find_array();
            
            // Logger la consultation de la liste des particuliers
            $this->activityLogger->logView(
                $_SESSION['username'] ?? null,
                'particuliers_list',
                "Consultation de la liste des particuliers (" . count($rows) . " résultats)"
            );
            
            return is_array($rows) ? $rows : [];
        } catch (Exception $e) {
            error_log('ParticulierController::listAll error: '.$e->getMessage());
            return [];
        }
    }

    /**
     * Rechercher des particuliers par nom (utilisé par le transfert de véhicule)
     * @param string $q
     * @param int $limit
     * @return array{id:int,nom:string,numero_national?:string,gsm?:string}[]
     */
    public function searchByName(string $q = '', int $limit = 20): array
    {
        try {
            $this->getConnexion();
            $q = trim($q);
            $orm = ORM::for_table('particuliers')
                ->select_many('id','nom','numero_national','gsm')
                ->order_by_asc('nom');
            if ($q !== '') {
                $orm = $orm->where_like('nom', '%'.$q.'%');
            }
            if ($limit > 0) { $orm = $orm->limit($limit); }
            $rows = $orm->find_array();
            return is_array($rows) ? $rows : [];
        } catch (Exception $e) {
            error_log('ParticulierController::searchByName error: '.$e->getMessage());
            return [];
        }
    }
}

<?php

class Ami extends AppModel {

    public function getAmitiesActivees($parieur_id){

        $conditions = array(
            'fields' => array(
                'Parieur.pseudo',
                'Parieur.avatar',
                'Parieur.id'
            ),
            'conditions' => array(
                "OR" => array(
                    "destinateur_id" => $parieur_id,
                    "destinataire_id" => $parieur_id),
                'amitie_acceptee' => 1),
            'joins' => array(
                array(
                    'table' => 'parieurs',
                    'alias' => 'Parieur',
                    'type' => 'inner',
                    'conditions'=> array('Parieur.id = ((CASE WHEN destinateur_id='.$parieur_id.' THEN destinataire_id ELSE destinateur_id END))'))),);
        return $this->find('all', $conditions);
    }

    public function getNombreAmitiesActivees($parieur_id){
        $conditions = array(
            'conditions' => array(
                "OR" => array(
                    "destinateur_id" => $parieur_id,
                    "destinataire_id" => $parieur_id),
                'amitie_acceptee' => 0),
            'joins' => array(
                array(
                    'table' => 'parieurs',
                    'alias' => 'Parieur',
                    'type' => 'inner',
                    'conditions'=> array('Parieur.id = ((CASE WHEN destinateur_id='.$parieur_id.' THEN destinataire_id ELSE destinateur_id END))'))),);
        return $this->find('count', $conditions);
    }

    public function amitieExiste($id_destinateur, $id_destinataire){
        return $this->find('count', array(
            'conditions' => array(
                'OR' => array(
                    array(
                        'destinataire_id' =>$id_destinateur,
                        'destinateur_id' => $id_destinataire)
                ,
                    array(
                        'destinateur_id' =>$id_destinateur,
                        'destinataire_id' => $id_destinataire
                    ))
            ))) == 1;
    }

    public function getDemandesAmitieRecues($id_destinataire){
        return $this->find('all', array(
            'fields' => array(
                'Parieur.pseudo',
                'Parieur.avatar',
                'Parieur.id',
                'Ami.id'
            ),
            'joins' => array(
                array(
                    'table' => 'parieurs',
                    'alias' => 'Parieur',
                    'type' => 'inner',
                    'conditions'=> array('Parieur.id = destinateur_id'))),
            'conditions' => array(
                'destinataire_id' => $id_destinataire,
                'amitie_acceptee' => false
            )));
    }

    public function getNombreDemandesAmitieRecues($id_destinataire){
        return $this->find('count', array(
            'conditions' => array(
                'destinataire_id' => $id_destinataire,
                'amitie_acceptee' => false
            )));
    }

    public function getDemandesAmitieEnvoyees($id_destinateur){
        return $this->find('all', array(
            'fields' => array(
                'Parieur.pseudo',
                'Parieur.avatar',
                'Parieur.id'
            ),
            'joins' => array(
                array(
                    'table' => 'parieurs',
                    'alias' => 'Parieur',
                    'type' => 'inner',
                    'conditions'=> array('Parieur.id = destinataire_id'))),
            'conditions' => array(
                'destinateur_id' => $id_destinateur,
                'amitie_acceptee' => false
            )));
    }
}

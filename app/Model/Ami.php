<?php

class Ami extends AppModel {

    public function getAmitiesActivees($parieur_id){

        $conditions = array(
            'fields' => array(
                'Parieur.pseudo',
                '((CASE WHEN destinateur_id='.$parieur_id.' THEN destinataire_id ELSE destinateur_id END)) AS ami_id'),
            'conditions' => array(
            "OR" => array(
                "destinateur_id" => $parieur_id,
                "destinataire_id" => $parieur_id),
            'amitie_acceptee' => 1));
        return $this->find('all', $conditions);
    }

    public function getAmitiesNonActivees($parieur_id){

        $conditions = array(
            'fields' => array(
                '((CASE WHEN destinateur_id='.$parieur_id.' THEN destinataire_id ELSE destinateur_id END)) AS ami_id'),
            'conditions' => array(
                "OR" => array(
                    "destinateur_id" => $parieur_id,
                    "destinataire_id" => $parieur_id),
                'amitie_acceptee' => 0));
        return $this->find('all', $conditions);
    }
}

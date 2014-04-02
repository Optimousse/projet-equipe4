<?php

class Ami extends AppModel {

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
}

<?php

/**
 * Class AmisController
 */
class AmisController extends AppController
{
    //Faire une demande d'amitié à un utilisateur
    public function ajouter($id){
        $this->autoRender = false;
        $this->layout = 'ajax';

        $id_destinataire = $id;
        $this->Ami->create();
        $data = array();
        array_push($data, array('Amitie' => $data));
        $data['destinateur_id'] = $this->Auth->user('id');
        $data['destinataire_id'] = $id_destinataire;
        $data['amitie_acceptee'] = false;

        $this->loadModel('Parieur');
        if(!$this->Parieur->usagerExiste($id_destinataire)){
            return json_encode(array('error_code' => -1));
        }
        else if($this->Ami->amitieExiste(AuthComponent::user('id'), $id_destinataire)){
            return json_encode(array('error_code' => -2));
        }

        if($this->Ami->save($data)){
            return json_encode(array('error_code' => 0));
        }
        else{
            return json_encode(array('error_code' => -3));
        }

    }
}

<?php

/**
 * Class AmisController
 */
class AmisController extends AppController
{
    //Faire une demande d'amitié à un utilisateur
    public function ajouter($id_destinataire){
        $this->autoRender = false;
        $this->layout = 'ajax';

        $this->Ami->create();
        $data = array();
        array_push($data, array('Amitie' => $data));
        $data['destinateur_id'] = $this->Auth->user('id');
        $data['destinataire_id'] = $id_destinataire;
        $data['amitie_acceptee'] = false;

        $this->loadModel('Parieur');
        if(!$this->Parieur->usagerExiste($id_destinataire)){
            return 0;
        }
        else if($this->Ami->amitieExiste(AuthComponent::user('id'), $id_destinataire)){
            return 0;
        }

        if($this->Ami->save($data)){
            return 1;
        }
        else{
            return 0;
        }

    }
}

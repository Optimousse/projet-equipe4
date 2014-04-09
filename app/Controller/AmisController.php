<?php

/**
 * Class AmisController
 */
class AmisController extends AppController
{
    //Faire une demande d'amitié à un utilisateur
    public function ajouter($id){
        if(!$id){
            return json_encode(array('error_code' => -4));
        }
        else if($id == AuthComponent::user('id')){
            return json_encode(array('error_code' => -5));
        }

        $id_destinataire = $id;

        $this->loadModel('Parieur');
        if(!$this->Parieur->usagerExiste($id_destinataire)){
            return json_encode(array('error_code' => -1));
        }
        else if($this->Ami->amitieExiste(AuthComponent::user('id'), $id_destinataire)){
            return json_encode(array('error_code' => -2));
        }

        $this->autoRender = false;
        $this->layout = 'ajax';

        $this->Ami->create();
        $data = array();
        array_push($data, array('Amitie' => $data));
        $data['destinateur_id'] = $this->Auth->user('id');
        $data['destinataire_id'] = $id_destinataire;
        $data['amitie_acceptee'] = false;

        if($this->Ami->save($data)){
            return json_encode(array('error_code' => 0));
        }
        else{
            return json_encode(array('error_code' => -3));
        }
    }

    public function consulter($id){

        if(!$id){
            return $this->_redirectAccueil();
        }

        $amis = $this->Ami->getAmitiesActivees($id);
        $this->set('amis', $amis);

        $this->loadModel('Parieur');
        $parieur = $this->Parieur->find('first', array('conditions'=>array('Parieur.id' => $id)));
        $this->set('parieur', $parieur);

        if($id == AuthComponent::user('id')){
            $demandesEnvoyees = $this->Ami->getDemandesAmitieEnvoyees(AuthComponent::user('id'));
            $this->set('demandesEnvoyees', $demandesEnvoyees);

            $demandesReçues = $this->Ami->getDemandesAmitieRecues(AuthComponent::user('id'));

            $this->set('demandesRecues', $demandesReçues);
        }
    }

    public function accepterDemandeAmitie($id){

        $this->autoRender = false;
        $this->layout = 'ajax';

        $this->Ami->id = $id;

        if($this->Ami->saveField('amitie_acceptee', 1)){
            return json_encode(array('error_code' => 0));
        }
        else{
            return json_encode(array('error_code' => -1));
        }
    }


    public function refuserDemandeAmitie($id){

        $this->autoRender = false;
        $this->layout = 'ajax';

        if($this->Ami->delete($id)){
            return json_encode(array('error_code' => 0));
        }
        else{
            return json_encode(array('error_code' => -1));
        }
    }
}

<?php

class MessagesController extends AppController
{
    public $components = array('RequestHandler');

    //Ajouter un message dans la bd
    public function ajouter(){

        if($this->request->is('Post')){
            $this->autoRender = false;
            $this->layout = 'ajax';

            $this->Message->create();
            $this->Message->save($this->request->data);
        }
    }

    //Obtenir les derniers messages de la bd
    public function getMessages(){

        $this->autoRender = false;
        $this->layout = 'ajax';

        //Si on ne vient pas tout juste de charger la page,
        //on va chercher l'ID du dernier message récupéré
        //pour aller lire les nouveaux (plus récents que cet id)
        if($this->request->data['estPageLoad'] == 'true'){
            $idRef = $this->getDernierMessageDataBase();
        }
        else{
            $idRef = $this->getDernierMessageRecupere();
        }

        $messages = $this->Message->find('all', array(
            'conditions' => array('Message.id > ' . $idRef),
            'joins' => array(
                            array(
                                'table' => 'parieurs',
                                'alias' => 'parieurs',
                                'type' => 'inner',
                                'conditions'=> array('parieurs.id = Message.parieur_id'))),
            'fields' => array('parieurs.pseudo', 'Message.id', 'message', 'created'), 'order' => 'Message.id DESC'));

        $nbNouveauxMessages = count($messages);
        if($nbNouveauxMessages > 0){

            if($this->request->data['estPageLoad'] == 'false' && $this->aLuMinimumUnMessage()){
                $this->setNombreMessagesNonLus($this->getNombreMessagesNonLus() + $nbNouveauxMessages);
            }
            array_push($messages, array('nbMessagesNonLus' => $this->getNombreMessagesNonLus()));
            $this->setDernierMessageRecupere($messages[0]['Message']['id']);
        }

        return json_encode(array_reverse($messages));
    }

    //Pour dire que tous les messages ont été lus
    public function setTousMessagesLus(){

        $this->autoRender = false;
        $this->layout = 'ajax';

        $idDernierEnvoye = -1;
        if($this->Session->check('dernierIdMessageRecupere'))
            $idDernierEnvoye = $this->Session->read('dernierIdMessageRecupere');
        $this->setNombreMessagesNonLus(0);
    }

    private function getDernierMessageRecupere(){
        $id = -1;
        if($this->Session->check('dernierIdMessageRecupere'))
            $id = $this->Session->read('dernierIdMessageRecupere');
        return $id;
    }

    //Mettre une nouvelle valeur pour le dernier message qui a été lu
    private function setDernierMessageRecupere($id){

        $this->Session->write('dernierIdMessageRecupere', $id);
    }

    //Aller chercher le 15e ID inséré à partir de la fin (pour afficher l'historique de la conversation)
    private function getDernierMessageDataBase(){

        $dernierId = $this->Message->find('first', array('fields' => array('Message.id'), 'order' => 'Message.id DESC', 'offset' => 15));

        if(count($dernierId) > 0){
            return $dernierId['Message']['id'];
        }
        return -1;
    }

    private function getNombreMessagesNonLus(){
        $nb = -1;
        if($this->Session->check('nombreMessagesNonLus'))
            $nb = $this->Session->read('nombreMessagesNonLus');
        return $nb;
    }

    private function setNombreMessagesNonLus($nb){

        if(isset($this->request->data['estAjout']) && $this->request->data['estAjout'] == 'true'){
            $nb--;
        }
        $this->Session->write('nombreMessagesNonLus', $nb);
    }

    private function aLuMinimumUnMessage(){
        return $this->getNombreMessagesNonLus() > -1;
    }
}
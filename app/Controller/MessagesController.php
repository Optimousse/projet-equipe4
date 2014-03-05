<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class MessagesController extends AppController
{
    public $helpers = array('Html', 'Form');
    public $components = array('Paginator', 'RequestHandler');


    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('ajouter');
    }

    //Ajouter un message dans la bd
    public function ajouter(){

        if($this->request->is('Post')){
            $this->autoRender = false;
            $this->layout = 'ajax';

            $this->Message->create();
            $this->Message->save($this->request->data);

            $this->setDernierMessageLu($this->Message->getInsertID());
        }
    }

    //Obtenir les 10 derniers messages de la bd
    public function getMessages(){

        $this->autoRender = false;
        $this->layout = 'ajax';
                    
        $messages = $this->Message->find('all', array( 'joins' => array(
            array(
                'table' => 'parieurs',
                'alias' => 'parieurs',
                'type' => 'inner',
                'conditions'=> array('parieurs.id = Message.parieur_id'))),
            'fields' => array('parieurs.pseudo', 'Message.id', 'message'), 'limit' => 10, 'order' => array('Message.id DESC'), 'page' => 1));

        $this->setDernierMessageEnvoye($messages[0]['Message']['id']);

        array_push($messages, array("nouveauMessage" => $this->NouveauMessage()));

        return json_encode(array_reverse($messages));
    }

    //Appelé par ajax pour dire que tous les messages ont été lus
    public function setTousMessagesLus(){

        $this->autoRender = false;
        $this->layout = 'ajax';

        $idDernierEnvoye = -1;
        if($this->Session->check('dernierIdMessageEnvoye'))
            $idDernierEnvoye = $this->Session->read('dernierIdMessageEnvoye');
        $this->setDernierMessageLu($idDernierEnvoye);
    }

    private function getDernierMessageEnvoye(){
        $id = -1;
        if($this->Session->check('dernierIdMessageEnvoye'))
            $id = $this->Session->read('dernierIdMessageEnvoye');
        return $id;
    }

    //Mettre une nouvelle valeur pour le dernier message qui a été lu
    private function setDernierMessageEnvoye($id){

        $this->Session->write('dernierIdMessageEnvoye', $id);
    }

    private function getDernierMessageLu(){
        $id = -1;
        if($this->Session->check('dernierIdMessageLu'))
            $id = $this->Session->read('dernierIdMessageLu');
        return $id;
    }

    private function setDernierMessageLu($valeur){
        $this->Session->write('dernierIdMessageLu', $valeur);
    }

    //Pour savoir si un nouveau message est présent et que l'utilisateur ne l'a pas lu.
    //Si $idDernierLu = -1, cela signifie qu'il n'a lu aucun message et ne veut
    //probablement pas être importuné.
    private function NouveauMessage(){

        $idDernierLu = $this->getDernierMessageLu();
        $idDernierEnvoye = $this->getDernierMessageEnvoye();

        if($idDernierEnvoye != -1 && $idDernierLu != -1){

            return $idDernierEnvoye != $idDernierLu;
        }

        return false;
    }
}
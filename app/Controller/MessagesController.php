<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class MessagesController extends AppController
{
    public $components = array('Paginator', 'RequestHandler');

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

        $estAjout = $this->request->data['estAjout'] == 'true';

        //Si on ne vient pas tout juste de charger la page,
        //on va chercher l'ID du dernier message récupéré
        //pour aller lire les nouveaux (plus récents que cet id)

        $idRef = $this->getDernierMessageRecupere();
        if($this->request->data['estPageLoad'] == 'true'){
            $idRef = $this->getDernierMessageDataBase();
        }

        $messages = $this->Message->find('all', array(
            'conditions' => array('Message.id > ' . $idRef),
            'joins' => array(
                            array(
                                'table' => 'parieurs',
                                'alias' => 'parieurs',
                                'type' => 'inner',
                                'conditions'=> array('parieurs.id = Message.parieur_id'))),
            'fields' => array('parieurs.pseudo', 'Message.id', 'message'), 'order' => 'Message.id DESC'));

        if(count($messages) > 0)
            $this->setDernierMessageRecupere($messages[0]['Message']['id']);
        if($estAjout){
            $this->setDernierMessageLu($this->Message->getInsertID());
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
        $this->setDernierMessageLu($idDernierEnvoye);
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

    private function setDernierMessageLu($valeur){
        $this->Session->write('dernierIdMessageLu', $valeur);
    }

    //Aller chercher le 15e ID inséré à partir de la fin (pour afficher l'historique de la conversation)
    private function getDernierMessageDataBase(){

        $dernierId = $this->Message->find('first', array('fields' => array('Message.id'), 'order' => 'Message.id DESC', 'offset' => 15));

        if(count($dernierId) > 0){
            return $dernierId['Message']['id'];
        }
        return -1;
    }
}
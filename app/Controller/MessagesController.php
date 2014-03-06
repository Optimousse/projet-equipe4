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

    //Ajouter un message
    public function ajouter()
    {
        if($this->request->is('Post')){
            $this->autoRender = false;
            $this->layout = 'ajax';

            $this->Message->create();
            $this->Message->save($this->request->data);
        }
    }

    public function getMessages(){

        $this->autoRender = false;
        $this->layout = 'ajax';
                    
        $messages = $this->Message->find('all', array( 'joins' => array(
            array(
                'table' => 'parieurs',
                'alias' => 'parieurs',
                'type' => 'inner',
                'conditions'=> array('parieurs.id = Message.parieur_id'))),
            'fields' => array('parieurs.pseudo', 'message'), 'limit' => 10, 'order' => array('Message.id DESC'), 'page' => 1));

        return json_encode(array_reverse($messages));
    }
}
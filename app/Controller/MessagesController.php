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
    public $components = array('Paginator');


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
            //Cette ligne = quoi ?
            $this->layout = 'ajax';

            $this->Message->create();
            $this->Message->save($this->request->data);


            $this->set('listeMessages', $this->Message->find('list', array('fields' => array('message'))));
        }
    }
}
<?php
class DiversController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('faq', 'accueil');
    }

    //Page d'accueil
    public function accueil(){
        $this->set('title_for_layout', 'Paris, pas la ville');
        $this->loadModel('Pari');
        $paris = $this->Pari->find('all', array('limit' => 5, 'order' => 'id DESC', 'fields' => array('nom', 'image', 'description', 'date_fin')));
        $this->set('paris', $paris);

        $this->loadModel('Lot');
        $lot = $this->Lot->find('first', array('conditions' => array('id' => '1'), 'fields'=> 'image'));
        $this->set('lot', $lot);
    }

    //Page Foire aux questions
    public function faq(){
        $this->set('title_for_layout', 'Foire aux questions');

    }
}

<?php

class LotsController extends AppController
{
    public $helpers = array('Html', 'Form');
    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 9,
        'order' => array(
            'Lot.nom' => 'asc',
            'Lot.prix' => 'asc'
        )
    );

    //Vue index: affiche tous les paris
    public function index()
    {
        $this->set('title_for_layout', 'Lots');
        $this->Paginator->settings = $this->paginate;

        $data = $this->Paginator->paginate('Lot');
        $this->set('lots', $data);
    }
}

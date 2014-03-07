<?php
/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 14-03-06
 * Time: 16:53
 */
class LotsController extends AppController
{


    public $helpers = array('Html', 'Form');
    public $components = array(
        'RequestHandler', 'Paginator');

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
        $this->Paginator->settings = $this->paginate;

        $data = $this->Paginator->paginate('Lot');
        $this->set('lots', $data);
    }


}

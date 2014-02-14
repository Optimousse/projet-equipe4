<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class ParisController extends AppController {
    public $helpers = array('Html', 'Form');
    //Pages accessibles lorsque le parieur n'est pas connecté
    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to register and logout.
        $this->Auth->allow('index');
    }

    //Vue index: affiche tous les paris
    public function index()
    {
        $this->set('paris', $this->Pari->find('all'));
    }

    //Permet d'ajouter un pari au site
    public function ajouter() {
        $this->set('id_util', $this->Auth->user('id'));
        $this->loadModel('Choix');
        if ($this->request->is('post')) {

            $this->Pari->create();
            $this->Choix->create();

            //Si l'utilisateur n'a rentré que deux choix, on supprime le troisième pour ne pas l'enregistrer.
            $choix3 = $this->request->data['Choix']['2'];
            if(empty($choix3) || empty($choix3['nom'])){
                unset($this->request->data['Choix']['2']);
            }
            else if(empty($choix3['cote']))
            {
                $this->Session->setFlash(
                    __('La cote est obligatoire si vous ajoutez un troisième choix.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                return;
            }

            if ($this->Pari->saveAll($this->request->data)) {
                $this->Session->setFlash(__('Le pari a été créé avec succès.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'miser', 'controller'=>'ParieursParis', $this->Pari->id));
            }
            $this->Session->setFlash(
                __('Une erreur est survenue lors de la sauvegarde du pari. Veuillez réessayer.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
        }
    }

    //Affiche les paris créés par l'utilisateur
    public function mes_paris(){

        $this->set('paris', $this->Pari->find('all', array('conditions' => array('Pari.parieur_id' => $this->Auth->user('id')))));
    }
}
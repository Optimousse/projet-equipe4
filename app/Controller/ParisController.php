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

        if ($this->request->is('post')) {

            $this->Pari->create();

            //Si l'utilisateur n'a rentré que deux choix, on supprime le troisième pour ne pas l'enregistrer.
            $choix3 = $this->request->data['Choix']['2']['nom'];
            if(empty($choix3)){
                unset($this->request->data['Choix']['2']);
            }

            if ($this->Pari->saveAll($this->request->data)) {
                $this->Session->setFlash(__('Le pari a été créé avec succès.', 'default', array('class' => 'alert alert-success')));
                return $this->redirect(array('action' => 'consulter', $this->Pari->id));
            }
            $this->Session->setFlash(
                __('Une erreur est survenue lors de la sauvegarde du pari. Veuillez réessayer.')
            );
        }
    }

    // Permet de consulter un paris
    public  function consulter($id = null) {

        if (!$id) {
            return $this->redirect(array('action' => 'index'));
        }

        $pari = $this->Pari->findById($id);
        if (!$pari) {
            return $this->redirect(array('action' => 'index'));
        }

        $this -> set('paris', $pari);
    }
}
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
        if ($this->request->is('post')) {
            $this->Pari->create();
            $this->Pari->set('parieur_id', $this->Auth->user('id'));
//allo
            //TODO poursuivre la sauvegarde des choix. Ne doit pas sauvegarder ceux qui sont vides.
            //essayer de construire une variable $data en y ajoutant les choix sils ne sont pas vides :)
            if ($this->Pari->saveAll($this->request->data)) {
                $this->Session->setFlash(__('Le pari a été créé avec succès.'));
                return $this->redirect(array('action' => 'index'));
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
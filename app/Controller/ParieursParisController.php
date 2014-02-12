<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class ParieursParisController extends AppController {
    public $helpers = array('Html', 'Form');
    public $uses = array(
        'ParieursPari',
        'Pari'
    );

    //Pages accessibles lorsque le parieur n'est pas connecté
    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to register and logout.
        $this->Auth->allow('index');
    }

    //Vue index: affiche toutes les mises du parieur
    public function index()
    {
        $this->set('paris', $this->Pari->find('all'));
    }

    // Permet de miser sur un paris
    public function miser($id = null) {
        $this->set('id_util', $this->Auth->user('id'));
        if (!$id) {
            return $this->redirect(array('action' => 'index'));
        }
        $pari = $this->Pari->findById($id);

        if (!$pari) {
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->ParieursPari->create();
            if ($this->ParieursPari->save($this->request->data)) {
                $this->Session->setFlash(__('La mise a bien été créée.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Une erreur est survenue lors de la création de la mise. Veuillez réessayer.'));
        }

        $this -> set('pari', $pari);
    }
}
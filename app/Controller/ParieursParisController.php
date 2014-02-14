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

    public function beforeFilter() {
        parent::beforeFilter();
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
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        }
        $pari = $this->Pari->findById($id);

        if (!$pari) {
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->ParieursPari->create();
            if ($this->ParieursPari->save($this->request->data)) {
                $this->Session->setFlash(__('La mise a bien été créée.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'mes_paris', 'controller' => 'paris'));
            }
            $this->Session->setFlash(__('Une erreur est survenue lors de la création de la mise. Veuillez réessayer.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }

        $this->loadModel('Choix');
        $choix = $this->Choix->find('all', array('conditions' => array('Choix.pari_id' => $id)));

        $options = $this->Choix->find('list', array('conditions' => array('Choix.pari_id' => $id), 'fields'=> array('id', 'nom')));

        $this -> set('options', $options);
        $this -> set('paris', $pari);
        $this -> set('choix', $choix);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class ParisController extends AppController {
    public $helpers = array('Html', 'Form');
    public function beforeFilter() {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
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
            if(empty($choix3['cote']) && empty($choix3['nom'])){
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
            else if(empty($choix3['nom']))
            {
                $this->Session->setFlash(
                    __('Le nom est obligatoire si vous ajoutez un troisième choix.'), 'alert', array(
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

    //Permet au créateur d'un pari de déterminer le choix gagnant d'un pari (Seulement lorsque ce pari est terminé)
    public function determiner_gagnant($id = null){

        if(!$id)
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        $pari = $this->Pari->findById($id);

        if(!$pari)
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        //Si tu n'es pas le créateur de ce pari
        if($pari['Pari']['parieur_id'] != $this->Auth->user('id'))
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        //Si le pari n'est pas encore terminé
        if($pari['Pari']['date_fin'] > date("Y-m-d"))
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        //Si un gagnant a déjà été choisi
        if(isset($pari['Pari']['choix_gagnant']))
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));

        $this->loadModel('Choix');
        $this->loadModel('ParieursPari');
        $this->loadModel('Parieur');

        //Pour créer le groupe de radiobuttons qui montrent les choix disponibles pour le pari
        $options = $this->Choix->find('list', array('conditions' => array('Choix.pari_id' => $id), 'fields'=> array('id', 'nom')));

        $this -> set('options', $options);
        $this -> set('paris', $pari);

        if ($this->request->is(array('post', 'put'))) {

            $this->Pari->id = $id;

            if ($this->Pari->save($this->request->data)) {

                //L'ID du choix gagnant qui vient d'être sélectionné
                $id_ChoixGagnant = $this->request->data['Pari']['choix_gagnant'];
                //Le choix correspondant à celui qui vient d'être sélectionné
                $cote = $this->Choix->findById($id_ChoixGagnant);
                //La cote du choix en question
                $coteChoix = $cote['Choix']['cote'];

                $misesGagnantes = $this->ParieursPari->find('all', array('conditions'=> array('pari_id' => $id, 'choix_id' => $id_ChoixGagnant)));

                //Met à jour le nombre de jetons pour chaque personne qui a parié.
                foreach($misesGagnantes as $item) {
                    $this->Parieur->id = $item['ParieursPari']['parieur_id'];
                    $parieur = $this->Parieur->findById($this->Parieur->id);
                    $nbJetons = $parieur['Parieur']['nombre_jetons'] + $item['ParieursPari']['mise'] * $coteChoix;
                    $this->Parieur->saveField('nombre_jetons', $nbJetons);
                }
                $this->Session->setFlash(__('Le pari a été correctement fermé. Les vainqueurs ont reçu leurs jetons.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));

                return $this->redirect(array('action' => 'mon_compte', 'controller' => 'parieurs'));
            }

            $this->Session->setFlash(__('Une erreur est survenue lors de la fermeture du pari. Veuillez réessayer.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }

    public function mes_paris(){
        $this->set('paris', $this->Pari->find('all', array('conditions' => array('Pari.parieur_id' => $this->Auth->user('id')))));
    }
}
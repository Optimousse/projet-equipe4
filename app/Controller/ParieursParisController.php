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
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('miser');
    }

    //Vue index: affiche toutes les mises du parieur
    public function index()
    {//TODO faire cette page
        $this->set('paris', $this->Pari->find('all'));
    }

    // Permet de miser sur un paris
    public function miser($id = null) {
        $this->set('id_util', $this->Auth->user('id'));
        if (!$id)
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));
        $pari = $this->Pari->findById($id);

        if (!$pari)
            return $this->redirect(array('action' => 'index', 'controller'=>'paris'));

        $this->loadModel('Choix');

        //Permet d'afficher les choix disponibles pour ce pari
        $choix = $this->Choix->find('all', array('conditions' => array('Choix.pari_id' => $id)));
        //Pour créer le groupe de radiobuttons qui montrent les choix disponibles pour le pari
        $options = $this->Choix->find('list', array('conditions' => array('Choix.pari_id' => $id), 'fields'=> array('id', 'nom')));

        //Pour vérifier si la personne a déjà misé sur ce pari.
        $dejaMise = false;
        if($this->ParieursPari->find('first', array('conditions'=> array('pari_id' => $id, 'parieur_id' => $this->Auth->user('id')))))
            $dejaMise = true;

        $this -> set('options', $options);
        $this -> set('paris', $pari);
        $this -> set('choix', $choix);
        $this -> set('dejaMise', $dejaMise);

        /*
         * si le paris est terminé on affiche les résultats (le nom du choix gagnant)
         *   et le nom du choix du joueur (si il a joué)
         */

        if(isset($pari['Pari']['choix_gagnant'])) {

            // on "fait passer" dans nom_choixGagnant le nom du choix Gagnant
            $this->set('nom_choixGagnant', $this->choixGagnant($id)) ;

            $leparis = $this->ParieursPari->find('first', array(
                'conditions' => array('pari_id' => $id,'parieur_id' => $this->Auth->user('id'))));


            if(!empty($leparis)) {

                // on "fait passer" dans nom_choixParieur le nom de son choix de mise
                $this->set('nom_choixParieur', $this->choixParieur($leparis)) ;
            }
        }

        if ($this->request->is(array('post', 'put'))) {
            if(!$this->Auth->User('id'))
                return;
            //On ne peut soumettre le formulaire si le pari est déjà terminé
            if(isset($pari['Pari']['choix_gagnant']))
                return ;
            //On ne peut soumettre le formulaire si on a créé ce pari OU si on a déjà misé
            if($pari['Pari']['parieur_id'] == $this->Auth->user('id') || $dejaMise)
                return;

            $this->loadModel('Parieur');

            // on cherche le nombre de jetons de la personne grace a son id
            $parieur = $this->Parieur->find('first', array('conditions' => array('Parieur.id' => $this->Auth->user('id')), 'fields'=> array('nombre_jetons')));
            $jetonPossede = $parieur['Parieur']['nombre_jetons'];

            $mise = $this->request->data["ParieursPari"]["mise"];

            // on teste si le parieur possède assez de jetons pour parier le montant désiré
            if ($mise > $jetonPossede ) {

                $lien = Router::url(array('controller'=>'parieurs', 'action'=>'acheter_jetons'), false);
                // explication de l'erreur
                $this->Session->setFlash(__('Vous n\'avez pas assez de jetons pour parier ce montant.
                                             <a href="' .$lien .'">Vous pouvez en racheter ici.</a>'),  'alert',
                    array( 'plugin' => 'BoostCake', 'class' => 'alert-info' ));

                return;
            }

            $this->ParieursPari->create();
            if ($this->ParieursPari->save($this->request->data)) {

                $this->Session->setFlash(__('La mise a bien été créée.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'mes_mises', 'controller' => 'paris'));
            }
            $this->Session->setFlash(__('Une erreur est survenue lors de la création de la mise. Veuillez réessayer.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }

    /*
     * Récupere l'intitulé du choix gagnant
     * pour l'afficher lorsque le paris est terminé
     */
    public function choixGagnant($idParis){

        // on récupere tout ce qui concerne le paris
        $leparis = $this->Pari->find('first', array(
            'conditions' => array('Pari.id' => $idParis)));

        // on garde la ligne qui nous interesse => l'id du choix gagnant
        $id_choixGagnant = $leparis['Pari']['choix_gagnant'];

        // on récupere la ligne correspondante du choix gagnant du paris
        $intituleChoixGagnant = $this->Choix->find('first', array(
            'conditions' => array('Choix.id' => $id_choixGagnant)));

        // on extrait juste le nom
        $nom = $intituleChoixGagnant['Choix']['nom'];

        return $nom;
    }

    /*
     * Récupere l'intitulé du choix du parieur
     * pour l'afficher lorsque le paris est terminé
     */
    public function choixParieur($leparis){

        // on garde la ligne qui nous interesse => l'id du choix gagnant
        $id_choix = $leparis['ParieursPari']['choix_id'];


        // on récupere la ligne correspondante du choix du joueur
        $intituleChoix = $this->Choix->find('first', array(
            'conditions' => array('Choix.id' => $id_choix)));

        // on extrait juste le nom
        $nom = $intituleChoix['Choix']['nom'];

        return $nom;
    }
}

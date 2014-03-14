<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class ParieursParisController extends AppController
{
    public $helpers = array('Html', 'Form');
    public $uses = array(
        'ParieursPari',
        'Pari'
    );
    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 5,
        'order' => array(
            'Pari.nom' => 'asc'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('miser');
    }

    // Permet de miser sur un pari
    public function miser($id = null)
    {
        $id_usager = $this->Auth->user('id');
        $this->set('id_util', $id_usager);
        if (!$id)
            return $this->redirectAccueil();

        $pari = $this->Pari->findById($id);
        if (!$pari)
            return $this->redirectAccueil();

        $this->set('title_for_layout', $pari['Pari']['nom']);
        $this->loadModel('Choix');

        //Permet d'afficher les choix disponibles pour ce pari
        $choix = $this->Choix->find('all', array('conditions' => array('Choix.pari_id' => $id)));
        //Pour créer le groupe de radiobuttons qui montrent les choix disponibles pour le pari
        $options = $this->Choix->find('list', array('conditions' => array('Choix.pari_id' => $id), 'fields' => array('id', 'nom')));

        // permet de récupérer le pseudo du créateur du paris
        $this->loadModel('Parieur');
        $this->loadModel('Pari');
        $CreateurParis = $this->Pari->find('first', array('conditions' => array('Pari.id' => $id)));
        $idCreateur = $CreateurParis['Pari']['parieur_id'];

        $lecreateur = $this->Parieur->find('first', array('conditions' => array('Parieur.id' => $idCreateur), 'fields' => array('pseudo')));
        $pseudo = $lecreateur['Parieur']['pseudo'];
        $this->set('pseudo',$pseudo);

        //Pour vérifier si la personne a déjà misé sur ce pari.
        $dejaMise = false;
        if ($this->ParieursPari->find('first', array('conditions' => array('ParieursPari.pari_id' => $id, 'ParieursPari.parieur_id' => $this->Auth->user('id')))))
            $dejaMise = true;

        $this->set('options', $options);
        $this->set('paris', $pari);
        $this->set('choix', $choix);
        $this->set('dejaMise', $dejaMise);

        if (isset($pari['Pari']['choix_gagnant'])) {
            $this->affichageChoixGagnant($id);
        }

        if ($this->request->is(array('post', 'put'))) {

            if(!$this->miseValide($dejaMise, $pari))
                return;

            $this->loadModel('Parieur');

            // on cherche le nombre de jetons de la personne grace a son id
            $parieur = $this->Parieur->find('first', array('conditions' => array('Parieur.id' => $id_usager), 'fields' => array('nombre_jetons')));
            $jetonPossede = $parieur['Parieur']['nombre_jetons'];

            $mise = $this->request->data["ParieursPari"]["mise"];

            // on teste si le parieur possède assez de jetons pour parier le montant désiré
            if ($mise > $jetonPossede) {

                $lien = Router::url(array('controller' => 'parieurs', 'action' => 'acheter_jetons'), false);
                // explication de l'erreur
                $this->messageAvertissement('Vous n\'avez pas assez de jetons pour parier ce montant.
                                                 <a href="' . $lien . '">Vous pouvez en racheter ici.</a>');
                return;
            }

            $this->ParieursPari->create();

            if ($this->sauvegarderNouveauxJetons($id_usager, $mise) && $this->ParieursPari->save($this->request->data)) {

                $this->messageSucces('La mise a bien été créée. Votre compte a été débité de '.$mise .' jetons.');
                return $this->redirect(array('action' => 'mes_mises', 'controller' => 'parieurs_paris'));
            }
            $this->messageErreur('Un ou plusieurs champs n\'ont pas été remplis correctement.');
        }
    }

    //Affiche les mises de l'usager
    public function mes_mises()
    {
        $this->set('title_for_layout', 'Mes mises');
        $this->Paginator->settings = array(
            'conditions' => array('ParieursPari.parieur_id' => $this->Auth->user('id')),
            'limit' => 5
        );

        $data = $this->Paginator->paginate('ParieursPari');
        $this->set('mises', $data);
    }

    /*
     * Fonctions privées
     */

    //Récupere l'intitulé du choix gagnant
    private function choixGagnant($idParis)
    {
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

    //Récupere l'intitulé du choix du parieur pour l'afficher lorsque le paris est terminé
    private function choixParieur($leparis)
    {
        // on garde la ligne qui nous interesse => l'id du choix gagnant
        $id_choix = $leparis['ParieursPari']['choix_id'];

        // on récupere la ligne correspondante du choix du joueur
        $intituleChoix = $this->Choix->find('first', array(
            'conditions' => array('Choix.id' => $id_choix)));

        // on extrait juste le nom
        $nom = $intituleChoix['Choix']['nom'];

        return $nom;
    }

    private function sauvegarderNouveauxJetons($id_usager, $mise){

        $this->Parieur->id = $id_usager;
        $parieur = $this->Parieur->findById($id_usager);
        $nbJetons = $parieur['Parieur']['nombre_jetons'] - $mise;

        return $this->Parieur->saveField('nombre_jetons', $nbJetons);
    }

    //valide que la personne peut miser
    private function miseValide($dejaMise, $pari){
        $valide = true;
        if (!$this->Auth->User('id'))
            $valide = false;
        else if (isset($pari['Pari']['choix_gagnant']))//On ne peut soumettre le formulaire si le pari est déjà terminé
            $valide = false;
        if ($pari['Pari']['parieur_id'] == $this->Auth->User('id') || $dejaMise)
            $valide = false;//On ne peut soumettre le formulaire si on a créé ce pari OU si on a déjà misé

        return $valide;
    }

    //Affiche le nom du choix gagnant d'un pari et le nom du choix du joueur (si il a joué)
    private function affichageChoixGagnant($id){

        // on "fait passer" dans nom_choixGagnant le nom du choix Gagnant
        $this->set('nom_choixGagnant', $this->choixGagnant($id));

        $leparis = $this->ParieursPari->find('first', array(
            'conditions' => array('ParieursPari.pari_id' => $id, 'ParieursPari.parieur_id' => $this->Auth->User('id'))));

        if (!empty($leparis)) {

            // on "fait passer" dans nom_choixParieur le nom de son choix de mise
            $this->set('nom_choixParieur', $this->choixParieur($leparis));
        }
    }
}

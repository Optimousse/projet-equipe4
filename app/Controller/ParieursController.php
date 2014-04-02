<?php

App::uses('FB', 'Facebook.Lib');
class ParieursController extends AppController
{
    public $components = array(
        'Stripe.Stripe', 'Paginator'
    );

    public $paginate = array(
        'limit' => 12,
        'order' => array(
            'Parieur.pseudo' => 'asc'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        //Permet d'accéder à ces pages sans être connecté.
        $this->Auth->allow('inscription', 'logout', 'connexion', 'rechercher', 'consulter');
    }

    //Connexion au site
    public function connexion()
    {
        if (!$this->Auth->user("id")) {
            $this->set('title_for_layout', 'Connexion');
            if ($this->request->is('post')) {
                if ($this->Auth->login()) {
                    $this->Session->write('connexionNormale', true);
                    $this->_messageSucces('Vous êtes maintenant connecté.');
                    return $this->redirect($this->Auth->redirect());
                }
                $this->_messageErreur('Pseudo ou mot de passe invalide.');
            }
        } else {
            return $this->redirect($this->_redirectAccueil());
        }
    }

    //Déconnexion (Prend en charge facebook et connexion normale)
    public function logout()
    {
        if(!$this->Session->check('connexionNormale')){
            $fb = new FB();
            $fb->api('/me');
            $fb->destroySession();
        }
        $this->_messageInfo('Vous êtes maintenant déconnecté.');
        $this->Session->delete('dernierIdMessageRecupere');
        $this->Session->delete('dernierIdMessageLu');
        $this->Session->destroy();
        $this->Auth->logout();

        return $this->_redirectAccueil();
    }

    //Inscription au site
    public function inscription()
    {
        if (!$this->Auth->user("id")) {
            $this->set('title_for_layout', 'Inscription');

            $this->setSexe();

            if ($this->request->is(array('post', 'put'))) {

                if ($this->MotsPasseIdentiques($this->request->data['Parieur']['mot_passe'], $this->request->data['Parieur']['mot_passe_confirmation'])) {

                    $this->Parieur->create();
                    if ($this->Parieur->save($this->request->data, true, array('pseudo', 'sexe_id', 'mot_passe', 'courriel', 'avatar'))) {

                        $this->_messageSucces('Votre compte a été créé avec succès. Vous avez maintenant accès à toutes les fonctionnalités du site.');
                        $this->Auth->login();
                        $this->Session->write('connexionNormale', true);
                        return $this->redirect(array('controller' => 'paris', 'action' => 'index'));
                    } else {
                        $this->_messageErreur('Une erreur est survenue lors de la création de votre compte.');
                    }
                } else
                    $this->_messageErreur('Les mots de passe doivent être identiques.');
            }
        } else {
            $this->_messageAvertissement('Vous êtes déja connecté');
            return $this->redirect($this->_redirectAccueil());
        }
    }

    //Affiche la page "Mon compte"
    public function mon_compte()
    {
        //On ne peut accéder à cette page si on est connecté via Facebook
        if(!$this->Session->check('connexionNormale')){
            return $this->_redirectAccueil();
        }
        $this->set('title_for_layout', 'Mon compte');

        if ($this->request->is(array('post', 'put'))) {

            // on récupere le mot de passe et l'id propre au parieur
            $mot_passe = $this->request->data['Parieur']['mot_passe'];
            $id = $this->request->data['Parieur']['id'];
            $this->Parieur->id = $id;

            // vérification que le nouveau mdp soit égal a la confirmation
            if ($this->MotsPasseIdentiques($mot_passe, $this->request->data['Parieur']['mot_passe_confirmation'])) {

                $parieur = $this->Parieur->find('first', array(
                        'fields' => array('avatar'),
                        'conditions' => array("Parieur.id" => $this->Auth->user('id')))
                );

                if(isset($this->request->data['avatar']) && $this->request->data['avatar']['delete'] == '1'){

                    unlink(WWW_ROOT.'\img\avatars\\'.$parieur['Parieur']['avatar']);
                    $this->setDefaultAvatar();
                }
                else if($this->request->data['Parieur']['avatar']['error'] == '4'){
                    //La personne n'a pas choisi de nouvelle image, mais on ne veut pas que le modèle
                    //pense qu'il doit changer l'image pour feminin.png ou masculin.png
                    $this->request->data['Parieur']['reinitialiserAvatar'] = false;
                }

                //On ne doit pas supprimer les images 'masculin.png' et 'feminin.png' si c'est celle utilisée par l'utilisateur
                if(!$this->usagerPossedeAvatarPersonnalise($parieur['Parieur']['avatar'])){
                    $this->request->data['Parieur']['doitSupprimer'] = false;
                }
                $champs_a_sauvegarder = array('courriel', 'avatar', 'sexe_id');
                if(!empty($this->request->data['Parieur']['mot_passe'])){
                    array_push($champs_a_sauvegarder, 'mot_passe');
                }

                if ($this->Parieur->save($this->request->data, true, $champs_a_sauvegarder)) {
                    $this->_messageSucces('Votre compte a bien été modifié.');
                }else{
                    $this->_messageErreur('Une erreur est survenue lors de la sauvegarde de vos informations.');
                }
            } else {
                // problème mdp différent
                $this->_messageErreur('Les mots de passe doivent être identiques.');
            }
        }

        //Afin que les champs soient déjà remplis
        $parieur = $this->Parieur->find('first', array(
                'fields' => array('pseudo', 'courriel', 'id', 'nombre_jetons', 'avatar', 'sexe_id', 'created'),
                'conditions' => array("Parieur.id" => $this->Auth->user('id')))
        );
        if (!$this->request->data) {
            $this->request->data = $parieur;
        }

        if($this->usagerPossedeAvatarPersonnalise($parieur['Parieur']['avatar'])){
            $this->set('avatar', $parieur['Parieur']['avatar']);
        }
        $this->setSexe();

    }

    //Fonction pour acheter des jetons avec le plugin Stripe
    //Param: $ref = page qui nous a appelé
    public function acheter_jetons($ref = null)
    {
        $this->set('title_for_layout', 'Acheter des jetons');
        $parieur = $this->Parieur->findById($this->Auth->user('id'));
        $this->set('nombre_jetons', $parieur['Parieur']['nombre_jetons']);

        if ($this->request->is('post')) {

            $nombre_jetons_achetes = $this->request->data['Parieur']['nombre_jetons'];
            if (!is_numeric($nombre_jetons_achetes))
                return;

            $token = $this->request->data['stripeToken'];
            $data = array(
                'amount' => $nombre_jetons_achetes * 5, // Les jetons coûtent 5$
                'stripeToken' => $token,
            );

            $result = $this->Stripe->charge($data);

            //Si c'est un array, la création de la charge a fonctionné.
            if (is_array($result)) {

                $this->Parieur->id = $this->Auth->user('id');
                $parieur = $this->Parieur->findById($this->Parieur->id);
                $nbJetons = $parieur['Parieur']['nombre_jetons'] + $nombre_jetons_achetes;
                $this->Parieur->set(array('nombre_jetons' => $nbJetons));

                if ($this->Parieur->save()) {

                    $this->_messageSucces($nombre_jetons_achetes . ' jetons ont été ajoutés à votre compte.');

                    if ($ref == 'lots')
                        return $this->redirect(array('controller' => 'lots', 'action' => 'index'));
                    else
                    {
                        //Redirige vers 'Mon compte' si on est connecté normalement. Redirige vers Catalogue si connecté via Facebook
                        if($this->Session->check('connexionNormale')){
                            return $this->redirect(array('controller' => 'parieurs', 'action' => 'mon_compte'));
                        }
                        else{
                            return $this->_redirectCatalogue();
                        }
                    }

                }
            }

            $this->_messageErreur('Les jetons n\'ont pas pu être ajoutés à votre compte.');
        }
    }

    public function rechercher(){
        $this->set('title_for_layout', 'Rechercher un usager');
        $this->Paginator->settings = $this->paginate;
        if($this->request->is('post')){
            $conditions = $this->postConditions(
                $this->request->data,
                array(
                    'pseudo' => 'LIKE'
                )
            );
            $data = $this->Paginator->paginate(
                'Parieur',
                $conditions
            );
        }
        else{
            $data = $this->Paginator->paginate('Parieur');
        }
        $this->set('parieurs', $data);
    }

    public function consulter($id){
        $parieur = $this->Parieur->findById($id);
        $this->set('parieur', $parieur);

        if(AuthComponent::user()){
            $id_util = AuthComponent::user('id');
            $this->set('amitieExiste', $this->Parieur->Ami->amitieExiste($id_util, $id));
        }

        $this->set('title_for_layout', $parieur['Parieur']['pseudo']);
        $this->set('nbParisCrees', $this->getNombreParisSelonIdUsager($id));
    }

    /*
     * Fonctions privées
     */

    private function MotsPasseIdentiques($mp, $mp_confirmation)
    {
        return $mp == $mp_confirmation;
    }

    private function setSexe(){

        $this->loadModel('Sexe');
        $ddlSexe = $this->Sexe->find('list', array('fields' => 'nom'));
        $this->set('ddlSexe', $ddlSexe);
    }

    private function setDefaultAvatar(){

        $sexe = $this->request->data['Parieur']['sexe_id'];
        if($sexe == 1){
            $this->request->data['Parieur']['avatar'] = 'masculin.png';
        }
        else{
            $this->request->data['Parieur']['avatar'] = 'feminin.png';
        }
    }

    private function usagerPossedeAvatarPersonnalise($avatar){
        return $avatar != "feminin.png" && $avatar != "masculin.png";
    }

    //Retourne le nombre de paris créés par cet usager
    private function getNombreParisSelonIdUsager($id){
        $this->loadModel('Pari');
        return $this->Pari->find('count', array('conditions' => array('parieur_id' => $id)));
    }
}
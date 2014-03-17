<?php

App::uses('FB', 'Facebook.Lib');
class ParieursController extends AppController
{
    public $components = array(
        'Stripe.Stripe'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        //Permet d'accéder à ces pages sans être connecté.
        $this->Auth->allow('inscription', 'logout', 'connexion');
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
            echo 'fb';
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
            if ($this->request->is(array('post', 'put'))) {

                if ($this->MotsPasseIdentiques($this->request->data['Parieur']['mot_passe'], $this->request->data['Parieur']['mot_passe_confirmation'])) {
                    $this->Parieur->create();

                    if ($this->Parieur->save($this->request->data, true, array('pseudo', 'mot_passe', 'courriel'))) {
                        $this->_messageSucces('Votre compte a été créé avec succès. Vous avez maintenant accès à toutes les fonctionnalités du site.');
                        $this->Auth->login($this->request->data);
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
        //Afin que les champs soient déjà remplis
        if (!$this->request->data) {
            $this->request->data = $this->Parieur->find('first', array(
                    'fields' => array('pseudo', 'courriel', 'id', 'nombre_jetons'),
                    'conditions' => array("id" => $this->Auth->user('id')))
            );
        }

        if ($this->request->is(array('post', 'put'))) {

            // on récupere le mot de passe et l'id propre au parieur
            $mot_passe = $this->request->data['Parieur']['mot_passe'];
            $id = $this->request->data['Parieur']['id'];
            $this->Parieur->id = $id;

            $sauvegardeOk = false; // par défaut

            if (empty($mot_passe)) {
                // juste un nouveau courriel va être enregistré
                if ($this->Parieur->save($this->request->data, true, array('courriel'))) {
                    $sauvegardeOk = true;
                }
            } else {
                // vérification que le nouveau mdp soit égal a la confirmation
                if ($this->MotsPasseIdentiques($mot_passe, $this->request->data['Parieur']['mot_passe_confirmation'])) {
                    // ok, on sauvegarde
                    if ($this->Parieur->save($this->request->data, true, array('courriel', 'mot_passe'))) {
                        $sauvegardeOk = true;
                    }
                } else {
                    // problème mdp différent
                    $this->_messageErreur('Les mots de passe doivent être identiques');
                    return;
                }
            }

            if ($sauvegardeOk) {
                $this->_messageSucces('Votre compte a bien été modifié.');

            } else {
                $this->_messageErreur('Votre compte n\'a pas pu être modifié.');
            }
        }
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

    /*
     * Fonctions privées
     */

    private function MotsPasseIdentiques($mp, $mp_confirmation)
    {
        return $mp == $mp_confirmation;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 15:40
 */
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
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->messageSucces('Vous êtes maintenant connecté.');
                return $this->redirect($this->Auth->redirect());
            }
            $this->messageErreur('Pseudo ou mot de passe invalide.');
        }
    }

    //Déconnexion
    public function logout()
    {
        $this->messageInfo('Vous êtes maintenant déconnecté.');
        return $this->redirect($this->Auth->logout());
    }

    //Inscription au site
    public function inscription()
    {
        if ($this->request->is(array('post', 'put'))) {

            $this->Parieur->create();

            if ($this->Parieur->save($this->request->data, true, array('pseudo', 'mot_passe', 'courriel'))) {
                $this->messageSucces('Votre compte a été créé. Veuillez maintenant vous connecter.');
                return $this->redirect(array('action' => 'connexion'));
            }
            else {
                $this->messageErreur('Une erreur est survenue lors de la création de votre compte.');
            }
        }
    }

    //Affiche la page "Mon compte"
    public function mon_compte()
    {
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
            }
            else {
                // vérification que le nouveau mdp soit égal a la confirmation
                if ($mot_passe == $this->request->data['Parieur']['mot_passe_confirmation']) {
                    // ok, on sauvegarde
                    if ($this->Parieur->save($this->request->data, true, array('courriel', 'mot_passe'))) {
                        $sauvegardeOk = true;
                    }
                }
                else {
                    // problème mdp différent
                    $this->messageErreur('Les mots de passes doivent être identiques');
                    return;
                }
            }

            if ($sauvegardeOk) {
                $this->messageSucces('Votre compte a bien été modifié.');

            } else {
                $this->messageErreur('Votre compte n\'a pas pu être modifié.');
            }
        }
    }

    //Fonction pour acheter des jetons avec le plugin Stripe
    public function acheter_jetons()
    {
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

                    $this->messageSucces($nombre_jetons_achetes . ' jetons ont été ajoutés à votre compte.');
                    return $this->redirect(array('controller' => 'parieurs', 'action' => 'mon_compte'));
                }
            }

            $this->messageErreur('Les jetons n\'ont pas pu être ajoutés à votre compte.');
        }
    }
}
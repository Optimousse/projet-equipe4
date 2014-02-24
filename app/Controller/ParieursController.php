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
                $this->Session->setFlash(__('Vous êtes maintenant connecté.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-info'
                ));
                return $this->redirect($this->Auth->redirect());
            }
            $this->Session->setFlash(__('Pseudo ou mot de passe invalide.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }

    //Déconnexion
    public function logout()
    {
        $this->Session->setFlash(__('Vous êtes maintenant déconnecté.'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-info'
        ));
        return $this->redirect($this->Auth->logout());
    }

    //Inscription au site
    public function inscription()
    {
        if ($this->request->is('post')) {
            $this->Parieur->create();


            if ($this->request->data['Parieur']['mot_passe'] == $this->request->data['Parieur']['confirmation']) {

                if ($this->Parieur->save($this->request->data)) {
                    $this->Session->setFlash(__('Votre compte a été créé. Veuillez maintenant vous connecter.'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    return $this->redirect(array('action' => 'connexion'));
                }
                $this->Session->setFlash(
                    __('Inscription impossible, le pseudo existe déja'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            } else {
                $this->Session->setFlash(__('Les mots de passes doivent être identiques'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        }
    }

    //Affiche la page "Mon compte"
    public function mon_compte()
    {

        if (!$this->request->data) {
            $this->request->data = $this->Parieur->find('first', array(
                    'fields' => array('pseudo', 'courriel', 'id'),
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
                // juste un nouveau mail va être saisis
                if ($this->Parieur->save($this->request->data, true, array('courriel'))) {
                    $sauvegardeOk = true;
                }
            } else {
                // une nouvelle adresse mail et un nouveau mot de passe ont été saisis

                // vérification que le nouveau mdp soit égal a la confirmation
                if ($mot_passe == $this->request->data['Parieur']['confirmation']) {

                    // ok, on sauvegarde
                    if ($this->Parieur->save($this->request->data, true, array('courriel', 'mot_passe'))) {
                        $sauvegardeOk = true;
                    }
                } else {
                    // proble;e, mdp différent
                    $this->Session->setFlash(__('Les mots de passes doivent être identiques'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));

                    return;
                }
            }

            if ($sauvegardeOk) {

                $this->Session->setFlash(__('Votre compte a bien été modifié.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));

            } else {

                $this->Session->setFlash(__('Votre compte n\'a pas été modifié.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));

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

                    $this->Session->setFlash(__($nombre_jetons_achetes . ' jetons ont été ajoutés à votre compte.'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    return $this->redirect(array('controller' => 'parieurs', 'action' => 'mon_compte'));
                }
            }

            $this->Session->setFlash(__('Les jetons n\'ont pas pu être ajoutés à votre compte.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }
}
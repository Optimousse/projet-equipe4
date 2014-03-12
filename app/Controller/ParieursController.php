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
        $this->set('title_for_layout', 'Connexion');
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
        $this->Session->delete('dernierIdMessageRecupere');
        $this->Session->delete('dernierIdMessageLu');
        return $this->redirect($this->Auth->logout());
    }

    //Inscription au site
    public function inscription()
    {
        $this->set('title_for_layout', 'Inscription');
        if ($this->request->is(array('post', 'put'))) {

            if($this->MotsPasseIdentiques($this->request->data['Parieur']['mot_passe'], $this->request->data['Parieur']['mot_passe_confirmation'])){
                $this->Parieur->create();

                if ($this->Parieur->save($this->request->data, true, array('pseudo', 'mot_passe', 'courriel'))) {
                    $this->messageSucces('Votre compte a été créé. Veuillez maintenant vous connecter.');
                    return $this->redirect(array('action' => 'connexion'));
                }
                else {
                    $this->messageErreur('Une erreur est survenue lors de la création de votre compte.');
                }
            }
            else
                $this->messageErreur('Les mots de passe doivent être identiques.');
        }
    }

    //Affiche la page "Mon compte"
    public function mon_compte()
    {
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
            }
            else {
                // vérification que le nouveau mdp soit égal a la confirmation
                if ($this->MotsPasseIdentiques($mot_passe, $this->request->data['Parieur']['mot_passe_confirmation'])) {
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

                    $this->messageSucces($nombre_jetons_achetes . ' jetons ont été ajoutés à votre compte.');

                    if($ref == 'lots')
                        return $this->redirect(array('controller' => 'lots', 'action' => 'index'));
                    else
                        return $this->redirect(array('controller' => 'parieurs', 'action' => 'mon_compte'));
                }
            }

            $this->messageErreur('Les jetons n\'ont pas pu être ajoutés à votre compte.');
        }
    }

    /*
     * Fonctions privées
     */

    private function MotsPasseIdentiques($mp, $mp_confirmation){
        return $mp == $mp_confirmation;
    }
}
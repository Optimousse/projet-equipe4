<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 15:40
 */
class ParieursController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();

        //Permet d'accéder à ces pages sans être connecté.
        $this->Auth->allow('inscription', 'logout', 'connexion');
    }

    //Connexion au site
    public function connexion() {
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
    public function logout() {
        $this->Session->setFlash(__('Vous êtes maintenant déconnecté.'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-info'
        ));
        return $this->redirect($this->Auth->logout());
    }

    //Inscription au site
    public function inscription() {
        if ($this->request->is('post')) {
            $this->Parieur->create();
            if ($this->Parieur->save($this->request->data)) {
                $this->Session->setFlash(__('Votre compte a été créé. Veuillez maintenant vous connecter.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'connexion'));
            }
            $this->Session->setFlash(
                __('Impossible de créer le compte.', 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ))
            );
        }
    }

    //Affiche la page "Mon compte"
    public function mon_compte(){
        //Pour la section "Mes paris"
        //TODO compléter cette page: on doit pouvoir modifier son mot de passe et son adresse courriel. On doit aussi pouvoir acheter de nouveaux jetons et échanger ceux qu'on a contre de l'argent (bidon).
        $this->loadModel('Pari');
        $this->set('paris', $this->Pari->find('all', array('conditions' => array('Pari.parieur_id' => $this->Auth->user('id')))));
    }
}
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
                $this->Session->setFlash(__('Vous êtes maintenant connecté.'));
                return $this->redirect($this->Auth->redirect());
            }
            $this->Session->setFlash(__('Pseudo ou mot de passe invalide.'));
        }
    }

    //Déconnexion
    public function logout() {
        $this->Session->setFlash(__('Vous êtes maintenant déconnecté.'));
        return $this->redirect($this->Auth->logout());
    }

    //Inscription au site
    public function inscription() {
        //TODO ne pas pouvoir ajouter deux fois le meme pseudo
        if ($this->request->is('post')) {
            $this->Parieur->create();
            if ($this->Parieur->save($this->request->data)) {
                $this->Session->setFlash(__('Votre compte a été créé. Veuillez maintenant vous connecter.'));
                return $this->redirect(array('action' => 'connexion'));
            }
            $this->Session->setFlash(
                __('Impossible de créer le compte.')
            );
        }
    }
}
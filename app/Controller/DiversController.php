<?php

App::uses('CakeEmail', 'Network/Email');
class DiversController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('faq', 'accueil', 'contactez_nous');
    }

    //Page d'accueil
    public function accueil(){
        $this->set('title_for_layout', 'Paris, pas la ville');
        $this->loadModel('Pari');
        $paris = $this->Pari->find('all', array('limit' => 5, 'order' => 'id DESC', 'fields' => array('nom', 'image', 'description', 'date_fin')));
        $this->set('paris', $paris);

        $this->loadModel('Lot');
        $lot = $this->Lot->find('first', array('conditions' => array('id' => '1'), 'fields'=> 'image'));
        $this->set('lot', $lot);
    }

    //Page Foire aux questions
    public function faq(){
        $this->set('title_for_layout', 'Foire aux questions');

    }

    //Formulaire permettant à un utilisateur de nous envoyer un courriel
    public function contactez_nous(){
        $this->set('title_for_layout', 'Contactez-nous');

        if($this->request->is('post')){
            $this->envoyerCourriel();
        }
    }

    /*
     * Fonctions privées
     */

    //Un utilisateur nous envoie un courriel
    private function envoyerCourriel(){

        $Email = new CakeEmail();
        $Email->config('gmail');
        $Email->from('parispaslaville@gmail.com', 'Paris, pas la ville');

        $courriel = $this->request->data['Contact']['courriel'];
        $message = $this->request->data['Contact']['message'];
        $titre = $this->request->data['Contact']['titre'];

        try{
            $Email->to($courriel);
        }
        catch(Exception $e){
            $this->_messageErreur('Cette adresse courriel est invalide.');
            return;
        }
        $Email->viewVars(array('message' => $message));
        $Email->template('contactezNous');
        $Email->subject($titre);
        $Email->emailFormat('both');
        $Email->send('ContactezNous');

        $this->_messageSucces('Votre message nous a bien été envoyé. Merci de votre intérêt pour <i>Paris, pas la ville </i> !');
        unset($this->request->data);
        return;
    }
}

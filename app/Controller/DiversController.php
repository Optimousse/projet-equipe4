<?php

App::uses('CakeEmail', 'Network/Email');
class DiversController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('faq', 'accueil');
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

    public function contactez_nous(){

        if($this->request->is('post')){

            if($this->envoyerCourrielPerdant()){
                $this->messageSucces('Votre message nous a bien été envoyé. Merci de votre intérêt pour <i>Paris, pas la ville </i> !');
            }
            else{
                $this->messageSucces('Une erreur est survenue lors de l\'envoi de votre message.');
            }
        }
    }

    /*
     * Fonctions prives
     */

    //Un utilisateur nous envoie un courriel
    private function envoyerCourrielPerdant(){

        $courriel = $this->request->data['Contact']['courriel'];
        $message = $this->request->data['Contact']['message'];
        $titre = $this->request->data['Contact']['titre'];

        $Email = new CakeEmail();
        $Email->config('gmail');

        $Email->from($courriel, $courriel);
        $Email->to('parispaslaville@gmail.com');

        $Email->viewVars(array('message' => $message));
        $Email->template('contactezNous');
        $Email->subject($titre);
        $Email->emailFormat('both');

        try{
            $Email->send('ContactezNous');
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
}

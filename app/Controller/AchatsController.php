<?php

App::uses('CakeEmail', 'Network/Email');
class AchatsController extends AppController
{
    // Informations pour l'achat
    public function informations($id = null)
    {
        $this->set('title_for_layout', 'Acheter un lot');
        // on récupere l'id de la personne
        $id_usager = $this->Auth->user('id');
        $this->set('id_util', $id_usager);

        // on récupere l'id du lot
        $this->set('id_lot', $id);

        // on cherche le nombre de jetons de la personne grace a son id
        $this->loadModel('Parieur');
        $parieur = $this->Parieur->find('first', array('conditions' => array('Parieur.id' => $id_usager), 'fields' => array('nombre_jetons', 'courriel')));
        $jetonPossede = $parieur['Parieur']['nombre_jetons'];

        // on cherche le montant du lot grace a son id
        $this->loadModel('Lot');
        $leLot = $this->Lot->find('first', array('conditions' => array('Lot.id' => $id), 'fields' => array('prix', 'nom','image','description')));

        $montant = $leLot['Lot']['prix'];
        $this->set('lot', $leLot['Lot']);

        // on teste si le parieur possède assez de jetons pour parier le montant désiré
        if ($leLot['Lot']['prix'] > $jetonPossede) {

            // calcule le nonbre de jetons manquant pour se procurer le lot => pour affichage du message d'erreur
            $sommeManquante = $montant - $jetonPossede;

            $lien = Router::url(array('controller' => 'parieurs', 'action' => 'acheter_jetons', 'lots'), false);
            // explication de l'erreur
            $this->_messageAvertissement('Il vous manque '.$sommeManquante.' jetons pour acheter ce lot.'.
                '<a href="' . $lien . '"><br/>Vous pouvez en racheter ici.</a>');

            // on renvoie sur la page des lots
            return $this->redirect(array('action' => 'index', 'controller' => 'Lots'));
        }

        if ($this->request->is(array('post', 'put'))) {

            if ($this->sauvegarderNouveauxJetons($id_usager, $montant)) {
                $this->Achat->create();

                if ($this->Achat->save($this->request->data, true, array('adresse', 'code_postal', 'ville','parieur_id', 'lot_id'))) {
                    $this->_messageSucces('Votre commande a bien été passée.');
                    $this->envoyerCourrielAcheteur($parieur['Parieur']['courriel'], $leLot['Lot']['nom'], $montant);
                    return $this->redirect(array('action' => 'index', 'controller' => 'Lots'));
                } else {
                    $this->_messageErreur('Une erreur est survenue lors de l\'achat du lot.');
                }
            }
        }
    }

    /*
     * Fonctions privées
     */

    private function sauvegarderNouveauxJetons($id_usager, $nombre_jetons){

        $this->Parieur->id = $id_usager;
        $parieur = $this->Parieur->findById($id_usager);
        $nbJetons = $parieur['Parieur']['nombre_jetons'] - $nombre_jetons;
        return $this->Parieur->saveField('nombre_jetons', $nbJetons);
    }

    //Envoie un courriel d'information à un utilisateur qui a acheté un lot.
    private function envoyerCourrielAcheteur($courriel, $nomLot, $montant){

        $Email = new CakeEmail();
        $Email->config('gmail');
        $Email->from('parispaslaville@gmail.com', 'Paris, pas la ville');

        try{
            $Email->to($courriel);
        }
        catch(Exception $e){
            $this->_loguerErreur($this->params['controller'], $this->action, $courriel. ' n\'est pas un courriel valide. Le courriel n\'a pas été envoyé.');
            return;
        }

        $Email->viewVars(array('nomLot' => $nomLot, 'nbJetons' => $montant));
        $Email->template('acheteLot');
        $Email->subject('Votre commande a été passée.');
        $Email->emailFormat('both');
        $Email->send('Acheteur');
    }
}

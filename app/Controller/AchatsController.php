<?php

/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 14-03-07
 * Time: 08:34
 */
class AchatsController extends AppController
{

    // Informations pour l'achat
    public function informations($id = null)
    {
        // on récupere l'id de la personne
        $id_usager = $this->Auth->user('id');
        $this->set('id_util', $id_usager);

        // on récupere l'id du lot
        $this->set('id_lot', $id);

        // on cherche le nombre de jetons de la personne grace a son id
        $this->loadModel('Parieur');
        $parieur = $this->Parieur->find('first', array('conditions' => array('Parieur.id' => $id_usager), 'fields' => array('nombre_jetons')));
        $jetonPossede = $parieur['Parieur']['nombre_jetons'];

        // on cherche le montant du lot grace a son id
        $this->loadModel('Lot');
        $leLot = $this->Lot->find('first', array('conditions' => array('Lot.id' => $id), 'fields' => array('prix')));
        $montant = $leLot['Lot']['prix'];

        // on teste si le parieur possède assez de jetons pour parier le montant désiré
        if ($montant > $jetonPossede) {

            $lien = Router::url(array('controller' => 'parieurs', 'action' => 'acheter_jetons'), false);
            // explication de l'erreur
            $this->messageAvertissement('Vous n\'avez pas assez de jetons pour acheter ce lot.
                                                 <a href="' . $lien . '">Vous pouvez en racheter ici.</a>');
            return;
        }

        if ($this->request->is(array('post', 'put'))) {

            $this->Achat->create();
            if ($this->sauvegarderNouveauxJetons($id_usager, $montant)) {

                $this->messageSucces('La mise a bien été créée. Votre compte a été débité de '.$montant .' jetons.');
            }

            $this->Achat->create();

            if ($this->Achat->save($this->request->data, true, array('adresse', 'code_postal', 'ville','parieur_id', 'lot_id'))) {
                $this->messageSucces('Votre compte commande a été passée');
                return $this->redirect(array('action' => 'index', 'controller' => 'Lots'));
            } else {
                $this->messageErreur('Une erreur est survenue lors de la création de votre compte.');
            }
        }

    }

    private function sauvegarderNouveauxJetons($id_usager, $nombre_jetons){

        var_dump($nombre_jetons);
        $this->Parieur->id = $id_usager;
        $parieur = $this->Parieur->findById($id_usager);
        $nbJetons = $parieur['Parieur']['nombre_jetons'] - $nombre_jetons;
         var_dump($nbJetons);
        return $this->Parieur->saveField('nombre_jetons', $nbJetons);
    }

}

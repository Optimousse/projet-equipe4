<?php
App::uses('CakeEmail', 'Network/Email');
class ParisController extends AppController
{
    public $helpers = array('Html', 'Form');
    public $components = array(
        'RequestHandler','Paginator');

    public $paginate = array(
        'limit' => 9,
        'order' => array(
            'Pari.date_fin' => 'desc'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles lorsque le parieur n'est pas connecté
        $this->Auth->allow('index');
    }

    //Vue index: affiche tous les paris
    public function index()
    {
        $this->set('title_for_layout', 'Catalogue');
        $this->Paginator->settings = $this->paginate;

        //Si on a rempli le champ de recherche
        if(isset($this->request->query['motCle'])){
            $data = $this->rechercherParisSelonCriteres();
        }
        else{
            $data = $this->Paginator->paginate('Pari');
        }

        $this->set('paris', $data);
    }

    //Permet d'ajouter un pari au site
    public function ajouter()
    {
        $this->set('title_for_layout', 'Créer un pari');
        $this->set('id_util', $this->Auth->user('id'));
        $this->loadModel('Choix');
        if ($this->request->is('post')) {

            $this->Pari->create();
            $this->Choix->create();

            $date = $this->request->data['Pari']["date_fin"];
            list($day, $month, $year) = split('[/.-]', $date);
            $date_fin = array("date_fin" => array("month" => $month, "day" => $day, "year" => $year));
            $this->request->data['Pari'] = array_replace($this->request->data['Pari'], $date_fin);

            //Si l'utilisateur n'a rentré que deux choix, on supprime le troisième pour ne pas l'enregistrer.
           $choix3 = $this->request->data['Choix']['2'];
            if (empty($choix3['cote']) && empty($choix3['nom'])) {
                unset($this->request->data['Choix']['2']);
            }
            else if (empty($choix3['cote'])) {
                $this->messageErreur('La cote est obligatoire si vous ajoutez un troisième choix.');
                return;
            }
            else if (empty($choix3['nom'])) {
                $this->messageErreur('Le nom est obligatoire si vous ajoutez un troisième choix.');
                return;
            }

            if($this->uploadImage()){

                if ($this->Pari->saveAll($this->request->data)) {
                    $this->messageSucces('Le pari a été créé avec succès.');
                    return $this->redirect(array('action' => 'miser', 'controller' => 'ParieursParis', $this->Pari->id));
                }

                $this->messageErreur('Vérifiez que tous les champs ont été correctement remplis.');
            }
        }
    }

    //Permet au créateur d'un pari de déterminer le choix gagnant d'un pari (Seulement lorsque ce pari est terminé)
    public function determiner_gagnant($id = null)
    {
        $this->set('title_for_layout', 'Déterminer le choix gagnant');
        if (!$id)
            return $this->redirect(array('action' => 'index', 'controller' => 'paris'));
        $pari = $this->Pari->findById($id);

        if(!$this->validerDroitsChoixGagnant($pari))
            return $this->redirectAccueil();

        $this->loadModel('Choix');

        //Pour créer le groupe de radiobuttons qui montrent les choix disponibles pour le pari
        $options = $this->Choix->find('list', array('conditions' => array('Choix.pari_id' => $id), 'fields' => array('id', 'nom')));

        $this->set('options', $options);
        $this->set('paris', $pari);

        if ($this->request->is(array('post', 'put'))) {

            $this->Pari->id = $id;
            if ($this->Pari->save($this->request->data)) {

                $this->majJetonsGagnants($pari);
                return $this->redirect(array('action' => 'mes_paris', 'controller' => 'paris'));
            }
            $this->messageErreur('Une erreur est survenue lors de la fermeture du pari. Veuillez réessayer.');
        }
    }

    //Affiche les paris créés par l'utilisateur
    public function mes_paris()
    {
        $this->set('title_for_layout', 'Mes paris');
        $this->Paginator->settings = array(
            'conditions' => array('Pari.parieur_id' => $this->Auth->user('id')),
            'limit' => 5
        );

        $data = $this->Paginator->paginate('Pari');
        $this->set('paris', $data);
    }

    //Page d'accueil
    public function accueil(){
        $this->set('title_for_layout', 'Paris, pas la ville');
        $paris = $this->Pari->find('all', array('limit' => 5, 'order' => 'id DESC', 'fields' => array('nom', 'image', 'description', 'date_fin')));
        $this->set('paris', $paris);
    }

    /*
     * Fonctions privées
     */

    private function validerDroitsChoixGagnant($pari){
        $estValide = true;

        if (!$pari)
            $estValide = false;
        else if ($pari['Pari']['parieur_id'] != $this->Auth->user('id'))
            $estValide = false; //Si tu n'es pas le créateur de ce pari
        else if ($pari['Pari']['date_fin'] > date("Y-m-d"))
            $estValide = false;//Si le pari n'est pas encore terminé
        if (isset($pari['Pari']['choix_gagnant']))
            $estValide = false;//Si un gagnant a déjà été choisi

        return $estValide;
    }

    //Appelé dans la fonction "Determiner choix gagnant" pour mettre à jour les jetons des gagnants
    //en fonction de leur mise et de la cote du choix gagnant
    private function majJetonsGagnants($pari){

        $this->loadModel('ParieursPari');
        $this->loadModel('Parieur');
        //L'ID du choix gagnant qui vient d'être sélectionné
        $id_ChoixGagnant = $this->request->data['Pari']['choix_gagnant'];
        //Le choix correspondant à celui qui vient d'être sélectionné
        $choix = $this->Choix->findById($id_ChoixGagnant);
        //La cote du choix en question
        $coteChoix = $choix['Choix']['cote'];

        $misesGagnantes = $this->ParieursPari->find('all', array('conditions' => array('ParieursPari.pari_id' => $pari['Pari']['id'], 'choix_id' => $id_ChoixGagnant)));

        //Met à jour le nombre de jetons pour chaque personne qui a parié.
        foreach ($misesGagnantes as $item) {
            $this->Parieur->id = $item['ParieursPari']['parieur_id'];
            $parieur = $this->Parieur->findById($this->Parieur->id);
            $nbJetons = $parieur['Parieur']['nombre_jetons'] + $item['ParieursPari']['mise'] * $coteChoix;
            $this->Parieur->saveField('nombre_jetons', $nbJetons);

            $this->envoyerCourrielGagnant($parieur['Parieur']['courriel'], $item['ParieursPari']['mise'] * $coteChoix, $pari);
        }

        //Envoie courriel aux perdants
        $misesPerdantes = $this->ParieursPari->find('all', array('conditions' => array('ParieursPari.pari_id' => $pari['Pari']['id'], 'choix_id != '. $id_ChoixGagnant)));
        foreach ($misesPerdantes as $item) {

            $this->Parieur->id = $item['ParieursPari']['parieur_id'];
            $parieur = $this->Parieur->findById($this->Parieur->id);

            $this->envoyerCourrielPerdant($parieur['Parieur']['courriel'], $pari);
        }

        $this->messageSucces('Le pari a été correctement fermé. Les vainqueurs ont reçu leurs jetons.');
    }

    //Envoie un courriel de félicitations à un utilisateur qui a remporté une mise sur un pari
    private function envoyerCourrielGagnant($courriel, $nbJetons, $pari){

        $Email = new CakeEmail();
        $Email->config('gmail');

        $Email->from('parispaslaville@gmail.com', 'Paris, pas la ville');
        $Email->to($courriel);

        $Email->viewVars(array('nbJetons' => $nbJetons, 'pari' => $pari));
        $Email->template('gagneMise');
        $Email->subject('Félicitations, vous avez remporté votre mise !');
        $Email->emailFormat('both');

        try{
            $Email->send('Gagnant');
        }
        catch(Exception $e){
        }
    }

    //Envoie un courriel d'information à un utilisateur qui a perdu une mise sur un pari
    private function envoyerCourrielPerdant($courriel, $pari){

        $Email = new CakeEmail();
        $Email->config('gmail');

        $Email->from('parispaslaville@gmail.com', 'Paris, pas la ville');
        $Email->to($courriel);

        $Email->viewVars(array('pari' => $pari));
        $Email->template('perduMise');
        $Email->subject('Vous avez perdu votre mise.');
        $Email->emailFormat('both');

        try{
            $Email->send('Perdant');
        }
        catch(Exception $e){
        }
    }

    //Upload une image lors de la création d'un pari
    private function uploadImage(){
        $dossier = 'uploads';

        $image = $this->request->data['Pari']['image'];
        $taillemax = 2097152;
        $taille = filesize($image['tmp_name']);
        $estValide = true;
        $extension = strrchr($image['name'], '.');
        $extensionsOK = array('.jpg', '.jpeg', '.png', '.gif', '.bmp');
        if(!empty($image['error'])){
            if($image['error'] == 1){
                $this->messageErreur('La taille maximale de l\'image est de 2 Mo.');
                $estValide = false;
            }
        }
        else if(!in_array($extension, $extensionsOK))
        {
            $this->messageErreur('L\'image doit être dans l\'un des formats suivants: jpg, jpeg, png, gif ou bmp.');
            $estValide = false;
        }
        else if($taille > $taillemax){

            $this->messageErreur('Le fichier est trop volumineux. La taille maximale est de 2 Mo.');
            $estValide = false;
        }
        else{
            $id = String::uuid();

            if(!move_uploaded_file($image['tmp_name'], IMAGES.$dossier.'/'.$id.$extension)){
                $this->messageErreur('Une erreur est survenue lors de l\'upload de l\'image.');
                $estValide = false;
            }

            unset($this->request->data['Pari']['image']);
            $this->request->data['Pari']['image'] =$dossier.'/'.$id.$extension;
        }
        return $estValide;
    }

    //Recherche des paris selon certains critères
    private function rechercherParisSelonCriteres(){

        $RechercheNom = array();
        $RechercheDescription = array();
        $RechercheEnCours = array();
        $RecherchePariTermine = array();

        if(isset($this->request->query['motCle'])){
            $RechercheNom = array("nom LIKE" => '%'.$this->request->query['motCle'].'%');
            $this->set('estRechercheParNom', array('checked' => 'checked'));
        }

        if(isset($this->request->query['description'])){
            $RechercheDescription = array("description LIKE" => '%'.$this->request->query['motCle'].'%');
            $this->set('estRechercheParDescription', array('checked' => 'checked'));
        }

        if(isset($this->request->query['enCours'])){
            $RechercheEnCours = array("date_fin >" => date("Y-m-d"));
            $this->set('estRechercheEnCours', array('checked' => 'checked'));
        }

        if(isset($this->request->query['termine'])){
            $RecherchePariTermine = array("date_fin < " => date("Y-m-d"));
            $this->set('estRechercheTermine', array('checked' => 'checked'));
        }

        $this->set('critereActuel', $this->request->query['motCle']);

        return $this->Paginator->paginate(
            'Pari',
            array("OR" => array(
                $RechercheNom, $RechercheDescription),  array( "OR" => array($RecherchePariTermine, $RechercheEnCours)))
        );
    }
}
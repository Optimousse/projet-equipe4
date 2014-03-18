<?php

class Contact extends AppModel {
    //N'utilise pas de table de la base de données
    public $useTable = false;
    //Validation des champs
    public $validate = array(
        'courriel' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Votre adresse courriel est obligatoire.'
            )),
        'titre' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le titre du message est obligatoire.'
            )),
        'message' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le message est obligatoire.'
        ))
    );
}
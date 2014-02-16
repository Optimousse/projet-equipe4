<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 18:31
 */
class Pari extends AppModel {

    /* Lien entre la table Pari et la table Choix */
    public $hasMany = array(
        'Choix' => array(
            'className' => 'Choix',
            'limit' => '3'
        )
    );

    //Validation des paris
    public $validate = array(
        'nom' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le nom est obligatoire'
            )
        ),
        'description' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'La description est obligatoire'
            )
        ),
        'image' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'L\'image est obligatoire'
            )
        ),
        'date_fin' => array(
            'rule'    => array('validationDate'),
            'message' => 'La date de fin ne doit pas être antérieure à la date actuelle.'
        )
    );

    public function validationDate($date) {

        return $date['date_fin'] > date("Y-m-d");
    }
}
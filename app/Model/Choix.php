<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-12
 * Time: 18:58
 */

class Choix extends AppModel {
    public $useTable = 'choix';

    /* Le lien entre la table Choix et la table Pari */
    public $belongsTo = array(
        'Pari' => array(
            'className' => 'Pari',
            'limit' => '1'
        )
    );

    //Validation des choix
    public $validate = array(
        'cote' => array(

            'rule1' => array(
                'rule'    => array('comparison', '>', 1),
                'message' => 'La cote doit être un chiffre compris entre 1.1 et 5',
                'allowEmpty' => true
            ),
            'rule2' => array(
                'rule'    => array('comparison', '<', 5),
                'message' => 'La cote doit être un chiffre compris entre 1.1 et 5',
                'allowEmpty' => true
            ),
            'rule3' => array(
                'rule'    => '/^[0-9]*(\.[0-9])?$/',
                'message' => 'La cote doit être un chiffre compris entre 1.1 et 5',
                'allowEmpty' => true
            )
        )
    );
}
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
    public $belongsTo = 'Pari';

    //Validation des choix
    public $validate = array(
        'cote' => array(
        'rule'    => array('comparison', '>', 1),
        'message' => 'La cote doit être un chiffre supérieur à 1',
        'allowEmpty' => true,
        'rule'    => '/^[0-9]*$/',
        'message' => 'La cote doit être un chiffre supérieur à 1'
        )
    );
}
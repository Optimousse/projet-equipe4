<?php
/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 14-03-03
 * Time: 10:32
 */

class Sexe extends AppModel {

    /* Lien entre la table Pari et la table ParieurParis */
    public $hasMany = 'Parieur';

    public $validate = array(
        'id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le sexe est obligatoire.',
                'on' => 'create'))
    );
}
<?php
/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 14-03-03
 * Time: 10:32
 */


class ParieursPari extends AppModel {

    /* Lien entre la table Pari et la table ParieurParis */
    public $belongsTo = array(
        'Pari' => array(
            'className' => 'Pari',
            'limit' => '1'
        ),

        'Choix' => array(
            'className' => 'Choix'
        )
    );

    public $validate = array(
        'mise' => array(
            'rule1' => array(
                'rule'    => 'naturalNumber',
                'message' => 'La mise doit être un nombre entier supérieur à 1.',
                'allowEmpty' => false
            )
        ),
        'choix_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le choix est obligatoire.'
            ))
    );
}
<?php
/**
 * Created by PhpStorm.
 * User: administrateur
 * Date: 14-03-03
 * Time: 10:32
 */


class ParieursPari extends AppModel {

    public $validate = array(
        'mise' => array(
            'rule'    => 'naturalNumber',
            'message' => 'La mise doit être un chiffre supérieur à 1',
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'La mise est obligatoire'
            )
        )
    );
}
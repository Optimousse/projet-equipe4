<?php

class Achat extends AppModel {

    public $hasMany= array(
        'Message' => array(
            'className' => 'Message'
        )
    );

    public $validate = array(
        'adresse' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'L\'adresse est obligatoire'
            ),
        ),
        'code_postal' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le code postal est obligatoire.',
                "on" => 'create'
            )
        ),

        'ville' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'La ville est obligatoire',
                'on' => 'create'
            ),
        )
    );
}

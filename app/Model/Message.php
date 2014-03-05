<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-03-03
 * Time: 19:59
 */
class Message extends AppModel {

    public $belongsTo = array(
        'Parieur' => array(
            'className' => 'Parieur'
        )
    );

    public $validate = array(
        'message' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Vous ne pouvez envoyer un message vide.'
            )
        ));
}
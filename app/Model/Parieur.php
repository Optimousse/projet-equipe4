<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 15:39
 */
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class Parieur extends AppModel {
    public $validate = array(
        'pseudo' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le pseudo est obligatoire.'
            ),
            'rule'    => 'isUnique',
            'message' => 'Ce pseudo a déjà été choisi par un autre utilisateur.'
        ),
        'mot_passe' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le mot de passe est obligatoire.',
                "on" => 'create'
            )
        ),
        'courriel' => array(//TODO validation email
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le courriel est obligatoire.'
            )
        )
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['mot_passe'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['mot_passe'] = $passwordHasher->hash(
                $this->data[$this->alias]['mot_passe']
            );
        }
        return true;
    }
}
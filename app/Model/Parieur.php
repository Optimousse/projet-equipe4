<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-04
 * Time: 15:39
 */
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class Parieur extends AppModel
{

    public $hasMany = array(
        'Message' => array(
            'className' => 'Message'
        )
    );

    public $validate = array(
        'pseudo' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le pseudo est obligatoire.'
            ),
            'rule' => 'isUnique',
            'message' => 'Ce pseudo a déjà été choisi par un autre utilisateur.'
        ),
        'mot_passe' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le mot de passe est obligatoire.',
                "on" => 'create'
            )
        ),
        'mot_passe_confirmation' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Le mot de passe de confirmation est obligatoire.',
            )
        ),
        'courriel' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'L\'adresse courriel est obligatoire.',
                'on' => 'create'
            ),
            'email' => array(
                'rule' => array('email', false),
                'message' => 'L\'adresse courriel doit avoir une forme valide.'
            )
        )
    );

    public $actsAs = array('ImageUpload' => array(
        'avatar' => array(
            'required' => true,
            'allowed_mime' => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
            'allowed_extension' => array('.jpg', '.jpeg', '.png', '.gif'),
            'allowed_size' => 2097152,
            'random_filename' => true,
            'resize' => array(
                'thumb' => array(
                    'directory' => 'img/avatars/',
                    'phpThumb' => array(
                        'far' => 1,
                        'bg'  => 'FFFFFF'
                    ),
                    'height' => 300,
                    'width' => 200
                )
            )
        )
    )
    );

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['mot_passe'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['mot_passe'] = $passwordHasher->hash(
                $this->data[$this->alias]['mot_passe']
            );
        }
        return true;
    }
}

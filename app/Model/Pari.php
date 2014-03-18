<?php

class Pari extends AppModel
{
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
                'message' => 'Le nom est obligatoire.'
            )
        ),
        'description' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'La description est obligatoire.'
            )
        ),
        'date_fin' => array(
            'rule' => array('validationDate'),
            'message' => 'La date de fin ne doit pas être antérieure à la date actuelle.'
        )
    );

    public $actsAs = array('ImageUpload' => array(
        'image' => array(
            'required' => true,
            'directory' => 'img/uploads/',
            'allowed_mime' => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
            'allowed_extension' => array('.jpg', '.jpeg', '.png', '.gif'),
            'allowed_size' => 2097152,
            'random_filename' => true,
            'resize' => array(
                'thumb' => array(
                    'directory' => 'img/uploads/thumbs/',
                    'phpThumb' => array(
                        'zc' => 0
                    ),
                    'height' => 300
                ),
                'max' => array(
                    'directory' => 'img/uploads/',
                    'phpThumb' => array(
                        'zc' => 0
                    ),
                    'width' => 1200,
                    'height' => 600
                )
            )
        )
    )
    );

    public function validationDate($date)
    {

        return $date['date_fin'] > date("Y-m-d");
    }
}
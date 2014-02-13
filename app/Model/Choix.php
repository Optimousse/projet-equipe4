<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14-02-12
 * Time: 18:58
 */

class Choix extends AppModel {
    public $useTable = 'Choix';

    /* Le lien entre la table Choix et la table Pari */
    public $belongsTo = 'Pari';
} 
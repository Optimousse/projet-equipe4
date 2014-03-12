<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $helpers = array(
        'Session',
        'Js' => array('Jquery'),
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator')
    );
    public $components = array(
        'Session',
        "RequestHandler",
        //Paramètres qui définissent la connexion. NE PAS MODIFIER LA SECTION "AUTHENTICATE"
        'Auth' => array(
            'flash' => array(
                'element' => 'alert',
                'key' => 'auth',
                'params' => array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                )
            ),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'Parieur', // On utilise le modèle Parieur plutôt que "User", donc il faut l'indiquer explicitement
                    'fields' => array('username' => 'pseudo', 'password' => 'mot_passe') // On utilise pseudo et mot_passe plutôt que username et password
                )
            ),
            'logoutRedirect' => array(
                'controller' => 'divers',
                'action' => 'accueil'
            ),
            //Si pas connecté, redirige vers la page de connexion
            'loginAction' => array(
                'controller' => 'parieurs',
                'action' => 'connexion'
            ),
            'authError' => 'Vous devez être connecté pour accéder à cette page'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        //Pages accessibles sans être connecté (Les actions accessibles pour tous les contrôleurs)
        $this->Auth->allow('index');
        $this->Auth->loginRedirect = array('controller' => 'paris', 'action' => 'index');
    }

    public function messageSucces($message)
    {
        $this->Session->setFlash(
            __($message), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-success'
        ));
    }

    public function messageErreur($message)
    {
        $this->Session->setFlash(
            __($message), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
    }


    public function messageInfo($message)
    {
        $this->Session->setFlash(
            __($message), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-info'
        ));
    }

    public function messageAvertissement($message){

        $this->Session->setFlash(
            __($message), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-warning'
        ));
    }

    public function redirectAccueil(){

        return $this->redirect(array('action' => 'accueil', 'controller' => 'divers'));
    }


    public function redirectCatalogue(){

        return $this->redirect(array('action' => 'index', 'controller' => 'paris'));
    }
}

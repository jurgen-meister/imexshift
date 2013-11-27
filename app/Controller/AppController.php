<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $helpers = array(
		'Session',
		'Js',
		'Html' => array('className' => 'TwitterBootstrap.BootstrapHtml'),
		'Form' => array('className' => 'TwitterBootstrap.BootstrapForm'),
		'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
	);

	public $components = array(
		'RequestHandler',
		'Session',
		'Permission',
		'Auth'=>array(
			'authenticate'=>array(
				'Form'=>array(
					'userModel'=>'AdmUser'
					,'fields'=>array('username'=>'login')
				)
			)
			,'loginRedirect' => array('controller' => 'admUsers', 'action' => 'welcome')
			,'logoutRedirect' => array('controller' => 'admUsers', 'action' => 'login')//this is used for login and logout
			,'loginAction'=>array(
				'controller' => 'admUsers',
				'action' => 'login',
			)
			,'authError'=>'Auth Error'
			,'authorize'=>array('Controller') // para que sirva la function isAuthorized sino naranjas
		)
	);
	

	public function beforeFilter() {
//			$this->set('logged_in', $this->Auth->loggedIn());
			
			//Some servers have issues with cakephp session component and give error session.auto_start, so this is a backup
			if(!isset($_SESSION)) session_start(); //If session didn't start, then start it
	}
	
	
	public  function isAuthorized($user){
		return true; //when is true there aren't permissions
//		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name)); //it activates permission for all controllers
	}

}

 
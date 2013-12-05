<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	
	public function beforeSave(){
		if($this->id OR isset($this->data[$this->name]['id'])){//for triggers update
//			if (!isset($this->data[$this->name]['lc_transaction'])) {
				$this->data[$this->name]['lc_transaction']='MODIFY';//in model there is no request->data ONLY data ;)
//			}
		}
        return true;
	}

	//When there wasn't triggers
//	public function beforeSave($options = array()) {
//		App::import('Model', 'CakeSession');
//		$session = new CakeSession();
//		if(isset($this->data[$this->name]['id'])){
//			$this->data[$this->name]['modifier']=$session->read('UserRestriction.id');
//			$this->data[$this->name]['lc_transaction']='MODIFY';
//		}else{
//			$this->data[$this->name]['creator']=$session->read('UserRestriction.id');
//		}
//        return true;
//    }
	
}

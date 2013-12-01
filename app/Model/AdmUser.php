<?php
App::uses('AppModel', 'Model');
/**
 * AdmUser Model
 *
 * @property AdmProfile $AdmProfile
 * @property AdmLogin $AdmLogin
 * @property AdmUserRestriction $AdmUserRestriction
 */
class AdmUser extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'login' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active_date' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public function beforeSave($options = array()) {
		App::import('Model', 'CakeSession');
		$session = new CakeSession();
		if(isset($this->data[$this->name]['id'])){
			$this->data[$this->name]['modifier']=$session->read('UserRestriction.id');
			$this->data[$this->name]['lc_transaction']='MODIFY';
		}else{
			$this->data[$this->name]['creator']=$session->read('UserRestriction.id');
		}
        if (isset($this->data['AdmUser']['password'])) {
            $this->data['AdmUser']['password'] = AuthComponent::password($this->data['AdmUser']['password']);
        }
        return true;
    }
	
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasOne = array(
		'AdmProfile' => array(
			'className' => 'AdmProfile',
			'foreignKey' => 'adm_user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public $hasMany = array(
		'AdmUserRestriction' => array(
			'className' => 'AdmUserRestriction',
			'foreignKey' => 'adm_user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	
	public function change_user_restriction($idUser, $idUserRestrictionSelected){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		////////////////////////////////////////////////
		$exist = $this->AdmUserRestriction->find('count', array(
			'conditions'=>array(
				'AdmUserRestriction.id'=>$idUserRestrictionSelected
			)
		));
		if($exist == 0){
			$dataSource->rollback();
			return false;
		}
			
		if(!$this->AdmUserRestriction->updateAll(array('AdmUserRestriction.selected'=>0, 'AdmUserRestriction.lc_transaction'=>"'MODIFY'"), array('AdmUserRestriction.adm_user_id'=>$idUser))){
			$dataSource->rollback();
			return false;
		}
		if(!$this->AdmUserRestriction->updateAll(array('AdmUserRestriction.selected'=>1, 'AdmUserRestriction.lc_transaction'=>"'MODIFY'"), array('AdmUserRestriction.id'=>$idUserRestrictionSelected))){
			$dataSource->rollback();
			return false;
		}
		$dataSource->commit();
		return true;
		///////////////////////////////////////////////
	}
	
	public function fnChangePassword($idUser, $password, $username){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		////////////////////////////////////////////
		if(!$this->save(array('id'=>$idUser,'password'=>$password))){
			$dataSource->rollback();
			return false;
		}
		
		$sql = "ALTER USER ".$username." WITH PASSWORD '".$password."';";
		try{
			$this->query($sql);	
		}catch(Exception $e){
			debug($e);
			$dataSource->rollback();
			return false;
		}
		///////////////////////////////////////////
		$dataSource->commit();
		return true;
	}
	
	public function fnAddUserProfile($data, $username, $password){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		////////////////////////////////////////////
		if(!$this->saveAssociated($data)){
			$dataSource->rollback();
			return false;
		}
		$sql = "CREATE USER ".$username." WITH PASSWORD '".$password."';";
		try{
			$this->query($sql);	
		}catch(Exception $e){
			debug($e);
			$dataSource->rollback();
			return false;
		}
		//every user can create a role, this is not good, but to fix this without depending on a DBA need to build a grant permission interface per user
		$sql = "ALTER ROLE ".$username." WITH CREATEROLE;";
		try{
			$this->query($sql);	
		}catch(Exception $e){
			debug($e);
			$dataSource->rollback();
			return false;
		}
		$sql = "GRANT group_average_users to ".$username.";";
		try{
			$this->query($sql);	
		}catch(Exception $e){
			debug($e);
			$dataSource->rollback();
			return false;
		}
		///////////////////////////////////////////
		$dataSource->commit();
		return true;
	}
	
///////////
}

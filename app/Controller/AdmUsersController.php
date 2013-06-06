<?php
App::uses('AppController', 'Controller');
/**
 * AdmUsers Controller
 *
 * @property AdmUser $AdmUser
 */
class AdmUsersController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'default';

/**
 * Helpers
 *
 * @var array
 */
	//public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Session');
	
	public  function isAuthorized($user){
		$array=$this->Session->read('Permission.'.$this->name);
		$array['welcome'] = 'welcome';
		$array['login'] = 'login';
		$array['logout'] = 'logout';
		$array['choose_role'] = 'choose_role';
		if(count($array)>0){
			if(in_array($this->action, $array)){
				return true;
			}
		}
		$this->redirect($this->Auth->logout());
	}
	/*
	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
	*/
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AdmUser->recursive = 0;
		$this->set('admUsers', $this->paginate());
	}
	
	public function login() {
		$this->layout = 'login';
		if ($this->request->is('post')) {
			if ($this->Auth->login()) { //If authentication is valid username and password
							
			/////////////////////////////////////////////BEGIN OF VALIDATION///////////////////////////////////////////////////
				$userInfo = $this->Auth->user();
				$active=$userInfo['active'];
				$activeDate = $this->AdmUser->find('count', array('conditions'=>array('AdmUser.active_date >' => date('Y-m-d H:i:s'), 'AdmUser.id'=>$userInfo['id']))); //The DB does the comparition between dates, it's simpler than creating a php function for this
				$role = $this->AdmUser->AdmUserRestriction->find('count', array('conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'])));
				$roleActive = $this->AdmUser->AdmUserRestriction->find('count', array('conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'], 'AdmUserRestriction.active'=>1, 'AdmUserRestriction.selected'=>1)));
				$roleActiveDate = $this->AdmUser->AdmUserRestriction->find('count', array('conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'], 'AdmUserRestriction.active_date >'=>date('Y-m-d H:i:s'), 'AdmUserRestriction.selected'=>1)));;
				$error=0;
				
				//User active
				if($active != 1){
					$this->_createMessage('El usuario esta inactivo');
					$error++;
					$this->redirect($this->Auth->logout());
				}
				
				//User date active
				if($activeDate == 0){
					$this->_createMessage('El usuario expiró');
					$error++;
					$this->redirect($this->Auth->logout());
				}
				
				//Roles Validation
				if ($role == 0){//No roles found
					$this->_createMessage('El usuario no tiene ningun rol asignado');
					$error++;
					$this->redirect($this->Auth->logout());
				}else{
					if($roleActive == 0 OR $roleActiveDate == 0){
						$otherRoles = $this->AdmUser->AdmUserRestriction->find('all', array(
							'conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'],
								'AdmUserRestriction.active_date >'=>date('Y-m-d H:i:s'),
								'AdmUserRestriction.active'=>1),
							'fields'=>array('AdmUser.id', 'AdmUser.login', 'AdmRole.name', 'AdmUserRestriction.period'),
							'order'=>array('AdmUserRestriction.adm_role_id', 'AdmUserRestriction.period')
						));
						if(count($otherRoles) > 0 ){
							$roleInactive = $this->AdmUser->AdmUserRestriction->find('all', array(
								'conditions'=>array('AdmUserRestriction.selected'=>1, 'AdmUserRestriction.adm_user_id'=>$userInfo['id']),
								'fields'=>array('AdmRole.name', 'AdmUserRestriction.period')
								));
							if(count($roleInactive) > 0){//if there is one role selected
								$this->Session->write('RoleInactive.name', $roleInactive[0]['AdmRole']['name']);
								$this->Session->write('PeriodInactive.name', $roleInactive[0]['AdmUserRestriction']['period']);
								$this->Session->write('User.chooserole', $otherRoles);
								$error++;
								$this->redirect(array('action'=>'choose_role'));
							}else{
								$this->_createMessage('No hay ningun rol asignado a esta cuenta');
								$error++;
								$this->redirect($this->Auth->logout());
							}
						}
						if($roleActive == 0){
							$this->_createMessage('El rol del usuario esta inactivo');
							$error++;
							$this->redirect($this->Auth->logout());
						}
						if($roleActiveDate == 0){
							$this->_createMessage('El rol del usuario expiró');
							$error++;
							$this->redirect($this->Auth->logout());
						}
						
					}
				}
			///////////////////////////////////////////////END OF VALIDATION////////////////////////////////////////////////////
			
			//////////////////////////////////////////////START - LOGIN /////////////////////////////////////////////////////////
				if($error == 0){//if there is no error
					$this->_createUserAccountSession($userInfo['id'], $userInfo['login']);
				} 
			//////////////////////////////////////////////END - LOGIN /////////////////////////////////////////////////////////		
			} else {
					$this->_createMessage('Usuario o contraseña incorrecta, intente de nuevo');
			}
		}
	}
	
	
	private function _createUserAccountSession($userId, $userName, $tipo = 'login'){
		////////Fill of sessions distinct to auth component users table
		$infoRole = $this->AdmUser->AdmUserRestriction->find('all', array(
			'fields'=>array('AdmRole.name','AdmRole.id', 'AdmUserRestriction.period', 'AdmUserRestriction.id'),
			'conditions'=>array('AdmUserRestriction.adm_user_id'=>$userId, 'AdmUserRestriction.active'=>1, 'AdmUserRestriction.selected'=>1)
		));
		//debug($userId);
		//debug($userName);
		//debug($infoRole);
		$this->Session->write('UserRestriction.id', $infoRole[0]['AdmUserRestriction']['id']);  //in case there is no trigger postgres user integration, it will help
		$this->Session->write('User.username', $userName);
		$this->Session->write('User.id', $userId);
		$this->Session->write('Role.name', $infoRole[0]['AdmRole']['name']);
		$this->Session->write('Role.id', $infoRole[0]['AdmRole']['id']);
		$this->Session->write('Menu', $this->_createMenu($this->Session->read('Role.id')));
		$this->Session->write('Period.name', $infoRole[0]['AdmUserRestriction']['period']);
		$this->_createPermissions($this->Session->read('Role.id'));
		//////////////////////////////////////////////////////////////////////////
		if($this->AdmUser->AdmUserRestriction->AdmUserLog->save(array('adm_user_restriction_id'=>$infoRole[0]['AdmUserRestriction']['id'], 'tipo'=>$tipo,'creator'=>1))){
			$this->redirect($this->Auth->redirect());//IN CASE OF NO ERRORS PROCEED TO LOGIN
		}else{
			$this->_createMessage('Ocurrio un problema vuelva a intentarlo');
			$this->redirect($this->Auth->logout());
		}
	}

	
	public function choose_role(){
		//echo "Aqui va para elegir roles con fecha activa en caso de que un rol este inactivo y tenga otros roles";
		if ($this->request->is('post')) {
			//debug($this->request->data);
			$data = explode('-', $this->request->data['AdmUser']['userAccountSession']);
			//debug($data[1]);
			//debug($data[2]);
			$this->_selectOtherRole($data[0], $data[1], $data[2]);
		}
	}
	
	private function _selectOtherRole($userRestrictionId, $userId, $userName){
		if($this->AdmUser->AdmUserRestriction->updateAll(array('AdmUserRestriction.selected'=>0), array('AdmUserRestriction.adm_user_id'=>$userId))){
			if($this->AdmUser->AdmUserRestriction->save(array('id'=>$userRestrictionId, 'selected'=>1))){
				$this->_createUserAccountSession($userId, $userName, 'login escogiendo rol');
			}else{
				$this->_createMessage('Ocurrio un error, comuniquese con su administrador para habilitar su cuenta');
				$this->redirect($this->Auth->logout());
			}	
		}else{
			$this->_createMessage('Ocurrio un error, vuelva a intentarlo');
			$this->redirect($this->Auth->logout());
		}
	}
	
	private function _createMessage($message, $key = 'error'){
		$this->Session->setFlash('<strong>'.$message.'</strong>',
								 'alert',
								 array('plugin' => 'TwitterBootstrap','class' => 'alert-'.$key)
		);
	}
	
	private function _createMenu($roleId){
		$this->loadModel('AdmRolesMenu');
		$parents = $this->AdmRolesMenu->find('all', array(
			'fields'=>array('AdmMenu.id', 'AdmMenu.name')
			,'conditions'=>array('AdmRolesMenu.adm_role_id'=>$roleId, "AdmMenu.parent_node"=>null, 'AdmMenu.inside'=>null)
			, 'order'=>array('AdmMenu.order_menu')
		));
		
		/////////////////////////////////////////////////////////////////////
		$str = '';
			/////////////////////////////////////START - Parents///////////////////////////////////////////////////////
				$str.='<ul>';
				foreach ($parents as $key2 => $value2) {
					$arrLinkContent = $this->_createLink($value2['AdmMenu']['id'], $value2['AdmMenu']['name'], 'SI');
						if($arrLinkContent['idForLi'] <> ''){$idForLi = 'id="'.$arrLinkContent['idForLi'].'"';}else{$idForLi='';}
							$str.='<li '.$idForLi.' class="submenu">'.$arrLinkContent['link'];//$value2['AdmMenu']['name'];
					////////////////////////////////////START - Children 1////////////////////////////////////////////////
					$str.='<ul>';
						$children1 = $this->_findMenus($value2['AdmMenu']['id'], $roleId);
						foreach ($children1 as $key3 => $value3) {
							$arrLinkContent = $this->_createLink($key3, $value3, '');
							if($arrLinkContent['idForLi'] <> ''){$idForLi = 'id="'.$arrLinkContent['idForLi'].'"';}else{$idForLi='';}
							$str.='<li '.$idForLi.'>'.$arrLinkContent['link'];//$value3;
								//more children.....
							$str.='</li>';
						}
					$str.='</ul>';
					////////////////////////////////////END - Children 1////////////////////////////////////////////////
					$str.='</li>';
				}
				$str.='</ul>';
			//////////////////////////////////////END - Parents///////////////////////////////////////////////////////
		return $str;
	}
	
	
	private function _createLink($idMenu, $nameMenu, $icon){
		$projectName = 'imexport';
		$this->loadModel('AdmMenu');
		
		$this->AdmMenu->unbindModel(array(
			'belongsTo'=>array('AdmAction', 'AdmModule'),
			'hasMany'=>array('AdmRolesMenu')
		));
		
		$this->AdmMenu->bindModel(array(
			'hasOne'=>array(
				'AdmAction'=>array(
					'foreignKey'=>false,
					'conditions' => array('AdmMenu.adm_action_id = AdmAction.id')
				),
				'AdmController'=>array(
					'foreignKey'=>false,
					'conditions' => array('AdmAction.adm_controller_id = AdmController.id')
				),
				
			)
		));
		
		$vec = $this->AdmMenu->find('all', array('fields'=>array('AdmAction.name', 'AdmController.name'),'conditions'=>array("AdmMenu.id"=>$idMenu)));
		
		$controlerName = $vec[0]['AdmController']['name'];
		$actionName = strtolower($vec[0]['AdmAction']['name']);
		$link = '/'.$projectName.'/'.$controlerName.'/'.$actionName;
		$idForLi =$controlerName.'-'.$actionName;
		
		if($vec[0]['AdmAction']['name'] == null){
			$link = '#';
		}
		//debug($vec);
		if($icon <> ''){
			$idName = '';
			$nameIcon='';
			switch($nameMenu){
				case 'ADMINISTRACION':
					$nameIcon='icon-wrench';
					$idName = 'adm';
					break;
				case 'INVENTARIO':
					$nameIcon='icon-list-alt';
					$idName = 'inv';
					break;
				case 'COMPRAS':
					$nameIcon='icon-shopping-cart';
					$idName = 'pur';
					break;
				case 'VENTAS':
					$nameIcon='icon-tags';
					$idName = 'sal';
					break;
			}
			if($vec[0]['AdmAction']['name'] == null){$idForLi = 'mod-'.$idName;}
			$str = '<a href="'.$link.'"><i class="icon '.$nameIcon.'"></i> <span>'.$nameMenu.'</span></a>';
		}else{
			if($vec[0]['AdmAction']['name'] == null){$idForLi = '';}
			$str = '<a href="'.$link.'">'.$nameMenu.'</a>';
		}
		
		//return $str;
		return array('link'=>$str, 'idForLi'=>$idForLi);
	}

	
	
	private function _findMenus($parent, $roleId){
		$this->loadModel('AdmRolesMenu');
		$vec = $this->AdmRolesMenu->find('all', array('fields'=>array('AdmMenu.id', 'AdmMenu.name') , 'order'=>array('AdmMenu.order_menu'),'conditions'=>array("AdmMenu.parent_node"=>$parent, "AdmRolesMenu.adm_role_id"=>$roleId)));
		$found=array();
		if(count($vec)>0){
			foreach ($vec as $key => $value) {
				$found[$value['AdmMenu']['id']] = $value['AdmMenu']['name'];
				
			}
			//debug($found);
		}
		return $found;
		
	}
	
	private function _createPermissions($roleId){
		$this->loadModel('AdmRolesMenu');
		
		//$array=$this->AdmRolesMenu->find('all');
		//debug($array);
		$this->AdmRolesMenu->unbindModel(array(
			'belongsTo'=>array('AdmRole', 'AdmMenu'),
		));
		
		$this->AdmRolesMenu->bindModel(array(
			'hasOne'=>array(
				'AdmMenu'=>array(
					'foreignKey'=>false,
					'conditions' => array('AdmRolesMenu.adm_menu_id = AdmMenu.id')
				),
				'AdmAction'=>array(
					'foreignKey'=>false,
					'conditions' => array('AdmMenu.adm_action_id = AdmAction.id')
				),
				'AdmController'=>array(
					'foreignKey'=>false,
					'conditions' => array('AdmAction.adm_controller_id = AdmController.id')
				),
				
			)
		));
		
		$vec = $this->AdmRolesMenu->find('all', array(
			 'conditions'=>array('AdmRolesMenu.adm_role_id'=>$roleId)
			,'fields'=>array('AdmRolesMenu.adm_role_id','AdmMenu.adm_action_id', 'AdmAction.id','AdmAction.name', 'AdmController.name')
			));
		//debug($vec);
		$formated = array();
		$extra = array();
		if(count($vec) >0){
			foreach ($vec as $key => $value) {
				if($value['AdmAction']['name'] != ''){
					$formated[$key]['controller'] = Inflector::camelize($value['AdmController']['name']);
					$formated[$key]['action'] = strtolower($value['AdmAction']['name']);
					//echo $value['AdmAction']['id'].'<br>';
					//$extra[$key] = $this->_findControllerActionAjax($value['AdmAction']['id']);
				}
			}
		}
		//debug($formated);
		
		$formatExtra = array();
		/*
		if(count($extra) > 0){
			foreach ($extra as $key => $value) {
				if(count($value) > 0){
					$formatExtra[$key]['controller'] = $value[0]['AdmController']['name'];
					$formatExtra[$key]['action'] = $value[0]['AdmAction']['name'];
				}
			}
		}
		*/
		//echo "extra";
		//debug($formatExtra);
		//debug(array_merge($formated,$formatExtra));
		
		//return array_merge($formated,$formatExtra);
		$merge = array_merge($formated,$formatExtra);
		//debug($merge);
		///// save in session array 
		
		for($i=0; $i<count($merge); $i++){
			$this->Session->write('Permission.'.$merge[$i]['controller'].'.'.$merge[$i]['action'], $merge[$i]['action']);
			//'Permission.AdmController.index'=index
			/*
			$this->Session->write('Role.name', $infoRole[0]['AdmRole']['name']);
			$this->Session->write('Role.id', $infoRole[0]['AdmRole']['id']);
			 */
			//echo 'Permission.'.$merge[$i]['controller'].'.'.$merge[$i]['action'];
		}
		//debug($merge);
		
	}
	
	private function _findControllerActionAjax($parent){
		$this->loadModel('AdmAction');	
		$array = $this->AdmAction->find('all', array(
			 'conditions'=>array('AdmAction.parent'=>$parent)
			,'fields'=>array('AdmAction.name', 'AdmController.name')
			));
		return $array;
	}
	

	public function welcome(){
		
	}
	

	public function logout() {
		$this->Session->destroy();
		$this->_createMessage('La sesión termino!', 'info');
		$this->redirect($this->Auth->logout());
	}
	
/////////////////////////////////////////////////////////////////////////////////////
	
	public  function ajax_generate_user_name(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START AJAX/////////////////////////////////////////////
			return $this->_generate_user_name($this->request->data['first_name'], $this->request->data['last_name']);
			////////////////////////////////////////////END AJAX///////////////////////////////////////////////
		}
	}

	private function _generate_user_name($first_name, $last_name){
		
			$firstName = explode(' ',$first_name);
			$lastName = explode(' ',$last_name);
			
			$userNameSimple = substr($firstName[0], 0, 1).$lastName[0];
			$userNameFull = '';
			if(isset($lastName[1]) && $lastName[1] <> ''){
				$userNameFull = $userNameSimple.substr($lastName[1], 0, 1); 
			}
			
			if($userNameFull == ''){
				$userNameAux = $userNameSimple;
			}else{
				$userNameAux = $userNameFull;
			}
			
			$founded = $this->AdmUser->find('count', array('conditions'=>array('AdmUser.login'=>$userNameAux)));
			
			if($founded > 0){
				$arrAux = explode('_', $userNameAux);
				if(isset($arrAux[1]) && $arrAux[1] <> ''){
					$userName = $arrAux[0].'_'.($arrAux[1] + 1);
				}else{
					$userName = $arrAux[0];
				}
			}
			return $userName;
	}
	
	private function _generate_password($length=10){
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmUser->create();
			if ($this->AdmUser->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		//$admJobTitles = $this->AdmUser->AdmJobTitle->find('list');
		$this->set(compact('admJobTitles'));
	}

	public function ajax_add_user_profile(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START AJAX///////////////////////////////////////////////
			$AdmUser = array();
			$AdmProfile = array();
			
			$AdmUser['AdmUser']['login'] = $this->request->data['login'];
			$AdmUser['AdmUser']['password'] = $this->_generate_password(8);
			$AdmUser['AdmUser']['active'] = 1;
			$AdmUser['AdmUser']['active_date'] = $this->request->data['active_date'];
			
			$AdmProfile['AdmProfile']['first_name'] = $this->request->data['first_name'];	
			$AdmProfile['AdmProfile']['last_name'] = $this->request->data['last_name'];	
			$AdmProfile['AdmProfile']['birthdate'] = $this->request->data['birthdate'];
			$AdmProfile['AdmProfile']['birthplace'] = $this->request->data['birthplace'];
			$AdmProfile['AdmProfile']['identity_document'] = $this->request->data['identity_document'];
			$AdmProfile['AdmProfile']['address'] = $this->request->data['address'];
			$AdmProfile['AdmProfile']['email'] = $this->request->data['email'];
			$AdmProfile['AdmProfile']['phone'] = $this->request->data['phone'];
			$AdmProfile['AdmProfile']['job'] = $this->request->data['job'];
			
			$data = array($AdmUser, $AdmProfile);
			
			if($this->AdmUser->saveAssociated($data)){
				echo 'inserted|'.$AdmUser['AdmUser']['password'];
			}
			////////////////////////////////////////////END AJAX///////////////////////////////////////////////
		}
	}
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmUser->id = $id;
		if (!$this->AdmUser->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm user')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
            //$this->request->data['AdmUser']['modifier']=$this->Auth->user['id'];
			$this->request->data['AdmUser']['lc_transaction']='MODIFY';
			if ($this->AdmUser->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm user')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmUser->read(null, $id);
			
		}
		$admJobTitles = $this->AdmUser->AdmJobTitle->find('list');
		$this->set(compact('admJobTitles'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->AdmUser->id = $id;
		if (!$this->AdmUser->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm user')));
		}
		if ($this->AdmUser->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm user')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm user')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}


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
	
	//public  function isAuthorized($user){
		/*
		$array=$this->Session->read('Permission.'.$this->name);
		$array['welcome'] = 'welcome';
		$array['login'] = 'login';
		$array['logout'] = 'logout';
		$array['choose_role'] = 'choose_role';
		debug($array);
		if(count($array)>0){
			if(in_array($this->action, $array)){
				return true;
			}
		}
		$this->redirect($this->Auth->logout());
		 * 
		 */
		
		
		//return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	//	return true;
	//}
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
		//$this->AdmUser->recursive = 0;
		//$this->set('admUsers', $this->paginate());
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$filters = '';
		$this->paginate = array(
			'conditions'=>array(
				$filters
			 ),
			'recursive'=>0,
			//'fields'=>array('InvMovement.id', 'InvMovement.code', 'InvMovement.document_code', 'InvMovement.date','InvMovement.inv_movement_type_id','InvMovementType.name', 'InvMovement.inv_warehouse_id', 'InvWarehouse.name', 'InvMovement.lc_state'),
			'order'=> array('AdmUser.id'=>'desc'),
			'limit' => 20,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$array = $this->_paintUserActiveDateField($this->paginate('AdmUser'));
		//debug($array);
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('admUsers', $array);
	}
	
	private function _paintUserActiveDateField($array){
		for($i=0; $i <count($array); $i++){
			$res = $this->AdmUser->find('count', array(
				'conditions'=>array(
					'AdmUser.active_date > now()',
					'AdmUser.id'=>$array[$i]['AdmUser']['id'])
			));
			$array[$i]['AdmUser']['token_valide_date'] = $res;
		}
		return $array;
	}
	
	public function add_user_restriction($id = null){
		if($id == null){
			$this->redirect(array('action'=>'index'));
		}
		$userInfo = $this->AdmUser->find('all', array(
			'conditions'=>array('AdmUser.id'=>$id),
			'fields'=>array('AdmUser.login'),
			'recursive'=>-1
		));
		
		$this->loadModel('AdmPeriod');
		$periods = $this->AdmPeriod->find('list', array('fields'=>array('AdmPeriod.name','AdmPeriod.name')));
		$periodInitial =  key($periods);
		$areas = $this->AdmUser->AdmUserRestriction->AdmArea->find('list', array('conditions'=>array('AdmArea.period'=>$periodInitial)));
		$rolesTaken = array();
		$admUserRestriction = $this->AdmUser->AdmUserRestriction->find('all', array(
				'conditions'=>array('AdmUserRestriction.adm_user_id'=>$id, 'AdmUserRestriction.period'=>$periodInitial),
				'fields'=>array('AdmUserRestriction.adm_role_id')
		));
		//debug($admUserRestriction);
		for($i=0; $i < count($admUserRestriction); $i++){
				$rolesTaken[$admUserRestriction[$i]['AdmUserRestriction']['adm_role_id']] = $admUserRestriction[$i]['AdmUserRestriction']['adm_role_id'];
		}
		
		$roles =$this->AdmUser->AdmUserRestriction->AdmRole->find('list',array(
			'conditions'=>array('NOT'=>array('AdmRole.id'=>$rolesTaken))
		));
		
		$this->set('username', $userInfo[0]['AdmUser']['login']);
		$this->set('userId', $id);
		$this->set('areas', $areas);
		$this->set('roles',$roles);
		$this->set('periods',$periods);
	}
	
	
	public function edit_user_restriction(){
		
		if(!isset($this->passedArgs['idUserRestriction'])){
			$this->redirect(array('action'=>'index'));
		}
		 
		//$id = $this->passedArgs['id'];
		$idUserRestriction = $this->passedArgs['idUserRestriction'];
		
		$AdmUserRestriction = $this->AdmUser->AdmUserRestriction->find('all', array(
			'conditions'=>array('AdmUserRestriction.id'=>$idUserRestriction),
			'fields'=>array('AdmUserRestriction.selected','AdmUser.id','AdmUser.login','AdmUserRestriction.active_date','AdmUserRestriction.active','AdmUserRestriction.adm_role_id', 'AdmUserRestriction.adm_area_id', 'AdmUserRestriction.period'),
		));
		
		$this->loadModel('AdmPeriod');
		$periods = $this->AdmPeriod->find('list', array('fields'=>array('AdmPeriod.name','AdmPeriod.name')));
		$roles =$this->AdmUser->AdmUserRestriction->AdmRole->find('list');
		$areas = $this->AdmUser->AdmUserRestriction->AdmArea->find('list', array(
			'conditions'=>array('AdmArea.period'=>$AdmUserRestriction[0]['AdmUserRestriction']['period'])
		));
		
		$periodId = $AdmUserRestriction[0]['AdmUserRestriction']['period'];
		$roleId = $AdmUserRestriction[0]['AdmUserRestriction']['adm_role_id'];
		$areaId = $AdmUserRestriction[0]['AdmUserRestriction']['adm_area_id'];
		$active = $AdmUserRestriction[0]['AdmUserRestriction']['active'];
		$activeDate = date("d/m/Y", strtotime($AdmUserRestriction[0]['AdmUserRestriction']['active_date']));
		$selected = $AdmUserRestriction[0]['AdmUserRestriction']['selected'];
		
		$this->set('username', $AdmUserRestriction[0]['AdmUser']['login']);
		$this->set('userId', $AdmUserRestriction[0]['AdmUser']['id']);
		$this->set('areas', $areas);
		$this->set('roles',$roles);
		$this->set('periods',$periods);
		$this->set(compact('periodId', 'roleId', 'areaId', 'active', 'activeDate', 'idUserRestriction', 'selected'));
	}
	
	public function ajax_list_roles_areas(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START AJAX/////////////////////////////////////////////
			$period= $this->request->data['period'];
			$userId= $this->request->data['userId'];
			$rolesTaken = array();
			
			$areas = $this->AdmUser->AdmUserRestriction->AdmArea->find('list', array('conditions'=>array('AdmArea.period'=>$period)));
			
			$admUserRestriction = $this->AdmUser->AdmUserRestriction->find('all', array(
				'conditions'=>array('AdmUserRestriction.adm_user_id'=>$userId, 'AdmUserRestriction.period'=>$period),
				'fields'=>array('AdmUserRestriction.adm_role_id')
			));
			
			for($i=0; $i < count($admUserRestriction); $i++){
				$rolesTaken[$admUserRestriction[$i]['AdmUserRestriction']['adm_role_id']] = $admUserRestriction[$i]['AdmUserRestriction']['adm_role_id'];
			}
			
			$roles =$this->AdmUser->AdmUserRestriction->AdmRole->find('list',array(
				'conditions'=>array('NOT'=>array('AdmRole.id'=>$rolesTaken))
			));
			
			$this->set('roles', $roles);
			$this->set('areas', $areas);
		}
		////////////////////////////////////////////END AJAX///////////////////////////////////////////////
	}
	
	public function index_user_restriction($id = null){
		
		if($id == null){
			$this->redirect(array('action'=>'index'));
		}
		
		$filters = array('AdmUserRestriction.adm_user_id'=>$id);
		$this->paginate = array(
			'conditions'=>array(
				$filters
			 ),
			'recursive'=>0,
			//'fields'=>array('InvMovement.id', 'InvMovement.code', 'InvMovement.document_code', 'InvMovement.date','InvMovement.inv_movement_type_id','InvMovementType.name', 'InvMovement.inv_warehouse_id', 'InvWarehouse.name', 'InvMovement.lc_state'),
			'order'=> array('AdmUserRestriction.id'=>'desc'),
			'limit' => 15,
		);
		//debug($this->paginate('AdmUserRestriction'));
		$userInfo = $this->AdmUser->find('all', array(
			'conditions'=>array('AdmUser.id'=>$id),
			'fields'=>array('AdmUser.login'),
			'recursive'=>-1
		));
		$array = $this->_paintUserRestrictionActiveDateField($this->paginate('AdmUserRestriction'));
		//debug($array);
		$this->set('userId', $id);
		$this->set('username', $userInfo[0]['AdmUser']['login']);
		$this->set('admUsers', $array);
	}
	
	private function _paintUserRestrictionActiveDateField($array){
		for($i=0; $i <count($array); $i++){
			$res = $this->AdmUser->AdmUserRestriction->find('count', array(
				'conditions'=>array(
					'AdmUserRestriction.active_date > now()'/* => date('Y-m-d H:i:s')*/,
					'AdmUserRestriction.id'=>$array[$i]['AdmUserRestriction']['id'])
			));
			$array[$i]['AdmUserRestriction']['token_valide_date'] = $res;
		}
		return $array;
	}
	
	public function login() {
		//before everything verify if the browser is IE from windows
		if(eregi("MSIE",getenv("HTTP_USER_AGENT")) || eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))){
			$this->redirect(array('controller'=>'Pages', 'action'=>'ie_denied'));
	   }
		
		//if is not, then go on
		$this->layout = 'login';
		if ($this->request->is('post')) {
			if ($this->Auth->login()) { //If authentication is valid username and password
							
			/////////////////////////////////////////////BEGIN OF VALIDATION///////////////////////////////////////////////////
				$userInfo = $this->Auth->user();
				$active=$userInfo['active'];
				$activeDate = $this->AdmUser->find('count', array('conditions'=>array('AdmUser.active_date > now()', 'AdmUser.id'=>$userInfo['id']))); //The DB does the comparition between dates, it's simpler than creating a php function for this
				$role = $this->AdmUser->AdmUserRestriction->find('count', array('conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'])));
				$roleActive = $this->AdmUser->AdmUserRestriction->find('count', array('conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'], 'AdmUserRestriction.active'=>1, 'AdmUserRestriction.selected'=>1)));
				$roleActiveDate = $this->AdmUser->AdmUserRestriction->find('count', array('conditions'=> array('AdmUserRestriction.adm_user_id'=>$userInfo['id'], 'AdmUserRestriction.active_date > now()', 'AdmUserRestriction.selected'=>1)));;
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
								'AdmUserRestriction.active_date > now()',
								'AdmUserRestriction.active'=>1),
							'fields'=>array('AdmUser.id', 'AdmUser.login', 'AdmRole.name', 'AdmUserRestriction.period', 'AdmUserRestriction.id'),
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
					$this->_createUserAccountSession($userInfo['id']);
				} 
			//////////////////////////////////////////////END - LOGIN /////////////////////////////////////////////////////////		
			} else {
					$this->_createMessage('Usuario o contraseña incorrecta, intente de nuevo');
			}
		}
	}
	
	private function _createUserAccountSession($userId, $tipo = 'login'){
		////////Fill of sessions distinct to auth component users table
		$infoRole = $this->AdmUser->AdmUserRestriction->find('all', array(
			'fields'=>array('AdmUser.login','AdmRole.name','AdmRole.id', 'AdmUserRestriction.period', 'AdmUserRestriction.id'),
			'conditions'=>array('AdmUserRestriction.adm_user_id'=>$userId, 'AdmUserRestriction.active'=>1, 'AdmUserRestriction.selected'=>1)
		));
		
		$this->Session->write('UserRestriction.id', $infoRole[0]['AdmUserRestriction']['id']);  //in case there is no trigger postgres user integration, it will help
		$this->Session->write('User.username', $infoRole[0]['AdmUser']['login']);
		$this->Session->write('User.id', $userId);
		$this->Session->write('Role.name', $infoRole[0]['AdmRole']['name']);
		$this->Session->write('Role.id', $infoRole[0]['AdmRole']['id']);
		$this->Session->write('Menu', $this->_createMenu($this->Session->read('Role.id')));
		$this->Session->write('Period.name', $infoRole[0]['AdmUserRestriction']['period']);
		$this->Session->delete('Message.auth');//to avoid bug showing auth messages when you are kickout and do login again
		$this->_createPermissions($this->Session->read('Role.id'));
		//////////////////////////////////////////////////////////////////////////
		
		/////////////////////////////Create USER,ROLE,PERIOD Session Buttons///////////////////////////////////////////
		$avaliableRoles = $this->_listAvaliableRoles($userId, $infoRole[0]['AdmUserRestriction']['id']);
		$this->Session->write('Avaliable.roles', $avaliableRoles);
		/////////////////////////////Create USER,ROLE,PERIOD Session Buttons///////////////////////////////////////////
		
		//////////////////////////////////////////////////////////////////////////
		$this->loadModel('AdmUserLog');
		try{
			$this->AdmUserLog->save(array('tipo'=>$tipo,'creator'=>$infoRole[0]['AdmUserRestriction']['id']));
			if($tipo == 'cambio rol'){
				$this->Session->setFlash(
					'<strong>Se cambio el rol a "'.$infoRole[0]['AdmRole']['name'].'" y a la gestión "'.$infoRole[0]['AdmUserRestriction']['period'].'"!</strong> Todos los cambios que haga este usuario se registraran con este rol y esta gestión',
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
			}
				$this->redirect($this->Auth->redirect());
			
		}catch (Exception $e){
			$this->_createMessage('Ocurrio un problema vuelva a intentarlo');
			$this->redirect($this->Auth->logout());
		} 
		
	}

	
	public function change_password(){
	}
	
	public function ajax_change_password(){
		if($this->RequestHandler->isAjax()){
			$password = $this->request->data['password'];
			$idUser = $this->Session->read('User.id');
			if($this->AdmUser->save(array('id'=>$idUser,'password'=>$password))){
				echo 'success';
			}
		}
	}

	
	public function change_email(){
	}
	
	public function ajax_change_email(){
		if($this->RequestHandler->isAjax()){
			$email = $this->request->data['email'];
			$idUser = $this->Session->read('User.id');
			$idProfile =$this->AdmUser->AdmProfile->find('list', array(
				'conditions'=>array('AdmProfile.adm_user_id'=>$idUser),
				'fields'=>array('AdmProfile.id', 'AdmProfile.id')
			));
			if($this->AdmUser->AdmProfile->save(array('id'=>key($idProfile),'email'=>$email))){
				echo 'success';
			}
			
		}
	}


	public function change_user_restriction($idUserRestrictionSelected){
		$idUser = $this->Session->read('User.id');
		try{
				$this->AdmUser->change_user_restriction($idUser, $idUserRestrictionSelected);
				$this->_createUserAccountSession($idUser, 'cambio rol');
		}catch(Exception $e){
			$this->Session->setFlash(
					'Ocurrio un problema, vuelva a intentarlo',
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			$this->redirect(array('action'=>'welcome'));
		}
		 
	}
	
	public function choose_role(){
		//echo "Aqui va para elegir roles con fecha activa en caso de que un rol este inactivo y tenga otros roles";
		if ($this->request->is('post')) {
			//debug($this->request->data);
			$data = explode('-', $this->request->data['AdmUser']['userAccountSession']);
			//debug($data[1]);
			//debug($data[2]);
			$this->_selectOtherRole($data[0], $data[1]);
		}
	}
	
	private function _selectOtherRole($userRestrictionId, $userId){
		try{
			$this->AdmUser->AdmUserRestriction->updateAll(array('AdmUserRestriction.selected'=>0), array('AdmUserRestriction.adm_user_id'=>$userId));
			try{
				$this->AdmUser->AdmUserRestriction->save(array('id'=>$userRestrictionId, 'selected'=>1));
				try{
					$this->_createUserAccountSession($userId, 'login escogiendo rol');
				}catch(Exception $e){
					$this->_createMessage('Ocurrio un error, comuniquese con su administrador para habilitar su cuenta');
					$this->redirect($this->Auth->logout());
				}
			}catch(Exception $e){
				$this->_createMessage('Ocurrio un error, comuniquese con su administrador para habilitar su cuenta');
				$this->redirect($this->Auth->logout());
			}
		}catch(Exception $e){
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
			'fields'=>array('AdmMenu.id', 'AdmMenu.name', 'AdmMenu.icon')
			,'conditions'=>array('AdmRolesMenu.adm_role_id'=>$roleId, "AdmMenu.parent_node"=>null, 'AdmMenu.inside'=>null)
			, 'order'=>array('AdmMenu.order_menu')
		));
		
		
		/////////////////////////////////////////////////////////////////////
		$str = '';
			/////////////////////////////////////START - Parents///////////////////////////////////////////////////////
				$str.='<ul>';
				foreach ($parents as $key2 => $value2) {
					$arrLinkContent = $this->_createLink($value2['AdmMenu']['id'], $value2['AdmMenu']['name'], $value2['AdmMenu']['icon']);
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
			/*
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
			 * 
			 */
			if($vec[0]['AdmAction']['name'] == null){$idForLi = 'menu-'.$nameMenu;}
			$str = '<a href="'.$link.'"><i class="icon '.$icon.'"></i> <span>'.$nameMenu.'</span></a>';
			
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
		//$avaliableRoles = $this->_listAvaliableRoles(1, 1);
		//$avaliableRoles[1]=2020;
		//debug($avaliableRoles);
		//debug(max($avaliableRoles[0]));
	}
	
	private function _listAvaliableRoles($userId, $userRestrictionId){
		$avaliableRoles = $this->AdmUser->AdmUserRestriction->find('all',array(
			'conditions'=>array(
				'AdmUserRestriction.adm_user_id'=>$userId, 
				'AdmUserRestriction.selected'=>0, 
				'AdmUserRestriction.active'=>1, 
				'AdmUserRestriction.active_date > now()',
				//'AdmUserRestriction.adm_role_id !='=>$roleId, 
				'AdmUserRestriction.id !='=>$userRestrictionId, 
			)
		));
		$array=array();
		for($i=0; $i < count($avaliableRoles); $i++){
			$array[$avaliableRoles[$i]['AdmUserRestriction']['id']]=  $avaliableRoles[$i]['AdmRole']['name'].' | '.$avaliableRoles[$i]['AdmUserRestriction']['period'];
		}
		return $array;
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
		
			$firstName = explode(' ',  strtolower($first_name));
			//debug($firstName);
			$lastName = explode(' ',  strtolower($last_name));
			//debug($lastName);
			$userNameSimple = substr(trim($firstName[0]), 0, 1).trim($lastName[0]);
			//debug($userNameSimple);
			$userNameFull = '';
			if(isset($lastName[1]) && $lastName[1] <> ''){
				$userNameFull = $userNameSimple.substr(trim($lastName[1]), 0, 1); 
				//debug($userNameFull);
			}
			
			if($userNameFull == ''){
				$userNameAux = $userNameSimple;
			}else{
				$userNameAux = $userNameFull;
			}
			//debug($userNameAux);
			$userName = $userNameAux;
			$founded = $this->AdmUser->find('count', array('conditions'=>array('AdmUser.login LIKE'=>'%'.$userNameAux.'%')));
			//debug($founded);
			
			if($founded > 0){
				$userName = $userNameAux.'_'.($founded+1);
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
	public function add(){
		//everything is done with ajax thats why is empty
	}

	public function ajax_verify_unique_di_number(){
		if($this->RequestHandler->isAjax()){
			$diNumber = $this->request->data['diNumber'];
			
			$res = $this->AdmUser->AdmProfile->find('count', array(
				'conditions'=>array('AdmProfile.di_number'=>$diNumber),
				'recursive'=>-1
				));
			echo $res;
		}
	}
	
	public function ajax_reset_password(){
		if($this->RequestHandler->isAjax()){
			$idUser = $this->request->data['idUser'];
			$password = $this->_generate_password(8);
			$this->AdmUser->save(array('id'=>$idUser, 'password'=>$password));
			$username = $this->AdmUser->find('list', array(
				'conditions'=>array('AdmUser.id'=>$idUser),
				'fields'=>array('AdmUser.id', 'AdmUser.login')
				));
			//debug(reset($username));
			$this->Session->write('Temp.username', reset($username));
			$this->Session->write('Temp.password', $password);
			echo 'success';
		}
	}
	
	public function ajax_add_user_restrictions(){
		if($this->RequestHandler->isAjax()){
			$AdmUserRestriction['adm_role_id'] = $this->request->data['roleId'];
			$AdmUserRestriction['adm_area_id'] = $this->request->data['areaId'];
			$AdmUserRestriction['adm_user_id'] = $this->request->data['userId'];
			$AdmUserRestriction['period'] = $this->request->data['period'];
			$AdmUserRestriction['active'] = $this->request->data['active'];
			$AdmUserRestriction['active_date'] = $this->request->data['activeDate'];
			//$AdmUserRestriction['creator'] = $this->Session->read('UserRestriction.id');
			$selected = $this->request->data['selected'];;
			if($selected == 0){
				$AdmUserRestriction['selected'] = 0;
			}else{
				$this->AdmUser->AdmUserRestriction->updateAll(array('AdmUserRestriction.selected'=>0), array('AdmUserRestriction.adm_user_id'=>$this->request->data['userId']));
				$AdmUserRestriction['selected'] = 1;
			}
			 
			if($this->AdmUser->AdmUserRestriction->save($AdmUserRestriction)){
				echo 'success|'.$this->request->data['roleId'];
			}
		}
	}

	public function ajax_edit_user_restrictions(){
		if($this->RequestHandler->isAjax()){
			$AdmUserRestriction['id'] = $this->request->data['userRestrictionId'];
			$AdmUserRestriction['adm_area_id'] = $this->request->data['areaId'];
			$AdmUserRestriction['active'] = $this->request->data['active'];
			$AdmUserRestriction['active_date'] = $this->request->data['activeDate'];
			$AdmUserRestriction['modifier'] = $this->Session->read('UserRestriction.id');
			$AdmUserRestriction['lc_transaction'] = 'MODIFY';
			
			$selected = $this->request->data['selected'];;
			if($selected == 0){
				$AdmUserRestriction['selected'] = 0;
			}else{
				$this->AdmUser->AdmUserRestriction->updateAll(array('AdmUserRestriction.selected'=>0), array('AdmUserRestriction.adm_user_id'=>$this->request->data['userId']));
				$AdmUserRestriction['selected'] = 1;
			}
			
			if($this->AdmUser->AdmUserRestriction->save($AdmUserRestriction)){
				echo 'success';
			}
		}
	}

	public function ajax_add_user_profile(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START AJAX///////////////////////////////////////////////
			$AdmUser = array();
			$AdmProfile = array();
			
			$username = $this->_generate_user_name(trim($this->request->data['txtFirstName']), str_replace(' ', '', $this->request->data['txtLastName1']).' '.str_replace(' ', '', $this->request->data['txtLastName2']));
			$password = $this->_generate_password(8);
			$AdmUser['login'] = $username;
			$AdmUser['password'] = $password;
			$AdmUser['active'] = $this->request->data['cbxActive'];
			$AdmUser['active_date'] = $this->request->data['txtActiveDate'];
			//$AdmUser['creator'] = $this->Session->read('UserRestriction.id');
			
			
			$AdmProfile['di_number'] = $this->request->data['txtDiNumber'];
			$AdmProfile['di_place'] = $this->request->data['txtDiPlace'];
			$AdmProfile['first_name'] = trim($this->request->data['txtFirstName']);	
			$AdmProfile['last_name1'] = trim($this->request->data['txtLastName1']);	
			$AdmProfile['last_name2'] = trim($this->request->data['txtLastName2']);
			$AdmProfile['email'] = $this->request->data['txtEmail'];
			$AdmProfile['job'] = $this->request->data['txtJob'];
			$AdmProfile['birthdate'] = $this->request->data['txtBirthdate'];
			$AdmProfile['birthplace'] = $this->request->data['txtBirthplace'];
			if($this->request->data['txtAddress'] <> ''){
				$AdmProfile['address'] = $this->request->data['txtAddress'];
			}
			if($this->request->data['txtPhone'] <> ''){
				$AdmProfile['phone'] = $this->request->data['txtPhone'];
			}
			//$AdmProfile['creator'] = $this->Session->read('UserRestriction.id');

			
			$data = array('AdmUser'=>$AdmUser, 'AdmProfile'=>$AdmProfile);
			if($this->AdmUser->saveAssociated($data)){
				$this->Session->write('Temp.username', $username);
				$this->Session->write('Temp.password', $password);
				echo 'success';
			}
			
			////////////////////////////////////////////END AJAX///////////////////////////////////////////////
		}
	}
	
	public function ajax_edit_user_profile(){
		if($this->RequestHandler->isAjax()){
			$AdmUser = array();
			$AdmProfile = array();
			$idUser = $this->request->data['idUser'];
			$AdmUser['id']=$idUser;
			$idProfile = $this->AdmUser->AdmProfile->find('list', array('conditions'=>array('AdmProfile.adm_user_id'=>$idUser)));
			$AdmProfile['id']= reset($idProfile); //get first element value
			
			$AdmUser['active'] = $this->request->data['cbxActive'];
			$AdmUser['active_date'] = $this->request->data['txtActiveDate'];
			//$AdmUser['creator'] = $this->Session->read('UserRestriction.id');
			
			$AdmProfile['di_number'] = $this->request->data['txtDiNumber'];
			$AdmProfile['di_place'] = $this->request->data['txtDiPlace'];
			$AdmProfile['first_name'] = trim($this->request->data['txtFirstName']);	
			$AdmProfile['last_name1'] = trim($this->request->data['txtLastName1']);	
			$AdmProfile['last_name2'] = trim($this->request->data['txtLastName2']);	
			$AdmProfile['email'] = $this->request->data['txtEmail'];
			$AdmProfile['job'] = $this->request->data['txtJob'];
			$AdmProfile['birthdate'] = $this->request->data['txtBirthdate'];
			$AdmProfile['birthplace'] = $this->request->data['txtBirthplace'];
			if($this->request->data['txtAddress'] <> ''){
				$AdmProfile['address'] = $this->request->data['txtAddress'];
			}
			if($this->request->data['txtPhone'] <> ''){
				$AdmProfile['phone'] = $this->request->data['txtPhone'];
			}
			$AdmProfile['modifier'] = $this->Session->read('UserRestriction.id');
			$AdmProfile['lc_transaction'] = 'MODIFY';
			
			$cont=0;
			if($this->AdmUser->save($AdmUser)){
				$cont++;
			}
			if($this->AdmUser->AdmProfile->save($AdmProfile)){
				$cont++;
			}
			if($cont == 2){
				echo 'success';
			}
		}
	}
	
	public function view_user_created(){
		if($this->Session->check('Temp.username') && $this->Session->check('Temp.password')){
			$this->set('username', $this->Session->read('Temp.username'));
			$this->set('password',$this->Session->read('Temp.password'));
			$this->Session->delete('Temp.username');
			$this->Session->delete('Temp.password');
		}else{
			//throw new NotFoundException(__('Invalid post'));
			$this->redirect(array('action'=>'add'));
		}
	}

	/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit() {
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}else{
			$this->redirect(array('action'=>'index'));
		}
		$this->AdmUser->id = $id;
		if (!$this->AdmUser->exists()) {
			throw new NotFoundException(__('No existe'));
		}
		$this->request->data = $this->AdmUser->read(null, $id);
		$this->set('data', $this->request->data);
	}
	
	public function view_user_profile(){
		$id = $this->Session->read('User.id');;
		$this->AdmUser->id = $id;
		$this->request->data = $this->AdmUser->read(null, $id);
		$this->set('data', $this->request->data);
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
		//$this->AdmUser->id = $id;
		/*
		if (!$this->AdmUser->exists()) {
			throw new NotFoundException(__('Invalido'));
		}*/
		/*
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
		}*/
		if ($this->AdmUser->AdmUserRestriction->find('count') > 0){
			$this->Session->setFlash(
				'No se puede eliminar este Usuario, ya que tiene Roles asignados!',
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		
		try{
			$this->AdmUser->AdmProfile->deleteAll(array('AdmProfile.adm_user_id'=>$id));
			try{
				$this->AdmUser->deleteAll(array('AdmUser.id'=>$id));
				$this->Session->setFlash(
				'Eliminado con exito!',
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
				);
				$this->redirect(array('action' => 'index'));
			}catch(Exception $e){
				$this->Session->setFlash(
				'Ocurrio un problema, vuelva a intentarlo',
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
				);
				$this->redirect(array('action' => 'index'));
			}
		}catch(Exception $e){
			$this->Session->setFlash(
			'Ocurrio un problema, vuelva a intentarlo',
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
			);
			$this->redirect(array('action' => 'index'));
		}
		
		
		/*
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm user')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
		 * 
		 */
	}
}


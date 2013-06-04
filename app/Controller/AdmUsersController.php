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
	
	/* 
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login'); // Por ahora no es necesario declare en AppController en loginAction ya que no es User por defecto sino AdmUser
	}
	*/
	public function login() {
		$this->layout = 'login';
		if ($this->request->is('post')) {
			//debug($this->Auth->login());
			//var_dump($this->request->data);
			if ($this->Auth->login()) { //If authentication is valid username and password
							
				/////////////////////BEGIN OF VALIDATION/////////////////////////
				$userInfo = $this->Auth->user();
				$active=$userInfo['active'];
				$activeDate = $this->AdmUser->find('count', array('conditions'=>array('AdmUser.active_date >' => date('Y-m-d H:i:s'), 'AdmUser.id'=>$userInfo['id']))); //se encarga la BD de hacer la comparacion sino mucho lio en vano
				
				$role = $this->AdmUser->AdmNodesRolesUser->find('count', array('conditions'=> array('AdmNodesRolesUser.adm_user_id'=>$userInfo['id'])));
				$roleActive = $this->AdmUser->AdmNodesRolesUser->find('count', array('conditions'=> array('AdmNodesRolesUser.adm_user_id'=>$userInfo['id'], 'AdmNodesRolesUser.active'=>1)));
				$roleActiveDate = $this->AdmUser->AdmNodesRolesUser->find('count', array('conditions'=> array('AdmNodesRolesUser.adm_user_id'=>$userInfo['id'], 'AdmNodesRolesUser.active_date >'=>date('Y-m-d H:i:s'))));;
				$roleComplete = $this->AdmUser->AdmNodesRolesUser->find('count', array('conditions'=> array('AdmNodesRolesUser.adm_user_id'=>$userInfo['id'], 'AdmNodesRolesUser.active_date >'=>date('Y-m-d H:i:s'), 'AdmNodesRolesUser.active'=>1)));;
				$error=0;
				
				//User active
				if($active != 1){
					$this->Session->setFlash(
							__('El usuario esta inactivo'),
							'alert',
							array(
								'plugin' => 'TwitterBootstrap',
								'class' => 'alert-error'
							)
					);
					$this->redirect($this->Auth->logout());
					$error++; 
				}
				
				//User date active
				if($activeDate == 0){
					$this->Session->setFlash(__('El usuario expiró'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
					);
					$this->redirect($this->Auth->logout());
					$error++;
				}
				
				//Roles Validation
				if ($role == 0){//No roles found
					$this->Session->setFlash(__('El usuario no tiene ningun rol asignado'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
					);
					$this->redirect($this->Auth->logout());
					$error++;
				}elseif($role == 1){//One role found
					if($roleActive == 0){
						$this->Session->setFlash(__('El rol del usuario esta inactivo'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
						);
						$this->redirect($this->Auth->logout());
						$error++;
					}else{
						if($roleActiveDate == 0){
							$this->Session->setFlash(__('El rol del usuario expiró'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
							);
							$this->redirect($this->Auth->logout());
							$error++;
						}
					}
				}else{//More than one role found			
					if($roleComplete == 0){//No complete role(active and active date), but I don't know which one is missing so..
						//Must check FIRST if there is an active role THEN if there is an active date
						if($roleActive == 0){//No active role
							if($roleActiveDate > 0){//At least one of those roles has an active date						
								$this->redirect(array('action' => 'choose_role')); //So the user can choose one in a view called "choose_role" and go on
								$error++;
							}else{
								$this->Session->setFlash(__('Los roles de usuario estan inactivos y expiraron'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
								);
								$this->redirect($this->Auth->logout());
								$error++;
							}
						}else{//one active role
							if($roleActiveDate > 0){//At least one of those roles has an active date						
								$this->redirect(array('action' => 'choose_role')); //So the user can choose one in a view called "choose_role" and go on
								$error++;
							}else{
								$this->Session->setFlash(__('Los roles de usuario expiraron'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
								);
								$this->redirect($this->Auth->logout());
								$error++;
							}
						}
					}
				}
				///////////////////////END OF VALIDATION/////////////////////////
				if($error == 0){
					////////Fill of sessions distinct to auth component users table
					
					$infoRole = $this->AdmUser->AdmNodesRolesUser->find('all', array('fields'=>array('AdmRole.name','AdmRole.id'),'conditions'=>array('AdmNodesRolesUser.adm_user_id'=>$userInfo['id'], 'AdmNodesRolesUser.active'=>1)));
					
					
					$this->Session->write('Role.name', $infoRole[0]['AdmRole']['name']);
					$this->Session->write('Role.id', $infoRole[0]['AdmRole']['id']);
					$this->Session->write('Menu', $this->_createMenu($this->Session->read('Role.id')));
					$this->Session->write('Period.year', $this->_findPeriod($userInfo['id'], $this->Session->read('Role.id')));
					$this->_createPermissions($this->Session->read('Role.id'));
					
					
					/*
					$this->Session->write('Role.name', '<ul><li><a href="add">Haga click my friend</a></li></ul>');
					$this->Session->write('Role.name2', '<ul><li><a href="/admin/admUsers/add">Haga click my friend2</a></li></ul>');
					$this->Session->write('Role.name3', '<ul><li><a href="add">Haga click my friend3</a></li></ul>');
					*/
					//////////////////////////////////////////////////////////////////////////
					
					$this->redirect($this->Auth->redirect());//IN CASE OF NO ERRORS PROCEED TO LOGIN
					
				} 
			} else {
				$this->Session->setFlash(__('Usuario o contraseña incorrecta, intente de nuevo'),
											'alert',
											array(
												'plugin' => 'TwitterBootstrap',
												'class' => 'alert-error'
											)	
				);
			}
		}
	}
	
	public function choose_role(){
		echo "Aqui va para elegir roles con fecha activa en caso de que un rol este inactivo y tenga otros roles";
	}
	
	private function _createMenu($roleId){
		$this->loadModel('AdmRolesMenu');
		//$this->loadModel('AdmModule');
		
		
		//$modules = $this->AdmModule->find('list');
		
		/*
		$parents = array();
		$cont = 0;
		foreach ($modules as $key => $value) {
			//$parents[$value] = $this->AdmRolesMenu->AdmMenu->find('all', array('fields'=>array('AdmMenu.id', 'AdmMenu.id') , 'order'=>array('AdmMenu.order_menu') ,'conditions'=>array("AdmMenu.parent_node"=>null, 'AdmMenu.adm_module_id'=>$key, 'AdmRolesMenu.adm_role_id'=>1)));
			$children = $this->AdmRolesMenu->find('all', array('fields'=>array('AdmMenu.id', 'AdmMenu.name'),'conditions'=>array('AdmRolesMenu.adm_role_id'=>$roleId, 'AdmMenu.adm_module_id'=>$key, "AdmMenu.parent_node"=>null), 'order'=>array('AdmMenu.order_menu')));
			if(count($children) > 0 ){ // to check if module is empty and exclude it
			$parents[$value]= $children;
			}
			$cont++;
		}
		 * 
		 */
		$parents = $this->AdmRolesMenu->find('all', array(
			'fields'=>array('AdmMenu.id', 'AdmMenu.name')
			,'conditions'=>array('AdmRolesMenu.adm_role_id'=>$roleId, "AdmMenu.parent_node"=>null, 'AdmMenu.inside'=>null)
			, 'order'=>array('AdmMenu.order_menu')
		));
		
		
		//debug($parents);
		
		/////////////////////////////////////////////////////////////////////
		$str = '';
		//$str.= '<ul>';
		//foreach($parents as $key => $var){
			//$str.='<li><a href="#">'.strtoupper($key).'</a>';
			//////////////////////////////Parents
				$str.='<ul>';
				foreach ($parents as $key2 => $value2) {
					$arrLinkContent = $this->_createLink($value2['AdmMenu']['id'], $value2['AdmMenu']['name'], 'SI');
						if($arrLinkContent['idForLi'] <> ''){$idForLi = 'id="'.$arrLinkContent['idForLi'].'"';}else{$idForLi='';}
							$str.='<li '.$idForLi.' class="submenu">'.$arrLinkContent['link'];//$value2['AdmMenu']['name'];
					////////////////////////////////////Children 1
					$str.='<ul>';
						$children1 = $this->_findMenus($value2['AdmMenu']['id'], $roleId);
						foreach ($children1 as $key3 => $value3) {
							$arrLinkContent = $this->_createLink($key3, $value3, '');
							if($arrLinkContent['idForLi'] <> ''){$idForLi = 'id="'.$arrLinkContent['idForLi'].'"';}else{$idForLi='';}
							$str.='<li '.$idForLi.'>'.$arrLinkContent['link'];//$value3;
								/*
								////////////////////////////////////Children 2
								$str.='<ul>';
									$children2=$this->_findMenus($key3, $roleId);
									foreach ($children2 as $key4 => $value4) {
										$str.='<li>'.$this->_createLink($key4, $value4);//$value4;
										////////////////////////////////////Children 3
										$str.='<ul>';
											$children3=$this->_findMenus($key4, $roleId);
											foreach ($children3 as $key5 => $value5) {
												$str.='<li>'.$this->_createLink($key5, $value5);//$value5;
												////////////////////////////////////Children 4
												$str.='<ul>';
												$children4=$this->_findMenus($key5, $roleId);
												foreach ($children4 as $key6 => $value6) {
													$str.='<li>'.$this->_createLink($key6, $value6);//$value6;
													$str.='</li>';
												}
												$str.='</ul>';
												////////////////////////////////////Children 4
												$str.='</li>';
											}
										$str.='</ul>';
										////////////////////////////////////Children 3
										$str.='</li>';
									}
								$str.='</ul>';
								////////////////////////////////////Children 2
								*/
							$str.='</li>';
						}
					$str.='</ul>';
					////////////////////////////////////Children 1
					$str.='</li>';
				}
				$str.='</ul>';
			//////////////////////////////Parents
			//$str.='</li>';
		//}
		//$str.= '</ul>';
		
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
	
	private function _findPeriod($user, $role){
		$this->loadModel('AdmNodesRolesUser');	
		$arrayNode = $this->AdmNodesRolesUser->find('all', array(
			'conditions'=>array('AdmNodesRolesUser.adm_user_id'=>$user, 'AdmNodesRolesUser.adm_role_id'=>$role),
			'fields'=>array('AdmNodesRolesUser.adm_node_id')
		));
		$nodeId = $arrayNode[0]['AdmNodesRolesUser']['adm_node_id'];
		$this->loadModel('AdmNode');	
		$arrayPeriod = $this->AdmNode->find('all', array(
			'conditions'=>array('AdmNode.id'=>$nodeId)
			,'fields'=>array('AdmPeriod.year')
		));
		$year = $arrayPeriod[0]['AdmPeriod']['year'];
		return $year;
	}
	
	public function welcome(){
		
		
		//$this->_createPermissions($this->Session->read('Role.id'));
		//debug($infoGestion = $this->AdmUser->AdmNodesRolesUser->AdmNode->AdmJobTitle->find('all')); //prueba para gestion
		//$infoRole = $this->AdmUser->AdmNodesRolesUser->find('all', array('fields'=>array('AdmRole.name','AdmRole.id'),'conditions'=>array('AdmNodesRolesUser.adm_user_id'=>1, 'AdmNodesRolesUser.active'=>1)));
		//debug($infoRole);
		
		//$prueba = $this->AdmUser->AdmJobTitle->AdmNode->find('all', array('conditions'=>array('AdmNodesRolesUser.'=>1)));
		//debug($prueba);
		
		//$this->_createPermissions($this->Session->read('Role.id'));
		/*
		echo "aaaaaaaaa";
		echo $this->name;
		echo $this->Session->read('Permission.AdmModules.index');
		*/
		/*
		$array=$this->Session->read('Permission.'.$this->name);
		echo count($array);
		debug($array);
		if(count($array) > 0){
			foreach ($array as $key => $value) {
				echo $value;
			}
		}
		*/
		//$this->_createMenu();
		/*
		$this->loadModel('AdmRolesMenu');
		$children = $this->AdmRolesMenu->find('all', array('fields'=>array('AdmMenu.id', 'AdmMenu.name'),'conditions'=>array('AdmRolesMenu.adm_role_id'=>1, "AdmMenu.parent_node"=>null), 'order'=>array('AdmMenu.order_menu')));
		debug($children);
		 */
		//$userInfo = $this->Auth->user();
		//debug($this->AdmUser->AdmNodesRolesUser->find('all', array('fields'=>array('AdmRole.name'/*, 'AdmNode.adm_period_id'*/),'conditions'=>array('AdmNodesRolesUser.adm_user_id'=>$userInfo['id'], 'AdmNodesRolesUser.active'=>1))));
		/*
		$this->loadModel('AdmRolesMenu');
		$this->loadModel('AdmModule');
		
		$modules = $this->AdmModule->find('list');
		
		$parents = array();
		$cont = 0;
		foreach ($modules as $key => $value) {
			//$parents[$value] = $this->AdmRolesMenu->AdmMenu->find('all', array('fields'=>array('AdmMenu.id', 'AdmMenu.id') , 'order'=>array('AdmMenu.order_menu') ,'conditions'=>array("AdmMenu.parent_node"=>null, 'AdmMenu.adm_module_id'=>$key, 'AdmRolesMenu.adm_role_id'=>1)));
			$children = $this->AdmRolesMenu->find('all', array('fields'=>array('AdmMenu.id', 'AdmMenu.name'),'conditions'=>array('AdmRolesMenu.adm_role_id'=>1, 'AdmMenu.adm_module_id'=>$key, "AdmMenu.parent_node"=>null), 'order'=>array('AdmMenu.order_menu')));
			if(count($children) > 0 ){ // to check if module is empty and exclude it
			$parents[$value]= $children;
			}
			$cont++;
		}
		
		debug($parents);
		*/
	}
	

	public function logout() {
		$this->Session->destroy();
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
		$admJobTitles = $this->AdmUser->AdmJobTitle->find('list');
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


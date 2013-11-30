<?php
App::uses('AppController', 'Controller');
/**
 * AdmActions Controller
 *
 * @property AdmAction $AdmAction
 */
class AdmActionsController extends AppController {

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
//	public $helpers = array('Js','TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
//	public $components = array('RequestHandler','Session');
	
	
	//public  function isAuthorized($user){
		/*
		if(!$this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name))){
			$this->redirect($this->Auth->logout());
		}
		return true;
		 */
	//	return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));

//	}
	
	public function beforeFilter(){
////		$this->getData();
		parent::beforeFilter();
//		if(!isset($_SESSION)) session_start(); //If session didn't start, then start it
//		$this->changeDB();
		$this->getData();
	}
	
	public function changeDB(){
//				// Getting the datasource cache in the ConnectionManager object
//		$connectionManagerInstance = ConnectionManager::getInstance();
//		$databaseConfig = &$connectionManagerInstance->_dataSources;
//
//// Saving the initial setting for reverting it later
//		$_defaultConfig = $databaseConfig['default'];
//
//// Changing the password so the credentials will fail
//		$databaseConfig['default']->config['password'].= 'pass';
//
//// Getting the updated datasource
//		$connect = ConnectionManager::getDataSource('default');
//		@App::import('Model', 'ConnectionManager'); //igual sirve
		
		$config = array(
		'datasource' => 'Database/Postgres',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'tester',
		'password' => 'mierda',
		'database' => 'imexport',
		'prefix' => '',
		'schema'=>'public'
		//'encoding' => 'utf8',
		);
		
		App::uses('ConnectionManager', 'Model');
		
		ConnectionManager::create('default', $config);
//		debug($change->config['login']);
		
//		$dataSource = ConnectionManager::getDataSource('default');
		
//		 $dataSource->config['login'] = 'tester';
//		$dataSource->config['password'] = 'xxjdsjd';
		
//		ConnectionManager::
//		debug( $dataSource);
//		debug( $dataSource->config['login']);
	}
	
	public function getData(){
		App::uses('ConnectionManager', 'Model');
		$dataSource = ConnectionManager::getDataSource('default');
		debug( $dataSource->config['login']);
	}

	/**
 * index method
 *
 * @return void
 */
	public function index() {
////		$this->getData();
////		$this->changeDB();
//		$this->getData();
//		$auth = $this->Auth->user(); 
//		debug($auth);
////		$encrypt = AuthComponent::password($auth['login']);
////		$encrypt = Security::rijndael($auth['login'], '495d654f495d654f495d654f495d654f', 'encrypt');
//		$encrypt = $this->BittionSecurity->encryptUserSessionPassword($auth['login']);
//		
//		$this->Session->write('prueba.encrypt', $encrypt);
//		$session = $this->Session->read('prueba.encrypt');
//		debug($session);
////		debug($encrypt);
////		$decrypt =  Security::rijndael($session, '495d654f495d654f495d654f495d654f', 'decrypt');
//		$decrypt = $this->BittionSecurity->decryptUserSessionPassword($session);
////		Security::rijndael
//		debug($decrypt);
//		
		/*$myQuery =*/// $this->AdmAction->myQuery();
////		debug($myQuery);
		
		$this->AdmAction->recursive = 0;
		 $this->paginate = array(
			'order'=>array('AdmController.name'=>'asc'),
			'limit' => 20
		);
		 
		 $array =$this->paginate();
		 //debug($array);
		 
		 //I loop in the called query, and modify each field which has a parent. There must be a better solution with subquery I think
		 /*
		 foreach ($array as $key => $value) {
			 //$value['AdmAction']['parent2'] = "vacio";
			 $parentId = $value['AdmAction']['parent'];
			 if($parentId != null){
				 $parentName = $this->AdmAction->find('all', array(
					 'conditions'=> array('AdmAction.id'=>$parentId),
					 'fields'=>array('AdmAction.name', 'AdmController.name')
				));
				 //here I change the parent value for a string, the rest still null
				 $array[$key]['AdmAction']['parent'] = $parentName[0]['AdmController']['name'].'->'.$parentName[0]['AdmAction']['name'];
			 }
		 }
		 */
		 //$array[0]['AdmAction']['orco'] = '333333';
		// debug($array);
		$this->set('admActions', $array);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmAction->create();
			// lo convierto a mayuscula tenga mismo formato que DB
			$this->request->data['AdmAction']['name'] = strtoupper($this->request->data['AdmAction']['name']);
			//debug($this->request->data);
			///
			if ($this->AdmAction->save($this->request->data)) {
				$this->Session->setFlash(
					__('Acción creada con exito'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(
					__('Ocurrio un problema, intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
				$this->redirect(array('action' => 'add'));
			}
			
			/////
		}
		//////////////////////////////////////////////////////////////
		$admModules = $this->AdmAction->AdmController->AdmModule->find('list', array('order'=>'AdmModule.id'));
		$initialModule =  key($admModules);
		$admControllers = $this->AdmAction->AdmController->find('list', array(
			'conditions'=>array('AdmController.adm_module_id'=>$initialModule),
			'order'=>array('AdmController.name'=>'ASC')
		));
		
		//$initialController = Inflector::camelize(reset($admControllers));
		//$idController = key($admControllers);
		//$admActions = $this->_getActions($initialController, $idController);
		if(count($admControllers) == 0){
				$admControllers[""]="--- Vacio ---";
				$admActions = array();
		}else{
				$initialController = Inflector::camelize(reset($admControllers));
				$idController = key($admControllers);
				$admActions = $this->_getActions($initialController, $idController);
		}
		
		if(count($admActions) == 0){$admActions[""]="--- Vacio ---";}

		$this->set(compact('admControllers','admModules', 'admActions'));
		
		///////////
			
		
	}

	
	
	private function _getActions($initialController, $idController){
		//$initialController = is the name of the controller
		//APP
        App::import('Controller', $initialController);
		$parentClassMethods = get_class_methods(get_parent_class(Inflector::camelize($initialController).'Controller'));
        //debug($parentClassMethods);
        $subClassMethods    = get_class_methods(Inflector::camelize($initialController).'Controller');
//		debug($subClassMethods);
//		debug($parentClassMethods);
		$classMethods = array();
		if($subClassMethods <> null and $parentClassMethods <> null) $classMethods = array_diff($subClassMethods, $parentClassMethods);
        
//		debug($parentClassMethods);
		
		if(count($classMethods) > 0){
			$appActions = array();
			foreach ($classMethods as $value) {
				if (strtolower(substr($value, 0, 4)) <> 'ajax') {
					if (substr($value, 0, 1) <> '_') {
						$appActions[$value] = $value;
					}
				}
			}

			//DB
			$dbActions = $this->AdmAction->find('all', array(
				'recursive' => 0,
				'fields' => array('AdmAction.name'),
				'conditions' => array('AdmAction.adm_controller_id' => $idController),
				'order' => array('AdmAction.name' => 'ASC')
			));
			$formatDbActions = array();
			foreach ($dbActions as $key => $value) {
				$formatDbActions[$key] = strtolower($value['AdmAction']['name']);
			}
			//debug(array_diff($appActions, $formatDbActions));
			//debug($formatDbActions);
			//debug($appActions);
			return array_diff($appActions, $formatDbActions);
		}
		
		return array();
	}
	
	private function _getActionsAjax($initialController, $idController, $idAction){
		//$miVar = 'AdmActionsRoles';
		//APP
        App::import('Controller', $initialController);
		$parentClassMethods = get_class_methods(get_parent_class(Inflector::camelize($initialController).'Controller'));
        //debug($parentClassMethods);
        $subClassMethods    = get_class_methods(Inflector::camelize($initialController).'Controller');
        $classMethods       = array_diff($subClassMethods, $parentClassMethods);
		$appActions=array();
		foreach ($classMethods as $value) {
			if(strtolower(substr($value, 0, 4)) == 'ajax'){
				if(substr($value, 0, 1) <> '_'){ 
					$appActions[$value]=$value;
				}
			}
		}

		//DB
		$dbActions = $this->AdmAction->find('all', array('recursive'=>0, 'fields'=>array('AdmAction.name'), 'conditions'=>array('AdmAction.parent'=>$idAction,'AdmAction.adm_controller_id'=>$idController)));
		$formatDbActions = array();
		foreach ($dbActions as $key => $value) {
			$formatDbActions[$key] = strtolower($value['AdmAction']['name']);
		}
		return array_diff($appActions, $formatDbActions);
	}
	
	
	public function ajax_list_controllers(){
		if($this->RequestHandler->isAjax()){
			//debug($this->request->data);
			$initialModule =  $this->request->data['module'];
			$admControllers = $this->AdmAction->AdmController->find('list', array(
				'conditions'=>array('AdmController.adm_module_id'=>$initialModule),
				'order'=>array('AdmController.name'=>'ASC')
			));
			//debug($admControllers);
			if(count($admControllers) == 0){
				$admControllers[""]="--- Vacio ---";
				$admActions = array();
			}else{
				$initialController = Inflector::camelize(reset($admControllers));
				$idController = key($admControllers);
				$admActions = $this->_getActions($initialController, $idController);
			}
			//$initialController = strtolower($this->request->data['controllerName']);
			//$idController = $this->request->data['controllerId'];
			
			if(count($admActions) == 0){$admActions[""]="--- Vacio ---";}
			$html = '';
		$this->set(compact('admControllers','admModules', 'admActions', 'html'));
			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	
	public function ajax_list_actions(){
		if($this->RequestHandler->isAjax()){
			//debug($this->request->data);
			$initialController = strtolower($this->request->data['controllerName']);
			$idController = $this->request->data['controllerId'];
			$admActions = $this->_getActions($initialController, $idController);
			if(count($admActions) == 0){$admActions[""]="--- Vacio ---";}
			$this->set(compact('admActions'));
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	
/** 
 * 
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
//		$this->changeDB();
//		$this->getData();
		$this->AdmAction->id = $id;
		if (!$this->AdmAction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm action')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['AdmAction']['lc_transaction']='MODIFY';
			if ($this->AdmAction->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm action')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm action')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmAction->read(null, $id);
		}
		$admControllers = $this->AdmAction->AdmController->find('list');
		$this->set(compact('admControllers'));
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
		$this->AdmAction->id = $id;
		if (!$this->AdmAction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm action')));
		}
		
		//verify if exist child
		//$child = $this->AdmAction->find('count', array('conditions'=>array("AdmAction.parent"=>$id)));
		$child = $this->AdmAction->AdmMenu->find('count', array('conditions'=>array("AdmMenu.adm_action_id"=>$id)));
		if($child > 0){
			$this->Session->setFlash(
				__('Tiene hijos no se puede eliminar'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		///////////////
		
		
		if ($this->AdmAction->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm action')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm action')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}

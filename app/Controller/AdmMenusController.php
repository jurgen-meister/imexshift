<?php
App::uses('AppController', 'Controller');
/**
 * AdmMenus Controller
 *
 * @property AdmMenu $AdmMenu
 */
class AdmMenusController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
//	public $layout = 'default';

/**
 * Helpers
 *
 * @var array
 */
//	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
//	public $components = array('Session');
	/*
	public  function isAuthorized($user){
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
	 * 
	 */
	
	public function index_out(){
		$modules = $this->AdmMenu->AdmModule->find('list');
		if ($this->request->is('post')) {
			$idModule = $this->request->data['formAdmMenuIndexOut']['modules'];
		}else{
			$idModule = key($modules);
		}
		
		$this->AdmMenu->unbindModel(array(
			'hasMany' => array('AdmRolesMenu')
		));
		 
		$this->AdmMenu->bindModel(array(
			'hasOne'=>array(
				'AdmController'=> array(
					'foreignKey' => false,
					'conditions' => array('AdmAction.adm_controller_id = AdmController.id')
				)
			)
		));
		$filters = '';
		$this->paginate = array(
			'conditions'=>array(
				$filters
			 ),
			'conditions'=>array('AdmMenu.inside'=>null, 'AdmMenu.adm_module_id'=>$idModule),
			'order'=>array('AdmMenu.parent_node'=>'desc', 'AdmMenu.order_menu'=>'asc'),
			'limit' => 50,
		);
		$this->set('admMenus', $this->paginate('AdmMenu'));
		$this->set(compact('modules'));
		//debug($this->paginate('AdmMenu')); //IMPORTANT.- this debug is not capturing de bind and unbind, but is working. Is better if I put it inside an array then do debug
	}

	
	public function index_inside(){
		$modules = $this->AdmMenu->AdmModule->find('list');
		if ($this->request->is('post')) {
			$idModule = $this->request->data['formAdmMenuIndexOut']['modules'];
		}else{
			$idModule = key($modules);
		}
		
		$this->AdmMenu->unbindModel(array(
			'hasMany' => array('AdmRolesMenu')
		));
		 
		$this->AdmMenu->bindModel(array(
			'hasOne'=>array(
				'AdmController'=> array(
					'foreignKey' => false,
					'conditions' => array('AdmAction.adm_controller_id = AdmController.id')
				)
			)
		));
		$filters = '';
		$this->paginate = array(
			'conditions'=>array(
				$filters
			 ),
			'conditions'=>array('AdmMenu.inside'=>1, 'AdmMenu.adm_module_id'=>$idModule),
			'order'=>array('AdmController.name'=>'ASC'),
			'limit' => 50,
		);
		$this->set('admMenus', $this->paginate('AdmMenu'));
		$this->set(compact('modules'));
		//debug($this->paginate('AdmMenu')); //IMPORTANT.- this debug is not capturing de bind and unbind, but is working. Is better if I put it inside an array then do debug
	}
	
	
/**
 * add method
 *
 * @return void
 */
	public function add_out() {
		if ($this->request->is('post')) {
			/////////////
			$this->AdmMenu->create();
			If($this->request->data['AdmMenu']['adm_action_id'] == 0){
				unset($this->request->data['AdmMenu']['adm_action_id']);
			}
			If($this->request->data['AdmMenu']['parent_node'] == 0){
				unset($this->request->data['AdmMenu']['parent_node']);
			}
			if ($this->AdmMenu->save($this->request->data)) {
				$this->Session->setFlash(
					__('Se creo correctamente'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index_out'));
			} else {
				$this->Session->setFlash(
					__('Ocurrio un problema intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
			///////////////	
		}
		$admModules = $this->AdmMenu->AdmModule->find('list');
		$module = key($admModules);
		$admActions = $this->_list_actions($module);
		//$admMenus = $this->AdmMenu->find('list', array("conditions"=>array("AdmMenu.adm_module_id"=>$module)));
		$admMenus = $this->AdmMenu->find('list', array(
				'conditions'=>array('AdmMenu.adm_module_id'=>$module, 'AdmMenu.inside'=>null),
				'order'=>array('AdmMenu.parent_node'=>'DESC')
		));
		$admMenus[0] = "Ninguno";
		$this->set(compact('admModules', 'admActions', 'admMenus'));
	}
	
	public function add_inside(){
		if ($this->request->is('post')) {
			/////////////
			$this->AdmMenu->create();
			$this->request->data['AdmMenu']['inside'] = 1;
			$this->request->data['AdmMenu']['order_menu'] = 0;
			//$this->request->data['AdmMenu']['name'] = 'interno';
			//debug($this->request->data);
			
			if ($this->AdmMenu->save($this->request->data)) {
				$this->Session->setFlash(
					__('Menu/Permiso Interno creado con exito'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index_inside'));
			} else {
				$this->Session->setFlash(
					__('Ocurrio un problema, vuelva a intentarlo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
			 
			///////////////	
		}
		
		$admModules = $this->AdmMenu->AdmModule->find('list');
		$module = key($admModules);
		$admControllers = $this->AdmMenu->AdmAction->AdmController->find('list', array(
			'conditions'=>array('AdmController.adm_module_id'=>$module),
			'order'=>array('AdmController.name'=>'ASC')
		));
		$controller = key($admControllers);
		//$admActions = $this->AdmMenu->AdmAction->find('list', array('conditions'=>array('AdmAction.adm_controller_id'=>$controller)));
		$admActions = $this->_list_action_inside($controller);		

		$this->set(compact('admModules', 'admActions', 'admControllers'));
	}
	
	public function edit_inside($id = null){
		$this->AdmMenu->id = $id;
		if (!$this->AdmMenu->exists()) {
			throw new NotFoundException('Invalido');
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {

			//$this->request->data['AdmMenu']['lc_transaction'] = 'MODIFY';
			$this->request->data['AdmMenu']['inside'] = 1;
			$this->request->data['AdmMenu']['order_menu'] = 0;
			
			
			//debug($this->request->data);
			if ($this->AdmMenu->save($this->request->data)) {
				$this->Session->setFlash(
					__('Cambios guardados'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index_inside'));
			} else {
				$this->Session->setFlash(
					__('Ocurrio un problema, intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
				$this->redirect(array('action' => 'index_inside'));
			}
			
		} else {
			$this->request->data = $this->AdmMenu->read(null, $id);
			$admModules = $this->AdmMenu->AdmModule->find('list');
			$module = $this->request->data['AdmMenu']['adm_module_id'];
			$admActions = $this->_list_actions($module);
			$action = $this->request->data['AdmMenu']['adm_action_id'];
			$this->set(compact('admModules','admActions', 'module', 'action'));
		}
		
	}

	

	private function _list_actions($module){
		$admAct = $this->AdmMenu->AdmAction->find('all', array(
			'recursive'=>1, 
			'conditions'=>array('AdmController.adm_module_id'=>$module),
			'fields'=>array('AdmAction.id', 'AdmAction.name', 'AdmController.name'),
			'order'=>array('AdmController.name'=>'ASC')
			));
		$admActions = array();
		//if(count($admAct) > 0){
			foreach($admAct as $var){
				$admActions[$var["AdmAction"]["id"]] = $var["AdmController"]["name"] . "->" . $var["AdmAction"]["name"];
			}
			$admActions[0] = "Ninguno";
		//}
		return $admActions;
	}
	
	private function _list_action_inside($controller){
		$admActions = $this->AdmMenu->AdmAction->find('list', array(
			'conditions'=>array('AdmAction.adm_controller_id'=>$controller, 'AdmAction.parent'=>null)
		));
		$formatedAdmAction = array();
		//echo "actions guardadas";
		//debug($admActions);
		if(count($admActions) > 0){
			foreach ($admActions as $key => $value) {
				$formatedAdmAction[$key] = $key;
			}
		}
		//echo "action guardas puestas en numeros";
		//debug($formatedAdmAction);
		
		//OPC 1 muestra todo //creo sirve para configurar menus internos como de amauta
		//echo "actions guardadas en menus que sean inside";//con esto se diferencia solo las actions inside
		$admSavedActions = $this->AdmMenu->find('all', array('fields'=>array('AdmMenu.adm_action_id','AdmAction.name'),'conditions'=>array('AdmMenu.adm_action_id'=>$formatedAdmAction, 'AdmMenu.inside'=>1))); 
		
		//OPC 2 muestra lo que no esta guardado //muestra info mas corta, pero si alguien mete uno que no sea interno ya no aparecera
		//echo "actions guardadas en menus sin diferenciar"; //con esto se diferencia todas las actions guardadas
		//$admSavedActions = $this->AdmMenu->find('all', array('fields'=>array('AdmMenu.adm_action_id','AdmAction.name'),'conditions'=>array('AdmMenu.adm_action_id'=>$formatedAdmAction))); 
		
		
		$saved=array();
		foreach ($admSavedActions as $key => $value) {
			$saved[$value['AdmMenu']['adm_action_id']]=$value['AdmAction']['name'];
		}
		
		//debug($saved);
		$diff = array_diff($admActions, $saved);
		//echo "La diferencia entre guardada action y guardada menu";
		//debug($diff);
		//$admControllers[""]="--- Vacio ---"; //must fix something like that
		return $diff;
	}
	
	public function ajax_list_controllers_inside(){
		if($this->RequestHandler->isAjax()){
			$module = $this->request->data['module'];
			$admControllers = $this->AdmMenu->AdmAction->AdmController->find('list', array(
				'conditions'=>array('AdmController.adm_module_id'=>$module),
				'order'=>array('AdmController.name'=>'ASC')
			));
			$controller = key($admControllers);
			//$admActions = $this->AdmMenu->AdmAction->find('list', array('conditions'=>array('AdmAction.adm_controller_id'=>$controller)));
			$admActions = $this->_list_action_inside($controller);
			$this->set(compact('admControllers','admActions'));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}

	public function ajax_list_actions_inside(){
		if($this->RequestHandler->isAjax()){
			$controller = $this->request->data['controller'];
			//$admActions = $this->AdmMenu->AdmAction->find('list', array('conditions'=>array('AdmAction.adm_controller_id'=>$controller)));
			$admActions = $this->_list_action_inside($controller);
			$this->set(compact('admActions'));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}

	
	public function ajax_list_actions_out(){
		if($this->RequestHandler->isAjax()){
			$module = $this->request->data['module'];
			$admActions = $this->_list_actions($module);
			$admMenus = $this->AdmMenu->find('list', array(
				'conditions'=>array('AdmMenu.adm_module_id'=>$module, 'AdmMenu.inside'=>null),
				'order'=>array('AdmMenu.parent_node'=>'DESC')
			));
			$admMenus[0] = "Ninguno";
			$this->set(compact('admActions', 'admMenus'));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit_out($id = null) {
		$this->AdmMenu->id = $id;
		if (!$this->AdmMenu->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm menu')));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {

			If($this->request->data['AdmMenu']['adm_action_id'] == 0){
				//hay habilitar allowEmpty en el modelo para guardar con null, para update no hay otra
				$this->request->data['AdmMenu']['adm_action_id'] = null;
			}
			If($this->request->data['AdmMenu']['parent_node'] == 0){
				//unset($this->request->data['AdmMenu']['parent_node']);
				$this->request->data['AdmMenu']['parent_node'] = null;
			}
			/*
			If($this->request->data['AdmMenu']['inside'] == 0){
				$this->request->data['AdmMenu']['inside'] = null;
			}
			 */
			$this->request->data['AdmMenu']['lc_transaction'] = 'MODIFY';
			//debug($this->request->data);
			
			if ($this->AdmMenu->save($this->request->data)) {
				$this->Session->setFlash(
					__('Se guardo correctamente'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index_out'));
			} else {
				$this->Session->setFlash(
					__('Ocurrio un problema intentelo de nuevo'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
				
		} else {
			$this->request->data = $this->AdmMenu->read(null, $id);
		}
    	
		
		
		//////////////////// Fill edit.ctp
		///Fix Null values in dropdownlist
		if($this->request->data['AdmMenu']['adm_action_id'] == null){
			$this->request->data['AdmMenu']['adm_action_id'] = 0;
		}
		if($this->request->data['AdmMenu']['parent_node'] == null){
			$this->request->data['AdmMenu']['adm_menu_id'] = 0;
		}else{
			//para que reconozca el valor parent_node al inicio update
			$this->request->data['AdmMenu']['adm_menu_id'] = $this->request->data['AdmMenu']['parent_node']; 
		}
	
		$admModules = $this->AdmMenu->AdmModule->find('list');
		$module = $this->request->data['AdmMenu']['adm_module_id'];
		$admActions = $this->_list_actions($module);
		
		//***************************************************+*****//
		//no debe mostrar al hijo del hijo del hijo
		$childrenAux = $this->AdmMenu->find('list', array('fields'=>array('AdmMenu.id', 'AdmMenu.id') ,'conditions'=>array("AdmMenu.parent_node"=>$id)));

		if(count($childrenAux)>0){ //fix bug last child
			$children = array_merge(array(intval($id)), $childrenAux); //array();
			do{
				$childrenAux = $this->AdmMenu->find('list', array('fields'=>array('AdmMenu.id', 'AdmMenu.id') ,'conditions'=>array("AdmMenu.parent_node"=>$childrenAux)));
				$children = array_merge($children, (array)$childrenAux);
			}while(count($childrenAux) > 0);
		}else{
			$children = intval($id);
		}
		
		//debug($children);
		$admMenus = $this->AdmMenu->find('list', array("conditions"=>array("AdmMenu.adm_module_id"=>$module, "NOT"=>array("AdmMenu.id"=>$children))));
		$admMenus[0] = "Ninguno";
		//$insides = array("No","Si");
		//$this->set(compact('admModules', 'admActions', 'admMenus', 'insides'));
		$this->set(compact('admModules', 'admActions', 'admMenus'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete_out($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->AdmMenu->id = $id;
		if (!$this->AdmMenu->exists()) {
			throw new NotFoundException('Menu invalido');
		}
		//verify if exist child
		$child = $this->AdmMenu->find('count', array('conditions'=>array("AdmMenu.parent_node"=>$id)));
		if($child > 0){
			$this->Session->setFlash(
				__('Tiene hijos no se puede eliminar'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index_out'));
		}
		/////////////////////////////////////////////////////////
		try{
			$this->AdmMenu->delete();
			$this->Session->setFlash(
				__('Se elimino correctamente'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index_out'));
		}catch(Exception $e){
			if($e->getCode() == 23503){
				$msge = 'No se puede eliminar este Menu porque tiene Roles asignados';
			}else{
				$msge = 'Ocurrio un problema vuelva a intentarlo';
			}
			$this->Session->setFlash(
			$msge,
			'alert',
			array('plugin' => 'TwitterBootstrap','class' => 'alert-error')
			);
			$this->redirect(array('action' => 'index_out'));
		}
	}
	
	public function delete_inside($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->AdmMenu->id = $id;
		if (!$this->AdmMenu->exists()) {
			throw new NotFoundException('Menu invalido');
		}
		//verify if exist child
		$child = $this->AdmMenu->find('count', array('conditions'=>array("AdmMenu.parent_node"=>$id)));
		if($child > 0){
			$this->Session->setFlash(
				__('Tiene hijos no se puede eliminar'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index_inside'));
		}
		/////////////////////////////////////////////////////////
		try{
			$this->AdmMenu->delete();
			$this->Session->setFlash(
				__('Se elimino correctamente'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index_inside'));
		}catch(Exception $e){
			if($e->getCode() == 23503){
				$msge = 'No se puede eliminar este Menu porque tiene Roles asignados';
			}else{
				$msge = 'Ocurrio un problema vuelva a intentarlo';
			}
			$this->Session->setFlash(
			$msge,
			'alert',
			array('plugin' => 'TwitterBootstrap','class' => 'alert-error')
			);
			$this->redirect(array('action' => 'index_inside'));
		}
	}
	
////////////////////////////////////////
}

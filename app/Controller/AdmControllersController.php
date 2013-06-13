<?php
App::uses('AppController', 'Controller');
/**
 * AdmControllers Controller
 *
 * @property AdmController $AdmController
 */
class AdmControllersController extends AppController {

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
	//me dio que no tiene sentido redeclarar porque ya esta en appControllers
	//public $helpers = array('Js','TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
	//public $components = array('RequestHandler','Session');  // lo puse en appController para que lo usen todos
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
		$this->AdmController->recursive = 0;
		 $this->paginate = array(
			'order'=>array('AdmController.name'=>'asc'),
			'limit' => 20
		);
		$this->set('admControllers', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		
		if ($this->request->is('post')) {
			$this->AdmController->create();
			///////////////
			$initials = $this->request->data['AdmController']['adm_module_id'];
			//busco el id por las initials, ya que de esa forma llamo los controllers de la app Ej:Adm
			$numericModuleId=$this->AdmController->AdmModule->find('all', array('fields'=>'id','recursive'=>-1, 'conditions'=>array('AdmModule.initials'=>  strtolower($initials))));
			//Coloco el id int en la data enviada para que no haya error	
			$this->request->data['AdmController']['adm_module_id'] = $numericModuleId[0]['AdmModule']['id'];
			///////////////

			//Save controller
			
			$this->AdmController->save($this->request->data);
			$idController = $this->AdmController->getInsertID();
			
			
			$admTransactions =array(
					array("adm_controller_id"=>$idController, "name"=>"CREATE", "description"=>"Record Creation", "sentence"=>"ADD"),
					array("adm_controller_id"=>$idController, "name"=>"MODIFY", "description"=>"Record Modification", "sentence"=>"EDIT"),
					array("adm_controller_id"=>$idController, "name"=>"ELIMINATE", "description"=>"Record Elimination", "sentence"=>"DELETE")
			);
			$this->AdmController->AdmTransaction->saveMany($admTransactions);
			
			$admStates =array(
					array("adm_controller_id"=>$idController, "name"=>"INITIAL", "description"=>"Initial state (Non-existent)"),
					array("adm_controller_id"=>$idController, "name"=>"ELABORATED", "description"=>"Elaborated state"),
					array("adm_controller_id"=>$idController, "name"=>"FINAL", "description"=>"Final state (Non-existent)")
			);
			$this->AdmController->AdmState->saveMany($admStates);
			
			
			$vector = $this->AdmController->AdmState->find('all', array("recursive"=>-1, "conditions"=>array("adm_controller_id"=>$idController), "fields"=>"id"));
			foreach ($vector as $key => $val){
				echo $val['AdmState']['id'];
				$vState[$key] = $val['AdmState']['id'];
			}
			//debug($vector);
			
			$vector2 = $this->AdmController->AdmTransaction->find('all', array("recursive"=>-1, "conditions"=>array("adm_controller_id"=>$idController), "fields"=>"id"));
			foreach ($vector2 as $key => $val){
				echo $val['AdmTransaction']['id'];
				$vTransaction[$key] = $val['AdmTransaction']['id'];
			}
			//debug($vector2);
			//debug($vTransaction);
			
			
			$admTransitions =array(
					array("adm_state_id"=>$vState[0], "adm_transaction_id"=>$vTransaction[0], "adm_final_state_id"=>$vState[1]),
					array("adm_state_id"=>$vState[1], "adm_transaction_id"=>$vTransaction[1], "adm_final_state_id"=>$vState[1]),
					array("adm_state_id"=>$vState[1], "adm_transaction_id"=>$vTransaction[2], "adm_final_state_id"=>$vState[2])
					
			);
			//$comprobado = $this->AdmController->AdmState->AdmTransition->saveMany($admTransitions);
			
			if ($this->AdmController->AdmState->AdmTransition->saveMany($admTransitions)) {
				//$idController = $this->AdmController->getInsertID();
				
			
				$this->Session->setFlash(
					__('Se creo el contralador con sus estados, transacciones y transiciones'),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm controller')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
			////////////////////
	  }
		
		$admModules = $this->AdmController->AdmModule->find('list', array('fields'=>array('initials', 'name'), 'order'=>'AdmModule.id'));
		
		$initialModule = strtolower(key($admModules)); //primera posicion del vector y muestra valor key de esa posicion
		////////

		///////
		$admControllers = $this->_getControllers($initialModule);
		if(count($admControllers) == 0){$admControllers[""]="--- Vacio ---";}
		

		$this->set(compact('admModules', 'admControllers'/*, 'checkedControllers'*/));
	}
	
	public function ajax_list_controllers(){
		if($this->RequestHandler->isAjax()){
			$initialModule = strtolower($this->request->data['module']);
			$admControllers = $this->_getControllers($initialModule);
			/*
			$catchCheckedControllers = $this->AdmController->find('all', array('recursive'=>0,'fields'=>array('AdmController.name'), 'conditions'=>array('AdmModule.initials'=>$initialModule)));
			$checkedControllers = array();
			foreach ($catchCheckedControllers as $key => $value) {
				$checkedControllers[$key] = $value['AdmController']['name'];
			}
			 */
			if(count($admControllers) == 0){$admControllers[""]="--- Vacio ---";}
			$this->set(compact('admControllers'/*, 'checkedControllers'*/));
		}else{
			$this->redirect($this->Auth->logout());
		}
	}

	private function _getControllers($initialModule){
		//Get all controllers from the APP except for plugins
		$array = App::objects('controller');
		$appControllers=array();
		//$cernir = array();
		foreach ($array as $value) {
			//if($value <> 'AppController'){
			//	if($value <> 'PagesController'){
					if(strtolower(substr($value, 0, 3)) == $initialModule){ //compara iniciales ej: adm(app) = adm(db)
						$clean = substr($value, 0, -10); //quito la palabra Controller del final del string
						$formatTrigger= strtolower(preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", "_", $clean));//underscore every capital letter, al formato trigger
						$appControllers[$formatTrigger] = $formatTrigger;
					}
				//}
			//}
		}
		
		//Get DB values and format them to compare to the App
		$dbControllers = $this->AdmController->find('all', array('recursive'=>0,'fields'=>array('AdmController.name'), 'conditions'=>array('AdmModule.initials'=>$initialModule)));
		$formatDbControllers = array();
		foreach ($dbControllers as $key => $value) {
			$formatDbControllers[$key] = $value['AdmController']['name'];
		}
		
		
		///////
		/*
		echo 'data base';
		debug($formatDbControllers);
		echo 'controladores cake';
		debug($controllers);
		$array2=array("AdmActions", "AdmControllers");
		echo 'la diferencia es';
		debug(array_diff($controllers, $formatDbControllers));
		 * 
		 */
		return array_diff($appControllers, $formatDbControllers); //comparo controllers de la aplicacion con los de la DB, solo devuelvo los que no estan registrados
		///////
		//return $controllers;
	}

	
	public function ajax_save(){
		//en este caso no sirve porque se debe ingresar otros datos mas
		/*
		if($this->RequestHandler->isAjax()){
			
			if(isset($this->request->data['controller'])){
					$new = $this->request->data['controller']; 
				}else{
					$new = array();
			}
			
			debug($new);
			
			
			
			$initialModule = strtolower($this->request->data['module']);
			$catchCheckedControllers = $this->AdmController->find('all', array('recursive'=>0,'fields'=>array('AdmController.name'), 'conditions'=>array('AdmModule.initials'=>$initialModule)));
			$old = array();
			foreach ($catchCheckedControllers as $key => $value) {
				$old[$key] = $value['AdmController']['name'];
			}
			debug($old);
			
			if(count($new) == 0 AND count($old) == 0){                   
					echo 'missing'; // envia al data del js de jquery
                }else{
					$insert=array_diff($new,$old);
                    $delete=array_diff($old,$new);
					
					//Aqui se elimina los antiguos valores
                    if(count($delete)>0){
                    $this->AdmController->deleteAll(array('adm_role_id'=>$role, 'adm_action_id' => $delete));
                    }
                    //Aqui se guarda los nuevos valores
                    if(count($insert)>0){
                        //Para Insertar, se debe formatear el vector para que reconozca ORM de cake
                        $miData = array();
                        $cont = 0;
                        foreach($insert as $var){
                            $miData[$cont]['adm_role_id'] = $role;
                            $miData[$cont]['adm_action_id'] = $var;
                            $cont++;
                        }
                        //debug($miData);
                        $this->AdmController->saveMany($miData);
                    }
					echo 'success'; // envia al data del js de jquery
			}
			
		}
		*/		
	}

			/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmController->id = $id;
		if (!$this->AdmController->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm controller')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                    $this->request->data['AdmController']['lc_action']='MODIFY';
			if ($this->AdmController->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm controller')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm controller')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmController->read(null, $id);
		}
		$admModules = $this->AdmController->AdmModule->find('list');
		$this->set(compact('admModules'));
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
		$this->AdmController->id = $id;
		if (!$this->AdmController->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm controller')));
		}
		
		$actions = $this->AdmController->AdmAction->find('count', array('conditions'=>array('AdmAction.adm_controller_id'=>$id)));
		
		if($actions > 0){
			$this->Session->setFlash(
				__('No se puede eliminar porque tiene acciones dependientes'),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->AdmController->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm controller')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm controller')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
}

<?php

App::uses('AppController', 'Controller');

/**
 * AdmTransitions Controller
 *
 * @property AdmTransition $AdmTransition
 */
class AdmTransitionsController extends AppController {
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
	 */

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->AdmTransition->bindModel(array(
			'hasOne' => array(
				'AdmController' => array(
					'foreignKey' => false,
					'conditions' => array('AdmTransaction.adm_controller_id = AdmController.id')
				)
			)
		));
		$this->paginate = array(
			'order' => array('AdmController.name' => 'ASC'),
			'limit' => 20,
		);
		$this->AdmTransition->recursive = 0;
		$this->set('admTransitions', $this->paginate('AdmTransition'));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function life_cycles() {
		//Ajax
		if ($this->RequestHandler->isAjax()) {
			//Data Catch
			$sSearch = $this->request->data['sSearch'];
//			$findType = $this->request->data['findType'];
			$json = array();
//			if($findType == 'Transition'){
			$json['Transitions'] = $this->_fnFindTransitions($sSearch);
//			}elseif($findType == 'State'){
			$json['States'] = $this->_fnFindStates($sSearch);
//			}elseif($findType == 'Transaction'){
			$json['Transactions'] = $this->_fnFindTransactions($sSearch);
//			}
			//Send data
			return new CakeResponse(array('body' => json_encode($json)));  //convert to json format and send
		}
		//On load
		$this->loadModel('AdmController');
		$controllers = $this->AdmController->find('list', array('order' => array('AdmController.name' => 'ASC')));
//		$controllers = array(0 => 'CONTROLADORES');
//		if (count($controllersClean) > 0) {
//			foreach ($controllersClean as $key => $value) {
//				$controllers[$key] = $value;
//			}
//		}
		$this->set(compact('controllers'));
	}

	private function _fnFindTransitions($sSearch) {
		$controller = 'AdmTransition'; //only replace this variable will help a lot of work for main controller
		//Query/Search
		$searchConditions = array(
			'AdmTransaction.adm_controller_id' => $sSearch
		);
		$this->paginate = array(
			'order' => array($controller . '.name' => 'asc'),
			'limit' => 50,
			'fields' => array(
				'AdmTransition.id'
				, 'AdmState.name'
				, 'AdmTransaction.name'
				, 'AdmFinalState.name'
			),
			'conditions' => $searchConditions
		);
		$data = $this->paginate($controller);

		//Data Json Formating
		$json["aaData"] = array();
		$counter = 1;
		foreach ($data as $key => $value) {
			$editButton = '<a href="#" class="btn btn-primary btnEditRow" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
			$deleteButton = '<a href="#" class="btn btn-danger btnDeleteRow" title="Eliminar"><i class="icon-trash icon-white"></i></a> ';
			$json["aaData"][$key][0] = 'tr' . $controller . '-' . $value[$controller]["id"];
			$json["aaData"][$key][1] = $counter;
			$json["aaData"][$key][2] = $value["AdmState"]["name"];
			$json["aaData"][$key][3] = $value["AdmTransaction"]["name"];
			$json["aaData"][$key][4] = $value["AdmFinalState"]["name"];
			$json["aaData"][$key][5] = $editButton . $deleteButton; //must find a another way to create these buttons or not?
//				$json["aaData"][$key]["DT_RowId"] = 'tr'.$controller.'-'.$value[$controller]["id"];
			$counter++;
		}

		return $json;
	}

	private function _fnFindStates($sSearch) {
		$controller = 'AdmState'; //only replace this variable will help a lot of work for main controller
		//Query/Search
		$searchConditions = array(
			'AdmState.adm_controller_id' => $sSearch
		);
		$this->paginate = array(
			'recursive' => -1,
//				'order' => array($controller . '.name' => 'ASC'), //it's not sorting by name ??
			'limit' => 50,
			'fields' => array(
				$controller . '.id'
				, $controller . '.name'
				, $controller . '.description'
			),
			'conditions' => $searchConditions
		);
		$data = $this->paginate($controller);

		//Data Json Formating
		$json["aaData"] = array();
		$counter = 1;
		foreach ($data as $key => $value) {
			$editButton = '<a href="#" class="btn btn-primary btnEditRow" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
			$deleteButton = '<a href="#" class="btn btn-danger btnDeleteRow" title="Eliminar"><i class="icon-trash icon-white"></i></a> ';
			$json["aaData"][$key][0] = 'tr' . $controller . '-' . $value[$controller]["id"];
			$json["aaData"][$key][1] = $counter;
			$json["aaData"][$key][2] = $value[$controller]["name"];
			$json["aaData"][$key][3] = $value[$controller]["description"];
			$json["aaData"][$key][4] = $editButton . $deleteButton; //must find a another way to create these buttons or not?
			$counter++;
		}

		return $json;
	}

	private function _fnFindTransactions($sSearch) {
		$controller = 'AdmTransaction'; //only replace this variable will help a lot of work for main controller
		//Query/Search
		$searchConditions = array(
			'AdmTransaction.adm_controller_id' => $sSearch
		);
		$this->paginate = array(
//				'order' => array($controller . '.name' => 'asc'),
			'limit' => 50,
			'fields' => array(
				$controller . '.id'
				, $controller . '.name'
				, $controller . '.sentence'
				, $controller . '.description'
			),
			'conditions' => $searchConditions
		);
		$data = $this->paginate($controller);

		//Data Json Formating
		$json["aaData"] = array();
		$counter = 1;
		foreach ($data as $key => $value) {
			$editButton = '<a href="#" class="btn btn-primary btnEditRow" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
			$deleteButton = '<a href="#" class="btn btn-danger btnDeleteRow" title="Eliminar"><i class="icon-trash icon-white"></i></a> ';
			$json["aaData"][$key][0] = 'tr' . $controller . '-' . $value[$controller]["id"];
			$json["aaData"][$key][1] = $counter;
			$json["aaData"][$key][2] = $value[$controller]["name"];
			$json["aaData"][$key][3] = $value[$controller]["description"];
			$json["aaData"][$key][4] = $value[$controller]["sentence"];
			$json["aaData"][$key][5] = $editButton . $deleteButton; //must find a another way to create these buttons or not?
			$counter++;
		}

		return $json;
	}

	public function ajax_modal_save_transition() {
		if ($this->RequestHandler->isAjax()) {
			$id = $this->request->data['id'];
			$controllerId = $this->request->data['controllerId'];
			if ($id > 0) {
				$this->request->data = $this->AdmTransition->read(null, $id);
			}

			$admStates = $this->AdmTransition->AdmState->find('list', array('conditions' => array('AdmState.adm_controller_id' => $controllerId)));
			$admFinalStates = $this->AdmTransition->AdmState->find('list', array('conditions' => array('AdmState.adm_controller_id' => $controllerId)));
			$admTransactions = $this->AdmTransition->AdmTransaction->find('list', array('conditions' => array('AdmTransaction.adm_controller_id' => $controllerId)));
			$this->set(compact('admStates', 'admFinalStates', 'admTransactions'));
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}

	public function ajax_modal_save_state() {
		if ($this->RequestHandler->isAjax()) {
			$id = $this->request->data['id'];
			$controllerId = $this->request->data['controllerId'];
			if ($id > 0) {
				$this->request->data = $this->AdmTransition->AdmState->read(null, $id);
			}
			$this->set('controllerId', $controllerId);
//			$this->set(compact('contollerId')); //this way is not working for ajax modal :S ??
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}

	public function ajax_modal_save_transaction() {
		if ($this->RequestHandler->isAjax()) {
			$id = $this->request->data['id'];
			$controllerId = $this->request->data['controllerId'];
			if ($id > 0) {
				$this->request->data = $this->AdmTransition->AdmTransaction->read(null, $id);
			}
			$this->set('controllerId', $controllerId);
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}

	public function fnAjaxSaveFormState() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
//          Configure::write('debug', 0);//To show a clean error for production, comment it when developing
			$model = 'AdmState';
			$this->loadModel($model);
			if ($this->request->data[$model]['id'] == '') {//if true prepare for insert
				unset($this->request->data[$model]['id']);
				$this->$model->create();
			}
			if (!empty($this->request->data)) {
				try {
					$this->$model->save($this->request->data);
					echo 'success';
				} catch (Exception $e) {
					if ($e->getCode() == 23502) {//Not null violation
						echo 'Un campo obligatorio esta vacio';
					} elseif ($e->getCode() == 23505) {//Unique violation
						echo 'No puede haber duplicado';
					} elseif ($e->getCode() == 23503) {//children
						echo 'Error al guardar los dependendientes';
					} else {
						echo 'Vuelva a intentarlo'; //None of the above errors
					}
				}
			}
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}

	public function fnAjaxSaveFormTransaction() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
//          Configure::write('debug', 0);//To show a clean error for production, comment it when developing
			$model = 'AdmTransaction';
			$this->loadModel($model);
			if ($this->request->data[$model]['id'] == '') {//if true prepare for insert
				unset($this->request->data[$model]['id']);
				$this->$model->create();
			}
			if (!empty($this->request->data)) {
				try {
					$this->$model->save($this->request->data);
					echo 'success';
				} catch (Exception $e) {
					if ($e->getCode() == 23502) {//Not null violation
						echo 'Un campo obligatorio esta vacio';
					} elseif ($e->getCode() == 23505) {//Unique violation
						echo 'No puede haber duplicado';
					} elseif ($e->getCode() == 23503) {//children
						echo 'Error al guardar los dependendientes';
					} else {
						echo 'Vuelva a intentarlo'; //None of the above errors
					}
				}
			}
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}

	public function fnAjaxSaveFormTransition() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
//          Configure::write('debug', 0);//To show a clean error for production, comment it when developing
			$model = 'AdmTransition';
			if ($this->request->data[$model]['id'] == '') {//if true prepare for insert
				unset($this->request->data[$model]['id']);
				$this->$model->create();
			}
			if (!empty($this->request->data)) {
				try {
					$this->$model->save($this->request->data);
					echo 'success';
				} catch (Exception $e) {
					if ($e->getCode() == 23502) {//Not null violation
						echo 'Un campo obligatorio esta vacio';
					} elseif ($e->getCode() == 23505) {//Unique violation
						echo 'No puede haber duplicado';
					} elseif ($e->getCode() == 23503) {//children
						echo 'Error al guardar los dependendientes';
					} else {
						echo 'Vuelva a intentarlo'; //None of the above errors
					}
				}
			}
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}

	public function fnAjaxDeleteRowTransition() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			$model = 'AdmTransition';
			$id = $this->request->data['id'];
			$this->$model->id = $id;
			try {
				$this->$model->delete();
				echo 'success';
			} catch (Exception $e) {
				if ($e->getCode() == 23503) {//children
					echo 'No se puede eliminar porque tiene dependendientes';
				} else {
					echo 'Vuelva a intentarlo'; //None of the above errors
				}
			}
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}
	
		public function fnAjaxDeleteRowState() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			$model = 'AdmState';
			$this->loadModel($model);
			$id = $this->request->data['id'];
			$this->$model->id = $id;
			try {
				$this->$model->delete();
				echo 'success';
			} catch (Exception $e) {
				if ($e->getCode() == 23503) {//children
					echo 'No se puede eliminar porque tiene dependendientes';
				} else {
					echo 'Vuelva a intentarlo'; //None of the above errors
				}
			}
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}
	
	public function fnAjaxDeleteRowTransaction() {
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			$model = 'AdmTransaction';
			$this->loadModel($model);
			$id = $this->request->data['id'];
			$this->$model->id = $id;
			try {
				$this->$model->delete();
				echo 'success';
			} catch (Exception $e) {
				if ($e->getCode() == 23503) {//children
					echo 'No se puede eliminar porque tiene dependendientes';
				} else {
					echo 'Vuelva a intentarlo'; //None of the above errors
				}
			}
		} else {
			$this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
		}
	}
	
	public function ajax_list_controllers() {
		if ($this->RequestHandler->isAjax()) {
			$initialModule = $this->request->data['module'];
			$admControllers = $this->AdmTransition->AdmAction->AdmController->find('list', array('conditions' => array('AdmController.adm_module_id' => $initialModule)));

			if (count($admControllers) == 0) {
				$admControllers[""] = "--- Vacio ---";
				//$admActions = array();
			}

			$this->set(compact('admControllers'));
		} else {
			$this->redirect($this->Auth->logout());
		}
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->AdmTransition->create();
			if ($this->AdmTransition->save($this->request->data)) {
				$this->Session->setFlash(
						__('The %s has been saved', __('adm transition')), 'alert', array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
						)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
						__('The %s could not be saved. Please, try again.', __('adm transition')), 'alert', array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
						)
				);
			}
		}
		$admStates = $this->_createComposeStatesList(); //$this->AdmTransition->AdmState->find('list');
		$admTransactions = $this->_createComposeTransactionList(); //$this->AdmTransition->AdmTransaction->find('list');
		$admFinalStates = $this->_createComposeStatesList(); //$this->AdmTransition->AdmState->find('list');
		$this->set(compact('admStates', 'admTransactions', 'admFinalStates'));
		//debug($this->_createComposeStatesList());
		//debug($this->_createComposeTransactionList());
	}

	private function _createComposeStatesList() {
		$admStates = $this->AdmTransition->AdmState->find('all', array(
			'fields' => array('AdmState.id', 'AdmState.name', 'AdmController.name'),
			'order' => array('AdmController.name' => 'ASC'),
			'recursive' => 0
		));
		$array = array();
		for ($i = 0; $i < count($admStates); $i++) {
			$array[$admStates[$i]['AdmState']['id']] = $admStates[$i]['AdmController']['name'] . '->' . $admStates[$i]['AdmState']['name'];
		}
		return $array;
	}

	private function _createComposeTransactionList() {
		$admTransaction = $this->AdmTransition->AdmTransaction->find('all', array(
			'fields' => array('AdmTransaction.id', 'AdmTransaction.name', 'AdmController.name'),
			'order' => array('AdmController.name' => 'ASC'),
			'recursive' => 0
		));
		$array = array();
		for ($i = 0; $i < count($admTransaction); $i++) {
			$array[$admTransaction[$i]['AdmTransaction']['id']] = $admTransaction[$i]['AdmController']['name'] . '->' . $admTransaction[$i]['AdmTransaction']['name'];
		}
		return $array;
	}

	/**
	 * edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->AdmTransition->id = $id;
		if (!$this->AdmTransition->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transition')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['AdmTransition']['lc_action'] = 'MODIFY';
			if ($this->AdmTransition->save($this->request->data)) {
				$this->Session->setFlash(
						__('The %s has been saved', __('adm transition')), 'alert', array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
						)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
						__('The %s could not be saved. Please, try again.', __('adm transition')), 'alert', array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-error'
						)
				);
			}
		} else {
			$this->request->data = $this->AdmTransition->read(null, $id);
		}
		$admStates = $this->_createComposeStatesList(); //$this->AdmTransition->AdmState->find('list');
		$admTransactions = $this->_createComposeTransactionList(); //$this->AdmTransition->AdmTransaction->find('list');
		$admFinalStates = $this->_createComposeStatesList(); //$this->AdmTransition->AdmState->find('list');
		$this->set(compact('admStates', 'admTransactions', 'admFinalStates'));
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
		$this->AdmTransition->id = $id;
		if (!$this->AdmTransition->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm transition')));
		}
		if ($this->AdmTransition->delete()) {
			$this->Session->setFlash(
					__('The %s deleted', __('adm transition')), 'alert', array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-success'
					)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
				__('The %s was not deleted', __('adm transition')), 'alert', array(
			'plugin' => 'TwitterBootstrap',
			'class' => 'alert-error'
				)
		);
		$this->redirect(array('action' => 'index'));
	}

}

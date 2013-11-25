<?php
App::uses('AppController', 'Controller');
/**
 * AdmParameters Controller
 *
 * @property AdmParameter $AdmParameter
 */
class AdmParametersController extends AppController {

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
	

/**
 * index method
 *
 * @return void
 */
	public function index_old() {
		$this->AdmParameter->recursive = 0;
		$this->set('admParameters', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmParameter->create();
			if ($this->AdmParameter->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->AdmParameter->id = $id;
		if (!$this->AdmParameter->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm parameter')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['AdmParameter']['lc_transaction']='MODIFY';
			if ($this->AdmParameter->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm parameter')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmParameter->read(null, $id);
		}
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
		$this->AdmParameter->id = $id;
		if (!$this->AdmParameter->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm parameter')));
		}
		if ($this->AdmParameter->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm parameter')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm parameter')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}	

	
	
	//////////////////////////////////////////////Test version 2.0/////////////////////////////////////
	public function index(){
		//Everything is through ajax now due the datatable plugin
		if ($this->RequestHandler->isAjax()) {
			//Data Catch
			$sEcho = $initialModule = $this->request->data['sEcho'];
			$iDisplayLength = $this->request->data['iDisplayLength'];
			$iDisplayStart = $this->request->data['iDisplayStart'];
			$sSearch = $this->request->data['sSearch'];
			
			$controller = 'AdmParameter'; //only replace this variable will help a lot of work for main controller
			//Query/Search

			$searchConditions = array(
				'OR' => array(
					'lower('.$controller.'.name) LIKE' => '%' . strtolower($sSearch) . '%',
					'lower('.$controller.'.description) LIKE' => '%' . strtolower($sSearch) . '%'
				)
			);
			
			//First query
			$this->$controller->recursive = 0;
			$this->paginate = array(
				'order' => array($controller.'.name' => 'asc'),
				'limit' => $iDisplayLength,
				'offset' => $iDisplayStart,
				'fields' => array(
					  $controller.'.id'
					, $controller.'.name'
					, $controller.'.description'
				),
				'conditions' => $searchConditions
			);
			$data = $this->paginate();
			
			//Second query without pagination limits, must see if there is a way to eliminate this for improve perfomance
			$total = $this->$controller->find("count", array( 
				'conditions' => $searchConditions
			));
			
			
			//Data Json Formating
			$json = array("sEcho" => $sEcho);
			$json["aaData"]=array();
			$counter = $iDisplayStart + 1;
			foreach ($data as $key => $value) {
				$editButton = '<a href="#" class="btn btn-primary btnEditRow" title="Editar"><i class="icon-pencil icon-white"></i></a> ';
				$deleteButton = '<a href="#" class="btn btn-danger btnDeleteRow" title="Eliminar"><i class="icon-trash icon-white"></i></a> ';
				$json["aaData"][$key][0] = $counter;
				$json["aaData"][$key][1] = $value[$controller]["name"];
				$json["aaData"][$key][2] = $value[$controller]["description"];
				$json["aaData"][$key][3] = $editButton . $deleteButton; //must find a another way to create these buttons or not?
				$json["aaData"][$key]["DT_RowId"] = 'tr'.$controller.'-'.$value[$controller]["id"];
				$counter++;
			}
			$json["iTotalRecords"] = $total;
			$json["iTotalDisplayRecords"] = $total;

			//Send data
			return new CakeResponse(array('body' => json_encode($json)));  //convert to json format and send
		}
	}
	
	public function ajax_modal_save(){
		if ($this->RequestHandler->isAjax()) {
			$id = $this->request->data['id'];
			if($id > 0){
				$this->request->data = $this->AdmParameter->read(null, $id);
			}
		}else{
			$this->redirect($this->Auth->logout());//only accesible through ajax otherwise logout
		}
	}
	
	public function fnAjaxSaveForm(){
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
//			Configure::write('debug', 0);//To show a clean error for production, comment it when developing
			if($this->request->data['AdmParameter']['id'] == ''){//if true prepare for insert
				unset($this->request->data['AdmParameter']['id']);
				$this->AdmParameter->create();
			}
			if (!empty($this->request->data)) {
				try{
					$this->AdmParameter->save($this->request->data);
					echo 'success';
				}catch(Exception $e){
					if ($e->getCode() == 23502) {//Not null violation
						echo 'Un campo obligatorio esta vacio';
					} elseif($e->getCode() == 23505) {//Unique violation
						echo 'No puede haber duplicado';
					}elseif($e->getCode() == 23503) {//children
						echo 'Error al guardar los dependendientes';
					}else{
						echo 'Vuelva a intentarlo';//None of the above errors
					}
				}
			}
		}else{
			$this->redirect($this->Auth->logout());//only accesible through ajax otherwise logout
		}
	}
	
	public function fnAjaxDeleteRow(){
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			$id = $this->request->data['id'];
			$this->AdmParameter->id = $id;
			try{
				$this->AdmParameter->delete();
				echo 'success';
			}catch(Exception $e){
				if ($e->getCode() == 23503) {//children
					echo 'No se puede eliminar, tiene dependendientes';
				} else {
					echo 'Vuelva a intentarlo';//None of the above errors
				}
			}	
		}else{
			$this->redirect($this->Auth->logout());//only accesible through ajax otherwise logout
		}
	}
	
//END CLASS	
}

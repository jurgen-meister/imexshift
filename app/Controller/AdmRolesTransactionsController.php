<?php
App::uses('AppController', 'Controller');
/**
 * AdmRolesTransactions Controller
 *
 * @property AdmRolesTransaction $AdmRolesTransaction
 */
class AdmRolesTransactionsController extends AppController {

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
		return $this->Permission->isAllowed($this->name, $this->action, $this->Session->read('Permission.'.$this->name));
	}
	
/**
 * index method
 *
 * @return void
 */
	/*
	public function index() {
		$this->AdmRolesTransaction->recursive = 0;
		$this->set('admRolesTransactions', $this->paginate());
	}
	 * 
	 */

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	/*
	public function view($id = null) {
		$this->AdmRolesTransaction->id = $id;
		if (!$this->AdmRolesTransaction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm roles transaction')));
		}
		$this->set('admRolesTransaction', $this->AdmRolesTransaction->read(null, $id));
	}
   */

	
/**
 * add method
 *
 * @return void
 */
	public function add() {		
		//////Para cargar todos los Selects, correlativamente al inicio.
		//ROLES
		$admRoles = $this->AdmRolesTransaction->AdmRole->find('list');
		$initialRole = key($admRoles);
		
        //MODULES
        $this->loadModel('AdmModule');
		$admModules = $this->AdmModule->find('list');
		$initialModule = key($admModules);
		
        //CONTROLLERS
        $this->loadModel('AdmController');
		$admControllers = $this->AdmController->find('list', array('conditions'=>array('adm_module_id'=>$initialModule)));
		$initialController = key($admControllers);
		
		//TRANSACTIONS
		$catchCheckedTransactions = $this->AdmRolesTransaction->find('all', array('recursive'=>-1,'fields'=>array('adm_transaction_id'), 'conditions'=>array('adm_role_id'=>$initialRole)));
        $checkedTransactions = array();
        $cont = 0;
		
        foreach($catchCheckedTransactions as $ca){
            $checkedTransactions[$cont] = $ca['AdmRolesTransaction']['adm_transaction_id'];
            $cont++;
        }
		
        $admTransactions = $this->AdmRolesTransaction->AdmTransaction->find('list', array('conditions'=>array('AdmTransaction.adm_controller_id'=>$initialController)));

        $this->set(compact('admRoles', 'admTransactions', 'admModules', 'admControllers', 'checkedTransactions'));
	
	}

	
	
	public function ajax_save(){
		if($this->RequestHandler->isAjax()){
				////////////////INICIO/////////////////
				$role = $this->request->data['role'];
                $controller = $this->request->data['controller'];
                //Captura los valores nuevos enviados por el checkbox
				if(isset($this->request->data['transaction'])){//Soluciona problema "Undefined index: action", porque la accion post no esta definida cuando el vector esta vacio
					$transaction = $this->request->data['transaction']; 
				}else{
					$transaction = array();
				}
				
                //Buscar los valor antiguos guardados, uso unbind y bind para que todo salga en un solo query
                $this->AdmRolesTransaction->unbindModel(array('belongsTo'=>array('AdmTransaction', 'AdmRole')));
                $this->AdmRolesTransaction->bindModel(array(
                    'belongsTo'=>array(
                        'AdmTransaction' => array(
                            'foreignKey' => false,
                            'conditions' => array('AdmRolesTransaction.adm_transaction_id = AdmTransaction.id')
                        ),
                        'AdmController' => array(
                            'foreignKey' => false,
                            'conditions' => array('AdmTransaction.adm_controller_id = AdmController.id', '')
                        )
                    )
                ));
                $catchOld =  $this->AdmRolesTransaction->find('all', array(
                    'conditions'=>array('AdmRolesTransaction.adm_role_id'=>$role, 'AdmController.id'=>$controller),
                    'fields'=>array('id', 'adm_role_id', 'AdmController.id', 'AdmController.name', 'AdmTransaction.id', 'AdmTransaction.name')));
                $old = array();
                for($i=0; $i< count($catchOld); $i++){
                    $old[$i] = (string)$catchOld[$i]['AdmTransaction']['id'];
                }
                
                //Compara los valores nuevos enviados con los valores antiguos guardados
                //De esta manera se ve que se insertara y que se borrara
                if(count($transaction) == 0 AND count($old) == 0){
					echo 'missing'; // envia al data del js de jquery
                }else{
                    $new = $transaction;

                    $insert=array_diff($new,$old);
                    //echo "<br>insert";
                    //debug($insert);
                    $delete=array_diff($old,$new);
                    //echo "delete";
                    //debug($delete);

                    //Aqui se elimina los antiguos valores
                    if(count($delete)>0){
                    $this->AdmRolesTransaction->deleteAll(array('adm_role_id'=>$role, 'adm_transaction_id' => $delete));
                    }
                    //Aqui se guarda los nuevos valores
                    if(count($insert)>0){
                        //Para Insertar, se debe formatear el vector para que reconozca ORM de cake
                        $miData = array();
                        $cont = 0;
                        foreach($insert as $var){
                            $miData[$cont]['adm_role_id'] = $role;
                            $miData[$cont]['adm_transaction_id'] = $var;
                            $cont++;
                        }
                        //debug($miData);
                        $this->AdmRolesTransaction->saveMany($miData);
                    }
                    //$this->render('success', 'ajax'); //(id, layout)
					//$this->set('msj', 'success');
					echo 'success'; // envia al data del js de jquery
                }
				////////////////FIN/////////////////
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	
	public function ajax_list_controllers(){
		if($this->RequestHandler->isAjax()){
			$module = $this->request->data['module'];
			$role = $this->request->data['role'];

			//CONTROLLERS
			$this->loadModel('AdmController');
			$admControllers = $this->AdmController->find('list', array('conditions'=>array('adm_module_id'=>$module)));
			$controller = 0;
			if(count($admControllers) > 0){
			$controller = key($admControllers);
			}
			//TRANSACTIONS
			$catchCheckedTransactions = $this->AdmRolesTransaction->find('all', array('recursive'=>-1,'fields'=>array('adm_transaction_id'), 'conditions'=>array('adm_role_id'=>$role)));
			$checkedTransactions = array();
			$cont = 0;
			foreach($catchCheckedTransactions as $ca){
				$checkedTransactions[$cont] = $ca['AdmRolesTransaction']['adm_transaction_id'];
				$cont++;
			}
			
			$admTransactions = array();
			if(count($admControllers) > 0){
			$admTransactions = $this->AdmRolesTransaction->AdmTransaction->find('list', array('conditions'=>array('AdmTransaction.adm_controller_id'=>$controller)));
			}

			$this->set('admControllers', $admControllers);
			$this->set('admTransactions', $admTransactions);
			$this->set('checkedTransactions', $checkedTransactions);
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	public function ajax_list_transactions(){
		if($this->RequestHandler->isAjax()){
			$role = $this->request->data['role'];
			$controller = $this->request->data['controller'];

			$catchCheckedTransactions = $this->AdmRolesTransaction->find('all', array('recursive'=>-1,'fields'=>array('adm_transaction_id'), 'conditions'=>array('adm_role_id'=>$role)));
			$checkedTransactions = array();
			$cont = 0;
			foreach($catchCheckedTransactions as $ca){
				$checkedTransactions[$cont] = $ca['AdmRolesTransaction']['adm_transaction_id'];
				$cont++;
			}

			$admTransactions = $this->AdmRolesTransaction->AdmTransaction->find('list', array('conditions'=>array('AdmTransaction.adm_controller_id'=>$controller)));

			$this->set('admTransactions', $admTransactions);
			$this->set('checkedTransactions', $checkedTransactions);
			
  		}else{
			$this->redirect($this->Auth->logout());
		}

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////7
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	/*
	public function edit($id = null) {
		$this->AdmRolesTransaction->id = $id;
		if (!$this->AdmRolesTransaction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm roles transaction')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AdmRolesTransaction->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm roles transaction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm roles transaction')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmRolesTransaction->read(null, $id);
		}
		$admRoles = $this->AdmRolesTransaction->AdmRole->find('list');
		$admTransactions = $this->AdmRolesTransaction->AdmTransaction->find('list');
		$this->set(compact('admRoles', 'admTransactions'));
	}
*/
	
/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	/*
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->AdmRolesTransaction->id = $id;
		if (!$this->AdmRolesTransaction->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm roles transaction')));
		}
		if ($this->AdmRolesTransaction->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm roles transaction')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm roles transaction')),
			'alert',
			array(
				'plugin' => 'TwitterBootstrap',
				'class' => 'alert-error'
			)
		);
		$this->redirect(array('action' => 'index'));
	}
	 * 
	 */
}

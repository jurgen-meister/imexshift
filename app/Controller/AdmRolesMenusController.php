<?php
App::uses('AppController', 'Controller');
/**
 * AdmRolesMenus Controller
 *
 * @property AdmRolesMenu $AdmRolesMenu
 */
class AdmRolesMenusController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'default';
/*
	public  function isAuthorized($user){
		$array=$this->Session->read('Permission.'.$this->name);
		if(count($array)>0){
			if(in_array($this->action, $array)){
				return true;
			}
		}
		$this->redirect($this->Auth->logout());
	}
*/	
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
	/*
	public function index() {
		$this->AdmRolesMenu->recursive = 0;
		$this->set('admRolesMenus', $this->paginate());
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
		$this->AdmRolesMenu->id = $id;
		if (!$this->AdmRolesMenu->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm roles menu')));
		}
		$this->set('admRolesMenu', $this->AdmRolesMenu->read(null, $id));
	}
*/
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->AdmRolesMenu->create();
			if ($this->AdmRolesMenu->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm roles menu')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm roles menu')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		}
		
		
		$admRoles = $this->AdmRolesMenu->AdmRole->find('list', array('order'=>array('AdmRole.id'=>'ASC')));
		//$admModules = $this->AdmRolesMenu->AdmMenu->AdmModule->find('list');
		$parentsMenus = $this->AdmRolesMenu->AdmMenu->find("list", array(
				"conditions"=>array(
					"AdmMenu.parent_node"=>null,// don't have parent
					"AdmMenu.inside "=>null //this will dissapear
				)
				,'order'=>array('AdmMenu.order_menu') 
				,"recursive"=>-1
			));
		//$parentsMenus[0] = "Ninguno";
		///////////////////////***************************************//////////////////
		if(count($admRoles) > 0 AND count($parentsMenus) > 0){
			$role = key($admRoles);
			$parentMenu =key($parentsMenus);
			$chkTree = $this->_createCheckboxTree($role, $parentMenu); //Crestes checkbox tree 5 level, must improve
		}else{
			$chkTree = "Debe existir un Rol y un Menu Padre";
		}
		///////////////////////***************************************//////////////////
		
		//$this->set(compact('admRoles', 'admModules','chkTree'));
		$this->set(compact('admRoles', 'parentsMenus','chkTree'));
	}
	
	private function _findMenus($var){
		$vec = $this->AdmRolesMenu->AdmMenu->find('list', array('fields'=>array('AdmMenu.id', 'AdmMenu.id') , 'order'=>array('AdmMenu.order_menu'),'conditions'=>array("AdmMenu.parent_node"=>$var)));;
		return $vec;
	}

	private function _createCheckboxTree($role, $parentMenu){
		
		//Til 5 levels, MUST be improved	
		//PART 1
		$parents = $this->AdmRolesMenu->AdmMenu->find('list', array(
			 'fields'=>array('AdmMenu.id', 'AdmMenu.id') 
			,'order'=>array('AdmMenu.order_menu') 
			,'conditions'=>array('AdmMenu.inside'=>null,"AdmMenu.parent_node"=>null, 'AdmMenu.id'=>$parentMenu)
		));
		//debug($parents);
		$vector = array();
		foreach($parents as $key => $var){
			$vector[$key] = $this->_findMenus($var);
		 	foreach($vector[$key] as $key2 => $var2){
				$vector[$key][$key2] = $this->_findMenus($var2);
				foreach($vector[$key][$key2] as $key3 => $var3){
					$vector[$key][$key2][$key3] = $this->_findMenus($var3);;
					foreach($vector[$key][$key2][$key3] as $key4 => $var4){
						$vector[$key][$key2][$key3][$key4] = $this->_findMenus($var4);;
					}
				}
			}
		}
		//debug($vector);
		//PART 2
		$str = '';
		
		$str.= '<ul id="tree1">';
		//////////N1
				foreach ($vector as $key => $value) {
					//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
					//$str.= $key;
					$str.= $this->_createCheck($key, $role);
					////////////N2
						if(count($vector[$key]) > 0){
							$str.= '<ul>';
							foreach ($vector[$key] as $key2 => $value2) {
								//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
								//$str.= $key2;
								$str.= $this->_createCheck($key2, $role);
								///////////N3
									if(count($vector[$key][$key2]) > 0){
										$str.= '<ul>';
											foreach ($vector[$key][$key2] as $key3 => $value3) {
												//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
												//$str.= $key3;
												$str.= $this->_createCheck($key3, $role);
												///////////N4
												if(count($vector[$key][$key2][$key3]) > 0){
													$str.= '<ul>';
														foreach ($vector[$key][$key2][$key3] as $key4 => $value4) {
															//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
															//$str.= $key4;
															$str.= $this->_createCheck($key4, $role);
															///////////N5
																if(count($vector[$key][$key2][$key3][$key4]) > 0){
																	$str.= '<ul>';
																		foreach ($vector[$key][$key2][$key3][$key4] as $key5 => $value5) {
																			//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
																			//$str.= $key5;
																			$str.= $this->_createCheck($key5, $role);
																			$str.= '</li>';
																		}
																	$str.= '</ul>';
																}
															///////////N5
															$str.= '</li>';
														}
													$str.= '</ul>';
												}
												///////////N4
												$str.= '</li>';
											}
										$str.= '</ul>';
									}
								/////////N3
								$str.= '</li>';
							}
							$str.= '</ul>';
						}
					////////////N2
					$str.= '</li>';
				}
		//////////N1
		$str.= '</ul>';
		
		return $str;
	}

	private function _createCheck($menu, $role){
		$exist = $this->AdmRolesMenu->find('count', array('conditions'=>array('adm_menu_id'=>$menu, 'adm_role_id'=>$role)));
		$this->AdmRolesMenu->AdmMenu->id = $menu;
		$name = $this->AdmRolesMenu->AdmMenu->field('name');
		
		$checked = '';
		if($exist > 0){
			$checked = ' checked = "checked" ';
		}
		$str ='<li><label class="checkbox"><input type="checkbox" name="chkTree[]" value="'.$menu.'" '. $checked .'  > '.$name.'</label>';
		return $str;
	}

	public function ajax_list_menus(){
		if($this->RequestHandler->isAjax()){
			$role = $this->request->data['role'];		
			$parentMenus = $this->request->data['parentMenus'];		
//echo $module;
//echo $role;
				if($role == "" OR $parentMenus == ""){
					$chkTree = "Debe existir un rol y un Menu Padre";
				}else{
					$chkTree = $this->_createCheckboxTree($role, $parentMenus); //Crestes checkbox tree 5 level, must improve
				}
			///////////////////////***************************************//////////////////
			$this->set(compact('chkTree'));
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
//	public function ajax_list_menu_inside(){
//		if($this->RequestHandler->isAjax()){
//			$role = $this->request->data['role'];		
//			$module = $this->request->data['module'];
//			//echo $role .'_>'.$module;
//			$chk = "";
//			if($role == "" OR $module == ""){
//					$chk = "Debe existir un rol y un modulo";
//				}else{
//					$this->_createCheckboxInsideMenu($role, $module); //Crestes checkbox tree 5 level, must improve
//			}
//			$this->set('chk', $chk);
//		}else{
//			$this->redirect($this->Auth->logout());
//		}
//	}
	
	public function ajax_save(){
		if($this->RequestHandler->isAjax()){
//			debug($this->request->data);
			$role = $this->request->data['role'];
			$parentMenu = $this->request->data['parentMenus'];
			//Capture checkbox values
			$type = $this->request->data['type'];
			//Solve problem when array is empty

//			$menu = $this->request->data['menu'];
//			if($menu <> ''){
//				$new = $this->request->data['menu']; 
//			}else{
//				$new = array();
//			}
			if(isset($this->request->data['menu'])){
				$new = $this->request->data['menu']; 
			}else{
				$new = array();
			}
			////check type menu or menu inside
			$valueType = null;
			if($type == 'inside'){
				$valueType = 1;
			}
			
			
			///////////OLD values
			//$old = $this->AdmRolesMenu->AdmMenu->find('list', array('fields'=>array('AdmMenu.id', 'AdmMenu.id'),'conditions'=>array('AdmRolesMenu.adm_role_id'=>$role, 'AdmMenu.adm_module_id'=>1)));
			$catchOld = $this->AdmRolesMenu->find('all', array(
				'fields'=>array('AdmRolesMenu.adm_menu_id')
				,'conditions'=>array('OR'=>array('AdmMenu.parent_node'=>$parentMenu, 'AdmMenu.id'=>$parentMenu), 'AdmRolesMenu.adm_role_id'=>$role, 'AdmMenu.inside'=>$valueType)
				));			
			//debug($catchOld);
			
			
			
			$old=array();
			if(count($catchOld) > 0){
				foreach ($catchOld as $key => $value) {
					$old[$key]=$value['AdmRolesMenu']['adm_menu_id'];
				}
			}
	
			//debug($old);
			//debug($new);
			//echo "old";
			//debug($catchOld);
			//debug($old);
			//echo "new";
			//debug($new);	
			/////////////
			if(count($new) == 0 AND count($old) == 0){
				echo 'missing'; // envia al data del js de jquery
             }else{
				$insert=array_diff($new,$old);
				//echo "insert";
				//debug($insert);
				$delete=array_diff($old,$new);
				//debug($delete);
				//DELETE	
				 if(count($delete)>0){
                    $this->AdmRolesMenu->deleteAll(array('adm_role_id'=>$role, 'adm_menu_id' => $delete));
                    }
                //SAVE    
				if(count($insert)>0){
					
					$miData = array();
					$cont = 0;
					foreach($insert as $var){
						$miData[$cont]['adm_role_id'] = $role;
						$miData[$cont]['adm_menu_id'] = $var;
						//$miData[$cont]['creator'] = $this->Session->read('UserRestriction.id');
						$cont++;
					}
					//debug($miData);
					
					$this->AdmRolesMenu->saveMany($miData);
				}
				echo 'success'; // envia al data del js de jquery
			}
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	
//	public function add_inside(){
//		
//		$admRoles = $this->AdmRolesMenu->AdmRole->find('list', array('order'=>array('AdmRole.id'=>'ASC')));
//		$role =key($admRoles);
//		$admModules = $this->AdmRolesMenu->AdmMenu->AdmModule->find('list');
//		$module = key($admModules);
//	////////////////////////////////////////////////////////////////////////////////////////	
//		$this->_createCheckboxInsideMenu($module, $role);
//		$this->set('admRoles',$admRoles);
//		$this->set('admModules',$admModules);
//	}
	
//	private function _createCheckboxInsideMenu($role, $module){
//		//clave 1
//		$controllers = $this->AdmRolesMenu->AdmMenu->AdmAction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$module)));
//		//clave 1
//		//debug($controllers);
//		$this->loadModel('AdmAction');
//
//		$menusCheckBoxes =array();
//
//		$auxMenus = array();
//		$cont = 0;
//		foreach ($controllers as $key1 => $value1) {
//			$menusCheckBoxes[$key1]=array();
//			//$checked[$key1]=array();
//			$menus = $this->AdmAction->find('all', array(
//				'conditions'=>array('AdmAction.adm_controller_id'=>$key1)
//			));
//			//debug($menus);
//			if(count($menus) > 0){
//				//$cont=0;
//				foreach ($menus as $key2 => $value2) {
//					if(count($value2['AdmMenu']) > 0){
//						if($value2['AdmMenu'][0]['inside'] == 1){
//							$menusCheckBoxes[$key1][$value2['AdmMenu'][0]['id']]=$value2['AdmAction']['name']; //$value2['AdmMenu'][0]['name']
//							$auxMenus[$cont] = $value2['AdmMenu'][0]['id'];
//						}
//						$cont++;
//					}
//				}
//			}
//		}
//		
//		//debug($auxMenus);
//		//clave 2
//		$checks = $this->AdmRolesMenu->find('list', array(
//			'conditions'=>array('AdmRolesMenu.adm_menu_id'=>$auxMenus, 'AdmRolesMenu.adm_role_id'=>$role)
//			,'fields'=>array('AdmRolesMenu.adm_menu_id', 'AdmRolesMenu.adm_menu_id')
//		));
//		//clave 2
//		//debug($checks);
//		
//		$this->set(compact('controllers', 'menusCheckBoxes', 'checks'));
//	}
	
	
	/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	/*
	public function edit($id = null) {
		$this->AdmRolesMenu->id = $id;
		if (!$this->AdmRolesMenu->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm roles menu')));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AdmRolesMenu->save($this->request->data)) {
				$this->Session->setFlash(
					__('The %s has been saved', __('adm roles menu')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-success'
					)
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__('The %s could not be saved. Please, try again.', __('adm roles menu')),
					'alert',
					array(
						'plugin' => 'TwitterBootstrap',
						'class' => 'alert-error'
					)
				);
			}
		} else {
			$this->request->data = $this->AdmRolesMenu->read(null, $id);
		}
		$admRoles = $this->AdmRolesMenu->AdmRole->find('list');
		$admMenus = $this->AdmRolesMenu->AdmMenu->find('list');
		$this->set(compact('admRoles', 'admMenus'));
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
		$this->AdmRolesMenu->id = $id;
		if (!$this->AdmRolesMenu->exists()) {
			throw new NotFoundException(__('Invalid %s', __('adm roles menu')));
		}
		if ($this->AdmRolesMenu->delete()) {
			$this->Session->setFlash(
				__('The %s deleted', __('adm roles menu')),
				'alert',
				array(
					'plugin' => 'TwitterBootstrap',
					'class' => 'alert-success'
				)
			);
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(
			__('The %s was not deleted', __('adm roles menu')),
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

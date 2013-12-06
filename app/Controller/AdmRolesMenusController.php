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
	public function save() {
		$admRoles = $this->AdmRolesMenu->AdmRole->find('list', array('order' => array('AdmRole.id' => 'ASC')));
		$parentsMenus = $this->AdmRolesMenu->AdmMenu->find("list", array(
			"conditions" => array(
				"AdmMenu.parent_node" => null, // don't have parent
				"AdmMenu.inside " => null //this will dissapear
			)
			, 'order' => array('AdmMenu.order_menu')
			, "recursive" => -1
		));
		///////////////////////***************************************//////////////////
		if (count($admRoles) > 0 AND count($parentsMenus) > 0) {
			$role = key($admRoles);
			$parentMenu = key($parentsMenus);
			$chkTree = $this->_createCheckboxTree($role, $parentMenu); //Crestes checkbox tree 5 level, must improve
		} else {
			$chkTree = "Debe existir un Rol y un Menu Padre";
		}
		///////////////////////***************************************//////////////////
		$this->set(compact('admRoles', 'parentsMenus', 'chkTree'));
	}

	private function _findMenus($var) {
		$vec = $this->AdmRolesMenu->AdmMenu->find('list', array('fields' => array('AdmMenu.id', 'AdmMenu.id'), 'order' => array('AdmMenu.order_menu'), 'conditions' => array("AdmMenu.parent_node" => $var)));
		;
		return $vec;
	}

	private function _createCheckboxTree($role, $parentMenu) {

		//Til 5 levels, MUST be improved	
		//PART 1
		$parents = $this->AdmRolesMenu->AdmMenu->find('list', array(
			'fields' => array('AdmMenu.id', 'AdmMenu.id')
			, 'order' => array('AdmMenu.order_menu')
			, 'conditions' => array('AdmMenu.inside' => null, "AdmMenu.parent_node" => null, 'AdmMenu.id' => $parentMenu)
		));
		//debug($parents);
		$vector = array();
		foreach ($parents as $key => $var) {
			$vector[$key] = $this->_findMenus($var);
			foreach ($vector[$key] as $key2 => $var2) {
				$vector[$key][$key2] = $this->_findMenus($var2);
				foreach ($vector[$key][$key2] as $key3 => $var3) {
					$vector[$key][$key2][$key3] = $this->_findMenus($var3);
					;
					foreach ($vector[$key][$key2][$key3] as $key4 => $var4) {
						$vector[$key][$key2][$key3][$key4] = $this->_findMenus($var4);
						;
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
			if (count($vector[$key]) > 0) {
				$str.= '<ol>';
				foreach ($vector[$key] as $key2 => $value2) {
					//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
					//$str.= $key2;
					$str.= $this->_createCheck($key2, $role);
					///////////N3
					if (count($vector[$key][$key2]) > 0) {
						$str.= '<ul>';
						foreach ($vector[$key][$key2] as $key3 => $value3) {
							//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
							//$str.= $key3;
							$str.= $this->_createCheck($key3, $role);
							///////////N4
							if (count($vector[$key][$key2][$key3]) > 0) {
								$str.= '<ul>';
								foreach ($vector[$key][$key2][$key3] as $key4 => $value4) {
									//$str.= '<li><input type="checkbox" name="chkN1[]" id="chkN1" value="1"  checked = "checked" >';
									//$str.= $key4;
									$str.= $this->_createCheck($key4, $role);
									///////////N5
									if (count($vector[$key][$key2][$key3][$key4]) > 0) {
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
				$str.= '</ol>';
			}
			////////////N2
			$str.= '</li>';
		}
		//////////N1
		$str.= '</ul>';

		return $str;
	}

	private function _createCheck($menu, $role) {
		$exist = $this->AdmRolesMenu->find('count', array('conditions' => array('adm_menu_id' => $menu, 'adm_role_id' => $role)));
		$this->AdmRolesMenu->AdmMenu->id = $menu;
		$name = $this->AdmRolesMenu->AdmMenu->field('name');

		$checked = '';
		if ($exist > 0) {
			$checked = ' checked = "checked" ';
		}
		$str = '<li><label class="checkbox"><input type="checkbox" name="chkTree[]" value="' . $menu . '" ' . $checked . '  > ' . $name . '</label>';
		return $str;
	}

	public function ajax_list_menus() {
		if ($this->RequestHandler->isAjax()) {
			$role = $this->request->data['role'];
			$parentMenus = $this->request->data['parentMenus'];
//echo $module;
//echo $role;
			if ($role == "" OR $parentMenus == "") {
				$chkTree = "Debe existir un rol y un Menu Padre";
			} else {
				$chkTree = $this->_createCheckboxTree($role, $parentMenus); //Crestes checkbox tree 5 level, must improve
			}
			///////////////////////***************************************//////////////////
			$this->set(compact('chkTree'));
		} else {
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

	public function ajax_save() {
		if ($this->RequestHandler->isAjax()) {
			$role = $this->request->data['role'];
			$parentMenu = $this->request->data['parentMenus'];
			$type = $this->request->data['type'];

			if (isset($this->request->data['menu'])) {
				$new = $this->request->data['menu'];
			} else {
				$new = array();
			}
			////check type menu or menu inside
			$valueType = null;
			if ($type == 'inside') {
				$valueType = 1;
			}


			///////////OLD values
			$catchOld = $this->AdmRolesMenu->find('all', array(
				'fields' => array('AdmRolesMenu.adm_menu_id')
				, 'conditions' => array('OR' => array('AdmMenu.parent_node' => $parentMenu, 'AdmMenu.id' => $parentMenu), 'AdmRolesMenu.adm_role_id' => $role, 'AdmMenu.inside' => $valueType)
			));



			$old = array();
			if (count($catchOld) > 0) {
				foreach ($catchOld as $key => $value) {
					$old[$key] = $value['AdmRolesMenu']['adm_menu_id'];
				}
			}

			//debug($old);
			//debug($new);
			/////////////
			if (count($new) == 0 AND count($old) == 0) {
				echo 'successEmpty'; 
			} else {
				$insert = array_diff($new, $old);
				$delete = array_diff($old, $new);
				if($this->AdmRolesMenu->saveMenus($role, $insert, $delete)){
					echo 'success';
				}else{
					echo 'error';
				}
			}
		} else {
			$this->redirect($this->Auth->logout());
		}
	}

//END CLASS
}



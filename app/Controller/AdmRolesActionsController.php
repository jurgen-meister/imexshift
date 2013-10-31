<?php
App::uses('AppController', 'Controller');
/**
 * AdmRolesActions Controller
 *
 * @property AdmRolesAction $AdmRolesAction
 */
class AdmRolesActionsController extends AppController {


	public $layout = 'default';

	public function vsave() {
		
		$admRoles = $this->AdmRolesAction->AdmRole->find('list', array('order'=>array('AdmRole.id'=>'ASC')));
		$this->loadModel("AdmModule");
		$admModules = $this->AdmModule->find('list');
		///////////////////////***************************************//////////////////
		if(count($admRoles) > 0 AND count($admModules) > 0){
				$role = key($admRoles);
				$module =key($admModules);
				$chkTree = $this->_createCheckboxTree($role, $module); //Crestes checkbox tree 5 level, must improve
				
		}else{
				$chkTree = "Debe existir un rol y un modulo";
		}
		
		///////////////////////***************************************//////////////////

		$this->set(compact('admRoles', 'admModules','chkTree'));
	}
	
	
	private function _findMenus($var){
		$vec = $this->AdmRolesMenu->AdmMenu->find('list', array('fields'=>array('AdmMenu.id', 'AdmMenu.id') , 'order'=>array('AdmMenu.order_menu'),'conditions'=>array("AdmMenu.parent_node"=>$var)));;
		return $vec;
	}

	
	private function _createCheckboxTree($role, $module){
		//Til 5 levels, MUST be improved	
		//PART 1
		$this->AdmRolesAction->AdmAction->unbindModel(array('hasMany'=>array('AdmMenu')));
		$actions = $this->AdmRolesAction->AdmAction->find('all', array(
			 'fields'=>array('AdmAction.id', 'AdmAction.name', 'AdmController.id', 'AdmController.name'),
			 'order'=>array('AdmController.name', 'AdmAction.name') 
			,'conditions'=>array('AdmController.adm_module_id'=>$module)
		));
		//debug($actions);
		
		$this->loadModel("AdmController");
		$controllers = $this->AdmController->find("list", array("conditions"=>array('AdmController.adm_module_id'=>$module)));
		//debug($controllers);
		$data = array();
		$actionClean = array();
		//debug($actions);
		foreach ($actions as $keyAction => $action) {
			$actionClean[$keyAction] = $action["AdmAction"]["id"];
		}
		$checked = $this->AdmRolesAction->find("list", array(
			"fields"=>array("id", "adm_action_id"),
			"conditions"=>array("adm_action_id"=>$actionClean, "adm_role_id"=>$role)
		));
		
		foreach ($actions as $keyAction => $action) {
			foreach ($controllers as $keyController => $controller) {
				if($action["AdmController"]["id"] == $keyController){
					$actionId = $action["AdmAction"]["id"];
					$data[$keyController]["controllerName"] = $controller;
					$data[$keyController]["controllerId"] = $keyController;
					$data[$keyController]["actions"][$actionId]["actionId"] = $actionId;
					$data[$keyController]["actions"][$actionId]["actionName"] = $action["AdmAction"]["name"];
					$data[$keyController]["actions"][$actionId]["actionChecked"] = $this->_createCheckAction($checked, $actionId);
				}
			}
			
		}
		
//		debug($data);
//		$checked = $this->AdmRolesAction->find("all");

		
//		debug($checked);

		$str= '<ul id="tree1">';
		//////////N1
				foreach ($data as $key => $value) {
					$checkController = $this->_createCheckController($value["actions"]);
					$str.= '<li><label class="checkbox"><input type="checkbox" name="chkTree[]" '.$checkController.'  value="empty" >'.$value["controllerName"].'</label>';
					/*
					//$str.= $key;
					$str.= $this->_createCheck($key, $role);
					 */ 
					////////////N2
						if(count($value["actions"]) > 0){
							$str.= '<ul>';
							foreach ($value["actions"] as $key2 => $value2) {
//								$check = $this->_createCheck($checked, $value2["actionId"]);
								$str.= '<li><label class="checkbox"><input type="checkbox" name="chkTree[]"  value="'.$value2["actionId"].'"  '.$value2["actionChecked"].' >'.$value2["actionName"].'</label>';
								//$str.= $key2;
								//$str.= $this->_createCheck($key2, $role);
								///////////////////more children
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
	
	
	private function _createCheckAction($checked, $actionId){
		$str = '';
		if(count($checked) > 0){
			foreach ($checked as $key => $value) {
				if($value == $actionId){
					$str = 'checked = "checked"';
				}
			}
		}
		
		return $str;
	}
	
	private function _createCheckController($actions){
		if(count($actions) > 0){
			foreach ($actions as $key => $value) {
				if($value['actionChecked'] <> ''){
					return $value['actionChecked'];
				}
			}
		}
		return '';
	}
	
	public function ajax_list_menus(){
		if($this->RequestHandler->isAjax()){
			$role = $this->request->data['role'];		
			$module = $this->request->data['module'];		
//echo $module;
//echo $role;
				if($role == "" OR $module == ""){
					$chkTree = "Debe existir un rol y un módulo";
				}else{
					$chkTree = $this->_createCheckboxTree($role, $module); //Crestes checkbox tree 5 level, must improve
				}
			///////////////////////***************************************//////////////////
			$this->set(compact('chkTree'));
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	

	
	public function ajax_save(){
		if($this->RequestHandler->isAjax()){
			$role = $this->request->data['role'];
			$module = $this->request->data['module'];
			//Capture checkbox values

			
			if(isset($this->request->data['menu'])){
				$new = $this->request->data['menu']; 
			}else{
				$new = array();
			}
			
			//debug($new);
			
			////check type menu or menu inside

			///////////OLD values
			//$old = $this->AdmRolesMenu->AdmMenu->find('list', array('fields'=>array('AdmMenu.id', 'AdmMenu.id'),'conditions'=>array('AdmRolesMenu.adm_role_id'=>$role, 'AdmMenu.adm_module_id'=>1)));
			$this->AdmRolesAction->bindModel(array(
                    'belongsTo'=>array(
                        'AdmController' => array(
                            'foreignKey' => false,
                            'conditions' => array('AdmAction.adm_controller_id = AdmController.id', '')
                        )
                    )
                ));
			$catchOld = $this->AdmRolesAction->find('all', array(
				'fields'=>array('AdmRolesAction.adm_action_id')
				,'conditions'=>array('AdmRolesAction.adm_role_id'=>$role, 'AdmController.adm_module_id'=>$module)
				));			
			//debug($catchOld);
			
			
			
			$old=array();
			if(count($catchOld) > 0){
				foreach ($catchOld as $key => $value) {
					$old[$key]=$value['AdmRolesAction']['adm_action_id'];
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
                    $this->AdmRolesAction->deleteAll(array('adm_role_id'=>$role, 'adm_action_id' => $delete));
                    }
                //SAVE    
				if(count($insert)>0){
					
					$miData = array();
					$cont = 0;
					foreach($insert as $var){
						$miData[$cont]['adm_role_id'] = $role;
						$miData[$cont]['adm_action_id'] = $var;
						//$miData[$cont]['creator'] = $this->Session->read('UserRestriction.id');
						$cont++;
					}
					//debug($miData);
					
					$this->AdmRolesAction->saveMany($miData);
				}
				echo 'success'; // envia al data del js de jquery
			} 
 
		}else{//ajax
			$this->redirect($this->Auth->logout());
		}//ajax

	}//function
	

////// End Controller	
}

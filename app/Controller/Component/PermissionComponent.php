<?php

class PermissionComponent extends Component{
	function isAllowed($controllerName, $actionName, $normalActions){
		//Check all the Ajax actions inside the controller
		 App::import('Controller', $controllerName);
		$parentClassMethods = get_class_methods(get_parent_class($controllerName.'Controller'));
        //debug($parentClassMethods);
        $subClassMethods    = get_class_methods($controllerName.'Controller');
		//debug($subClassMethods);
        $classMethods       = array_diff($subClassMethods, $parentClassMethods);
		//debug($classMethods);
		$ajaxActions=array();
		foreach ($classMethods as $value) {
			if(strtolower(substr($value, 0, 4)) == 'ajax'){
				if(substr($value, 0, 1) <> '_'){ 
					$ajaxActions[$value]=$value;
				}
			}
		}
		$allowed = array_unique(array_merge($normalActions, $ajaxActions));
		
		//here must add login default action if it is controller AdmUsers, tomorrow
		//debug($allowed);

		if(count($allowed)>0){
			if(in_array($actionName, $allowed)){
				return true;
			}
		}
		return false;
		
		
	}
}
?>

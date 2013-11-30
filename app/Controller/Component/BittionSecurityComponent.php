<?php

class BittionSecurityComponent extends Component{
	
	private $userSessionPasswordKey = '495d654f495d654f495d654f495d654f';
	
	public function encryptUserSessionPassword($text){
//		$encrypt = Security::rijndael($auth['login'], '495d654f495d654f495d654f495d654f', 'encrypt');
		return Security::rijndael($text, $this->userSessionPasswordKey, 'encrypt');
	}
	
	public function decryptUserSessionPassword($text){
		return Security::rijndael($text, $this->userSessionPasswordKey, 'decrypt');
	}
	
	function allowPermission($controllerName, $actionName, $normalActions) {
		//Check all the Ajax actions inside the controller
		App::import('Controller', $controllerName);
		$parentClassMethods = get_class_methods(get_parent_class($controllerName . 'Controller'));
		//debug($parentClassMethods);
		$subClassMethods = get_class_methods($controllerName . 'Controller');
		//debug($subClassMethods);
		$classMethods = array_diff($subClassMethods, $parentClassMethods);
		//debug($classMethods);
		$ajaxActions = array();
		foreach ($classMethods as $value) {
			if (strtolower(substr($value, 0, 4)) == 'ajax') {
				if (substr($value, 0, 1) <> '_') {
					$ajaxActions[$value] = $value;
				}
			}
		}
		if (count($normalActions) == 0) {
			return false;
		}
		$allowed = array_unique(array_merge($normalActions, $ajaxActions));

		//To always allow login actions, otherwise it won't work
		//I moving this permission to AdmUser direct to the session Permision, because with the new AdmRolesAction it's not working unless it has one 
		//Permission User in the session THEREFORE I better move everything there
		//		if($controllerName == 'AdmUsers'){
		//			$allowed['welcome'] = 'welcome';
		//			$allowed['login'] = 'login';
		//			$allowed['logout'] = 'logout';
		//			$allowed['choose_role'] = 'choose_role';
		//			$allowed['change_password'] = 'change_password';
		//			$allowed['change_user_restriction'] = 'change_user_restriction';
		//			$allowed['change_email'] = 'change_email';
		//			$allowed['view_user_profile'] = 'view_user_profile';
		//			$allowed['ie_denied'] = 'ie_denied';
		//		}
		//debug($allowed);

		if (count($allowed) > 0) {
			if (in_array($actionName, $allowed)) {
				return true;
			}
		}
		return false;
	}
	
	
//END CLASS	
}
?>

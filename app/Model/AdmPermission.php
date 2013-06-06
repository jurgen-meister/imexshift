<?php
App::uses('AppModel', 'Model');
/**
 * AdmPermission Model
 *
 * @property AdmRole $AdmRole
 * @property AdmAction $AdmAction
 */
class AdmPermission extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'AdmRole' => array(
			'className' => 'AdmRole',
			'foreignKey' => 'adm_role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'AdmAction' => array(
			'className' => 'AdmAction',
			'foreignKey' => 'adm_action_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

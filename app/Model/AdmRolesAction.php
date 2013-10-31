<?php
App::uses('AppModel', 'Model');
/**
 * AdmRolesTransaction Model
 *
 * @property AdmRole $AdmRole
 * @property AdmTransaction $AdmTransaction
 */
class AdmRolesAction extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'adm_role_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'adm_action_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

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


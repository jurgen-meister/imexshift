<?php
App::uses('AppModel', 'Model');
/**
 * AdmRolesMenu Model
 *
 * @property AdmRole $AdmRole
 * @property AdmMenu $AdmMenu
 */
class AdmRolesMenu extends AppModel {

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
		'adm_menu_id' => array(
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
		'AdmMenu' => array(
			'className' => 'AdmMenu',
			'foreignKey' => 'adm_menu_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

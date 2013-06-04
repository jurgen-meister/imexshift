<?php
App::uses('AppModel', 'Model');
/**
 * AdmLogin Model
 *
 * @property AdmUser $AdmUser
 */
class AdmLogin extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'adm_user_id' => array(
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
		'AdmUser' => array(
			'className' => 'AdmUser',
			'foreignKey' => 'adm_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

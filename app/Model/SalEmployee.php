<?php
App::uses('AppModel', 'Model');
/**
 * SalEmployee Model
 *
 * @property SalCustomer $SalCustomer
 * @property SalSale $SalSale
 */
class SalEmployee extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SalCustomer' => array(
			'className' => 'SalCustomer',
			'foreignKey' => 'sal_customer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'SalSale' => array(
			'className' => 'SalSale',
			'foreignKey' => 'sal_employee_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}

<?php
App::uses('AppModel', 'Model');
/**
 * SalSale Model
 *
 * @property SalEmployee $SalEmployee
 * @property SalTaxNumber $SalTaxNumber
 * @property SalPayment $SalPayment
 * @property SalDetail $SalDetail
 */
class SalSale extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SalEmployee' => array(
			'className' => 'SalEmployee',
			'foreignKey' => 'sal_employee_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SalTaxNumber' => array(
			'className' => 'SalTaxNumber',
			'foreignKey' => 'sal_tax_number_id',
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
		'SalPayment' => array(
			'className' => 'SalPayment',
			'foreignKey' => 'sal_sale_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SalDetail' => array(
			'className' => 'SalDetail',
			'foreignKey' => 'sal_sale_id',
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

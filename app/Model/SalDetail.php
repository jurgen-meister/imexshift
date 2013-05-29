<?php
App::uses('AppModel', 'Model');
/**
 * SalDetail Model
 *
 * @property SalSale $SalSale
 * @property InvItem $InvItem
 */
class SalDetail extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SalSale' => array(
			'className' => 'SalSale',
			'foreignKey' => 'sal_sale_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'InvItem' => array(
			'className' => 'InvItem',
			'foreignKey' => 'inv_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

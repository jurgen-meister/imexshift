<?php
App::uses('AppModel', 'Model');
/**
 * InvPrice Model
 *
 * @property InvItem $InvItem
 * @property InvPriceType $InvPriceType
 */
class InvPrice extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'price';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'inv_item_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'inv_price_type_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'price' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'description' => array(
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
		'InvItem' => array(
			'className' => 'InvItem',
			'foreignKey' => 'inv_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'InvPriceType' => array(
			'className' => 'InvPriceType',
			'foreignKey' => 'inv_price_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

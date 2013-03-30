<?php
App::uses('AppModel', 'Model');
/**
 * InvMovement Model
 *
 * @property InvItem $InvItem
 * @property InvWarehouse $InvWarehouse
 * @property InvMovementType $InvMovementType
 */
class InvMovement extends AppModel {

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
		'inv_warehouse_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'inv_movement_type_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'date' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'quantity' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			
			'higherThanStock'=>array(
				'rule'=>array('higherThanStock'),
				'message'=>'La Cantidad de Salida es mayor al Stock'
			)
			
		),
	);

	//My validation's rules
	
	
	function beforeSave($options = array()) {
		//parent::beforeSave($options);
		$idItem = $this->data['InvMovement']['inv_item_id'];
		$idWarehouse = $this->data['InvMovement']['inv_warehouse_id'];
		$quantity = $this->data['InvMovement']['quantity'];
		//$status = $this->data['InvMovement']['status'];
		$availableQuantity = $this->_find_available_quantity($idItem, $idWarehouse);
		/////
		if(!isset($this->data['InvMovement']['status'])){
			if($quantity > $availableQuantity){
				return false;
			}
		}
		
		return true;
	}
	
	function _find_available_quantity($idItem, $idWarehouse){
		$stockIns = $this->find('all', array(
			'conditions'=>array('inv_item_id'=> $idItem,'inv_warehouse_id'=>$idWarehouse, 'InvMovementType.status' => 'entrada'),
			//'contain' => array('InvMovement'=>array('InvMovementType')),
			'fields'=>array('id', 'quantity')
		));
		
		$stockInsCleaned = $this->_clean_nested_arrays($stockIns);
		
		$stockOuts = $this->find('all', array(
			'conditions'=>array('inv_item_id'=> $idItem,'inv_warehouse_id'=>$idWarehouse, 'InvMovementType.status' => 'salida'),
			//'contain' => array('InvMovement'=>array('InvMovementType')),
			'fields'=>array('id', 'quantity')
		));
		
		$stockOutsCleaned = $this->_clean_nested_arrays($stockOuts);
		
		$add = array_sum($stockInsCleaned);
		//debug($add);
		$sub = array_sum($stockOutsCleaned);
		//debug($sub);
		$availableQuantity = $add - $sub;
		return $availableQuantity;
	}
	
	function _clean_nested_arrays($array){
		$clean = array();
		foreach ($array as $key => $value) {
			$clean[$key] = $value['InvMovement']['quantity'];
		}
		return $clean;
	}
	
	function higherThanStock($data){
		if(isset($this->data['InvMovement']['avaliable'])){
			if($data['quantity'] > $this->data['InvMovement']['avaliable']){
				return false;
			}
		}
		return true;
	}
	
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
		'InvWarehouse' => array(
			'className' => 'InvWarehouse',
			'foreignKey' => 'inv_warehouse_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'InvMovementType' => array(
			'className' => 'InvMovementType',
			'foreignKey' => 'inv_movement_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

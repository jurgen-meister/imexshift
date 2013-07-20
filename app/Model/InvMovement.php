<?php
App::uses('AppModel', 'Model');
/**
 * InvMovement Model
 *
 * @property InvMovementType $InvMovementType
 * @property InvMovementDetail $InvMovementDetail
 */
class InvMovement extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'code';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'code' => array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'InvMovementType' => array(
			'className' => 'InvMovementType',
			'foreignKey' => 'inv_movement_type_id',
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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'InvMovementDetail' => array(
			'className' => 'InvMovementDetail',
			'foreignKey' => 'inv_movement_id',
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

	public function saveMovement($dataSave){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		
		//Save for insert or update
		if($this->saveAll($dataSave)){	
		    $dataSource->commit();
			return $this->id;
		}else{
			$dataSource->rollback();
			return 'error';
		}	
	}
	
	public function saveItem($dataSaveMovement, $dataSaveMovementDetail){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		//for save Movement if doesn't exist
		//if(count($dataSaveMovement) > 0){
			if(!$this->saveAll($dataSaveMovement)){
				$dataSource->rollback();
				return 'error';
			}else{
				$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id']=$this->id;
			}
		//}
		
		//for save MovementDetail
		/*	
		$exist = $this->InvMovementDetail->find('count', array(
			'conditions'=>array(
				'InvMovementDetail.inv_movement_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'],
				'InvMovementDetail.inv_item_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_item_id']
			),
			
		));	
		if($exist == 0){*/
			if(!$this->InvMovementDetail->saveAll($dataSaveMovementDetail)){
				$dataSource->rollback();
				return 'error';
			}
		/*}else{
			$this->updateAll(
					array('InvMovementDetail.quantity'=>$dataSaveMovementDetail['InvMovementDetail']['quantity']), 
					array(
						'InvMovementDetail.inv_movement_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'],
						'InvMovementDetail.inv_item_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_item_id']
					)
			);
		}*/
		
		
		$dataSource->commit();
		return $dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'];
	}
	
	
//END MODEL
}

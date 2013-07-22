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

	public function saveMovement($dataMovement, $dataMovementDetail, $OPERATION){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		
		if(!$this->saveAll($dataMovement)){
			$dataSource->rollback();
			return 'error';
		}else{
			$dataMovementDetail['InvMovementDetail']['inv_movement_id']=$this->id;
		}
		switch ($OPERATION) {
			case 'ADD':
				if(!$this->InvMovementDetail->saveAll($dataMovementDetail)){
					$dataSource->rollback();
					return 'error';
				}
				break;
			case 'EDIT':
				if($this->InvMovementDetail->updateAll(array('InvMovementDetail.quantity'=>$dataMovementDetail['InvMovementDetail']['quantity']), array('InvMovementDetail.inv_movement_id'=>$dataMovementDetail['InvMovementDetail']['inv_movement_id'],	'InvMovementDetail.inv_item_id'=>$dataMovementDetail['InvMovementDetail']['inv_item_id']))){
					$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
				}
				if($rowsAffected == 0){
					$dataSource->rollback();
					return 'error';
				}
				break;
			case 'DELETE':
				if(!$this->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$dataMovementDetail['InvMovementDetail']['inv_movement_id'],	'InvMovementDetail.inv_item_id'=>$dataMovementDetail['InvMovementDetail']['inv_item_id']))){
					$dataSource->rollback();
					return 'error';
				}
				break;
		}
		$dataSource->commit();
		return $dataMovementDetail['InvMovementDetail']['inv_movement_id'];
	}

	public function saveMovementTransfer($dataMovement, $OPERATION, $tokenTransfer){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		//debug($dataMovement);
		if($tokenTransfer == 'INSERT'){
			if(!$this->saveAll($dataMovement, array('deep' => true))){
				$dataSource->rollback();
				return 'error';
			}
		}else{
			
			
			if($OPERATION <> 'DELETE'){
				//debug($dataMovement);
				$this->save($dataMovement[0]);
				$this->save($dataMovement[1]);
				if($OPERATION == 'EDIT'){
					$this->InvMovementDetail->updateAll(
							array('InvMovementDetail.quantity'=>$dataMovement[2]['InvMovementDetail']['quantity']),
							array('InvMovementDetail.inv_movement_id'=>array($dataMovement[0]['InvMovement']['id'], $dataMovement[1]['InvMovement']['id']),
								'InvMovementDetail.inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id'])
							);
				}
				if($OPERATION == 'ADD'){
					//debug($dataMovement[0]['InvMovement']['id']);
					//debug($dataMovement[1]['InvMovement']['id']);
					$this->InvMovementDetail->create();//without this doesn't clean and update (in the beginning just in case)
					if($this->InvMovementDetail->save(array('InvMovementDetail'=>array('inv_movement_id'=>$dataMovement[0]['InvMovement']['id'], 'inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id'],'quantity'=>$dataMovement[2]['InvMovementDetail']['quantity'])))){
						
					}
					$this->InvMovementDetail->create();//without this doesn't clean and update
					if($this->InvMovementDetail->save(array('InvMovementDetail'=>array('inv_movement_id'=>$dataMovement[1]['InvMovement']['id'], 'inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id'],'quantity'=>$dataMovement[2]['InvMovementDetail']['quantity'])))){
						
					}
				}	
			}else{
				if(!$this->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>array($dataMovement[0]['InvMovement']['id'], $dataMovement[1]['InvMovement']['id']),	'InvMovementDetail.inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id']))){
					$dataSource->rollback();
					return 'error';
				}
			}
		}
		
		$dataSource->commit();
		return $this->id;
		
	}
	
	
	public function addItem($dataSaveMovement, $dataSaveMovementDetail){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
			if(!$this->saveAll($dataSaveMovement)){
				$dataSource->rollback();
				return 'error';
			}else{
				$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id']=$this->id;
			}

			if(!$this->InvMovementDetail->saveAll($dataSaveMovementDetail)){
				$dataSource->rollback();
				return 'error';
			}
		$dataSource->commit();
		return $dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'];
	}
	
	public function editItem($dataSaveMovement, $dataSaveMovementDetail){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		//debug($dataSaveMovement);
		
		if(isset($dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'])){
			$action = 'update';
		}else{
			$action = 'insert';
		}
		
		if(!$this->saveAll($dataSaveMovement)){
			$dataSource->rollback();
			return 'error';
		}else{
			$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id']=$this->id;
			//debug($dataSaveMovementDetail['InvMovementDetail']['inv_movement_id']=$this->id);
		}
		//if movemementTypeId is 1(buy) or 2(sell)
		//must go saveAll blabla
		
		if($action == 'update'){
			if($this->InvMovementDetail->updateAll(array('InvMovementDetail.quantity'=>$dataSaveMovementDetail['InvMovementDetail']['quantity']), array('InvMovementDetail.inv_movement_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'],	'InvMovementDetail.inv_item_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_item_id']))){
				$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
			}
			if($rowsAffected == 0){
				$dataSource->rollback();
				return 'error';
			}
		}
		
		if($action == 'insert'){
			if(!$this->InvMovementDetail->saveAll($dataSaveMovementDetail)){
				$dataSource->rollback();
				return 'error';
			}
		}	
		
		$dataSource->commit();
		return $dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'];
	}
	
	public function deleteItem($dataSaveMovement, $dataSaveMovementDetail){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		if(!$this->saveAll($dataSaveMovement)){
			$dataSource->rollback();
			return 'error';
		}
		if(!$this->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'],	'InvMovementDetail.inv_item_id'=>$dataSaveMovementDetail['InvMovementDetail']['inv_item_id']))){
			$dataSource->rollback();
			return 'error';
		}
		$dataSource->commit();
		return $dataSaveMovementDetail['InvMovementDetail']['inv_movement_id'];
	}

	
	public function reduceCredits($id, $amount) { 
                if($this->updateAll( 
                                array( 
                                        'Manager.credit' => "Manager.credit-{$amount}" 
                                         ), 
                                array( 
                                        'Manager.id' => $id, 
                                        'Manager.credit >= ' => $amount 
                                        ) 
                                ) 
                        )  { 
                        return $this->getAffectedRows(); 
                } 
                return false; 
	} 
	
//END MODEL
}

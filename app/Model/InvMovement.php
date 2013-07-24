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

	public function saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		$token = '';
		if($ACTION == 'save_purchase_in' || $ACTION == 'save_sale_out'){
			if(!isset($dataMovement['InvMovement']['id'])){
				$token = 'INSERT';
			}
		}
		if(!$this->saveAll($dataMovement)){
			$dataSource->rollback();
			return 'error';
		}else{
			$idMovement = $this->id;
			if($token <> 'INSERT'){
				$dataMovementDetail['InvMovementDetail']['inv_movement_id']=$idMovement;
			}
		}
		
		
		if($token == 'INSERT'){//Create for purchase or sale
			for($i=0;$i<count($dataMovementDetail['InvMovementDetail']);$i++){
				$dataMovementDetail['InvMovementDetail'][$i]['inv_movement_id'] = $idMovement;
			}
			for($i=0;$i<count($dataMovementDetail['InvMovementDetail']);$i++){
				$this->InvMovementDetail->create();
				if(!$this->InvMovementDetail->save($dataMovementDetail['InvMovementDetail'][$i])){
					$dataSource->rollback();
					return 'error';
				}
			}
		}else{
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
		}
		
		
		$dataSource->commit();
		return $idMovement;
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
				if(!$this->save($dataMovement[0])){
					$dataSource->rollback();
					return 'error';
				}
				if(!$this->save($dataMovement[1])){
					$dataSource->rollback();
					return 'error';
				}
				if($OPERATION == 'EDIT'){
					if($this->InvMovementDetail->updateAll(
							array('InvMovementDetail.quantity'=>$dataMovement[2]['InvMovementDetail']['quantity']),
							array('InvMovementDetail.inv_movement_id'=>array($dataMovement[0]['InvMovement']['id'], $dataMovement[1]['InvMovement']['id']),
								'InvMovementDetail.inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id']))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'error';
					}
				}
				if($OPERATION == 'ADD'){
					//debug($dataMovement[0]['InvMovement']['id']);
					//debug($dataMovement[1]['InvMovement']['id']);
					$this->InvMovementDetail->create();//without this doesn't clean and update (in the beginning just in case)
					if(!$this->InvMovementDetail->save(array('InvMovementDetail'=>array('inv_movement_id'=>$dataMovement[0]['InvMovement']['id'], 'inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id'],'quantity'=>$dataMovement[2]['InvMovementDetail']['quantity'])))){
						$dataSource->rollback();
						return 'error';
					}
					$this->InvMovementDetail->create();//without this doesn't clean and update
					if(!$this->InvMovementDetail->save(array('InvMovementDetail'=>array('inv_movement_id'=>$dataMovement[1]['InvMovement']['id'], 'inv_item_id'=>$dataMovement[2]['InvMovementDetail']['inv_item_id'],'quantity'=>$dataMovement[2]['InvMovementDetail']['quantity'])))){
						$dataSource->rollback();
						return 'error';
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

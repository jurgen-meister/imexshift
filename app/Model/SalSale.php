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

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sal_employee_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'sal_tax_number_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
//		'doc_code' => array(
//			'notempty' => array(
//				'rule' => array('notempty'),
//				//'message' => 'Your custom message here',
//				//'allowEmpty' => false,
//				//'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//		),
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
	);

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
	
		public function saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION, $STATE, $movementDocCode, $dataPayDetail, $arraySalePrices){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		
		if(!$this->saveAll($dataMovement[0])){
			$dataSource->rollback();
			return 'error';
		}else{
			$idMovement1 = $this->id;
			$dataMovementDetail[0]['SalDetail']['sal_sale_id']=$idMovement1;
			if($dataPayDetail != null){
				$dataPayDetail['SalPayment']['sal_sale_id']=$idMovement1;
			}
		}
		if($ACTION=='save_order'){
			if(!$this->saveAll($dataMovement[1])){
				$dataSource->rollback();
				return 'error';
			}else{
				$idMovement2 = $this->id;
				$dataMovementDetail[1]['SalDetail']['sal_sale_id']=$idMovement2;
//				if($dataPayDetail != null){
//					$dataPayDetail['SalPayment']['sal_sale_id']=$idMovement;
//				}
			}
		}	
			switch ($OPERATION) {
				case 'ADD':
					if(!$this->SalDetail->saveAll($dataMovementDetail)){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'ADD_PAY':	
					if($dataPayDetail != null){
						if(!$this->SalPayment->saveAll($dataPayDetail)){
							$dataSource->rollback();
							return 'error';
						}
					}
					break;
				case 'EDIT':							//array fields
					if($this->SalDetail->updateAll(array('SalDetail.sale_price'=>$dataMovementDetail[0]['SalDetail']['sale_price'], 
															'SalDetail.quantity'=>$dataMovementDetail[0]['SalDetail']['quantity'], 
															'SalDetail.ex_sale_price'=>$dataMovementDetail[0]['SalDetail']['ex_sale_price']
															/*'SalDetail.fob_price'=>$dataMovementDetail['SalDetail']['fob_price'],
															'SalDetail.ex_fob_price'=>$dataMovementDetail['SalDetail']['ex_fob_price'],
															'SalDetail.cif_price'=>$dataMovementDetail['SalDetail']['cif_price'],
															'SalDetail.ex_cif_price'=>$dataMovementDetail['SalDetail']['ex_cif_price']*/), 
								/*array conditions*/array('SalDetail.sal_sale_id'=>$dataMovementDetail[0]['SalDetail']['sal_sale_id'], 
														'SalDetail.inv_warehouse_id'=>$dataMovementDetail[0]['SalDetail']['inv_warehouse_id'], 
														'SalDetail.inv_item_id'=>$dataMovementDetail[0]['SalDetail']['inv_item_id']
													))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'error';
					}
					if($ACTION=='save_order'){
						if($this->SalDetail->updateAll(array('SalDetail.sale_price'=>$dataMovementDetail[1]['SalDetail']['sale_price'], 
															'SalDetail.quantity'=>$dataMovementDetail[1]['SalDetail']['quantity'], 
															'SalDetail.ex_sale_price'=>$dataMovementDetail[1]['SalDetail']['ex_sale_price']				
															/*'SalDetail.fob_price'=>$dataMovementDetail['SalDetail']['fob_price'],
															'SalDetail.ex_fob_price'=>$dataMovementDetail['SalDetail']['ex_fob_price'],
															'SalDetail.cif_price'=>$dataMovementDetail['SalDetail']['cif_price'],
															'SalDetail.ex_cif_price'=>$dataMovementDetail['SalDetail']['ex_cif_price']*/), 
								/*array conditions*/array('SalDetail.sal_sale_id'=>$dataMovementDetail[1]['SalDetail']['sal_sale_id'], 
														'SalDetail.inv_warehouse_id'=>$dataMovementDetail[1]['SalDetail']['inv_warehouse_id'], 
														'SalDetail.inv_item_id'=>$dataMovementDetail[1]['SalDetail']['inv_item_id']
													))){
							$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
						}
						if($rowsAffected == 0){
							$dataSource->rollback();
							return 'error';
						}
					}
					break;
				case 'EDIT_PAY':
					if($this->SalPayment->updateAll(array('SalPayment.amount'=>$dataPayDetail['SalPayment']['amount'], 
															'SalPayment.description'=>"'".$dataPayDetail['SalPayment']['description']."'", 
															'SalPayment.ex_amount'=>$dataPayDetail['SalPayment']['ex_amount']),
								/*array conditions*/array('SalPayment.sal_sale_id'=>$dataPayDetail['SalPayment']['sal_sale_id'], 
														'SalPayment.sal_payment_type_id'=>$dataPayDetail['SalPayment']['sal_payment_type_id'],
														'SalPayment.date'=>$dataPayDetail['SalPayment']['date']))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'DELETE':
					if(!$this->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$dataMovementDetail[0]['SalDetail']['sal_sale_id'],	
															'SalDetail.inv_warehouse_id'=>$dataMovementDetail[0]['SalDetail']['inv_warehouse_id'], 
															'SalDetail.inv_item_id'=>$dataMovementDetail[0]['SalDetail']['inv_item_id']))){
						$dataSource->rollback();
						return 'error';
					}
					if($ACTION=='save_order'){
						if(!$this->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$dataMovementDetail[1]['SalDetail']['sal_sale_id'],	
																'SalDetail.inv_warehouse_id'=>$dataMovementDetail[1]['SalDetail']['inv_warehouse_id'], 
																'SalDetail.inv_item_id'=>$dataMovementDetail[1]['SalDetail']['inv_item_id']))){
							$dataSource->rollback();
							return 'error';
						}
					}	
					break;
				case 'DELETE_PAY':
					if(!$this->SalPayment->deleteAll(array('SalPayment.sal_sale_id'=>$dataPayDetail['SalPayment']['sal_sale_id'], 
															'SalPayment.date'=>$dataPayDetail['SalPayment']['date']))){
						$dataSource->rollback();
						return 'error';
					}
					break;
			}		
			
			if ($ACTION == 'save_invoice' && $STATE == 'SINVOICE_APPROVED'/*$arraySalePrices != null*/){
			
				if(!ClassRegistry::init('InvPrice')->saveAll($arraySalePrices)){
					$dataSource->rollback();
					return 'error';
				}
				
			}		
		$dataSource->commit();
		return $idMovement1;
	}
	
	public function saveGeneratedMovements($data){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		
		foreach($data AS $row) { 
			if(!ClassRegistry::init('InvMovement')->saveAll($row)){
				$dataSource->rollback();
				return 'error';
			}else{
				$idMovement[] = $this->id;
			}
		} 	
		$dataSource->commit();
		return array('SUCCESS', $idMovement);
	}	
}

<?php
App::uses('AppModel', 'Model');
/**
 * PurPurchase Model
 *
 * @property InvSupplier $InvSupplier
 * @property PurPrice $PurPrice
 * @property PurPayment $PurPayment
 * @property PurDetail $PurDetail
 */
class PurPurchase extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
//		'inv_supplier_id' => array(
//			'notempty' => array(
//				'rule' => array('notempty'),
//				//'message' => 'Your custom message here',
//				//'allowEmpty' => false,
//				//'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
//		),
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
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
//	public $belongsTo = array(
//		'InvSupplier' => array(
//			'className' => 'InvSupplier',
//			'foreignKey' => 'inv_supplier_id',
//			'conditions' => '',
//			'fields' => '',
//			'order' => ''
//		)
//	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'PurPrice' => array(
			'className' => 'PurPrice',
			'foreignKey' => 'pur_purchase_id',
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
		'PurPayment' => array(
			'className' => 'PurPayment',
			'foreignKey' => 'pur_purchase_id',
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
		'PurDetail' => array(
			'className' => 'PurDetail',
			'foreignKey' => 'pur_purchase_id',
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

	public function saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, $dataPayDetail, $dataCostDetail){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		if(!$this->saveAll($dataMovement)){
			$dataSource->rollback();
			return 'error';
		}else{
			$idMovement = $this->id;
				$dataMovementDetail['PurDetail']['pur_purchase_id']=$idMovement;
				if($dataPayDetail != null){
					$dataPayDetail['PurPayment']['pur_purchase_id']=$idMovement;
				}
				if($dataCostDetail != null){
					$dataCostDetail['PurPrice']['pur_purchase_id']=$idMovement;
				}
		}
		
			switch ($OPERATION) {
				case 'ADD':
					if(!$this->PurDetail->saveAll($dataMovementDetail)){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'ADD_PAY':	
					if($dataPayDetail != null){
						if(!$this->PurPayment->saveAll($dataPayDetail)){
							$dataSource->rollback();
							return 'error';
						}
					}
					break;
				case 'ADD_COST':	
					if($dataCostDetail != null){
						if(!$this->PurPrice->saveAll($dataCostDetail)){
							$dataSource->rollback();
							return 'error';
						}
					}
					break;	
				case 'EDIT':							//array fields
					if($this->PurDetail->updateAll(array('PurDetail.ex_fob_price'=>$dataMovementDetail['PurDetail']['ex_fob_price'], 
															'PurDetail.quantity'=>$dataMovementDetail['PurDetail']['quantity'], 
															'PurDetail.fob_price'=>$dataMovementDetail['PurDetail']['fob_price']/*,
															'PurDetail.fob_price'=>$dataMovementDetail['PurDetail']['fob_price'],
															'PurDetail.ex_fob_price'=>$dataMovementDetail['PurDetail']['ex_fob_price'],
															'PurDetail.cif_price'=>$dataMovementDetail['PurDetail']['cif_price'],
															'PurDetail.ex_cif_price'=>$dataMovementDetail['PurDetail']['ex_cif_price']*/), 
								/*array conditions*/array('PurDetail.pur_purchase_id'=>$dataMovementDetail['PurDetail']['pur_purchase_id'], 
														'PurDetail.inv_supplier_id'=>$dataMovementDetail['PurDetail']['inv_supplier_id'], 
														'PurDetail.inv_item_id'=>$dataMovementDetail['PurDetail']['inv_item_id']))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'EDIT_PAY':
					if($this->PurPayment->updateAll(array('PurPayment.amount'=>$dataPayDetail['PurPayment']['amount'], 
															'PurPayment.description'=>"'".$dataPayDetail['PurPayment']['description']."'", 
															'PurPayment.ex_amount'=>$dataPayDetail['PurPayment']['ex_amount']),
								/*array conditions*/array('PurPayment.pur_purchase_id'=>$dataPayDetail['PurPayment']['pur_purchase_id'], 
														'PurPayment.pur_payment_type_id'=>$dataPayDetail['PurPayment']['pur_payment_type_id'],	
														'PurPayment.date'=>$dataPayDetail['PurPayment']['date']))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'EDIT_COST':
					if($this->PurPrice->updateAll(array('PurPrice.amount'=>$dataCostDetail['PurPrice']['amount'], 				
															'PurPrice.ex_amount'=>$dataCostDetail['PurPrice']['ex_amount']),
								/*array conditions*/array('PurPrice.pur_purchase_id'=>$dataCostDetail['PurPrice']['pur_purchase_id'], 
														'PurPrice.inv_price_type_id'=>$dataCostDetail['PurPrice']['inv_price_type_id']))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'error';
					}
					break;	
				case 'DELETE':
					if(!$this->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$dataMovementDetail['PurDetail']['pur_purchase_id'],	
//															'PurDetail.inv_supplier_id'=>$dataMovementDetail['PurDetail']['inv_supplier_id'], 
															'PurDetail.inv_item_id'=>$dataMovementDetail['PurDetail']['inv_item_id']))){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'DELETE_PAY':
					if(!$this->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$dataPayDetail['PurPayment']['pur_purchase_id'], 
															'PurPayment.date'=>$dataPayDetail['PurPayment']['date']))){
						$dataSource->rollback();
						return 'error';
					}
					break;
				case 'DELETE_COST':
					if(!$this->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$dataCostDetail['PurPrice']['pur_purchase_id'], 
															'PurPrice.inv_price_type_id'=>$dataCostDetail['PurPrice']['inv_price_type_id']))){
						$dataSource->rollback();
						return 'error';
					}
					break;	
			}		
		$dataSource->commit();
		return $idMovement;
	}
	
}

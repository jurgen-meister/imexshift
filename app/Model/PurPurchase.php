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

	public function saveMovement($dataPurchase, $dataPurchaseDetail, $dataMovement, $dataMovementDetail, $dataMovementHeadsUpd, $OPERATION, $ACTION, $STATE, $dataPayDetail, $dataCostDetail){
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		
		///////////////////////////////////////Start - Save Movement////////////////////////////////////////////
		/*Saving Order*/
		if(!$this->saveAll($dataPurchase[0])){
			$dataSource->rollback();
			return 'ERROR';
		}else{
			$idPurchase1 = $this->id;
			$dataPurchaseDetail[0]['PurDetail']['pur_purchase_id']=$idPurchase1;
			if($dataPayDetail != null){
				$dataPayDetail['PurPayment']['pur_purchase_id']=$idPurchase1;
			}
			if($dataCostDetail != null){
				$dataCostDetail['PurPrice']['pur_purchase_id']=$idPurchase1;
			}
		}
		if($ACTION=='save_order'){
			/*Saving Invoice*/
			if(!$this->saveAll($dataPurchase[1])){
				$dataSource->rollback();
				return 'ERROR';
			}else{
				$idPurchase2 = $this->id;
				$dataPurchaseDetail[1]['PurDetail']['pur_purchase_id']=$idPurchase2;
			}
		}	
		if($OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY' && $OPERATION != 'ADD_COST' && $OPERATION != 'EDIT_COST' && $OPERATION != 'DELETE_COST'){
			/*Saving Movement*/
			if(!ClassRegistry::init('InvMovement')->saveAll($dataMovement)){
				$dataSource->rollback();
				return 'ERROR';
			}else{
				$idMovement = ClassRegistry::init('InvMovement')->id;
				$dataMovementDetail['InvMovementDetail']['inv_movement_id']=$idMovement;
			}
		}	
			
		/*Updating Movement Heads*/
		if($dataMovementHeadsUpd <> null){
			if(!ClassRegistry::init('InvMovement')->saveAll($dataMovementHeadsUpd)){
				$dataSource->rollback();
				return 'ERROR';
			}
		}	
					
		///////////////////////////////////////End - Save Movement////////////////////////////////////////////
		
			switch ($OPERATION) {
				case 'ADD':
					if(!$this->PurDetail->saveAll($dataPurchaseDetail[0])){
						$dataSource->rollback();
						return 'ERROR';
					}
					if($ACTION=='save_order'){
						if(!$this->PurDetail->saveAll($dataPurchaseDetail[1])){
							$dataSource->rollback();
							return 'ERROR';
						}
					}	
					if(!ClassRegistry::init('InvMovement')->InvMovementDetail->saveAll($dataMovementDetail)){
						$dataSource->rollback();
						return 'ERROR';
					}
					
					break;
				case 'ADD_PAY':	
					if($dataPayDetail != null){
						if(!$this->PurPayment->saveAll($dataPayDetail)){
							$dataSource->rollback();
							return 'ERROR';
						}
					}
					break;
				case 'ADD_COST':	
					if($dataCostDetail != null){
						if(!$this->PurPrice->saveAll($dataCostDetail)){
							$dataSource->rollback();
							return 'ERROR';
						}
					}
					break;	
				case 'EDIT':							//array fields
					if($this->PurDetail->updateAll(array('PurDetail.ex_fob_price'=>$dataPurchaseDetail[0]['PurDetail']['ex_fob_price'], 
															'PurDetail.quantity'=>$dataPurchaseDetail[0]['PurDetail']['quantity'], 
															'PurDetail.fob_price'=>$dataPurchaseDetail[0]['PurDetail']['fob_price']/*,
															'PurDetail.fob_price'=>$dataMovementDetail['PurDetail']['fob_price'],
															'PurDetail.ex_fob_price'=>$dataMovementDetail['PurDetail']['ex_fob_price'],
															'PurDetail.cif_price'=>$dataMovementDetail['PurDetail']['cif_price'],
															'PurDetail.ex_cif_price'=>$dataMovementDetail['PurDetail']['ex_cif_price']*/), 
								/*array conditions*/array('PurDetail.pur_purchase_id'=>$dataPurchaseDetail[0]['PurDetail']['pur_purchase_id'], 
														'PurDetail.inv_supplier_id'=>$dataPurchaseDetail[0]['PurDetail']['inv_supplier_id'], 
														'PurDetail.inv_item_id'=>$dataPurchaseDetail[0]['PurDetail']['inv_item_id']))){
						$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'ERROR';
					}
					if($ACTION=='save_order'){
						if($this->PurDetail->updateAll(array('PurDetail.ex_fob_price'=>$dataPurchaseDetail[1]['PurDetail']['ex_fob_price'], 
																'PurDetail.quantity'=>$dataPurchaseDetail[1]['PurDetail']['quantity'], 
																'PurDetail.fob_price'=>$dataPurchaseDetail[1]['PurDetail']['fob_price']/*,
																'PurDetail.fob_price'=>$dataMovementDetail['PurDetail']['fob_price'],
																'PurDetail.ex_fob_price'=>$dataMovementDetail['PurDetail']['ex_fob_price'],
																'PurDetail.cif_price'=>$dataMovementDetail['PurDetail']['cif_price'],
																'PurDetail.ex_cif_price'=>$dataMovementDetail['PurDetail']['ex_cif_price']*/), 
									/*array conditions*/array('PurDetail.pur_purchase_id'=>$dataPurchaseDetail[1]['PurDetail']['pur_purchase_id'], 
															'PurDetail.inv_supplier_id'=>$dataPurchaseDetail[1]['PurDetail']['inv_supplier_id'], 
															'PurDetail.inv_item_id'=>$dataPurchaseDetail[1]['PurDetail']['inv_item_id']))){
							$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
						}
						if($rowsAffected == 0){
							$dataSource->rollback();
							return 'ERROR';
						}					
					}	
					if(ClassRegistry::init('InvMovement')->InvMovementDetail->updateAll(array('InvMovementDetail.quantity'=>$dataMovementDetail['InvMovementDetail']['quantity']), 
																						array('InvMovementDetail.inv_movement_id'=>$dataMovementDetail['InvMovementDetail']['inv_movement_id'],	
																							'InvMovementDetail.inv_item_id'=>$dataMovementDetail['InvMovementDetail']['inv_item_id']))){
							$rowsAffected = $this->getAffectedRows();//must do this because updateAll always return true
					}
					if($rowsAffected == 0){
						$dataSource->rollback();
						return 'ERROR';
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
						return 'ERROR';
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
						return 'ERROR';
					}
					break;	
				case 'DELETE':
					if(!$this->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$dataPurchaseDetail[0]['PurDetail']['pur_purchase_id'],	
															'PurDetail.inv_supplier_id'=>$dataPurchaseDetail[0]['PurDetail']['inv_supplier_id'], 
															'PurDetail.inv_item_id'=>$dataPurchaseDetail[0]['PurDetail']['inv_item_id']))){
						$dataSource->rollback();
						return 'ERROR';
					}
					if($ACTION=='save_order'){
						if(!$this->PurDetail->deleteAll(array('PurDetail.pur_purchase_id'=>$dataPurchaseDetail[1]['PurDetail']['pur_purchase_id'],	
																'PurDetail.inv_supplier_id'=>$dataPurchaseDetail[1]['PurDetail']['inv_supplier_id'], 
																'PurDetail.inv_item_id'=>$dataPurchaseDetail[1]['PurDetail']['inv_item_id']))){
							$dataSource->rollback();
							return 'ERROR';
						}
					}
					if(!ClassRegistry::init('InvMovement')->InvMovementDetail->deleteAll(array('InvMovementDetail.inv_movement_id'=>$dataMovementDetail['InvMovementDetail']['inv_movement_id'],	
																									'InvMovementDetail.inv_item_id'=>$dataMovementDetail['InvMovementDetail']['inv_item_id']))){
						$dataSource->rollback();
						return 'ERROR';
					}
					
					break;
				case 'DELETE_PAY':
					if(!$this->PurPayment->deleteAll(array('PurPayment.pur_purchase_id'=>$dataPayDetail['PurPayment']['pur_purchase_id'], 
															'PurPayment.date'=>$dataPayDetail['PurPayment']['date']))){
						$dataSource->rollback();
						return 'ERROR';
					}
					break;
				case 'DELETE_COST':
					if(!$this->PurPrice->deleteAll(array('PurPrice.pur_purchase_id'=>$dataCostDetail['PurPrice']['pur_purchase_id'], 
															'PurPrice.inv_price_type_id'=>$dataCostDetail['PurPrice']['inv_price_type_id']))){
						$dataSource->rollback();
						return 'ERROR';
					}
					break;	
			}		
		$dataSource->commit();
		return array('SUCCESS', $STATE.'|'.$idPurchase1);
	}
	
}

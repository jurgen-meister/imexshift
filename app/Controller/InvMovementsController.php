<?php
App::uses('AppController', 'Controller');
/**
 * InvMovements Controller
 *
 * @property InvMovement $InvMovement
 */
class InvMovementsController extends AppController {

/**
 *  Layout
 *
 * @var string
 */
	public $layout = 'default';

/**
 * Helpers
 *
 * @var array
 */
//	public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
//	public $components = array('Session');
	//*******************************************************************************************************//
	///////////////////////////////////////// START - FUNCTIONS ///////////////////////////////////////////////
	//*******************************************************************************************************//
	
	//////////////////////////////////////////// START - PDF ///////////////////////////////////////////////
	public function view_document_movement_pdf($id = null) {
		
		$this->InvMovement->id = $id;
		
		if (!$this->InvMovement->exists()) {
			throw new NotFoundException(__('Invalid post'));
		}
		// increase memory limit in PHP 
		ini_set('memory_limit', '512M');
		$movement = $this->InvMovement->read(null, $id);
		
		if($movement['InvMovement']['inv_movement_type_id'] == 4){
			$this->redirect(array('action'=>'index_warehouses_transfer'));
		}
		
		if($movement['InvMovement']['inv_movement_type_id'] == 3){
			
			$movementIdOut = $this->InvMovement->find('all', array(
				'conditions'=>array(
					'InvMovement.document_code'=>$movement['InvMovement']['document_code'],
					'InvMovement.inv_movement_type_id ='=>4
			)));//Out Origin
			$movement['Transfer']['code'] = $movementIdOut[0]['InvMovement']['code'];
			$movement['Transfer']['warehouseName'] = $movementIdOut[0]['InvWarehouse']['name'];
		}
		
		
		$details=$this->_get_movements_details_without_stock($id);
		$this->set('movement', $movement);
		$this->set('details', $details);
	}
	//////////////////////////////////////////// END - PDF /////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - REPORT ////////////////////////////////////////////////
	public function report(){
		$this->loadModel("InvWarehouse");
		$warehouse = $this->InvWarehouse->find('list');
		$item = $this->_find_items();
		$this->set(compact("warehouse", "item"));
	}
	
	private function _find_items($type = 'none', $selected = array()){
		$conditions = array();
		$order = array('InvItem.name');
		switch ($type){
			case 'category':
				$conditions = array('InvItem.inv_category_id'=>$selected);
				$order = array('InvCategory.name');
				break;
			case 'brand':
				$conditions = array('InvItem.inv_brand_id'=>$selected);
				$order = array('InvBrand.name');
				break;
		}
			
		$this->loadModel("InvItem");
		$this->InvItem->unbindModel(array('hasMany' => array('InvPrice', 'InvCategory', 'InvMovementDetail', 'InvItemsSupplier')));
		return $this->InvItem->find("all", array(
					"fields"=>array('InvItem.code', 'InvItem.name', 'InvCategory.name', 'InvBrand.name', 'InvItem.id'),
					"conditions"=>$conditions,
					"order"=>$order
				));
	}
	
	public function ajax_get_group_items_and_filters(){
		if($this->RequestHandler->isAjax()){
			$type = $this->request->data['type'];
			$group = array();
			switch ($type) {
				case 'category':
					$this->loadModel("InvCategory");
					$group = $this->InvCategory->find("list", array("order"=>array("InvCategory.name")));
					$this->set('group', $group);
					break;
				case 'brand':
					$this->loadModel("InvBrand");
					$group = $this->InvBrand->find("list", array("order"=>array("InvBrand.name")));
					$this->set('group', $group);
					break;
			}
			$item = $this->_find_items($type, array_keys($group));
			$this->set(compact("item"));
		}
	}
	
	public function ajax_get_group_items(){
		if($this->RequestHandler->isAjax()){
			$type = $this->request->data['type'];
			if(isset($this->request->data['selected'])){
				$selected = $this->request->data['selected'];
			}else{
				$selected = array(); 
			}
			$item = $this->_find_items($type, $selected);
			$this->set(compact("item"));
		}
	}
	
	public function ajax_generate_report(){
		if($this->RequestHandler->isAjax()){
			$startDate = $this->request->data['startDate'];
			$finishDate = $this->request->data['finishDate'];
			$movementType = $this->request->data['movementType'];
			$warehouse = $this->request->data['warehouses'];
			$items = $this->request->data['items'];
			
			//BEFORE WHEN SENDING
			$this->Session->write('ReportMovement.startDate', $startDate);
			$this->Session->write('ReportMovement.finishDate', $finishDate);
			$this->Session->write('ReportMovement.movementType', $movementType);
			$this->Session->write('ReportMovement.warehouse', $warehouse);
			$this->Session->write('ReportMovement.items', $items);
			echo 'success';
			 
		
			/*
			$movements = $this->InvMovement->InvMovementDetail->find('all', array(
				'conditions'=>array(
					'InvMovementDetail.inv_item_id'=>$items,
					'InvMovement.inv_warehouse_id'=>$warehouse,
					//'InvMovement.inv_movement_type_id'=>$movementType,
					'InvMovement.date BETWEEN ? AND ?' => array($startDate,$finishDate)
				),
				'fields'=>array('InvMovement.id', 'InvMovement.code', 'InvMovementDetail.inv_item_id', 'InvMovementDetail.quantity', 'InvMovement.date'),
				'order'=>array('InvMovementDetail.inv_item_id', 'InvMovement.id')
			));
			//debug($items);
			//debug($movements);
			$auxArray = array();
			foreach($items as $item){
				$totalQuantity = 0;
				foreach($movements as $movement){
					if($movement['InvMovementDetail']['inv_item_id'] == $item){
						$auxArray[$item]['movements'][] = array(
							'code'=>$movement['InvMovement']['code'],
							'quantity'=> $movement['InvMovementDetail']['quantity'],
							'date'=>date("d/m/Y", strtotime($movement['InvMovement']['date']))
							);
						$totalQuantity = $totalQuantity + $movement['InvMovementDetail']['quantity'];
					}
				}
				//stock solo irian en IN AND OUT all
				//$auxArray[$item]['stockFechaInicio']='algo va aqui';
				//$auxArray[$item]['stockFechaFin']='algo va aqui';
				//$auxArray[$item]['stockActual']='algo va aqui';
				if($totalQuantity <> 0){
					$auxArray[$item]['totalQuantity']=$totalQuantity;
					$auxArray[$item]['brands']='algo va aqui';
					$auxArray[$item]['categories']='algo va aqui';
					$auxArray[$item]['codeName']='[ ECO-102 ] laptop';
				}else{
					$auxArray[$item]['codeName']='ESTA VACIO';
				}
			}
			debug($auxArray);
			$this->Session->write('ReportMovement', $auxArray);
			*/
		///END AJAX
		}
	}
	
	public function report_movements_pdf(){
		//MAYBE - This whole process could go to the ajax function, so there could be a processing message blocking all the screen
		
		//put session data sent data into variables
		$startDate = $this->Session->read('ReportMovement.startDate');
		$finishDate = $this->Session->read('ReportMovement.finishDate');
		$movementType = $this->Session->read('ReportMovement.movementType');//must fix values inside combobox then will work correctly plus many ifs
		$warehouse = $this->Session->read('ReportMovement.warehouse');
		$itemsIds = $this->Session->read('ReportMovement.items');
		
		////get specific data like names, codes, etc  (must see if I can send it through ajax) nevertheless query takes just 4ms with 280items
		$this->loadModel('InvItem');
		$this->InvItem->unbindModel(array('hasMany' => array('InvMovementDetail', 'PurDetail', 'SalDetail', 'InvItemsSupplier', 'InvPrice')));
		$items = $this->InvItem->find('all', array(
			'fields'=>array('InvItem.id', 'InvItem.code', 'InvItem.name', 'InvBrand.name', 'InvCategory.name'),
			'conditions'=>array('InvItem.id'=>$itemsIds)
		));
		
		
		
		////get all movements with filters sent
		$this->InvMovement->InvMovementDetail->unbindModel(array('belongsTo' => array('InvItem')));
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array(
				'InvMovementDetail.inv_item_id'=>$itemsIds,
				'InvMovement.inv_warehouse_id'=>$warehouse,
				'InvMovement.date BETWEEN ? AND ?' => array($startDate, $finishDate)
			),
			'fields'=>array('InvMovement.id', 'InvMovement.code', 'InvMovementDetail.inv_item_id', 'InvMovementDetail.quantity', 'InvMovement.date'),
			'order'=>array('InvMovementDetail.inv_item_id', 'InvMovement.id')
		));
		
		//format data, grouping items with its respective movements
			$auxArray = array();
			foreach($items as $itemVal){
				$item = $itemVal['InvItem']['id'];
				$totalQuantity = 0;
				
				foreach($movements as $movement){
					if($movement['InvMovementDetail']['inv_item_id'] == $item){
						$auxArray[$item]['movements'][] = array(
							'code'=>$movement['InvMovement']['code'],
							'quantity'=> $movement['InvMovementDetail']['quantity'],
							'date'=>date("d/m/Y", strtotime($movement['InvMovement']['date']))

							);
						$totalQuantity = $totalQuantity + $movement['InvMovementDetail']['quantity'];
					}
				}
			
				//stock solo irian en IN AND OUT all
				//$auxArray[$item]['stockFechaInicio']='algo va aqui';
				//$auxArray[$item]['stockFechaFin']='algo va aqui';
				//$auxArray[$item]['stockActual']='algo va aqui';
					$auxArray[$item]['totalQuantity']=$totalQuantity;
					$auxArray[$item]['brands']=$itemVal['InvBrand']['name'];
					$auxArray[$item]['categories']=$itemVal['InvCategory']['name'];
					$auxArray[$item]['codeName']='[ '. $itemVal['InvItem']['code'].' ] '.$itemVal['InvItem']['name'];
				if($totalQuantity == 0){
					$auxArray[$item]['movements']=array();
				}
			}
			//debug($auxArray);
			//$this->Session->write('ReportMovement', $auxArray);
			//$this->set(compact('catch', 'auxArray'));
			$this->set(compact('auxArray'));
	}
	
	public function prueba(){
		$this->layout = 'print';
		//put session data sent data into variables
		$startDate = $this->Session->read('ReportMovement.startDate');
		$finishDate = $this->Session->read('ReportMovement.finishDate');
		$movementType = $this->Session->read('ReportMovement.movementType');//must fix values inside combobox then will work correctly plus many ifs
		$movementTypeName = 'TODAS LAS ENTRADAS';
		$warehouse = $this->Session->read('ReportMovement.warehouse');
		$warehouseName = 'SAN PEDRO';
		$itemsIds = $this->Session->read('ReportMovement.items');
		$currencyName = 'BOLIVIANOS';
		$documentHeader = array('startDate'=>$startDate, 'finishDate'=>$finishDate, 'movementTypeName'=>$movementTypeName, 'warehouseName'=>$warehouseName, 'currencyName'=>$currencyName);
		
		////get specific data like names, codes, etc  (must see if I can send it through ajax) nevertheless query takes just 4ms with 280items
		$this->loadModel('InvItem');
		$this->InvItem->unbindModel(array('hasMany' => array('InvMovementDetail', 'PurDetail', 'SalDetail', 'InvItemsSupplier', 'InvPrice')));
		$items = $this->InvItem->find('all', array(
			'fields'=>array('InvItem.id', 'InvItem.code', 'InvItem.name', 'InvBrand.name', 'InvCategory.name'),
			'conditions'=>array('InvItem.id'=>$itemsIds)
		));
		
		
		
		////get all movements with filters sent
		$this->InvMovement->InvMovementDetail->unbindModel(array('belongsTo' => array('InvItem')));
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array(
				'InvMovementDetail.inv_item_id'=>$itemsIds,
				'InvMovement.inv_warehouse_id'=>$warehouse,
				'InvMovement.date BETWEEN ? AND ?' => array($startDate, $finishDate)
			),
			'fields'=>array('InvMovement.id', 'InvMovement.code', 'InvMovement.date', 'InvMovementDetail.inv_item_id', 'InvMovementDetail.quantity', 'InvMovementDetail.fob_price', 'InvMovementDetail.cif_price', 'InvMovementDetail.sale_price'),
			'order'=>array('InvMovementDetail.inv_item_id', 'InvMovement.id')
		));
		
		//format data, grouping items with its respective movements
			$auxArray = array();
			foreach($items as $itemVal){
				$item = $itemVal['InvItem']['id'];
				$totalQuantity = 0;
				$totalFob = 0;
				$totalCif = 0;
				$totalSale = 0;
				$totalFobQuantity = 0;
				$totalCifQuantity = 0;
				$totalSaleQuantity = 0;
				foreach($movements as $movement){
					if($movement['InvMovementDetail']['inv_item_id'] == $item){
						$fobQuantity = $movement['InvMovementDetail']['quantity'] * $movement['InvMovementDetail']['fob_price'];
						$cifQuantity = $movement['InvMovementDetail']['quantity'] * $movement['InvMovementDetail']['cif_price'];
						$saleQuantity = $movement['InvMovementDetail']['quantity'] * $movement['InvMovementDetail']['sale_price'];
						$auxArray[$item]['movements'][] = array(
							'code'=>$movement['InvMovement']['code'],
							'quantity'=> $movement['InvMovementDetail']['quantity'],
							'date'=>date("d/m/Y", strtotime($movement['InvMovement']['date'])),
							'quantity'=> $movement['InvMovementDetail']['quantity'],
							'fob'=> $movement['InvMovementDetail']['fob_price'],
							'cif'=> $movement['InvMovementDetail']['cif_price'],
							'sale'=> $movement['InvMovementDetail']['sale_price'],
							'fobQuantity'=>number_format($fobQuantity,2),
							'cifQuantity'=>number_format($cifQuantity,2),
							'saleQuantity'=>number_format($saleQuantity,2)
							);
						$totalQuantity = $totalQuantity + $movement['InvMovementDetail']['quantity'];
						$totalFob = $totalFob + $movement['InvMovementDetail']['fob_price'];
						$totalCif = $totalCif + $movement['InvMovementDetail']['cif_price'];
						$totalSale = $totalSale + $movement['InvMovementDetail']['sale_price'];
						$totalFobQuantity = $totalFobQuantity + $fobQuantity;
						$totalCifQuantity = $totalCifQuantity + $cifQuantity;
						$totalSaleQuantity = $totalSaleQuantity + $saleQuantity;
					}
				}
			
				//stock solo irian en IN AND OUT all
				//$auxArray[$item]['stockFechaInicio']='algo va aqui';
				//$auxArray[$item]['stockFechaFin']='algo va aqui';
				//$auxArray[$item]['stockActual']='algo va aqui';
					$auxArray[$item]['totalQuantity']=$totalQuantity;
					$auxArray[$item]['totalFob']=number_format($totalFob,2);
					$auxArray[$item]['totalFobQuantity']=number_format($totalFobQuantity,2);
					$auxArray[$item]['totalCif']=number_format($totalCif,2);
					$auxArray[$item]['totalCifQuantity']=number_format($totalCifQuantity,2);
					$auxArray[$item]['totalSale']=number_format($totalSale,2);
					$auxArray[$item]['totalSaleQuantity']=number_format($totalSaleQuantity,2);
					$auxArray[$item]['brands']=$itemVal['InvBrand']['name'];
					$auxArray[$item]['categories']=$itemVal['InvCategory']['name'];
					$auxArray[$item]['codeName']='[ '. $itemVal['InvItem']['code'].' ] '.$itemVal['InvItem']['name'];
				if($totalQuantity == 0){
					$auxArray[$item]['movements']=array();
				}
			}
			$this->set(compact('auxArray', 'documentHeader'));
	}
	
	public function prueba2(){
		//debug($this->InvMovement->InvMovementDetail->find('all', array('conditions'=>array('InvMovementDetail.inv_item_id'=>9, 'InvMovement.inv_warehouse_id'=>1))));
		
		$this->loadModel('InvItem');
		$this->InvItem->unbindModel(array('hasMany' => array('InvMovementDetail', 'PurDetail', 'SalDetail', 'InvItemsSupplier', 'InvPrice')));
		$hose = $this->InvItem->find('all', array('fields'=>array('InvItem.id', 'InvItem.code', 'InvItem.name', 'InvBrand.name', 'InvCategory.name')));
		debug($hose);
		//$items = array(9,10,11,12,13,14,15);
		//$items = array(9);
		//$this->loadModel('InvItem');
		$items = $this->InvItem->find('list', array('fields'=>array('InvItem.id', 'InvItem.id'))); 
		
		$this->InvMovement->InvMovementDetail->unbindModel(array('belongsTo' => array('InvItem')));
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array(
				'InvMovementDetail.inv_item_id'=>$items,
				'InvMovement.inv_warehouse_id'=>1,
				'InvMovement.date BETWEEN ? AND ?' => array('01/07/2013','31/07/2013')
			),
			'fields'=>array('InvMovement.id', 'InvMovement.code', 'InvMovementDetail.inv_item_id', 'InvMovementDetail.quantity', 'InvMovement.date'/*, 'InvItem.code', 'InvItem.name'*/),
			'order'=>array('InvMovementDetail.inv_item_id', 'InvMovement.id')
		));
		debug($movements);
		$auxArray = array();
		foreach($items as $item){
			$totalQuantity = 0;
			foreach($movements as $movement){
				if($movement['InvMovementDetail']['inv_item_id'] == $item){
					$auxArray[$item]['movements'][] = array(
						'code'=>$movement['InvMovement']['code'],
						'quantity'=> $movement['InvMovementDetail']['quantity'],
						'date'=>date("d/m/Y", strtotime($movement['InvMovement']['date']))
						
						);
					$totalQuantity = $totalQuantity + $movement['InvMovementDetail']['quantity'];
				}
			}
			//stock solo irian en IN AND OUT all
			//$auxArray[$item]['stockFechaInicio']='algo va aqui';
			//$auxArray[$item]['stockFechaFin']='algo va aqui';
			//$auxArray[$item]['stockActual']='algo va aqui';
			$auxArray[$item]['totalQuantity']=$totalQuantity;
			$auxArray[$item]['brands']='algo va aqui';
			$auxArray[$item]['categories']='algo va aqui';
			$auxArray[$item]['codeName']='[ ECO-102 ] laptop';
		}
		
		debug($auxArray);
		/*
		//debug($array);
		///////////////////////
		$this->InvMovement->InvMovementDetail->unbindModel(array(
			'belongsTo' => array('InvItem')
		));
		
		$this->InvMovement->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		
		$ins = $this->InvMovement->InvMovementDetail->find('all', array(
			'fields'=>array('InvMovementDetail.inv_item_id', 'SUM(InvMovementDetail.quantity)'),
			'conditions'=>array('InvMovementType.status'=>'entrada'),
			'group'=>'InvMovementDetail.inv_item_id',
			'order'=>array('InvMovementDetail.inv_item_id')
		));
		debug($ins);	
		///////////////////////////////////////////////////////
		$this->InvMovement->InvMovementDetail->unbindModel(array(
			'belongsTo' => array('InvItem')
		));
		
		$this->InvMovement->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		
		$outs = $this->InvMovement->InvMovementDetail->find('all', array(
			'fields'=>array('InvMovementDetail.inv_item_id', 'SUM(InvMovementDetail.quantity)'),
			'conditions'=>array('InvMovementType.status'=>'salida'),
			'group'=>'InvMovementDetail.inv_item_id',
			'order'=>array('InvMovementDetail.inv_item_id')
		));
		debug($outs);	
		 * 
		 */
	}
		//////////////////////////////////////////// END - REPORT /////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////// START - INDEX ///////////////////////////////////////////////
	
	public function index_in() {
		
		//debug($this->request->params);
		//debug($this->passedArgs);
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$code = '';
		$document_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_in');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['code']) && $this->request->data['InvMovement']['code']){
				$parameters['code'] = trim(strip_tags($this->request->data['InvMovement']['code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 2){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['code'])){
			$filters['InvMovement.code LIKE'] = '%'.strtoupper($this->passedArgs['code']).'%';
			$code = $this->passedArgs['code'];
		}
		if(isset($this->passedArgs['document_code'])){
			$filters['InvMovement.document_code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			"conditions"=>array(
				"InvMovement.lc_state !="=>"LOGIC_DELETED",
				"to_char(InvMovement.date,'YYYY')"=> $period,
				"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("InvMovement.id", "InvMovement.code", "InvMovement.document_code", "InvMovement.date","InvMovement.inv_movement_type_id","InvMovementType.name", "InvMovement.inv_warehouse_id", "InvWarehouse.name", "InvMovement.lc_state"),
			"order"=> array("InvMovement.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invMovements', $this->paginate('InvMovement'));
		$this->set('code', $code);
		$this->set('document_code', $document_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	public function index_out() {
		
		//debug($this->request->params);
		//debug($this->passedArgs);
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$code = '';
		$document_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_out');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['code']) && $this->request->data['InvMovement']['code']){
				$parameters['code'] = trim(strip_tags($this->request->data['InvMovement']['code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 2){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['code'])){
			$filters['InvMovement.code LIKE'] = '%'.strtoupper($this->passedArgs['code']).'%';
			$code = $this->passedArgs['code'];
		}
		if(isset($this->passedArgs['document_code'])){
			$filters['InvMovement.document_code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			"conditions"=>array(
				"InvMovement.lc_state !="=>"LOGIC_DELETED",
				"to_char(InvMovement.date,'YYYY')"=> $period,
				"InvMovementType.status"=> "salida",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("InvMovement.id", "InvMovement.code", "InvMovement.document_code", "InvMovement.date","InvMovement.inv_movement_type_id","InvMovementType.name", "InvMovement.inv_warehouse_id", "InvWarehouse.name", "InvMovement.lc_state"),
			"order"=> array("InvMovement.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invMovements', $this->paginate('InvMovement'));
		$this->set('code', $code);
		$this->set('document_code', $document_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	public function index_purchase_in(){
	
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$document_code = '';  //seria code de pur_purchases
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_purchase_in');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 1){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		$this->loadModel('PurPurchase');
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['document_code'])){
			$filters['PurPurchase.code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			'conditions'=>array(
				"PurPurchase.lc_state !="=>"LOGIC_DELETED",
				"to_char(PurPurchase.date,'YYYY')"=> $period,
				"PurPurchase.lc_state"=>"ORDER_APPROVED",
				$filters
			 ),
			'fields'=>array('PurPurchase.id', 'PurPurchase.code', 'PurPurchase.date', 'PurPurchase.inv_supplier_id', 'InvSupplier.name'),
			'recursive'=>0,	
			'order'=> array('PurPurchase.id'=>'desc'),
			'limit' => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$pagination = $this->paginate('PurPurchase');
		$paginatedCodes = array();
		for($i = 0; $i<count($pagination); $i++){ 
			$paginatedCodes[$i] = $pagination[$i]['PurPurchase']['code'];
		}
		//debug($paginatedCodes);
		$movements = $this->InvMovement->find('all',array(
			'conditions'=>array('InvMovement.inv_movement_type_id'=>1, 'InvMovement.document_code'=>$paginatedCodes,'NOT'=>array('InvMovement.lc_state'=>array('LOGIC_DELETED', 'CANCELLED'))),
			'fields'=>array('InvMovement.lc_state', 'InvMovement.document_code'),
			'recursive'=>-1
		));
		//debug($this->paginate('PurPurchase'));
		//debug($movements);
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('purPurchases', $pagination);
		$this->set('document_code', $document_code);
		$this->set('movements', $movements);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	public function index_sale_out(){
	
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$document_code = '';  //seria code de pur_purchases
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_sale_out');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 1){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		$this->loadModel('SalSale');
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['document_code'])){
			$filters['SalSale.code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		//Add association for SalCustomers
		$this->SalSale->bindModel(array(
			'hasOne'=>array(
				'SalCustomer'=>array(
					'foreignKey'=>false,
					'conditions'=> array('SalEmployee.sal_customer_id = SalCustomer.id')
				)
				
			)
		));
		//debug($this->SalSale->find('all'));
		
		$this->paginate = array(
			'conditions'=>array(
				"SalSale.lc_state !="=>"LOGIC_DELETED",
				"to_char(SalSale.date,'YYYY')"=> $period,
				"SalSale.lc_state"=>"NOTE_APPROVED",
				$filters
			 ),
			'fields'=>array('SalSale.id','SalSale.code', 'SalSale.date', 'SalCustomer.name'),
			'recursive'=>0,	
			'order'=> array('SalSale.id'=>'desc'),
			'limit' => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$pagination = $this->paginate('SalSale');
		$paginatedCodes = array();
		for($i = 0; $i<count($pagination); $i++){ 
			$paginatedCodes[$i] = $pagination[$i]['SalSale']['code'];
		}
		//debug($paginatedCodes);
		$movements = $this->InvMovement->find('all',array(
			'conditions'=>array('InvMovement.inv_movement_type_id'=>2, 'InvMovement.document_code'=>$paginatedCodes,'NOT'=>array('InvMovement.lc_state'=>array('LOGIC_DELETED', 'CANCELLED'))),
			'fields'=>array('InvMovement.lc_state', 'InvMovement.document_code'),
			'recursive'=>-1
		));
		//debug($this->paginate('PurPurchase'));
		//debug($movements);
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('salSales', $pagination);
		$this->set('document_code', $document_code);
		$this->set('movements', $movements);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		 
	}
		
	///////////////////////////////////////////// END - INDEX ////////////////////////////////////////////////
	
	
	//////////////////////////////////////////// START - SAVE ///////////////////////////////////////////////
	
	public function save_in(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'entrada', 'InvMovementType.document'=>0, 'InvMovementType.id !='=>4)//0 'cause don't have system document
		));
		
		$this->InvMovement->recursive = -1;
		$this->request->data = $this->InvMovement->read(null, $id);
		$date=date('d/m/Y');
		//debug($this->request->data);
		$invMovementDetails = array();
		$documentState = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$invMovementDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
		}
		$this->set(compact('invMovementTypes','invWarehouses', 'id', 'date', 'invMovementDetails', 'documentState'));
	}
	
	public function save_out(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$invMovementTypes = $this->InvMovement->InvMovementType->find('list', array(
			'conditions'=>array('InvMovementType.status'=>'salida', 'InvMovementType.document'=>0, 'InvMovementType.id !='=>3)//0 'cause don't have system document
		));
		
		$this->InvMovement->recursive = -1;
		$this->request->data = $this->InvMovement->read(null, $id);
		$date=date('d/m/Y');

		$invMovementDetails = array();
		$documentState = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$invMovementDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
		}
		$this->set(compact('invMovementTypes','invWarehouses', 'id', 'date', 'invMovementDetails', 'documentState'));
	}
	
	public function save_purchase_in(){
		//debug($purchase);
		////////////////////////////////INICIO - VALIDAR SI ID COMPRA NO ESTA VACIO///////////////////////////////////
		$idMovement = '';
		$documentCode = '';
		if(isset($this->passedArgs['id'])){
			$idMovement = $this->passedArgs['id'];
		}
		if(isset($this->passedArgs['document_code'])){
			$documentCode = $this->passedArgs['document_code'];
		}
		
		if($documentCode == ''){
			$this->redirect(array('action' => 'index_purchase_in'));
			//echo 'codigo vacio';
		}
		////////////////////////////////FIN - VALIDAR SI ID COMPRA NO ESTA VACIO/////////////////////////////////////


		////////////////////////////////INICIO - VALIDAR SI CODIGO COMPRA EXISTE///////////////////////////////////
		$this->loadModel('PurPurchase');	
		$idPurchase = $this->PurPurchase->field('PurPurchase.id', array('PurPurchase.code'=>$documentCode));
		if(!$idPurchase){
			$this->redirect(array('action' => 'index_purchase_in'));
			//echo 'no existe codigo compra';
		}
		////////////////////////////////FIN - VALIDAR SI ID COMPRA EXISTE/////////////////////////////////////
		
		////////////////////////////////INICIO - DECLARAR VARIABLES///////////////////////////////////
		$arrayAux = array();
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$firstWarehouse = key($invWarehouses);
		$invMovementDetails = array();
		$documentState = '';
		$id='';
		$date=date('d/m/Y');
		////////////////////////////////FIN - DECLARAR VARIABLES///////////////////////////////////
		
		
		
		if($idMovement <> ''){//Si idMovimiento esta lleno, mostrar todo, hasta cancelados en index_in
			$this->InvMovement->recursive = -1;
			$arrayAux = $this->InvMovement->find('all', array('conditions'=>array(
			 'InvMovement.document_code'=>$documentCode
			,'InvMovement.id'=>$idMovement
			)));
			if(count($arrayAux) == 0){//si no existe el movimiento
				$this->redirect(array('action' => 'index_in'));
			}
		}else{//Si idMovimiento esta vacio, mostrar solo (nuevo, pendiente o aprobado) en index_save_in
			$this->InvMovement->recursive = -1;
			$arrayAux = $this->InvMovement->find('all', array('conditions'=>array(
			'InvMovement.document_code'=>trim($documentCode), 'InvMovement.lc_state'=>array('APPROVED','PENDANT')
			)));
		}
		
		//mostrar cancelados
		
		//mostrar activos
		

		////////////////////////////////INICIO - LLENAR VISTA ///////////////////////////////////////////////
		if(count($arrayAux) > 0){ //UPDATE
			$this->request->data = $arrayAux[0];
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$id = $this->request->data['InvMovement']['id'];
			$invMovementDetails = array();//$this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
			
			$arrPurchases = $this->_get_purchases_details($idPurchase, $firstWarehouse, 'editar');//$firstWarehouse no se usara porque es "editar", sino doble query para stock
			$arrMovementsSaved = $this->_get_movements_details($id);
			foreach ($arrMovementsSaved as $key => $value) {
				$invMovementDetails[$key]['itemId']=$value['itemId'];
				$invMovementDetails[$key]['item']=$value['item'];
				$invMovementDetails[$key]['cantidadCompra']=$arrPurchases[$key]['cantidadCompra'];
				$invMovementDetails[$key]['stock']=$value['stock'];
				$invMovementDetails[$key]['cantidad']=$value['cantidad'];
			}
		}else{//INSERT
			$invMovementDetails = $this->_get_purchases_details($idPurchase, $firstWarehouse,'nuevo');
		}
		
		$this->set(compact('invWarehouses', 'id', 'documentCode', 'date', 'invMovementDetails', 'documentState', 'idMovement'));
		////////////////////////////////FIN - LLENAR VISTA //////////////////////////////////////////////////
		
	}
	
	
	public function save_sale_out(){
		//debug($purchase);
		////////////////////////////////INICIO - VALIDAR SI ID COMPRA NO ESTA VACIO///////////////////////////////////
		$idMovement = '';
		$documentCode = '';
		if(isset($this->passedArgs['id'])){
			$idMovement = $this->passedArgs['id'];
		}
		if(isset($this->passedArgs['document_code'])){
			$documentCode = $this->passedArgs['document_code'];
		}
		
		if($documentCode == ''){
			$this->redirect(array('action' => 'index_sale_out'));
			//echo 'codigo vacio';
		}
		////////////////////////////////FIN - VALIDAR SI ID COMPRA NO ESTA VACIO/////////////////////////////////////


		////////////////////////////////INICIO - VALIDAR SI CODIGO COMPRA EXISTE///////////////////////////////////
		$this->loadModel('SalSale');	
		$idSale = $this->SalSale->field('SalSale.id', array('SalSale.code'=>$documentCode));
		if(!$idSale){
			$this->redirect(array('action' => 'index_sale_out'));
			//echo 'no existe codigo compra';
		}
		////////////////////////////////FIN - VALIDAR SI ID COMPRA EXISTE/////////////////////////////////////
		
		////////////////////////////////INICIO - DECLARAR VARIABLES///////////////////////////////////
		$arrayAux = array();
		$invWarehouses = $this->InvMovement->InvWarehouse->find('list');
		$firstWarehouse = key($invWarehouses);
		$invMovementDetails = array();
		$documentState = '';
		$id='';
		$date=date('d/m/Y');
		////////////////////////////////FIN - DECLARAR VARIABLES///////////////////////////////////
		
		
		
		if($idMovement <> ''){//Si idMovimiento esta lleno, mostrar todo, hasta cancelados en index_in
			$this->InvMovement->recursive = -1;
			$arrayAux = $this->InvMovement->find('all', array('conditions'=>array(
			 'InvMovement.document_code'=>$documentCode
			,'InvMovement.id'=>$idMovement
			)));
			if(count($arrayAux) == 0){//si no existe el movimiento
				$this->redirect(array('action' => 'index_in'));
			}
		}else{//Si idMovimiento esta vacio, mostrar solo (nuevo, pendiente o aprobado) en index_save_in
			$this->InvMovement->recursive = -1;
			$arrayAux = $this->InvMovement->find('all', array('conditions'=>array(
			'InvMovement.document_code'=>trim($documentCode), 'InvMovement.lc_state'=>array('APPROVED','PENDANT')
			)));
		}
		
		////////////////////////////////INICIO - LLENAR VISTA ///////////////////////////////////////////////
		if(count($arrayAux) > 0){ //UPDATE
			$this->request->data = $arrayAux[0];
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));//$this->request->data['InvMovement']['date'];
			$id = $this->request->data['InvMovement']['id'];
			$invMovementDetails = array();//$this->_get_movements_details($id);
			$documentState =$this->request->data['InvMovement']['lc_state'];
			
			$arrSales = $this->_get_sales_details($idSale, $firstWarehouse, 'editar');//$firstWarehouse no se usara porque es "editar", sino doble query para stock
			$arrMovementsSaved = $this->_get_movements_details($id);
			foreach ($arrMovementsSaved as $key => $value) {
				$invMovementDetails[$key]['itemId']=$value['itemId'];
				$invMovementDetails[$key]['item']=$value['item'];
				$invMovementDetails[$key]['cantidadVenta']=$arrSales[$key]['cantidadVenta'];
				$invMovementDetails[$key]['stock']=$value['stock'];
				$invMovementDetails[$key]['cantidad']=$value['cantidad'];
			}
		}else{//INSERT
			$invMovementDetails = $this->_get_sales_details($idSale, $firstWarehouse,'nuevo');
		}
		
		$this->set(compact('invWarehouses', 'id', 'documentCode', 'date', 'invMovementDetails', 'documentState', 'idMovement'));
		////////////////////////////////FIN - LLENAR VISTA //////////////////////////////////////////////////
		
	}
	
	
	public function save_warehouses_transfer(){
		/////////////////////////////////////////START - VARIABLES DECLARATION///////////////////
		$documentCode = '';
		
		$warehouseIn ='';
		$warehouseOut ='';
		$movementIdIn = '';
		$movementIdOut = '';
		$date=date('d/m/Y');
		$invMovementDetailsOut = array();
		$invMovementDetailsIn = array();
		$documentCode = '';
		$documentState = '';
		///////////////////////////////////////////END - VARIABLES DECLARATION///////////////////
		
		/////////////////////////////////////////START - VIEW VALIDATION FOR MODIFY///////////////////
		if(isset($this->passedArgs['document_code'])){
			$documentCode = $this->passedArgs['document_code'];
			$movementIdIn = $this->InvMovement->field('InvMovement.id', array(
				'InvMovement.document_code'=>$documentCode,
				'InvMovement.inv_movement_type_id ='=>4//In Destination
			));
			$movementIdOut = $this->InvMovement->field('InvMovement.id', array(
					'InvMovement.document_code'=>$documentCode,
					'InvMovement.inv_movement_type_id ='=>3//Out Origin
			));
			$url = '';
			if($movementIdIn == '' OR $movementIdOut == ''){
				if(isset($this->passedArgs['origin'])){
					if($this->passedArgs['origin'] == 'in'){
						$url = array('action'=>'index_in');
					}elseif($this->passedArgs['origin'] == 'out'){
						$url = array('action'=>'index_out');
					}
					$this->redirect($url);
				}else{
					$this->redirect(array('action'=>'index_in'));
				}
			}
			
			$warehouseIn = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementIdIn));
			$this->InvMovement->recursive = -1;
			$this->request->data = $this->InvMovement->read(null, $movementIdOut);
			$date = date("d/m/Y", strtotime($this->request->data['InvMovement']['date']));
			//$warehouseOut = $this->InvMovement->field('InvMovement.inv_warehouse_id', array('InvMovement.id'=>$movementIdOut));
			$warehouseOut = $this->request->data['InvMovement']['inv_warehouse_id'];
			$documentState =$this->request->data['InvMovement']['lc_state'];
			$invMovementDetailsOut = $this->_get_movements_details($movementIdOut);
			$invMovementDetailsIn = $this->_get_movements_details($movementIdIn);
			
		}
		///////////////////////////////////////////END - VIEW VALIDATION FOR MODIFY///////////////////
		$warehouses = $this->InvMovement->InvWarehouse->find('list');
		
		
		
		$this->set(compact('warehouses','warehouseIn','warehouseOut', 'movementIdOut', 'date', 'invMovementDetailsOut', 'invMovementDetailsIn', 'documentState', 'documentCode'));
	}
	
	public function index_warehouses_transfer(){

		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$document_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_warehouses_transfer');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['InvMovement']['document_code']) && $this->request->data['InvMovement']['document_code']){
				$parameters['document_code'] = trim(strip_tags($this->request->data['InvMovement']['document_code']));
			}else{
				$empty++;
			}
			if($empty == 1){
				$parameters['search']='empty';
			}else{
				$parameters['search']='yes';
			}
			$this->redirect(array_merge($url,$parameters));
		}
		////////////////////////////END - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING URL FILTERS//////////////////////////////////////
		if(isset($this->passedArgs['document_code'])){
			$filters['InvMovement.document_code LIKE'] = '%'.strtoupper($this->passedArgs['document_code']).'%';
			$document_code = $this->passedArgs['document_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			'conditions'=>array(
				"InvMovement.lc_state !="=>"LOGIC_DELETED",
				"to_char(InvMovement.date,'YYYY')"=> $period,
				"InvMovement.inv_movement_type_id"=> 3,//out
				$filters
			 ),
			'recursive'=>0,
			'fields'=>array('InvMovement.id', 'InvMovement.document_code', 'InvMovement.date','InvMovement.inv_warehouse_id', 'InvWarehouse.name', 'InvMovement.lc_state'),
			'order'=> array('InvMovement.id'=>'desc'),
			'limit' => 15,
		);
		
		$pagination = $this->paginate('InvMovement');
		//debug($pagination);
		$paginatedDocumentCodes = array();
		for($i = 0; $i<count($pagination); $i++){ 
			$paginatedDocumentCodes[$i] = $pagination[$i]['InvMovement']['document_code'];
		}
		$warehouseDestination = $this->InvMovement->find('all', array(
				'conditions'=>array(
					'InvMovement.lc_state !='=>'LOGIC_DELETED',
					'InvMovement.document_code'=>$paginatedDocumentCodes,
					'InvMovement.inv_movement_type_id'=> 4,//in
					$filters
				 ),
				 'recursive'=>0,
				 'fields'=>array('InvMovement.id','InvMovement.inv_warehouse_id','InvWarehouse.name', 'InvMovement.document_code')
			));
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		//debug($warehouseDestination);
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('invMovements', $pagination);
		$this->set('document_code', $document_code);
		$this->set('warehouseDestination',$warehouseDestination);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
	}
	
	
	//////////////////////////////////////////// END - SAVE /////////////////////////////////////////////////
	
	
	//////////////////////////////////////////// START - AJAX ///////////////////////////////////////////////
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
			$transfer = $this->request->data['transfer'];
			
			$items = $this->InvMovement->InvMovementDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySaved)
				),
				'recursive'=>-1,
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
			$firstItemListed = key($items);
			$stock = $this->_find_stock($firstItemListed, $warehouse); //if it's warehouse_transfer is OUT
			$stock2 = '';
			if($transfer == 'warehouses_transfer'){
				$stock2 = $this->_find_stock($firstItemListed, $warehouse2);//if it's warehouse_transfer is IN	
			}
			//debug($stock2);
			$this->set(compact('items', 'stock', 'stock2', 'transfer'));
		}
	}
	
	public function ajax_update_stock_modal(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
			$transfer = $this->request->data['transfer'];
			
			$stock = $this->_find_stock($item, $warehouse);//if it's warehouse_transfer is OUT
			$stock2 ='';
			if($transfer == 'warehouses_transfer'){
				$stock2 = $this->_find_stock($item, $warehouse2);//if it's warehouse_transfer is IN	
			}
			
			$this->set(compact('stock', 'stock2', 'transfer'));
		}
	}
	
	
	public function ajax_save_movement(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START - RECIEVE AJAX////////////////////////////////////////////////////////
			//Movement
			$movementId = $this->request->data['movementId'];
			$date = $this->request->data['date'];
			$warehouseId = $this->request->data['warehouseId'];
			$description = $this->request->data['description'];
			$code = $this->request->data['code'];
			$documentCode ='';
			If(isset($this->request->data['documentCode'])){$documentCode = $this->request->data['documentCode'];}
			$warehouseId2 = $this->request->data['warehouseId2'];
			$movementTypeId = 0;
			if(isset($this->request->data['movementTypeId'])){$movementTypeId = $this->request->data['movementTypeId'];}
			//Movement Details
			$itemId = $this->request->data['itemId'];
			$quantity = $this->request->data['quantity'];
			//For making algorithm
			$ACTION = $this->request->data['ACTION'];
			$OPERATION= $this->request->data['OPERATION'];
			$STATE = $this->request->data['STATE'];//also for Movement
			//For validate before approve OUT or cancelled IN
			$arrayForValidate = array();
			if(isset($this->request->data['arrayForValidate'])){$arrayForValidate = $this->request->data['arrayForValidate'];}
			//Internal variables
			$error=0;
			$movementDestinationId=0;
			$code2 = '';
			////////////////////////////////////////////END - RECIEVE AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////////START - SET DATA/////////////////////////////////////////////////////
			$arrayMovement = array('date'=>$date, 'inv_warehouse_id'=>$warehouseId, 'description'=>$description, 'lc_state'=>$STATE);
			
			if($ACTION == 'save_warehouses_transfer'){
				$arrayMovementDestination = $arrayMovement; //IN(destination),OUT(origin)
				$arrayMovementDestination['inv_warehouse_id'] = $warehouseId2;
			}
			
			$arrayMovementDetails = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
			
			//INSERT OR UPDATE
			if($movementId == ''){//INSERT
				//$code = 'BORRADOR'.date('Y').'-'.date('mdHis');
				switch ($ACTION) {
					case 'save_in':
						$arrayMovement['document_code'] = 'NO';
						$arrayMovement['inv_movement_type_id']=$movementTypeId;
						$code = $this->_generate_code('ENT');
						$arrayMovement['code'] = $code;
						break;
					case 'save_purchase_in':
						$arrayMovement['document_code'] = $documentCode;
						$arrayMovement['inv_movement_type_id']=1;
						$code = $this->_generate_code('ENT');
						$arrayMovement['code'] = $code;
						$arrayMovementDetails = $arrayForValidate;
						break;
					case 'save_out':
						$arrayMovement['document_code'] = 'NO';
						$arrayMovement['inv_movement_type_id']=$movementTypeId;
						$code = $this->_generate_code('SAL');
						$arrayMovement['code'] = $code;
						break;
					case 'save_sale_out':
						$arrayMovement['document_code'] = $documentCode;
						$arrayMovement['inv_movement_type_id']=2;
						$code = $this->_generate_code('SAL');
						$arrayMovement['code'] = $code;
						$arrayMovementDetails = $arrayForValidate;
						break;
					case 'save_warehouses_transfer':
						$code = $this->_generate_code('SAL');
						$arrayMovement['code'] = $code;
						
						$code2 = $this->_generate_code('ENT');
						$arrayMovementDestination['code'] = $code2;
						
						$documentCode = $this->_generate_document_code_transfer('TRA-ALM');
						$arrayMovement['document_code'] = $documentCode;
						$arrayMovementDestination['document_code'] = $documentCode;
						
						$arrayMovement['inv_movement_type_id']=3; //Origin/Out
						$arrayMovementDestination['inv_movement_type_id']=4;//Destination/In
						
						$dataOut = array('InvMovement'=>$arrayMovement, 'InvMovementDetail'=>array($arrayMovementDetails));
						$dataIn = array('InvMovement'=>$arrayMovementDestination, 'InvMovementDetail'=>array($arrayMovementDetails));
						$dataTransfer = array($dataIn, $dataOut);
						
						$tokenTransfer = 'INSERT';
						break;
				}
				if($code == 'error'){$error++;}
				if($code2 == 'error'){$error++;}
				if($documentCode == 'error'){$error++;}
			}else{//UPDATE
				$arrayMovement['id'] = $movementId;
				if($ACTION == 'save_warehouses_transfer'){
					try{
						$movementDestinationId = $this->InvMovement->field('InvMovement.id', array(
							'InvMovement.document_code'=>$documentCode,
							'InvMovement.id !='=>$movementId
						));
					}catch(Exception $e){ //IF ERROR
						$error++;
					}
					$tokenTransfer = 'UPDATE';
				}
				$arrayMovementDestination['id'] = $movementDestinationId;
				$dataOut = array('InvMovement'=>$arrayMovement);
				$dataIn = array('InvMovement'=>$arrayMovementDestination);
				$movementDetails = array('InvMovementDetail'=>$arrayMovementDetails);
				$dataTransfer = array($dataIn, $dataOut, $movementDetails);
			}

			
			if($ACTION <> 'save_warehouses_transfer'){
				$dataMovement = array('InvMovement'=>$arrayMovement);
				$dataMovementDetail = array('InvMovementDetail'=> $arrayMovementDetails);
			}
			////////////////////////////////////////////////END - SET DATA//////////////////////////////////////////////////////
			
			$validation['error'] = 0;
			$strItemsStock = '';
			////////////////////////////////////////////START- CORE SAVE////////////////////////////////////////////////////////
			if($error == 0){
				/////////////////////START - SAVE/////////////////////////////	
					if(count($arrayForValidate) > 0){
						if(($STATE == 'APPROVED') AND ($ACTION == 'save_out' OR $ACTION == 'save_sale_out')){
							$validation=$this->_validateItemsStocksOut($arrayForValidate, $warehouseId);
						}
						if(($STATE == 'CANCELLED') AND ($ACTION == 'save_in' OR $ACTION == 'save_purchase_in')){
							$validation=$this->_validateItemsStocksOut($arrayForValidate, $warehouseId);
						}
						if($ACTION == 'save_warehouses_transfer'){
							if(($STATE == 'APPROVED')){
								$validation=$this->_validateItemsStocksOut($arrayForValidate, $warehouseId);
								$strItemsStock = '|'.$this->_createStringItemsStocksUpdated($arrayForValidate, $warehouseId2).'|APPROVED';
							}
							if($STATE == 'CANCELLED'){
								$validation=$this->_validateItemsStocksOut($arrayForValidate, $warehouseId2);
								$strItemsStock = '|'.$this->_createStringItemsStocksUpdated($arrayForValidate, $warehouseId).'|CANCELLED';
							}
						}
						
					}
					if($validation['error'] == 0){
						if($ACTION <> 'save_warehouses_transfer'){
							$res = $this->InvMovement->saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION);
						}else{
							$res = $this->InvMovement->saveMovementTransfer($dataTransfer, $OPERATION, $tokenTransfer);
							$code = $documentCode;
						}
						if($res <> 'error'){
							$movementIdSaved = $res;
							$strItemsStockDestination = '';
							if($STATE == 'APPROVED' OR $STATE == 'CANCELLED'){
								$strItemsStock = $this->_createStringItemsStocksUpdated($arrayForValidate, $warehouseId);
								if($ACTION == 'save_warehouses_transfer'){
									$strItemsStockDestination = '|'.$this->_createStringItemsStocksUpdated($arrayForValidate, $warehouseId2);
								}
							}
							echo $STATE.'|'.$movementIdSaved.'|'.$code.'|'.$strItemsStock.$strItemsStockDestination;
						}else{
							echo 'ERROR|onSaving';
						}
					}else{
							echo 'VALIDATION|'.$validation['itemsStocks'].$strItemsStock;
					}

				/////////////////////END - SAVE////////////////////////////////	
			}else{
				echo 'ERROR|onGeneratingParameters';
			}
			////////////////////////////////////////////END-CORE SAVE////////////////////////////////////////////////////////
		}
	}
	
	
	public function ajax_update_multiple_stocks(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$warehouse = $this->request->data['warehouse'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CADENA ITEMS STOCKS///////////////////////////////////////////////
			$strItemsStock = $this->_createStringItemsStocksUpdated($arrayItemsDetails, $warehouse);
			echo $strItemsStock;
		}
	}
	
	public function ajax_logic_delete(){
		if($this->RequestHandler->isAjax()){
			$code = $this->request->data['code'];		
			$type = $this->request->data['type'];		
			if($type == 'transfer'){
				$conditions = array('InvMovement.document_code'=>$code);
			}else{
				$conditions = array('InvMovement.code'=>$code);
			}
			
			if($this->InvMovement->updateAll(array('InvMovement.lc_state'=>"'LOGIC_DELETED'"), $conditions)){
				echo 'success';
			}
		}
	}
	//////////////////////////////////////////// END - AJAX /////////////////////////////////////////////////
	
	
	
	//////////////////////////////////////////// START - PRIVATE ///////////////////////////////////////////////
	
	private function _get_movements_details($idMovement){
		$movementDetails = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array('InvMovementDetail.inv_movement_id'=>$idMovement),
			'fields'=>array('InvItem.name', 'InvItem.code', 'InvMovementDetail.quantity', 'InvItem.id', 'InvMovement.inv_warehouse_id')
			));
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			$formatedMovementDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'stock'=> $this->_find_stock($value['InvItem']['id'], $value['InvMovement']['inv_warehouse_id']),//llamar funcion
				'cantidad'=>$value['InvMovementDetail']['quantity']//llamar cantidad
				);
		}
		
		return $formatedMovementDetails;
	}
	
	private function _get_movements_details_without_stock($idMovement){
		$movementDetails = $this->InvMovement->InvMovementDetail->find('all', array(
			'conditions'=>array('InvMovementDetail.inv_movement_id'=>$idMovement),
			'fields'=>array('InvItem.name', 'InvItem.code', 'InvMovementDetail.quantity', 'InvItem.id', 'InvMovement.inv_warehouse_id')
			));
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			$formatedMovementDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				//'stock'=> $this->_find_stock($value['InvItem']['id'], $value['InvMovement']['inv_warehouse_id']),//llamar funcion
				'cantidad'=>$value['InvMovementDetail']['quantity']//llamar cantidad
				);
		}
		
		return $formatedMovementDetails;
	}
	
	private function _get_purchases_details($idPurchase, $idWarehouse, $state){
		$stock = 0;
		$this->loadModel('PurDetail');
		$purchaseDetails = $this->PurDetail->find('all', array(
		'conditions'=>array('PurDetail.pur_purchase_id'=>$idPurchase),
		'fields'=>array('InvItem.name', 'InvItem.code', 'PurDetail.quantity', 'InvItem.id')
		));
		$formatedPurchaseDetails = array();
		foreach ($purchaseDetails as $key => $value) {
			
			if($state == 'nuevo'){
				$stock = $this->_find_stock($value['InvItem']['id'], $idWarehouse);
			}
			$formatedPurchaseDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'cantidadCompra'=>$value['PurDetail']['quantity'],
				'stock'=> $stock,//llamar funcion
				'cantidad'=>$value['PurDetail']['quantity']
				);
		}
		//debug($formatedPurchaseDetails);
		return $formatedPurchaseDetails;
	}
	
	private function _get_sales_details($idSale, $idWarehouse, $state){
		$stock = 0;
		$this->loadModel('SalDetail');
		$saleDetails = $this->SalDetail->find('all', array(
		'conditions'=>array('SalDetail.sal_sale_id'=>$idSale),
		'fields'=>array('InvItem.name', 'InvItem.code', 'SalDetail.quantity', 'InvItem.id')
		));
		$formatedSaleDetails = array();
		foreach ($saleDetails as $key => $value) {
			
			if($state == 'nuevo'){
				$stock = $this->_find_stock($value['InvItem']['id'], $idWarehouse);
			}
			$formatedSaleDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'cantidadVenta'=>$value['SalDetail']['quantity'],
				'stock'=> $stock,//llamar funcion
				'cantidad'=>$value['SalDetail']['quantity']
				);
		}
		//debug($formatedPurchaseDetails);
		return $formatedSaleDetails;
	}
	
	
	
	private function _find_stock($idItem, $idWarehouse){		
		$movementsIn = $this->_get_quantity_movements_item($idItem, $idWarehouse, 'entrada');
		$movementsOut = $this->_get_quantity_movements_item($idItem, $idWarehouse, 'salida');
		$add = array_sum($movementsIn);
		$sub = array_sum($movementsOut);
		$stock = $add - $sub;
		return $stock;
	}
	
	private function _get_quantity_movements_item($idItem, $idWarehouse, $status){
		//******************************************************************************//
		//unbind for perfomance InvItem 'cause it isn't needed
		$this->InvMovement->InvMovementDetail->unbindModel(array(
			'belongsTo' => array('InvItem')
		));
		//Add association for InvMovementType
		$this->InvMovement->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		//******************************************************************************//
		//Movements
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
			'fields'=>array('InvMovementDetail.inv_movement_id', 'InvMovementDetail.quantity'),
			'conditions'=>array(
				'InvMovement.inv_warehouse_id'=>$idWarehouse,
				'InvMovementDetail.inv_item_id'=>$idItem,
				'InvMovementType.status'=>$status,
				'InvMovement.lc_state'=>'APPROVED',
				)
		));
		//Give format to nested array movements
		$movementsCleaned = $this->_clean_nested_arrays($movements);
		return $movementsCleaned;
	}
	
	private function _clean_nested_arrays($array){
		$clean = array();
		foreach ($array as $key => $value) {
			$clean[$key] = $value['InvMovementDetail']['quantity'];
		}
		return $clean;
	}
	
	private function _generate_code($keyword){
		$period = $this->Session->read('Period.name');
		$movementType = '';
		if($keyword == 'ENT'){$movementType = 'entrada';}
		if($keyword == 'SAL'){$movementType = 'salida';}
		if($period <> ''){
			try{
				$movements = $this->InvMovement->find('count', array(
					'conditions'=>array('InvMovementType.status'=>$movementType)
				)); 
			}catch(Exception $e){
				return 'error';
			}
		}else{
			return 'error';
		}
		
		$quantity = $movements + 1; 
		$code = 'MOV-'.$period.'-'.$keyword.'-'.$quantity;
		return $code;
	}
	
	private function _generate_document_code_transfer($keyword){
		$period = $this->Session->read('Period.name');
		if($keyword == 'TRA-ALM'){$idMovementType = 3;}
		if($period <> ''){
			try{
				$transfers = $this->InvMovement->find('count', array('conditions'=>array('InvMovement.inv_movement_type_id'=>$idMovementType))); 
			}catch(Exception $e){
				return 'error';
			}
		}else{
			return 'error';
		}
		
		$quantity = $transfers + 1; 
		$code = 'MOV-'.$period.'-'.$keyword.'-'.$quantity;
		return $code;
	}
	
	private function _validateItemsStocksOut($arrayItemsDetails, $warehouse){
		$strItemsStockErrorSuccess = '';
		$cont=0;
		for($i = 0; $i<count($arrayItemsDetails); $i++){
				$updatedStock = $this->_find_stock($arrayItemsDetails[$i]['inv_item_id'], $warehouse);
				if($updatedStock < $arrayItemsDetails[$i]['quantity']){
					$strItemsStockErrorSuccess .= $arrayItemsDetails[$i]['inv_item_id'].'=>error:'.$updatedStock.','; //error
					$cont++;
				}else{
					$strItemsStockErrorSuccess .= $arrayItemsDetails[$i]['inv_item_id'].'=>success:'.$updatedStock.',';//success
				}
		}
		return array('error'=>$cont, 'itemsStocks'=>$strItemsStockErrorSuccess);
	}
	
	private function _createStringItemsStocksUpdated($arrayItemsDetails, $idWarehouse){
		////////////////////////////////////////////INICIO-CREAR CADENA ITEMS STOCK ACUTALIZADOS//////////////////////////////
			$strItemsStock = '';
			for($i = 0; $i<count($arrayItemsDetails); $i++){
				$updatedStock = $this->_find_stock($arrayItemsDetails[$i]['inv_item_id'], $idWarehouse);
				$strItemsStock .= $arrayItemsDetails[$i]['inv_item_id'].'=>'.$updatedStock.',';
			}
			////////////////////////////////////////////FIN-CREAR CADENA ITEMS STOCK ACUTALIZADOS/////////////////////////////////
			return $strItemsStock;
	}
	//////////////////////////////////////////// END - PRIVATE /////////////////////////////////////////////////
	
	

	

	//*******************************************************************************************************//
	/////////////////////////////////////////// END - FUNCTIONS ///////////////////////////////////////////////
	//*******************************************************************************************************//
}


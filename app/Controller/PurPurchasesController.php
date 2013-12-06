<?php
App::uses('AppController', 'Controller');
/**
 * PurPurchases Controller
 *
 * @property PurPurchase $PurPurchase
 */
class PurPurchasesController extends AppController {

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
	//public $helpers = array('TwitterBootstrap.BootstrapHtml', 'TwitterBootstrap.BootstrapForm', 'TwitterBootstrap.BootstrapPaginator');
/**
 * Components
 *
 * @var array
 */
	//public $components = array('Session');
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
	public function vreport_generator(){
		$this->loadModel("InvWarehouse");
		$warehouse = $this->InvWarehouse->find('list');
		$item = $this->_find_items();
		$this->set(compact("warehouse", "item"));
	}
	
	private function _find_items($type = 'none', $selected = array()){
		$conditions = array();
		$order = array('InvItem.code');
		
		switch ($type){
			case 'category':
				$conditions = array('InvItem.inv_category_id'=>$selected);
				//$order = array('InvCategory.name');
				break;
			case 'brand':
				$conditions = array('InvItem.inv_brand_id'=>$selected);
				//$order = array('InvBrand.name');
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
//			$item = $this->_find_items($type, array_keys($group));
			$item = $this->_find_items($type, array_keys(array()));
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
			//SETTING DATA
			$this->Session->write('ReportMovement.startDate', $this->request->data['startDate']);
			$this->Session->write('ReportMovement.finishDate', $this->request->data['finishDate']);
			$this->Session->write('ReportMovement.movementType', $this->request->data['movementType']);
			$this->Session->write('ReportMovement.movementTypeName', $this->request->data['movementTypeName']);
			$this->Session->write('ReportMovement.warehouse', $this->request->data['warehouse']);
			$this->Session->write('ReportMovement.warehouseName', $this->request->data['warehouseName']);
			$this->Session->write('ReportMovement.currency', $this->request->data['currency']);
			
			//for transfer
			$this->Session->write('ReportMovement.warehouse2', $this->request->data['warehouse2']);
			$this->Session->write('ReportMovement.warehouseName2', $this->request->data['warehouseName2']);
			//array items
			$this->Session->write('ReportMovement.items', $this->request->data['items']);
			
			//to send data response to ajax success so it can choose the report view
			echo $this->request->data['movementType']; 
		///END AJAX
		}
	}
	
	public function vreport_ins_or_outs(){
		$this->_generate_report();
	}
	
	public function vreport_ins_and_outs(){
		$this->_generate_report();
	}
	
	public function vreport_transfers(){
		$this->_generate_report(); 
	}
	
	private function _generate_report(){
		//special ctp template for printing due DOMPdf colapses generating too many pages
		$this->layout = 'print';
		
		//Check if session variables are set otherwise redirect
		if(!$this->Session->check('ReportMovement')){
			$this->redirect(array('action' => 'vreport_generator'));
		}
		
		//put session data sent data into variables
		$initialData = $this->Session->read('ReportMovement');
		
		//debug($initialData);

		$settings = $this->_generate_report_settings($initialData);
		
		//debug($settings);
		
		$movements=$this->_generate_report_movements($settings['values'], $settings['conditions'], $settings['fields']);
		//debug($movements);
		
		$currencyFieldPrefix = '';
		$currencyAbbreviation = '(BS)';
		if(trim($initialData['currency']) == 'DOLARES AMERICANOS'){
			$currencyFieldPrefix = 'ex_';
			$currencyAbbreviation = '($US)';
		}
		
		
		$itemsComplete = $this->_generate_report_items_complete($initialData['items']);
		//debug($itemsComplete);
		$itemsMovements = $this->_generate_report_items_movements($itemsComplete, $movements, $currencyFieldPrefix);
		//debug($itemsMovements);
		
		$initialData['currencyAbbreviation']=$currencyAbbreviation;//setting currency abbreviation before send
		$initialData['items']='';//cleaning items ids 'cause won't be needed begore send
		//debug($initialData);
		$this->set('initialData', $initialData);
		$this->set('itemsMovements', $itemsMovements);
		//debug($settings['initialStocks']);
		$this->set('initialStocks', $settings['initialStocks']);
		$this->Session->delete('ReportMovement');
	//END FUNCTION	
	}
	
	
	
	private function _generate_report_items_movements($itemsComplete, $movements, $currencyFieldPrefix){
		//I'll not calculate totals 'cause will be easier in the view and specially cleaner due the variation of calculation in every report
		$auxArray=array();
		foreach($itemsComplete as $item){
			$fobQuantityTotal = 0;
			$cifQuantityTotal = 0;
			$saleQuantityTotal = 0;
			$counter = 0;
			
			$forPricesSubQuery = 0; //before 'InvMovementDetail'
			
			//movements
			foreach($movements as $movement){
				if($item['InvItem']['id'] == $movement['InvMovementDetail']['inv_item_id']){
					$fobQuantity = $movement['InvMovementDetail']['quantity'] * $movement[$forPricesSubQuery][$currencyFieldPrefix.'fob_price'];
					$cifQuantity = $movement['InvMovementDetail']['quantity'] * $movement[$forPricesSubQuery][$currencyFieldPrefix.'cif_price'];
					$saleQuantity = $movement['InvMovementDetail']['quantity'] * $movement[$forPricesSubQuery][$currencyFieldPrefix.'sale_price'];
					$fobQuantityTotal = $fobQuantityTotal + $fobQuantity;
					$cifQuantityTotal = $cifQuantityTotal + $cifQuantity;
					$saleQuantityTotal = $saleQuantityTotal + $saleQuantity;
					$auxArray[$item['InvItem']['id']]['Movements'][$counter] = array(
						'code'=>$movement['InvMovement']['code'],
						'document_code'=>$movement['InvMovement']['document_code'],
						'quantity'=> $movement['InvMovementDetail']['quantity'],
						'date'=>date("d/m/Y", strtotime($movement['InvMovement']['date'])),
						'fob'=> $movement[$forPricesSubQuery][$currencyFieldPrefix.'fob_price'],
						'cif'=> $movement[$forPricesSubQuery][$currencyFieldPrefix.'cif_price'],
						'sale'=> $movement[$forPricesSubQuery][$currencyFieldPrefix.'sale_price'],
						'fobQuantity'=>$fobQuantity,
						'cifQuantity'=>$cifQuantity,
						'saleQuantity'=>$saleQuantity,
						'warehouse'=>$movement['InvMovement']['inv_warehouse_id']
					);
					if(isset($movement['InvMovementType']['status'])){
						$auxArray[$item['InvItem']['id']]['Movements'][$counter]['status']=$movement['InvMovementType']['status'];
					}
					$counter++;
				}
			}
			//Items
			$auxArray[ $item['InvItem']['id'] ]['Item']['codeName']='[ '.$item['InvItem']['code'].' ] '.$item['InvItem']['name'];
			$auxArray[ $item['InvItem']['id'] ]['Item']['brand']=$item['InvBrand']['name'];
			$auxArray[ $item['InvItem']['id'] ]['Item']['category']=$item['InvCategory']['name'];
			$auxArray[ $item['InvItem']['id'] ]['Item']['id']=$item['InvItem']['id'];
			//Totals
			$auxArray[ $item['InvItem']['id'] ]['TotalMovements']['fobQuantityTotal'] = $fobQuantityTotal;
			$auxArray[ $item['InvItem']['id'] ]['TotalMovements']['cifQuantityTotal'] = $cifQuantityTotal;
			$auxArray[ $item['InvItem']['id'] ]['TotalMovements']['saleQuantityTotal'] = $saleQuantityTotal;
			////I don't calculate total quantity here 'cause could vary in every report, it will be done in the report views
		}
		return $auxArray;
	}
	
	private function _generate_report_settings($initialData){
		///////////////////VALUES, FIELDS, CONDITIONS////////////////////////
		$values = array();
		$conditions = array();
		$fields = array();
		$initialStocks=array();
				
		
		$values['startDate']=$initialData['startDate'];
		$values['finishDate']=$initialData['finishDate'];
		$warehouses = array(0=>$initialData['warehouse']);
		
		switch ($initialData['movementType']) {
			case 998://TODAS LAS ENTRADAS
				$conditions['InvMovement.inv_movement_type_id']=array(1,4,5,6);
				break;
			case 999://TODAS LAS SALIDAS
				$conditions['InvMovement.inv_movement_type_id']=array(2,3,7);
				break;
			case 1000://ENTRADAS Y SALIDAS
				$values['bindMovementType'] = 1;
				$initialStocks = $this->_get_stocks($initialData['items'], $initialData['warehouse'], $initialData['startDate'], '<');//before starDate, 'cause it will be added or substracted with movements quantities
				break;
			case 1001://TRASPASOS ENTRE ALMACENES
				$values['bindMovementType'] = 1;
				$conditions['InvMovement.inv_movement_type_id']=array(3,4);
				$warehouses[1]=$initialData['warehouse2'];
				break;
			default:
				$conditions['InvMovement.inv_movement_type_id']=$initialData['movementType'];
				break;
		}
		$conditions['InvMovement.inv_warehouse_id']=$warehouses;//necessary to be here
		$values['items']=$initialData['items'];//just for order
		switch($initialData['currency']){
			case 'BOLIVIANOS':
				//$fields = array('InvMovementDetail.fob_price', 'InvMovementDetail.cif_price', 'InvMovementDetail.sale_price');
				$fields[]='(SELECT price FROM inv_prices where inv_item_id = "InvMovementDetail"."inv_item_id" AND date <= "InvMovement"."date" AND inv_price_type_id=1 order by date DESC, date_created DESC LIMIT 1) AS "fob_price"';
				$fields[]='(SELECT price FROM inv_prices where inv_item_id = "InvMovementDetail"."inv_item_id" AND date <= "InvMovement"."date" AND inv_price_type_id=8 order by date DESC, date_created DESC LIMIT 1) AS "cif_price"';
				$fields[]='(SELECT price FROM inv_prices where inv_item_id = "InvMovementDetail"."inv_item_id" AND date <= "InvMovement"."date" AND inv_price_type_id=9 order by date DESC, date_created DESC LIMIT 1) AS "sale_price"';
				break;
			case 'DOLARES AMERICANOS':
				//$fields = array('InvMovementDetail.ex_fob_price', 'InvMovementDetail.ex_cif_price', 'InvMovementDetail.ex_sale_price');
				$fields[]='(SELECT ex_price FROM inv_prices where inv_item_id = "InvMovementDetail"."inv_item_id" AND date <= "InvMovement"."date" AND inv_price_type_id=1 order by date DESC, date_created DESC LIMIT 1) AS "ex_fob_price"';
				$fields[]='(SELECT ex_price FROM inv_prices where inv_item_id = "InvMovementDetail"."inv_item_id" AND date <= "InvMovement"."date" AND inv_price_type_id=8 order by date DESC, date_created DESC LIMIT 1) AS "ex_cif_price"';
				$fields[]='(SELECT ex_price FROM inv_prices where inv_item_id = "InvMovementDetail"."inv_item_id" AND date <= "InvMovement"."date" AND inv_price_type_id=9 order by date DESC, date_created DESC LIMIT 1) AS "ex_sale_price"';
				break;
		}
		
		return array('values'=>$values,'conditions'=>$conditions, 'fields'=>$fields, 'initialStocks'=>$initialStocks);
	}
	
	
	private function _generate_report_movements($values, $conditions, $fields){
		$staticFields = array(
			'InvMovement.id',
			'InvMovement.code',
			'InvMovement.document_code',
			'InvMovement.date',
			'InvMovement.inv_warehouse_id',
			'InvMovementDetail.inv_item_id',
			'InvMovementDetail.quantity'
			);
		if(isset($values['bindMovementType']) AND $values['bindMovementType'] == 1){
			$this->InvMovement->InvMovementDetail->bindModel(array(
				'hasOne'=>array(
					'InvMovementType'=>array(
						'foreignKey'=>false,
						'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
					)
				)
			));
			$fields[] = 'InvMovementType.status'; 
		}
		$this->InvMovement->InvMovementDetail->unbindModel(array('belongsTo' => array('InvItem')));
		return $this->InvMovement->InvMovementDetail->find('all', array(
					'conditions'=>array(
						'InvMovementDetail.inv_item_id'=>$values['items'],
						'InvMovement.lc_state'=>'APPROVED',
						'InvMovement.date BETWEEN ? AND ?' => array($values['startDate'], $values['finishDate']),
						$conditions
					),
					'fields'=>  array_merge($staticFields, $fields),
					'order'=>array('InvMovement.date', 'InvMovementDetail.id')
				));
	}
	
	
	private function _generate_report_items_complete($items){
		$this->loadModel('InvItem');
		$this->InvItem->unbindModel(array('hasMany' => array('InvMovementDetail', 'PurDetail', 'SalDetail', 'InvItemsSupplier', 'InvPrice')));
		return $this->InvItem->find('all', array(
			'fields'=>array('InvItem.id', 'InvItem.code', 'InvItem.name', 'InvBrand.name', 'InvCategory.name'),
			'conditions'=>array('InvItem.id'=>$items),
			'order'=>array('InvItem.code')
		));
	}
	
	//////////////////////////////////////////// END - REPORT /////////////////////////////////////////////////
	
	//////////////////////////////////////////START-GRAPHICS//////////////////////////////////////////
	
	
	public function vgraphics(){
		$this->loadModel("AdmPeriod");
		$years = $this->AdmPeriod->find("list", array(
			"order"=>array("name"=>"desc"),
			"fields"=>array("name", "name")
			)
		);
		/*
		$this->loadModel("InvItem");
		$itemsClean = $this->InvItem->find("list", array('order'=>array('InvItem.code')));
		$items[0]="TODOS";
		foreach ($itemsClean as $key => $value) {
			$items[$key] = $value;
		}
		*/
		$item = $this->_find_items();
		//$this->loadModel("InvPriceType");
		//$priceTypes = $this->InvPriceType->find("list", array("conditions"=>array("name"=>array("FOB", "CIF"))));
		
		$this->set(compact("years", "item"));
		//debug($this->_get_bars_sales_and_time("2013", "0"));
	}
	
	public function ajax_get_graphics_data(){
		if($this->RequestHandler->isAjax()){
			$year = $this->request->data['year'];
			//$month = $this->request->data['month'];
			$currency = $this->request->data['currency'];
			$item = $this->request->data['item'];
			$priceType = $this->request->data['priceType'];;
			$string = $this->_get_bars_purchases_and_time($year, $item, $currency, $priceType);
			echo $string;
		}
//		$string .= '30|54|12|114|64|100|98|80|10|50|169|222';
	}
	
	private function _get_bars_purchases_and_time($year, $item, $currency, $priceType){
		$conditionItem = null;
		$dataString = "";
		//$conditionMonth= null;
		if($item > 0){
			$conditionItem = array("PurDetail.inv_item_id" => $item);
		}
		
		$currencyField = "";
		if($currency == "dolares"){
			$currencyField = "ex_";
		}
		
		$priceTypeField = "fob_price";
		if($priceType == "CIF"){
			$priceTypeField = "cif_price";
		}
		/*
		if($month > 0){
			if(count($month) == 1){
				$conditionMonth = array("to_char(PurPurchase.date,'mm')" => "0".$month);
			}else{
				$conditionMonth = array("to_char(PurPurchase.date,'mm')" => $month);
			}
			
		}
		*/
		//*****************************************************************************//
		$this->PurPurchase->PurDetail->unbindModel(array('belongsTo' => array('InvSupplier')));
		$data = $this->PurPurchase->PurDetail->find('all', array(
			"fields"=>array(
				"to_char(\"PurPurchase\".\"date\",'mm') AS month",
				//'SUM("PurDetail"."quantity" * (SELECT '.$currencyType.'  FROM inv_prices where inv_item_id = "PurDetail"."inv_item_id" AND date <= "PurPurchase"."date" AND inv_price_type_id='.$priceType.' order by date DESC, date_created DESC LIMIT 1))'
				'SUM("PurDetail"."quantity" * "PurDetail"."'.$currencyField.$priceTypeField.'")'
			),
			"conditions"=>array(
				"to_char(PurPurchase.date,'YYYY')"=>$year,
				"PurPurchase.lc_state"=>"PINVOICE_APPROVED",
				$conditionItem,
				//$conditionMonth
				
			),
			'group'=>array("to_char(PurPurchase.date,'mm')")
		));
		//*****************************************************************************//
		
		
		//format data on string to response ajax request
		$months = array(1,2,3,4,5,6,7,8,9,10,11,12);
		
		foreach ($months as $month) {
			$exist = 0;
			foreach ($data as $value) {
				if($month == (int)$value[0]['month']){
					$dataString .= $value[0]['sum']."|";
					//debug($dataString);
					$exist++;
				}
			}
			if($exist == 0){
				$dataString .= "0|";
			}
		}
		
		return substr($dataString, 0, -1);
	}
	
	
	
	//////////////////////////////////////////END-GRAPHICS//////////////////////////////////////////
	
	//////////////////////////////////////////// START - INDEX ///////////////////////////////////////////////
	
	public function index_order() {	
		
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$doc_code = '';
		$note_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_order');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['PurPurchase']['code']) && $this->request->data['PurPurchase']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['PurPurchase']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['PurPurchase']['doc_code']) && $this->request->data['PurPurchase']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['PurPurchase']['note_code']));
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
		if(isset($this->passedArgs['doc_code'])){
			$filters['PurPurchase.code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['doc_code'])){
			$filters['PurPurchase.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$doc_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			"conditions"=>array(
				"PurPurchase.lc_state !="=>"ORDER_LOGIC_DELETED",
				'PurPurchase.lc_state LIKE'=> '%ORDER%',
				"to_char(PurPurchase.date,'YYYY')"=> $period,
			//	"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("PurPurchase.id", "PurPurchase.code", "PurPurchase.doc_code", "PurPurchase.date", "PurPurchase.note_code", /*"PurPurchase.inv_supplier_id", "InvSupplier.name",*/ "PurPurchase.lc_state"),
			"order"=> array("PurPurchase.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('purPurchases', $this->paginate('PurPurchase'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		
//		$this->paginate = array(
//			'conditions' => array(
//				'PurPurchase.lc_state !='=>'ORDER_LOGIC_DELETED'
//				,'PurPurchase.lc_state LIKE'=> '%ORDER%'
//			),
//			'order' => array('PurPurchase.id' => 'desc'),
//			'limit' => 15
//		);
//		$this->PurPurchase->recursive = 0;
//		$this->set('purPurchases', $this->paginate());
	}
	
	public function index_invoice(){
		///////////////////////////////////////START - CREATING VARIABLES//////////////////////////////////////
		$filters = array();
		$doc_code = '';
		$note_code = '';
		$period = $this->Session->read('Period.name');
		///////////////////////////////////////END - CREATING VARIABLES////////////////////////////////////////
		
		////////////////////////////START - WHEN SEARCH IS SEND THROUGH POST//////////////////////////////////////
		if($this->request->is("post")) {
			$url = array('action'=>'index_invoice');
			$parameters = array();
			$empty=0;
			if(isset($this->request->data['PurPurchase']['doc_code']) && $this->request->data['PurPurchase']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['PurPurchase']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['PurPurchase']['note_code']) && $this->request->data['PurPurchase']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['PurPurchase']['note_code']));
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
		if(isset($this->passedArgs['doc_code'])){
			$filters['PurPurchase.code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$doc_code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['note_code'])){
			$filters['PurPurchase.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$note_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->paginate = array(
			"conditions"=>array(
				"PurPurchase.lc_state !="=>"PINVOICE_LOGIC_DELETED",
				'PurPurchase.lc_state LIKE'=> '%PINVOICE%',
				"to_char(PurPurchase.date,'YYYY')"=> $period,
			//	"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("PurPurchase.id", "PurPurchase.code", "PurPurchase.doc_code", "PurPurchase.date", "PurPurchase.note_code",/*"PurPurchase.inv_supplier_id", "InvSupplier.name",*/ "PurPurchase.lc_state"),
			"order"=> array("PurPurchase.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('purPurchases', $this->paginate('PurPurchase'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		
		
//		$this->paginate = array(
//			'conditions' => array(
//				'PurPurchase.lc_state !='=>'INVOICE_LOGIC_DELETED',
//				'PurPurchase.lc_state LIKE'=> '%INVOICE%',
//			),
//			'order' => array('PurPurchase.id' => 'desc'),
//			'limit' => 15
//		);
//		$this->PurPurchase->recursive = 0;
//		$this->set('purPurchases', $this->paginate());
	}
	
	///////////////////////////////////////////// END - INDEX ////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - SAVE ///////////////////////////////////////////////
	
	public function save_order(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$this->loadModel('AdmParameter');
		$currency = $this->AdmParameter->AdmParameterDetail->find('first', array(
				'conditions'=>array(
					'AdmParameter.name'=>'Moneda',
					'AdmParameterDetail.par_char1'=>'Dolares'
				)
			)); 
		$currencyId = $currency['AdmParameterDetail']['id'];
		
		$this->PurPurchase->recursive = -1;
		$this->request->data = $this->PurPurchase->read(null, $id);
		$genericCode ='';
		$purDetails = array();
		$documentState = '';
		$date=date('d/m/Y');
		
		//////////////////////////////////////////////////////////
		$this->loadModel('AdmExchangeRate');
		$xxxRate = $this->AdmExchangeRate->find('first', array(
				'fields'=>array('AdmExchangeRate.value'),
				'conditions'=>array(
					'AdmExchangeRate.currency'=>$currencyId,
					'AdmExchangeRate.date'=>$date
				),
				'recursive'=>-1
			)); 		
		
		
		$exRate = $xxxRate['AdmExchangeRate']['value'];	//esto llamar al cambio del dia
		////////////////////////////////////////////////////////////
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['PurPurchase']['date']));//$this->request->data['InvMovement']['date'];
			$purDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['PurPurchase']['lc_state'];
			$genericCode = $this->request->data['PurPurchase']['code'];			
			$exRate = $this->request->data['PurPurchase']['ex_rate'];
		}
		$this->set(compact(/*'invSuppliers', */'id', 'date', 'purDetails', 'documentState', 'genericCode', 'exRate'));
	}
	
	public function save_invoice(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$this->loadModel('AdmParameter');
		$currency = $this->AdmParameter->AdmParameterDetail->find('first', array(
			//	'fields'=>array('AdmParameterDetail.id'),
				'conditions'=>array(
					'AdmParameter.name'=>'Moneda',
					'AdmParameterDetail.par_char1'=>'Dolares'
				)
			//	'recursive'=>-1
			)); 
//		debug($currency);
		$currencyId = $currency['AdmParameterDetail']['id'];
//		debug($currencyId);
		
		
//		$invSuppliers = $this->PurPurchase->InvSupplier->find('list');
				
		$this->PurPurchase->recursive = -1;
		$this->request->data = $this->PurPurchase->read(null, $id);
		$date='';
		$genericCode ='';
		$originCode = '';
		$purDetails = array();
		$purPrices = array();
		$purPayments = array();
		$documentState = '';
	//	$exRate = '8.00';	//esto tiene q llamar al cambio del dia
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['PurPurchase']['date']));//$this->request->data['InvMovement']['date'];
			$purDetails = $this->_get_movements_details($id);
			$purPrices = $this->_get_costs_details($id);
			$purPayments = $this->_get_pays_details($id);
			$documentState =$this->request->data['PurPurchase']['lc_state'];
			$genericCode = $this->request->data['PurPurchase']['code'];
			//buscar el codigo del documento origen
			$originDocCode = $this->PurPurchase->find('first', array(
				'fields'=>array('PurPurchase.doc_code'),
				'conditions'=>array(
					'PurPurchase.code'=>$genericCode,
					'PurPurchase.lc_state LIKE'=> '%ORDER%'
					)
			));
			$originCode = $originDocCode['PurPurchase']['doc_code'];
			
			
			
			$exRate = $this->request->data['PurPurchase']['ex_rate'];
		}
		
			
		$this->set(compact(/*'invSuppliers', */'id', 'date', 'purDetails', 'purPrices', 'purPayments', 'documentState', 'genericCode', 'originCode', 'exRate'));
//debug($this->request->data);
	}
	
	//////////////////////////////////////////// END - SAVE /////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - AJAX ///////////////////////////////////////////////
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$supplierItemsAlreadySaved = $this->request->data['supplierItemsAlreadySaved'];
			$date = $this->request->data['date'];
			
			$invSuppliers = $this->PurPurchase->PurDetail->InvItem->InvItemsSupplier->InvSupplier->find('list');
			$supplier = key($invSuppliers);
			$itemsBySupplier = $this->PurPurchase->PurDetail->InvItem->InvItemsSupplier->find('list', array(
				'fields'=>array('InvItemsSupplier.inv_item_id'),
				'conditions'=>array(
					'InvItemsSupplier.inv_supplier_id'=>$supplier
				),
				'recursive'=>-1
			)); 	
			
			$itemsAlreadyTakenFromSupplier = array();
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($supplierItemsAlreadySaved[$i] == $supplier){
					$itemsAlreadyTakenFromSupplier[] = $itemsAlreadySaved[$i];
				}	
			}		
			$items = $this->PurPurchase->PurDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadyTakenFromSupplier)
					,'InvItem.id'=>$itemsBySupplier
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
		// gets the last date price in the list of the item prices
		$firstItemListed = key($items);
		$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.ex_price'),
			'order' => array('InvPrice.date' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$firstItemListed
				,'InvPrice.inv_price_type_id'=>1
				,'InvPrice.date <='=>$date
				)
		));
		if($priceDirty == array() || $priceDirty['InvPrice']['ex_price'] == null){
			$price = '';
		}  else {
			$price = $priceDirty['InvPrice']['ex_price'];
		}
//			$fields[]="SELECT ex_price FROM inv_prices where inv_item_id = '$firstItemListed' AND date <= '$date' AND inv_price_type_id=1 order by date DESC, date_created DESC LIMIT 1";
//			debug($fields);	
			$this->set(compact('items', 'price', 'invSuppliers', 'supplier'));
		}
	}
	
	public function ajax_update_items_modal(){
		if($this->RequestHandler->isAjax()){
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$supplierItemsAlreadySaved = $this->request->data['supplierItemsAlreadySaved'];
			$supplier = $this->request->data['supplier'];
			$date = $this->request->data['date'];
			
			$itemsBySupplier = $this->PurPurchase->PurDetail->InvItem->InvItemsSupplier->find('list', array(
				'fields'=>array('InvItemsSupplier.inv_item_id'),
				'conditions'=>array(
					'InvItemsSupplier.inv_supplier_id'=>$supplier
				),
				'recursive'=>-1
			)); 	
			
			$itemsAlreadyTakenFromSupplier = array();
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($supplierItemsAlreadySaved[$i] == $supplier){
					$itemsAlreadyTakenFromSupplier[] = $itemsAlreadySaved[$i];
				}	
			}
			
			$items = $this->PurPurchase->PurDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadyTakenFromSupplier)
					,'InvItem.id'=>$itemsBySupplier
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
//			debug($items);
			$item = key($items);
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$item
				,'InvPrice.inv_price_type_id'=>1
				,'InvPrice.date <='=>$date
				)
			));
			if($priceDirty==array()){
			$price = '';
			}  else {
			
			$price = $priceDirty['InvPrice']['price'];
			}
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			//$stock = $this->_find_stock($item, $warehouse);
//			$stocks = $this->_get_stocks($item, $warehouse);
//			$stock = $this->_find_item_stock($stocks, $item);
			$this->set(compact('items', 'price'/*, 'stock'*/));
		}
	}
	
	public function ajax_initiate_modal_add_cost(){
		if($this->RequestHandler->isAjax()){
		//				$cost = $this->request->data['cost'];
			$costsAlreadySaved = $this->request->data['costsAlreadySaved'];
//			$warehouse = $this->request->data['warehouse'];
		//	$supplier = $this->request->data['supplier'];
//	//		$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
//				'fields'=>array('InvItemsSupplier.inv_item_id'),
//				'conditions'=>array(
//					'InvItemsSupplier.inv_supplier_id'=>$supplier
//				),
//				'recursive'=>-1
//			)); 
//debug($itemsBySupplier);			
			$costs = $this->PurPurchase->PurPrice->InvPriceType->find('list', array(
					'fields'=>array('InvPriceType.name'),
					'conditions'=>array(
						'NOT'=>array('InvPriceType.id'=>$costsAlreadySaved,'InvPriceType.name'=>array('VENTA','FOB','CIF'))
				),
				
				'recursive'=>-1
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
//debug($costs);			
//debug($items);
//debug($this->request->data);
		// gets the first price in the list of the item prices
//		$firstItemListed = key($items);
//		$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//			'fields'=>array('InvPrice.price'),
//			'order' => array('InvPrice.date_created' => 'desc'),
//			'conditions'=>array(
//				'InvPrice.inv_item_id'=>$firstItemListed
//				)
//		));
////debug($priceDirty);
//		if($priceDirty==array()){
//			$price = 0;
//		}  else {
//			
//			$price = $priceDirty['InvPrice']['price'];
//		}
//			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
//			'fields'=>array('PurPrice.amount'),
//	//		'order' => array('rice.date_created' => 'desc'),
//			'conditions'=>array(
//				'PurPrice.inv_price_type_id'=>$costsAlreadySaved
//				)
//			));
//			if($amountDirty==array()){
//			$amount = 0;
//		}  else {
//			
//			$amount = $amountDirty['PurPrice']['amount'];
//		}
				
			$this->set(compact('costs'/*, 'amount'*/));
		}
	}
	
	//Hace un find en la BD de los elementos que se mostraran en el combobox 
	public function ajax_initiate_modal_add_pay(){
		if($this->RequestHandler->isAjax()){
			$paysAlreadySaved = $this->request->data['paysAlreadySaved'];
			$payDebt = $this->request->data['payDebt'];
//			debug($payDebt);
			$datePay=date('d/m/Y');
//			$debt = $this->SalSale->SalPayment->find('list', array(
//					'fields'=>array('SalPayment.amount'),
//					'conditions'=>array(
//						'SalPayment.date'=>$paysAlreadySaved
//				),
//				'recursive'=>-1
//			));
//	debug($debt);
//			$warehouse = $this->request->data['warehouse'];
		//	$supplier = $this->request->data['supplier'];
//	//		$itemsBySupplier = $this->PurPurchase->InvSupplier->InvItemsSupplier->find('list', array(
//				'fields'=>array('InvItemsSupplier.inv_item_id'),
//				'conditions'=>array(
//					'InvItemsSupplier.inv_supplier_id'=>$supplier
//				),
//				'recursive'=>-1
//			)); 
//debug($itemsBySupplier);			
			$pays = $this->PurPurchase->PurPayment->PurPaymentType->find('list', array(
					'fields'=>array('PurPaymentType.name'),
					'conditions'=>array(
//						'NOT'=>array('InvPriceType.id'=>$paysAlreadySaved) /*aca se hace la discriminacion de items seleccionados*/
				),
				
				'recursive'=>-1
				//'fields'=>array('InvItem.id', 'CONCAT(InvItem.code, '-', InvItem.name)')
			));
//debug($supplier);			
//debug($items);
//debug($this->request->data);
		// gets the first price in the list of the item prices
//		$firstItemListed = key($items);
//		$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//			'fields'=>array('InvPrice.price'),
//			'order' => array('InvPrice.date_created' => 'desc'),
//			'conditions'=>array(
//				'InvPrice.inv_item_id'=>$firstItemListed
//				)
//		));
////debug($priceDirty);
//		if($priceDirty==array()){
//			$price = 0;
//		}  else {
//			
//			$price = $priceDirty['InvPrice']['price'];
//		}
//			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
//			'fields'=>array('PurPrice.amount'),
//	//		'order' => array('rice.date_created' => 'desc'),
//			'conditions'=>array(
//				'PurPrice.inv_price_type_id'=>$costsAlreadySaved
//				)
//			));
//			if($amountDirty==array()){
//			$amount = 0;
//		}  else {
//			
//			$amount = $amountDirty['PurPrice']['amount'];
//		}
				
			$this->set(compact('pays', 'datePay', 'payDebt'/*, 'amount'*/));
		}
	}
	
	public function ajax_update_stock_modal(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$date = $this->request->data['date'];
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$item
				,'InvPrice.inv_price_type_id'=>1
				,'InvPrice.date <='=>$date
				)
			));
			if($priceDirty==array()){
			$price ='';
			}else{
			
			$price = $priceDirty['InvPrice']['price'];
			}
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$this->set(compact('price'));
		}
	}
	
	//no se utiliza pq no tiene que mostrar ningun valos en otro campo a partir del elemento elegido en el combobox
	public function ajax_update_amount(){
		if($this->RequestHandler->isAjax()){
			$cost = $this->request->data['cost'];
//			$warehouse = $this->request->data['warehouse']; //if it's warehouse_transfer is OUT
//			$warehouse2 = $this->request->data['warehouse2'];//if it's warehouse_transfer is IN
//			$transfer = $this->request->data['transfer'];
			
//			$stock = $this->_find_stock($item, $warehouse);//if it's warehouse_transfer is OUT
//			$stock2 ='';
//			if($transfer == 'warehouses_transfer'){
//				$stock2 = $this->_find_stock($item, $warehouse2);//if it's warehouse_transfer is IN	
//			}
			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
			'fields'=>array('PurPrice.amount'),
	//		'order' => array('rice.date_created' => 'desc'),
			'conditions'=>array(
				'PurPrice.inv_price_type_id'=>$cost
				)
			));
			if($amountDirty==array()){
			$amount = 0;
		}  else {
			
			$amount = $amountDirty['PurPrice']['amount'];
		}
			$this->set(compact('amount'));
		}
	}
	
	public function ajax_update_ex_rate(){
		if($this->RequestHandler->isAjax()){
			$date = $this->request->data['date']; 
			
			$this->loadModel('AdmParameter');
			$currency = $this->AdmParameter->AdmParameterDetail->find('first', array(
					'conditions'=>array(
						'AdmParameter.name'=>'Moneda',
						'AdmParameterDetail.par_char1'=>'Dolares'
					)
				)); 
			$currencyId = $currency['AdmParameterDetail']['id'];
			$this->loadModel('AdmExchangeRate');
			$xxxRate = $this->AdmExchangeRate->find('first', array(
					'fields'=>array('AdmExchangeRate.value'),
					'conditions'=>array(
						'AdmExchangeRate.currency'=>$currencyId,
						'AdmExchangeRate.date'=>$date
					),
					'recursive'=>-1
				)); 		
			if ($xxxRate == array()){
				$exRate = '';
			}else{
				$exRate = $xxxRate['AdmExchangeRate']['value'];
			}
			$this->set(compact('exRate'));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	
	//aca tendria que poder calcular el pago adeudado en base a los pagos guardados
//	public function ajax_update_pay(){
//		if($this->RequestHandler->isAjax()){
//			$pay = $this->request->data['pay'];
//			$amountDirty = $this->PurPurchase->PurPrice->find('first', array(
//			'fields'=>array('PurPrice.amount'),
//			'conditions'=>array(
//				'PurPrice.inv_price_type_id'=>$cost
//				)
//			));
//			if($amountDirty==array()){
//			$amount = 0;
//		}  else {
//			$amount = $amountDirty['PurPrice']['amount'];
//		}
//			$this->set(compact('amount'));
//		}
//	}
	
	public function ajax_save_movement(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START - RECIEVE AJAX////////////////////////////////////////////////////////
			//For making algorithm
			$ACTION = $this->request->data['ACTION'];
			$OPERATION= $this->request->data['OPERATION'];
			$STATE = $this->request->data['STATE'];//also for Movement
			$OPERATION3 = $OPERATION;
			//Sale
			$purchaseId = $this->request->data['purchaseId'];
			$purchaseOrderDocCode = $this->request->data['movementDocCode'];
			$purchaseCode = $this->request->data['movementCode'];
			$noteCode = $this->request->data['noteCode'];
			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			//Sale Details
			$supplierId = $this->request->data['supplierId'];
			$itemId = $this->request->data['itemId'];
			$exFobPrice = $this->request->data['exFobPrice'];
			$quantity = $this->request->data['quantity'];
			$fobPrice = $exFobPrice * $exRate;
			if ($ACTION == 'save_invoice' && $STATE == 'PINVOICE_APPROVED'){
				//variables used to calculate apportionment assigned when Invoice is APPROVED
				$arrayItemsDetails = $this->request->data['arrayItemsDetails'];	
				$total = $this->request->data['total'];
				$totalCost = $this->request->data['totalCost'];
//				debug($arrayItemsDetails);
//				debug($total);
//				debug($totalCost);
			}
			if (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
				//variables used to save Pays assigned on Invoice
				$payDate = $this->request->data['payDate'];
				$payAmount = $this->request->data['payAmount'];
				$payDescription = $this->request->data['payDescription'];
			}
			if (($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')) {
				//variables used to save Costs assigned on Invoice
				$costId = $this->request->data['costId'];
				$costExAmount = $this->request->data['costExAmount'];
			}
			//For validate before approve OUT or cancelled IN
//			$arrayForValidate = array();
//			if(isset($this->request->data['arrayForValidate'])){$arrayForValidate = $this->request->data['arrayForValidate'];}
			//Internal variables
			$error=0;
			$movementDocCode = '';
//			$movementDocCode4 = '';
			////////////////////////////////////////////END - RECIEVE AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////////START - SET DATA/////////////////////////////////////////////////////
			//header for ORDER
			$arrayPurchaseOrder['note_code']=$noteCode;
			$arrayPurchaseOrder['date']=$date;
			$arrayPurchaseOrder['inv_supplier_id']=$supplierId;
			$arrayPurchaseOrder['description']=$description;
			$arrayPurchaseOrder['ex_rate']=$exRate;
			$arrayPurchaseOrder['lc_state']=$STATE;
			if ($ACTION == 'save_order'){
				//header for INVOICE
				$arrayPurchaseInvoice['note_code']=$noteCode;
				$arrayPurchaseInvoice['date']=$date;
				$arrayPurchaseInvoice['inv_supplier_id']=$supplierId;
				$arrayPurchaseInvoice['description']=$description;
				$arrayPurchaseInvoice['ex_rate']=$exRate;
				//header for MOVEMENT
				$arrayMovement['date']=$date;
				$arrayMovement['inv_warehouse_id']=2;//EL ALTO 2 MANUALMENTE VER COMO ELEGIR ESTO
				$arrayMovement['inv_movement_type_id']=1; //Reynaldo Rojas Compra = 1
				$arrayMovement['description']=$description;
				
				if ($STATE == 'ORDER_APPROVED') {
					$arrayPurchaseInvoice['lc_state']='PINVOICE_PENDANT';
				}elseif ($STATE == 'ORDER_PENDANT') {
					$arrayPurchaseInvoice['lc_state']='DRAFT';
					$arrayMovement['lc_state']='DRAFT';
//					$arrayMovement4['lc_state']='DRAFT';
//					debug($arrayMovement3['lc_state']);
				}
			}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')){
				//pay details for INVOICE
				$arrayPayDetails = array('pur_payment_type_id'=>1,//Efectivo(Contado?) 
										'date'=>$payDate,
										'description'=>$payDescription,
										//Bs.					$us.
										'amount'=>$payAmount, 'ex_amount'=>($payAmount / $exRate)
										);
			}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')){
				//cost details for INVOICE
				$arrayCostDetails = array('inv_price_type_id'=>$costId,
										//$us.						  Bs.
										'ex_amount'=>$costExAmount, 'amount'=>($costExAmount * $exRate)
										);
			}elseif($ACTION == 'save_invoice'){
				//header for MOVEMENT
				$arrayMovement['date']=$date;
				$arrayMovement['inv_warehouse_id']=2;//EL ALTO 2 MANUALMENTE VER COMO ELEGIR ESTO
				$arrayMovement['inv_movement_type_id']=1; //Reynaldo Rojas Compra = 1
				$arrayMovement['description']=$description;
				if ($STATE == 'PINVOICE_PENDANT') {
					$arrayMovement['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovementHeadsUpd
//					$arrayMovement4['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovementHeadsUpd
				}
			}			
			//item details for ORDER & INVOICE
			$arrayPurchaseDetails = array('inv_supplier_id'=>$supplierId,  
										'inv_item_id'=>$itemId,
										'ex_fob_price'=>$exFobPrice, 'fob_price'=>$fobPrice,
										'quantity'=>$quantity);
			if ($ACTION == 'save_order'){
//				$stocks = $this->_get_stocks($itemId, 1);//$warehouseId);
//				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement['type']=1;//NON BACKORDER

//				$arrayMovement4['date']=$date;
//				$arrayMovement4['inv_warehouse_id']=2;//EL ALTO 2 MANUALMENTE VER COMO ELEGIR ESTO
//				$arrayMovement4['inv_movement_type_id']=1; //Reynaldo Rojas Compra = 1
//				$arrayMovement4['description']=$description;
//				$arrayMovement4['type']=2;
//				$surplus = $quantity - $stock;
//				if($quantity > $stock){
//					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
//					$OPERATION4 = 'ADD';
//				}else{
					$arrayMovementDetails = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
//				}	
//				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {//CREO Q FALTA COST!!!!!!!!!!!!!!!!!!!!!!!!!!!11
//				$stocks = $this->_get_stocks($itemId, $warehouseId);
//				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement['type']=1;
//				$arrayMovement4['date']=$date;
//				$arrayMovement4['inv_warehouse_id']=$warehouseId;
//				$arrayMovement4['inv_movement_type_id']=2;
//				$arrayMovement4['description']=$description;
//				$arrayMovement4['type']=2;
//				$surplus = $quantity - $stock;
//				if($quantity > $stock){
//					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
//					$OPERATION4 = 'ADD';
//				}else{
					$arrayMovementDetails = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
//				}	
//				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}
			//INSERT OR UPDATE
			if($purchaseId == ''){//INSERT
//				switch ($ACTION) {
//					case 'save_order':
				if($ACTION == 'save_order'){
					//ORDER
					$purchaseCode = $this->_generate_code('COM');
					$purchaseOrderDocCode = $this->_generate_doc_code('ORD');
					$arrayPurchaseOrder['code'] = $purchaseCode;
					$arrayPurchaseOrder['doc_code'] = $purchaseOrderDocCode;
					//INVOICE
					$purchaseInvoiceDocCode = 'NO';
					$arrayPurchaseInvoice['code'] = $purchaseCode;
					$arrayPurchaseInvoice['doc_code'] = $purchaseInvoiceDocCode;
					//MOVEMENT type 1(hay stock)
					$arrayMovement['document_code'] = $purchaseCode;
					$arrayMovement['code'] = $purchaseInvoiceDocCode;
					//MOVEMENT type 2(NO hay stock)
//						$arrayMovement4['document_code'] = $movementCode;
//						$arrayMovement4['code'] = $movementDocCode2;
//						break;
				}
//				}
				if($purchaseCode == 'error'){$error++;}
				if($purchaseOrderDocCode == 'error'){$error++;}
				if($purchaseInvoiceDocCode == 'error'){$error++;}
			}else{//UPDATE
				//ORDER id
				$arrayPurchaseOrder['id'] = $purchaseId;
				if ($ACTION == 'save_order'){
					//gets INVOICE id
					$arrayPurchaseInvoice['id'] = $this->_get_doc_id($purchaseId, $purchaseCode, null, null);
					//gets MOVEMENT id type 1(hay stock)
					$arrayMovement['id'] = $this->_get_doc_id(null, $purchaseCode, 1, 2);//$warehouseId);
					if($arrayMovement['id'] === null){
						$arrayMovement['document_code'] = $purchaseCode;
						$arrayMovement['code'] = 'NO';
					}
					//movement id type 2(NO hay stock)
//					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, 2);//$warehouseId);
//					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){
//						$arrayMovement4['document_code'] = $movementCode;
//						$arrayMovement4['code'] = 'NO';
//					}
//					if($quantity > $stock){//CHEKAR BIEN ESTO, CREO Q YA NO VA!!!
//						$arrayMovement4['document_code'] = $movementCode;
//						$arrayMovement4['code'] = 'NO';
//					}
//					REVISAR SI ESTO SERVIA PARA ALGO |||||| REVISAR SI ESTO SERVIA PARA ALGO ||||| REVISAR SI ESTO SERVIA PARA ALGO  					
//					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
//						$OPERATION4 = 'DELETE';
//					}
					if ($STATE == 'ORDER_APPROVED') {
						//FOR INVOICE
						$purchaseInvoiceDocCode = $this->_generate_doc_code('CFA');
						$arrayPurchaseInvoice['doc_code'] = $purchaseInvoiceDocCode;
					}
				//UPDATING ITEMS DETAILS	
				}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY' && $OPERATION != 'ADD_COST' && $OPERATION != 'EDIT_COST' && $OPERATION != 'DELETE_COST') {
					//movement id type 1(hay stock)
					$arrayMovement['id'] = $this->_get_doc_id(null, $purchaseCode, 1, 2);//$warehouseId);
					if($arrayMovement['id'] === null){//SI NO HAY EL DOCUMENTO (CON STOCK) SE CREA
						$arrayMovement['document_code'] = $purchaseCode;
						$movementDocCode = $this->_generate_movement_code('SAL',null);
						$arrayMovement['code'] = $movementDocCode;//'NO';
					}
					//movement id type 2(NO hay stock)
//					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, $warehouseId);
//					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){//SI NO HAY EL DOCUMENTO (SIN STOCK), Y LA CANTIDAD SOBREPASA EL STOCK SE CREA
//						$arrayMovement4['document_code'] = $movementCode;
//						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
//						$arrayMovement4['code'] = $movementDocCode4;//'NO';
//					}
//					if($quantity > $stock){
//						$arrayMovement4['document_code'] = $movementCode;
//						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
//						$arrayMovement4['code'] = $movementDocCode4;//'NO';
//					}
//					REVISAR SI ESTO SERVIA PARA ALGO |||||| REVISAR SI ESTO SERVIA PARA ALGO ||||| REVISAR SI ESTO SERVIA PARA ALGO  
//					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
//						$OPERATION4 = 'DELETE';
//					}
				}
				if($purchaseOrderDocCode == 'error'){$error++;}
//				if($movementDocCode4 == 'error'){$error++;}
			}
			//-------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order--------------------------------
//			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE')){	
			$arrayMovement6 = null;
			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE'/* && $OPERATION4 == 'DELETE'*/)){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
				if (($arrayMovement['id'] != null)||($arrayMovementDetails['inv_item_id'] != null)){
					$rest3 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement['id']
							),
						'recursive'=>0
					));
				}
//				if (($arrayMovement4['id'] != null)||($arrayMovementDetails4['inv_item_id'] != null)){
//					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
//						'conditions'=>array(
//							'NOT'=>array(
//								'AND'=>array(
//									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
//									)
//								)
//							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//							),
//						'recursive'=>0
//					));
//				}	
//				if(($rest3 == 0) && ($rest4 == 0) && ($arrayMovement3['id'] != null) && ($arrayMovement4['id'] != null)){
//					$arrayMovement6 = array(
//						array('InvMovement.id' => array($arrayMovement3['id'],$arrayMovement4['id']))
//					);
//				}else
					if(($rest3 == 0) && ($arrayMovement['id'] != null)){
					$arrayMovement6 = array(
						array('InvMovement.id' => $arrayMovement['id'])
					);
				}
//				elseif(($rest4 == 0) && ($arrayMovement4['id'] != null)){
//					$arrayMovement6 = array(
//						 array('InvMovement.id' => $arrayMovement4['id'])
//					);
//				}
//				else{
//					$arrayMovement6 = null;
//				}
			}
			//---------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order------------------------------
//			-------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice--------------------------------
//			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')){	
			$draftId3 = null;
//			$draftId4 = null;
			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE' /*&& $OPERATION4 == 'DELETE'*/)){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
				if (($arrayMovement['id'] != null)||($arrayMovementDetails['inv_item_id'] != null)){
					$rest3 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement['id']
							),
						'recursive'=>0
					));
				}
//				if (($arrayMovement4['id'] != null)||($arrayMovementDetails4['inv_item_id'] != null)){
//					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
//						'conditions'=>array(
//							'NOT'=>array(
//								'AND'=>array(
//									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
//									)
//								)
//							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
//							),
//						'recursive'=>0
//					));
//				}	
//				if(($rest3 == 0) && ($rest4 == 0) && ($arrayMovement3['id'] != null) && ($arrayMovement4['id'] != null)){
//					$draftId3 = $arrayMovement3['id'];
//					$draftId4 = $arrayMovement4['id'];
//					echo "<br>1<br>";
//					debug($draftId3);
//					debug($draftId4);
//				}else
					if(($rest3 == 0) && ($arrayMovement['id'] != null)){
					$draftId3 = $arrayMovement['id'];
//					$draftId4 = null;
//					echo "<br>2<br>";
//					debug($draftId3);
				}
//				elseif(($rest4 == 0) && ($arrayMovement4['id'] != null)){
//					$draftId4 = $arrayMovement4['id'];
//					$draftId3 = null;
//					echo "<br>3<br>";
//					debug($draftId4);
//				}
//				else{
//					$draftId3 = null;
//					$draftId4 = null;
//				}
			}
//			---------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice------------------------------
//			---------------------------FOR UPDATING HEADS ON MOVEMENTS------------------------------------------------------
				$this->loadModel('InvMovement');
				$arrayMovementHeadsUpd = $this->InvMovement->find('all', array(
					'fields'=>array(
						'InvMovement.id'
	//					,'InvMovement.date'
	//					,'InvMovement.description'
	//					,'InvMovement.lc_state'
	//					,'InvMovement.inv_warehouse_id'
						),
					'conditions'=>array(
							'InvMovement.document_code'=>$purchaseCode
						)
					,'order' => array('InvMovement.id' => 'ASC')
					,'recursive'=>0
				));
				if(($arrayMovementHeadsUpd <> null)&&($STATE == 'ORDER_CANCELLED')){
					for($i=0;$i<count($arrayMovementHeadsUpd);$i++){
						$arrayMovementHeadsUpd[$i]['InvMovement']['lc_state'] = 'DRAFT';
					}
				}elseif(($arrayMovementHeadsUpd <> null)&&($STATE == 'ORDER_APPROVED')) {
					for($i=0;$i<count($arrayMovementHeadsUpd);$i++){
						$movementDocCode5 = $this->_generate_movement_code('ENT','inc');
						$arrayMovementHeadsUpd[$i]['InvMovement']['lc_state']='PENDANT';
						$arrayMovementHeadsUpd[$i]['InvMovement']['code'] = $movementDocCode5;
						$arrayMovementHeadsUpd[$i]['InvMovement']['date'] = $date;
						$arrayMovementHeadsUpd[$i]['InvMovement']['description'] = $description;
					}
				}elseif($arrayMovementHeadsUpd <> null){
					for($i=0;$i<count($arrayMovementHeadsUpd);$i++){
						$arrayMovementHeadsUpd[$i]['InvMovement']['date'] = $date;
						$arrayMovementHeadsUpd[$i]['InvMovement']['description'] = $description;
						/////////////////////////////////////////////////////////////////
						if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE')/* || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')*/){		
							if($arrayMovementHeadsUpd[$i]['InvMovement']['id'] === $draftId3){
								$arrayMovementHeadsUpd[$i]['InvMovement']['lc_state']='DRAFT';
							}
	//						if($arrayMovementHeadsUpd[$i]['InvMovement']['id'] === $draftId4){
	//							$arrayMovementHeadsUpd[$i]['InvMovement']['lc_state']='DRAFT';
	//						}
						}	
						/////////////////////////////////////////////////////////////////
					}
				}
//			---------------------------FOR UPDATING HEADS ON MOVEMENTS------------------------------------------------------

			//*********************************************************
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//			if ($ACTION == 'save_invoice' && $STATE == 'SINVOICE_PENDANT'){
//				if($draftId3 == null){
//					$arrayMovement3['lc_state']='PENDANT';
//				}
//				if($draftId4 == null){
//					$arrayMovement4['lc_state']='PENDANT';
//				}
//			}
			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			$dataMovement = null;
			$dataMovementDetail = null;
			$dataMovementHeadsUpd = null;
			//for ORDER	when save_order / INVOICE when save_invoice
			$dataPurchase[0] = array('PurPurchase'=>$arrayPurchaseOrder);
			if ($ACTION == 'save_order'){
				$this->loadModel('InvMovement');
				//for INVOICE
				$dataPurchase[1] = array('PurPurchase'=>$arrayPurchaseInvoice);
				//for MOVEMENT
				$dataMovement = array('InvMovement'=>$arrayMovement);
				//for MOVEMENT Details
				$dataMovementDetail = array('InvMovementDetail'=> $arrayMovementDetails);
//				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
//				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovementHeadsUpd <> null){
					$dataMovementHeadsUpd = $arrayMovementHeadsUpd;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null)/* || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)*/) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
				$dataCostDetail = null;
			}elseif (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
				$dataPayDetail = array('PurPayment'=> $arrayPayDetails);
				if($arrayMovementHeadsUpd <> null){
					$dataMovementHeadsUpd = $arrayMovementHeadsUpd;
				}	
				$dataCostDetail = null;
			}elseif (($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')) {
				$dataCostDetail = array('PurPrice'=> $arrayCostDetails);
				if($arrayMovementHeadsUpd <> null){
					$dataMovementHeadsUpd = $arrayMovementHeadsUpd;
				}	
				$dataPayDetail = null;
			}elseif ($ACTION == 'save_invoice') {
				$this->loadModel('InvMovement');
				//for movement
				$dataMovement = array('InvMovement'=>$arrayMovement);
				$dataMovementDetail = array('InvMovementDetail'=> $arrayMovementDetails);
//				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
//				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovementHeadsUpd <> null){
					$dataMovementHeadsUpd = $arrayMovementHeadsUpd;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) /*|| ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)*/) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
				$dataCostDetail = null;
			}
			$dataPurchaseDetail[0] = array('PurDetail'=> $arrayPurchaseDetails);
			if ($ACTION == 'save_order'){
				$dataPurchaseDetail[1] = array('PurDetail'=> $arrayPurchaseDetails);
			}
			////////////////////////////////////////////////END - SET DATA//////////////////////////////////////////////////////
			
//			$validation['error'] = 0;
//			$strItemsStock = '';	//IS IT USEFUL?
			////////////////////////////////////////////START- CORE SAVE////////////////////////////////////////////////////////
			if($error == 0){
				/////////////////////START - SAVE/////////////////////////////	
//				echo '$dataPurchase';	
//					print_r($dataPurchase);
//				echo '$dataPurchaseDetail';	
//					print_r($dataPurchaseDetail);
//				echo '------------------------------------------------ <br>';
//				echo 'OPERATION';
//					debug($OPERATION);
//				echo 'ACTION';
//					debug($ACTION);
//				echo '$dataMovement';	
//					print_r($dataMovement);
//				echo '$dataMovementDetail';	
//					print_r($dataMovementDetail);
//				echo '------------------------------------------------ <br>';
//				echo 'OPERATION2';
//					debug($OPERATION);
//				echo 'ACTION';
//					debug($ACTION);
//				echo '$dataMovement2';	
//					debug($dataMovement2);
//				echo '$dataMovementDetail2';	
//					debug($dataMovementDetail);
//				echo '------------------------------------------------ <br>';
//				echo 'STOCK';
//					debug($stock);
//				echo 'OPERATION3';
//					debug($OPERATION3);
//				echo '$dataMovement3';	
//					debug($dataMovement3);
//				echo '$dataMovementDetail3';	
//					debug($dataMovementDetail3);
//				echo '------------------------------------------------ <br>';	
//				echo 'QUANTITY';
//					debug($quantity);
//				echo 'OPERATION4';
//					debug($OPERATION4);
//				echo '$dataMovement4';	
//					debug($dataMovement4);
//				echo '$dataMovementDetail4';	
//					debug($dataMovementDetail4);
//				echo '------------------------------------------------ <br>';	
//				echo '$dataMovementHeadsUpd';	
//					debug($dataMovementHeadsUpd);
//				echo '------------------------------------------------ <br>';	
//				echo '$dataMovement6';	
//					debug($dataMovement6);
//				echo '------------------------------------------------ <br>';
//				echo '$dataPayDetail';
//				debug($dataPayDetail);
//				debug($arrayPayDetails);
//				debug($dataPayDetail);
//				debug($supplier);
//				debug($dataCostDetail);
//				
//				if ($ACTION == 'save_invoice' && $STATE == 'PINVOICE_APPROVED'){
//					$this->loadModel('InvPrice');
//					$prices = $this->InvPrice->find('all', array(
//					'fields'=>array(
//						'InvPrice.inv_item_id'
//						,'InvPrice.inv_price_type_id'
//						,'InvPrice.ex_price'
//						),
//					'conditions'=>array(
//						'InvPrice.date'=>$date
//						),
//					'recursive'=>-1
//					));
//			print_r($prices);
//			print_r($arrayItemsDetails);
//					for($i=0;$i<count($arrayItemsDetails);$i++){
//						$contFob = 0;
//						print_r($arrayItemsDetails[$i]);
//						for($j=0;$j<count($prices);$j++){
//							print_r($prices[$j]['InvPrice']);
//							if($prices[$j]['InvPrice']['inv_item_id'] == $arrayItemsDetails[$i]['inv_item_id'] &&  $prices[$j]['InvPrice']['inv_price_type_id'] == 1 && $prices[$j]['InvPrice']['ex_price'] == $arrayItemsDetails[$i]['ex_fob_price']){	
//								/*echo */$contFob += 1;
//							}
//						}
//						if($contFob == 0){
//								debug($arrayItemsDetails[$i]);
//						}
//					}
//				}
				$arrayFobPrices = array();
				$arrayCifPrices = array();
				if ($ACTION == 'save_invoice' && $STATE == 'PINVOICE_APPROVED'){
					$this->loadModel('InvPrice');
					$prices = $this->InvPrice->find('all', array(
					'fields'=>array(
						'InvPrice.inv_item_id'
						,'InvPrice.inv_price_type_id'
						,'InvPrice.ex_price'
						),
					'conditions'=>array(
						'InvPrice.date <='=>$date
						),
					'recursive'=>-1
					));
					$arrayFobPrices = array();
					$arrayCifPrices = array();
					$perc = $totalCost/$total;
					for($i=0;$i<count($arrayItemsDetails);$i++){
						$cif = $arrayItemsDetails[$i]['ex_fob_price'] + ($arrayItemsDetails[$i]['ex_fob_price'] * $perc);
						$contFob = 0; 
						$contCif = 0;
						for($j=0;$j<count($prices);$j++){
							if($prices[$j]['InvPrice']['inv_item_id'] == $arrayItemsDetails[$i]['inv_item_id'] && $prices[$j]['InvPrice']['inv_price_type_id'] == 1 && $prices[$j]['InvPrice']['ex_price'] == $arrayItemsDetails[$i]['ex_fob_price']){	
								$contFob += 1;
							}							
							if($prices[$j]['InvPrice']['inv_item_id'] == $arrayItemsDetails[$i]['inv_item_id'] && $prices[$j]['InvPrice']['inv_price_type_id'] == 8 && $prices[$j]['InvPrice']['ex_price'] == $cif){	
								$contCif += 1;
							}							
						}
						if($contFob === 0){							
							$arrayFobPrices[$i]['inv_item_id'] = $arrayItemsDetails[$i]['inv_item_id'];
							$arrayFobPrices[$i]['inv_price_type_id'] = 1;//or better relate by name FOB
							$arrayFobPrices[$i]['ex_price'] = $arrayItemsDetails[$i]['ex_fob_price'];
							$arrayFobPrices[$i]['price'] = $arrayItemsDetails[$i]['fob_price'];
							$arrayFobPrices[$i]['description'] = "Precio FOB de la compra ".$noteCode." del ".$date; 
							$arrayFobPrices[$i]['date'] = $date;
						}
						if($contCif === 0){	
							$arrayCifPrices[$i]['inv_item_id'] = $arrayItemsDetails[$i]['inv_item_id'];
							$arrayCifPrices[$i]['inv_price_type_id'] = 8;//or better relate by name CIF
							$arrayCifPrices[$i]['ex_price'] = $cif;
							$arrayCifPrices[$i]['price'] = $cif * $exRate;
							$arrayCifPrices[$i]['description'] = "Precio CIF prorrateado de la compra ".$noteCode." del ".$date; 
							$arrayCifPrices[$i]['date'] = $date;
						}	
					}
				}
//				debug($arrayFobPrices);
//				debug($arrayCifPrices);
//					if($validation['error'] == 0){
						
							$res = $this->PurPurchase->saveMovement($dataPurchase, $dataPurchaseDetail, $dataMovement, $dataMovementDetail, $dataMovementHeadsUpd, $OPERATION, $ACTION, $STATE, $dataPayDetail, $dataCostDetail, $arrayFobPrices, $arrayCifPrices);
							
//							if ($ACTION == 'save_invoice' && $STATE == 'PINVOICE_APPROVED'){
//									$this->loadModel('InvPrice');
//									$this->InvPrice->saveAll($arrayCifPrices);
//									$this->InvPrice->saveAll($arrayFobPrices);
//							}
//							if ($ACTION == 'save_order'){
//								$res2 = $this->PurPurchase->saveMovement($dataMovement2, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, null, null);
//								if(($OPERATION3 != 'DEFAULT')){
//									//used to insert/update type 1 detail movements 
//									//used to delete movement details type 1
////									echo "ini3";
//									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
////									echo "fin3";
//								}
////								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
////									//used to insert/update type 2 detail movements									
////									//used to delete movement details type 2
//////									echo "ini4";
////									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
//////									echo "fin4";
////								}	
//								if($arrayMovement5 <> null){
//									//used to update movements head
////									echo "ini5";
//									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
////									echo "fin5";
//								}
//								if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null)/*|| ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)*/) ){
////									echo "ini6";
//									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null, null, null);
////									echo "fin6";
//								}
//								
//							}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY' && $OPERATION != 'ADD_COST' && $OPERATION != 'EDIT_COST' && $OPERATION != 'DELETE_COST') {
//								if(($OPERATION3 != 'DEFAULT')){
//									//used to insert/update type 1 detail movements 
//									//used to delete movement details type 1
////									echo "ini3";
//									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
////									echo "fin3";
//								}
////								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
////									//used to insert/update type 2 detail movements									
////									//used to delete movement details type 2
//////									echo "ini4";
////									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
//////									echo "fin4";
////								}	
//								if($arrayMovement5 <> null){
//									//used to update movements head
//									//LO QUE ENTRE AQUI SOBREESCRIBE LA CABECERA DE $dataMovement3 y $dataMovement4
////									echo "ini5";
//									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
////									echo "fin5";
//								}
////								if((($OPERATION3 == 'DELETE' || $OPERATION4 == 'DELETE') && $arrayMovement6 <> null)){
////									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null);
////								}
//							}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'ADD_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_COST') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_COST')){
//								if($arrayMovement5 <> null){
//									//used to update movements head
//									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
//								}
//							}
//						if(($res <> 'error')/*||($res2 <> 'error')*/){
//							$movementIdSaved = $res;	//sal_sales NOTE id
////							if ($ACTION == 'save_order'){
////								$movementIdSaved2 = $res2;	//sal_sales INVOICE id
////							}
//							$strItemsStockDestination = '';
//							echo $STATE.'|'.$movementIdSaved.'|'.$purchaseOrderDocCode.'|'.$purchaseCode.'|'.$strItemsStock.$strItemsStockDestination;
//						}else{
//							echo 'ERROR|onSaving';
//						}
							
						switch ($res[0]) {
							case 'SUCCESS':
								echo $res[1].'|'.$purchaseOrderDocCode.'|'.$purchaseCode;
								break;
							case 'ERROR':
								echo 'ERROR|onSaving';
								break;
						}	
							
//					}else{
//							echo 'VALIDATION|'.$validation['itemsStocks'].$strItemsStock;
//					}

				/////////////////////END - SAVE////////////////////////////////	
			}else{
				echo 'ERROR|onGeneratingParameters';
			}
			////////////////////////////////////////////END-CORE SAVE////////////////////////////////////////////////////////
		}
	}
	
	public function ajax_logic_delete(){
		if($this->RequestHandler->isAjax()){
			$purchaseId = $this->request->data['purchaseId'];
			$type = $this->request->data['type'];	
			$genCode = $this->request->data['genCode'];
				if($this->PurPurchase->updateAll(array('PurPurchase.lc_state'=>"'$type'", 'PurPurchase.lc_transaction'=>"'MODIFY'"), array('PurPurchase.id'=>$purchaseId)) 
						){
					echo 'success';
				}
				if($type === 'PINVOICE_LOGIC_DELETED'){
					$this->loadModel('InvMovement');
					$arrayMovement5 = $this->InvMovement->find('all', array(
						'fields'=>array(
							'InvMovement.id'
//							,'InvMovement.date'
//							,'InvMovement.description'
							,'InvMovement.inv_warehouse_id'
							),
						'conditions'=>array(
								'InvMovement.document_code'=>$genCode
							)
						,'order' => array('InvMovement.id' => 'ASC')
						,'recursive'=>0
					));
					if($arrayMovement5 <> null){
						for($i=0;$i<count($arrayMovement5);$i++){
							$arrayMovement5[$i]['InvMovement']['lc_state'] = 'DRAFT';
//							$arrayMovement5[$i]['InvMovement']['code'] = 'NO'; //not sure to put this
						}
					}
					if($arrayMovement5 <> null){
						$dataMovement5 = $arrayMovement5;
					}
					if($arrayMovement5 <> null){
						$res5 = $this->InvMovement->saveMovement($dataMovement5, null,'UPDATEHEAD', null, null, null);
					}
				}
		}
	}
	
	//////////////////////////////////////////// END - AJAX /////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - PRIVATE ///////////////////////////////////////////////
	
	private function _get_doc_id($purchaseId, $movementCode, $type, $warehouseId){
		if ($purchaseId <> null) {
			$invoiceId = $this->PurPurchase->find('list', array(
				'fields'=>array('PurPurchase.id'),
				'conditions'=>array(
					'PurPurchase.code'=>$movementCode,
					"PurPurchase.id !="=>$purchaseId
					)
			));
			$docId = key($invoiceId);
		}else{
			$this->loadModel('InvMovement');
			$movementId = $this->InvMovement->find('list', array(
				'fields'=>array('InvMovement.id'),
				'conditions'=>array(
					'InvMovement.document_code'=>$movementCode,
					'InvMovement.type'=>$type,
					'InvMovement.inv_warehouse_id'=>$warehouseId,
					)
			));
			$docId = key($movementId);
		}
		return $docId;
	}
	
	private function _get_stocks($items, $warehouse, $limitDate = '', $dateOperator = '<='){
		$this->loadModel('InvMovement');
		$this->InvMovement->InvMovementDetail->unbindModel(array('belongsTo' => array('InvItem')));
		$this->InvMovement->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		$dateRanges = array();
		if($limitDate <> ''){
			$dateRanges = array('InvMovement.date '.$dateOperator => $limitDate);
		}
		
		$movements = $this->InvMovement->InvMovementDetail->find('all', array(
			'fields'=>array(
				"InvMovementDetail.inv_item_id", 
				"(SUM(CASE WHEN \"InvMovementType\".\"status\" = 'entrada' AND \"InvMovement\".\"lc_state\" = 'APPROVED' THEN \"InvMovementDetail\".\"quantity\" ELSE 0 END))-
				(SUM(CASE WHEN \"InvMovementType\".\"status\" = 'salida' AND \"InvMovement\".\"lc_state\" = 'APPROVED' THEN \"InvMovementDetail\".\"quantity\" ELSE 0 END)) AS stock"
				),
			'conditions'=>array(
				'InvMovement.inv_warehouse_id'=>$warehouse,
				'InvMovementDetail.inv_item_id'=>$items,
				$dateRanges
				),
			'group'=>array('InvMovementDetail.inv_item_id'),
			'order'=>array('InvMovementDetail.inv_item_id')
		));
		//the array format is like this:
		/*
		array(
			(int) 0 => array(
				'InvMovementDetail' => array(
					'inv_item_id' => (int) 9
				),
				(int) 0 => array(
					'stock' => '20'
				)
			),...etc,etc
		)	*/
		return $movements;
	}
	
	private function _find_item_stock($stocks, $item){
		foreach($stocks as $stock){//find required stock inside stocks array 
			if($item == $stock['InvMovementDetail']['inv_item_id']){
				return $stock[0]['stock'];
			}
		}
		//this fixes in case there isn't any item inside movement_details yet with a determinated warehouse
		return 0;
	}
	
	private function _generate_code($keyword){
		$period = $this->Session->read('Period.name');
		if($period <> ''){
			try{
				$movements = $this->PurPurchase->find('count', array(
					'conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED','ORDER_LOGIC_DELETED'))
				));
			}catch(Exception $e){
				return 'error';
			}
		}else{
			return 'error';
		}
		
		$quantity = $movements + 1; 
		$code = $keyword.'-'.$period.'-'.$quantity;
		return $code;
	}
	
	private function _generate_doc_code($keyword){
		$period = $this->Session->read('Period.name');
		if($period <> ''){
			try{
				if ($keyword == 'ORD'){
					$movements = $this->PurPurchase->find('count', array(
						'conditions'=>array('PurPurchase.lc_state'=>array('ORDER_PENDANT','ORDER_APPROVED','ORDER_CANCELLED','ORDER_LOGIC_DELETED'))
					)); 
				}elseif ($keyword == 'CFA'){
					$movements = $this->PurPurchase->find('count', array(
						'conditions'=>array('PurPurchase.lc_state'=>array('PINVOICE_PENDANT','PINVOICE_APPROVED','PINVOICE_CANCELLED','PINVOICE_LOGIC_DELETED'))
					));
				}
			}catch(Exception $e){
				return 'error';
			}
		}else{
			return 'error';
		}
		
		$quantity = $movements + 1; 
		$docCode = $keyword.'-'.$period.'-'.$quantity;
		return $docCode;
	}
	
	private function _generate_movement_code($keyword, $type){
		$this->loadModel('InvMovement');
		$period = $this->Session->read('Period.name');
		$movementType = '';
		if($keyword == 'ENT'){$movementType = 'entrada';}
		if($keyword == 'SAL'){$movementType = 'salida';}
		if($period <> ''){
			try{
				$movements = $this->InvMovement->find('count', array(
					'conditions'=>array(
						'InvMovementType.status'=>$movementType
						,'InvMovement.code !='=>'NO'
						)
				)); 
			}catch(Exception $e){
				return 'error';
			}
			
//			$movementss = $this->InvMovement->find('all', array(
//					'conditions'=>array('InvMovementType.status'=>$movementType)
//				)); 
//		echo '------------------------------------------------ <br>';		
//		echo '---movements count--- <br>';	
//		debug($movements);
//		echo '---movements --- <br>';	
//		debug($movementss);
//		echo '----movement type------- <br>';
//		debug($movementType);
//		echo '------------------------------------------------ <br>';
			
		}else{
			return 'error';
		}
		if($type == 'inc'){
			static $inc = 0;
			$quantity = $movements + 1 + $inc;
			$inc++;
		}else{
			$quantity = $movements + 1; 
		}
		$code = $keyword.'-'.$period.'-'.$quantity;
		return $code;
	}
	
	public function _get_movements_details($idMovement){
		$movementDetails = $this->PurPurchase->PurDetail->find('all', array(
			'conditions'=>array(
				'PurDetail.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('InvItem.name', 'InvItem.code', 'PurDetail.ex_fob_price', 'PurDetail.quantity','PurDetail.inv_supplier_id', 'InvItem.id', 'InvSupplier.name','InvSupplier.id',)
			));
		
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			// gets the first price in the list of the item prices
//			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//					'fields'=>array('InvPrice.price'),
//					'order' => array('InvPrice.date_created' => 'desc'),
//					'conditions'=>array(
//						'InvPrice.inv_item_id'=>$value['InvItem']['id']
//						)
//				));
				//$price = $priceDirty['InvPrice']['price'];
			
			$formatedMovementDetails[$key] = array(
				'itemId'=>$value['InvItem']['id'],
				'item'=>'[ '. $value['InvItem']['code'].' ] '.$value['InvItem']['name'],
				'exFobPrice'=>$value['PurDetail']['ex_fob_price'],//llamar precio
				'cantidad'=>$value['PurDetail']['quantity'],//llamar cantidad
				'supplierId'=>$value['InvSupplier']['id'],
				'supplier'=>$value['InvSupplier']['name'],//llamar almacen
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_costs_details($idMovement){
		$movementDetails = $this->PurPurchase->PurPrice->find('all', array(
			'conditions'=>array(
				'PurPrice.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('InvPriceType.name', 'PurPrice.ex_amount', 'InvPriceType.id'/*, 'PurPurchase.inv_supplier_id','InvPrice.price'*/)
			));
		
		$formatedMovementDetails = array();
		foreach ($movementDetails as $key => $value) {
			// gets the first price in the list of the item prices
//			$priceDirty = $this->PurPurchase->PurDetail->InvItem->InvPrice->find('first', array(
//					'fields'=>array('InvPrice.price'),
//					'order' => array('InvPrice.date_created' => 'desc'),
//					'conditions'=>array(
//						'InvPrice.inv_item_id'=>$value['InvItem']['id']
//						)
//				));
				//$price = $priceDirty['InvPrice']['price'];
			
			$formatedMovementDetails[$key] = array(
				'costId'=>$value['InvPriceType']['id'],
				'costCodeName'=>$value['InvPriceType']['name'],
				'costExAmount'=>$value['PurPrice']['ex_amount']//llamar precio
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_pays_details($idMovement){
		$paymentDetails = $this->PurPurchase->PurPayment->find('all', array(
			'conditions'=>array(
				'PurPayment.pur_purchase_id'=>$idMovement
				),
			'fields'=>array('PurPayment.date', 'PurPayment.amount', 'PurPayment.description')
			));
		
		$formatedPaymentDetails = array();
		foreach ($paymentDetails as $key => $value) {
			$formatedPaymentDetails[$key] = array(
				'dateId'=>$value['PurPayment']['date'],//llamar precio
				//'payDate'=>strftime("%A, %d de %B de %Y", strtotime($value['SalPayment']['date'])),
				'payDate'=>strftime("%d/%m/%Y", strtotime($value['PurPayment']['date'])),
				'payAmount'=>$value['PurPayment']['amount'],//llamar cantidad
				'payDescription'=>$value['PurPayment']['description']
				);
		}
//debug($formatedPaymentDetails);		strftime("%A, %d de %B de %Y", $value['SalPayment']['date'])
		return $formatedPaymentDetails;
	}

	//////////////////////////////////////////// END - PRIVATE /////////////////////////////////////////////////
	
	//*******************************************************************************************************//
	/////////////////////////////////////////// END - CLASS ///////////////////////////////////////////////
	//*******************************************************************************************************//
}

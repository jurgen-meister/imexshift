<?php
App::uses('AppController', 'Controller');
/**
 * SalSales Controller
 *
 * @property SalSale $SalSale
 */
class SalSalesController extends AppController {

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
	//public $components = array('Session')
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
		
		$this->loadModel("InvItem");
		
		$itemsClean = $this->InvItem->find("list", array('order'=>array('InvItem.code')));
		$items[0]="TODOS";
		foreach ($itemsClean as $key => $value) {
			$items[$key] = $value;
		}
		
		$this->set(compact("years", "items"));
		//debug($this->_get_bars_sales_and_time("2013", "0"));
	}
	
	public function ajax_get_graphics_data(){
		if($this->RequestHandler->isAjax()){
			$year = $this->request->data['year'];
			$currency = $this->request->data['currency'];
			$item = $this->request->data['item'];
			$string = $this->_get_bars_sales_and_time($year, $item, $currency);
			echo $string;
		}
//		$string .= '30|54|12|114|64|100|98|80|10|50|169|222';
	}
	
	private function _get_bars_sales_and_time($year, $item, $currency){
		$conditionItem = null;
		$dataString = "";
		
		if($item > 0){
			$conditionItem = array("SalDetail.inv_item_id" => $item);
		}
		
		$currencyType = "price";
		if($currency == "dolares"){
			$currencyType = "ex_price";
		}
		
		//*****************************************************************************//
		$data = $this->SalSale->SalDetail->find('all', array(
			"fields"=>array(
				"to_char(\"SalSale\".\"date\",'mm') AS month",
				'SUM("SalDetail"."quantity" * (SELECT '.$currencyType.'  FROM inv_prices where inv_item_id = "SalDetail"."inv_item_id" AND date <= "SalSale"."date" AND inv_price_type_id=9 order by date DESC, date_created DESC LIMIT 1))'
			),
			'group'=>array("to_char(SalSale.date,'mm')"),
			"conditions"=>array(
				"to_char(SalSale.date,'YYYY')"=>$year,
				"SalSale.lc_state"=>"SINVOICE_APPROVED",
				$conditionItem
			)
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
			if(isset($this->request->data['SalSale']['doc_code']) && $this->request->data['SalSale']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['SalSale']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['SalSale']['note_code']) && $this->request->data['SalSale']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['SalSale']['note_code']));
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
			$filters['SalSale.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$doc_code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['note_code'])){
			$filters['SalSale.note_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$note_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->SalSale->bindModel(array('hasOne'=>array('SalCustomer'=>array('foreignKey'=>false,'conditions'=> array('SalEmployee.sal_customer_id = SalCustomer.id')))));
		
		$this->paginate = array(
			"conditions"=>array(
				"SalSale.lc_state !="=>"NOTE_LOGIC_DELETED",
				'SalSale.lc_state LIKE'=> '%NOTE%',
				"to_char(SalSale.date,'YYYY')"=> $period,
			//	"InvMovementType.status"=> "entrada",
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("SalSale.id", "SalSale.code", "SalSale.doc_code", "SalSale.date", "SalSale.note_code", "SalSale.sal_employee_id", "SalEmployee.name", "SalSale.lc_state", "SalCustomer.name"),
			"order"=> array("SalSale.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('salSales', $this->paginate('SalSale'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		
		
		
//		$this->paginate = array(
//			'conditions' => array(
//				'SalSale.lc_state !='=>'NOTE_LOGIC_DELETED'
//				,'SalSale.lc_state LIKE'=> '%ORDER%'
//			),
//			'order' => array('SalSale.id' => 'desc'),
//			'limit' => 15
//		);
//		$this->SalSale->recursive = 0;
//		$this->set('salSales', $this->paginate());
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
			if(isset($this->request->data['SalSale']['doc_code']) && $this->request->data['SalSale']['doc_code']){
				$parameters['doc_code'] = trim(strip_tags($this->request->data['SalSale']['doc_code']));
			}else{
				$empty++;
			}
			if(isset($this->request->data['SalSale']['note_code']) && $this->request->data['SalSale']['note_code']){
				$parameters['note_code'] = trim(strip_tags($this->request->data['SalSale']['note_code']));
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
			$filters['SalSale.doc_code LIKE'] = '%'.strtoupper($this->passedArgs['doc_code']).'%';
			$doc_code = $this->passedArgs['doc_code'];
		}
		if(isset($this->passedArgs['note_code'])){
			$filters['SalSale.note_code LIKE'] = '%'.strtoupper($this->passedArgs['note_code']).'%';
			$note_code = $this->passedArgs['note_code'];
		}
		////////////////////////////END - SETTING URL FILTERS//////////////////////////////////////
		
		////////////////////////////START - SETTING PAGINATING VARIABLES//////////////////////////////////////
		$this->SalSale->bindModel(array('hasOne'=>array('SalCustomer'=>array('foreignKey'=>false,'conditions'=> array('SalEmployee.sal_customer_id = SalCustomer.id')))));
		
		$this->paginate = array(
			"conditions"=>array(
				"SalSale.lc_state !="=>"SINVOICE_LOGIC_DELETED",
				'SalSale.lc_state LIKE'=> '%SINVOICE%',
				"to_char(SalSale.date,'YYYY')"=> $period,
				$filters
			 ),
			"recursive"=>0,
			"fields"=>array("SalSale.id", "SalSale.code", "SalSale.doc_code", "SalSale.date", "SalSale.note_code", "SalSale.sal_employee_id", "SalEmployee.name", "SalSale.lc_state", "SalCustomer.name"),
			"order"=> array("SalSale.id"=>"desc"),
			"limit" => 15,
		);
		////////////////////////////END - SETTING PAGINATING VARIABLES//////////////////////////////////////
		
		////////////////////////START - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
		$this->set('salSales', $this->paginate('SalSale'));
		$this->set('doc_code', $doc_code);
		$this->set('note_code', $note_code);
		////////////////////////END - SETTING PAGINATE AND OTHER VARIABLES TO THE VIEW//////////////////
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
		
		
		$this->loadModel('AdmUser');
		
		$salAdmUsers = $this->AdmUser->AdmProfile->find('list');
		//array_unshift($salAdmUsers,"Sin Vendedor"); //REVISAR ESTO ARRUINA EL CODIGO Q BOTA EL DROPDOWN
		$salCustomers = $this->SalSale->SalEmployee->SalCustomer->find('list'/*, array('conditions'=>array('SalCustomer.location'=>'COCHABAMBA'))*/);
		$customer = key($salCustomers);
		$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
	//	$taxNumber = key($salCustomers);
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));
			
		$this->SalSale->recursive = -1;
		$this->request->data = $this->SalSale->read(null, $id);
	//	$date='';
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
//		debug($xxxRate);
		
		
		$exRate = $xxxRate['AdmExchangeRate']['value'];	//esto tiene q llamar al cambio del dia
		////////////////////////////////////////////////////////////
//		debug($exRate);
		$genericCode ='';
		//debug($this->request->data);
	//	debug($salAdmUsers);
		$salDetails = array();
		$documentState = '';
		$customerId = '';
		$admUserId = '';
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['SalSale']['date']));
			$salDetails = $this->_get_movements_details($id);
			$documentState =$this->request->data['SalSale']['lc_state'];
			$genericCode = $this->request->data['SalSale']['code'];
			
			$employeeId = $this->request->data['SalSale']['sal_employee_id'];
			$customerId = $this->SalSale->SalEmployee->find('list', array('fields'=>array('SalEmployee.sal_customer_id'),'conditions'=>array('SalEmployee.id'=>$employeeId)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customerId)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customerId)));		
			
			$admProfileId = $this->request->data['SalSale']['salesman_id'];
			$admUserId = $this->AdmUser->AdmProfile->find('list', array('fields'=>array('AdmProfile.id'),'conditions'=>array('AdmProfile.adm_user_id'=>$admProfileId)));
//		debug($admProfileId);
//			debug($admUserId);
			$exRate = $this->request->data['SalSale']['ex_rate'];
		}
		$this->set(compact('salCustomers','customerId', 'salTaxNumbers', 'salEmployees','employeeId', 'salAdmUsers', 'admUserId','id', 'date', 'salDetails', 'documentState', 'genericCode', 'exRate'));
		//debug($admProfileId);
	//	debug($salAdmUsers);
	}
	
	public function save_invoice(){
		$id = '';
		if(isset($this->passedArgs['id'])){
			$id = $this->passedArgs['id'];
		}
		$this->loadModel('AdmUser');
		
		$salAdmUsers = $this->AdmUser->AdmProfile->find('list');
	//	debug($salAdmUsers);
		//array_unshift($salAdmUsers,"Sin Vendedor");//REVISAR ESTO ARRUINA EL CODIGO Q BOTA EL DROPDOWN
	
		$salCustomers = $this->SalSale->SalEmployee->SalCustomer->find('list');
		$customer = key($salCustomers);
		$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
	//	$taxNumber = key($salCustomers);
		$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));
	
				
		$this->SalSale->recursive = -1;
		$this->request->data = $this->SalSale->read(null, $id);
		$date='';
		$genericCode ='';
		$originCode = '';
		$customerId = '';
		$admUserId = '';
		//debug($this->request->data);
		$salDetails = array();
		$salPayments = array();
//		$purPrices = array();
		$documentState = '';
		$exRate = '8.00';	//esto tiene q llamar al cambio del dia
		if($id <> null){
			$date = date("d/m/Y", strtotime($this->request->data['SalSale']['date']));
			$salDetails = $this->_get_movements_details($id);
			$salPayments = $this->_get_pays_details($id);
			$documentState =$this->request->data['SalSale']['lc_state'];
			$genericCode = $this->request->data['SalSale']['code'];
			//buscar el codigo del documento origen
			$originDocCode = $this->SalSale->find('first', array(
				'fields'=>array('SalSale.doc_code'),
				'conditions'=>array(
					'SalSale.code'=>$genericCode,
					'SalSale.lc_state LIKE'=> '%NOTE%'
					)
			));
			$originCode = $originDocCode['SalSale']['doc_code'];
			$employeeId = $this->request->data['SalSale']['sal_employee_id'];
			$customerId = $this->SalSale->SalEmployee->find('list', array('fields'=>array('SalEmployee.sal_customer_id'),'conditions'=>array('SalEmployee.id'=>$employeeId)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customerId)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customerId)));		
			
			$admProfileId = $this->request->data['SalSale']['salesman_id'];
			$admUserId = $this->AdmUser->AdmProfile->find('list', array('fields'=>array('AdmProfile.id'),'conditions'=>array('AdmProfile.adm_user_id'=>$admProfileId)));
			$exRate = $this->request->data['SalSale']['ex_rate'];
		}
		
			
		$this->set(compact('salCustomers','customerId', 'salTaxNumbers', 'salEmployees','employeeId', 'salAdmUsers', 'admUserId','id', 'date', 'salDetails', 'salPayments', 'documentState', 'genericCode', 'originCode', 'exRate'));
//debug($this->request->data);
	}
	
	//////////////////////////////////////////// END - SAVE /////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - AJAX ///////////////////////////////////////////////
	
	public function ajax_initiate_modal_add_item_in(){
		if($this->RequestHandler->isAjax()){
						
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouseItemsAlreadySaved = $this->request->data['warehouseItemsAlreadySaved'];
			
			$invWarehouses = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->InvWarehouse->find('list');
			
			$warehouse = key($invWarehouses);
			
			$itemsAlreadySavedInWarehouse = [];
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($warehouseItemsAlreadySaved[$i] == $warehouse){
					$itemsAlreadySavedInWarehouse[] = $itemsAlreadySaved[$i];
				}	
			}
			
			$items = $this->SalSale->SalDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySavedInWarehouse)
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
			
			$firstItemListed = key($items);
			
			//$stock = $this->_find_stock($firstItemListed, $warehouse);
			$stocks = $this->_get_stocks($firstItemListed, $warehouse);
			$stock = $this->_find_item_stock($stocks, $firstItemListed);
			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
				'fields'=>array('InvPrice.price'),
				'order' => array('InvPrice.date' => 'desc'),
				'conditions'=>array(
					'InvPrice.inv_item_id'=>$firstItemListed
					,'InvPrice.inv_price_type_id'=>9
					)
			));
//			debug($priceDirty);
			if($priceDirty == array() || $priceDirty['InvPrice']['price'] == null){
				$price = 0;
			}  else {

				$price = $priceDirty['InvPrice']['price'];
			}

				$this->set(compact('items', 'price', 'invWarehouses', 'stock', 'warehouse'));
			}
	}
	
	public function ajax_update_stock_modal(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$item
				)
			));
			if($priceDirty==array()){
			$price = 0;
			}else{
			
			$price = $priceDirty['InvPrice']['price'];
			}
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$this->set(compact('price'));
		}
	}
	
	
	public function ajax_update_stock_modal_1(){
		if($this->RequestHandler->isAjax()){
			$item = $this->request->data['item'];
			$warehouse = $this->request->data['warehouse'];
			
			$stock = $this->_find_stock($item, $warehouse);			
			
			$this->set(compact('stock'));
		}
	}
	
	public function ajax_list_controllers_inside(){
		if($this->RequestHandler->isAjax()){
			$customer = $this->request->data['customer']; //???????????????????
		//	print_r( $customer);
		//	$admControllers = $this->AdmMenu->AdmAction->AdmController->find('list', array('conditions'=>array('AdmController.adm_module_id'=>$module)));
			$salEmployees = $this->SalSale->SalEmployee->find('list', array('conditions'=>array('SalEmployee.sal_customer_id'=>$customer)));
			$salTaxNumbers = $this->SalSale->SalTaxNumber->find('list', array('conditions'=>array('SalTaxNumber.sal_customer_id'=>$customer)));

		//	print_r( $salEmployees);
		//	$controller = key($admControllers);
		//	$employee = key($salEmployees);
			//$admActions = $this->AdmMenu->AdmAction->find('list', array('conditions'=>array('AdmAction.adm_controller_id'=>$controller)));
		//	$admActions = $this->_list_action_inside($controller);
			$this->set(compact('salEmployees', 'salTaxNumbers'/*'admControllers','admActions'*/));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}	
	
	public function ajax_update_items_modal(){
		if($this->RequestHandler->isAjax()){
			$itemsAlreadySaved = $this->request->data['itemsAlreadySaved'];
			$warehouseItemsAlreadySaved = $this->request->data['warehouseItemsAlreadySaved'];
			$warehouse = $this->request->data['warehouse'];
			
			$itemsAlreadySavedInWarehouse = [];
			for($i=0; $i<count($itemsAlreadySaved); $i++){
				if($warehouseItemsAlreadySaved[$i] == $warehouse){
					$itemsAlreadySavedInWarehouse[] = $itemsAlreadySaved[$i];
				}	
			}
			
			$items = $this->SalSale->SalDetail->InvItem->find('list', array(
				'conditions'=>array(
					'NOT'=>array('InvItem.id'=>$itemsAlreadySavedInWarehouse)
				),
				'recursive'=>-1,
				'order'=>array('InvItem.code')
			));
			
			$item = key($items);
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			$priceDirty = $this->SalSale->SalDetail->InvItem->InvPrice->find('first', array(
			'fields'=>array('InvPrice.price'),
			'order' => array('InvPrice.date_created' => 'desc'),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$item
				)
			));
			if($priceDirty==array()){
			$price = 0;
			}  else {
			
			$price = $priceDirty['InvPrice']['price'];
			}
			//////////////////////CAMBIAR POR EL ALGORITMO QUE SACA EL PRECIO PRORRATEADO////////////////
			//$stock = $this->_find_stock($item, $warehouse);
			$stocks = $this->_get_stocks($item, $warehouse);
			$stock = $this->_find_item_stock($stocks, $item);
			$this->set(compact('items', 'price', 'stock'));
		}
	}
	
	public function ajax_save_movement(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////START - RECIEVE AJAX////////////////////////////////////////////////////////
			//For making algorithm
			$ACTION = $this->request->data['ACTION'];
			$OPERATION= $this->request->data['OPERATION'];
			$STATE = $this->request->data['STATE'];//also for Movement
			$OPERATION3 = $OPERATION;
			$OPERATION4 = $OPERATION;
			//Sale
			$purchaseId = $this->request->data['purchaseId'];
			$movementDocCode = $this->request->data['movementDocCode'];
			$movementCode = $this->request->data['movementCode'];
			$noteCode = $this->request->data['noteCode'];
			$date = $this->request->data['date'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			///////////////////////////////////////////////////////
			$this->loadModel('AdmUser');
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			///////////////////////////////////////////////////////
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			//Sale Details
			$warehouseId = $this->request->data['warehouseId'];
			$itemId = $this->request->data['itemId'];
			$salePrice = $this->request->data['salePrice'];
			$quantity = $this->request->data['quantity'];
//			$cifPrice =  $this->_get_price($itemId, $date, 'CIF', 'bs');//$this->request->data['cifPrice'];
//			$exCifPrice = $this->_get_price($itemId, $date, 'CIF', 'dolar');//$this->request->data['exCifPrice'];
			//For prices IF DETAILS ARE PASSED / IF ACTION ADD OR EDIT
//			$exFobPrice =  $this->_get_price($itemId, $date, 'FOB', 'dolar');
//			$fobPrice =  $exFobPrice * $exRate;//$this->_get_price($itemId, $date, 'FOB', 'bs');
			$exSalePrice = $salePrice / $exRate;
			if ($ACTION == 'save_invoice' && $STATE == 'SINVOICE_APPROVED'){
				$arrayItemsDetails = $this->request->data['arrayItemsDetails'];	
			}
			if (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
//				$dateId = $this->request->data['dateId'];
				$payDate = $this->request->data['payDate'];
				$payAmount = $this->request->data['payAmount'];
				$payDescription = $this->request->data['payDescription'];
			}
			//For validate before approve OUT or cancelled IN
			$arrayForValidate = array();
			if(isset($this->request->data['arrayForValidate'])){$arrayForValidate = $this->request->data['arrayForValidate'];}
			//Internal variables
			$error=0;
			$movementDocCode3 = '';
			$movementDocCode4 = '';
			////////////////////////////////////////////END - RECIEVE AJAX////////////////////////////////////////////////////////
			
			////////////////////////////////////////////////START - SET DATA/////////////////////////////////////////////////////
			$arrayMovement['note_code']=$noteCode;
			$arrayMovement['date']=$date;
			$arrayMovement['sal_employee_id']=$employee;
			$arrayMovement['sal_tax_number_id']=$taxNumber;
			$arrayMovement['salesman_id']=$salesman;
			$arrayMovement['description']=$description;
			$arrayMovement['ex_rate']=$exRate;
			$arrayMovement['lc_state']=$STATE;
			if ($ACTION == 'save_order'){
				//header for invoice
				$arrayMovement2['note_code']=$noteCode;
				$arrayMovement2['date']=$date;
				$arrayMovement2['sal_employee_id']=$employee;
				$arrayMovement2['sal_tax_number_id']=$taxNumber;
				$arrayMovement2['salesman_id']=$salesman;
				$arrayMovement2['description']=$description;
				$arrayMovement2['ex_rate']=$exRate;
				//header for movement
				$arrayMovement3['date']=$date;
				$arrayMovement3['inv_warehouse_id']=$warehouseId;
				$arrayMovement3['inv_movement_type_id']=2;
				$arrayMovement3['description']=$description;
				
				if ($STATE == 'NOTE_APPROVED') {
					$arrayMovement2['lc_state']='SINVOICE_PENDANT';
				}elseif ($STATE == 'NOTE_PENDANT') {
					$arrayMovement2['lc_state']='DRAFT';
					$arrayMovement3['lc_state']='DRAFT';
					$arrayMovement4['lc_state']='DRAFT';
//					debug($arrayMovement3['lc_state']);
				}
			}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')){
				$arrayPayDetails = array('sal_payment_type_id'=>1, 
										'date'=>$payDate,
										//'description'=>"'$payDescription'",
										'description'=>$payDescription,
										'amount'=>$payAmount, 'ex_amount'=>($payAmount / $exRate)
										);
			}elseif ($ACTION == 'save_invoice') {
				//header for movement
				$arrayMovement3['date']=$date;
				$arrayMovement3['inv_warehouse_id']=$warehouseId;
				$arrayMovement3['inv_movement_type_id']=2;
				$arrayMovement3['description']=$description;
				if ($STATE == 'SINVOICE_PENDANT') {
					$arrayMovement3['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovement5
					$arrayMovement4['lc_state']='PENDANT';//ESTO ESTA SOBREESCRITO POR LO Q DIGA $arrayMovement5
				}
			}			
			$arrayMovementDetails = array('inv_warehouse_id'=>$warehouseId, 
										'inv_item_id'=>$itemId,
										'sale_price'=>$salePrice, 'ex_sale_price'=>$exSalePrice,
										'quantity'=>$quantity, 
										/*'cif_price'=>$cifPrice, 'ex_cif_price'=>$exCifPrice, 
										'fob_price'=>$fobPrice, 'ex_fob_price'=>$exFobPrice*/);
			if ($ACTION == 'save_order'){
				$stocks = $this->_get_stocks($itemId, $warehouseId);
				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement3['type']=1;

				$arrayMovement4['date']=$date;
				$arrayMovement4['inv_warehouse_id']=$warehouseId;
				$arrayMovement4['inv_movement_type_id']=2;
				$arrayMovement4['description']=$description;
				$arrayMovement4['type']=2;
				$surplus = $quantity - $stock;
				if($quantity > $stock){
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
					if($stock !== 0){
						$OPERATION4 = 'ADD';
					}
				}else{
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
				}	
				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
				$stocks = $this->_get_stocks($itemId, $warehouseId);
				$stock = $this->_find_item_stock($stocks, $itemId);
				$arrayMovement3['type']=1;
				$arrayMovement4['date']=$date;
				$arrayMovement4['inv_warehouse_id']=$warehouseId;
				$arrayMovement4['inv_movement_type_id']=2;
				$arrayMovement4['description']=$description;
				$arrayMovement4['type']=2;
				$surplus = $quantity - $stock;
				if($quantity > $stock){
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$stock);
					if($stock !== 0){
						$OPERATION4 = 'ADD';
					}	
				}else{
					$arrayMovementDetails3 = array('inv_item_id'=>$itemId, 'quantity'=>$quantity);
				}	
				$arrayMovementDetails4 = array('inv_item_id'=>$itemId, 'quantity'=>$surplus);
			}
			//INSERT OR UPDATE
			if($purchaseId == ''){//INSERT
				switch ($ACTION) {
					case 'save_order':
						//SALES NOTE
						$movementCode = $this->_generate_code('VEN');
						$movementDocCode = $this->_generate_doc_code('NOT');
						$arrayMovement['code'] = $movementCode;
						$arrayMovement['doc_code'] = $movementDocCode;
						//SALES INVOICE
						$movementDocCode2 = 'NO';
						$arrayMovement2['code'] = $movementCode;
						$arrayMovement2['doc_code'] = $movementDocCode2;
						//MOVEMENT type 1(hay stock)
						$arrayMovement3['document_code'] = $movementCode;
						$arrayMovement3['code'] = $movementDocCode2;
						//MOVEMENT type 2(NO hay stock)
						$arrayMovement4['document_code'] = $movementCode;
						$arrayMovement4['code'] = $movementDocCode2;
						break;
				}
				if($movementCode == 'error'){$error++;}
				if($movementDocCode == 'error'){$error++;}
				if($movementDocCode2 == 'error'){$error++;}
			}else{//UPDATE
				//sale note id
				$arrayMovement['id'] = $purchaseId;
				if ($ACTION == 'save_order'){
					//sale invoice id
					$arrayMovement2['id'] = $this->_get_doc_id($purchaseId, $movementCode, null, null);
					//movement id type 1(hay stock)
					$arrayMovement3['id'] = $this->_get_doc_id(null, $movementCode, 1, $warehouseId);
					if($arrayMovement3['id'] === null){
						$arrayMovement3['document_code'] = $movementCode;
						$arrayMovement3['code'] = 'NO';
					}
					//movement id type 2(NO hay stock)
					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, $warehouseId);
					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){
						$arrayMovement4['document_code'] = $movementCode;
						$arrayMovement4['code'] = 'NO';
					}
					if($quantity > $stock){//CHEKAR BIEN ESTO, CREO Q YA NO VA!!!
						$arrayMovement4['document_code'] = $movementCode;
						$arrayMovement4['code'] = 'NO';
					}
//					Para eliminar el detalle que ocupaba la HEAD type 2 					
					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
						$OPERATION4 = 'DELETE';
					}
					if ($STATE == 'NOTE_APPROVED') {
						//FOR INVOICE
						$movementDocCode2 = $this->_generate_doc_code('VFA');
						$arrayMovement2['doc_code'] = $movementDocCode2;
					}
				}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
					//movement id type 1(hay stock)
					$arrayMovement3['id'] = $this->_get_doc_id(null, $movementCode, 1, $warehouseId);
					if($arrayMovement3['id'] === null){//SI NO HAY EL DOCUMENTO (CON STOCK) SE CREA
						$arrayMovement3['document_code'] = $movementCode;
						$movementDocCode3 = $this->_generate_movement_code('SAL',null);
						$arrayMovement3['code'] = $movementDocCode3;//'NO';
					}
					//movement id type 2(NO hay stock)
					$arrayMovement4['id'] = $this->_get_doc_id(null, $movementCode, 2, $warehouseId);
					if(($arrayMovement4['id'] === null) && ($quantity > $stock)){//SI NO HAY EL DOCUMENTO (SIN STOCK), Y LA CANTIDAD SOBREPASA EL STOCK SE CREA
						$arrayMovement4['document_code'] = $movementCode;
						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
						$arrayMovement4['code'] = $movementDocCode4;//'NO';
					}
//					if($quantity > $stock){
//						$arrayMovement4['document_code'] = $movementCode;
//						$movementDocCode4 = $this->_generate_movement_code('SAL',null);
//						$arrayMovement4['code'] = $movementDocCode4;//'NO';
//					}
//					Para eliminar el detalle que ocupaba la HEAD type 2
					if(($arrayMovement4['id'] <> null) && ($quantity <= $stock)){
						$OPERATION4 = 'DELETE';
					}
				}
				if($movementDocCode3 == 'error'){$error++;}
				if($movementDocCode4 == 'error'){$error++;}
			}
			//-------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order--------------------------------
//			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE')){	
			$arrayMovement6 = null;	
			$rest3 = null;
			$rest4 = null;																		//VER SI ESTA V RESTRICCION NO INCLUYE OTRAS OPERACIONES MAS ??????????					
			if(($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $OPERATION4 == 'DELETE')||($ACTION == 'save_order' && $OPERATION3 == 'EDIT' && $OPERATION4 == 'DELETE')){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
				if (($arrayMovement3['id'] !== null && $arrayMovementDetails3['inv_item_id'] !== null && $OPERATION3 == 'DELETE') ){
					$rest3 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails3['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
							),
						'recursive'=>0
					));
				}
				if (($arrayMovement4['id'] !== null && $arrayMovementDetails4['inv_item_id'] !== null && $OPERATION4 == 'DELETE')){
					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
							),
						'recursive'=>0
					));
				}	
				if(($rest3 === 0) && ($rest4 === 0) && ($arrayMovement3['id'] !== null) && ($arrayMovement4['id'] !== null)){
					$arrayMovement6 = array(
						array('InvMovement.id' => array($arrayMovement3['id'],$arrayMovement4['id']))
					);
				}elseif(($rest3 === 0) && ($arrayMovement3['id'] !== null)){
					$arrayMovement6 = array(
						array('InvMovement.id' => $arrayMovement3['id'])
					);
				}elseif(($rest4 === 0) && ($arrayMovement4['id'] !== null)){
					$arrayMovement6 = array(
						 array('InvMovement.id' => $arrayMovement4['id'])
					);
				}
//				else{
//					$arrayMovement6 = null;
//				}
			}
			//---------------------------FOR DELETING HEAD ON MOVEMENTS RELATED ON save_order------------------------------
//			-------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice--------------------------------
//			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')){	
			$draftId3 = null;
			$draftId4 = null;																		//VER SI ESTA V RESTRICCION NO INCLUYE OTRAS OPERACIONES MAS ??????????			
			if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE' && $OPERATION4 == 'DELETE')||($ACTION == 'save_invoice' && $OPERATION3 == 'EDIT' && $OPERATION4 == 'DELETE')){//TOMANDO EN CUENTA QUE SIEMPRE QUE $OPERATION3 == 'DELETE' TAMBIEN $OPERATION4 == 'DELETE' Y VICEVERSA
				if (($arrayMovement3['id'] !== null && $arrayMovementDetails3['inv_item_id'] !== null  && $OPERATION3 == 'DELETE')){
					$rest3 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails3['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement3['id']
							),
						'recursive'=>0
					));
				}
				if (($arrayMovement4['id'] !== null && $arrayMovementDetails4['inv_item_id'] !== null && $OPERATION4 == 'DELETE')){
					$rest4 = $this->InvMovement->InvMovementDetail->find('count', array(
						'conditions'=>array(
							'NOT'=>array(
								'AND'=>array(
									'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
									,'InvMovementDetail.inv_item_id'=>$arrayMovementDetails4['inv_item_id']
									)
								)
							,'InvMovementDetail.inv_movement_id'=>$arrayMovement4['id']
							),
						'recursive'=>0
					));
				}	
				if(($rest3 === 0) && ($rest4 === 0) && ($arrayMovement3['id'] !== null) && ($arrayMovement4['id'] !== null)){
					$draftId3 = $arrayMovement3['id'];
					$draftId4 = $arrayMovement4['id'];
//					echo "<br>1<br>";
//					debug($draftId3);
//					debug($draftId4);
				}elseif(($rest3 === 0) && ($arrayMovement3['id'] !== null)){
					$draftId3 = $arrayMovement3['id'];
//					$draftId4 = null;
//					echo "<br>2<br>";
//					debug($draftId3);
				}elseif(($rest4 === 0) && ($arrayMovement4['id'] !== null)){
					$draftId4 = $arrayMovement4['id'];
//					$draftId3 = null;
//					echo "<br>3<br>";
//					debug($draftId4);
				}
//				else{
//					$draftId3 = null;
//					$draftId4 = null;
//				}
			}
//			---------------------------FOR UPDATING HEAD ON DELETED MOVEMENTS ON save_invoice------------------------------
			//*********************************************************MAKE AN IF WHEN $STATE == DEFAULT
			$this->loadModel('InvMovement');
			$arrayMovement5 = $this->InvMovement->find('all', array(
				'fields'=>array(
					'InvMovement.id'
//					,'InvMovement.date'
//					,'InvMovement.description'
//					,'InvMovement.lc_state'
//					,'InvMovement.inv_warehouse_id'
					),
				'conditions'=>array(
						'InvMovement.document_code'=>$movementCode
					)
				,'order' => array('InvMovement.id' => 'ASC')
				,'recursive'=>0
			));
			if(($arrayMovement5 <> null)&&($STATE == 'NOTE_CANCELLED')){
				for($i=0;$i<count($arrayMovement5);$i++){
					$arrayMovement5[$i]['InvMovement']['lc_state'] = 'DRAFT';
				}
			}elseif(($arrayMovement5 <> null)&&($STATE == 'NOTE_APPROVED')) {
				for($i=0;$i<count($arrayMovement5);$i++){
					$movementDocCode5 = $this->_generate_movement_code('SAL','inc');
					$arrayMovement5[$i]['InvMovement']['lc_state']='PENDANT';
					$arrayMovement5[$i]['InvMovement']['code'] = $movementDocCode5;
					$arrayMovement5[$i]['InvMovement']['date'] = $date;
					$arrayMovement5[$i]['InvMovement']['description'] = $description;
				}
			}elseif($arrayMovement5 <> null){
				for($i=0;$i<count($arrayMovement5);$i++){
					$arrayMovement5[$i]['InvMovement']['date'] = $date;
					$arrayMovement5[$i]['InvMovement']['description'] = $description;
					/////////////////////////////////////////////////////////////////
					if(($ACTION == 'save_invoice' && $OPERATION3 == 'DELETE') || ($ACTION == 'save_invoice' && $OPERATION4 == 'DELETE')){		
						if($arrayMovement5[$i]['InvMovement']['id'] === $draftId3){
							$arrayMovement5[$i]['InvMovement']['lc_state']='DRAFT';
						}
						if($arrayMovement5[$i]['InvMovement']['id'] === $draftId4){
							$arrayMovement5[$i]['InvMovement']['lc_state']='DRAFT';
						}
					}	
					/////////////////////////////////////////////////////////////////
				}
			}
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
			$dataMovement = array('SalSale'=>$arrayMovement);
			if ($ACTION == 'save_order'){
				$this->loadModel('InvMovement');
				//for invoice
				$dataMovement2 = array('SalSale'=>$arrayMovement2);
				//for movement
				$dataMovement3 = array('InvMovement'=>$arrayMovement3);
				$dataMovementDetail3 = array('InvMovementDetail'=> $arrayMovementDetails3);
				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
			}elseif (($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')) {
				$dataPayDetail = array('SalPayment'=> $arrayPayDetails);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
			}elseif ($ACTION == 'save_invoice') {
				$this->loadModel('InvMovement');
				//for movement
				$dataMovement3 = array('InvMovement'=>$arrayMovement3);
				$dataMovementDetail3 = array('InvMovementDetail'=> $arrayMovementDetails3);
				$dataMovement4 = array('InvMovement'=>$arrayMovement4);
				$dataMovementDetail4 = array('InvMovementDetail'=> $arrayMovementDetails4);
				if($arrayMovement5 <> null){
					$dataMovement5 = $arrayMovement5;
				}	
				if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)) ){
					$dataMovement6 = $arrayMovement6;
				}	
				$dataPayDetail = null;
			}
			$dataMovementDetail = array('SalDetail'=> $arrayMovementDetails);
			
			////////////////////////////////////////////////END - SET DATA//////////////////////////////////////////////////////
			
			$validation['error'] = 0;
			$strItemsStock = '';
			////////////////////////////////////////////START- CORE SAVE////////////////////////////////////////////////////////
			if($error === 0){
				/////////////////////START - SAVE/////////////////////////////	
//				echo 'OPERATION';
//					debug($OPERATION);
//				echo 'ACTION';
//					debug($ACTION);
//				echo '$dataMovement';	
//					debug($dataMovement);
//				echo '$dataMovementDetail';	
//					debug($dataMovementDetail);
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
//				echo '$arrayMovement5';	
//					debug($arrayMovement5);
//				echo '------------------------------------------------ <br>';	
//				echo '$arrayMovement6';	
//				debug($arrayMovement6);
//				debug($dataMovement6);
//				echo '------------------------------------------------ <br>';
//				echo '$dataPayDetail';
//				debug($dataPayDetail);
//				debug($arrayPayDetails);
//				debug($dataPayDetail);
//				echo '$rest3<br>';
//				debug($rest3);
//				echo 'id3<br>';
//				debug($arrayMovement3['id']);
//				echo '<br>$rest4<br>';
//				debug($rest4);
//				echo 'id4<br>';
//				debug($arrayMovement4['id']);
				
				if ($ACTION == 'save_invoice' && $STATE == 'SINVOICE_APPROVED'){
					$this->loadModel('InvPrice');
					$prices = $this->InvPrice->find('all', array(
						'fields'=>array(
							'InvPrice.inv_item_id'
							,'InvPrice.inv_price_type_id'
							,'InvPrice.price'
							),
						'conditions'=>array(
							'InvPrice.date'=>$date
							),
						'recursive'=>-1
					));
					$arraySalePrices = array();
					for($i=0;$i<count($arrayItemsDetails);$i++){
						$contSale = 0; 
						for($j=0;$j<count($prices);$j++){
							if($prices[$j]['InvPrice']['inv_item_id'] == $arrayItemsDetails[$i]['inv_item_id'] && $prices[$j]['InvPrice']['inv_price_type_id'] == 9 && $prices[$j]['InvPrice']['price'] == $arrayItemsDetails[$i]['sale_price']){	
								$contSale += 1;
							}							
						}
						if($contSale === 0){	
							$arraySalePrices[$i]['inv_item_id'] = $arrayItemsDetails[$i]['inv_item_id'];
							$arraySalePrices[$i]['inv_price_type_id'] = 9;//or better relate by name VENTA
							$arraySalePrices[$i]['price'] = $arrayItemsDetails[$i]['sale_price'];
							$arraySalePrices[$i]['ex_price'] = $arrayItemsDetails[$i]['ex_sale_price'];
							$arraySalePrices[$i]['description'] = $noteCode; 
							$arraySalePrices[$i]['date'] = $date;
						}	
					}
				}
					if($validation['error'] === 0){
							$res = $this->SalSale->saveMovement($dataMovement, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, $dataPayDetail);
							if ($ACTION == 'save_invoice' && $STATE == 'SINVOICE_APPROVED'){
									$this->loadModel('InvPrice');
									$this->InvPrice->saveAll($arraySalePrices);
							}
							if ($ACTION == 'save_order'){
								$res2 = $this->SalSale->saveMovement($dataMovement2, $dataMovementDetail, $OPERATION, $ACTION, $movementDocCode, null);
								if(($stock != 0)||(($OPERATION3 == 'DELETE')&&($arrayMovement3['id']!==null))){
									//used to insert/update type 1 detail movements 
									//used to delete movement details type 1
//									echo "ini3";
									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
//									echo "fin3";
								}
								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
									//used to insert/update type 2 detail movements									
									//used to delete movement details type 2
//									echo "ini4";
									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
//									echo "fin4";
								}	
								if($arrayMovement5 <> null){
									//used to update movements head
//									echo "ini5";
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
//									echo "fin5";
								}
								if((($ACTION == 'save_order' && $OPERATION3 == 'DELETE' && $arrayMovement6 <> null) || ($ACTION == 'save_order' && $OPERATION4 == 'DELETE' && $arrayMovement6 <> null)) ){
//									echo "ini6";
									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null, null, null);
//									echo "fin6";
								}
							
							}elseif ($ACTION == 'save_invoice' && $OPERATION != 'ADD_PAY' && $OPERATION != 'EDIT_PAY' && $OPERATION != 'DELETE_PAY') {
								if(($stock != 0)||(($OPERATION3 == 'DELETE')&&($arrayMovement3['id']!==null))){
									//used to insert/update type 1 detail movements 
									//used to delete movement details type 1
//									echo "ini3";
									$res3 = $this->InvMovement->saveMovement($dataMovement3, $dataMovementDetail3, $OPERATION3, 'save_in', null, $movementDocCode3);
//									echo "fin3";
								}							//VER SI v ESTA CONDICION DEJA ENTRAR LO NECESARIO										//VER SI v ESTA OTRA CONDICION DEJA ENTRAR LO NECESARIO 
								if(($quantity > $stock)||(($OPERATION3 == 'EDIT')&&($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))||(($OPERATION3 == 'DELETE')&&($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){	//($quantity > $stock) doesn't work when stock changes
//								if(($quantity > $stock)||(($OPERATION4 == 'DELETE')&&($arrayMovement4['id']!==null))){
									//used to insert/update type 2 detail movements									
									//used to delete movement details type 2
//									echo "ini4";
									$res4 = $this->InvMovement->saveMovement($dataMovement4, $dataMovementDetail4, $OPERATION4, 'save_in', null, $movementDocCode4);
//									echo "fin4";
								}	
								if($arrayMovement5 <> null){
									//used to update movements head
									//LO QUE ENTRE AQUI SOBREESCRIBE LA CABECERA DE $dataMovement3 y $dataMovement4
//									echo "ini5";
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
//									echo "fin5";
								}
//								if((($OPERATION3 == 'DELETE' || $OPERATION4 == 'DELETE') && $arrayMovement6 <> null)){
//									$res6 = $this->InvMovement->saveMovement($dataMovement6, null, 'DELETEHEAD', null);
//								}
							}elseif(($ACTION == 'save_invoice' && $OPERATION == 'ADD_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'EDIT_PAY') || ($ACTION == 'save_invoice' && $OPERATION == 'DELETE_PAY')){
								if($arrayMovement5 <> null){
									//used to update movements head
									$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
								}
							}
						if(($res <> 'error')||($res2 <> 'error')){
							$movementIdSaved = $res;	//sal_sales NOTE id
							if ($ACTION == 'save_order'){
								$movementIdSaved2 = $res2;	//sal_sales INVOICE id
							}
							$strItemsStockDestination = '';
							echo $STATE.'|'.$movementIdSaved.'|'.$movementDocCode.'|'.$movementCode.'|'.$strItemsStock.$strItemsStockDestination;
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
	
	public function ajax_logic_delete(){
		if($this->RequestHandler->isAjax()){
			$purchaseId = $this->request->data['purchaseId'];
			$type = $this->request->data['type'];	
			$genCode = $this->request->data['genCode'];
				if($this->SalSale->updateAll(array('SalSale.lc_state'=>"'$type'"), array('SalSale.id'=>$purchaseId)) 
						){
					echo 'success';
				}
				if($type === 'SINVOICE_LOGIC_DELETED'){
					$this->loadModel('InvMovement');
					$arrayMovement5 = $this->InvMovement->find('all', array(
						'fields'=>array(
							'InvMovement.id',
//							,'InvMovement.date'
//							,'InvMovement.description'
							'InvMovement.inv_warehouse_id'
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
						$res5 = $this->InvMovement->saveMovement($dataMovement5, null, 'UPDATEHEAD', null, null, null);
					}
				}
		}
	}
	
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
			$pays = $this->SalSale->SalPayment->SalPaymentType->find('list', array(
					'fields'=>array('SalPaymentType.name'),
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
	
	
	public function ajax_update_ex_rate(){
		if($this->RequestHandler->isAjax()){
			$date = $this->request->data['date']; 
			
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
			$this->loadModel('AdmExchangeRate');
			$xxxRate = $this->AdmExchangeRate->find('first', array(
					'fields'=>array('AdmExchangeRate.value'),
					'conditions'=>array(
						'AdmExchangeRate.currency'=>$currencyId,
						'AdmExchangeRate.date'=>$date
					),
					'recursive'=>-1
				)); 		
	//		debug($xxxRate);
			$exRate = $xxxRate['AdmExchangeRate']['value'];
		
			$this->set(compact('exRate'));			
		}else{
			$this->redirect($this->Auth->logout());
		}
	}
	
	//////////////////////////////////////////// END - AJAX /////////////////////////////////////////////////
	
	//////////////////////////////////////////// START - PRIVATE ///////////////////////////////////////////////
		
	public function _get_movements_details($idMovement){
		$movementDetails = $this->SalSale->SalDetail->find('all', array(
			'conditions'=>array(
				'SalDetail.sal_sale_id'=>$idMovement
				),																									                             /*REVISAR ESTO V*/
			'fields'=>array('InvItem.name', 'InvItem.code', 'SalDetail.sale_price', 'SalDetail.quantity','SalDetail.inv_warehouse_id', 'InvItem.id', 'InvWarehouse.name','InvWarehouse.id', 'InvItem.id')
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
				'salePrice'=>$value['SalDetail']['sale_price'],//llamar precio
				'cantidad'=>$value['SalDetail']['quantity'],//llamar cantidad
				'warehouseId'=>$value['InvWarehouse']['id'],
				'warehouse'=>$value['InvWarehouse']['name'],//llamar almacen
//	'cifPrice'=>$value['SalDetail']['cif_price'],
//	'exCifPrice'=>$value['SalDetail']['ex_cif_price'],
				'stock'=> $this->_find_stock($value['InvItem']['id'], $value['SalDetail']['inv_warehouse_id'])
				
				);
		}
//debug($formatedMovementDetails);		
		return $formatedMovementDetails;
	}
	
	public function _get_pays_details($idMovement){
		$paymentDetails = $this->SalSale->SalPayment->find('all', array(
			'conditions'=>array(
				'SalPayment.sal_sale_id'=>$idMovement
				),																									                            
			'fields'=>array('SalPayment.date', 'SalPayment.amount','SalPayment.description')
			));
		
		$formatedPaymentDetails = array();
		foreach ($paymentDetails as $key => $value) {
			$formatedPaymentDetails[$key] = array(
				'dateId'=>$value['SalPayment']['date'],//llamar precio
				//'payDate'=>strftime("%A, %d de %B de %Y", strtotime($value['SalPayment']['date'])),
				'payDate'=>strftime("%d/%m/%Y", strtotime($value['SalPayment']['date'])),
				'payAmount'=>$value['SalPayment']['amount'],//llamar cantidad
				'payDescription'=>$value['SalPayment']['description']
				);
		}
//debug($formatedPaymentDetails);		strftime("%A, %d de %B de %Y", $value['SalPayment']['date'])
		return $formatedPaymentDetails;
	}
	
	
	
	private function _get_price($itemId, $date, $type, $currType){
		$this->loadModel('InvPrice');
		//To change UK date format to US date format
		$bits = explode('/',$date);
		$date = $bits[1].'/'.$bits[0].'/'.$bits[2];
		//To get id of the price type
		$typeId = $this->InvPrice->InvPriceType->find('list', array(
			'fields'=>array(
				'InvPriceType.id'
				),
			'conditions'=>array(
				'InvPriceType.name'=>$type
				)
			));
		//To get the history of prices
		$prices = $this->InvPrice->find('list', array(
			'fields'=>array(
				'InvPrice.id',
				'InvPrice.date'
				),
			'conditions'=>array(
				'InvPrice.inv_item_id'=>$itemId,
				'InvPrice.inv_price_type_id'=>$typeId//'InvPrice.inv_price_type_id'=>1
				)
			));
		if($prices <> null){
			//To get the list of subtracted dates in unix time format
			foreach($prices as $id => $day){
				$interval[$id] = abs(strtotime($date) - strtotime($day));
			}
			asort($interval);
			$closest = key($interval);
			//To get the price
			if($currType == 'dolar'){
				$priceField = $this->InvPrice->find('first', array(
				'fields'=>array(
					'InvPrice.ex_price'
					),
				'conditions'=>array(
					'InvPrice.id'=>$closest
					)
				));
				$price = $priceField['InvPrice']['ex_price'];
			}else{
				$priceField = $this->InvPrice->find('first', array(
				'fields'=>array(
					'InvPrice.price'
					),
				'conditions'=>array(
					'InvPrice.id'=>$closest
					)
				));
				$price = $priceField['InvPrice']['price'];
			}
			if ($price === null){
				$price = 0;
			}
		}else{
			$price = 0;
		}
		//debug($price);
		return $price;
	}
		
	private function _get_doc_id($purchaseId, $movementCode, $type, $warehouseId){
		if ($purchaseId <> null) {
			$invoiceId = $this->SalSale->find('list', array(
				'fields'=>array('SalSale.id'),
				'conditions'=>array(
					'SalSale.code'=>$movementCode,
					"SalSale.id !="=>$purchaseId
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
				$movements = $this->SalSale->find('count', array(
					'conditions'=>array('SalSale.lc_state'=>array('NOTE_PENDANT','NOTE_APPROVED','NOTE_CANCELLED','NOTE_LOGIC_DELETED'))
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
				if ($keyword == 'NOT'){
					$movements = $this->SalSale->find('count', array(
						'conditions'=>array('SalSale.lc_state'=>array('NOTE_PENDANT','NOTE_APPROVED','NOTE_CANCELLED','NOTE_LOGIC_DELETED'))
					)); 
				}elseif ($keyword == 'VFA'){
					$movements = $this->SalSale->find('count', array(
						'conditions'=>array('SalSale.lc_state'=>array('SINVOICE_PENDANT','SINVOICE_APPROVED','SINVOICE_CANCELLED','SINVOICE_LOGIC_DELETED'))
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
//		$this->InvMovement->InvMovementDetail->unbindModel(array(
//			'belongsTo' => array('InvItem')
//		));
//		//Add association for InvMovementType
		$this->SalSale->SalDetail->InvItem->InvMovementDetail->bindModel(array(
			'hasOne'=>array(
				'InvMovementType'=>array(
					'foreignKey'=>false,
					'conditions'=> array('InvMovement.inv_movement_type_id = InvMovementType.id')
				)
				
			)
		));
		//******************************************************************************//
		//Movements
//		$movs = $this->SalSale->SalDetail->InvItem->InvMovementDetail->InvMovement->find('all', array(	
//			'fields'=>array('InvMovement.inv_warehouse_id', 'InvMovement.lc_state'),
//			'conditions'=>array(
//				'InvMovement.inv_warehouse_id'=>$idWarehouse,
//				'InvMovementDetail.inv_item_id'=>$idItem,
//				'InvMovementType.status'=>$status,
//				'InvMovement.lc_state'=>'APPROVED',
//				)
//		));
		
	//	$movements = $this->InvMovement->InvMovementDetail->find('all', array(
		$movements = $this->SalSale->SalDetail->InvItem->InvMovementDetail->find('all', array(	
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
	
	//////////////////////////////////////////// END - PRIVATE /////////////////////////////////////////////////
	
	//*******************************************************************************************************//
	/////////////////////////////////////////// END - CLASS ///////////////////////////////////////////////
	//*******************************************************************************************************//
	
/*********************************************************************************************************************/
/***********************************************************************************************************************/
/*********************************************************************************************************************/
/***********************************************************************************************************************/
/*********************************************************************************************************************/
/***********************************************************************************************************************/
	

	
	
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	public function ajax_generate_movements(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$date = $this->request->data['date'];
			$description = $this->request->data['description'];
			$note_code = $this->request->data['note_code'];
			$genericCode = $this->request->data['genericCode'];
			$originCode = $this->request->data['originCode'];
			print_r($arrayItemsDetails);
			echo "<br>";
			print_r($date);
			echo "<br>";
			print_r($description);
			echo "<br>";
			print_r($note_code);
			echo "<br>";
			print_r($genericCode);
			echo "<br>";
			print_r($originCode);
			echo "<br>";
			echo 'creado|';
		}	
	}
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	public function ajax_change_state_approved_movement_in_full(){
		if($this->RequestHandler->isAjax()){
			////////////////////////////////////////////INICIO-CAPTURAR AJAX/////////////////////////////////////////////////////
			$arrayItemsDetails = $this->request->data['arrayItemsDetails'];		
			$purchaseId = $this->request->data['purchaseId'];
	
			$this->loadModel('AdmUser');
			
			$date = $this->request->data['date'];
			$employee = $this->request->data['employee'];
			$taxNumber = $this->request->data['taxNumber'];
			$admProfileId = $this->request->data['salesman'];
			$description = $this->request->data['description'];
			$exRate = $this->request->data['exRate'];
			$note_code = $this->request->data['note_code'];
	
			$admUserId = $this->AdmUser->AdmProfile->find('list', array(
			'fields'=>array('AdmProfile.adm_user_id'),
			'conditions'=>array('AdmProfile.id'=>$admProfileId)
			));
			
			$salesman = key($this->AdmUser->find('list', array(
			'conditions'=>array('AdmUser.id'=>$admUserId)
			)));
			
			$generalCode = $this->request->data['genericCode'];
			////////////////////////////////////////////FIN-CAPTURAR AJAX/////////////////////////////////////////////////////
			
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////
//			$arrayMovement = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman, 'description'=>$description, 'note_code'=>$note_code, 'ex_rate'=>$exRate);
//			$arrayMovement['lc_state'] = 'SINVOICE_PENDANT';
//			$arrayMovement['id'] = $purchaseId;
			$arrayNote = array('id' => $purchaseId, 'lc_state'=>'NOTE_APPROVED');
			$arrayInvoice = array('date'=>$date, 'sal_employee_id'=>$employee,'sal_tax_number_id'=>$taxNumber,'salesman_id'=>$salesman, 'description'=>$description, 'note_code'=>$note_code, 'ex_rate'=>$exRate);		
			$movementDocCode = $this->_generate_doc_code('VFA');
			$arrayInvoice['lc_state'] = 'SINVOICE_PENDANT';
//			$arrayInvoice['lc_transaction'] = 'CREATE';
			$arrayInvoice['code'] = $generalCode;
			$arrayInvoice['doc_code'] = $movementDocCode;
//			$arrayInvoice['inv_supplier_id'] = $supplier;
			//*********************************************
				
			$cont1 = 0;
			$cont2 = 0;
			$arrayMovement1 = array();
			$arrayMovement2 = array();
			$arrayMovementDetails1 = array();
			$arrayMovementDetails2 = array();
			for($i=0;$i<count($arrayItemsDetails);$i++){
				if ($arrayItemsDetails[$i]['inv_warehouse_id'] == 1){
					$arrayMovementDetails1[$i]['inv_item_id'] = $arrayItemsDetails[$i]['inv_item_id'];
					$arrayMovementDetails1[$i]['quantity'] = $arrayItemsDetails[$i]['quantity'];
					
					$cont1 += 1;
				} elseif ($arrayItemsDetails[$i]['inv_warehouse_id'] == 2) {
					$arrayMovementDetails2[$i]['inv_item_id'] = $arrayItemsDetails[$i]['inv_item_id'];
					$arrayMovementDetails2[$i]['quantity'] = $arrayItemsDetails[$i]['quantity'];
					
					$cont2 += 1;
				}
			}
			
			$data1 = array();
			$data2 = array();
			if ($cont1 > 0 && $cont2 == 0){
				$arrayMovement1['date']=$date;
				$arrayMovement1['inv_warehouse_id']=1;
				$arrayMovement1['inv_movement_type_id']=2;
				$arrayMovement1['description']=$description;
				$arrayMovement1['document_code'] = $generalCode;
				$arrayMovement1['type']=1;
				$arrayMovement1['lc_state']='PENDANT';
				$arrayMovement1['code'] = $this->_generate_movement_code('SAL',null);
				
				$data1 = array('InvMovement'=>$arrayMovement1, 'InvMovementDetail'=>$arrayMovementDetails1);
			}elseif($cont2 > 0 && $cont1 == 0){
				$arrayMovement2['date']=$date;
				$arrayMovement2['inv_warehouse_id']=2;
				$arrayMovement2['inv_movement_type_id']=2;
				$arrayMovement2['description']=$description;
				$arrayMovement2['document_code'] = $generalCode;
				$arrayMovement2['type']=1;
				$arrayMovement2['lc_state']='PENDANT';
				$arrayMovement2['code'] = $this->_generate_movement_code('SAL',null);
				
				$data2 = array('InvMovement'=>$arrayMovement2, 'InvMovementDetail'=>$arrayMovementDetails2);
			}elseif($cont1 > 0 && $cont2 > 0){
				$arrayMovement1['date']=$date;
				$arrayMovement1['inv_warehouse_id']=1;
				$arrayMovement1['inv_movement_type_id']=2;
				$arrayMovement1['description']=$description;
				$arrayMovement1['document_code'] = $generalCode;
				$arrayMovement1['type']=1;
				$arrayMovement1['lc_state']='PENDANT';
				$arrayMovement1['code'] = $this->_generate_movement_code('SAL','inc');
				
				$arrayMovement2['date']=$date;
				$arrayMovement2['inv_warehouse_id']=2;
				$arrayMovement2['inv_movement_type_id']=2;
				$arrayMovement2['description']=$description;
				$arrayMovement2['document_code'] = $generalCode;
				$arrayMovement2['type']=1;
				$arrayMovement2['lc_state']='PENDANT';
				$arrayMovement2['code'] = $this->_generate_movement_code('SAL','inc');
				
				$data1 = array('InvMovement'=>$arrayMovement1, 'InvMovementDetail'=>$arrayMovementDetails1);
				$data2 = array('InvMovement'=>$arrayMovement2, 'InvMovementDetail'=>$arrayMovementDetails2);
			}
			
			$dataNot = array('SalSale'=>$arrayNote);		
			$dataInv = array('SalSale'=>$arrayInvoice, 'SalDetail'=>$arrayItemsDetails);
			
			
//			print_r($dataInv);
//			print_r($data1);
//			print_r($data2);
			
			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
			////////////////////////////////////////////INICIO-CREAR PARAMETROS////////////////////////////////////////////////////////

			////////////////////////////////////////////FIN-CREAR PARAMETROS////////////////////////////////////////////////////////
//			if ($data2 == array()){
//				echo "DATA2 VACIO";
//			}
			//print_r($code);
//			print_r($data2);
//			print_r($dataInv);
			////////////////////////////////////////////INICIO-SAVE////////////////////////////////////////////////////////
//			if($purchaseId <> ''){//update
//				if($this->SalSale->SalDetail->deleteAll(array('SalDetail.sal_sale_id'=>$purchaseId))){
				$this->loadModel('InvMovement');
					if($data2===array()){
						if(($this->SalSale->saveAll($dataNot))&&($this->SalSale->saveAssociated($dataInv))&&($this->InvMovement->saveAssociated($data1))){
							echo 'aprobado|first';
						}
					}elseif($data1===array()){
						if(($this->SalSale->saveAll($dataNot))&&($this->SalSale->saveAssociated($dataInv))&&($this->InvMovement->saveAssociated($data2))){
							echo 'aprobado|sec';
						}
					}else{
						
						if(($this->SalSale->saveAll($dataNot))&&($this->SalSale->saveAssociated($dataInv))&&($this->InvMovement->saveAssociated($data1))&&($this->InvMovement->saveAssociated($data2))){
							echo 'aprobado|both';
						}
					}
//				}$this->saveAll($dataNot)
//			}
			////////////////////////////////////////////FIN-SAVE////////////////////////////////////////////////////////
		}
	}
	
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}

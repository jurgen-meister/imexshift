$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation
	////////////////////////////////////// START - INITIAL ACTIONS /////////////////////////////////////////
	
	$('select').select2();
	$("#txtReportStartDate, #txtReportFinishDate").datepicker({
		showButtonPanel: true
	});
	startDataTable();
	
	////////////////////////////////////// END - INITIAL ACTIONS /////////////////////////////////////////
	
	
	
	////////////////////////////////////// START - EVENTS /////////////////////////////////////////
	$('#cbxReportGroupTypes').change(function(){
		getGroupItemsAndFilters();
	});
	
	$('#cbxReportMovementTypes').change(function(){
        if($(this).val() === '1001'){
			$('#boxWarehouse2').show();
			//$('#boxWarehouse2').find('label').css({"color":"red","border":"2px solid red"});
			$('#boxWarehouse').find('label').text('* Almacen Origen (Salida):');
		}else{
			$('#boxWarehouse2').hide();
			$('#boxWarehouse').find('label').text('Almacen:');
		}
	});
	
	$('#btnGenerateReport').click(function(){
		var startDate = $('#txtReportStartDate').val();
		var finishDate = $('#txtReportFinishDate').val();
		var movementType= $('#cbxReportMovementTypes').val();
		//var warehouse = getSelectedMultiSelect('#cbxReportWarehouse');//when it was multiple
		var warehouse = $('#cbxReportWarehouse').val();
		var warehouseName = $('#cbxReportWarehouse option:selected').text();
		var currency = $('#cbxReportCurrency').val();
		var groupBy = $('#cbxReportGroupTypes').val();
		var movementTypeName = $('#cbxReportMovementTypes option:selected').text();
		var items = getSelectedCheckboxes();
		var warehouse2 = 'non-existent';
		var warehouseName2 = 'non-existent';
		if($('#boxWarehouse2').css('display') === 'block'){
			warehouse2 = $('#cbxReportWarehouse2').val();
			warehouseName2 = $('#cbxReportWarehouse2 option:selected').text();
		}
		var error = validate(startDate, finishDate, movementType, warehouse, warehouse2, currency, groupBy, items);
		if(error === ''){
			var DATA = {
						startDate:startDate,
						finishDate:finishDate,
						movementType:movementType,
						movementTypeName:movementTypeName,
						warehouse:warehouse,
						warehouseName:warehouseName,
						currency:currency,
						groupBy:groupBy,
						warehouse2:warehouse2,
						warehouseName2:warehouseName2,
						items:items
					   };
			ajax_generate_report(DATA);
			$('#boxMessage').html('');
		}else{
			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
		}
	});
	
	////////////////////////////////////// END - EVENTS /////////////////////////////////////////
	
	
	////////////////////////////////////// START - FUNCTIONS /////////////////////////////////////////
   function getGroupItemsAndFilters(){
		ajax_get_group_items_and_filters();
   }
   
   function validate(startDate, finishDate, movementType, warehouse, warehouse2, currency, groupBy, items){
	   var  error='';
	   if(startDate === ''){error+='<li> El campo "Fecha Inicio" esta vacio </li>';}
	   if(finishDate === ''){error+='<li> El campo "Fecha Fin" esta vacio </li>';}
	   startDate = startDate.split("/");
	   finishDate = finishDate.split("/");
	   if(error === ''){
		    if(validateSameYearOnly(startDate[2], finishDate[2]) === 1){
				error+='<li> La "Fecha Inicio" y "Fecha Fin" deben ser del mismo año </li>';
			}else{
				if(validateGreaterThanStartDate(startDate, finishDate) === 1){error+='<li> La "Fecha Inicio" es mayor a la "Fecha Fin" </li>';}
			}
	   }
	   if(movementType === ''){error+='<li> El campo "Tipo de Movimiento" esta vacio </li>';}
	   if(warehouse === ''){
		   if(warehouse2 === 'non-existent'){
			   error+='<li> El campo "Almacen" esta vacio </li>';
		   }else{
			   error+='<li> El campo "Almacen Origen (Salida)" esta vacio </li>';
		   }
	   }
	   if(warehouse2 !== 'non-existent'){
			if(warehouse2 === ''){error+='<li> El campo "Almacen Destino  (Salida)" esta vacio </li>';}
			if(warehouse2 === warehouse){error+='<li> El "Almacen Origen (Salida)" no puede ser igual que el "Almacen Destino (Entrada)" </li>';}
	   }
	   if(currency === ''){error+='<li> El campo "Tipo de Cambio" esta vacio </li>';}
	   if(groupBy === ''){error+='<li> El campo "Agrupar por" esta vacio </li>';}
	   if(items.length === 0){error+='<li> Debe elegir al menos un "Item" </li>';}
	   return error;
   }
   
   function getSelectedCheckboxes(){
	   var selected = new Array();
	   $(".data-table tbody input:checkbox:checked").each(function() {
			selected.push($(this).val());
	   });
	   return selected;
   }
   
   function getSelectedMultiSelect(id){
		var selected = new Array();
	    $("form "+id+" option:selected").each(function () {
			selected.push($(this).val());
		});
		return selected;
   }
   
   function validateGreaterThanStartDate(startDate, finishDate){
		//Don't validate year 'cause is obligatory to be from the same year in other function
	   if(startDate[1] > finishDate[1]){//month
			return 1;//error
		}
		if(startDate[1] === finishDate[1]){//month
			if(startDate[0] > finishDate[0]){//day
				return 1;//error
			}
		}
		return 0;//ok
   }
   
   function validateSameYearOnly(startYear, finishYear){
	   if(startYear !== finishYear){
		   return 1; //error
	   }
	   return 0;//ok
   }
   
   function startDataTable(){
	   $('.data-table').dataTable({
			"bJQueryUI": true,
			//"sPaginationType": "full_numbers",
			"sDom": '<"">t<"F"f>i',
			"sScrollY": "200px",
			"bPaginate": false,
			"aaSorting":[], //on start sorting setting to empty
			"oLanguage": {
				"sSearch": "Filtrar:",
				 "sZeroRecords":  "No hay resultados que coincidan.",
				 //"sInfo":         "Ids from _START_ to _END_ of _TOTAL_ total" //when pagination exists
				 "sInfo": "Encontrados _TOTAL_ Items",
				 "sInfoEmpty": "Encontrados 0 Items",
				 "sInfoFiltered": "(filtrado de _MAX_ Items)"
			},
			"aoColumnDefs": [
			  { 'bSortable': false, 'aTargets': [ 0 ] }// do not sort first column
			]
		});
		$('input[type=checkbox]').uniform();
		$("#title-table-checkbox").click(function() {
			var checkedStatus = this.checked;
			var checkbox = $(this).parents('.widget-box').find('tr td:first-child input:checkbox');		
			checkbox.each(function() {
				this.checked = checkedStatus;
				if (checkedStatus === this.checked) {
					$(this).closest('.checker > span').removeClass('checked');
				}
				if (this.checked) {
					$(this).closest('.checker > span').addClass('checked');
				}
			});
		});	
   }
   
    function open_in_new_tab(url)
	{
	  var win=window.open(url, '_blank');
	  win.focus();
	}
   
////////////////////////////////////// END - FUNCTIONS /////////////////////////////////////////
	
//////////////////////////////////// START - AJAX ///////////////////////////////////////////////

	function ajax_generate_report(dataSent){ //Report
		$.ajax({
            type:"POST",
			async:false, // the key to open new windows when success
            url:moduleController + "ajax_generate_report",			
            data:dataSent,
			beforeSend: function(){
				$('#boxProcessing').text('Procesando...');
			},
            success: function(data){
				open_in_new_tab(moduleController+'report');
				$('#boxProcessing').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxProcessing').text('');
			}
        });
	}
	
	function ajax_get_group_items_and_filters(){ //Report
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_group_items_and_filters",			
            data:{type: $('#cbxReportGroupTypes').val()},
			beforeSend: function(){
				$('#boxProcessing').text('Procesando...');
			},
            success: function(data){
				$('#boxGroupItemsAndFilters').html(data);
				$('select').select2();
				startDataTable();
				$('#boxGroupItemsAndFilters #cbxReportGroupFilters').bind("change",function(){ 
					var selected = new Array();
					$("#boxGroupItemsAndFilters #cbxReportGroupFilters option:selected").each(function () {
						selected.push($(this).val());
					});
					ajax_get_group_items(selected);
				});
				$('#boxProcessing').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxProcessing').text('');
			}
        });
	}
	
	function ajax_get_group_items(selected){ //Report
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_group_items",			
            data:{type: $('#cbxReportGroupTypes').val(), selected: selected},
			beforeSend: function(){
				$('#boxProcessing').text('Procesando...');
			},
            success: function(data){
				$('#boxGroupItems').html(data);
				startDataTable();
				$('#boxProcessing').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxProcessing').text('');
			}
        });
	}
	
	//////////////////////////////////// END - AJAX ///////////////////////////////////////////////
	
//END SCRIPT	
});


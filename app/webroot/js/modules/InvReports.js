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
	////////////////////////////////////// END - EVENTS /////////////////////////////////////////
	
	
	////////////////////////////////////// START - FUNCTIONS /////////////////////////////////////////
	function startDataTable(){
	   $('.data-table').dataTable({
			"bJQueryUI": true,
			//"sPaginationType": "full_numbers",
			"sDom": '<"">t<"F"f>i',
			"sScrollY": "200px",
			"bPaginate": false,
			"oLanguage": {
				"sSearch": "Filtrar:",
				 "sZeroRecords":  "No hay resultados que coincidan.",
				 //"sInfo":         "Ids from _START_ to _END_ of _TOTAL_ total" //when pagination exists
				 "sInfo": "Encontrados _TOTAL_ Items",
				 "sInfoEmpty": "Encontrados 0 Items",
				 "sInfoFiltered": "(filtrado de _MAX_ Items)"
			}
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
   
   function getGroupItemsAndFilters(){
		ajax_get_group_items_and_filters();
   }
////////////////////////////////////// END - FUNCTIONS /////////////////////////////////////////
	
//////////////////////////////////// START - AJAX ///////////////////////////////////////////////
	function ajax_get_group_items_and_filters(){ //Report
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_group_items_and_filters",			
            data:{type: $('#cbxReportGroupTypes').val()},
			beforeSend: function(){
				$('#boxMessage').text('Procesando...');
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
				$('#boxMessage').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxMessage').text('');
			}
        });
	}
	
	function ajax_get_group_items(selected){ //Report
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_get_group_items",			
            data:{type: $('#cbxReportGroupTypes').val(), selected: selected},
			beforeSend: function(){
				$('#boxMessage').text('Procesando...');
			},
            success: function(data){
				$('#boxGroupItems').html(data);
				startDataTable();
				$('#boxMessage').text('');
			},
			error:function(data){
				showGrowlMessage('error', 'Vuelva a intentarlo.');
				$('#boxMessage').text('');
			}
        });
	}
	//////////////////////////////////// END - AJAX ///////////////////////////////////////////////
	
	/*
	//A simple Test
	$('form #cbxReportWarehouses').change(function(){
		var str = "YOU SELECTED :";
		$("form #cbxReportWarehouses option:selected").each(function () {
		//str += $(this).text() + " ";
		str += $(this).val() + " ";
		});
	   alert(str);
	});
	*/
	
	
//END SCRIPT	
});


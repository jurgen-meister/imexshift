$(document).ready(function() {
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/' + arr[1] + '/' + arr[2] + '/');

	//Initialize dropdown lists to position 0 for firefox refresh bug
	$('#modules option:nth-child(1)').attr("selected", "selected");
	$('#controllers option:nth-child(1)').attr("selected", "selected");
	
	if (arr[3] === 'index' || arr[3] === undefined) {
		startDataTable();
	}
	if(arr[3] === 'index2'){
		startDataTable2();
//		$('#myDataTable .btnEditRow').click(function(){
//			alert('editar fila');
//		});
	}
	
	//Initialize AJAX
	$('#modules').change(function(event) {
		ajax_list_controllers();
		$("#message").hide();
		event.preventDefault();
	});

	function ajax_list_controllers() {
		$.ajax({
			type: "POST",
			url: moduleController + "ajax_list_controllers",
			data: {module: $('#modules').val()},
			beforeSend: showProcessing,
			success: showControllers
		});
	}



	function showProcessing() {
		$("#processing").text("Procesando...");
	}

	function showControllers(data) {
		$("#processing").text("");
		$("#boxControllers").html(data);
	}

	function showSave(data) {
		$("#processing").text("");
		$("#message").html(data);
	}

	function captureCheckbox() {
		var allVals = [];
		$('form .checkbox :checked').each(function() {
			allVals.push($(this).val());
		});
		return allVals;
	}

	///////////////New function for dataTable
	function startDataTable() {
		$("#myDataTable").dataTable({
			"bJQueryUI": true
			,"sPaginationType": "full_numbers"
			,"sDom": '<"F"fl>t<"H"p>ir' //FUNCIONA si quito el widget-title todo OK 100% como quiero
			,"bStateSave": true
			, "oLanguage": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
			, "aoColumnDefs": [
				{"bSortable": false, "aTargets": [0]}
			]
			, "aaSorting": [[1, 'asc']]
			/////////Ajax
			, "bProcessing": true
			, "bServerSide": true
			, "sAjaxSource": moduleController + "index"
			, "sServerMethod": "POST"
		});//end dataTable
	}//end function startDataTable
	
	
	function startDataTable2() {
		$("#myDataTable").dataTable({
			"bJQueryUI": true
			,"sPaginationType": "full_numbers"
			,"sDom": '<"F"fl>t<"H"p>ir' //FUNCIONA si quito el widget-title todo OK 100% como quiero
			,"bStateSave": true
			, "oLanguage": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
			, "aoColumnDefs": [
				{"bSortable": false, "aTargets": [0]}
			]
			, "aaSorting": [[1, 'asc']]
			/////////Ajax
			, "bProcessing": true
			, "bServerSide": true
			, "sAjaxSource": moduleController + "index2"
			, "sServerMethod": "POST"
			,"fnDrawCallback": function () {
				$('#myDataTable tbody .btnEditRow').on('click',function(event){
//					alert('editar fila');
					editRow($(this));
					event.preventDefault();
				});
				
				$('#myDataTable tbody .btnDeleteRow').on('click',function(event){
					alert('eliminar fila');
					event.preventDefault();
				});
				 
//				$('#tblEmployees tbody tr:last .btnRowEditEmployee').bind("click", function(event) {
//					editRowEmployee($(this), event);
//				});
			}
		});//end dataTable
	}//end function startDataTable
	
	function editRow(object){
		var tr = object.closest('tr');
		tr.find('td:eq(1)').text('aarrgh');
		alert(tr.attr('id'));
	}
	
	//*************************************************************************************************//
	//*************************************************************************************************//
	function createModal(id, title){
		var modal = '';
		modal += '<div id="'+id+'" class="modal hide fade ">';
		modal +='<div class="modal-header">';
		modal +='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
		modal +='<h3>'+title+'</h3>';
		modal +='</div>';
		modal +='<div class="modal-body">';
		modal +='</div>';
		modal +='</div>';
		$('body #content').prepend(modal);
	}
	
	function ajax_load_modal(id, title){
		
		if($('#'+id).length === 0){
			createModal(id, title);
		}
		
		$.ajax({
			type: "POST",
			url: moduleController + "modal",
			data: {id:id, title:title},
			beforeSend: showProcessing,
			success: function(data){
				$('#'+id+' .modal-body').html(data);
				$('#btnPrueba').on('click',function(event){
					alert('modal remote de mierda');
					event.preventDefault();
				});
				
				$('#modules').on('change',function(event) {
					ajax_list_controllers();
					$("#message").hide();
					event.preventDefault();
				});
				
				$('#'+id).modal({
					show: 'true',
					backdrop:'static'
				});
			},
			error:function(data){
				alert(data);
			}
		});
	}
	
	$('#btnPruebaModal').click(function(event){
		ajax_load_modal('modalSave', 'TITULO DE MODAL');
		event.preventDefault();
	});
	
	function bindEventsOnModal(id){
		$('#'+id+' #cbxModules').bind('change', function(event){
			alert("FUNCIONAAAA");
			event.preventDefault();
		});
	}
	
	//*************************************************************************************************//
	//*************************************************************************************************//

///////////////////////////////////////////////////////////////////////////////////////////	
	
	$('#btnAddDataTable').click(function(event){
			var data = {'AdmController':{'adm_module_id':1, 'name':'aaa34', 'initials':'ppp', 'description':'blabla'}};
			ajax_save(data);
			event.preventDefault();
	});
	
	
	
	function ajax_save(data) {
		$.ajax({
			type: "POST",
			url: moduleController + "ajax_save",
			data: data,
			beforeSend: showProcessing,
			success: function(data){
				if(data === 'success'){
					alert("exito");
//					$("#myDataTable").dataTable();
//					startDataTable();
					$("#myDataTable").dataTable().fnAddData([
						1, 2, 3, 4, 5, 6 //when server doesn't matter with what I fill the array but must have the same quantity position as the table columns
					]);
				}
				
			},
			error:function(data){
				alert(data);
			}
		});
	}
//END Javascript 
});


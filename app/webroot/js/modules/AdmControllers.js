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
	
	
	//Initialize AJAX
	$('#modules').change(function() {
		ajax_list_controllers();
		$("#message").hide();
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

	function ajax_save() {
		$.ajax({
			type: "POST",
			url: moduleController + "ajax_save",
			data: {controller: captureCheckbox(), module: $("#modules").val()},
			beforeSend: showProcessing,
			success: showSave
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
});


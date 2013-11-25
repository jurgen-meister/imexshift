/* 
 * Javascript Test for vesion 2.0
 * Bittion - rey - 25/11/2013
 */
$(document).ready(function() {
//START SCRIPT
	///VARIABLES
	var url = window.location.pathname;
	var urlPaths = url.split('/');
	var urlModuleController = ('/' + urlPaths[1] + '/' + urlPaths[2] + '/');
	var urlAction = urlPaths[3];
	var oLanguage = { //spanish language object downloaded from the datatable site
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
			};
	
	//***********************************************************************************************************************************//
	///////////////////////////////////////////////////START - AJAX CRUD MAIN//////////////////////////////////////////////////////////////
	var crudModalId = 'modalSave';
	var crudFormId = 'formSave';
	var crudDataTableId = 'dataTable';
	
	
	var crudModalAction ='ajax_modal_save';
	var crudFormSaveAction ='fnAjaxSaveForm';
	var crudFormDeleteAction ='fnAjaxDeleteRow';
	var crudDataTableAction ='index';
	
	var crudModalTitle = 'Parámetro';
	var crudFieldMainFocus = 'btnAdd';
	var crudFieldModalFocus = 'AdmParameterName';
	
	
	//MAIN
	if (urlAction === crudDataTableAction || urlAction === undefined) {
		startDataTable();
		$('#'+crudFieldMainFocus).focus();
//		$('#dataTable_wrapper #dataTable_filter input').focus(); //FOCUS on dataTable Searcher
	}
	
	//EVENTS
	$('#btnAdd').click(function(event){
		ajaxGenerateModal();
		event.preventDefault();
	});
	///////////////////////////////////////////////////END - AJAX CRUD MAIN//////////////////////////////////////////////////////////////
	//***********************************************************************************************************************************//
	
	
	
	
	//***********************************************************************************************************************************//
	///////////////////////////////////////////////////START - AJAX CRUD CORE//////////////////////////////////////////////////////////////
	//FUNCTIONS
	//Datatable generator
	function startDataTable() {
		$('#'+crudDataTableId).dataTable({
			"bJQueryUI": true //creates speciall rows F and H
			,"sPaginationType": "full_numbers"
			,"sDom": '<"F"fl>t<"H"p>ir' //I had to take the widget-title for the table in order to work properly
			,"bStateSave": true //creates a cookie that save the search state
			, "oLanguage": oLanguage
			, "aoColumnDefs": [
				{"bSortable": false, "aTargets": [0]} //the first column is not sortable because there is the rows numeration
			]
			, "aaSorting": [[1, 'asc']] //orders the table from the second row
			/////////Ajax
			, "bProcessing": true //show message of processing  r
			, "bServerSide": true //ajax enabled
			, "sAjaxSource": urlModuleController + crudDataTableAction //the server side source
			, "sServerMethod": "POST" 
			,"fnDrawCallback": function () {
				$('#'+crudDataTableId+' tbody .btnEditRow').on('click',function(event){
					editRow($(this));
					event.preventDefault();
				});
				$('#'+crudDataTableId+' tbody .btnDeleteRow').on('click',function(event){
					deleteRow($(this));
					event.preventDefault();
				});
			}
		});//end dataTable
	}//end function startDataTable
	
	//btnEdit rows function
	function editRow(object){
		var tr = object.closest('tr');
		var trId = tr.attr('id');
		var trIdSplited = trId.split('-');
		ajaxGenerateModal(trIdSplited[1]);
	}
	
	//btnDelete rows function
	function deleteRow(object){
		var tr = object.closest('tr');
		var trId = tr.attr('id');
		var trIdSplited = trId.split('-');
		showBittionAlertModal({content:'¿Está seguro de eliminar?'});
		$('#bittionBtnYes').click(function(event){
			ajaxDeleteRow(trIdSplited[1]);
			hideBittionAlertModal();
			event.preventDefault();
		});
	}
	
	//Modal generator, only need to create a view with a form helper and that's it
	function createModal(){
		var modal = '';
		modal += '<div id="'+crudModalId+'" class="modal hide ">'; //took off "fade" from the class to be faster
		modal +='<div class="modal-header">';
		modal +='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
		modal +='<h3>'+crudModalTitle+'</h3>';
		modal +='</div>';
		modal +='<div class="modal-body">';
		modal +='</div>';
		modal +='</div>';
		$('body #content').prepend(modal);
	}
	
	//Through ajax generate dynamically the modal
	function ajaxGenerateModal(id){
		if(id === undefined){
			id = 0;
		}
		if($('#'+crudModalId).length === 0){
			createModal();
		}
		$.ajax({
			type: "POST",
			url: urlModuleController + crudModalAction,
			data: {id:id},
//			beforeSend: showProcessing,
			success: function(data){
				$('#'+crudModalId+' .modal-body').html(data);//load the modal
				$('#'+crudModalId).modal({//show modal
					show: 'true',
					backdrop:'static'
				});
				bindEventsToModal();//bind events if needed, ALWAYS after modal show, otherwise won't work
				$("html,body").css("overflow","hidden");//remove scroll, but I can't find a trigger to put it back
			},
			error:function(data){
				alert(data);
			}
		});
	}
	

	
	//Binding events if need, because the modal is generated dinamically
	function bindEventsToModal(){
//		$('#btnPrueba').on('click', function(event) {
//			alert('modal remote de mierda');
//			event.preventDefault();
//		});
		validateSaveForm();
		$('#'+crudFieldModalFocus).focus();
		$('#' + crudModalId).on('hidden', function() {
			$("html,body").css("overflow", "auto");
		});
	}
	
	//Main Save function
	function ajaxSaveForm() {
		var form = $('#'+crudFormId);
		var formData = form.serialize();
		$.ajax({
			type: 'POST',
			async:false,//hang request and prevent for multi submit, however there is no processing message
			url: urlModuleController + crudFormSaveAction,
			data: formData,
			beforeSend: function() {
			},
			success: function(data, textStatus, xhr) {
				if(data === 'success'){
					$('#'+crudModalId).modal('hide');
					var countTh = $('#'+crudDataTableId+' thead tr th').length;
					var arrayThPositions = [];
					for(var i=0; i< countTh; i++){
						arrayThPositions[i]=i;
					}
					$('#'+crudDataTableId).dataTable().fnAddData(
						arrayThPositions//Could be filled just with anything, because is SERVER SIDE just need the same quantity of columns
					);
					$.gritter.add({title: 'EXITO!', text: 'Cambios guardados.',	sticky: false,	image: '/imexport/img/check.png'});	
				}else{
					$('#'+crudModalId).modal('hide');
					$.gritter.add({title: 'OCURRIO UN PROBLEMA!', text: data,	sticky: false,	image: '/imexport/img/error.png'});	
				}
				$('#'+crudFieldMainFocus).focus();
			},
			error: function(xhr, textStatus, error) {
				$('#'+crudModalId).modal('hide');
				$('#'+crudFieldMainFocus).focus();
				$.gritter.add({title: 'ERROR!', text: 'Vuelva a intentarlo.',	sticky: false,	image: '/imexport/img/error.png'});	
			}
		});
	}
	
	function ajaxDeleteRow(id){
		$.ajax({
			type: 'POST',
			async:false,//hang request and prevent for multi submit, however there is no processing message
			url: urlModuleController + crudFormDeleteAction,
			data: {id:id},
			beforeSend: function() {
			},
			success: function(data, textStatus, xhr) {
				if(data === 'success'){
					$('#'+crudDataTableId).dataTable().fnDeleteRow();
					$.gritter.add({title: 'EXITO!', text: 'Eliminado.',	sticky: false,	image: '/imexport/img/check.png'});	
				}else{
					$('#'+crudModalId).modal('hide');
					$.gritter.add({title: 'OCURRIO UN PROBLEMA!', text: data,	sticky: false,	image: '/imexport/img/error.png'});	
				}
				$('#'+crudFieldMainFocus).focus();
			},
			error: function(xhr, textStatus, error) {
				$('#'+crudModalId).modal('hide');
				$('#'+crudFieldMainFocus).focus();
				$.gritter.add({title: 'ERROR!', text: 'Vuelva a intentarlo.',	sticky: false,	image: '/imexport/img/error.png'});	
			}
		});
	}
	
	//Validate Plugin
	function validateSaveForm() {
		$("#"+crudFormId).validate({
			onkeyup: false,
			submitHandler: function() {
				ajaxSaveForm();
			},
			rules: {
				'AdmParameterName': {
					required: true
							//,date:true
				},
				'AdmParameterDescription': {
					required: true
				}
			},
			errorClass: "help-inline",
			errorElement: "span",
			errorPlacement: function(error, element) {
				if (element.attr("type") === "checkbox" || element.attr("type") === "radio")
					error.insertAfter(element.parent().siblings().last());//this will insert the error message at the end of the checkboxes and radiobuttons
				else
					error.insertAfter(element);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').addClass('error');
				$(element).parents('.control-group').removeClass('success');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
				$(element).parents('.control-group').addClass('success');
			}
		});
	}
	/////////////////////////////////////////////////////END - AJAX CRUD CORE//////////////////////////////////////////////////////////////
	//***********************************************************************************************************************************//
	
	
//END SCRIPT
});


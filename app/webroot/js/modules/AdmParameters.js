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
	
	
	
	//MAIN
	if (urlAction === 'vindex') {
		startDataTable(urlAction);
		$('#btnAdd').focus();
//		$('#dataTable_wrapper #dataTable_filter input').focus(); //FOCUS on dataTable Searcher
	}
	
	//EVENTS
	$('#btnAdd').click(function(event){
		generateDynamicModal('modalSave', 'Parámetro');
//		validateForm();
//		alert('hola');
		event.preventDefault();
	});
	
	
	//FUNCTIONS
	//Datatable generator
	function startDataTable(action) {
		$("#dataTable").dataTable({
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
			, "sAjaxSource": urlModuleController + action //the server side source
			, "sServerMethod": "POST" 
		});//end dataTable
	}//end function startDataTable
	
	//Modal generator, only need to create a view with a form helper and that's it
	function createModal(id, title){
		var modal = '';
		modal += '<div id="'+id+'" class="modal hide ">'; //took off "fade" from the class to be faster
		modal +='<div class="modal-header">';
		modal +='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
		modal +='<h3>'+title+'</h3>';
		modal +='</div>';
		modal +='<div class="modal-body">';
		modal +='</div>';
		modal +='</div>';
		$('body #content').prepend(modal);
	}
	
	
	//Through ajax generate dynamically the modal
	function generateDynamicModal(id, title){
		if($('#'+id).length === 0){
			createModal(id, title);
		}
		$.ajax({
			type: "POST",
			url: urlModuleController + "vmodal",
			data: {id:id},
//			beforeSend: showProcessing,
			success: function(data){
				$('#'+id+' .modal-body').html(data);//load the modal
				$('#'+id).modal({//show modal
					show: 'true',
					backdrop:'static'
				});
				bindEventsToModal();//bind events if needed, always after modal show
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
		validateForm();
		$('#AdmParameterName').focus();
	}
	
	//Main Save function
	function ajaxSaveForm(idForm, idModal, idDataTable) {
		var form = $('#'+idForm);
		var formData = form.serialize();
//		var formUrl = form.attr('action');
//		var formId = form.attr('id');
		$.ajax({
			type: 'POST',
			async:false,//hang request and prevent for multi submit, however there is no processing message
			url: urlModuleController+'ajax_save',
			data: formData,
			beforeSend: function() {
			},
			success: function(data, textStatus, xhr) {
				if(data === 'success'){
					$('#'+idModal).modal('hide');
					var countTh = $('#dataTable thead tr th').length;
					var arrayThPositions = [];
					for(var i=0; i< countTh; i++){
						arrayThPositions[i]=i;
					}
					$('#'+idDataTable).dataTable().fnAddData(
						arrayThPositions//Could be filled just with anything, because is SERVER SIDE just need the same quantity of columns
					);
					showGrowlMessage('ok', 'Cambios guardados.');	
				}else{
					$('#'+idModal).modal('hide');
					showGrowlMessage('error', 'Vuelva a intentarlo.');
				}
			},
			error: function(xhr, textStatus, error) {
				$('#'+idModal).modal('hide');
				showGrowlMessage('error', 'Vuelva a intentarlo.');
			}
		});
	}
	
	//Validate Plugin
	function validateForm() {
		$("#AdmParameterVmodalForm").validate({
			onkeyup: false,
			submitHandler: function() {
				//Replace form submit for:
//			disableSubmit('saveForm');//prevent insert duplicate
				ajaxSaveForm('AdmParameterVmodalForm', 'modalSave', 'dataTable');
			},
			rules: {
				'AdmParameterName': {
					required: true
							//,date:true
				},
				'AdmParameterDescription': {
					required: true
							//,date:true
				}
			},
			errorClass: "help-inline",
			errorElement: "span",
			errorPlacement: function(error, element) {
				if (element.attr("type") === "checkbox" || element.attr("type") === "radio")
					error.insertAfter(element.parent().siblings().last());//this will insert the error message at the end of the checkboxes
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
	

	
	
	//Growl Message
	function showGrowlMessage(type, text, sticky){
		if(typeof(sticky)==='undefined') sticky = false;
		var title;
		var image;
		switch(type){
			case 'ok':
				title = 'EXITO!';
				image= '/imexport/img/check.png';
				break;
			case 'error':
				title = 'OCURRIO UN PROBLEMA!';
				image= '/imexport/img/error.png';
				break;
			case 'warning':
				title = 'PRECAUCIÓN!';
				image= '/imexport/img/warning.png';
				break;
		}
		$.gritter.add({
			title:	title,
			text: text,
			sticky: sticky,
			image: image
		});	
	}
	
	
//END SCRIPT
});


$(document).ready(function() {
//START SCRIPT

///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/' + arr[1] + '/' + arr[2] + '/');//Path validation




	function createRowEmployee(id, name, phone, email) {
		var rowCount = $('#tblEmployees tbody tr').length + 1;
		var row = '<tr>';
		row += '<td style="text-align:center;"><span class="spaNumber">' + rowCount + '</span><input type="text" value="' + id + '" class="spaIdEmployee"></td>';
		row += '<td><span class="spaNameEmployee">' + name + '</span></td>';
		row += '<td><span class="spaPhoneEmployee">' + phone + '</span></td>';
		row += '<td><span class="spaEmailEmployee">' + email + '</span></td>';
		row += '<td>';
		row += '<a href="#" class="btn btn-primary btnRowEditEmployee" title="Editar"><i class="icon-pencil icon-white"></i></a>';
		row += ' <a href="#" class="btn btn-danger btnRowDeleteEmployee" title="Eliminar"><i class="icon-trash icon-white"></i></a>';
		row += '</td>';
		row += '</tr>';
		return row;
	}

	function bindButtonEventsRowEmployee() {
		$('#tblEmployees tbody tr:last .btnRowEditEmployee').bind("click", function(event) {
			editRowEmployee($(this), event);
		});

		$('#tblEmployees tbody tr:last .btnRowDeleteEmployee').bind("click", function(event) {
			deleteRowEmployee($(this), event);
		});
	}

	////////////EVENTS
	$("#btnAddEmployee").click(function(event) {
		event.preventDefault();
		if($("#txtIdCustomer").val() !== ""){
			addEmployee();
		}else{
			alert('Debe "Guardar Cambios" del Cliente antes de adicionar un Empleado');
		}
	});
				

	function reordeRowNumbers(table){
		var counter = 1;
		$('#'+table+' tbody tr').each(function() {
			$(this).find('.spaNumber').text(counter);
			counter++;
		});
	}
	
	function addEmployee(){
		var id = $("#txtIdEmployee").val();
		var name = $("#txtNameEmployee").val();
		var phone = $("#txtPhoneEmployee").val();
		var email = $("#txtEmailEmployee").val();

		var error = validateBeforeAddEmployee(name);

		if (error === "") {
			//this will go inside ajax success
			addRowEmployee(id, name, phone, email);
		} else {
//			$('#boxMessage').html('<div class="alert-error"><ul>'+error+'</ul></div>');
			alert(error);
		}
	}
	
	function validateBeforeAddEmployee(name) {
		var error = '';
		
		if (name === '') {
			error += '<li> Debe registrar un nombre para el empleado </li>';
		}
		return error;
	}

	$(".btnRowEditEmployee").click(function(event) {
		editRowEmployee($(this), event);
	});

	$(".btnRowDeleteEmployee").click(function(event) {
		deleteRowEmployee($(this), event);
		
	});

	///////////PAGE FUNCTIONS
	function editRowEmployee(object, event) {
		event.preventDefault();
		var objectTableRowSelected = object.closest('tr');
		var valor = objectTableRowSelected.find('.spaNameEmployee').text();
		alert(valor);
	}

	function deleteRowEmployee(object, event) {
		showBittionAlertModal({content: '¿Está seguro de eliminar este item?'});
		$('#bittionBtnYes').click(function(event) {
			hideBittionAlertModal();
			object.closest('tr').fadeOut("slow", function() {
				$(this).remove();
				reordeRowNumbers('tblEmployees');//must go inside due the fadeout efect
			});
			event.preventDefault();
			
		});
		
		event.preventDefault();
	}

	function addRowEmployee(id, name, phone, email) {
		var pruebaRow = createRowEmployee(id, name, phone, email);
		$('#tblEmployees tbody').append(pruebaRow);
		bindButtonEventsRowEmployee();
		$('#SalEmployeeVsaveForm input[type=hidden], #SalEmployeeVsaveForm input[type=text]').val(""); //clear all after add
	}
	

	///////////AJAX FUNCTIONS

//END SCRIPT	
});


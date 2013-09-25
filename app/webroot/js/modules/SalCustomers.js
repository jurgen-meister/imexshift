$(document).ready(function(){
	//Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-CONTROLS EVENTS/////////////////////
	//************************************************************************//
	
	//Save Customer
	$('#saveButton').click(function(event){		
		saveCustomer();
		event.preventDefault();		
	});
	
	
	
	
	
	
	//************************************************************************//
	//////////////////////////////////END-CONTROLS EVENTS//////////////////////
	//************************************************************************//
	
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-FUNCTIONS////////////////
	//************************************************************************//
	
	
	//show message of procesing for ajax
	function showProcessing(){
        $('#processing').text("Procesando...");
    }
	//empty textboxs validation
	function validateRequired(){
		var error = '';
		
		if($('#txtName').val() === ''){
			error+='<li>"Nombre de Cliente" no puede estar vacio</li>';			
		}
		if($('#txtNit').val() === ''){
			error+='<li>"NIT" no puede estar vacio</li>';
		}
		if($('#txtNitName').val() === ''){
			error+='<li>"A nombre de" no puede estar vacio</li>';
		}
		if($('#txtEmpName').val() === ''){
			error+='<li>"Nombre de Encargado" no puede estar vacio</li>';
		}
		return error;
	}
	
	//set Error
	function setOnError(){
		showGrowlMessage('error', 'Vuelva a intentarlo.');
	}
	
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
	
	function saveCustomer(){		
		var error = validateRequired();
		if(error === ''){
			ajax_save_customer();
		}
		else{
			$('#boxMessage').html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+error+'</div>');			
		}
		
	}
	
	//************************************************************************//
	//////////////////////////////////END-FUNCTIONS//////////////////////
	//************************************************************************//
	
	
	//************************************************************************//
	//////////////////////////////////BEGIN-AJAX FUNCTIONS//////////////////////
	////************************************************************************//
	
	//Save Customer
	function ajax_save_customer(){
		
		
				
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save_customer",			
            data:{	name : $('#txtName').val(),
					address : $('#txtAddress').val(),
					phone : $('#txtPhone').val(),
					location : $('#txtLocation').val(),
					email : $('#txtEmail').val(),
					nit : $('#txtNit').val(),
					nitname : $('#txtNitName').val(),
					empname : $('#txtEmpName').val(),
					empphone : $('#txtEmpPhone').val(),
					empmail : $('#txtEmpMail').val()					
			},
            
			beforeSend: showProcessing(),
            success: function(data){
				
				//window.location.replace(moduleController + 'index');

				$('#boxMessage').html('<div class="alert alert-success">\n\
				<button type="button" class="close" data-dismiss="alert">&times;</button>Item guardado con exito<div>');
				$('#processing').text('');
				
			},
			error:function(data){
				$('#boxMessage').html('');
				$('#processing').text('');
				setOnError();
				//showGrowlMessage('error', 'Vuelva a intentarlo.');
			}
        });
	}
	
	
	
	
	//************************************************************************//
	//////////////////////////////////END-AJAX FUNCTIONS////////////////////////
	//************************************************************************//
	
});
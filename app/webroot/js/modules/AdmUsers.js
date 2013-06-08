$(document).ready(function(){
///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation	

//Firefox refresh bug
$('input').val('');
$('#txtBirthplace').val('Bolivia');

//BEGIN SCRIPT

   $("#txtActiveDate").datepicker({
	  showButtonPanel: true
   });
   $('#txtActiveDate').keypress(function(){return false;});
  
   $("#txtBirthdate").datepicker({
	  showButtonPanel: true,
	  viewMode: "years"
   });
   $('#txtBirthdate').keypress(function(){return false;});


$.validator.addMethod('diNumberUnique', function(value,element){
	var response;
	$.ajax({
		type:"POST",
		url:moduleController + "ajax_verify_unique_di_number",
		async:false,//the key for jquery.validation plugin, if it's true it finishes the function rigth there and it doesn't work
		data:{di_number:value
		  },
		success: function(data){			
			response = data;
		},
		error:function(data){
			alert('ocurrio un problema al validar el Documento de Identidad, vuelva a llenarlo');
			$('#txtDiNumber').val('');
			//return ('error intente de nuevo');
		}
	});
	
	if (response == '0'){
		return true;
	}else{
		return false;
	}
}, 'El documento de identidad ya existe');


 //$('input[type=checkbox],input[type=radio],input[type=file]').uniform(); // to verify if complete multiple options controls
 //$('select').select2(); // to create advanced select or combobox


// Form Validation
    $("#AdmUserAddForm").validate({
		onkeyup:false,
		submitHandler: function(form) {
            //Replace form submit for:
			ajax_add_user_profile();
        },
		rules:{
			txtFirstName:{
				required:true
			},
			txtLastName1:{
				required:true
			},
			txtLastName2:{
				required:true
			},
			cbxActive:{
				required:true
				//,date:true
			},
			txtActiveDate:{
				required:true
				//,date:true
			},
			txtEmail:{
				required:true,
				email: true
			},
			txtJob:{
				required:true
			},
			txtBirthdate:{
				required:true
				//,date: true
			},
			txtDiNumber:{
				digits:true,
				required:true,
				diNumberUnique:true
			},
			txtBirthplace:{
				required:true
			},
			txtDiPlace:{
				required:true
			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			$(element).parents('.control-group').removeClass('success');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.control-group').removeClass('error');
			$(element).parents('.control-group').addClass('success');
		}
	});



	function ajax_add_user_profile(){
		$.ajax({
			type:"POST",
			async:false, // the key to open new windows when success
			url:moduleController + "ajax_add_user_profile",
			data:{
					 txtDiNumber:$('#txtDiNumber').val()
					,txtDiPlace:$('#txtDiPlace').val()
				    ,txtFirstName:$('#txtFirstName').val()
					,txtLastName1:$('#txtLastName1').val()
					,txtLastName2:$('#txtLastName2').val()
					,cbxActive:$('#cbxActive').val()
					,txtActiveDate:$('#txtActiveDate').val()
					,txtEmail:$('#txtEmail').val()
					,txtJob:$('#txtJob').val()
					,txtBirthdate:$('#txtBirthdate').val()
					,txtBirthplace:$('#txtBirthplace').val()
					,txtAddress:$('#txtAddress').val()
					,txtPhone:$('#txtPhone').val()
			  },
			 // beforeSend:function(data){alert('sdhfjdshk')},
			success: function(data){			
				$.gritter.add({
					title:	'EXITO!',
					text: 'Usuario creado',
					sticky: true,
					image:'/imexport/img/check.png'
				});	
				$('input').val('');
				$('#txtBirthplace').val('Bolivia');
				//view_user_created
				open_in_new_tab('view_user_created.pdf');
			},
			error:function(data){
				$.gritter.add({
					title:	'OCURRIO UN PROBLEMA!',
					text:	'Vuelva a intentarlo',
					sticky: false,
					image:'/imexport/img/error.png'
				});		
			}
		});
	}
   
   function open_in_new_tab(url)
	{
	  var win=window.open(url, '_blank');
	  win.focus();
	}
   
	
//END SCRIPT	
});

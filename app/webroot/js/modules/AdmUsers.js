$(document).ready(function(){
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
////////////////////////////////////////////////////
function myTextOnlyValidation(value, element){
    return /[a-zA-Z ]/.test(value);
}

function debeSerIgual(value, element){
	if(element.value == 'hola'){return true;}
	return false;
	
}

//custom validation rule - text only
$.validator.addMethod("soloTexto", 
					  myTextOnlyValidation, 
					  "Alpha Characters Only."
);

$.validator.addMethod("igualHola", 
					  debeSerIgual, 
					  "El valor debe ser igual a hola."
);


   $('input[type=checkbox],input[type=radio],input[type=file]').uniform();
	
   //$('select').select2();
   
   $('#btnValidate').click(function(){
	   //return false;
   });
   
   // Form Validation
    $("#AdmUserAddForm").validate({
		submitHandler: function(form) {
            //do submit
			alert('aqui va el Ajax');
        },
		rules:{
			txtFirstName:{
				required:true
			},
			txtLastName:{
				required:true
				//soloTexto:true
				//igualHola:true
			},
			txtLogin:{
				required:true
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
				digits:true
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
//END SCRIPT	
});

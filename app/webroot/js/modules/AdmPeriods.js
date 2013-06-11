$(document).ready(function(){
///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation	
//BEGIN SCRIPT

function ajax_add_period(){
	 $.ajax({ 
            type:"POST",
            url:moduleController + "ajax_add_period",			
            data:{period: $("#txtPeriod").val()},
            //beforeSend: showProcessing,
            success: function(data){			
				var arrayCatch = data.split('|');
				if(arrayCatch[0] == 'success'){
						$.gritter.add({
						title:	'EXITO!',
						text: 'Nueva gestión creada',
						sticky: false,
						image:'/imexport/img/check.png'
					});	
					$('#txtPeriod').val(arrayCatch[1]);
					$('#spaPeriod').text(arrayCatch[1]);
				}else{
					$.gritter.add({
					title:	'OCURRIO UN PROBLEMA!',
					text:	'Vuelva a intentarlo',
					sticky: false,
					image:'/imexport/img/error.png'
				});		
				}
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


$('#btnYes').click(function(){
	ajax_add_period();
});

//END SCRIPT	
});


$(document).ready(function(){
///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');//Path validation	
//BEGIN SCRIPT

	function ajax_list_periods_areas(){
	    $.ajax({ 
            type:"POST",
            url:moduleController + "ajax_list_periods_areas",			
            data:{period: $("#cbxPeriods").val()},
            //beforeSend: showProcessing,
            success: function(data){
				 $("#boxParentAreas").html(data);
			},
			error: function(data){
				$.gritter.add({
					title:	'OCURRIO UN PROBLEMA!',
					text:	'Vuelva a intentarlo',
					sticky: false,
					image:'/imexport/img/error.png'
				});		
			}
        });
   }

	$('#cbxPeriods').change(function(){
		ajax_list_periods_areas();
	});

//END SCRIPT	
});


$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	
	//Initialize AJAX
    $('#modules').change(function(){
        ajax_list_actions();		
    });
	
	$('#modules_inside').change(function(){
        ajax_list_controllers_inside();		
    });
	
	$('#controllers').change(function(){
        ajax_list_actions_inside();		
    });
	
	function ajax_list_actions(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_actions",			
            data:{module: $("#modules").val()},
            beforeSend: showProcessing,
            success: showActions
        });
    }
	
	function ajax_list_controllers_inside(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_controllers_inside",			
            data:{module: $("#modules_inside").val()},
            beforeSend: showProcessing,
            //success: showControllersInside
			success:function(data){
				showControllersInside(data);
				$('#controllers').bind("change",function(){
					 ajax_list_actions_inside();
				});
			}
        });
    }
	
	function ajax_list_actions_inside(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_actions_inside",			
            data:{controller: $("#controllers").val()},
            beforeSend: showProcessing,
            success: showActionsInside
        });
    }
	
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
    function showActions(data){
        $("#processing").text("");
        $("#boxActions").html(data);
    }
	function showControllersInside(data){
        $("#processing").text("");
        $("#boxControllers").html(data);
    }
	function showActionsInside(data){
        $("#processing").text("");
        $("#boxActions").html(data);
    }
	
});
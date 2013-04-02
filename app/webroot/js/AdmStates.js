$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	//Initialize dropdown lists to position 0 for firefox refresh bug
	$('#modules option:nth-child(1)').attr("selected", "selected");
	$('#controllers option:nth-child(1)').attr("selected", "selected");
    
	//Initialize AJAX
    $('#modules').change(function(){
        ajax_list_controllers();
		$("#message").hide();
    });
	
	function ajax_list_controllers(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_controllers",
            data:{module: $('#modules').val()},
            beforeSend: showProcessing,
            success:showControllers
        });
    }
	
	function ajax_save(){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save",
            data:{controller: captureCheckbox(), module: $("#modules").val() },
            beforeSend:showProcessing,
            success:showSave
        });
	}
	
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
	
    function showControllers(data){
        $("#processing").text("");
        $("#boxControllers").html(data);
    }
	
	function showSave(data){
		$("#processing").text("");
		$("#message").html(data);		
	}
	
	function captureCheckbox(){
	 var allVals =[];
     $('form .checkbox :checked').each(function(){
       allVals.push($(this).val());});	   
	   return allVals;
	}

	
});


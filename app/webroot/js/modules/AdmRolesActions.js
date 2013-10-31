$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	///Initialize checkboxTree behavior
		 $('#cbxRoles option:nth-child(1)').attr("selected", "selected");
		 $('#cbxModules option:nth-child(1)').attr("selected", "selected");
		 $('#tree1').checkboxTree();
	
	$("select").select2();
	
	//Initialize dropdown lists to position 0 for firefox refresh bug
    $('#cbxModules option:nth-child(1)').attr("selected", "selected");
    $('#cbxRoles option:nth-child(1)').attr("selected", "selected");
	/////////Ajax
	$('#cbxRoles').change(function(){
        ajax_list_menus();		
    });
	$('#cbxModules').change(function(){
        ajax_list_menus();		
    });
	
	
	
	function ajax_list_menus(){
        $.ajax({
            type:"POST",
            url: moduleController + "ajax_list_menus",			
            data:{module: $("#cbxModules").val(), role: $("#cbxRoles").val()},
            beforeSend: showProcessing,
            success: showMenus
        });
    }
	
		
	$('#saveButton').click(function(){
		//$("#message").hide();
		ajax_save();
		return false; //evita haga submit form
    });
	
	
	
	function ajax_save(){
		var role = $("#cbxRoles").val();
		var module = $("#cbxModules").val();
		var menu = [];
		menu = captureCheckbox();
		$.ajax({
            type:"POST",
            url:moduleController +"ajax_save",
            data:{role: role, module: module, menu: menu },
            beforeSend:showProcessing,
            success:showSave,
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
	
	function showSave(data){
	   $.gritter.add({
			title:	'EXITO!',
			text: 'Cambios guardados',
			sticky: false,
			image:'/imexport/img/check.png'
		});	
	   $("#processing").text("");
	}
	
	function captureCheckbox(){
	 var allVals =[];
     $('form #boxChkTree :checked').each(function(){
		 if($(this).val() !== "empty"){
			allVals.push($(this).val());
		 }
       });	   
	   return allVals;
	}
	
	
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
    function showMenus(data){
        $("#processing").text("");
        $("#boxChkTree").html(data);
		$('#tree1').checkboxTree();
    }
	

	
});


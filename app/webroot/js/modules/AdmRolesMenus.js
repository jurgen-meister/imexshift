$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	///Initialize checkboxTree behavior
	if(arr[3] === 'add'){
		 $('#roles option:nth-child(1)').attr("selected", "selected");
		 $('#modules option:nth-child(1)').attr("selected", "selected");
		 $('#tree1').checkboxTree();
	}
	
	$("select").select2();
	
	//Initialize dropdown lists to position 0 for firefox refresh bug
    $('#parentMenus option:nth-child(1)').attr("selected", "selected");
    $('#roles option:nth-child(1)').attr("selected", "selected");
	$('#modules_inside option:nth-child(1)').attr("selected", "selected");
    $('#roles_inside option:nth-child(1)').attr("selected", "selected");
	/////////Ajax
	$('#roles').change(function(){
        ajax_list_menus();		
    });
	$('#parentMenus').change(function(){
        ajax_list_menus();		
    });
	
	$('#roles_inside').change(function(){
        ajax_list_menus_inside();		
    });
	$('#modules_inside').change(function(){
        ajax_list_menus_inside();		
    });
	
	function ajax_list_menus(){
        $.ajax({
            type:"POST",
            url: moduleController + "ajax_list_menus",			
            data:{parentMenus: $("#parentMenus").val(), role: $("#roles").val()},
            beforeSend: showProcessing,
            success: showMenus
        });
    }
	
//	function ajax_list_menus_inside(){
//        $.ajax({
//            type:"POST",
//            url:moduleController +"ajax_list_menu_inside",			
//            data:{role: $("#roles_inside").val(), module: $("#modules_inside").val()},
//            beforeSend: showProcessing,
//            success: showMenusInside
//        });
//    }
	
	$('#saveButton').click(function(){
		//$("#message").hide();
		ajax_save();
		return false; //evita haga submit form
    });
	
	
	
	function ajax_save(){
		var roleGeneric = $("#roles").val();
		var parentMenusGeneric = $("#parentMenus").val();
		var menuGeneric = [];
		menuGeneric = captureCheckbox();
		var type = 'outside';
		if(arr[3] === 'add_inside'){
			roleGeneric = $("#roles_inside").val();
			moduleGeneric = $("#modules_inside").val();
			menuGeneric = captureCheckboxInside();
			type = 'inside';
		}
		
		$.ajax({
            type:"POST",
            url:moduleController +"ajax_save",
            data:{role: roleGeneric, parentMenus: parentMenusGeneric, menu: menuGeneric, type: type },
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
		//$("#message").html(data);
		/*
		var send = "";
		if(data == 'missing'){
			send = '<div class="alert alert-error">*Debe marcar un menu</div>';
			$("#message").fadeIn();
			$("#message").html(send);
		}
		if(data == 'success'){
		send = '<div class="alert alert-success">Guardado con exito</div>';
			$("#message").fadeIn();
			$("#message").html(send);
			$("#message").delay(1500).fadeOut(1000);
		}
		*/
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
       allVals.push($(this).val());});	   
	   return allVals;
	}
	
//	function captureCheckboxInside(){
//	 var allVals =[];
//     $('#boxMenusInside :checked').each(function(){
//       allVals.push($(this).val());});	   
//	   return allVals;
//	}
	
	function showProcessing(){
        $("#processing").text("Procesando...");
    }
    function showMenus(data){
        $("#processing").text("");
        $("#boxChkTree").html(data);
		$('#tree1').checkboxTree();
    }
	
//	function showMenusInside(data){
//		$("#processing").text("");
//        $("#boxMenusInside").html(data);
//	}
	
});


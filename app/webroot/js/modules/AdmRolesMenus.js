$(document).ready(function() {
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/' + arr[1] + '/' + arr[2] + '/');
	///Initialize checkboxTree behavior
//	if (arr[3] === 'save') {
		$('#roles option:nth-child(1)').attr("selected", "selected");
		$('#modules option:nth-child(1)').attr("selected", "selected");
		$('#tree1').checkboxTree();
//	}

	$("select").select2();

	//Initialize dropdown lists to position 0 for firefox refresh bug
	$('#parentMenus option:nth-child(1)').attr("selected", "selected");
	$('#roles option:nth-child(1)').attr("selected", "selected");
	$('#modules_inside option:nth-child(1)').attr("selected", "selected");
	$('#roles_inside option:nth-child(1)').attr("selected", "selected");
	/////////Ajax
	$('#roles').change(function() {
		ajax_list_menus();
	});
	$('#parentMenus').change(function() {
		ajax_list_menus();
	});

	$('#roles_inside').change(function() {
		ajax_list_menus_inside();
	});
	$('#modules_inside').change(function() {
		ajax_list_menus_inside();
	});

	function ajax_list_menus() {
		$.ajax({
			type: "POST",
			url: moduleController + "ajax_list_menus",
			data: {parentMenus: $("#parentMenus").val(), role: $("#roles").val()},
			beforeSend: showProcessing,
			success: showMenus
		});
	}



	$('#saveButton').click(function() {
		//$("#message").hide();
		ajax_save();
		return false; //evita haga submit form
	});



	function ajax_save() {
		var roleGeneric = $("#roles").val();
		var parentMenusGeneric = $("#parentMenus").val();
		var menuGeneric = [];
		menuGeneric = captureCheckbox();
		var type = 'outside';
		if (arr[3] === 'add_inside') {
			roleGeneric = $("#roles_inside").val();
			moduleGeneric = $("#modules_inside").val();
			menuGeneric = captureCheckboxInside();
			type = 'inside';
		}
		$.ajax({
			type: "POST",
			async:false,//will freeze the browser until it's done, avoid repeated inserts after happy button clicker, con: processsing message won't work
			url: moduleController + "ajax_save",
			data: {role: roleGeneric, parentMenus: parentMenusGeneric, menu: menuGeneric, type: type},
			beforeSend: showProcessing,
			success:function(data){
				if(data === 'success' || data ==='successEmpty'){
					$.gritter.add({
					   title: 'EXITO!',
					   text: 'Cambios guardados.',
					   sticky: false,
					   image:'/imexport/img/check.png'
				   });	
				}else{
					$.gritter.add({
						title:	'NO SE GUARDO!',
						text:	'Ocurrio un error.',
						sticky: false,
						image:'/imexport/img/error.png'
					});		
				};
				$("#processing").text("");
			},
			error:function(data){
				$.gritter.add({
					title:	'ERROR!',
					text:	'Ocurrio un problema.',
					sticky: false,
					image:'/imexport/img/error.png'
				});		
				$("#processing").text("");
			}
		});
	}

	function captureCheckbox() {
		var allVals = [];
		$('form #boxChkTree :checked').each(function() {
			allVals.push($(this).val());
		});
		return allVals;
	}

	function showProcessing() {
		$("#processing").text("Procesando...");
	}

	function showMenus(data) {
		$("#processing").text("");
		$("#boxChkTree").html(data);
		$('#tree1').checkboxTree();
	}

});


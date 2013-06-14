$(document).ready(function(){
	///Url Paths
	var path = window.location.pathname;
	var arr = path.split('/');
	var moduleController = ('/'+arr[1]+'/'+arr[2]+'/');
	
    //Initialize dropdown lists to position 0 for firefox refresh bug
    $('#roles option:nth-child(1)').attr("selected", "selected");
    $('#modules option:nth-child(1)').attr("selected", "selected");
    $('#controllers option:nth-child(1)').attr("selected", "selected");
	

    //Initialize AJAX
    $('#modules').change(function(){
        ajax_list_controllers();
		$("#message").hide();
    });

    $('#controllers').change(function(){
        ajax_list_transactions();
		$("#message").hide();
    });

    $('#roles').change(function(){
        ajax_list_transactions();
		$("#message").hide();
    });
	
	$('#saveButton').click(function(){
		$("#message").hide();
		ajax_save();
		//setTimeout(save_ajax(),5000);
		return false; //evita haga submit form
		//$(this).delay(5000);
    });
	
    function ajax_list_controllers(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_controllers",
            data:{module: $('#modules').val(), role: $('#roles').val() },
            beforeSend: showProcessing,
            //success:showControllers
			success:function(data){
				showControllers(data);
				$('#controllers').bind("change",function(){
					 ajax_list_transactions();
					 $("#message").hide();
				});
			}
        });
    }

    function ajax_list_transactions(){
        $.ajax({
            type:"POST",
            url:moduleController + "ajax_list_transactions",			
            data:{role: $("#roles").val(), controller: $("#controllers").val() },
            beforeSend: showProcessing,
            success:showTransactions
        });
    }
	
	function ajax_save(){
		$.ajax({
            type:"POST",
            url:moduleController + "ajax_save",
            data:{role: $("#roles").val(), controller: $("#controllers").val(), transaction: captureCheckbox() },
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
	
    function showProcessing(){
        $("#processing").text("Procesando...");
    }
    function showControllers(data){
        $("#processing").text("");
        $("#boxControllers").html(data);
    }

    function showTransactions(data){
        $("#processing").text("");
        $("#boxTransactions").html(data);
    }
	function showSave(data){
		$("#processing").text("");
		var send = "";
		if(data == 'missing'){
			send = '<div style="color:#ff0000;">*Debe marcar una accion</div>';
			$("#message").fadeIn();
			$("#message").html(send);
		}
		if(data == 'success'){
			/*
			send = '<div style="background-color: #90ee90;">Guardado con exito</div>';
			$("#message").fadeIn();
			$("#message").html(send);
			$("#message").delay(1500).fadeOut(1000);
			*/
		   $.gritter.add({
			title:	'EXITO!',
			text: 'Cambios guardados',
			sticky: false,
			image:'/imexport/img/check.png'
			});	
		}
	}
	
	function captureCheckbox(){
	 var allVals =[];
     $('form .checkbox :checked').each(function(){
       allVals.push($(this).val());});	   
	   return allVals;
	}

});


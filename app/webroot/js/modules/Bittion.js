/* 
 * Bittion default scripts, june 2013
 */
//$(document).ready(function(){
//START SCRIPT	
	//***************************************************************************//
	//************************START - BITTION ALERT MODAL************************//
	//***************************************************************************//
	function bittionAlertModal(arg){
		if(arg === undefined){
			//alert('No hay objeto definido');
			//return false;
			var arg = {
			title:'Mensaje',
			content: '¿Esta seguro?',
			btnYes:'Aceptar',
			btnNo:'Cancelar',
			btnOptional:''
			}
		}else{
			if(arg.title === undefined){
				arg.title ='Mensaje';
			}
			if(arg.content === undefined){
				arg.content ='¿Esta seguro?';
			}
			if(arg.btnYes === undefined){
				arg.btnYes ='Aceptar';
			}
			if(arg.btnNo === undefined){
				arg.btnNo ='Cancelar';
			}
			if(arg.btnOptional === undefined){
				arg.btnOptional ='';
			}
		}

		if($('#bittionAlertModal').length === 0){
			$('#content').append(createAlertModal(arg));
		}

	}

	var html ='';
	function createAlertModal(arg){
		html = '<div id="bittionAlertModal" class="modal hide">';
		html += createHeader(arg.title);
		html += createBody(arg.content);
		html += createFooter(arg.btnYes, arg.btnNo, arg.btnOptional);
		html += '</div>';
		return html;
	}

	function createHeader(title){
		html =  '<div class="modal-header">';
		html += '<button data-dismiss="modal" class="close" type="button">×</button>';
		html += '<h3>'+title+'</h3>';
		html += '</div>';
		return html;
	}
	function createBody(content){
		html = '<div class="modal-body">';
		html += '<p>'+content+'</p>';
		html += '</div>';
		return html;
	}
	function createFooter(btnYes, btnNo, btnOptional){
		html = '<div class="modal-footer">';
		if(btnYes !== ''){
			html += '<a class="btn btn-primary" id="bittionBtnYes" href="#">'+btnYes+'</a>';
		}
		if(btnNo !== ''){
			html += '<a class="btn" id="bittionBtnNo" href="#">'+btnNo+'</a>';
		}
		if(btnOptional !== ''){
			html += '<a class="btn btn-primary" id="bittionBtnOptional" href="#">'+btnOptional+'</a>';
		}
		html +='</div>';
		return html;
	}
	//***************************************************************************//
	//************************FINISH - BITTION ALERT MODAL************************//
	//***************************************************************************//


//END SCRIPT
//});

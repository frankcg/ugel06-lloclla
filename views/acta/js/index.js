$(document).on('ready',function(){

	function tablapersonal(){
		$('#tablapersonal').dataTable().fnDestroy();		 	
		$('#tablapersonal').DataTable({

			//PARA EXPORTAR
			dom: "Bfrtip",
			buttons: [{
				extend: "copy",
				className: "btn-sm"
			}, {
				extend: "csv",
				className: "btn-sm"
			}, {
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,
			
			"order" : [ [ 1, "asc" ] ],
			"ajax" : "../acta/getpersonales",
			"columns" : [{
				"data" : "DNI"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "NOMBRE_OFICINA"
			},{
				"data" : "STATUS"
			},{
				"data" : "CANT"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablapersonal();


	/* **************************************************************************************************** */
	/* 												ACTA DE RETIRO
	/* **************************************************************************************************** */

	function tablaactaretiro(idpersona){
		$('#tablaactaretiro').dataTable().fnDestroy();		 	
		$('#tablaactaretiro').DataTable({

			//PARA EXPORTAR
			dom: "Bfrtip",
			buttons: [{
				extend: "copy",
				className: "btn-sm"
			}, {
				extend: "csv",
				className: "btn-sm"
			}, {
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,
			
			"order" : [ [ 1, "asc" ] ],
			"lengthMenu": [20],
			"ajax" : "../acta/getactivosretiro/"+idpersona,
			"columns" : [{
				"data" : "IDACTIVO"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "MODELO"
			},{
				"data" : "MARCA"
			},{
				"data" : "SERIE"
			},{
				"data" : "CAPACIDAD"
			},{
				"data" : "ESTADO"
			},{
				"data" : "TIPO"
			},{
				"data" : "DESCRIPCION"
			},{
				"data" : "MOTIVO"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$("#marcarTodo").change(function () {
	    if ($(this).is(':checked')) {
	        //$("input[type=checkbox]").prop('checked', true); //todos los check
	        $("input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
	    } else {
	        //$("input[type=checkbox]").prop('checked', false);//todos los check
	        $("input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
	    }
	});


	$('#btn_consultar').click(function(){

		var idpersona = $('#idpersonal').val();
				
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(idpersona == null){ $('#mensaje').html('Seleccione Persona'); }
		else{
			tablaactaretiro(idpersona);
			$("#btn_procesar").attr("href", "../acta/generaractaretiro/"+idpersona);
			$('#div_mensaje').hide();
		}
	});


	$('#btn_procesar').click(function(){

		var idpersona = $('#idpersonal').val();
		var formData = new FormData($("#form_actaretiro")[0]);
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(idpersona == null){ $('#mensaje').html('Seleccione Persona'); }
		else{	

			$.ajax({
				url: '../acta/updatetemporal',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);		
					if(data=='ok'){
						$('#div_mensaje').show();
						$('#mensaje').css("color", "blue");	
						$('#mensaje').html('Se realizo el retiro correctamente');
						$('#div_mensaje').hide(8000);
						var idpersona = $('#idpersonal').val();
						tablaactaretiro(idpersona);
					}else{
						$('#div_mensaje').show();
						$('#mensaje').css("color", "red");
						$('#mensaje').html(data);
					}
				}

			});

		}

	});


	/* **************************************************************************************************** */
    /*                                             HISTORIAL DE ACTAS
    /* **************************************************************************************************** */


    $('#idpersonal').change(function(){
		var idpersonal = $('#idpersonal').val();

		$.post('../acta/getcomponenteacta/' + idpersonal,
			function(data) {		
			$('#idcomponente').html(data);
		});

	});


	function tablahistorialacta(idpersona, idcomponente){
		$('#tablahistorialacta').dataTable().fnDestroy();		 	
		$('#tablahistorialacta').DataTable({

			//PARA EXPORTAR
			dom: "Bfrtip",
			buttons: [{
				extend: "copy",
				className: "btn-sm"
			}, {
				extend: "csv",
				className: "btn-sm"
			}, {
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,
			
			"order" : [ [ 9, "desc" ] ],
			"ajax" : "../acta/gethistorialacta/"+idpersona+"/"+idcomponente,
			"columns" : [
			{
				"data" : "IDACTIVO"
			},{
				"data" : "INVENTARIO"
			},{
				"data" : "COMPONENTE"
			},{
				"data" : "MODELO"
			},{
				"data" : "MARCA"
			},{
				"data" : "SERIE"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "ACTA"
			},{
				"data" : "IDUSUARIOCREACION"
			},{
				"data" : "FECHAOPERACION"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}


	$('#btn_consultar_historial_acta').click(function(){

		var idpersona = $('#idpersonal').val();
		var idcomponente = $('#idcomponente').val();
						
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(idpersona == null){ $('#mensaje').html('Seleccione Persona'); }
		else if(idcomponente == ''){ $('#mensaje').html('Seleccione Componente'); }
		else{
			tablahistorialacta(idpersona, idcomponente);
			$('#div_mensaje').hide();
		}
	});



});



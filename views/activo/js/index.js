// esta linea sirve para inicializar el jquery
$(document).on('ready',function(){

	/* ***********************************************************************************
								ACTIVOS DEL ACTUAL INVENTARIO
	************************************************************************************** */

	function tablaactivos(){
		$('#tablaactivos').dataTable().fnDestroy();		 	
		$('#tablaactivos').DataTable({

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
			"ajax" : "../activo/getactivos",
			"columns" : [{
				"data" : "NOMACTIVO"
			},{
				"data" : "MARCA"
			},{
				"data" : "SERIE"
			},{
				"data" : "MODELO"
			},{
				"data" : "ESTADO"
			},{
				"data" : "CAPACIDAD"
			},{
				"data" : "PERSONAL"
			},{
				"data" : "OFICINA"
			},{
				"data" : "IDPATRI"
			},{
				"data" : "ASIGNADO"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	tablaactivos();


	$('#btn_nuevo').click(function() {
		

		$('#serie').removeAttr("readonly")
		$('#btn_actualizar_activo').hide();
		$('#btn_asignar_activo').hide();		
		$('#btn_grabar_activo').show();

		$('#componente').val('').change();
		$('#modelo').val('');
		$('#marca').val('');
		$('#serie').val('');
		$('#capacidad').val('');
		$('#status').val('').change();
		$('#caducidad').val('');
		$('#tipo').val('').change();
		$('#medioingreso').val('').change();
		$('#codingreso').val('');
		$('#patrimonio').val('');
		$('#descripcion').val('');
		$('#title').html('Añadir Nuevo Activo');
	});

	$('#btn_grabar_activo').click(function(){

		var componente= $('#componente').val();
		var modelo= $('#modelo').val();
		var marca= $('#marca').val();
		var serie= $('#serie').val();
		var capacidad= $('#capacidad').val();
		var status= $('#status').val();
		var caducidad= $('#caducidad').val();
		var tipo= $('#tipo').val();
		var medioingreso= $('#medioingreso').val();
		var codingreso= $('#codingreso').val();
		var patrimonio= $('#patrimonio').val();
		var descripcion= $('#descripcion').val();
		//alert (codingreso);

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		//VALIDA LOS CAMPOS Y MUESTRA UN MENSAJE
		if(componente == ''){ $('#mensaje').html('Seleccione un componente');}
		else if(marca == ''){ $('#mensaje').html('Ingrese Marca');}
		else if(serie == ''){ $('#mensaje').html('Ingrese Serie');}
		else if(status == ''){ $('#mensaje').html('Seleccione Estado');}
		else if(tipo == ''){ $('#mensaje').html('Seleccione tipo');}
		else if(medioingreso == ''){ $('#mensaje').html('Seleccione medio de ingreso');}
		else if(codingreso == ''){ $('#mensaje').html('Ingrese codigo de ingreso');}
		else{
			$.post('../activo/addactivo',{
				componente : componente,
				modelo : modelo,
				marca : marca,
				serie : serie,
				capacidad : capacidad,
				status : status,
				caducidad : caducidad,
				tipo : tipo,
				medioingreso: medioingreso,
				codingreso: codingreso,
				patrimonio : patrimonio,
				descripcion : descripcion
			},function(data){
				//trim(data);
				//alert(data);
				console.log(data);
				//data = '1';
				if(data == 1){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Registro el activo Correctamente');
					$('#div_mensaje').hide(8000);
					$('#componente').val('').change();
					$('#modelo').val('');
					$('#marca').val('');
					$('#serie').val('');
					$('#capacidad').val('');
					$('#status').val('').change();
					$('#caducidad').val('');
					$('#tipo').val('').change();
					$('#medioingreso').val('').change();
					$('#codingreso').val('');
					$('#patrimonio').val('');
					$('#descripcion').val('');
					tablaactivos();					
				}else if(data == 0){
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}else{
					$('#mensaje').html(data);
				}
			})
		}
	})

	$("#tablaactivos tbody").on('click','button.editactivo',function(){
		$('#btn_actualizar_activo').show();
		$('#btn_grabar_activo').hide();
		$('#btn_asignar_activo').show();	
		$('#title').html('Actualizar Activo');

		var idactivo =  $(this).attr("id");	
		//alert(idactivo)
		var datos = { 'idactivo' : idactivo}

		$.ajax({
			url: '../activo/getactivo',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				$('#idactivo').val(data.IDACTIVO);
				$('#serie').attr("readonly","readonly");
				$('#componente').val(data.IDCOMPONENTE).change();
				//$('#componente option:eq('+data.IDCOMPONENTE+')').prop('selected', true).change();
				$('#modelo').val(data.MODELO);
				$('#marca').val(data.MARCA);
				$('#serie').val(data.SERIE);
				$('#medioingreso').val(data.MEDIOINGRESO).change();
				$('#codingreso').val(data.CODINGRESO);				
				$('#capacidad').val(data.CAPACIDAD);				
				$('#status').val(data.ESTADO).change();
				$('#caducidad').val(data.CADUCIDAD);
				$('#tipo').val(data.TIPO).change();
				$('#patrimonio').val(data.IDPATRIMONIO);
				$('#descripcion').val(data.DESCRIPCION);

				//modal asignamiento
				//alert(data.IDPERSONA +' - '+data.IDACTIVO);
				//alert(data.IDCOMPONENTE);
				$('#idactivo2').val(data.IDACTIVO);
				$('#idpersona').val(data.IDPERSONA).change();
				$('#getidinventario').val(data.IDINVENTARIO);							
			}
		});
	});

	$('#btn_actualizar_activo').click(function(){
		
		var idactivo= $('#idactivo').val();
		var componente= $('#componente').val();
		var modelo= $('#modelo').val();
		var marca= $('#marca').val();
		var serie= $('#serie').val();
		var capacidad= $('#capacidad').val();
		var status= $('#status').val();
		var caducidad= $('#caducidad').val();
		var tipo= $('#tipo').val();
		var medioingreso= $('#medioingreso').val();
		var codingreso= $('#codingreso').val();
		var patrimonio= $('#patrimonio').val();
		var descripcion= $('#descripcion').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(componente == ''){ $('#mensaje').html('Seleccione un componente');}
		else if(marca == ''){ $('#mensaje').html('Ingrese Marca');}
		else if(serie == ''){ $('#mensaje').html('Ingrese Serie');}
		else if(status == ''){ $('#mensaje').html('Seleccione Estado');}
		else if(tipo == ''){ $('#mensaje').html('Seleccione tipo');}
		else if(medioingreso == ''){ $('#mensaje').html('Seleccione medio de ingreso');}
		else if(codingreso == ''){ $('#mensaje').html('Ingrese codigo de ingreso');}
		else{
			$.post('../activo/updateactivo',{
				idactivo: idactivo,
				componente : componente,
				modelo : modelo,
				marca : marca,
				serie : serie,
				capacidad : capacidad,
				status : status,
				caducidad : caducidad,
				tipo : tipo,
				medioingreso: medioingreso,
				codingreso: codingreso,
				patrimonio : patrimonio,
				descripcion : descripcion
			},function(data){	
				//alert(data);
				console.log(data);
				if(data == 1){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se Actualizo Correctamente');
					$('#div_mensaje').hide(8000);
					tablaactivos();
				}else{
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}
			})
		}

	});

	$('#btn_asignar_activo').click(function(){

		var idactivo= $('#idactivo').val();

		$.post('../activo/verificarasignacion',{
				idactivo: idactivo
			},function(data){
				//alert(data);				
				if(data == 1){
					$('#btn_asignar').attr("disabled","disabled");
				}else{
					$('#btn_asignar').removeAttr("disabled")
				}
			})
	});

	$('#btn_asignar').click(function(){

		var idactivo= $('#idactivo2').val();
		var idpersona= $('#idpersona').val();
		var componente = $('#componente').val();
		//var status = $('#status').val();
		var idinventario = $('#getidinventario').val();
		

		$('#div_mensaje_asignar').show();
		$('#mensaje_asignar').css("color", "red");				

		//alert(idpersona+'/'+status);

		if(idpersona == ''){ $('#mensaje_asignar').html('Seleccione Persona');}
		else{
			$.post('../activo/asignaractivo',{
				idactivo : idactivo,
				idpersona : idpersona,
				componente : componente,
				//status : status,
				idinventario: idinventario
			},function(data){	
				alert(data);
				if(data == 1){
					$('#div_mensaje_asignar').show();
					$('#mensaje_asignar').css("color", "blue");	
					$('#mensaje_asignar').html('Se asigno Correctamente');
					$('#div_mensaje_asignar').hide(8000);
					tablaactivos();				
				}else{
					$('#mensaje_asignar').html('Ocurrio un Error Intente de Nuevo !!');
				}
			});
		}
		

	});

	/* ***********************************************************************************
							ACTIVOS DE LOS INVENTARIOS HISTORICOS
	************************************************************************************** */

	function tablaactivos_historicos(idinventario){
		$('#tablaactivos_historicos').dataTable().fnDestroy();		 	
		$('#tablaactivos_historicos').DataTable({

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
			"ajax" : "../activo/getactivos_historicos/"+idinventario,
			"columns" : [{
				"data" : "NOMACTIVO"
			},{
				"data" : "MARCA"
			},{
				"data" : "SERIE"
			},{
				"data" : "MODELO"
			},{
				"data" : "ESTADO"
			},{
				"data" : "PERSONAL"
			},{
				"data" : "IDPATRI"
			},{
				"data" : "USERCREACION"
			},{
				"data" : "FECHACREACION"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$('#btn_procesar_activos').click(function(){
		
		var idinventario = $('#idinventario').val();
		if(idinventario == ''){
			$('#div_mensaje_proceso').show();
			$('#mensaje_proceso').css("color", "red");
			$('#mensaje_proceso').html('Seleccione Algun inventario');
		}else{
			$('#div_mensaje_proceso').hide();
			tablaactivos_historicos(idinventario);
		}

	})

	/* ***********************************************************************************
							TRANSFERENCIA DE ACTIVOS
	************************************************************************************** */

	function tablatransferencia(idpersona){
		$('#tablatransferencia').dataTable().fnDestroy();		 	
		$('#tablatransferencia').DataTable({

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
			"ajax" : "../activo/getactivos_penultimo/"+idpersona,
			"columns" : [
			{
				"data" : "INPUT"
			},{
				"data" : "NOMACTIVO"
			},{
				"data" : "MARCA"
			},{
				"data" : "MODELO"
			},{
				"data" : "SERIE"
			},{
				"data" : "ESTADO"
			},{
				"data" : "IDPATRI"
			},{
				"data" : "NOMBREINVENTARIO"
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

	$('#btn_consultar_transferencia').click(function(){
		var idpersona = $('#idpersonal').val();

		$('#div_transfer').show();
		$('#transfer').css("color", "red");

		if(idpersona == null){ var idpersona = 'nada'; }
			//alert(idpersona);
			//if(idpersona == 'nada'){ $('#transfer').html('Seleccione Personal'); }

		tablatransferencia(idpersona);
		$('#div_transfer').hide();

		/*if(idpersona == null){ $('#transfer').html('Seleccione Personal'); }
		else{
			$('#div_transfer').hide();
			tablatransferencia(idpersona);
		}*/

	});
	
	$('#btn_transferir').click(function(){	

		var formData = new FormData($("#form_transferencia")[0]);

		$('#div_transfer').show();
		$('#transfer').css("color", "red");

		$.ajax({
				url: '../activo/transferactivos',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){

					if(data == 1){
						$('#div_transfer').show();
						$('#transfer').css("color", "blue");	
						$('#transfer').html('Se transfirio correctamente los Activos Seleccionados');
						$('#div_transfer').hide(4000);

						var idpersona = $('#idpersonal').val();
						tablatransferencia(idpersona);
						tablaactivos();
					}else{
						$('#transfer').html('No hay activos seleccionados');
					}
				}				
		});
	})

	
})
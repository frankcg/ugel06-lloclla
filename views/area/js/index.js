$(document).on('ready',function(){

	function tablaarea(){
		$('#tablaarea').dataTable().fnDestroy();		 	
		$('#tablaarea').DataTable({

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
			"ajax" : "../area/getareas",
			"columns" : [{
				"data" : "NOMBRE_AREA"
			},{
				"data" : "ESTADO"
			},{
				"data" : "DESCRIPCION"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablaarea();


	
	//BOTON NUEVA AREA
	$('#btn_nuevo').click(function() {

		$('#nombre').removeAttr("readonly")
		$('#btn_actualizar').hide();
		$('#btn_grabar').show();
		
		$('#nombre').val('');
		$('#descripcion').val('');
		$('#title').html('Añadir Nueva Area');
	});

	$('#btn_grabar').click(function(){

		var nombre = $('#nombre').val();
		var status = $('#status').val();
		var descripcion = $('#descripcion').val();

		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(nombre == '')
			{ $('#mensaje').html('Ingrese Nombre');}
		else{
			
			$.post('../area/addarea',{
				nombre : nombre,
				status : status,
				descripcion : descripcion

			},function(data){	
				//alert(data);
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se registro Correctamente');
					$('#div_mensaje').hide(6000);

					$('#apellido_pat').val('');
					$('#apellido_mat').val('');
					$('#nombre').val('');
					$('#dni').val('');
					$('#oficina').val('').change();
					
					// REFRESCA LA TABLA 
					tablaarea();

				}else if(data=='error'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "red");	
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
					$('#div_mensaje').hide(6000);
				}else{
					$('#div_mensaje').show();
					$('#mensaje').css("color", "red");	
					$('#mensaje').html(data);
					$('#div_mensaje').hide(6000);
				}
			})
		}
	});



	//BOTON EDITAR AREA
	$("#tablaarea tbody").on('click','button.editarea',function(){
		$('#btn_actualizar').show();
		$('#btn_grabar').hide();
		$('#title').html('Actualizar Area');

		var idarea =  $(this).attr("id");	
		//alert(idarea)
		var datos = {'idarea' : idarea}

		$.ajax({
			url: '../area/getarea',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				$('#nombre').attr("readonly","readonly");
				$('#idarea').val(data.IDAREA);
				$('#nombre').val(data.NOMBRE_AREA);
				$('#status').val(data.ESTADO).change();
				$('#descripcion').val(data.DESCRIPCION);
				
			}
		});
	});

	$('#btn_actualizar').click(function(){
		
		var idarea= $('#idarea').val();
		var nombre= $('#nombre').val();
		var status= $('#status').val();
		var descripcion= $('#descripcion').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(status == ''){ $('#mensaje').html('Seleccione Estado');}
		else{
			$.post('../area/updatearea',{
				idarea: idarea,
				nombre : nombre,
				status : status,
				descripcion : descripcion
			},function(data){	
				//alert(data);
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se Actualizo Correctamente');
					$('#div_mensaje').hide(8000);
					tablaarea();
				}else{
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}
			})
		}

	});



});
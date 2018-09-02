$(document).on('ready',function(){

	function tablaoficina(){
		$('#tablaoficina').dataTable().fnDestroy();		 	
		$('#tablaoficina').DataTable({

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
			"ajax" : "../oficina/getoficinas",
			"columns" : [{
				"data" : "NOMBRE_OFICINA"
			},{
				"data" : "ESTADO"
			},{
				"data" : "DESCRIPCION"
			},{
				"data" : "IDAREA"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablaoficina();


	
	//BOTON NUEVA oficina
	$('#btn_nuevo').click(function() {

		$('#nombre').removeAttr("readonly")
		$('#btn_actualizar').hide();
		$('#btn_grabar').show();
		
		$('#nombre').val('');
		$('#estado').val('').change();
		$('#descripcion').val('');
		$('#area').val('').change();
		$('#title').html('Añadir Nueva oficina');
	});

	$('#btn_grabar').click(function(){

		var nombre = $('#nombre').val();
		var status = $('#status').val();
		var area = $('#area').val();
		var descripcion = $('#descripcion').val();

		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(nombre == '')
			{ $('#mensaje').html('Ingrese Nombre');}
		else{
			
			$.post('../oficina/addoficina',{
				nombre : nombre,
				status : status,
				area : area,
				descripcion : descripcion

			},function(data){	
				//alert(data);
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se registro Correctamente');
					$('#div_mensaje').hide(6000);

					$('#nombre').val('');
					$('#estado').val('').change();
					$('#descripcion').val('');
					$('#area').val('').change();
					
					// REFRESCA LA TABLA 
					tablaoficina();

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



	//BOTON EDITAR oficina
	$("#tablaoficina tbody").on('click','button.editoficina',function(){
		$('#btn_actualizar').show();
		$('#btn_grabar').hide();
		$('#title').html('Actualizar oficina');

		var idoficina =  $(this).attr("id");	
		//alert(idoficina)
		var datos = {'idoficina' : idoficina}

		$.ajax({
			url: '../oficina/getoficina',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				$('#nombre').attr("readonly","readonly");
				$('#idoficina').val(data.IDOFICINA);
				$('#nombre').val(data.NOMBRE_OFICINA);
				$('#status').val(data.ESTADO).change();
				$('#area').val(data.IDAREA).change();
				$('#descripcion').val(data.DESCRIPCION);
				
			}
		});
	});

	$('#btn_actualizar').click(function(){
		
		var idoficina= $('#idoficina').val();
		var nombre= $('#nombre').val();
		var status= $('#status').val();
		var area= $('#area').val();
		var descripcion= $('#descripcion').val();
		
		//alert(idoficina);


		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(status == ''){ $('#mensaje').html('Seleccione Estado');}
		else{
			$.post('../oficina/updateoficina',{
				idoficina: idoficina,
				nombre : nombre,
				area : area,
				status : status,
				descripcion : descripcion
			},function(data){	
				
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se Actualizo Correctamente');
					$('#div_mensaje').hide(8000);
					tablaoficina();
				}else{
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}
			})
		}

	});



});

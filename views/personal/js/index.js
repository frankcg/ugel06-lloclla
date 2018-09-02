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
			"ajax" : "../personal/getpersonales",
			"columns" : [{
				"data" : "DNI"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "EMAIL"
			},{
				"data" : "TELEFONO"
			},{
				"data" : "NOMBRE_OFICINA"
			},{
				"data" : "STATUS"
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


	
	//BOTON NUEVO USUARIO
	$('#btn_nuevo').click(function() {
		$('#dni').removeAttr("readonly")
		$('#btn_actualizar').hide();
		$('#btn_grabar').show();
		
		$('#apellido_pat').val('');
		$('#apellido_mat').val('');
		$('#nombre').val('');
		$('#dni').val('');
		$('#email').val('');
		$('#telefono').val('');
		$('#oficina option:eq(0)').prop('selected', true).change();
		$('#title').html('Añadir Nuevo Personal');
	});

	$('#btn_grabar').click(function(){

		var ape_pat = $('#apellido_pat').val();
		var ape_mat = $('#apellido_mat').val();
		var nombre = $('#nombre').val();
		var dni = $('#dni').val();
		var email = $('#email').val();
		var telefono = $('#telefono').val();
		var long_dni = $('#dni').val().length;
		var oficina = $('#oficina').val();
		var status = $('#status').val();
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(ape_pat == ''){ $('#mensaje').html('Ingrese Apellido pat');}
		else if(ape_mat == ''){ $('#mensaje').html('Ingrese Apellido mat');}
		else if(nombre == ''){ $('#mensaje').html('Ingrese Nombre');}
		else if(dni == ''){ $('#mensaje').html('Ingrese DNI');}
		else if(long_dni < 8){ $('#mensaje').html('DNI Incorrecto');}
		else if(oficina == ''){ $('#mensaje').html('Seleccione Oficina');}
		else if(status == ''){ $('#mensaje').html('Seleccione Status');}
		else{

			$.post('../personal/addpersonal',{
				ape_pat : ape_pat,
				ape_mat : ape_mat,
				nombre : nombre,
				dni : dni,
				email: email,
				telefono : telefono,
				oficina : oficina,
				status : status
			},function(data){	
				alert(data);
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
					tablapersonal();
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


	$("#tablapersonal tbody").on('click','button.editpersonal',function(){
		$('#btn_grabar').hide();
		$('#btn_actualizar').show();
		
		$('#title').html('Editar Personal');
		var codpersona =  $(this).attr("id");		
		var datos = { 'codpersona' : codpersona}

		$.ajax({
			url: '../personal/getpersonal',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){				
				$('#idpersona').val(data.IDPERSONA);						
				$('#dni').attr("readonly","readonly");						
				$('#apellido_pat').val(data.AP_PATERNO);
				$('#apellido_mat').val(data.AP_MATERNO);
				$('#nombre').val(data.NOMBRES);
				$('#dni').val(data.DNI);
				$('#email').val(data.CORREO);
				$('#telefono').val(data.TELEFONO);
				$('#oficina').val(data.IDOFICINA).change();
				$('#status').val(data.ESTADO).change();
			}				
		});
	});

	$('#btn_actualizar').click(function(){

		var idpersona = $('#idpersona').val();
		var ape_pat = $('#apellido_pat').val();
		var ape_mat = $('#apellido_mat').val();
		var nombre = $('#nombre').val();
		var dni = $('#dni').val();
		var email = $('#email').val();
		var telefono = $('#telefono').val();
		var oficina = $('#oficina').val();
		var status = $('#status').val();
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(ape_pat == ''){ $('#mensaje').html('Ingrese Apellido pat');}
		else if(ape_mat == ''){ $('#mensaje').html('Ingrese Apellido mat');}
		else if(nombre == ''){ $('#mensaje').html('Ingrese Nombre');}
		else if(dni == ''){ $('#mensaje').html('Ingrese DNI');}
		else if(oficina == ''){ $('#mensaje').html('Seleccione Oficina');}
		else if(status == ''){ $('#mensaje').html('Seleccione Status');}
		else{

			$.post('../personal/updatepersonal',{
				idpersona : idpersona,
				ape_pat : ape_pat,
				ape_mat : ape_mat,
				nombre : nombre,
				dni : dni,
				email:email,
				telefono: telefono,
				oficina : oficina,
				status : status
			},function(data){	
				//alert(data);
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se actualizo Correctamente');
					$('#div_mensaje').hide(6000);
					tablapersonal();
				}else{
					('#div_mensaje').show();
					$('#mensaje').css("color", "red");	
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
					$('#div_mensaje').hide(6000);
				}
			})
		}

	})




});
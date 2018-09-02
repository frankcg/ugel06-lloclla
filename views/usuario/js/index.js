$(document).on('ready',function(){

	/* ********************************************************************************************************************************
														MODULO USUARIO
	******************************************************************************************************************************** */
	 

	// LISTA TODOS LOS USUARIOS DE LA BD
	function tablausuarios(){
		$('#tablausuarios').dataTable().fnDestroy();		 	
		$('#tablausuarios').DataTable({

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
			"ajax" : "../usuario/getusuarios",
			"columns" : [{
				"data" : "IDUSUARIO"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "AREA"
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
	
	tablausuarios();
	
	//BOTON NUEVO USUARIO
	$('#btn_nuevo').click(function() {
		$('#usuario').removeAttr("readonly")
		$('#dni').removeAttr("readonly")
		$('#btn_actualizar').hide();
		$('#btn_acceso').hide();
		$('#btn_grabar_usuario').show();
		$('#usuario').val('');
		$('#apellido_pat').val('');
		$('#apellido_mat').val('');
		$('#nombre').val('');
		$('#dni').val('');
		$('#email').val('');
		$('#telefono').val('');
		$('#oficina option:eq(0)').prop('selected', true).change();
		$('#password').val('');
		$('#password2').val('');
		$('#title').html('Añadir Nuevo Usuario');
	});

	//GRABAR NUEVO USUARIO
	$('#btn_grabar_usuario').click(function(){
		var usuario = $('#usuario').val();
		var ape_pat = $('#apellido_pat').val();
		var ape_mat = $('#apellido_mat').val();
		var nombre = $('#nombre').val();
		var dni = $('#dni').val();
		var email = $('#email').val();
		var telefono = $('#telefono').val();
		var oficina = $('#oficina').val();
		var password = $('#password').val();
		var password2 = $('#password2').val();
		var status = $('#status').val();
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				

		if(usuario == ''){ $('#mensaje').html('Ingrese Usuario');}
		else if(ape_pat == ''){ $('#mensaje').html('Ingrese Apellido pat');}
		else if(ape_mat == ''){ $('#mensaje').html('Ingrese Apellido mat');}
		else if(nombre == ''){ $('#mensaje').html('Ingrese Nombre');}
		else if(dni == ''){ $('#mensaje').html('Ingrese DNI');}
		else if(oficina == ''){ $('#mensaje').html('Seleccione Oficina');}
		else if(password == ''){ $('#mensaje').html('Ingrese Password');}
		else if(password2 == ''){ $('#mensaje').html('Repita Password');}
		else if(password != password2){ $('#mensaje').html('Las contraseñas no coinciden');}
		else if(status == ''){ $('#mensaje').html('Seleccione Status');}
		else{
			$.post('../usuario/addusuario',{
				usuario : usuario,
				ape_pat : ape_pat,
				ape_mat : ape_mat,
				nombre : nombre,
				dni : dni,
				email : email,
				telefono : telefono,
				oficina : oficina,
				password : password,
				status : status
			},function(data){	
				//alert(data);
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Registro el usuario Correctamente');
					$('#div_mensaje').hide(8000);
					$('#usuario').val('');
					$('#apellido_pat').val('');
					$('#apellido_mat').val('');
					$('#nombre').val('');
					$('#dni').val('');
					$('#email').val('');
					$('#telefono').val('');
					$('#oficina').val('').change();
					$('#password').val('');
					$('#password2').val('');					
					tablausuarios();
				}else if(data=='error'){
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}else{
					$('#mensaje').html(data);
				}
			})
		}
	});

	//TRAER EL USUARIO AL MODAL
	$("#tablausuarios tbody").on('click','button.editusuario',function(){
		$('#btn_grabar_usuario').hide();
		$('#btn_actualizar').show();
		$('#btn_acceso').show();
		$('#title').html('Editar Usuario');
		var idusuario =  $(this).attr("id");		
		var datos = { 'idusuario' : idusuario}

		$.ajax({
			url: '../usuario/getusuario',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				$('#usuario').attr("readonly","readonly");
				$('#usuario').val(data.IDUSUARIO);					
				$('#apellido_pat').val(data.AP_PATERNO);
				$('#apellido_mat').val(data.AP_MATERNO);
				$('#nombre').val(data.NOMBRES);
				$('#dni').attr("readonly","readonly");
				$('#dni').val(data.DNI);								
				$('#telefono').val(data.TELEFONO);	
				$('#email').val(data.CORREO);	
				$('#oficina').val(data.IDOFICINA).change();	
				//alert(data.ESTADO);
				$('#status').val(data.ESTADO).change();
				$('#password').val(data.CONTRASENIA);	
				$('#password2').val(data.CONTRASENIA);
			}				
		});
	});

	//ACTUALIZAR USUARIO
	$('#btn_actualizar').click(function(){

		var usuario = $('#usuario').val();
		var ape_pat = $('#apellido_pat').val();
		var ape_mat = $('#apellido_mat').val();
		var nombre = $('#nombre').val();
		var dni = $('#dni').val();
		var long_dni = $('#dni').val().length;
		var email = $('#email').val();
		var telefono = $('#telefono').val();
		var oficina = $('#oficina').val();
		var password = $('#password').val();
		var password2 = $('#password2').val();
		var status = $('#status').val();
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");				
		
		if(usuario == ''){ $('#mensaje').html('Ingrese Usuario');}
		else if(ape_pat == ''){ $('#mensaje').html('Ingrese Apellido Paterno');}
		else if(ape_mat == ''){ $('#mensaje').html('Ingrese Apellido Materno');}
		else if(nombre == ''){ $('#mensaje').html('Ingrese Nombre');}
		else if(dni == ''){ $('#mensaje').html('Ingrese DNI');}
		else if(long_dni < 8){ $('#mensaje').html('DNI Incorrecto');}
		else if(oficina == ''){ $('#mensaje').html('Seleccione Oficina');}
		else if(password == ''){ $('#mensaje').html('Ingrese Password');}
		else if(password2 == ''){ $('#mensaje').html('Repita Password');}
		else if(password != password2){ $('#mensaje').html('Las contraseñas no coinciden');}
		else if(status == ''){ $('#mensaje').html('Seleccione Status');}
		else{
			$.post('../usuario/updateusuario',{
				usuario : usuario,
				ape_pat : ape_pat,
				ape_mat : ape_mat,
				nombre : nombre,
				dni : dni,
				email : email,
				telefono : telefono,
				oficina : oficina,
				password : password,
				status : status
			},function(data){
				//alert(data);
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se Actualizo Correctamente');
					$('#div_mensaje').hide(8000);										
					tablausuarios();
				}else{
					$('#mensaje').html('Ocurrio un error. Intente de nuevo');
				}
			})
		}
	});

	//DESHABILITAR USUARIO
	$("#tablausuarios tbody").on('click','button.delusuario',function(){

		var idusuario =  $(this).attr("id");

		$.confirm({
			title: 'Inactivar Usuario !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('../usuario/delusuario',{
					idusuario : idusuario
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Inactivo Correctamente !!');						
						tablausuarios();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});				

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	/* ********************************************************************************************************************************
																MODULO PERMISOS
	******************************************************************************************************************************** */

	//OBTENER PERMISOS DEL USUARIO
	$('#btn_acceso').click(function(){

		$('#lectura').prop('checked', false);
		$('#escritura').prop('checked', false);

		var idusuario = $('#usuario').val();
		var datos = { 'idusuario' : idusuario}
		
		$.ajax({
			url: '../usuario/getaccesosmodulo',  
			type: 'POST',
			data: datos,
			cache: false,
			dataType:'json',
			success: function(data){

				var lectura = data.LECTURA;	//alert('lectura: '+lectura);
				var escritura = data.ESCRITURA;	//alert('escritura: '+escritura);
				
				if(lectura == '1'){
					$('#check_lectura').prop('checked', true);
				}else{
					$('#check_lectura').prop('checked', false);
				}
				if(escritura == '1'){
					$('#check_escritura').prop('checked', true);
				}else{
					$('#check_escritura').prop('checked', false);
				}
				var perfil = data.IDPERFIL;
				$('#idperfil').val(perfil).change();
			}				
		});
	});

	//GRABAR Y/O ACTUALIZAR PERMISOS DEL USUARIO
	$('#btn_grabar_permisos').click(function(){

		var idperfil = $('#idperfil').val();
		var lectura = $('input:checkbox[name=check_lectura]:checked').val();
		var escritura = $('input:checkbox[name=check_escritura]:checked').val();
		var usuario = $('#usuario').val();

		if(idperfil == ''){ $('#mensaje').html('Seleccione Perfil');}
		else if(lectura == '' &&  escritura == ''){ $('#mensaje').html('Seleccione algun permiso');}
		else{
			$.post('../usuario/addpermisos',{
				idperfil : idperfil,
				lectura : lectura,
				escritura : escritura,
				usuario : usuario
			},function(data){
				if(data=='ok'){
						$('#div_mensaje_permiso').show();
						$('#mensaje_permiso').css("color", "blue");	
						$('#mensaje_permiso').html('Se registro Correctamente');
						$('#div_mensaje_permiso').hide(8000);
						tablaprofiles();   
				}else{
						$('#mensaje_permiso').html('Ha ocurrido un Error. Intente de Nuevo!!');
				}				
			});
		}
	});	

	/* ********************************************************************************************************************************
																MODULO PERFIL
	******************************************************************************************************************************** */

	function comoboperfil(){

		$.post('../usuario/getcomoboperfil',{
		},function(data){
			$('#idperfil').html(data);
		});


	}

	comoboperfil();

	function tablaprofiles(){
		$('#tablaperfiles').dataTable().fnDestroy();		 	
		$('#tablaperfiles').DataTable({

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

			"order" : [ [ 0, "asc" ] ],
			"ajax" : "../usuario/getprofiles",
			"columns" : [{
				"data" : "IDPERFIL"
			},{
				"data" : "CANTMODULOS"
			},{
				"data" : "IDUSUARIOCREACION"
			},{
				"data" : "OPCIONES"
			},			
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}   
	
	tablaprofiles();

	// NUEVO PERFIL
	$('#btn_nuevo_perfil').click(function(){
		$('#btn_update_perfil').hide();
		$('#btn_grabar_perfil').show();
		$('#title_profile').html('Agregar Perfil');
		$('#perfil').removeAttr("readonly")
		$('#perfil').val('');
		$('#idmodulo').val('').change();
		// traer todos los modulos para poder seleccionar
		$.post('../usuario/getaccesoprofilenew',{			
		},function(data){
			$('#idmodulo').html(data);
		});
	});

	// GUARDAR PERFIL
	$('#btn_grabar_perfil').click(function(){
		var nomperfil = $('#perfil').val();
		var idmodulo = $('#idmodulo').val();
		var formData = new FormData($("#form_perfiles")[0]);

		if(nomperfil == ''){ $('#mensaje_profile').html('Ingrese nombre del Perfil');}
		else if(idmodulo == null){ $('#mensaje_profile').html('Seleccione Modulos');}
		else{
			$.ajax({
				url: '../usuario/addprofile',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);				
					if(data=='ok'){
						$('#div_mensaje_profile').show();
						$('#mensaje_profile').css("color", "blue");	
						$('#mensaje_profile').html('Se registro Correctamente');
						$('#div_mensaje_profile').hide(8000);
						tablaprofiles();  
						comoboperfil();
					}else if(data=='error'){
						$('#mensaje_profile').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#mensaje_profile').html(data);
					}					
				}				
			});
		}
	})	

	// TRAE DATOS DEL PERFIL
	$("#tablaperfiles tbody").on('click','button.editprofile',function(){

		$('#title_profile').html('Editar Perfil');
		$('#btn_grabar_perfil').hide();
		$('#btn_update_perfil').show();
		$('#perfil').attr('readonly','readonly');	

		var idperfil =  $(this).attr("id");
		var datos = { 'idperfil' : idperfil}
		
		// TRAER EL NOMBRE DEL PERFIL AL MODAD
		$.ajax({
			url: '../usuario/getaccesoprofile',  
			type: 'POST',
			data: datos,
			cache: false,
			dataType:'json',
			success: function(data){
				$('#perfil').val(data.NOMBRE_PERFIL);					
				$('#getidperfil').val(data.IDPERFIL);					
			}				
		});

		// TRAE LOS MODULOS RELACIONADOS AL PERFIL
		$.post('../usuario/getaccesoprofile2',{
			idperfil : idperfil
		},function(data){
			$('#idmodulo').html(data);
		});

	});

	//ACTUALIZAR PERFIL
	$('#btn_update_perfil').click(function(){
		var nomperfil = $('#perfil').val();
		var idmodulo = $('#idmodulo').val();
		
		var formData = new FormData($("#form_perfiles")[0]);

		if(nomperfil == ''){ $('#mensaje_profile').html('Ingrese nombre del Perfil');}
		else if(idmodulo == null){ $('#mensaje_profile').html('Seleccione Modulos');}
		else{
			$.ajax({
				url: '../usuario/updateprofile',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){	
					//alert(data)				
					if(data=='ok'){
						$('#div_mensaje_profile').show();
						$('#mensaje_profile').css("color", "blue");	
						$('#mensaje_profile').html('Se registro Correctamente');
						$('#div_mensaje_profile').hide(8000);
						tablaprofiles();   
					}else{
						$('#mensaje_profile').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}					
				}				
			});
		}
	});
	
	// ELIMINAR PERFIL
	$("#tablaperfiles tbody").on('click','button.delprofile',function(){
		
		var idperfil =  $(this).attr("id");
		$.confirm({
			title: 'Eliminar Perfil !!!',
			content: 'Desea Continuar ??',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){

				$.post('../usuario/deleteprofile',{
					idperfil :  idperfil
				},function(data){
					//alert(data);
					if(data == 'ok'){
						$.alert('Se elimino Correctamente !!');						
						tablaprofiles();
						comoboperfil();
					}else if(data == 'error'){
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}else{
						$.alert(data);
					}			
				});
			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});	
	});

	
})
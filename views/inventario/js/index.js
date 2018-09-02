$(document).on('ready',function(){



	function tablainventario(){
		$('#tablainventario').dataTable().fnDestroy();		 	
		$('#tablainventario').DataTable({

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
			
			"order" : [ [ 3, "des" ] ],
			"ajax" : "../inventario/getinventarios",
			"columns" : [{
				"data" : "NOM_INVENTARIO"
			},{
				"data" : "FECHAINICIO"
			},{
				"data" : "FECHAFIN"
			},{
				"data" : "STATUS"
			},{
				"data" : "DESCRIPCION"
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
	
	tablainventario();

	$('#btn_nuevo').click(function(){
		$('#title').html('Añadir Nuevo Inventario');
		$('#btn_grabar_inventario').show();
		$('#btn_actualizar').hide();
		$('#nombre').val('');
		$('#fechainicio').val('');
		$('#div_mensaje').show();
		$('#mensaje').html('');
		$('#descripcion').val('');
	})


	$('#btn_grabar_inventario').click(function(){
		
		var fechaactual = new Date().toJSON().slice(0,10)	
		//alert(fechaactual);	
		var nombre =  $('#nombre').val();
		var fechainicio =  $('#fechainicio').val();
		var descripcion =  $('#descripcion').val();
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");

		if(nombre == ''){ $('#mensaje').html('Ingrese Nombre del Inventario');}
		else if(nombre.length <= 5){ $('#mensaje').html('El nombre debe contener minimo 6 caracteres');}
		else if(fechainicio == ''){ $('#mensaje').html('Ingrese Fecha de Inicio');}
		else if(fechainicio < fechaactual){ $('#mensaje').html('Ingrese una Fecha Inicio correcta');}
		else{
			$.post('../inventario/addinventario',{
				nombre : nombre,
				fechainicio :  fechainicio,
				descripcion :  descripcion
			},function (data){
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se registro Correctamente');
					$('#div_mensaje').hide(8000);
					$('#nombre').val('');
					$('#fechainicio').val('');									
					tablainventario();
				}else if(data=='error'){
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}else{
					$('#mensaje').html(data);
				}				
			});
		}		
	})

	//CONCLUR INVENTARIO
	$("#tablainventario tbody").on('click','button.editinventario',function(){

		$('#title').html('Actualizar Inventario');
		$('#btn_grabar_inventario').hide();
		$('#btn_actualizar').show();

		var idinventario =  $(this).attr("id");

		var datos = { 'idinventario' : idinventario}

		$.ajax({
			url: '../inventario/getinventario',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				//$('#usuario').attr("readonly","readonly");
				$('#nombre').val(data.NOM_INVENTARIO);					
				$('#fechainicio').val(data.FECHAINICIO);
				$('#idinventario').val(data.IDINVENTARIO);				
			}
		});

		
	});

	//CONCLUR INVENTARIO
	$("#tablainventario tbody").on('click','button.concluir',function(){

		var idinventario =  $(this).attr("id");

		$.confirm({
			title: 'Concluir Inventario !!',
			content: '¿Desea Continuar? ',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('../inventario/conluirinventario',{
					idinventario : idinventario
				},function(data){
					//alert(data);
					if(data == 'ok'){
						$.alert('Se Concluyó Correctamente !!');						
						tablainventario();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});				

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	//ACTUALIZAR INVENTARIO
	$('#btn_actualizar').click(function(){
		
		var fechaactual = new Date().toJSON().slice(0,10)		
		var nombre =  $('#nombre').val();
		var fechainicio =  $('#fechainicio').val();
		var idinventario = $('#idinventario').val();
		var descripcion = $('#descripcion').val();
		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");

		if(nombre == ''){ $('#mensaje').html('Ingrese Nombre del Inventario');}
		else if(nombre.length <= 5){ $('#mensaje').html('El nombre debe contener minimo 5 caracteres');}
		else if(fechainicio == ''){ $('#mensaje').html('Ingrese Fecha de Inicio');}
		else if(fechainicio < fechaactual){ $('#mensaje').html('Ingrese una Fecha Inicio correcta');}
		else{
			$.post('../inventario/updateinventario',{
				nombre : nombre,
				fechainicio :  fechainicio,
				descripcion : descripcion,
				idinventario : idinventario
			},function (data){
				if(data == 'ok'){
					$('#div_mensaje').show();
					$('#mensaje').css("color", "blue");	
					$('#mensaje').html('Se actualizo Correctamente');
					$('#div_mensaje').hide(8000);												
					tablainventario();
				}else if(data=='error'){
					$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
				}else{
					$('#mensaje').html(data);
				}				
			});
		}		
	})



})


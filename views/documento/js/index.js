$(document).on('ready',function(){

	function tabladocumentos(){
		$('#tabladocumentos').dataTable().fnDestroy();		 	
		$('#tabladocumentos').DataTable({

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
			"ajax" : "../documento/getdocumentos",
			"columns" : [{
				"data" : "PERSONAL"
			},{
				"data" : "TIPO"
			},{
				"data" : "DESCRIPCION"
			},{
				"data" : "IDUSUARIOCREACION"
			},{
				"data" : "FECHACREACION"
			},{
				"data" : "ADJUNTO"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
		tabladocumentos();
	



	
	//BOTON NUEVO DOCUMENTO
	$('#btn_nuevo').click(function() {

		$('#btn_actualizar').hide();
		$('#btn_grabar').show();
		$('#archivo').hide();
		
		$('#persona').val('').change();
		$('#tipo').val('').change();
		$('#descripcion').val('');
		$('#adjunto').val('');
		$('#title').html('Añadir Nuevo Documento');
	});


	$('#btn_grabar').click(function(){

		var persona = $('#persona').val();		
		var tipo = $('#tipo').val();
		var adjunto = $('#adjunto').val();
		//alert (persona+'-'+tipo+'-'+adjunto);
		//exit();
		var formData = new FormData($("#form_documento")[0]);
		var extensiones = adjunto.substring(adjunto.lastIndexOf("."));
		//alert (adjunto);

		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(persona == ''){ $('#mensaje').html('Seleccione Persona'); }
		else if(tipo == ''){ $('#mensaje').html('Seleccione Tipo'); }
		else if(adjunto == ''){ $('#mensaje').html('Seleccione Archivo Escaneado'); }
		else if(extensiones != ".pdf"){ $('#mensaje').html('El archivo no es un PDF <br> Esta intentando guardar un Archivo de Extension: <strong> <br>'+extensiones+'</strong>');}
		else{

			$.ajax({
				url: '../documento/adddocumento',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data)
				{
					//alert(data);		
					if(data==1)
					{
						$('#div_mensaje').show();
						$('#mensaje').css("color", "blue");	
						$('#mensaje').html('Se registro correctamente');
						$('#div_mensaje').hide(8000);

						$('#persona').val('').change();
						$('#tipo').val('').change();
						$('#descripcion').val('');
						$('#adjunto').val('');
						
						// REFRESCA LA TABLA 
						tabladocumentos();

					}
					else
					{
						$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
					}	
				}
			});
			}
	});

//BOTON EDITAR DOCUMENTO
	$("#tabladocumentos tbody").on('click','button.editdocumento',function(){
		$('#btn_actualizar').show();
		$('#btn_grabar').hide();
		$('#archivo').show();
		$('#title').html('Actualizar Documento');

		var iddocumento =  $(this).attr("id");
		//alert(iddocumento);
		var datos = {'iddocumento' : iddocumento}
		// var formData= new FormData(iddocumento);

		$.ajax({
				url: '../documento/getdocumento',  
				type: 'POST',
				data: datos,
				cache: false,
      			dataType:'json',
      			//dataType:'text', comprobar errores
			success: function(data){
				//alert (data);
				console.log(data);
				$('#direccion').val(data.ADJUNTO);
				$('#archivo').html('<label><a name="pdf" class="btn btn-primary btn-circle btn-lg" href="../'+data.ADJUNTO+'" target="_blank"><i class="fa fa-file-pdf-o"></i></a></label>');
				$('#iddocumento').val(data.IDDOCUMENTO);
				$('#persona').val(data.IDPERSONA).change();
				$('#tipo').val(data.TIPO).change();
				$('#descripcion').val(data.DESCRIPCION);
				$('#adjunto').val(data.ADJUNTO);


			},
			error: function(data){
				console.log('error')
				console.log(data)
			}

		});
	});

	$('#btn_actualizar').click(function(){

		var iddocumento = $('#iddocumento').val();
		var persona = $('#persona').val();
		var tipo = $('#tipo').val();
		var adjunto = $('#adjunto').val();

		//alert (direccion);
		
		var formData = new FormData($("#form_documento")[0]);
		var extensiones = adjunto.substring(adjunto.lastIndexOf("."));
		//alert (adjunto);

		
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	


		
		if(persona == ''){ $('#mensaje').html('Seleccione Persona'); }
		else if(tipo == ''){ $('#mensaje').html('Seleccione Tipo'); }
		
		else{

			$.ajax({
				url: '../documento/updatedocumento',
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data)
				{
					//alert(data);		
					if(data==1)
					{
						$('#div_mensaje').show();
						$('#mensaje').css("color", "blue");	
						$('#mensaje').html('Se actualizo correctamente');
						$('#div_mensaje').hide(8000);
						
						// REFRESCA LA TABLA 
						tabladocumentos();

					}
					else
					{
						$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
					}	
				}
			});
			}
	});


 $("input[type=file]").change(function(){

 		var iddocumento = $('#iddocumento').val();
 		var adjunto = $('#adjunto').val();
		//alert (persona+'-'+tipo+'-'+adjunto);
		//exit();
		var formData = new FormData($("#form_documento")[0]);
		var extensiones = adjunto.substring(adjunto.lastIndexOf("."));
		//alert (adjunto);

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(extensiones != ".pdf"){ $('#mensaje').html('El archivo no es un PDF <br> Esta intentando guardar un Archivo de Extension: <strong> <br>'+extensiones+'</strong>');}
		else
        
        $.ajax({
				url: '../documento/updatedocumentodigital',
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data)
				{
					console.log(data);
					// undefined= no existe valor - js
					// isset es php : para ver si contiene data
					if(data != undefined)
					{
						$('#div_mensaje').show();
						$('#mensaje').css("color", "blue");	
						$('#mensaje').html('Se cargo archivo correctamente');
						$('#div_mensaje').hide(8000);

						// REFRESCA LA TABLA Y EL BOTON PDF
						//$('#archivo').html(data.ADJUNTO);
						$('#archivo').html('<label><a name="pdf" class="btn btn-primary btn-circle btn-lg" href="../'+data+'" target="_blank"><i class="fa fa-file-pdf-o"></i></a></label>');
						tabladocumentos();
						

					}
					else
					{
						$('#mensaje').html('Ocurrio un Error Intente de Nuevo !!');
					}	
				}
			});
    });
})

// esta linea sirve para inicializar el jquery
$(document).on('ready',function(){

	function tablainventario(inventario1, inventario2){
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
			
			"order" : [ [ 1, "asc" ] ],
			"ajax" : "../exactitud/exactitud/"+inventario1+'/'+inventario2,
			"columns" : [{
				"data" : "NOMBRE1"
			},{
				"data" : "CANT1"
			},{
				"data" : "NOMBRE2"
			},{
				"data" : "CANT2"
			},{
				"data" : "DIFERENCIA"
			},{
				"data" : "PORCENTAJE"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	//tablainventario();
	//
	function tablainventario_dif(inventario1, inventario2){
		$('#tablainventario_dif').dataTable().fnDestroy();		 	
		$('#tablainventario_dif').DataTable({

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
			"ajax" : "../exactitud/getdiferencia/"+inventario1+"/"+inventario2,
			"columns" : [{
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


	$('#btn_procesar').click(function(){
		var inventario1 = $('#inventario1').val();
		var inventario2 = $('#inventario2').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(inventario1 == null){ $('#mensaje').html('Seleccione Inventario 1'); }
		else if(inventario2 == null){ $('#mensaje').html('Seleccione Inventario 2'); }
		else if(inventario1 == inventario2){ $('#mensaje').html('No puede Seleccionar inventarios iguales'); }
		else{
			tablainventario(inventario1, inventario2);
			$('#div_mensaje').hide();
			tablainventario_dif(inventario1, inventario2);

			var datos = { 'inventario1' : inventario1, 'inventario2' : inventario2 };

			$.ajax({
				url: '../exactitud/getnombre',  
				type: 'POST',
				data:  datos, 
				cache: false,
				dataType:'json',				
				//dataType:'text', comprobar errores
				success: function(data){
					//alert(data);
					$('#title_inv1').html(data.NOM_INVENTARIO1);
					$('#title_inv2').html(data.NOM_INVENTARIO2);					
				}
			});
		}
	})

	$('#btn_generar_pdf').click(function(){

		var inventario1 = $('#inventario1').val();
		var inventario2 = $('#inventario2').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(inventario1 == null){ $('#mensaje').html('Seleccione Inventario 1'); }
		else if(inventario2 == null){ $('#mensaje').html('Seleccione Inventario 2'); }
		else if(inventario1 == inventario2){ $('#mensaje').html('No puede Seleccionar inventarios iguales'); }
		else{
			$('#div_mensaje').hide();
			$("#btn_generar_pdf").attr("href", "../exactitud/generarpdf/"+inventario1+'/'+inventario2);
			
			
		}

	})

});
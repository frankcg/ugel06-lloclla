$(document).on('ready',function(){

	$("#marcarTodo").change(function () {
	    if ($(this).is(':checked')) {
	        //$("input[type=checkbox]").prop('checked', true); //todos los check
	        $("input[type=checkbox]").prop('checked', true); //solo los del objeto #diasHabilitados
	    } else {
	        //$("input[type=checkbox]").prop('checked', false);//todos los check
	        $("input[type=checkbox]").prop('checked', false);//solo los del objeto #diasHabilitados
	    }
	});


function tablabaja(idinventario){
		$('#tablainforme').dataTable().fnDestroy();		 	
		$('#tablainforme').DataTable({

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
			
			"order" : [ [ 7, "desc" ] ],
			"lengthMenu": [100],
			"ajax" : "../baja/getbaja/"+idinventario,
			"columns" : [
			{
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
				"data" : "ESTADO"
			},{
				"data" : "CAPACIDAD"
			},{
				"data" : "MEDIOINGRESO"
			},{
				"data" : "IDPATRIMONIO"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}


	$('#btn_consultar').click(function(){

		var idinventario = $('#idinventario').val();
								
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(idinventario == null){ $('#mensaje').html('Seleccione Inventario'); }
		else{
			tablabaja(idinventario);
			$('#div_mensaje').hide();
		}
	});

	/*$('#idinventario').change(function(){
		var idinventario = $('#idinventario').val();
		$("#btn_informe").attr("href", "../baja/informebaja/"+idinventario);
	})*/

	$("#btn_procesar").click(function(){

		var idinventario = $('#idinventario').val();
		var idactivo = $('#activo').val();
		// 	alert(idactivo);
		var formData = new FormData($("#form_baja")[0]);

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");
		
		if(idinventario == null){ $('#mensaje').html('Seleccione Inventario'); }
		else if(idactivo == null){ $('#mensaje').html('Seleccione Activo'); }
		else{
			$('#div_mensaje').hide();

			$.confirm({
			title: 'Baja de Activo !!',
			content: 'Seleccione Motivo de Baja<br><select id="motivo" name="motivo" class="form-control" ><option selected="" disabled="">Seleccione</option><option value="4">INACTIVO</option><option value="5">PERDIDO</option><option value="6">RETIRO</option></select>',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				var motivo = $('#motivo').val();
				formData.append("motivo", motivo);

				$.ajax({
					url: '../baja/addbaja',  
					type: 'POST',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data){
						//alert(data);							
						if(data==1){
							$.alert('Se realizo la baja correctamente !!');
							tablabaja(idinventario);
						}else{
							$.alert('Ocurrio un error, intente de nuevo !!');	
						}
					}
				});
			},cancel: function(){
				$.alert('Cancelado');		        
				}
			});
		}

	});

	

});
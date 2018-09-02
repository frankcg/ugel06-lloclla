// esta linea sirve para inicializar el jquery
$(document).on('ready',function(){

	function tablaindicador(idinventario){
		$('#tablaindicador').dataTable().fnDestroy();		 	
		$('#tablaindicador').DataTable({

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
			"ajax" : "../vejez/getvejez/"+idinventario,
			"columns" : [{
				"data" : "NOMBRE"
			},{
				"data" : "ACTIVO"
			},{
				"data" : "DANADO"
			},{
				"data" : "OBSOLETO"
			},{
				"data" : "VENCIDO"
			},{
				"data" : "TOTAL"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	//tablainventario();


	$('#btn_procesar').click(function(){
		var idinventario = $('#inventario').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(idinventario == null){ $('#mensaje').html('Seleccione Inventario'); }
		else{
			tablaindicador(idinventario);
			$('#div_mensaje').hide();			
		}
	})

	$('#btn_generar_pdf').click(function(){

		var idinventario = $('#inventario').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(idinventario == null){ $('#mensaje').html('Seleccione Inventario'); }
		else{
			$('#div_mensaje').hide();
			$("#btn_generar_pdf").attr("href", "../vejez/generarpdf/"+idinventario);			
		}

	})

});
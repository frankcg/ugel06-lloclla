// esta linea sirve para inicializar el jquery
$(document).on('ready',function(){

	function tablaindicador(fechainicio, fechafinal){
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
			"ajax" : "../reportebajas/getreportebaja/"+fechainicio+'/'+fechafinal,
			"columns" : [{
				"data" : "NOMBRE"
			},{
				"data" : "MARCA"
			},{
				"data" : "MODELO"
			},{
				"data" : "SERIE"
			},{
				"data" : "ESTADO"
			},{
				"data" : "CAPACIDAD"
			},{
				"data" : "IDUSUARIOMOD"
			},{
				"data" : "FECHABAJA"
			},{
				"data" : "DESCRIPCION"
			},	
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	//tablainventario();


	$('#btn_consultar').click(function(){
		var fechainicio = $('#fechainicio').val();
		var fechafinal = $('#fechafinal').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(fechainicio == ''){ $('#mensaje').html('Ingrese Fecha Inicio'); }
		else if(fechafinal == ''){ $('#mensaje').html('Ingrese Fecha Final'); }
		else if(fechainicio > fechafinal ){ $('#mensaje').html('Rango de fecha Incorrectas'); }
		else{
			tablaindicador(fechainicio, fechafinal);
			$('#div_mensaje').hide();			
		}
	})

	$('#btn_generar_pdf').click(function(){

		var fechainicio = $('#fechainicio').val();
		var fechafinal = $('#fechafinal').val();

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(fechainicio == ''){ $('#mensaje').html('Ingrese Fecha Inicio'); }
		else if(fechafinal == ''){ $('#mensaje').html('Ingrese Fecha Final'); }
		else if(fechainicio > fechafinal ){ $('#mensaje').html('Rango de fecha Incorrectas'); }
		else{
			$('#div_mensaje').hide();
			$("#btn_generar_pdf").attr("href", "../reportebajas/informebaja/"+fechainicio+'/'+fechafinal);		
		}

		

	})

});
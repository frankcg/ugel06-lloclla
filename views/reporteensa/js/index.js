// esta linea sirve para inicializar el jquery
$(document).on('ready',function(){

	function tablareporteensa(tipo,fechainicio,fechafin){
		$('#tablareporteensa').dataTable().fnDestroy();		 	
		$('#tablareporteensa').DataTable({

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
			"ajax" : "../reporteensa/getreporteensa/"+tipo+'/'+fechainicio+'/'+fechafin,
			"columns" : [{
				"data" : "COMPONENTE"
			},{
				"data" : "MARCA"
			},{
				"data" : "SERIE"
			},{
				"data" : "PERSONA"
			},{
				"data" : "ESTADO"
			},{
				"data" : "USUARIOCREACION"
			},{
				"data" : "FECHACREACION"
			},{
				"data" : "INVENTARIO"
			},{
				"data" : "FECHAINICIO"
			},{
				"data" : "FECHAFIN"
			},		
			],
			"language": {
				"url": "/ugel06_dev/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	//tablareporteensa();


	$('#btn_procesar').click(function(){

		var tipo = $('#tipo').val();
		var fechainicio = $('#fechainicio').val();
		var fechafin = $('#fechafin').val();
		//alert(tipo+'/'+fechainicio+'/'+fechafin);

		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		if(tipo == ''){ $('#mensaje').html('Seleccione tipo'); }
		else if(fechainicio == ''){ $('#mensaje').html('Ingrese Fecha Inicio'); }
		else if(fechafin == ''){ $('#mensaje').html('Ingrese Fecha Final'); }
		else if(fechainicio > fechafin ){ $('#mensaje').html('Rango de fecha Incorrectas'); }
		else{
			tablareporteensa(tipo,fechainicio,fechafin);
			$('#div_mensaje').hide();			
		}
	});

	
});
$(document).on('ready',function(){

	$('#archivo').change(function(){
		var file = $("#archivo")[0].files[0];
		var fileName = file.name;        
		fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
		if(fileExtension!='jpg' && fileExtension!='png' ){ 
			alert('Solo archivos con extension .jpg .png');
			$('#archivo').val('');
		}       
	});


	$('#btn_update_usuario').click(function(){

		var password = $('#password').val();
		var password2 = $('#password2').val();
		var archivo = $('#archivo').val();
			
		$('#div_mensaje').show();
		$('#mensaje').css("color", "red");	

		var formData = new FormData($("#form_usuario")[0]);			
		
		if(password == ''){ $('#mensaje').html('Ingrese Password');}
		else if(password2 == ''){ $('#mensaje').html('Repita Password');}
		else if(password != password2){ $('#mensaje').html('Las contraseñas no coinciden');}
		else{
			$.ajax({
				url: '../profile/updateprofile',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					if(data=="ok"){
						$('#div_mensaje').show();
						$('#mensaje').css("color", "blue");	
						$('#mensaje').html('Se actualizo Correctamente. \n Debe cerrar session para actualizar la sesion');
						$('#div_mensaje').hide(10000);
						$('#archivo').val('');
						//alert('Se actualizo Correctamente. \n Debe cerrar session para actualizar la sesion');
						//location.reload();			
					}else{
						$('#mensaje').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}				
				}				
			});
		}

	});

})

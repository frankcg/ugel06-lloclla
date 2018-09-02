	$(document).on('ready',function(){

		$(document).bind('keypress', function(e) {
			if(e.keyCode==13){
				$('#btn_ingresar').trigger('click');
			}
	 	});
		
		$('#mensaje').hide();
		
		$('#btn_ingresar').click(function(){
			
			var user = $('#usuario').val();
			var pass = $('#password').val();


			if(user=="")
				$('#mensaje_user').html('Por favor ingrese usuario');
							
			else if (pass=="")
				$('#mensaje_pass').html('Por favor ingresar contraseña');								
			
			else{				
				$.post('index/login',{
					user : user,
					pass : pass					

				},function(data){
					//alert(data);
					if (data=='ok')
						window.location="panel/index";
					else
						$('#mensaje').show();
						$('#mensaje').html(data);
				});
			}

		});

	});
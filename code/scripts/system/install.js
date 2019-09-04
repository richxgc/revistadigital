$(document).ready(function() {
	$(document).on('submit','#form-install',function(e){
		e.preventDefault();
		var name = $('#adm_nombre').val();
		var email = $('#adm_email').val();
		var pass1 = $('#adm_password').val();
		var pass2 = $('#adm_rep_password').val();
		var checkmail = /^[\w\-\.]{1,}@(([0-9A-Za-z][\w\-\.]{1,}\.)([a-zA-Z]{1,4}))(\.[a-zA-Z]{2,4})?$/;
		if(name.length < 5){
			call_alert('alert-danger','El nombre de usuario no es correcto.',function(){});
			return;
		}
		if(!email.match(checkmail)){
			call_alert('alert-danger','El correo electrónico no es correcto.',function(){});
			return
		}
		if(pass1 != pass2 || pass1.length < 5){
			call_alert('alert-danger','Las contraseñas no coinciden o no son correctas.',function(){});
			return;
		}
		$('#button-submit').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		var dataString = $(this).serialize();
		$.ajax({
			url: path + '/system/install_system',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					call_alert('alert-success','El sistema se ha instalado correctamente y usted será redirigido al panel de administración.',function(){
						window.location = path + '/admin/login';
					});
				} else{
					$('#button-submit').prop('disabled',false);
					call_alert('alert-danger','Ha ocurrido un error durante la instalación, contacte al administrador del sistema.',function(){});
				}
			},
			error: function(){
				$('#loading-img').fadeOut('slow');
				$('#button-submit').prop('disabled',false);
				call_alert('alert-danger','Ha ocurrido un error durante la instalación, contacte al administrador del sistema.',function(){});
			}
		});
		
	});
});
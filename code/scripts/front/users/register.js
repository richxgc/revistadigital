$(document).on('submit','#form-register-user',function(e){
	e.preventDefault();
	//variables para validar el formulario
	var name = $('#usr_nombre').val();
	var email = $('#usr_email').val();
	var pass1 = $('#usr_password').val();
	var pass2 = $('#usr_rep_password').val();
	var checkmail = /^[\w\-\.]{1,}@((itmorelia.edu.mx)|(tecmor.mx))$/;
	if(name.length < 5){
		call_alert('alert-danger','El nombre no es correcto.',function(){});
		return;
	}
	// if(!email.match(checkmail)){
	// 	call_alert('alert-danger','El correo electrónico no es correcto.',function(){});
	// 	return;
	// }
	if(pass1 != pass2 || pass1.length < 5){
		call_alert('alert-danger','Las contraseñas no coinciden o no son correctas.',function(){});
		return;
	}
	$('#btn-submit').prop('disabled',true);
	$('#loading-img').fadeIn('fast');
	//obtiene la informacion del formulario
	var dataString = $(this).serialize();
	//envia la peticion al servidor
	$.ajax({
		url: path + '/users/register_new',
		type: 'post',
		data: dataString,
		dataType: 'json',
		success: function(response){
			$('#loading-img').fadeOut('slow');
			if(response.succeed == true){
				$('#form-register-user').trigger('reset');
				if(response.code == 200){
					var msg = '¡Tu cuenta se ha creado correctamente! Pero antes de poder iniciar sesión en la revista y acceder a las funciones exclusivas es necesario que confirmes la cuenta, para esto hemos enviado un código de confirmación al correo electrónico que nos has proporcionados. Revisa tu bandeja de entrada y activa la cuenta.';
					$('#modal-info-content').text(msg);
					$('#modal-info').modal('show');
				} else {
					call_alert('alert-success',response.status,function(){});
				}
			} else{
				call_alert('alert-danger',response.status,function(){});
			}
			$('#btn-submit').prop('disabled',false);
		},
		error: function(){
			$('#loading-img').fadeOut('slow');
			$('#btn-submit').prop('disabled',false);
			call_alert('alert-danger','Ha ocurrido un error, intentalo de nuevo más tarde.',function(){});
		}
	});
});
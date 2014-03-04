var go = false;
$(document).on('submit','#form-save-account',function(e){
	if(go == true){
		return;
	}
	e.preventDefault();
	var password1 = $('#usr_password').val();
	var password2 = $('#usr_rep_password').val();
	if(password1.length > 0 && (password1.length < 5 || password1 != password2)){
		call_alert('alert-danger','Las contraseñas no coinciden o no son correctas',function(){});
		return;
	}
	$('#modal-edit').modal('show');
});

$(document).on('submit','#form-password-account',function(e){
	e.preventDefault();
	var dataString = $(this).serialize();
	$.ajax({
		url: path + '/users/check_for_password',
		type: 'post',
		data: dataString,
		dataType: 'json',
		complete: function(){
			$('#form-password-account').trigger('reset');
		},
		success: function(response){
			if(response.succeed == true){
				$('#modal-edit').modal('hide');
				//si la contraseña actual es correcta envia el formulario normalmente
				go = true;
				$('#form-save-account').submit();
			} else {
				call_alert('alert-danger',response.status,function(){
					if(response.code == 400){
						window.location = path + '/users/login'
					}
				});
			}
		},
		error: function(){
			call_alert('alert-danger','Ha ocurrido un error, intentalo de nuevo más tarder.',function(){});
		}
	});
});
$(document).ready(function() {

	var formData;
	
	$(document).on('submit','#form-edit-account',function(e){
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
			return;
		}
		if(pass1 != '' && (pass1 != pass2 || pass1.length < 5)){
			call_alert('alert-danger','Las contraseñas no coinciden o no son correctas.',function(){});
			return;
		}
		formData = $(this).serialize();
		$('#modal-edit').modal('show');
	});

	$(document).on('submit','#form-account-pass',function(e){
		e.preventDefault();
		var dataString = formData + '&' + $(this).serialize();
		$(this).trigger('reset');
		$('#btn-update').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		$.ajax({
			url: path + '/usuarios/save_account',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					$('#modal-edit').modal('hide');
					call_alert('alert-success',response.status,function(){});
				} else{
					call_alert('alert-danger',response.status,function(){
						if(response.code == 401){
							window.location = path;
						}
					});
				}
				$('#btn-update').prop('disabled',false);
			},
			error: function(){
				$('#btn-update').prop('disabled',false);
				$('#loading-img').fadeOut('slow');
				call_alert('alert-danger','Ha ocurrido un error, intente de nuevo más tarde.',function(){});
			}
		});
	});

});
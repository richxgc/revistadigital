$(document).ready(function() {
	$(document).on('submit','#form-create-user',function(e){
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
		if(pass1 != pass2 || pass1.length < 5){
			call_alert('alert-danger','Las contraseñas no coinciden o no son correctas.',function(){});
			return;
		}
		$('#btn-submit').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		var dataString = $(this).serialize();
		$.ajax({
			url: path + '/usuarios/create_user',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					if(response.code == 200){
						call_alert('alert-success',response.status,function(){
							window.location.href = path + '/usuarios';
							return;
						});
					} else{
						call_alert('alert-danger',response.status,function(){});
					}
				} else{
					call_alert('alert-danger',response.status,function(){
						if(response.code == 401){
							window.location = path;
						}
					});
				}
				$('#btn-submit').prop('disabled',false);
			},
			error: function(){
				$('#loading-img').fadeOut('slow');
				$('#btn-submit').prop('disabled',false);
				call_alert('alert-danger','Ha ocurrido un error intente de nuevo más tarde.',function(){});
			}
		});
	});
});
$(document).ready(function() {
	$(document).on('submit','#form-login',function(e){
		e.preventDefault();
		$('#button-submit').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		var dataString = $(this).serialize();
		$.ajax({
			url: path + '/login_now',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('fast');
				if(response.succeed == true){
					window.location = path;
				} else{
					$('#button-submit').prop('disabled',false);
					call_alert('alert-danger','El usuario o contraseña es incorrecto.',function(){});
				}
			},
			error: function(){
				$('#loading-img').fadeOut('fast');
				$('#button-submit').prop('disabled',false);
				call_alert('alert-danger','Ha ocurrido un error, intentelo de nuevo más tarde.',function(){});
			}
		});
	})
});
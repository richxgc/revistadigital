$(document).on('submit','#form-login',function(e){
	e.preventDefault();
	$('#btn-submit').prop('disabled',true);
	$('#loading-img').fadeIn('fast');
	var dataString = $(this).serialize();
	$.ajax({
		url: path + '/users/login_now',
		type: 'post',
		data: dataString,
		dataType: 'json',
		success: function(response){
			$('#loading-img').fadeOut('slow');
			if(response.succeed == true){
				window.location = path + '/users/login';
			} else {
				if(response.code == 401){
					$('#modal-info').modal('show');
					$('#btn-submit').prop('disabled',false);
					return;
				}
				call_alert('alert-danger',response.status,function(){
					if(response.code == 301){
						window.location = path + '/users/login';
					}
				});
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
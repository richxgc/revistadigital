$(document).ready(function() {
	var user_id = null;

	$(document).on('click','.delete-user',function(e){
		e.preventDefault();
		user_id = $(this).attr('id').substr(3);
		$('#md-title').text('Eliminar Usuario ' + user_id);
		$('#modal-delete').modal('show');
	});

	$(document).on('submit','#form-delete-post',function(e){
		e.preventDefault();
		var dataString = $(this).serialize() + '&adm_id=' + user_id;
		$(this).trigger('reset');
		$('#btn-delete').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		$.ajax({
			url: path + '/delete_user',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					if(response.code == 200){
						$('#modal-delete').modal('hide');
						call_alert('alert-success',response.status,function(){
							$('#usr-'+user_id).fadeOut('slow',function(){$(this).remove();});
						});
					} else if(response.code == 301){
						call_alert('alert-success',response.status,function(){
							window.location = path + '/logout';
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
				$('#btn-delete').prop('disabled',false);
			},
			error: function(){
				$('#btn-delete').prop('disabled',false);
				$('#loading-img').fadeOut('slow');
				call_alert('alert-danger','Ha ocurrido un error, intentelo de nuevo más tarde.',function(){});
			}
		});
	});
});
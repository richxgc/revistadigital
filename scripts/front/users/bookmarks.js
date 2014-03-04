$(document).on('click','.remove-bookmark',function(e){
	e.preventDefault();
	var bookmark = $(this).parent('header').parent('.home-spotlight');
	var bus_id = $(this).attr('data-bookmark-id');
	$.ajax({
		url: path + '/users/remove_bookmark',
		type: 'post',
		data: {bus_id: bus_id},
		dataType: 'json',
		success: function(response){
			if(response.succeed == true){
				call_alert('alert-success',response.status,function(){});
				bookmark.fadeOut('slow');
			} else {
				call_alert('alert-danger',response.status,function(){
					if(response.code == 400){
						window.location = path + '/users/login'
					}
				});
			}
		},
		error: function(){
			call_alert('alert-danger','No hemos podido eliminar tu marcador, intentalo de nuevo más tarde.',function(){});
		}
	});
});
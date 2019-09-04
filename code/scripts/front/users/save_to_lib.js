$(document).on('click','#save-to-library',function(){
	var art_id = $(this).attr('data-article-id');
	$.ajax({
		url: path + '/users/save_bookmark',
		type: 'post',
		data: {art_id: art_id},
		dataType: 'json',
		success: function(response){
			if(response.succeed == true){
				call_alert('alert-success',response.status,function(){});
			} else {
				call_alert('alert-danger',response.status,function(){
					if(response.code == 400){
						window.location = path + '/users/login'
					}
				});
			}
		},
		error: function(){
			call_alert('alert-danger','No hemos podido guardar el artículo en tu biblioteca, intentalo de nuevo más tarde.',function(){});
		}
	});
});
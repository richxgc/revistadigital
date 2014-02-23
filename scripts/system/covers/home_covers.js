$(document).on('click','.create-cover',function(e){
	e.preventDefault();
	var cat_id = $(this).attr('data-cat-id');
	$.ajax({
		url: path + '/portadas/create_category_cover',
		type: 'post',
		data: {cat_id: cat_id},
		dataType: 'json',
		success: function(response){
			if(response.succeed == true){
 				call_alert('alert-success',response.status,function(){
 					if(response.code == 200){
 						window.location.href = path + '/portadas/editar/' + response.por_id;
						return;	
 					}
				});
 			} else {
 				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){
						window.location = path; return;
					}
				});
 			}
		},
		error: function(){
			call_alert('alert-danger','Ha ocurrido un error, intentelo de nuevo más tarde.',function(){});
		}
	});
});
$(document).ready(function() {

	var delete_object;
	var delete_id;

	$(document).on('click','.delete-category',function(e){
		e.preventDefault();
		delete_object = $(this).parent('td').parent('tr');
		delete_id = $(this).attr('data-delete-id');
		$('#md-title').text('Eliminar Categoría ' + delete_id);
		$('#modal-delete').modal('show');
	});

	$(document).on('submit','#form-delete-post',function(e){
		e.preventDefault();
		var dataString = $(this).serialize() + '&cat_id=' + delete_id;
		$(this).trigger('reset');
		$('#btn-delete').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		$.ajax({
			url: path + '/categorias/delete_category',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					if(response.code == 200){
						$('#modal-delete').modal('hide');
						call_alert('alert-success',response.status,function(){
							delete_object.fadeOut('slow',function(){$(this).remove();});
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
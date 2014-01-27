$(document).ready(function() {
	$('#art_contenido').wysihtml5({
		'font-styles': true,
		'emphasis': true,
		'list': true,
		'html': true,
		'link': true,
		'image': true,
		'color': false,
		'size': 'sm',
		locale: "es-ES"
	});

	$(document).on('submit','#form-create-article',function(e){
		e.preventDefault();
		var dataString = $(this).serialize();
		$('#btn-submit').prop('disabled', true);
		$('#loading-img').fadeIn('fast');
		$.ajax({
			url: path + '/create_article',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					if(response.code == 200){
						call_alert('alert-success',response.status,function(){
							window.location.href = path + '/articulos';
							return;
						});
					} else{
						call_alert('alert-danger',response.status,function(){});
					}
				} else{
					call_alert('alert-danger',response.status,function(){
						if(response.code == 401){
							window.location = path;
							return;
						}
					});
				}
				$('#btn-submit').prop('disabled', false);
			},
			error: function(){
				call_alert('alert-danger','Ha ocurrido un error intente de nuevo más tarde.',function(){});
			}
		});
	});

});
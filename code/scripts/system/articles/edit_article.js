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

	$(document).on('submit','#form-edit-article',function(e){
		e.preventDefault();
		//verifica si existen usuarios y categorias seleccionadas solo aplica si el documento sera publicado
		if($('input[name="art_autores[]"]').length == 0 && $('#art_estado').val() == 'publicado'){
			call_alert('alert-danger','Tienes que agregar por lo menos un autor para poder publicar.',function(){
				$('#art_buscar_autores').focus();
			});
			return;
		}
		if($('input[name="art_categorias[]"]').length == 0 && $('#art_estado').val() == 'publicado'){
			call_alert('alert-danger','Tienes que agregar por lo menos una categoría para poder publicar.',function(){
				$('#art_agregar_categorias').focus();
			});
			return;
		}
		//continua con la operacion
		var dataString = $(this).serialize();
		$('#btn-submit').prop('disabled', true);
		$('#loading-img').fadeIn('fast');
		$.ajax({
			url: path + '/articulos/save_article',
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
				$('#loading-img').fadeOut('slow');
				$('#btn-submit').prop('disabled', false);
				call_alert('alert-danger','Ha ocurrido un error intente de nuevo más tarde.',function(){});
			}
		});
	});
	
});
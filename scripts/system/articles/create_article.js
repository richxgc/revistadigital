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
		$.ajax({
			url: path + '/create_article',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){

			},
			error: function(){

			}
		});
	});
});
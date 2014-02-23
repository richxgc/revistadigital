$(document).ready(function() {
	var perm_array = permissions.split(',');
	if(perm_array[0] != 'NULL' && perm_array[0] != '0' && perm_array[0] != ''){
		for(var i=0; i<perm_array.length; i++){
			var op = $('#art_permisos_publicar option[value="'+perm_array[i]+'"]');
			var cat_name = op.text();
			var cat_id = op.val();
			var list_obj =  '<li class="perm_item">'+cat_name+' <a href="#" class="delete-category" title="Eliminar categoría"><i class="fa fa-times"></i></a>'+
						'<input type="hidden" name="art_cat_permiso[]" value="'+cat_id+'"/></li>';
			$('#cat-p-list').append(list_obj);
		}
	}
	if($('#publish_articles').is(':checked')){
		$('#permissions_articles').show();
	}
});

$(document).on('change','#publish_articles',function(e){
	if($(this).is(':checked')){
		$('#permissions_articles').fadeIn('slow');
	} else { 
		$('#permissions_articles').fadeOut('slow',function(){
			$('.perm_item').each(function(index, el) { el.remove();});
		});
	}
});

$(document).on('click','#add-cat-p',function(){
	var cat_id = $('#art_permisos_publicar').find(":selected").val();
	var cat_name = $('#art_permisos_publicar').find(":selected").text();
	if(cat_id == '' || cat_id == null){
		call_alert('alert-warning','Selcciona una categoría de la lista',function(){});
		return;
	}
	var list_obj =  '<li class="perm_item">'+cat_name+' <a href="#" class="delete-category" title="Eliminar categoría"><i class="fa fa-times"></i></a>'+
					'<input type="hidden" name="art_cat_permiso[]" value="'+cat_id+'"/></li>';
	$('#cat-p-list').append(list_obj);
});

$(document).on('click','.delete-category',function(e){
	e.preventDefault();
	var delete_object = $(this).parent('li');
	delete_object.fadeOut('slow',function(){$(this).remove();});
});
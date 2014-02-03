$(document).on('click','#add-category',function(){
	var cat_id = $('#art_agregar_categorias').find(":selected").val();
	var cat_name = $('#art_agregar_categorias').find(":selected").text();
	if(cat_id == '' || cat_id == null){
		call_alert('alert-warning','Selcciona una categoría de la lista',function(){});
		return;
	}
	var list_obj =  '<li>'+cat_name+' <a href="#" class="delete-category" title="Eliminar categoría"><i class="fa fa-times"></i></a>'+
					'<input type="hidden" name="art_categorias[]" value="'+cat_id+'"/></li>';
	$('#categories-list').append(list_obj);
});

$(document).on('click','.delete-category',function(e){
	e.preventDefault();
	var delete_object = $(this).parent('li');
	delete_object.fadeOut('slow',function(){$(this).remove();});
});
$(document).on('click','#add-category',function(){
	var cat_id = $('#art_agregar_categorias').find(":selected").val();
	var cat_name = $('#art_agregar_categorias').find(":selected").text();
	if(cat_id == '' || cat_id == null){
		call_alert('alert-warning','Selcciona una categoria de la lista',function(){});
		return;
	}
	var cat_list = '<li id="cat-'+cat_id+'">'+cat_name+' <a href="#" role="button" class="delete-category" id="dc-'+cat_id+'" title="Eliminar categoría"><i class="fa fa-times"></i></a></li>';
	var input_obj = '<input type="hidden" name="art_categorias[]" id="art_categorias-'+cat_id+'" value="'+cat_id+'"/>';
	$('#categories-list').append(cat_list);
	$('#panel-categories').append(input_obj);
});

$(document).on('click','.delete-category',function(e){
	e.preventDefault();
	var cat_id = $(this).attr('id').substr(3);
	$('#cat-'+cat_id).fadeOut('slow',function(){
		$(this).remove();
		$('#art_categorias-'+cat_id).remove();
	});
});
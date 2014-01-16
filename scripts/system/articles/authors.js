var authors = {};
var timeoutReference;

function doneTyping(callback){
	if(!timeoutReference) return;
	timeoutReference = null;
	callback();
}

function searchAuthor(search_string){
	$('#loading-authors').fadeIn('fast');
	$('#search-author-list').empty();
	$.ajax({
		url: path + '/search_authors',
		type: 'post',
		data: {'search': search_string},
		dataType: 'json',
		complete: function(){
			$('#loading-authors').fadeOut('slow');
		},
		success: function(response){
			if(response.succeed == false){
				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){
						window.location = path;
					}
				});
			} else{
				authors = response;
				if(authors.length > 0){
					for(var i=0; i< authors.length; i++){
						var author = '';
						if(authors[i].usr_imagen != ''){
							//TODO: mostrar lista con imagen del usuario
						} else{
							var author = '<li class="list-group-item"><a href="#" class="add-author" id="aut-'+authors[i].usr_id+'"><img src="'+base_url+'images/profile_thumbnail.jpg" width="20" height="20"/>'+authors[i].usr_nombre+'</a></li>';
						}
						$('#search-author-list').append(author);
					}
					$('#search-author-list').fadeIn('fast');
				} else{
					$('#search-author-list').fadeOut('slow');
					call_alert('alert-warning','No hay ningún usuario con ese nombre.',function(){});
				}
			}
		},
		error: function(){
			call_alert('alert-danger','El servicio no esta disponible por el momento.',function(){});
		}
	});
}

function hideList(){
	$('#search-author-list').fadeOut('slow');
	$('#search-author-list').empty();
}

$(document).on('click','body',function(){
	hideList();
});

$(document).on('focus','#art_buscar_autores',function(){
	if($(this).val() != ''){
		searchAuthor($(this).val())
	}
});

$(document).on('keyup','#art_buscar_autores',function(){
	var search = $(this).val();
	if(timeoutReference) clearTimeout(timeoutReference);
	timeoutReference = setTimeout(function(){
		doneTyping(function(){
			if(search == ''){
				hideList();
				return;	
			} 
			searchAuthor(search);
		});
	},1000);
});

$(document).on('click','.add-author',function(e){
	e.preventDefault();
	$('#art_buscar_autores').val('');
	var author_id = $(this).attr('id').substr(4);
	var author_name = $(this).text();
	var list_obj = '<li id="lau-'+author_id+'">'+author_name+' <a href="#" role="button" class="remove-author" id="da-'+author_id+'" title="Eliminar autor"><i class="fa fa-times"></i></a></li>';
	var input_obj = '<input type="hidden" name="art_autores[]" id="art_autores-'+author_id+'" value="'+author_id+'"/>';
	$('#authors-list').append(list_obj);
	$('#panel-author').append(input_obj);
});

$(document).on('click','.remove-author',function(e){
	e.preventDefault();
	var author_id = $(this).attr('id').substr(3);
	$('#lau-'+author_id).fadeOut('slow',function(){
		$(this).remove();
		$('#art_autores-'+author_id).remove();
	});
});
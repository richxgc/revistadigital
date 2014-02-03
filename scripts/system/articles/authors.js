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
		url: path + '/articulos/search_authors',
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
							author = '<li class="list-group-item"><a href="#" class="add-author" id="aut-'+authors[i].usr_id+'"><img src="'+base_domain+'images/profile_thumbnail.jpg" width="20" height="20" style="margin-right:5px;"/>'+authors[i].usr_nombre+'</a></li>';
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
	var author_image = $(this).children('img').attr('src');
	var author_name = $(this).text();
	var list_obj =  '<li><img src="'+author_image+'" alt="img" width="20" height="20"/>'+
					' '+author_name+' <a href="#" class="remove-author" title="Eliminar autor"><i class="fa fa-times"></i></a>'+
					'<input type="hidden" name="art_autores[]" value="'+author_id+'"/></li>';
	$('#authors-list').append(list_obj);
	console.log(author_image);
});

$(document).on('click','.remove-author',function(e){
	e.preventDefault();
	var to_delete = $(this).parent('li');
	to_delete.fadeOut('slow',function(){$(this).remove();});
});
//parsear los datos tipo json
var data_json;
$(document).ready(function() {
	if(data == null || data == "" || data == "undefined"){
		data_json = data_json = {};
	} else {
		data_json = JSON.parse(data);	
	}
	renderCoverItems();
	console.log(data_json);
});
//crear los elementos que existen en data_json
function renderCoverItems(){
	//slider
	if(data_json.art_titulo){
		var article = '<div class="slide">'+
		'<strong>Artículo: '+data_json.art_titulo+'</strong> '+
		'<small>por: '+data_json.art_autores+' ('+data_json.art_fecha+')</small>'+
		'<a href="#" class="pull-right only-icon delete-article" title="Eliminar el artículo de portada"><i class="fa fa-times"></i></a></div>';
		$('#main-slider').append(article);
	}
}
/*
 * Funciones para guardar el contenido de la portada en la bd
 */
 $(document).on('submit','#form-edit-category-cover',function(e){
 	e.preventDefault();
 	$('#btn-submit').prop('disabled',true);
	$('#loading-img').fadeIn('fast');
 	var serialized = JSON.stringify(data_json);
 	var dataString = $(this).serialize() + '&por_datos=' + serialized;
 	$.ajax({
 		url: path + '/portadas/save_cover',
 		type: 'post',
 		data: dataString,
 		dataType: 'json',
 		success: function(response){
 			$('#loading-img').fadeOut('slow');
 			if(response.succeed == true){
 				if(response.code == 200){
					call_alert('alert-success',response.status,function(){
						window.location.href = path + '/portadas'; return;
					});
				} else {
					call_alert('alert-danger',response.status,function(){});
				}
 			} else {
 				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){
						window.location = path; return;
					}
				});
 			}
 			$('#btn-submit').prop('disabled',false);
 		},
 		error: function(){
 			$('#loading-img').fadeOut('slow');
			$('#btn-submit').prop('disabled',false);
			call_alert('alert-danger','Ha ocurrido un error intente de nuevo más tarde.',function(){});
 		}
 	});
 });
/*
 * Funciones para agregar y remover articulos de los slides
 */
var slide_id = 0;
$(document).on('click','.add-article',function(e){
	e.preventDefault();
	$('.search_article').val('');
	data_json = articles[parseInt($(this).attr('data-article-id'))];
	var article = '<div class="slide">'+
	'<strong>Artículo: '+data_json.art_titulo+'</strong> '+
	'<small>por: '+data_json.art_autores+' ('+data_json.art_fecha+')</small>'+
	'<a href="#" class="pull-right only-icon delete-article" title="Eliminar el artículo de portada"><i class="fa fa-times"></i></a></div>';
	$('.slide').remove();
	$('#main-slider').append(article);
});
$(document).on('click','.delete-article',function(e){
	e.preventDefault();
	$(this).parent('.slide').fadeOut('slow',function(){$(this).remove()});
	data_json = {};
});
/*
 * Funciones de la busqueda de contenido
 */
var articles = {};
var timeoutReference;
function doneTyping(callback){
	if(!timeoutReference) return;
	timeoutReference = null;
	callback();
}
function hideList(){
	$('.search-articles-list').fadeOut('slow');
	$('.search-articles-list').empty();
}
function searchArticle(search_string,type,origin){
	$.ajax({
		url: path + '/portadas/search_articles',
		type: 'post',
		data: {'search': search_string},
		dataType: 'json',
		success: function(response){
			if(response.succeed == false){
				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){
						window.location = path;
					}
				});
			} else {
				articles = response;
				if(articles.length > 0){
					$.each(articles, function(index, val) {
						var article = '<li class="list-group-item">'+
						'<a href="#" class="add-article" data-article-id="'+index+'" data-article-type="'+type+'">'+
						'<strong>'+val.art_titulo+'</strong> <small>por: '+val.art_autores+' ('+val.art_fecha+')</small>'+
						'</a></li>';
						origin.parent('div').next('.search-articles-list').append(article);
					});
					origin.parent('div').next('.search-articles-list').fadeIn('fast');
				} else{
					origin.parent('div').next('.search-articles-list').fadeOut('slow');
					call_alert('alert-warning','No hay ningún artículo con ese nombre.',function(){});
				}
			}
		},
		error: function(){
			call_alert('alert-danger','El servicio no esta disponible por el momento.',function(){});
		}
	});
}
$(document).on('keyup','.search_article',function(){
	var search = $(this).val();
	var type = $(this).attr('data-type-slide');
	var origin = $(this);
	if(timeoutReference) clearTimeout(timeoutReference);
	timeoutReference = setTimeout(function(){
		doneTyping(function(){
			if(search == ''){
				hideList();
				return;	
			}
			searchArticle(search,type,origin);
		});
	},1000);
});
$(document).on('focus','.search_article',function(){
	var search = $(this).val();
	var type = $(this).attr('data-type-slide');
	var origin = $(this);
	if($(this).val() != ''){
		searchArticle(search,type,origin);
	}
});
$(document).on('click','body',function(){
	hideList();
});
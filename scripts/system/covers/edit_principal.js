//parsear los datos tipo json
var data_json;
$(document).ready(function() {
	if(data == null || data == "" || data == "undefined"){
		data_json = data_json = {sliders: {},highlights: {}};
	} else {
		data_json = JSON.parse(data);	
	}
	renderCoverItems();
});
//crear los elementos que existen en data_json
function renderCoverItems(){
	//slider
	$.each(data_json.sliders, function(index, val) {
		var slide = '<div class="slide" draggable="true" data-object-id="'+index+'" data-slide-type="slide">'+
		'<strong>Artículo: '+val.art_titulo+'</strong> '+
		'<small>por: '+val.art_autores+' ('+val.art_fecha+')</small>'+
		'<a href="#" class="pull-right only-icon delete-slide" title="Eliminar Slide"><i class="fa fa-times"></i></a></div>';
		$('#main-slider').append(slide);
		slide_id++;
	});
	addEventListenerSlides();
	//highlights
	$.each(data_json.highlights, function(index, val) {
		var slide = '<div class="slide" draggable="true" data-object-id="'+index+'" data-slide-type="highlight">'+
		'<strong>Artículo: '+val.art_titulo+'</strong> '+
		'<small>por: '+val.art_autores+' ('+val.art_fecha+')</small>'+
		'<a href="#" class="pull-right only-icon delete-slide" title="Eliminar Slide"><i class="fa fa-times"></i></a></div>';
		$('#highlights-articles').append(slide);
		highlight_id++;
	});
	addEventListenerHighlights();
}

/*
 * Funciones para guardar el contenido de la portada en la bd
 */
 $(document).on('submit','#form-edit-main-cover',function(e){
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
var highlight_id = 0;
$(document).on('click','.add-article',function(e){
	e.preventDefault();
	$('.search_article').val('');
	var article = articles[parseInt($(this).attr('data-article-id'))];
	var type = $(this).attr('data-article-type');
	if(type == 'slide'){
		data_json.sliders[slide_id] = article;
		var slide = '<div class="slide" draggable="true" data-object-id="'+slide_id+'" data-slide-type="slide">'+
		'<strong>Artículo: '+article.art_titulo+'</strong> '+
		'<small>por: '+article.art_autores+' ('+article.art_fecha+')</small>'+
		'<a href="#" class="pull-right only-icon delete-slide" title="Eliminar Slide"><i class="fa fa-times"></i></a></div>';
		$('#main-slider').append(slide);
		slide_id++;
		addEventListenerSlides();
	} else if(type == 'highlight'){
		data_json.highlights[highlight_id] = article;
		var slide = '<div class="slide" draggable="true" data-object-id="'+highlight_id+'" data-slide-type="highlight">'+
		'<strong>Artículo: '+article.art_titulo+'</strong> '+
		'<small>por: '+article.art_autores+' ('+article.art_fecha+')</small>'+
		'<a href="#" class="pull-right only-icon delete-slide" title="Eliminar Slide"><i class="fa fa-times"></i></a></div>';
		$('#highlights-articles').append(slide);
		highlight_id++;
		addEventListenerHighlights();
	}
});
$(document).on('click','.delete-slide',function(e){
	e.preventDefault();
	var obj = $(this).parent('.slide');
	var type = obj.attr('data-slide-type');
	var pos = parseInt(obj.attr('data-object-id'));
	removeSlide(type,pos,obj);
});

function removeSlide(type,pos,obj){
	var data_length = null;
	var last_obj = obj;
	if(type == 'slide'){
		data_length = (Object.keys(data_json.sliders).length - 1);
		for(var i=pos; i<data_length; i++){
			var tmp = data_json.sliders[i];
			data_json.sliders[i] = data_json.sliders[(i+1)];
			data_json.sliders[(i+1)] = data_json.sliders[i];
			last_obj.next('div').attr('data-object-id',i);
			last_obj = last_obj.next('div');
		}
		delete data_json.sliders[data_length];
		obj.fadeOut('slow',function(){$(this).remove()});
		slide_id--;
	} else if(type == 'highlight'){
		data_length = (Object.keys(data_json.highlights).length - 1);
		for(var i=pos; i<data_length; i++){
			var tmp = data_json.highlights[i];
			data_json.highlights[i] = data_json.highlights[(i+1)];
			data_json.highlights[(i+1)] = data_json.highlights[i];
			last_obj.next('div').attr('data-object-id',i);
			last_obj = last_obj.next('div');
		}
		delete data_json.highlights[data_length];
		obj.fadeOut('slow',function(){$(this).remove()});
		highlight_id--;
	}
}

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

/*
 * Funciones drag & drop slider principal
 */
var dragSliderEl = null;
function handleDragStarSlide(e){
	e.target.style.opacity = '0.4';
	dragSliderEl = e.target;
	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData('text/html',e.target.innerHTML);
}
function handleDragEndSlide(e){
	e.target.style.opacity = '1.0';
	[].forEach.call(cols,function(col){
		col.classList.remove('over');
	});
}
function handleDragOverSlide(e){
	if(e.preventDefault){e.preventDefault();}
	e.dataTransfer.dropEffect = 'move';
	return false;
}
function handleDragEnterSlide(e){
	e.target.classList.add('over');
}
function handleDragLeaveSlide(e){
	e.target.classList.remove('over');
}
function handleDropSlide(e){
	if(e.stopPropagation){e.stopPropagation();}
	e.target.classList.remove('over');
	if(dragSliderEl != e.target){
		var position1 = $(e.target).attr('data-object-id');
		var position2 = $(dragSliderEl).attr('data-object-id');
		switchSlider(position1,position2);
		dragSliderEl.innerHTML = e.target.innerHTML;
		e.target.innerHTML = e.dataTransfer.getData('text/html');
	}
	return false;
}
var cols = null;
function addEventListenerSlides(){
	cols = document.querySelectorAll('#main-slider .slide');
	[].forEach.call(cols,function(col){
		//for desktop
		col.addEventListener('dragstart',handleDragStarSlide,false);
		col.addEventListener('dragend',handleDragEndSlide,false);
		col.addEventListener('dragover',handleDragOverSlide,false);
		col.addEventListener('dragenter',handleDragEnterSlide,false);
		col.addEventListener('dragleave',handleDragLeaveSlide,false);
		col.addEventListener('drop',handleDropSlide,false);
	});
}
function switchSlider(pos1,pos2){
	var tmp = data_json.sliders[pos1];
	data_json.sliders[pos1] = data_json.sliders[pos2];
	data_json.sliders[pos2] = tmp;
}
function handleDropHighlight(e){
	if(e.stopPropagation){e.stopPropagation();}
	e.target.classList.remove('over');
	if(dragSliderEl != e.target){
		var position1 = $(e.target).attr('data-object-id');
		var position2 = $(dragSliderEl).attr('data-object-id');
		switchHighlight(position1,position2);
		dragSliderEl.innerHTML = e.target.innerHTML;
		e.target.innerHTML = e.dataTransfer.getData('text/html');
	}
	return false;
}
var cols2 = null;
function addEventListenerHighlights(){
	cols2 = document.querySelectorAll('#highlights-articles .slide');
	[].forEach.call(cols2,function(col){
		col.addEventListener('dragstart',handleDragStarSlide,false);
		col.addEventListener('dragend',handleDragEndSlide,false);
		col.addEventListener('dragover',handleDragOverSlide,false);
		col.addEventListener('dragenter',handleDragEnterSlide,false);
		col.addEventListener('dragleave',handleDragLeaveSlide,false);
		col.addEventListener('drop',handleDropHighlight,false);
	});
}
function switchHighlight(pos1,pos2){
	var tmp = data_json.highlights[pos1];
	data_json.highlights[pos1] = data_json.highlights[pos2];
	data_json.highlights[pos2] = tmp;
}
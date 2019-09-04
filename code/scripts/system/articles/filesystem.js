//on document ready launch the aplication DnDUpload
$(document).on('ready',initDnDUpload());
//var for define the upload permisive zone
var drop_zone = null;
var input_up = null;
//the function intialize the drag&drop file upload plugin
function initDnDUpload(){
	//check if some HTML5 APIs are available
	if(window.File && window.FileReader && window.FileList && window.Blob){
		//if the APIs are supported create some event listeners
		drop_zone = document.getElementById('drop-files-zone');
		drop_zone.addEventListener('dragover',handleDragOver,false);
		drop_zone.addEventListener('dragenter',handleDragEnter,false);
		drop_zone.addEventListener('drop',handleFileSelect,false);
		input_up = document.getElementById('the-file-selector');
		input_up.addEventListener('change',handleFileInput,false);
		//load folders inside uploads path
		get_folders('uploads');
	} else{
		//if the apis aren't available display a warning message
		call_alert('alert-warning','Algunas funciones de HTML5 no son soportadas por este navegador.',function(){});
	}
	return;
}
//the function get through ajax request the folders at specific path
function get_folders(route){
	$('#list-directories-zone').empty();
	$.ajax({
		url: base_url + '/filesystem/list_directories/' + route,
		type: 'get',
		dataType: 'json',
		success: function(response){
			if(response.succeed == false){
				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){ window.location = path;}
				});
			} else{
				get_files(route);
				if(response.files.length > 0){
					for(var i=0; i<response.files.length; i++){
						var ls_obj = '<a href="#" class="list-group-item folder-list">'+
							'<i class="fa fa-folder"></i> <span>'+response.files[i].file+'</span>'+
							'<div class="hide folder-route">'+response.files[i].dir+'</div></a>';
						$('#list-directories-zone').append(ls_obj);
					}
				}
			}
		},
		error: function(){
			call_alert('alert-danger','Ha ocurrido un error, intente de nuevo más tarde.',function(){});
		}
	});
}
//the function gets through ajax request the files into specific path
function get_files(route){
	$('#list-files-zone').empty();
	$.ajax({
		url: base_url + '/filesystem/list_files/' + route,
		type: 'get',
		dataType: 'json',
		success: function(response){
			if(response.succeed == false){
				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){ window.location = path;}
				});
			} else if(response.files.length > 0){
				for(var i=0; i<response.files.length; i++){
					var ls_obj = null;
					if(response.files[i].type == 'image'){
						ls_obj = '<li class="fs-item"><img src="'+response.files[i].file+'" alt="'+response.files[i].file_name+'" title="'+response.files[i].file_name+'"/>'+
						'<div class="btn-group btn-group-sm">'+
						'<a href="'+response.files[i].file+'" class="btn btn-default" title="Abrir el archivo" target="_blank"><i class="fa fa-eye"></i></a>'+
						'<a href="'+response.files[i].file+'" class="btn btn-default btn-add-file" data-file-name="'+response.files[i].file_name+'" title="Insertar archivo"><i class="fa fa-check-circle"></i></a>'+
						'</div><span>'+response.files[i].file_name+'</span></li>';
					} else if(response.files[i].type == 'pdf'){
						ls_obj = '<li class="fs-item"><div class="icon-doc id-pdf" title="'+response.files[i].file_name+'"></div>'+
						'<div class="btn-group btn-group-sm">'+
						'<a href="'+response.files[i].file+'" class="btn btn-default" title="Abrir el archivo" target="_blank"><i class="fa fa-eye"></i></a>'+
						'<a href="'+response.files[i].file+'" class="btn btn-default btn-add-file" data-file-name="'+response.files[i].file_name+'" title="Insertar archivo"><i class="fa fa-check-circle"></i></a>'+
						'</div><span>'+response.files[i].file_name+'</span></li>';
					}
					$('#list-files-zone').append(ls_obj);
				}
			}
		},
		error: function(){
			call_alert('alert-danger','Ha ocurrido un error, intente de nuevo más tarde.',function(){});
		}
	});
}
function uploadFiles(files){
	//define a xhr process stack
	var xhr = Array();
	//for each file into array o files upload to server
	for(var i=0; i<files.length; i++){
		//create a new reader object to display preview of file and get blob data for uploading
		var reader = new FileReader();
		//when the file is completely loaded show into ui
		reader.onloadend = (function(theFile,i){
			//return a function with de op logic for upload theFile
			return function(e){
				//create the html object to insert in unordered list tag
				var ls_obj = null;
				if(files[i].type.match('image.*')){
					ls_obj ='<li class="fs-item"><img src="'+e.target.result+'" alt="'+theFile.name+'" title="'+theFile.name+'" id="itu-'+i+'"/>'+
							'<div class="progress progress-striped">'+
							'<div id="pgb-'+i+'" class="progress-bar progress-bar-success" aria-valuenow="100" aria-valuemin="0" aria-valuenow="100" style="width: 0%;"></div></div>'+
							'<div class="btn-group btn-group-sm" id="bgf-'+i+'">'+
							'<a href="#" class="btn btn-default" title="Abrir el archivo" target="_blank"><i class="fa fa-eye"></i></a>'+
							'<a href="#" class="btn btn-default btn-add-file" data-file-name="'+theFile.name+'" title="Insertar archivo"><i class="fa fa-check-circle"></i></a>'+
							'</div><span>'+theFile.name+'</span></li>';
				} else if(files[i].type.match('application/pdf')){
					ls_obj ='<li class="fs-item"><div class="icon-doc id-pdf" title="'+theFile.name+'"></div>'+
							'<div class="progress progress-striped">'+
							'<div id="pgb-'+i+'" class="progress-bar progress-bar-success" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div>'+
							'<div class="btn-group btn-group-sm" id="bgf-'+i+'">'+
							'<a href="#" class="btn btn-default" title="Abrir el archivo" target="_blank"><i class="fa fa-eye"></i></a>'+
							'<a href="#" class="btn btn-default btn-add-file" data-file-name="'+theFile.name+'" title="Insertar archivo"><i class="fa fa-check-circle"></i></a>'+
							'</div><span>'+theFile.name+'</span></li>';
				}
				//add the file created into beginning of file list
				$('#list-files-zone').prepend(ls_obj);
				//create and xml http request (xhr) for each file in the stack
				xhr[i] = new XMLHttpRequest();
				//define content type of the xhr response
				xhr[i].responseType = 'json';
				//lets track upload progress for each xhr in the stack
				xhr[i].upload.addEventListener('progress',function(e){
					var progress = (e.loaded/e.total*100);
					$('#pgb-'+i).css('width',(progress+'%'));
					if(progress == 100){
						//delete the progress bar one second after the file is completely uploaded
						setTimeout(function(){$('#pgb-'+i).parent('div').fadeOut('slow',function(){$(this).remove()});},1000);
					}
				},false);
				//open an stream for the url that has a controller in php
				xhr[i].open('POST', base_url + '/filesystem/upload',true);
				//create the form data object
				var data = new FormData();
				//define the path where the file will be uploaded
				var cpath = null;
				if(last_route.length > 0){
					cpath = last_route[last_route.length-1].current_route
				} else{
					cpath = 'uploads';
				}
				//append all data to send it at server
				data.append('reqnum',i); //number of the request o number of the element in the stack
				data.append('path',cpath); //the path where the file will be uploaded
				data.append('file',e.target.result); //the file enconded into base64 uri type
				data.append('name',theFile.name); //the file name for save it into server
				//send the request to server
				xhr[i].send(data);
				//check when a xhr in the stack change his state and do something
				xhr[i].onreadystatechange = function(){
					if(xhr[i].readyState == 4){
						if(xhr[i].status == 200){
							if(xhr[i].response.succeed == true){
								call_alert('alert-success','Los archivos se han subido correctamente',function(){
									$('#bgf-'+xhr[i].response.reqnum).children('a').each(function(){
										$(this).attr('href',xhr[i].response.fullpath);
									});
									if(xhr[i].response.type == 'image'){
										$('#itu-'+xhr[i].response.reqnum).attr('src',xhr[i].response.fullpath);
									}
								});
							} else{
								call_alert('alert-danger',xhr[i].response.status,function(){});
							}
						} else{
							call_alert('alert-danger','Ha ocurrido un error al subir el archivo '+theFile.name,function(){});
						}
					}
				}
			}
		})(files[i],i);
		//transform the source into base64 uri for send it to server
		reader.readAsDataURL(files[i]);
	}
}
//on change input files upload the files
function handleFileInput(e){
	var files = e.target.files;
	uploadFiles(files);
}
//on drop files call uploadFiles() function to upload dropped files
function handleFileSelect(e){
	e.stopPropagation();
	e.preventDefault();
	//restore style of the box
	this.style.opacity = 1.0;
	this.style.borderColor = '#CCC';
	//get the files with data transfer html5 object
	var files = e.dataTransfer.files;
	uploadFiles(files);
}
//on drag enter change the style of the box
function handleDragEnter(e){
	e.preventDefault();
	e.stopPropagation();
	this.style.opacity = 0.8;
	this.style.borderColor = '#2963F8';
	return;
}
//on drag over create copy of files into dataTransfer object
function handleDragOver(e){
	e.stopPropagation();
	e.preventDefault();
	e.dataTransfer.dropEffect = 'copy';
	return;
}
//behavior of buttons in the file
$(document).on({
	mouseenter: function(){$(this).children('.btn-group').fadeIn('fast');},
	mouseleave: function(){$(this).children('.btn-group').fadeOut('slow');}
},'.fs-item');

var last_route = Array();

$(document).on('click','.folder-list',function(e){
	e.preventDefault();
	var dir = $(this).children('span').text();
	var route = $(this).children('.folder-route').text();
	$('#back-folder').removeClass('hidden');
	$('#selected-folder').text(dir);
	get_folders(route+'-'+dir);
	var lr = {};
	if(last_route.length == 0){
		lr = {'dir': dir, 'last_dir': '', 'route': route, 'current_route': route+'-'+dir};
	} else{
		lr = {'dir': dir, 'last_dir': last_route[last_route.length-1].dir, 'route': route, 'current_route': route+'-'+dir};
	}
	last_route.push(lr);
});

$(document).on('click','#back-folder',function(e){
	e.preventDefault();
	var lr = last_route.pop();
	if(lr.route == 'uploads'){
		$('#back-folder').addClass('hidden');
		$('#selected-folder').text('Todas las Carpetas');
	} else{
		$('#selected-folder').text(lr.last_dir);
	}
	get_folders(lr.route);
});

$(document).on('click','#add-new-folder',function(e){
	e.preventDefault();
	var folder_name = $('#new-folder-name').val();
	var check_name = /^[\w\_]{2,50}$/;
	if(!folder_name.match(check_name)){
		call_alert('alert-danger','El nombre para la carpeta no es correcto.',function(){});
		return;
	}
	var folder_route = '';
	if(last_route.length == 0){
		folder_route = 'uploads';
	} else{
		folder_route = last_route[last_route.length-1].current_route;
	}
	$(this).prop('disabled',true);
	$.ajax({
		url: base_url + '/filesystem/create_directory',
		type: 'post',
		data: {'folder_name':folder_name,'folder_route':folder_route},
		dataType: 'json',
		success: function(response){
			if(response.succeed == true){
				$('#new-folder-name').val('');
				call_alert('alert-success',response.status,function(){
					$('#add-new-folder').prop('disabled',false);
					var ls_obj = '<a href="#" class="list-group-item folder-list">'+
						'<i class="fa fa-folder"></i> <span>'+folder_name+'</span>'+
						'<div class="hide folder-route">'+folder_route+'</div></a>';
					$('#list-directories-zone').append(ls_obj);
				});
			} else{
				call_alert('alert-danger',response.status,function(){
					if(response.code == 401){ window.location = path;}
				});
			}
		},
		error: function(){
			call_alert('alert-danger','Ha ocurrido un error, intente de nuevo más tarde.',function(){});
		}
	});
});
//on "add file" click open file selector input
$(document).on('click','#file-selector-btn',function(e){
	e.preventDefault();
	$('#the-file-selector').trigger('click');
});

var method = null;
var insert_point = null;
var doc_index = 0;

$(document).on('click','.btn-filesystem',function(e){
	e.preventDefault();
	method = $(this).attr('data-upload-method');
	point = $(this).attr('data-upload-point')
	insert_point = $(point);
	$('#modal-file-upload').modal('show');
});

$(document).on('click','.btn-add-file',function(e){
	e.preventDefault();
	var src = $(this).attr('href');
	var name = $(this).attr('data-file-name');
	if(method == null){
		call_alert('alert-danger','Ha ocurrido un error al insertar el documento.',function(){});
		return;
	} else if(method == 'input' && insert_point != null){
		if(insert_point.val(src)){
			$('#modal-file-upload').modal('hide');
		}
	} else if(method == 'editor'){
		editor.currentView.element.focus();
		editor.composer.commands.exec("insertImage", src)
		$('#modal-file-upload').modal('hide');
	}
});
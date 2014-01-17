//on document ready launch the aplication DnDUpload
$(document).on('ready',initDnDUpload());

var drop_zone = null;

function initDnDUpload(){
	//launches modal / for testing
	$('#modal-file-upload').modal('show');
	//check if some HTML5 APIs are available
	if(window.File && window.FileReader && window.FileList && window.Blob){
		//if the APIs are supported create some event listeners
		drop_zone = document.getElementById('drop-files-zone');
		drop_zone.addEventListener('dragover',handleDragOver,false);
		drop_zone.addEventListener('dragenter',handleDragEnter,false);
		drop_zone.addEventListener('drop',handleFileSelect,false);
	} else{
		console.log('Some HTML5 modules are not supported in this browser');
	}
	return;
}

function handleFileSelect(e){
	e.stopPropagation();
	e.preventDefault();
	//remove "upload files" text
	$('#advertising-dnd').fadeOut('fast');
	//restore style of the box
	this.style.opacity = 1.0;
	this.style.borderColor = '#CCC';
	this.style.color = '#CCC';
	//get the files with data transfet html5 object
	var files = e.dataTransfer.files;
	//for each file into array upload into server
	for(var i=0; i < files.length; i++){
		//check if the object mime type is an image
		if(!files[i].type.match('image.*')){
			continue;
		}
		//create an reader object to read each file
		var reader = new FileReader();
		//when the file is completely loaded show into UI and send to server
		reader.onloadend = (function(theFile,i){
			return function(e){
				//create the html object to insert in unordered list tag
				var ls_obj = 
				'<li class="img-thumbnail">'+ 
					'<img src="'+e.target.result+'" alt="'+theFile.name+'" title="'+theFile.name+'"/>'+
					'<div class="progress progress-striped">'+
						'<div id="pgb-'+i+'" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>'+
					'</div>'+
					'<span id="spb-'+i+'"></span>'+
				'</li>';
				$('#list-files-zone').append(ls_obj);

				//create and xml http request (xhr) object for stream data to server
				xhr = new XMLHttpRequest();
				//lets track upload progress
				xhr.upload.addEventListener('progress',function(e){
					$('#pgb-'+i).css('width',((e.loaded/e.total*100)+'%'));
				},false);
				//set content type of response
				xhr.responseType = 'json';
				//open an stream for that url
				xhr.open('POST', base_url + '/filesystem/upload',true);
				//create the form and append data
				var data = new FormData();
				data.append('file',e.target.result);
				data.append('name',theFile.name);
				//send the request to server
				xhr.send(data);
				//state change observer
				xhr.onreadystatechange = function(){
					if(xhr.readyState == 4){
						if(xhr.status == 200){
							console.log(xhr.response);
							if(xhr.response.succeed == true){
								call_alert('alert-success','Los archivos se han subido correctamente',function(){});
							} else{
								call_alert('alert-danger',xhr.response.status,function(){});
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
//on drag enter change the style of the box
function handleDragEnter(e){
	e.preventDefault();
	e.stopPropagation();
	this.style.opacity = 0.8;
	this.style.borderColor = '#2963F8';
	this.style.color = '#2963F8';
	return;
}
//on draag over create copy of files into dataTransfer object
function handleDragOver(e){
	e.stopPropagation();
	e.preventDefault();
	e.dataTransfer.dropEffect = 'copy';
	return;
}
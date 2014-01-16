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
		reader.onloadend = (function(theFile){
			return function(e){
				//create the html object to insert in unordered list tag
				var ls_obj = '<li class="img-thumbnail"><img src="'+e.target.result+'" alt="'+theFile.name+'" title="'+theFile.name+'"/></li>';
				$('#list-files-zone').append(ls_obj);

				//create and xml http request (xhr) object for stream data to server
				xhr = new XMLHttpRequest();
				//open an stream for that url
				xhr.open('POST', base_url + '/filesystem/upload',true);
				//create a custom function to send binary data, for multi-browser support
				XMLHttpRequest.prototype.mySendAsBinary = function(text){
					var data = new ArrayBuffer(text.length);
					var ui8a = new Uint8Array(data,0);
					for(var i=0; i<text.length; i++){ui8a[i] = (text.charCodeAt(i) & 0xff);}
					if(typeof window.Blob == 'function'){
						var blob = new Blob([data]);
					} else{
						var bb = new (window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder)();
						bb.append(data);
						var blob = bb.getBlob();
					}
					//send blob data to server side
					this.send(blob);
				}
				//lets track upload progress
				var eventSource = xhr.upload || xhr;
				eventSource.addEventListener('progress',function(e){
					var position = e.position || e.loaded;
					var total = e.totalSize || e.total;
					var percentage = Math.round((position/total)*100);

					console.log(e);
				});
				//state change observer
				xhr.onreadystatechange = function(){
					if(xhr.readyState == 4){
						if(xhr.status == 200){
							console.log('success');
						} else{
							console.log('error');
						}
					}
				}
				//send the binary data to server
				xhr.mySendAsBinary(e.target.result);
			}
		})(files[i]);
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
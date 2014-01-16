$(document).ready(function() {
	//funcionalidad del colopicker 
	$('#cat_color').ColorPicker({
		color: '#00ff00',
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val('#'+hex.toUpperCase());
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});

	$(document).on('submit','#form-edit-category',function(e){
		e.preventDefault();
		$('#btn-submit').prop('disabled',true);
		$('#loading-img').fadeIn('fast');
		var dataString = $(this).serialize();
		$.ajax({
			url: path + '/save_category',
			type: 'post',
			data: dataString,
			dataType: 'json',
			success: function(response){
				$('#loading-img').fadeOut('slow');
				if(response.succeed == true){
					if(response.code == 200){
						call_alert('alert-success',response.status,function(){
							window.location.href = path + '/categorias';
							return;
						});
					} else{
						call_alert('alert-danger',response.status,function(){});
					}
				} else{
					call_alert('alert-danger',response.status,function(){
						if(response.code == 401){
							window.location = path;
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

});
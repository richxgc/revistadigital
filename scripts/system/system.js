function call_alert(alert_class,alert_text,callback){
	$('#alert-overlay').addClass(alert_class);
	$('#alert-text').html(alert_text);
	$('#alert-overlay').fadeIn('fast',function(){
		setTimeout(function(){
			$('#alert-overlay').fadeOut('slow',function(){$(this).removeClass(alert_class);});
			callback();
		},2000);
	});
}
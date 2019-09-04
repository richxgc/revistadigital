/*
 * Funciones para el scrolling de las dos columnas principales del sitio
 * //http://rocha.la/jQuery-slimScroll --> plugin de scroll
 */
var window_height = 0;
var main_width = 0;
$(document).ready(function() {
	resizeItems();
	$('#navbar-inner').slimScroll({
		position: 'right',
		railVisible: true,
		height: window_height
	});
	$('.slimScrollBar').hide(); //fix issue in the plugin
});

$(window).resize(function(event) {
	resizeItems();
});

function resizeItems(){
	//define el tamaño de la navbar
	window_height = $(window).height() - 40;
	main_width = $('#content').width();
	console.log(main_width);
	$('#navbar').width((main_width*0.24));
	$('#navbar-inner').height(window_height);
}

/*
 * Funciones para modificar y aplicar colores al menu lateral
 */
var back_color;
$(document).ready(function() {
	var active_menu = $('.color-menu.active');
	back_color = active_menu.attr('data-color-menu');
	active_menu.css('background-color',back_color);
	active_menu.children('a').css('color','#fff');
});
$(document).on('mouseover','.color-menu',function(e){
	back_color = $(this).attr('data-color-menu');
	$(this).css('background-color', back_color);
	$(this).children('a').css('color','#fff');
});
$(document).on('mouseleave','.color-menu',function(e){
	if($(this).hasClass('active')){
		return;
	}
	$(this).css('background-color', '#e9e9e9');
	$(this).children('a').css('color','#333');
});

/*
 * Funciones de alerta
 */
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
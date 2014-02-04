
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
	$(this).css('background-color', '#f5f5f5');
	$(this).children('a').css('color','#333');
});
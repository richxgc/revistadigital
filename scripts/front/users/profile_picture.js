$(document).ready(function() {
	var img_width = $('.account-image').width();
	$('.account-image').height(img_width);
});
$(window).resize(function(event) {
	var img_width = $('.account-image').width();
	$('.account-image').height(img_width);
});
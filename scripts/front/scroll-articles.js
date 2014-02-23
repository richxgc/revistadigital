var offset = 0;
var busy = false;
$(window).scroll(function(e) {
	if($(window).scrollTop() == ($(document).height() - $(window).height())){
		if(busy == false){
			busy = true;
			offset += 5;
			busy = getMoreContent();
		}
	}
});
function getMoreContent(){
	var url_request = null;
	var flags = true;
	if(category == null){ url_request = path + '/front/articles/get_front_content/'+offset+'/';} 
	else{url_request = path + '/front/articles/get_front_content/'+offset+'/'+category;}
	$('#loading-content').fadeIn('fast').css('display', 'block');
	$.ajax({
		url: url_request,
		type: 'get',
		dataType: 'html',
		complete: function(){
			$('#loading-content').fadeOut('slow');
		},
		success: function(html){
			if(html){
				$('#articles-content').append(html);
				flags = false;
			} else {
				$('#load-more-content').fadeOut('fast',function(){$(this).remove();});
				$('#nomore-content').fadeIn('fast');
			}
		}
	});
	return flags;
}
$(document).on('click','#load-more-content',function(e){
	e.preventDefault();
	busy = true;
	offset += 5;
	busy = getMoreContent();
});
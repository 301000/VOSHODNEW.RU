jQuery(function($){
	
	var mouse_trig = false;
	$('.djtabs').on('mousedown', function(){
		mouse_trig = true;
	});
	$('.djtabs').on('keydown', function(){
		mouse_trig = false;
	});

	$('.djtabs-title').on('focus', function(){
		if(mouse_trig){
			return;
		}

		var tab_no = $(this).attr('data-tab-no');
		$body = $(this).closest('.djtabs').find('.djtabs-body[data-tab-no="'+tab_no+'"]').first();

		if(!$(this).hasClass('djtabs-active')){
			$(this).trigger('click');
		}

		setTimeout(function(){
			$body.focus();
		}, 0);
	});


	$('.djtabs').on('keyup', function(event){
		if(event.key == 'ArrowLeft'){
			$(this).find('.djtabs-prev').first().children('.djtabs-title').focus();
		}else if(event.key == 'ArrowRight'){
			$(this).find('.djtabs-next').first().children('.djtabs-title').focus();
		}
	});
	
	$('.djtabs-article-group').on('focus', function() {
		$body = $(this).children('.djtabs-article-body').first();

		if(!$(this).hasClass('djtabs-group-active') && !mouse_trig){
			$(this).children('.djtabs-panel').first().trigger('click');
		}

		setTimeout(function(){
			$body.focus();
		});
	});

	$('.accordion-in').on('keyup', function(event){
		$content = $(this).children('.djtabs-group-active');

		if(event.key == 'ArrowUp'){
			if($content.length){
				$prevContent = $content.prev('.djtabs-article-group');
			}else{
				$prevContent = $(this).children('.djtabs-article-group').first();
			}
			$prevContent.focus();
		}else if(event.key == 'ArrowDown'){
			if($content.length){
				$nextContent = $content.next('.djtabs-article-group');
			}else{
				$nextContent = $(this).children('.djtabs-article-group').last();
			}
			$nextContent.focus();
		}
	});
});
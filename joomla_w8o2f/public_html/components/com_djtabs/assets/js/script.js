function djTabsInit(id, layout, opts_new)
{
	var opts = {
		'tab_no': '1',
		'duration': '400',
		'trigger': 'click', // 'mouseenter' 
		'acc_display': 'first_out', // 'all_in'
		'acc_scroll': '', // 'main', 'cat', 'all'
		'vid_auto': 'pause' // 'pauseplay', ''
	};
	jQuery.extend(opts, opts_new);

	var $tabs = jQuery('#'+id);

	if(layout == 'tabs' || opts.acc_display == 'first_out' || opts_new.tab_no > 0){
		handleTabChange($tabs, opts.tab_no, true);
	}else{ // hide all accordion tabs' bodies
		$tabs.children('.djtabs-in-border').each(function(){
			jQuery(this).hide();
		});
	}

	$tabs.find('.djtabs-title').off(opts.trigger).on(opts.trigger, function(){
		if(jQuery(this).closest('.djtabs').attr('id') != id){
			return;
		}
		var i = jQuery(this).attr('data-tab-no');
		handleTabChange($tabs, i, false);
	});

	function handleTabChange($tabs, i, init)
	{
		//var $tab = $tabs.find('.djtabs-title[data-tab-no="'+i+'"]').first();
		var $tab = {};
		$tabs.find('.djtabs-title[data-tab-no="'+i+'"]').each(function(){
			if(jQuery(this).closest('.djtabs').attr('id') != id){
				return;
			}
			$tab = jQuery(this);
		});
		if(!$tab.length){
			var $tab = $tabs.find('.djtabs-title').last();
		}

		$tabParent = $tab.parent();

		$tabParent.siblings().children('.djtabs-title').each(function(){
			jQuery(this).removeClass('djtabs-active');
			jQuery(this).attr('aria-expanded', false);
		});
		$tabParent.siblings().removeClass('djtabs-active-wrapper');

		$tabParent.removeClass('djtabs-prev djtabs-next');
		$tabParent.siblings().removeClass('djtabs-prev djtabs-next');
		$tabParent.prevAll('.djtabs-title-wrapper').first().addClass('djtabs-prev');
		$tabParent.nextAll('.djtabs-title-wrapper').first().addClass('djtabs-next');

		if(layout == 'tabs'){
			$tab.addClass('djtabs-active');
			$tab.attr('aria-expanded', 'true');

			$tab.parent().addClass('djtabs-active-wrapper');

			$tab.closest('.djtabs').find('.djtabs-body').each(function(){
				var $content = jQuery(this);
				if($content.closest('.djtabs').attr('id') != id){
					return;
				}
				if($content.attr('data-tab-no') == i){
					if(init){
						$content.show('normal', showCallback);
					}else{
						$content.fadeIn(opts.duration, showCallback);
					}
					if($content.hasClass('type-video') && opts.vid_auto == 'pauseplay'){
						toggleVideo($content[0], 1);
					}
				}else{
					$content.hide();
					if($content.hasClass('type-video') && !init && (opts.vid_auto == 'pause' || opts.vid_auto == 'pauseplay')){
						toggleVideo($content[0], 0);
					}
				}
			});
		}else{
			$tab.toggleClass('djtabs-active');
			$tab.attr('aria-expanded', function(index, attr){
				return attr == 'true' ? 'false' : 'true';
			});


			$tab.parent().toggleClass('djtabs-active-wrapper');

			//$tab.closest('.djtabs').find('.djtabs-in-border').each(function(){
			$tab.closest('.djtabs').children('.djtabs-in-border').each(function(){
				var $content = jQuery(this);
				if($content.closest('.djtabs').attr('id') != id){
					return;
				}
				if($content.attr('data-no') == i){
					if(init){
						$content.show('normal', showCallback);
					}else{
						$content.slideToggle(opts.duration, function(){
							if((opts.acc_scroll == 'all' || opts.acc_scroll == 'main') && $tab.hasClass('djtabs-active')){
								scrollToItem($tab);
							}
							showCallback();
						});
					}
					if($content.find('.djtabs-body').first().hasClass('type-video') && opts.vid_auto == 'pauseplay'){
						toggleVideo($content.find('.djtabs-body').first()[0], 1);
					}
					if($content.find('.djtabs-body').first().hasClass('type-video') && $tab.attr('aria-expanded') == 'false' && (opts.vid_auto == 'pause' || opts.vid_auto == 'pauseplay')){ // pausing video on acc. close
						toggleVideo($content.find('.djtabs-body').first()[0], 0);
					}
				}else{
					if(init){
						$content.hide();
					}else{
						$content.slideUp(opts.duration);
						if($content.find('.djtabs-body').first().hasClass('type-video') && (opts.vid_auto == 'pause' || opts.vid_auto == 'pauseplay')){
							toggleVideo($content.find('.djtabs-body').first()[0], 0);
						}
					}
				}
			});
		}
	}

	$tabs.find('.accordion_first_out, .accordion_all_in').each(function(){
		if(jQuery(this).closest('.djtabs').attr('id') != id){
			return;
		}

		var $acc = jQuery(this);

		//$acc.find('.djtabs-panel').each(function(key){
		$acc.children('.djtabs-article-group').children('.djtabs-panel').each(function(key){
			var i = key + 1;
			jQuery(this).attr('data-panel-no', i);

			jQuery(this).on('click', function(){
				handlePanelChange($acc, i, false);
			});
		});

		if($acc.hasClass('accordion_first_out')){
			handlePanelChange($acc, 1, true); // needs to be fired after assigning 'data-panel-no' attr
		}else{ // hide all panels' bodies
			$acc.find('.djtabs-article-body').hide();
		}

		function handlePanelChange($acc, i, init)
		{
			var $tab = $acc.find('.djtabs-panel[data-panel-no="'+i+'"]').first();

			$tab.toggleClass('djtabs-panel-active');
			$tab.parent().siblings().children('.djtabs-panel').removeClass('djtabs-panel-active');
	
			$tab.parent().toggleClass('djtabs-group-active');
			$tab.parent().siblings().removeClass('djtabs-group-active');

			$tab.parent().siblings().children('.djtabs-panel').attr('aria-expanded', false);
			$tab.attr('aria-expanded', function(index, attr){
				return attr == 'true' ? 'false' : 'true';
			});
	
			if(init){
				$acc.find('.djtabs-article-body').not('[data-no="'+i+'"]').hide();
				$acc.find('.djtabs-article-body[data-no="'+i+'"]').show('normal', showCallback);
			}else{
				$acc.find('.djtabs-article-body').not('[data-no="'+i+'"]').slideUp(opts.duration);
				$acc.find('.djtabs-article-body[data-no="'+i+'"]').slideToggle(opts.duration, function(){
					if((opts.acc_scroll == 'all' || opts.acc_scroll == 'cat') && $tab.hasClass('djtabs-panel-active')){
						scrollToItem($tab);
					}
					showCallback();
				});
			}
		}
	});

	function showCallback()
	{
		jQuery(this).find('iframe:not(.reloaded)').attr( 'src', function ( i, val ) { return val; }).addClass('reloaded');
		
		if(typeof showCallbackCustom === 'function'){
			showCallbackCustom(this);
		}
	}

	if(layout == 'tabs'){
		checkTabsWidth();
		jQuery(window).resize(function(){
			checkTabsWidth();
		});
	}

	function checkTabsWidth()
	{
		if($tabs.prop('data-width-changed') || !$tabs.outerWidth()){
			return false;
		}

		var tabs_width_sum = 0;
		var margins = 0;
		var $tab_items = jQuery('#'+id+' > .tabs-wrapper > .djtabs-title-wrapper > .djtabs-title');

		$tab_items.each(function(){
			tabs_width_sum += jQuery(this).outerWidth(true);
			margins +=  parseInt(jQuery(this).css('margin-left')) + parseInt(jQuery(this).css('margin-right'));
		})

		if(tabs_width_sum > $tabs.outerWidth() || $tabs.parent().hasClass('tabs-full-row') || $tabs.closest('.jm-module').hasClass('tabs-full-row')){ // 'full-tabs-ms'
			$tab_items.css('box-sizing', 'border-box');
			if(margins){
				$tab_items.css('width', 'calc('+(100 / $tab_items.length)+'% - '+(margins / $tab_items.length)+'px)');
			}else{
				$tab_items.css('width', (100 / $tab_items.length)+'%');
			}
			$tabs.prop('data-width-changed', true);
		}
	}

	function scrollToItem($tab)
	{
		jQuery('html, body').animate({
			scrollTop: $tab.offset().top
		}, 500);
	}

	function toggleVideo(el, task)
	{
		var post_msg;
		var iframe;
		var djVideoWrapper = el.getElementsByClassName("djVideoWrapper")[0];
		
		if (djVideoWrapper){
			
			iframe = djVideoWrapper.getElementsByTagName("iframe")[0];
			
			if (iframe){
				
				if(iframe.src.includes('vimeo'))
					post_msg = task ? '"method":"play"' : '"method":"pause"';
				else if(iframe.src.includes('youtube'))
					post_msg = task ? '"func":"playVideo"' : '"func":"pauseVideo"';

				if(post_msg)
					iframe.contentWindow.postMessage('{"event":"command",'+post_msg+'}', '*');
			}
		}
	}

	jQuery('#'+id+' > .djtabs-in-border > .djtabs-in > .djtabs-body').css('overflow', 'auto');
	//$tabs.find('.djtabs-body').css('overflow', 'auto');
	$tabs.css('visibility', 'visible');
	jQuery('#'+id+'_loading').remove();
}
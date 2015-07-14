(function($) {

	var tablet = 1023;
	var mobile = 768;

	// addclass mobile and desktop
	var onMobile = function(){
		if ($(window).width() <= tablet) {
			$('#body-wrapper').addClass('toggle').find('.burger').removeClass('open');
		}else{
			$('#body-wrapper').removeClass('toggle').find('.burger').addClass('open');
		}
	};

	var adminMobile = function(){
		$('#layer-sidebar').each( function(){
			var admin = $(this).parents('body').find('.navbar-collapse');

			admin.clone().appendTo( "#layer-sidebar" );			
		});
		
	};

	// menu animated icon
	var shuweeMenu = function(){
		$('#body-wrapper').each( function(){
			var t = $(this);
			var icon = t.find('.burger');

			icon.on('click', function(){
				if(t.hasClass('toggle')){
					t.removeClass('toggle');
					$(this).addClass('open');
				}else{
					t.addClass('toggle');
					$(this).removeClass('open');
				}
			});
		});
	};

	// responsive table plugin
	var stacktablePlugin = function(){
		$('#responsive-example-table').stacktable({myClass:'stacktable small-only'});
	};

	// sidebar height 100%
	var documentHeight = function(){
		var documentH = $( document ).height();

		$('#layer-sidebar').height(documentH);
	};

	// sidebar open/collapse
	var collapse = function(){
		$('#layer-sidebar').each( function(){
			var item = $(this).find('ul li span');
			var itemP = item.parent();
			var itemChild = $(this).find('ul li > span');

			// open/collapse menu on sidebar
			item.on('click', function(){
				if($(this).hasClass('js-active')){
					$(this).removeClass('js-active').parent().find('ul').slideUp();
				}else{
					$(this).addClass('js-active').parent().find('ul').slideDown();
				}
			});

			// check if current exist if yes let the ul open
			itemP.each( function(){
				if($(this).hasClass('current_ancestor')){
					$(this).find('span').addClass('js-active');
				}else{
					$(this).find('span').removeClass('js-active');
				}
			});
		});
	};

	$(document).ready(function() {
		shuweeMenu();
		stacktablePlugin();
		onMobile();
		adminMobile();
		documentHeight();
		collapse();

		$(window).bind('resize', function(){
			onMobile();
		});
	});

})(jQuery);
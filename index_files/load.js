$(function(){

	//mailing list	
	$("#mailing-list-subscribe-full").pkdDataSignup();
	
	//sidebar
	$("#sidebar-subscribe").pkdDataSignup();
	$("#sidebar-search-refine-inner").mmenu({
		position : "right",
		subtitle_text: "Back",
		zposition : ""
	}, {
		clone : true,
		pageSelector: '#outer-page-wrapper',
		bootstrap_compat : false,
		bootstrap_classes: 'visible-xs hidden-xs',
		panelClass: 'refine-panel',
		isMenu: false,
		pageNodetype: 'ul',
		panelNodetype: 'ul',
		subnavLockingEnable: false
	
	});
		
	//automatically expand category if category-list-open is present
	$("a.category-list-open").expandCategory();
	
	//add touch ability to home carousel
	if($(".carousel-inner").length > 0) {
		//Enable carousel swiping
		$(".carousel-inner").swipe( {
			swipeLeft:function(event, direction, distance, duration, fingerCount) {
				$(this).parent().carousel('next');
			},
			swipeRight: function() {
				$(this).parent().carousel('prev');
			},
			threshold:55
		});
	}
	
	//search results
	// Enable onchange event on results view buttons & sortby
	if($("#advSearchResultsView").length > 0) {
		$('.viewRadio').change(function(){
			$("#advSearchResultsView").submit();
		});
	}
	
	if($('.gallery-sort-tools').length > 0) {
		$('.gallery-sort-tools select').on('change', function(){
			$(this).closest("form").submit();
		});
	}
	
	//nav bar
	// Initialize mobile menu			
	$("#navbar").mmenu({
		position : "left",
		subtitle_text: "Back",
		zposition : "",
		header: { add: true, content: '<img src="/images/logo.png" class="img-responsive" alt="'+company_name+'">' }
	}, {
		clone : true,
		pageSelector: '#outer-page-wrapper',
		bootstrap_compat : true,
		bootstrap_classes: 'visible-xs'
	});
	
	$('#navbar-container').affix({
		offset: {
			top: $('#navbar-container').offset().top
		}
	});
	
	$(window).on('scroll resize', function(event){	
		if($('#navbar-container').hasClass('affix')) {
			$('body').addClass('navbar-affixed');
		} else {
			$('body').removeClass('navbar-affixed');
		}
      		if(event.type == 'resize' && !$('body').hasClass('navbar-affixed'))
      			$('#navbar-container').data('bs.affix').options.offset = $('#navbar-container').offset().top;
	});
	
	// Superfish Dropdown	
	var navmenu = $('.nav').superfish({
		//cssArrows: false
	});
	
});
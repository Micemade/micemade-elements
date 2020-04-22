"use strict";

jQuery.noConflict();

(function ($) {
  "use strict";

  var isEditMode = false;
  /**
   *	FILTER ITEMS (Isotope) :
   *	var function filterItems
   *	var arg container
   *	var arg filter
   */

  /* 
   (function( $ ){
  		window.filterItems = function( container, filter ) {
  		
  		container.imagesLoaded( function(){
  		// init Isotope
  			container.isotope({
  				itemSelector: '.item',
  				layoutMode: 'masonry',
  				containerStyle: {
  					position: 'relative',
  					overflow: 'hidden'
  				},
  				transitionDuration: '0.5s'
  			});
  			// filter items on button click
  			if( filter ) {
  				filter.on( 'click', 'a', function() {
  					var filterValue = $(this).attr('data-filter');
  					container.isotope({ filter: filterValue });
  				});
  			}
  			
  			container.on( 'arrangeComplete', function() { 
  				if( $.waypoints ) {
  					$.waypoints('refresh');
  				}
  			});
  		
  		});
  	};
  	
  })( jQuery );
  */

  /**
   * Micemade Tabs - display WC products in tabs
   */

  var micemadeElementsTabs = function micemadeElementsTabs() {
    var tabs = $('.micemade-elements_tabs');
    tabs.each(function () {
      var $_Tabs = $(this); // Single tabs holder

      var tabsWrapper = $_Tabs.find('.tabs-wrapper'),
          tabTitles = tabsWrapper.find('.tab-title'),
          tabsContent = $_Tabs.find('.tabs-content-wrapper'),
          content = tabsContent.find('.tab-content');
      tabTitles.on('click touchstart', function (event) {
        $(this).addClass('active');
        $(this).siblings().removeClass('active'); // Hide inactive tab titles and show active one and content

        var tab = $(this).data('tab');
        content.not('.tab-' + tab).css('display', 'none').removeClass('active');
        tabsContent.find('.tab-' + tab).fadeIn().addClass('active');
      });
    });
  };
  /**
   *  SWIPER SLIDER
   */


  var micemadeElementsSwiper = function micemadeElementsSwiper(rootElement) {
    var root = $('body');

    if (rootElement) {
      root = $(rootElement);
    }

    var swiperContainers = '',
        swiperContainers = root.find('.swiper-container');

    if (!swiperContainers.length) {
      return;
    }

    swiperContainers.each(function (index, element) {
      var sliderContainer = $(this); // Take only MM Elements sliders.

      if (!sliderContainer.hasClass('micemade-elements__sliders')) {
        return true;
      } // Slider settings (slides per view, space, pagination ...)
      // var settings = sliderContainer.closest('.elementor-element').data('settings'),


      var settings = sliderContainer.data('settings'),
          pps = settings.posts_per_slide,
          pps_t = settings.posts_per_slide_tab,
          pps_m = settings.posts_per_slide_mob,
          space = settings.space,
          space_t = settings.space_tablet,
          space_m = settings.space_mobile,
          pagin = settings.pagination,
          autoplay = settings.autoplay,
          loop = settings.loop; // Main swiper arguments

      var mainSwiperArgs = {
        slideActiveClass: 'active',
        autoHeight: true,
        effect: 'slide',
        grabCursor: true,
        loop: loop,
        pagination: {
          el: '.swiper-pagination',
          type: pagin,
          clickable: true
        },
        navigation: {
          prevEl: sliderContainer.find('.swiper-button-prev')[0],
          nextEl: sliderContainer.find('.swiper-button-next')[0]
        },
        breakpoints: {
          // when window width is >= 320px
          320: {
            slidesPerView: pps_m ? pps_m : 1,
            spaceBetween: space_m ? space_m : 0
          },
          // when window width is >= 480px
          480: {
            slidesPerView: pps_t ? pps_t : 2,
            spaceBetween: space_t ? space_t : 20
          },
          // when window width is >= 640px
          768: {
            slidesPerView: pps ? pps : 1,
            spaceBetween: space ? space : 30
          }
        },
        on: {
          transitionStart: function transitionStart() {},
          transitionEnd: function transitionEnd() {}
        }
      }; // If autoplay is enabled.

      var autoplayArgs = {};

      if (autoplay && autoplay >= 1000) {
        var autoplayArgs = {
          autoplay: {
            delay: autoplay
          }
        };
      } // Join arg objects.


      var swiperArgs = $.extend(mainSwiperArgs, autoplayArgs); // Initialize Swiper.

      var micemadeSwiper = new Swiper(sliderContainer, swiperArgs);
      /*
      micemadeSwiper.on( 'transitionStart', function() {
      	var slides  = this.slides,
      		current = this.realIndex;
      			console.log(elementorFrontend);
      	slides.each( function( index, element ) {
      			var eachSlide = $(this),
      			elmData   = eachSlide.find('[data-settings]');
      			console.log(elementorFrontend.getItems);
      			// elementorFrontend.waypoint(
      			// 	element,
      			// 	function() {
      			// 		console.log($(this));
      			// 	}
      			// );
      			
      		// if( ! eachSlide.hasClass( 'active' ) ) {
      		// 	animatedEls.removeClass( 'animated' );
      		// } else {
      		// 	window.setTimeout( function () {
      		// 		animatedEls.addClass( 'animated' ).addClass( animationName )
      		// 	}, 1);
      		// }
      			// if( index === this.activeIndex ) {}
      	} );
      	
      });
      */
      //swiper.on('init', function() { /* do something */});
      //swiper.init();
    });
  }; // Helper function for Micemade slider (Swiper).

  /* var mmeAnimate = function( element, animationName ) {
  	if ( element.hasClass( 'animated' ) ) {
  		element .removeClass( 'animated' );
  	}
   
  	if ( element.hasClass( animationName )) {
  		element.removeClass( animationName );
  	}
   
  	window.setTimeout( function () {
  		element.addClass( 'animated' ).addClass( animationName )
  	}, 1);
  } */


  (function ($) {
    /**
     * Make ID - create random ID
     */
    window.mmMakeId = function mmMakeId() {
      var text = "";
      var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

      for (var i = 0; i < 5; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
      }

      return text;
    };
  })(jQuery);

  (function ($) {
    /**
     * Detect Micemade Elements widgets when in viewport
     */
    window.micemadeInViewport = function micemadeInViewportFunc() {
      $('.mm-enter-animate').whenInViewport(function ($paragraph) {
        var animType = $paragraph.data('anim');
        var delay = $paragraph.data('delay');
        setTimeout(function () {
          $paragraph.addClass(animType).addClass('anim-done');
        }, delay);
      });
    };
  })(jQuery);

  (function ($) {
    window.initMicemadeMenuNav = function micemadeMenuNav() {
      var navMenuObj = $('.micemade-elements-nav-menu');
      navMenuObj.each(function () {
        // Start Smartmenus plugin
        var $_this = $(this).smartmenus({
          showTimeout: 0,
          hideDuration: 0
        });

        jQuery.SmartMenus.prototype.isCSSOn = function () {
          return true;
        };
      });
    };
  })(jQuery);

  (function ($) {
    window.megaMenuWatch = function () {
      var mutationObserver = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
          var target = mutation.target,
              name = mutation.attributeName,
              value = target.getAttribute(name);

          if (value == 'true') {
            var restartMicemadeElementSwiper = micemadeElementsSwiper('.mega-menu');
          } else {
            mutationObserver.disconnect();
          }
        });
      });
      var megaMenus = document.getElementsByClassName('mega-menu');
      Array.prototype.forEach.call(megaMenus, function (el) {
        mutationObserver.observe(el, {
          attributes: true,
          attributeFilter: ['aria-expanded']
        });
      });
    };
  })(jQuery);
  /**
   * Micemade WC Categories Menu.
   */


  var wcCategoriesMenu = function wcCategoriesMenu() {
    var wcCatMenuToggle = $('.micemade-elements_wc_cat_menu').find('.sub-toggler');
    wcCatMenuToggle.each(function () {
      var $_this = $(this),
          liItem = $_this.parent().parent(),
          submenu = liItem.find(".children"),
          siblings = liItem.siblings().find(".sub-toggler");
      $_this.on('click', function (e) {
        e.preventDefault();
        siblings.removeClass("activeparent");
        siblings.parent().next(".children").removeClass("active");

        if (!$_this.hasClass("activeparent")) {
          submenu.addClass("active");
          $_this.addClass("activeparent");
        } else {
          submenu.removeClass("active").delay(200);
          $_this.removeClass("activeparent");
        }
      });
    });
  }; //( function( $ ){})( jQuery );
  // Hook into Elementor JS hooks.


  $(window).on('elementor/frontend/init', function () {
    if (elementorFrontend.isEditMode()) {
      isEditMode = true;
    } // Start Micemade Products Slider


    elementorFrontend.hooks.addAction('frontend/element_ready/micemade-wc-products-slider.default', micemadeElementsSwiper); // Start Micemade Products Tab

    elementorFrontend.hooks.addAction('frontend/element_ready/micemade-wc-products-tabs.default', micemadeElementsTabs); // Start Micemade Product Categories Menu

    elementorFrontend.hooks.addAction('frontend/element_ready/micemade-wc-cat-menu.default', wcCategoriesMenu);
  });

  (function ($) {
    window.initMicemadeElements = function () {
      var startMicemadeInViewport = window.micemadeInViewport();
      var startMicemadeMenuNav = window.initMicemadeMenuNav();
      var startMicemadeMegaMenuWatch = window.megaMenuWatch();
    };
  })(jQuery);

  $(document).ready(function () {
    var initMicemadeElements = window.initMicemadeElements();
    /**
     * Test for mobile broswers
     */

    var testMobile;
    var isMobile = {
      Android: function Android() {
        return navigator.userAgent.match(/Android/i);
      },
      BlackBerry: function BlackBerry() {
        return navigator.userAgent.match(/BlackBerry/i);
      },
      iOS: function iOS() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
      },
      Opera: function Opera() {
        return navigator.userAgent.match(/Opera Mini/i);
      },
      Windows: function Windows() {
        return navigator.userAgent.match(/IEMobile/i);
      },
      any: function any() {
        return isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows();
      }
    };
    /**
     *  Add class for mobile browsers
     */

    if (isMobile.any()) {
      $('body').addClass('micemade-elements-is-mobile');
    }
    /**
     *  Parallax for all "parallax" sections/columns selectors
     */


    var parallax = function parallax() {
      testMobile = isMobile.any();

      if (testMobile == null) {
        $(".parallax").parallax("50%", 0.3);
      }
    }; // Dom Ready


    $(function () {
      parallax();
    });
    /**
     * AJAX LOADING POSTS
     *
     */

    var $posts_holder = $('.micemade-elements-load-more');
    $posts_holder.each(function () {
      var $_posts_holder = $(this);
      var $loader = $_posts_holder.next('.micemade-elements_more-posts-wrap').find('.more_posts'),
          postoptions = $_posts_holder.find('.posts-grid-settings').data('postoptions'),
          post_type = postoptions.post_type,
          taxonomy = postoptions.taxonomy,
          ppp = postoptions.ppp,
          sticky = postoptions.sticky,
          categories = postoptions.categories,
          style = postoptions.style,
          show_thumb = postoptions.show_thumb,
          img_format = postoptions.img_format,
          excerpt = postoptions.excerpt,
          excerpt_limit = postoptions.excerpt_limit,
          meta = postoptions.meta,
          meta_ordering = postoptions.meta_ordering,
          css_class = postoptions.css_class,
          grid = postoptions.grid,
          startoffset = postoptions.startoffset,
          offset = $_posts_holder.find('.post').length;
      $loader.on('click', load_ajax_posts);

      function load_ajax_posts(e) {
        e.preventDefault();

        if (!($loader.hasClass('post_loading_loader') || $loader.hasClass('no_more_posts'))) {
          $.ajax({
            type: 'POST',
            dataType: 'html',
            url: micemadeJsLocalize.ajaxurl,
            data: {
              'post_type': post_type,
              'taxonomy': taxonomy,
              'ppp': ppp,
              'sticky': sticky,
              'categories': categories,
              'style': style,
              'show_thumb': show_thumb,
              'img_format': img_format,
              'excerpt': excerpt,
              'excerpt_limit': excerpt_limit,
              'meta': meta,
              'meta_ordering': meta_ordering,
              'css_class': css_class,
              'grid': grid,
              'offset': offset + startoffset,
              'action': 'micemade_elements_more_post_ajax'
            },
            beforeSend: function beforeSend() {
              $loader.addClass('post_loading_loader').html(micemadeJsLocalize.loadingposts);
            },
            success: function success(data) {
              var $data = $(data);

              if ($data.length) {
                var $newElements = $data.css({
                  opacity: 0
                });
                $_posts_holder.append($newElements);
                $newElements.animate({
                  opacity: 1
                });
                $loader.removeClass('post_loading_loader').html(micemadeJsLocalize.loadmore);
              } else {
                $loader.removeClass('post_loading_loader').addClass('no_more_posts disabled').html(micemadeJsLocalize.noposts);
              }
            },
            error: function error(jqXHR, textStatus, errorThrown) {
              $loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
            }
          });
        }

        offset += ppp;
        return false;
      }
    }); // end $posts_holder.each
    // Restart micemadeInViewport when element changes
    // Elementor editor only

    var isEditor = $('.elementor-editor-active');

    if (isEditor.length) {
      elementorFrontend.hooks.addAction('frontend/element_ready/micemade-wc-categories.default', function ($scope) {
        var restartVieportAfterReload = window.micemadeInViewport();
      });
    }
  }); // end doc ready
})(jQuery);
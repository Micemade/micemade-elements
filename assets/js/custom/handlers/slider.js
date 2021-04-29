class MicemadeSliderHandler extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
		return {
			selectors: {
				carousel: '.micemade-elements_slider',
				slideContent: '.swiper-slide',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		const elements = {
			$carousel: this.$element.find( selectors.carousel ),
		};

		elements.$swiperSlides = elements.$carousel.find( selectors.slideContent );

		return elements;
	}

	getSlidesCount() {
		return this.elements.$swiperSlides.length;
	}

	getSwiperSettings() {

		const elementSettings = this.getElementSettings(),
			slidesToShow = +elementSettings.posts_per_slide || 1,
			slidesToShowTab = +elementSettings.posts_per_slide_tab || 1,
			slidesToShowMob = +elementSettings.posts_per_slide_mob || 1,
			isSingleSlide = 1 === slidesToShow,
			elementorBreakpoints = elementorFrontend.config.breakpoints,
			watchSlidesProgress = true,
			watchSlidesVisibility = true;

			this.transitionSpeed = elementSettings.speed;
			

		const swiperOptions = {
			slidesPerView: slidesToShow,
			handleElementorBreakpoints: true,
			autoHeight: true,
			loop: 'yes' === elementSettings.infinite,
			speed: elementSettings.speed,
			spaceBetween: elementSettings.space ? elementSettings.space : 0,
		};

		swiperOptions.breakpoints = {};

		swiperOptions.breakpoints[ elementorBreakpoints.md ] = {
			slidesPerView: slidesToShowMob,
			slidesPerGroup: 1,
		};

		swiperOptions.breakpoints[ elementorBreakpoints.lg ] = {
			slidesPerView: slidesToShowTab,
			slidesPerGroup: 1,
		};

		if ( ! this.isEdit && 'yes' === elementSettings.autoplay ) {
			swiperOptions.autoplay = {
				delay: elementSettings.autoplay_speed,
				disableOnInteraction: 'yes' === elementSettings.pause_on_interaction,
			};
		}

		if ( isSingleSlide ) {
			swiperOptions.effect = elementSettings.effect;
			if ( 'fade' === elementSettings.effect ) {
				swiperOptions.fadeEffect = { crossFade: true };
			}
		} else {
			swiperOptions.slidesPerGroup = +elementSettings.slides_to_scroll || 1;
		}

		const showArrows = 'yes' === elementSettings.buttons,
			pagination = 'none' !== elementSettings.pagination;

		
		const carouselInstance = this.getDefaultElements().$carousel;
		
		if ( showArrows ) {
			swiperOptions.navigation = {
				prevEl: carouselInstance.find('>.swiper-button-prev'),
				nextEl: carouselInstance.find('>.swiper-button-next'),
			};
		}

		if ( pagination ) {
			swiperOptions.pagination = {
				el: carouselInstance.find( '>.swiper-pagination' ),
				type: elementSettings.pagination,
				clickable: true,
			};
		}

		swiperOptions.on = {

			/* slideChange: () => {
				this.swiper.slides.each(
					( item, slide ) => {
						let slideClasses = slide.classList;
						if( slideClasses.contains('swiper-slide-active') ) {
							
							this.wcCategories( slide, slideClasses );
						}
					}
				);
			}, */
			transitionStart: () => {
				if( this.swiper === undefined ) return;
				// let slides = this.swiper.slides;
				let slides = this.getDefaultElements().$swiperSlides;
				slides.each(
					( item, slide ) => {
						let slideClasses = slide.classList;
						if( slideClasses.contains('swiper-slide-active') && ! slideClasses.contains('swiper-slide-prev') || ! slideClasses.contains('swiper-slide-next'))  {
							this.bigSliderAnimations( slide, slideClasses );
							this.wcCategories( slide, slideClasses );
						} else {
							this.clearInvisSlides( slide );
						}
					}
				);
			}
		}

		return swiperOptions;
	}

	bigSliderAnimations( slide ) {

		const elemElem = slide.getElementsByClassName('elementor-element');

		for ( var i = 0; i < elemElem.length; i++ ) {

			let settings = JSON.parse( elemElem[i].getAttribute('data-settings') );
			if ( settings === null ) { continue; }

			if ( settings.hasOwnProperty('_animation') || settings.hasOwnProperty('animation') ) {
			//if ( settings._animation !== undefined || settings.animation !== undefined ) {
				var animType = settings._animation ? settings._animation : settings.animation;
				if ( ! animType ) { continue; }

				elemElem[i].classList.remove('elementor-invisible');

				elemElem[i].style.transitionDuration = '';
				elemElem[i].style.opacity            = '';

				if( settings.animation_delay ) {
					var delay = settings.animation_delay /1000;
					elemElem[i].style.animationDelay = delay.toString() + 's';
				}
				elemElem[i].classList.add(animType);
				elemElem[i].classList.add('animated');

			}
		}

	}

	clearInvisSlides( slide ) {
		const elemElem = slide.getElementsByClassName('elementor-element');
		const speed    = this.transitionSpeed / 1000;

		for ( var i = 0; i < elemElem.length; i++ ) {
			let settings = JSON.parse(elemElem[i].getAttribute('data-settings'));
			if ( settings === null ) { continue; }
			
			if ( settings.hasOwnProperty('_animation') || settings.hasOwnProperty('animation') ) {
			// if ( settings._animation !== undefined || settings.animation !== undefined ) {
				var animType = settings._animation ? settings._animation : settings.animation;
				if ( ! animType ) { continue; }

				elemElem[i].style.transitionDuration = speed.toString() + 's';
				elemElem[i].style.opacity            = 0;

				elemElem[i].classList.add('elementor-invisible');
				elemElem[i].classList.remove( animType );
				elemElem[i].classList.remove('animated');

			}
		}
	}

	wcCategories( slide, slideClasses ) {
		// const settings = JSON.parse(index.dataset.settings);
		const settings = JSON.parse( slide.getAttribute('data-settings') ); // supposably faster then dataset.settings.
		// If there is no data-settings attribute, quit.
		if ( settings === null ) { return true; }
		
		// WC CATEGORIES WIDGET - If element is a category AND slide has '_animation' setting.
		// In case entering animation is set, discard animations, except for the first visible slides.
		if ( settings.hasOwnProperty('_enter_animation') && slideClasses.contains('category')) {
			const animType = settings._enter_animation;
			if ( ! animType ) { return true; }
			// Remove entering animation from slides not visible at slider init.
			if( slideClasses.contains( 'mm-enter-animate' ) ) {
				slideClasses.remove( 'mm-enter-animate' );
			}
		}
	}

	updateSpaceBetween() {
		this.swiper.params.spaceBetween = this.getElementSettings( 'space' ).size || 0;
		this.swiper.update();
	}

	onInit( ...args ) {
		super.onInit( ...args );

		const elementSettings = this.getElementSettings();

		if ( ! this.elements.$carousel.length || 2 > this.elements.$swiperSlides.length ) {
			return;
		}

		// Since Elementor v.3.1.0 - optimized asset loading.
		// https://developers.elementor.com/experiment-optimized-asset-loading/
		if ( 'undefined' === typeof Swiper ) {
			const asyncSwiper = elementorFrontend.utils.swiper;
			new asyncSwiper( this.elements.$carousel, this.getSwiperSettings() ).then( ( newSwiperInstance ) => {
				// console.log( 'New Swiper instance is ready: ', newSwiperInstance );
				this.swiper = newSwiperInstance;
			} );
		} else {
			// Legacy (< v.3.1.0) Swiper instantiation.
			// console.log( 'Swiper global variable is ready, create a new instance: ', Swiper );
			this.swiper = new Swiper( this.elements.$carousel, this.getSwiperSettings() );
		}

		// Expose the swiper instance in the frontend
		this.elements.$carousel.data( 'swiper', this.swiper );

		if ( 'yes' === elementSettings.pause_on_hover ) {
			this.elements.$carousel.on( {
				mouseenter: () => {
					this.swiper.autoplay.stop();
				},
				mouseleave: () => {
					this.swiper.autoplay.start();
				},
			} );
		}
	}

	onElementChange( propertyName ) {
		if ( 0 === propertyName.indexOf( 'space' ) ) {
			this.updateSpaceBetween();
		} else if ( 'arrows_position' === propertyName ) {
			this.swiper.update();
		}
	}

	onEditSettingsChange( propertyName ) {
		if ( 'activeItemIndex' === propertyName ) {
			this.swiper.slideToLoop( this.getEditSettings( 'activeItemIndex' ) - 1 );
		}
	}
	
}

jQuery( window ).on( 'elementor/frontend/init', () => {

	elementorFrontend.elementsHandler.attachHandler( 'micemade-slider', MicemadeSliderHandler );
	elementorFrontend.elementsHandler.attachHandler( 'micemade-wc-categories', MicemadeSliderHandler );
	elementorFrontend.elementsHandler.attachHandler( 'micemade-wc-products-slider', MicemadeSliderHandler );
	elementorFrontend.elementsHandler.attachHandler( 'micemade-posts-slider', MicemadeSliderHandler );

	/*
	const addHandler = ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( MicemadeSliderHandler, {
			$element,
		} );
	}; 

	elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-slider.default', addHandler );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-categories.default', addHandler );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-products-slider.default', addHandler );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-posts-slider.default', addHandler );
	*/
 } );
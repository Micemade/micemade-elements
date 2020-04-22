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
			slidesToShow = 1,
			isSingleSlide = 1 === slidesToShow,
			defaultLGDevicesSlidesCount = isSingleSlide ? 1 : 2,
			elementorBreakpoints = elementorFrontend.config.breakpoints;

		const swiperOptions = {
			slidesPerView: slidesToShow,
			loop: 'yes' === elementSettings.infinite,
			speed: elementSettings.speed,
			handleElementorBreakpoints: true,
		};

		swiperOptions.breakpoints = {};

		swiperOptions.breakpoints[ elementorBreakpoints.md ] = {
			slidesPerView: 1,
			slidesPerGroup: 1,
		};

		swiperOptions.breakpoints[ elementorBreakpoints.lg ] = {
			slidesPerView: defaultLGDevicesSlidesCount,
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

		const showArrows = 'yes' === elementSettings.slider_arrows,
			pagination = 'none' !== elementSettings.pagination;

		if ( showArrows ) {
			swiperOptions.navigation = {
				prevEl: '.swiper-button-prev',
				nextEl: '.swiper-button-next',
			};
		}

		if ( pagination ) {
			swiperOptions.pagination = {
				el: '.swiper-pagination',
				type: pagination,
				clickable: true,
			};
		}

		return swiperOptions;
	}

	updateSpaceBetween() {
		this.swiper.params.spaceBetween = this.getElementSettings( 'image_spacing_custom' ).size || 0;

		this.swiper.update();
	}

	onInit( ...args ) {
		super.onInit( ...args );

		const elementSettings = this.getElementSettings();

		if ( ! this.elements.$carousel.length || 2 > this.elements.$swiperSlides.length ) {
			return;
		}

		this.swiper = new Swiper( this.elements.$carousel, this.getSwiperSettings() );

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

/* export default ( $scope ) => {
	elementorFrontend.elementsHandler.addHandler( MicemadeSliderHandler, { $element: $scope } );
};
 */
jQuery( window ).on( 'elementor/frontend/init', () => {
	const addHandler = ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( MicemadeSliderHandler, {
			$element,
		} );
	};
 
	elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-slider.default', addHandler );
 } );
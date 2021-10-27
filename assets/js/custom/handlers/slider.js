class MicemadeSliderHandler extends elementorModules.frontend.handlers
	.SwiperBase {
	getDefaultSettings() {
		return {
			selectors: {
				carousel: ".micemade-elements_slider",
				slideContent: "> .swiper-wrapper > .swiper-slide",
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings("selectors");
		const elements = {
			$carousel: this.$element.find(selectors.carousel),
		};

		elements.$swiperSlides = elements.$carousel.find(selectors.slideContent);
		return elements;
	}

	getSlidesCount() {
		return this.elements.$swiperSlides.length;
	}

	getMicemadeElementType() {
		let NamedNodeMapItem =
			this.elements.$carousel[0].attributes.item("data-mme-widget");
		return NamedNodeMapItem.nodeValue;
	}

	getSliderId() {
		let carousels = this.elements.$carousel;
		if (0 !== carousels.length) {
			return carousels[0].id;
		}
	}

	getSwiperSettings() {
		const elementSettings = this.getElementSettings(),
			elementorVersion = elementorFrontend.config.version, // get Elementor version.
			// No. of slides to show.
			slidesToShow = +elementSettings.posts_per_slide || 1,
			slidesToShowTab = +elementSettings.posts_per_slide_tab || 1,
			slidesToShowMob = +elementSettings.posts_per_slide_mob || 1,
			// No. of slides to scroll.
			slidesToScroll = +elementSettings.posts_to_scroll || 1,
			slidesToScrollTab = +elementSettings.posts_to_scroll_tab || 1,
			slidesToScrollMob = +elementSettings.posts_to_scroll_mob || 1,
			isSingleSlide = 1 === slidesToShow,
			defaultLGDevicesSlidesCount = isSingleSlide ? 1 : 2,
			// Check for changed breakpoints config with version 3.2.0 (v.3.2.0 >=).
			elementorBreakpoints = this.versionCheck(elementorVersion, "3.1.4")
				? elementorFrontend.config.responsive.activeBreakpoints
				: elementorFrontend.config.breakpoints;

		const swiperOptions = {
			slidesPerView: slidesToShow,
			// No. of slides to scroll - don't allow more to scroll than to show.
			slidesPerGroup:
				slidesToScroll > slidesToShow ? slidesToShow : slidesToScroll,
			handleElementorBreakpoints: true,
			autoHeight: true,
			loop: "yes" === elementSettings.infinite,
			speed: elementSettings.speed,
			spaceBetween: elementSettings.space || 0,
			// ,resizeObserver: true
			// ,observer: true
			// observeParents: true
		};

		// Allow fade effect only for single slide.
		if (isSingleSlide) {
			swiperOptions.effect = elementSettings.effect;
			if ("fade" === elementSettings.effect) {
				swiperOptions.fadeEffect = { crossFade: true };
			}
		}

		// Start with breakpoints.
		swiperOptions.breakpoints = {};

		// Check for changed breakpoints config with version 3.2.0 (v.3.2.0 >=).
		if (this.versionCheck(elementorVersion, "3.1.4")) {
			// v.3.2.0 >=
			swiperOptions.breakpoints[elementorBreakpoints.mobile.value] = {
				slidesPerView: slidesToShowMob || 1,
				// No. of slides to scroll - don't allow more to scroll than to show.
				slidesPerGroup:
					slidesToScrollMob > slidesToShowMob
						? slidesToShowMob
						: slidesToScrollMob,
			};
			swiperOptions.breakpoints[elementorBreakpoints.tablet.value] = {
				slidesPerView: slidesToShowTab || defaultLGDevicesSlidesCount,
				// No. of slides to scroll - don't allow more to scroll than to show.
				slidesPerGroup:
					slidesToScrollTab > slidesToShowTab
						? slidesToShowTab
						: slidesToScrollTab,
			};
		} else {
			// v. 3.1.4 <=
			swiperOptions.breakpoints[elementorBreakpoints.md] = {
				slidesPerView: slidesToShowMob,
				slidesPerGroup: 1,
			};
			swiperOptions.breakpoints[elementorBreakpoints.lg] = {
				slidesPerView: slidesToShowTab,
				slidesPerGroup: 1,
			};
		}

		// Autoplay.
		if (!this.isEdit && "yes" === elementSettings.autoplay) {
			swiperOptions.autoplay = {
				delay: elementSettings.autoplay_speed,
				disableOnInteraction: "yes" === elementSettings.pause_on_interaction,
			};
		}

		// Navigation.
		const showArrows = "yes" === elementSettings.buttons,
			pagination = "none" !== elementSettings.pagination;

		const sliderId = this.getSliderId();

		if (showArrows) {
			swiperOptions.navigation = {
				prevEl: "#" + sliderId + " > .swiper-button-prev",
				nextEl: "#" + sliderId + ">  .swiper-button-next",
			};
		}

		if (pagination) {
			swiperOptions.pagination = {
				el: "#" + sliderId + " > .swiper-pagination",
				type: elementSettings.pagination,
				clickable: true,
			};
		}

		// Events.
		swiperOptions.on = {
			transitionStart: () => {
				if (this.sliderId === undefined) return;

				let slides = this.getDefaultElements().$swiperSlides,
					realIndex = this.sliderId.realIndex,
					sliderType = this.getMicemadeElementType();

				slides.each((item, slide) => {
					let cssSelectors = slide.classList;
					let slideIndex = parseInt(
						slide.getAttribute("data-swiper-slide-index")
					);

					if (realIndex === slideIndex) {
						// Based on slider type, choose which animation methods to run.
						if ("micemade_slider" === sliderType) {
							this.startAnimations(slide, cssSelectors);
						} else if ("micemade_slider_wc_categories" === sliderType) {
							this.wcCategories(slide, cssSelectors);
						}
					}
				});
			}, // end transitionStart
			transitionEnd: () => {
				if (this.sliderId === undefined) return;
				let slides = this.getDefaultElements().$swiperSlides,
					realIndex = this.sliderId.realIndex;

				slides.each((item, slide) => {
					// let cssSelectors = slide.classList;
					let slideIndex = parseInt(
						slide.getAttribute("data-swiper-slide-index")
					);
					// Reset all slides, except the active one.
					if (realIndex !== slideIndex) {
						this.resetSlides(slide);
					}
				});
			}, // end transitionEnd
			imagesReady: () => {}, // end imagesReady

			// Slide duplicates issue.
			// from: https://github.com/nolimits4web/swiper/issues/2629#issuecomment-477655183
			slideChangeTransitionStart: (swiper) => {
				if (this.sliderId === undefined) return;

				// if ("yes" === elementSettings.infinite) return;

				let $wrapperEl = this.sliderId.$wrapperEl;
				let params = this.sliderId.params;
				$wrapperEl
					.children("." + params.slideClass + "." + params.slideDuplicateClass)
					.each(function () {
						let idx = this.getAttribute("data-swiper-slide-index");
						this.innerHTML = $wrapperEl
							.children(
								"." +
									params.slideClass +
									'[data-swiper-slide-index="' +
									idx +
									'"]:not(.' +
									params.slideDuplicateClass +
									")"
							)
							.html();
					});
				// if (this.isEdit) {
				// 	this.addEditorListeners();
				// }
			},
			slideChangeTransitionEnd: (swiper) => {
				if (this.sliderId === undefined) return;

				// if ("yes" === elementSettings.infinite) return;

				this.sliderId.slideToLoop(this.sliderId.realIndex, 0, false);
			},
			init: () => {
				// if ( this.isEdit ) {
				// 	console.log(this.getEditorListeners());
				// 	this.addEditorListeners();
				// }
			},
		};

		return swiperOptions;
	}

	startAnimations(slide) {
		// All slide inner elements (widgets, columns, sections).
		const elemElem = slide.querySelectorAll(".elementor-element");

		for (var i = 0; i < elemElem.length; i++) {
			let settings = JSON.parse(elemElem[i].getAttribute("data-settings"));
			if (settings === null) {
				continue;
			}

			// If animations are set for each slide inner elements.
			if (
				settings.hasOwnProperty("_animation") ||
				settings.hasOwnProperty("animation")
			) {
				// Animation type for any element.
				var animType = settings._animation
					? settings._animation
					: settings.animation;

				// No animation set - abort.
				if (!animType) {
					continue;
				}

				// If animation delay is set.
				if (
					settings.hasOwnProperty("_animation_delay") ||
					settings.hasOwnProperty("animation_delay")
				) {
					let anim_delay = settings._animation_delay
							? settings._animation_delay
							: settings.animation_delay,
						delay = anim_delay / 1000;
					elemElem[i].style.animationDelay = delay.toString() + "s";

					setTimeout(
						function (e) {
							e.classList.remove("elementor-invisible");
						},
						anim_delay + 100,
						elemElem[i]
					);
				} else {
					elemElem[i].classList.remove("elementor-invisible");
				}

				// elemElem[i].style.transitionDuration = '';
				elemElem[i].style.opacity = "";
				elemElem[i].classList.add(animType);
				elemElem[i].classList.add("animated");
			}

			// If Micemade Kenburns effect is added to section background.
			if (settings.hasOwnProperty("mme_kenburns_anim_type")) {
				let mmeKenburns = settings.mme_kenburns_anim_type;
				elemElem[i].classList.add("micemade-kenburns-" + mmeKenburns);
			}
		} // end for
	}

	resetSlides(slide) {
		const elemElem = slide.querySelectorAll(".elementor-element");

		for (var i = 0; i < elemElem.length; i++) {
			let settings = JSON.parse(elemElem[i].getAttribute("data-settings"));
			// No data settings - next item.
			if (settings === null) {
				continue;
			}

			// If any element has animation set.
			if (
				settings.hasOwnProperty("_animation") ||
				settings.hasOwnProperty("animation")
			) {
				let animType = settings._animation
					? settings._animation
					: settings.animation;
				if (!animType) {
					continue;
				}

				elemElem[i].style.opacity = 0;
				elemElem[i].classList.add("elementor-invisible");
				elemElem[i].classList.remove(animType);
				elemElem[i].classList.remove("animated");
			}

			// If Micemade Kenburns effect is added to section background.
			if (settings.hasOwnProperty("mme_kenburns_anim_type")) {
				let mmeKenburns = settings.mme_kenburns_anim_type;
				elemElem[i].classList.remove("micemade-kenburns-" + mmeKenburns);
			}
		} // end for.
	}

	wcCategories(slide, cssSelectors) {
		// const settings = JSON.parse(index.dataset.settings);
		const settings = JSON.parse(slide.getAttribute("data-settings")); // supposably faster then dataset.settings.
		// No data settings - quit.
		if (settings === null) {
			return true;
		}

		// WC CATEGORIES WIDGET - If element is a category AND slide has '_animation' setting.
		// In case entering animation is set, discard animations, except for the first visible slides.
		if (
			settings.hasOwnProperty("_enter_animation") &&
			cssSelectors.contains("category")
		) {
			const animType = settings._enter_animation;
			if (!animType) {
				return true;
			}
			// Remove entering animation from slides not visible at slider init.
			if (cssSelectors.contains("mm-enter-animate")) {
				cssSelectors.remove("mm-enter-animate");
			}
		}
	}

	updateSpaceBetween() {
		this.id.params.spaceBetween = this.getElementSettings("space").size || 0;
		this.sliderId.update();
	}

	onInit(...args) {
		super.onInit(...args);

		const elementSettings = this.getElementSettings();

		if (
			!this.elements.$carousel.length ||
			2 > this.elements.$swiperSlides.length
		) {
			return;
		}

		this.initSwiper();

		// Expose the swiper instance in the frontend
		this.elements.$carousel.data("swiper", this.sliderId);

		if ("yes" === elementSettings.pause_on_hover) {
			this.elements.$carousel.on({
				mouseenter: () => {
					this.sliderId.autoplay.stop();
				},
				mouseleave: () => {
					this.sliderId.autoplay.start();
				},
			});
		}
	}

	async initSwiper() {
		const sliderId = this.getSliderId();
		// Since Elementor v.3.1.0 - optimized asset loading.
		// https://developers.elementor.com/experiment-optimized-asset-loading/
		if ("undefined" === typeof Swiper) {
			const Swiper = elementorFrontend.utils.swiper;

			// --- Use without "async" before initSwiper()
			// const asyncSwiper = elementorFrontend.utils.swiper;
			// new asyncSwiper( this.elements.$carousel, this.getSwiperSettings() ).then( ( newSwiperInstance ) => {
			// 	this.sliderId = newSwiperInstance;
			// } );

			// --- Use without "async" before initSwiper()
			const asyncSwiper = elementorFrontend.utils.swiper;
			new Swiper(
				document.getElementById(sliderId),
				this.getSwiperSettings()
			).then((newSwiperInstance) => {
				this.sliderId = newSwiperInstance;
			});

			// this.sliderId = await new Swiper( document.getElementById( sliderId ), this.getSwiperSettings() );
		} else {
			// Legacy (< v.3.1.0) Swiper instantiation.
			this.sliderId = new Swiper(
				document.getElementById(sliderId),
				this.getSwiperSettings()
			);
		}
	}

	onElementChange(propertyName) {
		if (0 === propertyName.indexOf("space")) {
			this.updateSpaceBetween();
		} else if ("arrows_position" === propertyName) {
			this.sliderId.update();
		} else if ("autoplay" === propertyName) {
			this.sliderId.update();
		}
	}

	onEditSettingsChange(propertyName) {
		if ("activeItemIndex" === propertyName) {
			this.sliderId.slideToLoop(this.getEditSettings("activeItemIndex") - 1);
		}
	}

	// From: https://gist.github.com/ZitRos/7f79837f3d46dd9cc70a9ffe68e416b8
	versionCheck(high, low) {
		let v1 = high.split(/[\-\.]/g),
			v2 = low.split(/[\-\.]/g);
		for (let i = 0; i < v1.length; i++) {
			if (isNaN(+v1[i]) || isNaN(+v2[i])) {
				if (v1[i] > v2[i]) return true;
				else if (v1[i] < v2[i]) return false;
			} else {
				if (+v1[i] > +v2[i]) return true;
				else if (+v1[i] < +v2[i]) return false;
			}
		}
		return v2.length > v1.length;
	}
}

jQuery(window).on("elementor/frontend/init", () => {
	elementorFrontend.elementsHandler.attachHandler(
		"micemade-slider",
		MicemadeSliderHandler
	);
	elementorFrontend.elementsHandler.attachHandler(
		"micemade-wc-categories",
		MicemadeSliderHandler
	);
	elementorFrontend.elementsHandler.attachHandler(
		"micemade-wc-products-slider",
		MicemadeSliderHandler
	);
	elementorFrontend.elementsHandler.attachHandler(
		"micemade-posts-slider",
		MicemadeSliderHandler
	);
});

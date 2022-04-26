// let elementChanged = false;
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
			$swiperContainer: this.$element.find(selectors.carousel),
		};

		elements.$slides = elements.$swiperContainer.find(selectors.slideContent);

		return elements;
	}

	getBreakpoints() {
		return elementorFrontend.config.responsive.activeBreakpoints;
	}

	getSwiperSettings() {
		const defaultSlidesNum =
			"micemade_slider" === this.getMicemadeElementType() ? 1 : 3;

		const elementSettings = this.getElementSettings(),
			slidesToShow = +elementSettings.slides_to_show || defaultSlidesNum,
			isSingleSlide = 1 === slidesToShow,
			elementorBreakpoints = this.getBreakpoints(),
			defaultSlidesToShowMap = {
				mobile: 1,
				tablet: isSingleSlide ? 1 : 2,
			};

		const swiperOptions = {
			autoHeight: true,
			slidesPerView: slidesToShow,
			loop: "yes" === elementSettings.infinite,
			speed: elementSettings.speed,
			spaceBetween: elementSettings.space || 0,
			handleElementorBreakpoints: true,
		};

		swiperOptions.breakpoints = {};

		// -- MME Elementor breakpoints handling (only in editor)
		// -- If options are edited (onElementChange() method).
		// if (elementChanged) {
		// 	swiperOptions.breakpoints["0"] = {
		// 		slidesPerView: slidesToShow,
		// 		slidesPerGroup: +elementSettings.slides_to_scroll || 1,
		// 	};
		// 	Object.entries(elementorBreakpoints).forEach(([key, value]) => {
		// 		let val = value.value;
		// 		swiperOptions.breakpoints[val] = {
		// 			slidesPerView: +elementSettings["slides_to_show_" + key],
		// 			slidesPerGroup: +elementSettings["slides_to_scroll_" + key],
		// 		};
		// 	});
		// 	swiperOptions.breakpoints = this.shiftByOne(swiperOptions.breakpoints);
		// } else {
		// ---- else, use "Default Elementor breakpoints handling" from bellow.
		// }

		// Default Elementor breakpoints handling.
		let lastBreakpointSlidesToShowValue = slidesToShow;
		Object.keys(elementorBreakpoints)
			.reverse()
			.forEach((breakpointName) => {
				// Tablet has a specific default `slides_to_show`.
				const defaultSlidesToShow = defaultSlidesToShowMap[breakpointName]
					? defaultSlidesToShowMap[breakpointName]
					: lastBreakpointSlidesToShowValue;

				swiperOptions.breakpoints[elementorBreakpoints[breakpointName].value] =
					{
						slidesPerView:
							+elementSettings["slides_to_show_" + breakpointName] ||
							defaultSlidesToShow,
						slidesPerGroup:
							+elementSettings["slides_to_scroll_" + breakpointName] || 1,
					};

				lastBreakpointSlidesToShowValue =
					+elementSettings["slides_to_show_" + breakpointName] ||
					defaultSlidesToShow;
			});
		// END Default Elementor breakpoints handling.

		// Autoplay.
		if (!parent.thisIsEditor && "yes" === elementSettings.autoplay) {
			swiperOptions.autoplay = {
				delay: elementSettings.autoplay_speed,
				disableOnInteraction: "yes" === elementSettings.pause_on_interaction,
			};
		}

		// Allow fade effect only for single slide.
		if (isSingleSlide) {
			swiperOptions.effect = elementSettings.effect;

			if ("fade" === elementSettings.effect) {
				swiperOptions.fadeEffect = { crossFade: true };
			}
		} else {
			swiperOptions.slidesPerGroup = +elementSettings.slides_to_scroll || 1;
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

		// Events - custom Micemade slider events.
		swiperOptions.on = this.swiperEvents();

		return swiperOptions;
	}

	async onInit(...args) {
		super.onInit(...args);

		const elementSettings = this.getElementSettings();

		if (
			!this.elements.$swiperContainer.length ||
			2 > this.elements.$slides.length
		) {
			return;
		}

		this.initSwiper();

		// Expose the swiper instance in the frontend
		this.elements.$swiperContainer.data("swiper", this.swiper);

		if ("yes" === elementSettings.pause_on_hover) {
			this.togglePauseOnHover(true);
		}

		// FIXING NUM OF ITEMS TO SCROLL, IF NOT MICEMADE SLIDER:
		if ("micemade-slider" !== this.getWidgetType() && parent.thisIsEditor) {
			// On init.
			this.fixScrollItemsOnInit();
			// On accessing settings with Hook action.
			this.fixScrollItemsAccessSettings();
		}

		// Elementor version.
		// const elementorVersion = elementorFrontend.config.version,
	}

	// Two methods for inital fixing no. of items to scroll.
	fixScrollItemsAccessSettings() {
		const parent = this;
		elementor.hooks.addAction(
			"panel/open_editor/widget/" + this.getWidgetType(),
			parent.fixScrollItemsOnInit()
		);
	}
	fixScrollItemsOnInit() {
		const breakpoints = this.getBreakpoints();
		let propertyNames = ["slides_to_show"];
		Object.keys(breakpoints).forEach((breakpointName) => {
			propertyNames.push("slides_to_show_" + breakpointName);
		});
		propertyNames.forEach((propertyName) => {
			this.fixScrollItems(propertyName);
		});
	}

	async initSwiper() {
		const sliderElemId = this.getSliderId(),
			Swiper = elementorFrontend.utils.swiper;

		this.swiper = await new Swiper(
			jQuery("#" + sliderElemId),
			this.getSwiperSettings()
		);
	}

	updateSwiperOption(propertyName) {
		const elementSettings = this.getElementSettings(),
			newSettingValue = elementSettings[propertyName],
			params = this.swiper.params;

		params.breakpoints[0] = {
			slidesPerView: params.slidesPerView,
			slidesPerGroup: params.slidesPerGroup,
		};

		switch (propertyName) {
			case "space":
				params.spaceBetween = +newSettingValue || 0;
				break;
			case "autoplay_speed":
				params.autoplay.delay = +newSettingValue;
				break;
			case "speed":
				params.speed = +newSettingValue;
				break;
			case "slides_to_show":
				params.slidesPerView = +newSettingValue;
				params.breakpoints[0].slidesPerView = +newSettingValue;
				break;
			case "slides_to_scroll":
				params.slidesPerGroup = +newSettingValue;
				params.breakpoints[0].slidesPerGroup = +newSettingValue;
				break;
		}

		const breakpoints = this.getBreakpoints();

		// Add settings for breakpoints params.
		Object.entries(breakpoints).forEach(([key, value]) => {
			let val = value.value;
			if (propertyName === "slides_to_show_" + key) {
				params.breakpoints[val].slidesPerView = +newSettingValue;
			}
			if (propertyName === "slides_to_scroll_" + key) {
				params.breakpoints[val].slidesPerGroup = +newSettingValue;
			}
		});

		params.breakpoints = this.shiftByOne(params.breakpoints);

		this.swiper.update();
	}

	// Helper method for fixing breakpoint items position.
	shiftByOne(bPoints) {
		if (typeof bPoints !== "object") {
			return;
		}
		// Shift breakpoints (rotate position by one item).
		var keys = Object.keys(bPoints),
			result = Object.assign(
				...keys.map((k, i) => ({ [k]: bPoints[keys[(i + 1) % keys.length]] }))
			);
		// Corrected breakpoints.
		return result;
	}

	getChangeableProperties() {
		return {
			pause_on_hover: "pauseOnHover",
			autoplay_speed: "delay",
			speed: "speed",
			space: "spaceBetween",
			slides_to_show: "slidesPerView",
			slides_to_show_mobile: "breakpoints",
			slides_to_show_tablet: "breakpoints",
			slides_to_scroll: "slidesPerGroup",
			slides_to_scroll_mobile: "breakpoints",
			slides_to_scroll_tablet: "breakpoints",
		};
	}

	onElementChange(propertyName) {
		const changeableProperties = this.getChangeableProperties();

		if (changeableProperties[propertyName]) {
			// 'pause_on_hover' is implemented by the handler with event listeners, not the Swiper library.
			if ("pause_on_hover" === propertyName) {
				const newSettingValue = this.getElementSettings("pause_on_hover");
				this.togglePauseOnHover("yes" === newSettingValue);
			} else {
				this.updateSwiperOption(propertyName);
			}
		}

		if ("micemade-slider" !== this.getWidgetType()) {
			this.fixScrollItems(propertyName);
		}

		// elementChanged = true;
	}

	fixScrollItems(propertyName) {
		const elementSettings = this.getElementSettings(),
			newSettingValue = elementSettings[propertyName];

		const numItems = [1, 2, 3, 4, 6];

		const toShow = "slides_to_show";
		if (propertyName.startsWith(toShow)) {
			// Str. replace for slides_to_scroll ...
			const toScroll = propertyName.replace("show", "scroll");
			// Select controls for slide_to_show/to_scroll.
			let toShowSel = parent.document.querySelector(
				"[data-setting=" + propertyName + "]"
			);
			if (!toShowSel) return;
			let toShowValue = toShowSel.value,
				toScrolSel = parent.document.querySelector(
					"[data-setting=" + toScroll + "]"
				),
				toScrollOptions = toScrolSel.querySelectorAll("option");

			// Valid number of scroll items.
			const validItemNums = numItems.filter((num) => toShowValue % num === 0),
				itemsNumDisable = numItems.filter(
					(x) => validItemNums.indexOf(x) === -1
				);

			if (
				itemsNumDisable.includes(parseInt(toScrolSel.value)) ||
				!toScrolSel.value
			) {
				// Reset scroll value if num items to scroll is in itemsNumDisable.
				toScrolSel.value = newSettingValue;
			}

			// Make non-divisible items number disabled for to scroll options.
			toScrollOptions.forEach(function (option) {
				option.removeAttribute("disabled");
				if (itemsNumDisable.includes(parseInt(option.value))) {
					option.setAttribute("disabled", true);
				}
				toScrolSel.add(option);
			});
		}
	}

	onEditSettingsChange(propertyName) {
		if ("activeItemIndex" === propertyName) {
			this.swiper.slideToLoop(this.getEditSettings("activeItemIndex") - 1);
		}
	}

	// Micemade slider template animations.
	templateAnimations(slide) {
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
				if (!animType) continue;

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

	wcCategories(slide) {
		// getAttribuite = faster than dataset.settings.
		const settings = JSON.parse(slide.getAttribute("data-settings"));
		if (settings === null) {
			return true; // quit if no data-settings.
		}

		// If entering animation is set, discard animations.
		if (settings.hasOwnProperty("_enter_animation")) {
			const animType = settings._enter_animation;
			if (!animType) {
				return true;
			}

			// Remove entering animation from slides.
			if (slide.classList.contains("mm-enter-animate")) {
				slide.classList.remove("mm-enter-animate");
				slide.classList.remove(animType);
			}
		}
	}

	// "data-mme-widget" attribute (dataset usage "mmeWidget") in .swiper-container element.
	getMicemadeElementType() {
		return this.elements.$swiperContainer[0].dataset.mmeWidget;
	}

	getSliderId() {
		let carousels = this.elements.$swiperContainer;
		if (0 !== carousels.length) {
			return carousels[0].id;
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

	// Custom Micemade slider events.
	swiperEvents() {
		return {
			update: () => {
				window.viewportAction();
			},
			transitionStart: () => {
				const sliderType = this.getMicemadeElementType(),
					thisSliderType = ["micemade_slider", "micemade_slider_wc_categories"];

				// Bail early if Swiper not started,
				// or Micemade Slider or MM Slider WC Categories.
				if (this.swiper === undefined || !thisSliderType.includes(sliderType))
					return;

				let slides = this.getDefaultElements().$slides,
					realIndex = this.swiper.realIndex;

				slides.each((item, slide) => {
					let slideIndex = parseInt(
						slide.getAttribute("data-swiper-slide-index")
					);

					if (realIndex === slideIndex) {
						// If Micemade Slider widget, handle templates used in slides.
						if ("micemade_slider" === sliderType) {
							this.templateAnimations(slide);
						}
					}
					// Handle WC categories entering animations.
					if ("micemade_slider_wc_categories" === sliderType) {
						this.wcCategories(slide);
					}
				});
			}, // end transitionStart
			transitionEnd: () => {
				if (this.swiper === undefined) return;
				let slides = this.getDefaultElements().$slides,
					realIndex = this.swiper.realIndex;

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
				if (this.swiper === undefined) return;

				// if ("yes" === elementSettings.infinite) return;

				let $wrapperEl = this.swiper.$wrapperEl;
				let params = this.swiper.params;
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
				// if (parent.thisIsEditor) {
				// 	this.addEditorListeners();
				// }
			},
			slideChangeTransitionEnd: (swiper) => {
				if (this.swiper === undefined) return;

				// if ("yes" === elementSettings.infinite) return;

				this.swiper.slideToLoop(this.swiper.realIndex, 0, false);
			},
			init: () => {
				// if ( this.parent.thisIsEditor ) {
				// 	console.log(this.getEditorListeners());
				// 	this.addEditorListeners();
				// }
			},
		};
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

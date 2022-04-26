class MicemadeWCCategories extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: ".micemade-elements_product-categories",
				items: ".category",
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings("selectors");

		const elements = {
			container: this.$element.find(selectors.container),
		};

		elements.items = elements.container.find(selectors.items);

		return elements;
	}

	itemClick(e) {
		// console.log(e.currentTarget);
	}

	// onElementChange(propertyName) {
	// 	console.log(propertyName);
	// }

	async onInit(...args) {
		super.onInit(...args);
	}

	// Bind events.
	bindEvents() {
		this.elements.items.on("click", this.itemClick.bind(this));
	}
}

jQuery(window).on("elementor/frontend/init", () => {
	elementorFrontend.elementsHandler.attachHandler(
		"micemade-wc-categories",
		MicemadeWCCategories
	);
});

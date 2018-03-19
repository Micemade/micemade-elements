# Micemade Elements

Author URI: http://micemade.com  
Plugin URI: http://micemade.com/micemade-elements  
Contributors: Micemade  
Tags: elementor, elementor addon, elementor extension, elements, widgets, posts grid, woocommerce, catalog, products, product, micemade  
Requires at least: 4.3  
Tested up to: 4.9.1  
Stable Tag: 0.5.0  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A collection of free elements for Elementor page builder WordPress plugin . "Elementor" plugin is required.

## Description

Micemade Elements features a collection of premium, easy to use yet highly functional elements that can be used with [Elementor page builder](https://wordpress.org/plugins/elementor/). This plugin adds premium features to Elementor for free, which you can also get for free.

[Elementor page builder](https://wordpress.org/plugins/elementor/) plugin must be activated to use this plugin. After you activate the required plugin, in WP admin go to Pages > Add new, press "Edit with Elementor" button, and enjoy creating pages..


**The plugin currently comes with the following elements.**


* **Micemade Posts Grid** - display posts in grid
* **Micemade WC Products**	- display WooCommerce products as in catalog / product taxonomies page - (requires WooCommerce)
* **Micemade WC Single Product** - display WooCommerce single product - (requires WooCommerce)
* **Micemade WC Products Slider** - display WooCommerce products slider ( Swiper.js ) - (requires WooCommerce)
* **Micemade WC Categories** - display WooCommerce categories - (requires WooCommerce)
* **Micemade WC Products Tabs** - display WooCommerce products in tabs - (requires WooCommerce)
* **Micemade Revolution Slider** - display single Revolution slider - (requires Revolution Slider - premium plugin)
* **Micemade Contact Form 7 forms** - display one of contact forms created with "Contact Form 7" plugin
* **Micemade MailChimp 4 WP forms** - display one of newsletter forms created with "MailChimp 4 WP forms" plugin
* **Micemade Buttons** - add buttons to group in single element, stacked horizontally or vertically
* **Micemade Instagram** - display images from Instagram using @username or #hashtag

Do you have suggestions to make or want to be notified of important updates? Reach out to us on [Twitter](http://twitter.com/themicemade)

### Installation and usage

1. Install and activate the **required plugin** [Elementor page builder](https://wordpress.org/plugins/elementor/).
2. Unzip the downloaded micemade-elements.zip file and upload to the '/wp-content/plugins/' directory. Activate the plugin through the 'Plugins' menu in WordPress
3. Create new page and start creating page with Elementor and Micemade Elements :) .


### Frequently Asked Questions

* Does it work with the theme that I am using ?

Our tests indicate that the elements work well with most themes that are well coded. You may need some minor custom CSS with themes that hijack the styling for heading tags by using !important keyword. Best use with theme's full width page templates, or use Elementor canvas page template.

* Will this plugin be available in wordpress.org ?

Time will tell. When we estimate plugin has enough added functionalities to Elementor, or some other reason, it will be available on wp.org. So far, install it from GitHub (Download zip or clone the repository) .

**Changelog**
*** 0.6.8 ***
* Improvements and fixes for WC Categories element
* 
*** 0.6.7 ***
* Added "Micemade Instagram" element
* Added responsive grid point for medium (tablet) screen sizes for multiple elements
* Improved options for elements: WC Products Slider, Posts Grid
* Improved CSS styling for multiple elements
* Included gulpfile.js and package.json

*** 0.6.6.1 ***
* Improvements in WC Products slider options.

*** 0.6.6. ***
* Controls / options changes in WC elements: products slider, single product, product categories

*** 0.6.5. ***
* New layout settings in "Micemade MailChimp 4 WP Forms"
* Updated translation strings for couple of elements

*** 0.6.4. ***
* new widget - "Micemade Instagram"
* "Micemade WC Categories" 
* * editor fixes (show animation on animation settings change) 
* * added incremental delay per category item

*** 0.6.3. ***
* Tweaks and option changes in "Micemade WC Products Slider"
* Additional options in "Micemade WC Single product" - product image as background
* Refactored "Micemade WC Categories" - added single category entering animations

*** 0.6.2. ***
* Changed theme support - usage of add_theme_support / current_theme_supports with Micemade themes

*** 0.6.1. ***
* Minor fix for registering Elementor enabled post types ( automatically enable "Mega Menu" & "Footer" CPT's - "mmmegamenu" / "mmfooter" )

*** 0.6.0. ***
* Added Footer CPT ( 'mmfooter' ) for usage with Micemade Themes - create unlimited footers
* Added custom control to Elementor section - sticky section (sticky header, menus)

*** 0.5.1. ***
* "Micemade Buttons" element tweaks - vertical button spacing is on for horizontal and vertical layout
* "Micemade Posts Grid" element
	*  tweaked hover colors - hover title, meta and excerpt colors change on items hover now
	*  Admin micemade_posts_array filter - added fix for large number of posts query items

*** 0.5.0. ***
* Added "Micemade Buttons" element

*** 0.4.2. ***
* CSS / JS tweaks for various elements
* improved "Micemade WC Products Tabs" 
	* added tab padding;
	* better responsive styles;
	* added vertical tabs
	* vertical alignment

*** 0.4.1. ***
* Translation strings fixes
* functions re-organized in appropriate files

*** 0.4.0. ***
* Added "Micemade WC Products Tabs" element - display WC catalog items in tabs ( use WC or theme "content-product.php" template)
* CSS tweaks in various elements
* Added "Ambiance" and "Ayame" to Micemade themes list for Mega Menu support
* Additional options for "Micemade Posts Grid" element
* Additional options for "Micemade WC Single Product"

*** 0.3.8. ***
* Added option for displaying product info as in catalog (simple) - in "Micemade WC Single Product"

*** 0.3.7 *** 
* Changed registering of MM Elements elements - simpler method
* Added "Micemade Contact Form 7 Forms" element for displaying contact forms (required "Contact Form 7" plugin)
* Added "Micemade MailChimp 4 WP Forms" element for displaying newsletter subscription forms (required "MailChimp 4 WP" plugin)
* Refactored "Micemade Posts Grid" - added grid spacing control, rearranged title / meta / excerpt controls
* Added "Inner spacing" control in "Micemade WC Categories"
* Added "Elements vertical spacing" for product info elements, in "Micemade WC Single Product"

*** 0.3.6 *** 
* Micemade elements grouped in separate "Micemade Elements" category, in editor sidebar
* Fixed slider navigation color changing in "Micemade WC Products Slider"
* Refactored background / overlay color (with hover) for "Micemade Posts Grid"
* Added spacing between product info elements un "Micemade WC Products Slider"
* Additional base style and hover image effect added to "Micemade WC Categories"
	
*** 0.3.5 ***
* Additional fix for Micemade WC Categories element (missing categories)

*** 0.3.4 ***
* Fix - Micemade WC Categories errors when missing category
* Fix - corrected Micemade WC products horizontal spacing 
* Tweak -  removed css transition if class "parallax" is added to section/column

*** 0.3.3=
* Fix - missing Mega menu if Micemade CHILD theme was activated

*** 0.3.2 ***
* Added "Florist" theme to supported Micemade Themes

*** 0.3.1 ***
* Fixes for WC products post_class (reset post class)

*** 0.3.0 ***
* Fixes for terms functions
* Changes in Micemade WC products (catalog view)

*** 0.2.9 ***
* Fixes for css styling of Single product element

*** 0.2.8 ***
* Repaced support for Adorn theme with support for Beautify theme

*** 0.2.7 ***
* Single product elements css and settings tweaks

*** 0.2.6 ***
* Tweaked product info styling options on products slider element

*** 0.2.5 ***
* Fixed responsive styles for Single product element
* Added alignment control in Single product element

*** 0.2.4 ***
* Replaced all the CHECKBOX controls with SWITCHER controls

*** 0.2.3 ***
* Added automatic enabling of MM Mega Menu CPT for Micemade themes.

*** 0.2.2 ***
* Fixed GitHub updater
* Added color control for Products slider navigaton buttons

*** 0.2.1 ***
* Added parallax script ( use by adding "parallax" selector in Edit Section/Column > Advanced > CSS Classes)
* Fixed left/right swiper slider position
* Added left/right toggle off/on

*** 0.2.0 ***
* Added "MM Mega menu" custom post type for usage with Micemade themes ( MegaMenu with Elementor )
* Added background, border and padding controls for Products slider and Posts grid titles and excerpt
* Added vertical alignment for styles 3 & 4 in Products slider and Posts grid
* corrected all the textdomains

*** 0.1.1 ***
* Added Color control for swiper pagination

*** 0.1.0 ***
* Added "Micemade WC Categories" element
* Added "Micemade WC Products Slider" element
* Added 4th style for posts grid and products slider elements

*** 0.0.5 ***
* Added ajax "Load more" to Micemade posts grid elements
* Added 3rd style posts grid

*** 0.0.4 ***
* Added Revolution Slider element - pick one of created Revolution sliders
* Added css class field for custom "Read more" button class

*** 0.0.3=
* Php errors fixes in multiple files

*** 0.0.2 ***
* Added responsive breakpoints
* Posts grid element - added text padding control
* Single product widget tweaks

*** 0.0.1 ***
* Initial release.

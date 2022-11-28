# Micemade Elements

Author URI: http://micemade.com  
Plugin URI: http://micemade.com/micemade-elements  
Contributors: Micemade  
Tags: elementor, elementor addon, elementor extension, elements, widgets, posts grid, woocommerce, catalog, products, product, micemade  
Requires at least: 5.5  
Tested up to: 6.0
Stable Tag: 1.0.0
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
* **Micemade Slider** - Elementor templates and custom content slider

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

*** v.1.0.0 ***
* Dynamic creation of plugin CSS to wp-content/uploads dir, upon activation or breakpoints change.
* Lot of small fixes and tweaks.
* Removed commented and reduntant code.
* Fixed issues with custom post types for mega menus and footer.

*** v.0.9.1 ***
* Replaced 'get_render_attribute_string' method with 'print_render_attribute_string' for escaping issues.
* Micemade Buttons tweaks and fixes.

*** v.0.9.0 ***
* Added - controls for number of slides to change in sliders.
* Removed - custom section control for fixed positioned (sticky) elements
* Tweak - /assets/js/custom/handlers/slider.js - number of improvements and compatiblity issues resolved.

*** v.0.8.0 ***
* Added - Posts grid element - new custom meta, ordering, cherry pick from post types

*** v.0.7.2 ***
* Tweak - completely removed old Github libraries
* Fix - Posts grid element - fixed background controls

*** v.0.7.1 ***
* Tweak - Changed library for Github plugin updates
* Fix - Swiper slider compatibility issues

*** v.0.7.0 ***
* Added - support for WPML and Polylang for custom queries (Posts Grid, WC Product elements)
* Added - Slider element with Elementor templates / custom content 
* Added - WC Categories menu element
* Added custom post type/taxonomies to Posts grid element
* Added - "Micemade Posts grid" element - added post meta ordering and toggling
* Tweak - Main plugin class moved to separate file
* Fix - Instagram widget "Unable to communicate with Instagram."
* Fix - updated package.json and package-lock.json to remove vunerable dependencies

*** v.0.6.9.2 ***
* Fixed missing Elementor Pro's Global Widget feature - editor hook priority issue
* Fixed swiper slider (WC products slider) arrows navigation

*** v.0.6.9.1 ***
* Added - single products selection in WC products slider, WC products and WC product tabs
* WP Coding Standards tweaks

*** v.0.6.9 ***
* pre 0.7.0 (Header CPT and elements)
* Fix - lots of fixes and tweaks

*** 0.6.8 ***
* Tweak - Improvements and fixes for WC Categories element

*** 0.6.7 ***
* Added - "Micemade Instagram" element
* Added - responsive grid point for medium (tablet) screen sizes for multiple elements
* Added - Included gulpfile.js and package.json
* Tweak - options for elements: WC Products Slider, Posts Grid
* Tweak - CSS styling for multiple elements

*** 0.6.6.1 ***
* Tweak - Improvements in WC Products slider options.

*** 0.6.6. ***
* Tweak - Controls / options changes in WC elements: products slider, single product, product categories

*** 0.6.5. ***
* Tweak - New layout settings in "Micemade MailChimp 4 WP Forms"
* Tweak - Updated translation strings for couple of elements

*** 0.6.4. ***
* Added - new widget - "Micemade Instagram"
* Tweak - added incremental delay per category item
* Fix - "Micemade WC Categories" editor fixes (show animation on animation settings change) 

*** 0.6.3. ***
* Added - Additional options in "Micemade WC Single product" - product image as background
* Tweak - option changes in "Micemade WC Products Slider"
* Tweak - Refactored "Micemade WC Categories" - added single category entering animations

*** 0.6.2. ***
* Tweak - Changed theme support - usage of add_theme_support / current_theme_supports with Micemade themes

*** 0.6.1. ***
* Fix - Minor fix for registering Elementor enabled post types ( automatically enable "Mega Menu" & "Footer" CPT's - "mmmegamenu" / "mmfooter" )

*** 0.6.0. ***
* Added - Footer CPT ( 'mmfooter' ) for usage with Micemade Themes - create unlimited footers
* Added - custom control to Elementor section - sticky section (sticky header, menus)

*** 0.5.1. ***
* Tweak - "Micemade Buttons" element  - vertical button spacing is on for horizontal and vertical layout
* Tweak - "Micemade Posts Grid" element - tweaked hover colors - hover title, meta and excerpt colors change on items hover now
* Fix - Admin micemade_posts_array filter - added fix for large number of posts query items

*** 0.5.0. ***
* Added - "Micemade Buttons" element

*** 0.4.2. ***
* Tweak - CSS / JS tweaks for various elements
* Tweak - improved "Micemade WC Products Tabs" - added tab padding; better responsive styles; added vertical tabs; added vertical alignment

*** 0.4.1. ***
* Tweak - functions re-organized in appropriate files
* Fix - Translation strings fixes

*** 0.4.0. ***
* Added "Micemade WC Products Tabs" element - display WC catalog items in tabs ( use WC or theme "content-product.php" template)
* Added - "Ambiance" and "Ayame" to Micemade themes list for Mega Menu support
* Added - Additional options for "Micemade Posts Grid" element
* Added - Additional options for "Micemade WC Single Product"
* Tweak - CSS tweaks in various elements

*** 0.3.8. ***
* Added - option for displaying product info as in catalog (simple) - in "Micemade WC Single Product"

*** 0.3.7 *** 
* Added "Micemade Contact Form 7 Forms" element for displaying contact forms (required "Contact Form 7" plugin)
* Added "Micemade MailChimp 4 WP Forms" element for displaying newsletter subscription forms (required "MailChimp 4 WP" plugin)
* Added "Inner spacing" control in "Micemade WC Categories"
* Added "Elements vertical spacing" for product info elements, in "Micemade WC Single Product"
* Tweak -  Changed registering of MM Elements elements - simpler method
* Tweak - Refactored "Micemade Posts Grid" - added grid spacing control, rearranged title / meta / excerpt controls

*** 0.3.6 *** 
* Added - spacing between product info elements un "Micemade WC Products Slider"
* Added - Additional base style and hover image effect added to "Micemade WC Categories"
* Tweak - Micemade elements grouped in separate "Micemade Elements" category, in editor sidebar
* Tweak - Refactored background / overlay color (with hover) for "Micemade Posts Grid"
* Fix - slider navigation color changing in "Micemade WC Products Slider"

*** 0.3.5 ***
* Fix - Additional fixes for Micemade WC Categories element (missing categories)

*** 0.3.4 ***
* Tweak -  removed css transition if class "parallax" is added to section/column
* Fix - Micemade WC Categories errors when missing category
* Fix - corrected Micemade WC products horizontal spacing 

*** 0.3.3=
* Fix - missing Mega menu if Micemade CHILD theme was activated

*** 0.3.2 ***
* Added - "Florist" theme to supported Micemade Themes

*** 0.3.1 ***
* Fix - for WC products post_class (reset post class) issues

*** 0.3.0 ***
* Tweak - Changes in Micemade WC products (catalog view)
* Fixes for terms functions

*** 0.2.9 ***
* Fix - CSS styling of Single product element

*** 0.2.8 ***
* Tweak - Replaced support for Adorn theme with support for Beautify theme

*** 0.2.7 ***
* Tweak - Single product elements css and settings 

*** 0.2.6 ***
* Tweak - product info styling options on products slider element

*** 0.2.5 ***
* Added alignment control in Single product element
* Fix - responsive styles for Single product element

*** 0.2.4 ***
* Tweak - Replaced all the CHECKBOX controls with SWITCHER controls

*** 0.2.3 ***
* Added - automatic enabling of MM Mega Menu CPT for Micemade themes.

*** 0.2.2 ***
* Added -  color control for Products slider navigaton buttons
* Fix - GitHub updater errors

*** 0.2.1 ***
* Added - parallax script ( use by adding "parallax" selector in Edit Section/Column > Advanced > CSS Classes)
* Added left/right toggle off/on
* Fix - left/right swiper slider position

*** 0.2.0 ***
* Added - "MM Mega menu" custom post type for usage with Micemade themes ( MegaMenu with Elementor )
* Added - background, border and padding controls for Products slider and Posts grid titles and excerpt
* Added - vertical alignment for styles 3 & 4 in Products slider and Posts grid
* Fix - corrected all the textdomains

*** 0.1.1 ***
* Added - Color control for swiper pagination

*** 0.1.0 ***
* Added - "Micemade WC Categories" element
* Added - "Micemade WC Products Slider" element
* Added - 4th style for posts grid and products slider elements

*** 0.0.5 ***
* Added - ajax "Load more" to Micemade posts grid elements
* Added - 3rd style posts grid

*** 0.0.4 ***
* Added - Revolution Slider element - pick one of created Revolution sliders
* Added - css class field for custom "Read more" button class

*** 0.0.3=
* FIx - Php errors fixes in multiple files

*** 0.0.2 ***
* Added - responsive breakpoints
* Added - Posts grid element - added text padding control
* Tweak - Single product widget tweaks

*** 0.0.1 ***
* Initial release.

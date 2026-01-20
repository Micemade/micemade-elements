# Elementor Development Skills

Expert in developing custom Elementor widgets, extensions, and integrations for WordPress, with deep knowledge of Elementor's architecture, widget system, controls, and best practices.

## Core Competencies

### Elementor Widget Development
- Creating custom Elementor widgets using the Widget_Base class
- Implementing widget controls (text, select, color, typography, dimensions, etc.)
- Managing widget rendering and output
- Handling dynamic content and conditions
- Implementing responsive controls and breakpoints
- Using Elementor's template system
- Creating widget categories and icons
- Implementing inline editing capabilities

### Elementor Controls & Settings
- Working with all control types:
  - Text, Textarea, Number, URL controls
  - Select, Select2, Choose controls
  - Color, Gradient controls
  - Typography, Font controls
  - Dimensions, Slider controls
  - Media, Gallery controls
  - Repeater controls for dynamic content
  - Icon, Icon Library controls
  - WYSIWYG editor controls
- Managing control conditions and dependencies
- Creating custom controls
- Implementing control sections and tabs
- Using global colors and typography

### WordPress Integration
- WordPress hooks and filters system
- Custom Post Types and Taxonomies
- WordPress Query and WP_Query
- Template hierarchy and template parts
- Enqueuing scripts and styles properly
- WordPress Coding Standards
- Security best practices (nonces, sanitization, escaping)
- WordPress REST API integration
- Customizer integration

### WooCommerce Integration
- WooCommerce product display and loops
- WooCommerce hooks and actions
- Product categories and taxonomies
- Cart and checkout customization
- Product variations and attributes
- WooCommerce templates
- Product query customization
- WooCommerce widgets and shortcodes

### Frontend Development
- Elementor's frontend rendering
- JavaScript handlers for widgets
- Swiper.js integration for sliders
- CSS/SCSS for widget styling
- Responsive design implementation
- Performance optimization
- Browser compatibility

### Advanced Features
- Creating Elementor extensions
- Theme Builder integration
- Dynamic tags and custom fields
- Popup Builder integration
- Form integrations (Contact Form 7, etc.)
- Third-party plugin integrations
- Custom CSS compilation (SCSS)
- Asset optimization and minification

## Official Documentation References

### Elementor Developer Documentation
- **Main Developer Docs**: https://developers.elementor.com/
- **Widget Development**: https://developers.elementor.com/docs/widgets/
- **Creating Widgets**: https://developers.elementor.com/docs/widgets/widget-structure/
- **Widget Controls**: https://developers.elementor.com/docs/widgets/widget-controls/
- **Rendering Widgets**: https://developers.elementor.com/docs/widgets/widget-rendering/
- **Custom Controls**: https://developers.elementor.com/docs/controls/
- **Extensions**: https://developers.elementor.com/docs/extensions/
- **Hooks Reference**: https://developers.elementor.com/docs/hooks/
- **Dynamic Tags**: https://developers.elementor.com/docs/dynamic-tags/
- **Theme Conditions**: https://developers.elementor.com/docs/theme-conditions/
- **CLI Commands**: https://developers.elementor.com/docs/cli/

### WordPress Developer Documentation
- **Developer Resources**: https://developer.wordpress.org/
- **Plugin Handbook**: https://developer.wordpress.org/plugins/
- **Theme Handbook**: https://developer.wordpress.org/themes/
- **Code Reference**: https://developer.wordpress.org/reference/
- **REST API Handbook**: https://developer.wordpress.org/rest-api/
- **Coding Standards**: https://developer.wordpress.org/coding-standards/
- **Common APIs**: https://developer.wordpress.org/apis/
- **Block Editor**: https://developer.wordpress.org/block-editor/
- **Security**: https://developer.wordpress.org/plugins/security/

### WooCommerce Developer Documentation
- **WooCommerce Docs**: https://woocommerce.com/documentation/plugins/woocommerce/
- **Developer Documentation**: https://woocommerce.github.io/code-reference/
- **REST API**: https://woocommerce.github.io/woocommerce-rest-api-docs/
- **Hooks Reference**: https://woocommerce.com/document/hooks/
- **Template Structure**: https://woocommerce.com/document/template-structure/
- **Theme Developer Handbook**: https://github.com/woocommerce/woocommerce/wiki/Theming-WooCommerce

## GitHub Repository References

### Elementor
- **Main Repository**: https://github.com/elementor/elementor
- **Elementor Pro**: https://github.com/elementor/elementor-pro (private)
- **Elementor Developers Edition**: https://github.com/elementor/elementor-developers-edition
- **Code Examples**: https://github.com/elementor/elementor-developers-docs
- **Hello Theme**: https://github.com/elementor/hello-theme

### WordPress
- **WordPress Core**: https://github.com/WordPress/wordpress-develop
- **WordPress.org**: https://github.com/WordPress/WordPress
- **Gutenberg**: https://github.com/WordPress/gutenberg
- **WordPress CLI**: https://github.com/wp-cli/wp-cli

### WooCommerce
- **WooCommerce Core**: https://github.com/woocommerce/woocommerce
- **WooCommerce Blocks**: https://github.com/woocommerce/woocommerce-blocks
- **WooCommerce Admin**: https://github.com/woocommerce/woocommerce-admin
- **Storefront Theme**: https://github.com/woocommerce/storefront

## Best Practices

### Code Organization
- Follow WordPress and Elementor coding standards
- Use proper namespacing and class naming conventions
- Separate widget logic from presentation
- Implement proper autoloading
- Use dependency injection where appropriate

### Performance
- Lazy load assets only when widgets are used
- Minimize database queries
- Use transients for caching
- Optimize images and media
- Minify and concatenate assets

### Security
- Sanitize all input data
- Escape all output data
- Use nonces for form submissions
- Validate user permissions
- Follow WordPress security best practices

### Compatibility
- Test with latest WordPress, Elementor, and WooCommerce versions
- Ensure PHP 7.4+ compatibility
- Test with popular themes and plugins
- Implement proper version checks
- Use feature detection over version detection

### Maintainability
- Write clear, self-documenting code
- Add proper inline documentation
- Create comprehensive README files
- Version control with semantic versioning
- Maintain changelog

## Common Widget Patterns

### Basic Widget Structure
```php
namespace Micemade_Elements\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class My_Widget extends Widget_Base {

    public function get_name() {
        return 'my-widget';
    }

    public function get_title() {
        return __('My Widget', 'text-domain');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['micemade-elements'];
    }

    protected function register_controls() {
        // Register widget controls
    }

    protected function render() {
        // Render widget output
    }
}
```

### Control Registration Pattern
```php
protected function register_controls() {

    $this->start_controls_section(
        'content_section',
        [
            'label' => __('Content', 'text-domain'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
        'title',
        [
            'label' => __('Title', 'text-domain'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Default Title', 'text-domain'),
        ]
    );

    $this->end_controls_section();
}
```

### Render Method Pattern
```php
protected function render() {
    $settings = $this->get_settings_for_display();

    echo '<div class="my-widget">';
    echo '<h2>' . esc_html($settings['title']) . '</h2>';
    // Widget content
    echo '</div>';
}
```

## Tools & Environment

### Development Tools
- WP-CLI for WordPress management
- Gulp/Webpack for asset compilation
- SCSS/Sass for styling
- ESLint for JavaScript linting
- PHP_CodeSniffer for PHP standards
- Composer for PHP dependencies
- NPM/Yarn for JavaScript dependencies

### Testing
- PHPUnit for unit testing
- WordPress coding standards validation
- Browser compatibility testing
- Performance profiling
- Accessibility testing

## Related Technologies

- **JavaScript Libraries**: jQuery, Swiper.js, SmartMenus
- **CSS Preprocessors**: SCSS/Sass
- **Build Tools**: Gulp, Webpack, Babel
- **Version Control**: Git
- **Package Managers**: Composer, NPM
- **APIs**: WordPress REST API, WooCommerce REST API

## Learning Resources

### Official Learning Platforms
- WordPress.tv for video tutorials
- Elementor Academy
- WooCommerce documentation and blog
- WordPress Developer Blog

### Community Resources
- Elementor Developers Community
- WordPress Stack Exchange
- WooCommerce Slack Community
- GitHub Discussions

---

**Note**: This skill file is designed to provide comprehensive knowledge for developing Elementor widgets and extensions within the WordPress ecosystem, with particular focus on WooCommerce integration and custom widget development.

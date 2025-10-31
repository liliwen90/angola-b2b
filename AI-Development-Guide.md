# AI Development Guide - Angola B2B Product Showcase
## WordPress Multi-language Product Website Development Workflow

**Document Purpose**: Step-by-step development guide for AI assistant
**Project Type**: WordPress custom theme with advanced features
**Languages**: 4 languages (zh-CN, zh-TW, pt, en) - Backend fixed zh-CN
**Target**: Complete from scratch to production deployment

---

## PHASE 0: PRE-DEVELOPMENT SETUP

### Task 0.1: Project Structure Initialization
**Priority**: CRITICAL
**Estimated Time**: 30 minutes

#### Subtasks:
1. Create root project directory structure
2. Initialize Git repository
3. Create .gitignore file
4. Create initial documentation structure

#### File Creation Checklist:
```
project-root/
├── .gitignore
├── README.md (basic project info)
├── wp-content/
│   └── themes/
│       └── angola-b2b/  (main theme folder)
└── docs/ (optional, for development notes)
```

#### .gitignore Template:
```
# WordPress core files
/wp-admin/
/wp-includes/
*.log
wp-config.php
.htaccess

# Theme development
node_modules/
*.map
.DS_Store
.vscode/
.idea/

# Uploads and cache
/wp-content/uploads/
/wp-content/cache/
/wp-content/upgrade/
```

#### Validation Checklist:
- [ ] Directory structure created
- [ ] Git initialized (`git init`)
- [ ] .gitignore in place
- [ ] Initial commit made

---

## PHASE 1: WORDPRESS CORE SETUP

### Task 1.1: WordPress Installation
**Priority**: CRITICAL
**Dependencies**: None
**Estimated Time**: 20 minutes

#### Implementation Steps:
1. Download WordPress latest version (6.4+)
2. Extract to project root or set up with Local by Flywheel
3. Configure wp-config.php:
   - Database connection
   - Security keys (use WordPress secret key generator)
   - Language: 'zh_CN'
   - Debug mode for development: `define('WP_DEBUG', true);`

#### wp-config.php Critical Settings:
```php
define('DB_NAME', 'angola_b2b');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// Security keys - generate from https://api.wordpress.org/secret-key/1.1/salt/

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);

// Force admin language to zh_CN
define('WPLANG', 'zh_CN');
```

#### Validation Checklist:
- [ ] WordPress installed successfully
- [ ] Can access /wp-admin
- [ ] Admin language is 简体中文
- [ ] Database connected
- [ ] Debug mode active

---

### Task 1.2: Base Theme Structure Creation
**Priority**: CRITICAL
**Dependencies**: Task 1.1
**Estimated Time**: 1 hour

#### Theme Directory Structure:
```
wp-content/themes/angola-b2b/
├── style.css (theme header information)
├── functions.php (main theme functions)
├── index.php (fallback template)
├── header.php (site header)
├── footer.php (site footer)
├── front-page.php (homepage template)
├── single.php (single post)
├── page.php (single page)
├── archive-product.php (product archive)
├── single-product.php (product single)
├── 404.php (404 page)
├── searchform.php (search form)
├── assets/
│   ├── css/
│   │   ├── variables.css (CSS custom properties)
│   │   ├── reset.css (CSS reset)
│   │   ├── base.css (base styles)
│   │   ├── layout.css (layout styles)
│   │   ├── components.css (component styles)
│   │   ├── animations.css (animation definitions)
│   │   ├── responsive.css (media queries)
│   │   └── main.css (imports all above)
│   ├── js/
│   │   ├── main.js (main JavaScript)
│   │   ├── animations.js (GSAP animations)
│   │   ├── product-gallery.js (product gallery functions)
│   │   ├── product-360.js (360 rotation) [已移除 - 无法获取多角度产品图]
│   │   ├── ajax-filters.js (AJAX product filters)
│   │   ├── mobile-menu.js (mobile navigation)
│   │   └── utils.js (utility functions)
│   └── images/
│       ├── logo.svg
│       └── placeholders/
├── template-parts/
│   ├── header/
│   │   ├── site-header.php
│   │   └── mobile-menu.php
│   ├── footer/
│   │   └── site-footer.php
│   ├── product/
│   │   ├── product-card.php
│   │   ├── product-gallery.php
│   │   ├── product-info.php
│   │   └── product-related.php
│   └── components/
│       ├── hero-banner.php
│       ├── advantages-section.php
│       └── cta-section.php
├── inc/
│   ├── theme-setup.php (theme setup functions)
│   ├── enqueue-scripts.php (script/style registration)
│   ├── custom-post-types.php (register CPTs)
│   ├── custom-taxonomies.php (register taxonomies)
│   ├── acf-fields.php (ACF field registration)
│   ├── admin-customization.php (admin UI customization)
│   ├── multilingual.php (language functions)
│   ├── ajax-handlers.php (AJAX handlers)
│   ├── inquiry-system.php (inquiry management)
│   └── helpers.php (helper functions)
└── languages/
    └── (translation files will be generated)
```

#### style.css Header:
```css
/*
Theme Name: Angola B2B Showcase
Theme URI: https://example.com
Author: Your Name
Author URI: https://example.com
Description: Modern B2B product showcase with 4-language support
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: angola-b2b
*/
```

#### functions.php Initial Structure:
```php
<?php
/**
 * Angola B2B Theme Functions
 * 
 * @package Angola_B2B
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('ANGOLA_B2B_VERSION', '1.0.0');
define('ANGOLA_B2B_THEME_DIR', get_template_directory());
define('ANGOLA_B2B_THEME_URI', get_template_directory_uri());

// Include required files
require_once ANGOLA_B2B_THEME_DIR . '/inc/theme-setup.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/enqueue-scripts.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/custom-post-types.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/custom-taxonomies.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/acf-fields.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/admin-customization.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/multilingual.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/ajax-handlers.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/inquiry-system.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/helpers.php';
```

#### Validation Checklist:
- [ ] All directories created
- [ ] style.css with proper header
- [ ] functions.php with includes
- [ ] All inc/ files created (empty for now)
- [ ] Theme activated in WordPress admin

---

### Task 1.3: CSS Design System Implementation
**Priority**: HIGH
**Dependencies**: Task 1.2
**Estimated Time**: 2 hours

#### assets/css/variables.css:
```css
/**
 * CSS Custom Properties (Design System)
 */

:root {
    /* Colors */
    --color-primary: #FFFFFF;
    --color-secondary: #F5F7FA;
    --color-dark: #2D3748;
    --color-accent: #FF6B35;
    --color-accent-hover: #FF8A5B;
    --color-success: #38A169;
    --color-error: #E53E3E;
    --color-text-primary: #1A202C;
    --color-text-secondary: #718096;
    --color-border: #E2E8F0;
    
    /* Typography */
    --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --font-heading: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --font-chinese: 'Noto Sans SC', 'Microsoft YaHei', sans-serif;
    
    /* Font Sizes - Desktop */
    --font-size-base: 16px;
    --font-size-sm: 14px;
    --font-size-xs: 12px;
    --font-size-h1: 48px;
    --font-size-h2: 36px;
    --font-size-h3: 28px;
    --font-size-h4: 24px;
    
    /* Font Weights */
    --font-weight-normal: 400;
    --font-weight-medium: 500;
    --font-weight-semibold: 600;
    --font-weight-bold: 700;
    
    /* Spacing (8px base unit) */
    --spacing-xs: 8px;
    --spacing-sm: 16px;
    --spacing-md: 24px;
    --spacing-lg: 32px;
    --spacing-xl: 48px;
    --spacing-2xl: 64px;
    
    /* Layout */
    --container-max-width: 1280px;
    --container-padding: 24px;
    
    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-full: 9999px;
    
    /* Shadows */
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 2px 15px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.12);
    
    /* Transitions */
    --transition-fast: 0.15s;
    --transition-base: 0.3s;
    --transition-slow: 0.5s;
    --easing-in: cubic-bezier(0.25, 0.46, 0.45, 0.94);
    --easing-out: cubic-bezier(0.55, 0.085, 0.68, 0.53);
    
    /* Z-index layers */
    --z-dropdown: 100;
    --z-sticky: 200;
    --z-fixed: 300;
    --z-modal-backdrop: 400;
    --z-modal: 500;
    --z-tooltip: 600;
    
    /* Breakpoints (for JS) */
    --breakpoint-xs: 480px;
    --breakpoint-sm: 768px;
    --breakpoint-md: 1024px;
    --breakpoint-lg: 1280px;
    --breakpoint-xl: 1920px;
}

/* Tablet adjustments */
@media (max-width: 1024px) {
    :root {
        --font-size-base: 15px;
        --font-size-h1: 36px;
        --font-size-h2: 28px;
        --font-size-h3: 24px;
        --container-padding: 20px;
    }
}

/* Mobile adjustments */
@media (max-width: 768px) {
    :root {
        --font-size-base: 14px;
        --font-size-h1: 28px;
        --font-size-h2: 24px;
        --font-size-h3: 20px;
        --font-size-h4: 18px;
        --container-padding: 16px;
        --spacing-lg: 24px;
        --spacing-xl: 32px;
    }
}
```

#### assets/css/reset.css:
```css
/**
 * Modern CSS Reset
 */

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
    scroll-behavior: smooth;
}

body {
    font-family: var(--font-primary);
    font-size: var(--font-size-base);
    line-height: 1.6;
    color: var(--color-text-primary);
    background-color: var(--color-primary);
}

/* Chinese font support */
html[lang^="zh"] body {
    font-family: var(--font-chinese);
}

img, picture, video, canvas, svg {
    display: block;
    max-width: 100%;
    height: auto;
}

button, input, textarea, select {
    font: inherit;
}

a {
    color: inherit;
    text-decoration: none;
}

ul, ol {
    list-style: none;
}

h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-heading);
    font-weight: var(--font-weight-semibold);
    line-height: 1.2;
}
```

#### Validation Checklist:
- [ ] variables.css created with all design tokens
- [ ] reset.css applied
- [ ] Responsive font sizes working
- [ ] Colors matching design system

---

### Task 1.4: Essential Plugin Installation
**Priority**: CRITICAL
**Dependencies**: Task 1.1
**Estimated Time**: 30 minutes

#### Required Plugins List:
1. **Advanced Custom Fields PRO** (ACF Pro)
   - License required
   - Install via upload or Composer

2. **WPML Multilingual CMS** (or Polylang Pro)
   - License required
   - Configure 4 languages: zh-CN (default), zh-TW, pt, en

3. **Contact Form 7**
   - Free from WordPress.org
   - For inquiry forms

4. **WP Rocket** (or W3 Total Cache)
   - For caching and performance

5. **ShortPixel Image Optimizer** (or Imagify)
   - For automatic image optimization

6. **Wordfence Security**
   - For security

7. **UpdraftPlus**
   - For backups

#### Installation Steps:
1. Navigate to Plugins > Add New
2. Upload/search each plugin
3. Activate plugins
4. Configure basic settings for each

#### WPML Configuration:
```
1. Set default language: 简体中文 (zh-CN)
2. Add languages:
   - 繁體中文 (zh-TW)
   - Português (pt)
   - English (en)
3. Language switcher: Dropdown in header
4. URL format: Language code in directory (/zh-cn/, /zh-tw/, /pt/, /en/)
5. Admin language: Force Chinese (separate plugin or custom code)
```

#### ACF Pro Configuration:
```
1. Activate license
2. Set JSON sync folder: /wp-content/themes/angola-b2b/acf-json/
3. Enable local JSON for version control
```

#### Validation Checklist:
- [ ] All required plugins installed
- [ ] All plugins activated
- [ ] WPML configured with 4 languages
- [ ] ACF Pro license activated
- [ ] Contact Form 7 ready
- [ ] Image optimizer active

---

## PHASE 2: CORE THEME DEVELOPMENT

### Task 2.1: Theme Setup Functions
**Priority**: CRITICAL
**Dependencies**: Task 1.2
**Estimated Time**: 1 hour

#### File: inc/theme-setup.php

**Implementation Checklist:**
- [ ] Register nav menus
- [ ] Add theme support for features
- [ ] Register widget areas
- [ ] Set content width
- [ ] Add thumbnail sizes

#### Code Implementation:
```php
<?php
/**
 * Theme Setup
 */

function angola_b2b_setup() {
    // Make theme available for translation
    load_theme_textdomain('angola-b2b', ANGOLA_B2B_THEME_DIR . '/languages');
    
    // Add default posts and comments RSS feed links
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    
    // Register custom image sizes
    add_image_size('product-thumbnail', 400, 400, true);
    add_image_size('product-medium', 800, 800, true);
    add_image_size('product-large', 1200, 1200, true);
    // add_image_size('product-360', 600, 600, true); // 360功能已移除
    add_image_size('hero-banner', 1920, 1080, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'angola-b2b'),
        'footer-1' => __('Footer Menu 1', 'angola-b2b'),
        'footer-2' => __('Footer Menu 2', 'angola-b2b'),
        'mobile' => __('Mobile Menu', 'angola-b2b'),
    ));
    
    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for wide alignment
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'angola_b2b_setup');

/**
 * Register widget areas
 */
function angola_b2b_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Column 1', 'angola-b2b'),
        'id'            => 'footer-1',
        'description'   => __('Footer widget area 1', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Column 2', 'angola-b2b'),
        'id'            => 'footer-2',
        'description'   => __('Footer widget area 2', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Column 3', 'angola-b2b'),
        'id'            => 'footer-3',
        'description'   => __('Footer widget area 3', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Column 4', 'angola-b2b'),
        'id'            => 'footer-4',
        'description'   => __('Footer widget area 4', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'angola_b2b_widgets_init');

/**
 * Set content width
 */
if (!isset($content_width)) {
    $content_width = 1280;
}
```

#### Validation Checklist:
- [ ] Theme features registered
- [ ] Custom image sizes added
- [ ] Navigation menus registered
- [ ] Widget areas registered
- [ ] Can see menus in Appearance > Menus
- [ ] Can see widgets in Appearance > Widgets

---

### Task 2.2: Scripts and Styles Enqueue
**Priority**: HIGH  
**Dependencies**: Task 2.1
**Estimated Time**: 1.5 hours

#### File: inc/enqueue-scripts.php

```php
<?php
/**
 * Enqueue scripts and styles
 */

function angola_b2b_enqueue_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'angola-b2b-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&family=Noto+Sans+SC:wght@400;500;600&family=Noto+Sans+TC:wght@400;500;600&display=swap',
        array(),
        null
    );
    
    // Theme styles (in order)
    wp_enqueue_style('angola-b2b-variables', ANGOLA_B2B_THEME_URI . '/assets/css/variables.css', array(), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-reset', ANGOLA_B2B_THEME_URI . '/assets/css/reset.css', array('angola-b2b-variables'), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-base', ANGOLA_B2B_THEME_URI . '/assets/css/base.css', array('angola-b2b-reset'), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-layout', ANGOLA_B2B_THEME_URI . '/assets/css/layout.css', array('angola-b2b-base'), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-components', ANGOLA_B2B_THEME_URI . '/assets/css/components.css', array('angola-b2b-layout'), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-animations', ANGOLA_B2B_THEME_URI . '/assets/css/animations.css', array('angola-b2b-components'), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-responsive', ANGOLA_B2B_THEME_URI . '/assets/css/responsive.css', array('angola-b2b-components'), ANGOLA_B2B_VERSION);
    wp_enqueue_style('angola-b2b-style', get_stylesheet_uri(), array('angola-b2b-responsive'), ANGOLA_B2B_VERSION);
    
    // GSAP
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), '3.12.2', true);
    
    // Swiper.js for carousels
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    
    // PhotoSwipe for lightbox
    wp_enqueue_style('photoswipe-css', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.7/photoswipe.min.css', array(), '5.3.7');
    wp_enqueue_script('photoswipe-js', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.7/umd/photoswipe.umd.min.js', array(), '5.3.7', true);
    wp_enqueue_script('photoswipe-lightbox', 'https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.7/umd/photoswipe-lightbox.umd.min.js', array('photoswipe-js'), '5.3.7', true);
    
    // Theme JavaScript files
    wp_enqueue_script('angola-b2b-utils', ANGOLA_B2B_THEME_URI . '/assets/js/utils.js', array(), ANGOLA_B2B_VERSION, true);
    wp_enqueue_script('angola-b2b-mobile-menu', ANGOLA_B2B_THEME_URI . '/assets/js/mobile-menu.js', array('angola-b2b-utils'), ANGOLA_B2B_VERSION, true);
    wp_enqueue_script('angola-b2b-animations', ANGOLA_B2B_THEME_URI . '/assets/js/animations.js', array('gsap', 'gsap-scrolltrigger'), ANGOLA_B2B_VERSION, true);
    
    // Product pages specific scripts
    if (is_singular('product')) {
        wp_enqueue_script('angola-b2b-product-gallery', ANGOLA_B2B_THEME_URI . '/assets/js/product-gallery.js', array('swiper-js', 'photoswipe-lightbox'), ANGOLA_B2B_VERSION, true);
        // 360度展示功能已移除 - 无法获取多角度产品图片
        // wp_enqueue_script('angola-b2b-product-360', ANGOLA_B2B_THEME_URI . '/assets/js/product-360.js', array('angola-b2b-utils'), ANGOLA_B2B_VERSION, true);
    }
    
    // Product archive specific scripts
    if (is_post_type_archive('product') || is_tax('product_category')) {
        wp_enqueue_script('angola-b2b-ajax-filters', ANGOLA_B2B_THEME_URI . '/assets/js/ajax-filters.js', array('jquery'), ANGOLA_B2B_VERSION, true);
    }
    
    // Main theme JavaScript (always last)
    wp_enqueue_script('angola-b2b-main', ANGOLA_B2B_THEME_URI . '/assets/js/main.js', array('angola-b2b-utils', 'angola-b2b-animations'), ANGOLA_B2B_VERSION, true);
    
    // Localize script with AJAX URL and nonce
    wp_localize_script('angola-b2b-main', 'angolaB2B', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('angola_b2b_nonce'),
        'homeUrl' => home_url('/'),
        'themeUrl' => ANGOLA_B2B_THEME_URI,
        'currentLang' => apply_filters('wpml_current_language', 'zh-CN'),
        'isMobile' => wp_is_mobile(),
    ));
}
add_action('wp_enqueue_scripts', 'angola_b2b_enqueue_scripts');

/**
 * Admin scripts and styles
 */
function angola_b2b_admin_enqueue_scripts($hook) {
    // Admin custom styles
    wp_enqueue_style('angola-b2b-admin', ANGOLA_B2B_THEME_URI . '/assets/css/admin.css', array(), ANGOLA_B2B_VERSION);
    
    // Admin custom JavaScript
    wp_enqueue_script('angola-b2b-admin', ANGOLA_B2B_THEME_URI . '/assets/js/admin.js', array('jquery'), ANGOLA_B2B_VERSION, true);
}
add_action('admin_enqueue_scripts', 'angola_b2b_admin_enqueue_scripts');
```

#### Validation Checklist:
- [ ] All CSS files loading in correct order
- [ ] All JavaScript files loading
- [ ] GSAP and ScrollTrigger working
- [ ] Swiper.js available
- [ ] PhotoSwipe available
- [ ] angolaB2B object available in console
- [ ] No console errors

---

### Task 2.3: Custom Post Type - Product
**Priority**: CRITICAL
**Dependencies**: Task 2.1
**Estimated Time**: 45 minutes

#### File: inc/custom-post-types.php

```php
<?php
/**
 * Register Custom Post Types
 */

function angola_b2b_register_product_cpt() {
    $labels = array(
        'name'                  => '产品',
        'singular_name'         => '产品',
        'menu_name'             => '产品管理',
        'add_new'               => '添加新产品',
        'add_new_item'          => '添加新产品',
        'edit_item'             => '编辑产品',
        'new_item'              => '新产品',
        'view_item'             => '查看产品',
        'view_items'            => '查看产品',
        'search_items'          => '搜索产品',
        'not_found'             => '未找到产品',
        'not_found_in_trash'    => '回收站中未找到产品',
        'all_items'             => '所有产品',
        'archives'              => '产品归档',
        'attributes'            => '产品属性',
        'insert_into_item'      => '插入到产品',
        'uploaded_to_this_item' => '上传到此产品',
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'product', 'with_front' => false),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-products',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'show_in_rest'        => true,
        'rest_base'           => 'products',
    );
    
    register_post_type('product', $args);
}
add_action('init', 'angola_b2b_register_product_cpt');

/**
 * Flush rewrite rules on theme activation
 */
function angola_b2b_rewrite_flush() {
    angola_b2b_register_product_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'angola_b2b_rewrite_flush');
```

#### Validation Checklist:
- [ ] "产品管理" menu appears in admin
- [ ] Can create new product
- [ ] Can edit product
- [ ] Product supports title, editor, thumbnail, excerpt
- [ ] Permalink structure works (/product/product-name/)

---

### Task 2.4: Custom Taxonomy - Product Category
**Priority**: HIGH
**Dependencies**: Task 2.3
**Estimated Time**: 30 minutes

#### File: inc/custom-taxonomies.php

```php
<?php
/**
 * Register Custom Taxonomies
 */

function angola_b2b_register_product_taxonomy() {
    // Product Category
    $cat_labels = array(
        'name'              => '产品分类',
        'singular_name'     => '产品分类',
        'search_items'      => '搜索分类',
        'all_items'         => '所有分类',
        'parent_item'       => '父级分类',
        'parent_item_colon' => '父级分类:',
        'edit_item'         => '编辑分类',
        'update_item'       => '更新分类',
        'add_new_item'      => '添加新分类',
        'new_item_name'     => '新分类名称',
        'menu_name'         => '产品分类',
    );
    
    $cat_args = array(
        'labels'            => $cat_labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'rewrite'           => array('slug' => 'product-category', 'with_front' => false),
        'show_in_rest'      => true,
    );
    
    register_taxonomy('product_category', array('product'), $cat_args);
    
    // Product Tag
    $tag_labels = array(
        'name'              => '产品标签',
        'singular_name'     => '产品标签',
        'search_items'      => '搜索标签',
        'all_items'         => '所有标签',
        'edit_item'         => '编辑标签',
        'update_item'       => '更新标签',
        'add_new_item'      => '添加新标签',
        'new_item_name'     => '新标签名称',
        'menu_name'         => '产品标签',
    );
    
    $tag_args = array(
        'labels'            => $tag_labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'rewrite'           => array('slug' => 'product-tag', 'with_front' => false),
        'show_in_rest'      => true,
    );
    
    register_taxonomy('product_tag', array('product'), $tag_args);
}
add_action('init', 'angola_b2b_register_product_taxonomy');
```

#### Validation Checklist:
- [ ] "产品分类" appears in Products menu
- [ ] "产品标签" appears in Products menu
- [ ] Can create categories
- [ ] Can create tags
- [ ] Categories are hierarchical
- [ ] Tags are flat

---

[Remaining phases continue with same detail level...]

## TASK COMPLETION CHECKLIST

### Phase 1 Complete When:
- [ ] WordPress installed and configured
- [ ] Theme structure created
- [ ] All required plugins installed
- [ ] Basic theme activated
- [ ] Can access admin in Chinese

### Phase 2 Complete When:
- [ ] Custom post type "Product" registered
- [ ] Product categories working
- [ ] All scripts and styles loading
- [ ] Design system CSS in place
- [ ] No console errors

### Phase 3 Complete When:
- [ ] All ACF fields created and working
- [ ] Product can be fully edited
- [ ] All custom fields saving data
- [ ] Field groups translated

### Phase 4 Complete When:
- [ ] All template files created
- [ ] Header and footer working
- [ ] Homepage displaying correctly
- [ ] Product archive working
- [ ] Product single page working

### Phase 5 Complete When:
- [ ] All animations implemented
- [ ] GSAP ScrollTrigger working
- [ ] ~~360° rotation working~~ (功能已移除 - 无法获取多角度产品图)
- [ ] Product gallery functional
- [ ] All micro-interactions working

### Phase 6 Complete When:
- [ ] 4 languages fully configured
- [ ] All content translated
- [ ] Language switcher working
- [ ] Admin forced to Chinese
- [ ] All URLs include language codes

### Phase 7 Complete When:
- [ ] Mobile responsive perfect
- [ ] Tablet responsive working
- [ ] Desktop layout optimal
- [ ] Touch gestures working
- [ ] All breakpoints tested

### Phase 8 Complete When:
- [ ] Lighthouse score > 85 mobile
- [ ] Lighthouse score > 90 desktop
- [ ] All images optimized
- [ ] Lazy loading working
- [ ] Cache configured

### Phase 9 Complete When:
- [ ] Tested on iPhone
- [ ] Tested on Android
- [ ] Tested on iPad
- [ ] Tested on Windows PC
- [ ] Tested on Mac
- [ ] All browsers tested

### Phase 10 Complete When:
- [ ] Live on production server
- [ ] SSL configured
- [ ] CDN configured
- [ ] Backups scheduled
- [ ] Final acceptance complete

---

## CRITICAL DEVELOPMENT NOTES

### Always Check After Each Task:
1. No PHP errors (check debug.log)
2. No JavaScript console errors
3. No CSS layout breaks
4. Responsive works on all devices
5. All languages display correctly
6. Admin stays in Chinese
7. Git commit after each working feature

### Performance Guidelines:
- Optimize images before upload (max 200KB)
- Use WebP format with fallback
- Lazy load all images below fold
- Minimize HTTP requests
- Defer non-critical JavaScript
- Inline critical CSS

### Testing Protocol:
1. Test in Chrome DevTools mobile emulator
2. Test on real iPhone device
3. Test on real Android device
4. Test on real iPad
5. Test desktop at 1920px, 1440px, 1366px
6. Test all 4 languages
7. Test all browsers (Chrome, Safari, Firefox, Edge)

### Code Quality Standards:
- Follow WordPress Coding Standards
- Comment complex functions
- Use proper escaping (esc_html, esc_attr, esc_url)
- Sanitize all inputs
- Validate all data
- Use nonces for AJAX
- Prepare SQL queries properly

### Git Commit Strategy:
- Commit after each completed task
- Use descriptive commit messages
- Format: "[Task X.X] Description of changes"
- Example: "[Task 2.3] Register Product custom post type"
- Create branches for major features
- Merge to main when tested

---

## NEXT STEPS AFTER THIS GUIDE

1. Start with PHASE 1 tasks in order
2. Complete each task's validation checklist
3. Commit to Git after each working task
4. Move to next task only when current is 100% done
5. Test continuously during development
6. Refer back to 外贸站开发规划.md for design details
7. Keep this guide updated if requirements change

**Development Start Date**: [To be filled]
**Expected Completion**: 7 weeks from start
**Current Phase**: Phase 0 (Pre-Development)
**Next Action**: Begin Task 0.1 - Project Structure Initialization


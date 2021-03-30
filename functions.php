<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */
define('AB_THEME_DIR', wp_normalize_path(get_stylesheet_directory() . '/'));
/**
 * Wp  Enqueue
 */
function ab_enqueue_styles() {
    $parent_style = 'hello-elementor'; 
    // Style
    wp_enqueue_style( 'child-style',get_stylesheet_directory_uri() . '/style.css',array( $parent_style ),wp_get_theme()->get('Version')
    );
    wp_enqueue_style( 'ab-style',get_stylesheet_directory_uri() . '/assets/css/ab-style.css',array( 'child-style' ),wp_get_theme()->get('Version')
    );
    //Script
    wp_register_script('ab-screenshot', get_stylesheet_directory_uri() . '/assets/js/html2canvas.min.js',array('ab-scripts'), false, true);
    wp_register_script('ab-mapoid', get_stylesheet_directory_uri() . '/assets/js/mapoid.min.js',array('ab-scripts'), false, true);
    wp_register_script( 'ab-sticky-kit-js', get_stylesheet_directory_uri() . '/assets/js/jquery.sticky-kit.min.js', array('ab-scripts'), false, true);
    wp_enqueue_script( 'ab-jquery-ui', get_stylesheet_directory_uri() . '/assets/js/jquery-ui-drag-datepicker.min.js', array('ab-scripts'), false, true);
    wp_enqueue_script( 'ab-slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array('ab-scripts'), false, true);
    wp_register_script( 'ab-compare', get_stylesheet_directory_uri() . '/assets/js/products-compare.js', array('ab-scripts'), false, true);
    wp_register_script( 'ab-wishlist', get_stylesheet_directory_uri() . '/assets/js/wishlist.js', array('ab-scripts'), false, true);
    wp_enqueue_script('ab-scripts', get_stylesheet_directory_uri() . '/assets/js/ab-scripts.js',array('jquery-core'), false, true);
}
add_action( 'wp_enqueue_scripts', 'ab_enqueue_styles' );
/**
 * Ajax Url
 */
add_action('wp_enqueue_scripts', 'ab_ajax_url_render', 1000);
function ab_ajax_url_render(){
    wp_add_inline_script('ab-scripts', ab_ajax_url());
}
function ab_ajax_url(){
    $ajaxurl = 'var ajaxurl = "' . esc_url(admin_url('admin-ajax.php')) . '";';
    return $ajaxurl;
}
/**
 * Add a sidebar.
 */
function ab_widgets_init() {
	register_sidebar( array(
        'name'          => __( 'Blog Sidebar', 'hello-elementor' ),
        'id'            => 'blog-sidebar',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'hello-elementor' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'hello-elementor' ),
        'id'            => 'shop-sidebar',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'hello-elementor' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'ab_widgets_init' );

/**
 * Require 
 */
include_once( AB_THEME_DIR . 'includes/kirki/kirki.php' );

function ab_kirki_configuration() {
    return array( 'url_path'     => get_stylesheet_directory_uri() . '/includes/kirki/' );
}
add_filter( 'kirki/config', 'ab_kirki_configuration' );

/**
 * Customizer 
 */
include_once( AB_THEME_DIR . 'customize/customize.php');
/*
* Theme
*/
include_once( AB_THEME_DIR . 'functions/theme/features.php');
include_once( AB_THEME_DIR . 'functions/theme/hooks.php');
include_once( AB_THEME_DIR . 'functions/theme/shortcode.php');
/*
* WooCommerce
*/
if (class_exists('WooCommerce', false)) {
    include_once( AB_THEME_DIR . 'functions/woocommerce/features.php');
    include_once( AB_THEME_DIR . 'functions/woocommerce/hooks.php');
    include_once( AB_THEME_DIR . 'functions/woocommerce/heplper.php');
    include_once( AB_THEME_DIR . 'functions/woocommerce/shortcode.php');
}

/* Chèn code google trên header vào đây */
function ab_add_custom_code_head() { ?>
<!--CHÈN CODE VÀO GIỮA KHU VỰC NÀY-->

<!--KẾT THÚC CHÈN CODE-->

<?php
}

add_action( 'wp_head', 'ab_add_custom_code_head' );

/* Chèn code trong body vào đây */
function ab_add_custom_code_body() { ?>
<!--CHÈN CODE VÀO GIỮA KHU VỰC NÀY-->

<!--KẾT THÚC CHÈN CODE-->

<?php
}
add_action( 'wp_body_open', 'ab_add_custom_code_body' );

/* Chèn code css cho admin vào đây */
add_action('admin_enqueue_scripts', function()
{
    wp_add_inline_style('dashicons', '');
    
});

/* Chèn code css cho fontend header vào đây */
add_action('wp_enqueue_scripts', function()
{
    wp_add_inline_style('ab-style', '');
    
});
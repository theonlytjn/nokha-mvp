<?php

// Adding WP Functions & Theme Support
function joints_theme_support() {

    // Add WP Thumbnail Support
    add_theme_support( 'post-thumbnails' );

    // Default thumbnail size
    set_post_thumbnail_size(960, 540, true);

    /*********************
    THUMNAIL SIZE OPTIONS
    *********************/

    // Thumbnail sizes
    add_image_size( 'post-thumb', 960, 540, true );
    add_image_size( 'article-thumb', 768, 768, true );
    add_image_size( 'article-landscape', 1920, 1080, true );
    add_image_size( 'article-portrait', 1080, 1920, true );
    /*
    to add more sizes, simply copy a line from above
    and change the dimensions & name. As long as you
    upload a "featured image" as large as the biggest
    set width or height, all the other sizes will be
    auto-cropped.

    You can change the names and dimensions to whatever
    you like.
    */

    // Add RSS Support
    add_theme_support( 'automatic-feed-links' );

    // Add Support for WP Controlled Title Tag
    add_theme_support( 'title-tag' );

    // Add HTML5 Support
    add_theme_support( 'html5',
             array(
                 'comment-list',
                 'comment-form',
                 'search-form',
             )
    );

    // Adding post format support
     add_theme_support( 'post-formats',
        array(
            'aside',             // title less blurb
            'gallery',           // gallery of images
            'link',              // quick link to other site
            'image',             // an image
            'quote',             // a quick quote
            'status',            // a Facebook like status update
            'video',             // video
            'audio',             // audio
            'chat'               // chat transcript
        )
    );

    // Set the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
    $GLOBALS['content_width'] = apply_filters( 'joints_theme_support', 1200 );

} /* end theme support */

add_action( 'after_setup_theme', 'joints_theme_support' );

// Adding WWooCommerce Functions & Theme Support

add_action( 'after_setup_theme', 'joints_theme_support' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<div id="main-content" class="clearfix">';
}

function my_theme_wrapper_end() {
  echo '</div>';
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}


/**
 * Remove product data tabs
 */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );          // Remove the description tab
    unset( $tabs['reviews'] );             // Remove the reviews tab
    unset( $tabs['additional_information'] );      // Remove the additional information tab

    return $tabs;
}

/**
 * Change Read More for Out Of Stock Text
 */
add_filter( 'woocommerce_product_add_to_cart_text', function( $text ) {
    if ( 'Read more' == $text ) {
        $text = __( 'Out of Stock', 'woocommerce' );
    }

    return $text;
} );

/**
 * Remove related products output
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
 */

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'     => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'        => false
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'    => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'    => 'theme-general-settings',
    ));

}

function disable_wp_responsive_images() {
    return 1;
}

// Disable responsive images
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');

// Remove Breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

// Add custom logo

add_theme_support( 'custom-logo' );

function themename_custom_logo_setup() {
    $defaults = array(
        'height'               => 100,
        'width'                => 400,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array( 'site-title', 'site-description' ),
        'unlink-homepage-logo' => true,
    );

    add_theme_support( 'custom-logo', $defaults );
}

add_action( 'after_setup_theme', 'themename_custom_logo_setup' );

add_filter( 'auto_plugin_update_send_email', '__return_false' );
add_filter( 'auto_theme_update_send_email', '__return_false' );

// ACF Front-end Styling

add_action('admin_head', 'acf_sections');

function acf_sections() {
  echo '<style>

.half-repeater .acf-repeater tr.acf-row:not(.acf-clone){
    display: inline-block !important;
    width: 50% !important;
}

.half-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields{
    width: 100%;
}

.third-repeater .acf-repeater tr.acf-row:not(.acf-clone){
    display: inline-block !important;
    width: 33.33% !important;
}

.third-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields{
    width: 100%;
}

.fourth-repeater .acf-repeater tr.acf-row:not(.acf-clone){
    display: inline-block !important;
    width: 25% !important;
}

.fourth-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields{
    width: 100%;
}

.fifth-repeater .acf-repeater tr.acf-row:not(.acf-clone){
    display: inline-block !important;
    width: 20% !important;
}

.fifth-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields{
    width: 100%;
}

.sixth-repeater .acf-repeater tr.acf-row:not(.acf-clone){
    display: inline-block !important;
    width: 16.6666666667% !important;
}

.sixth-repeater .acf-repeater tr.acf-row:not(.acf-clone) td.acf-fields{
    width: 100%;
}

td.acf-fields{
    width: 100% !important;
}

  </style>';
}

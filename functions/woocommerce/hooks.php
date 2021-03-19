<?php
/**
 * Hook theme Features
 *
 * @package     AB
 * @version     1.0
 * @author      AB
 * @link        http://wp-webdesign.site/
 * @copyright   Copyright (c) 2020 AB
 */
/*===Wishlist + Compare===*/
add_action('wp_enqueue_scripts', function () {   
	if(get_theme_mod('ab_wishlist','1')){
        wp_enqueue_script('ab-wishlist');
        $add_to_wishlist_icon = '<i class="ab-font ab-icon-heart-3"></i> ';
        $browse_to_wishlist_icon = '<i class="ab-font ab-icon-heart-5"></i> ';
        wp_localize_script('ab-wishlist', 'zooWishlistCDATA', [
            'addToWishlist' => get_theme_mod('zoo_text_add_to_wishlist', esc_html__('Thêm yêu thích', 'hello-elementor')),
            'addToWishlistIcon' => $add_to_wishlist_icon,
            'browseWishlist' => get_theme_mod('zoo_text_browse_to_wishlist', esc_html__('Xem xem thích', 'hello-elementor')),
            'browseWishlistIcon' => $browse_to_wishlist_icon,
            'addToWishlistErr' => esc_html__('Không thêm được vào danh sách yêu thích.', 'hello-elementor'),
            'wishlistIsEmpty' => esc_html__('Mục yêu thích trống.', 'hello-elementor')
        ]);
    }
    if(get_theme_mod('ab_compare','1')) {
        wp_enqueue_script('ab-compare');
        $add_to_compare_icon = '<i class="ab-font ab-icon-law"></i>';
        $browse_to_compare_icon = '<i class="ab-font ab-icon-law-1"></i>';
        wp_localize_script( 'ab-compare', 'zooProductsCompareCDATA', [
            'addToCompare'      => get_theme_mod( 'zoo_text_add_to_compare', esc_html__( 'Thêm so sánh', 'hello-elementor' ) ),
            'addToCompareIcon'  => $add_to_compare_icon,
            'browseCompare'     => get_theme_mod( 'zoo_text_browse_to_compare', esc_html__( 'Xem so sánh', 'hello-elementor' ) ),
            'browseCompareIcon' => $browse_to_compare_icon,
            'addToCompareErr'   => esc_html__( 'Lỗi, xin bạn vui lòng thử lại.', 'hello-elementor' ),
            'compareIsEmpty'    => esc_html__( 'Không có sản phẩm trong bảng so sánh.', 'hello-elementor' )
        ] );
    }
}, 10, 0);

/* Compare button */
if(get_theme_mod('ab_compare','1')){
	function ab_add_button_compare(){ ?>
		<span class="zoo-compare-button add-to-products-compare" rel="nofollow" data-id="<?php echo get_the_ID() ?>" title="So sánh"  data-label-added="So sánh"><i class="ab-font ab-icon-law"></i></span>
	<?php
	}
	add_action('group_feature','ab_add_button_compare', 20);
    add_action('group_cp_wl', 'ab_add_button_compare', 20, 0);
}
if(get_theme_mod('ab_wishlist','1')){
	function ab_add_button_wishlist(){ ?>
		<span class="zoo-wishlist-button add-to-wishlist" rel="nofollow" data-id="<?php echo get_the_ID() ?>" title="Yêu thích"  data-label-added="Yêu thích"><i class="ab-font ab-icon-heart-3"></i></span>
	<?php
	}
	add_action('group_feature','ab_add_button_wishlist', 30);
    add_action('group_cp_wl', 'ab_add_button_wishlist', 10, 0);
}
if(get_theme_mod('ab_compare','1') || get_theme_mod('ab_wishlist','1') ){
    function ab_group_cp_wl(){ ?>
        <div class="group-cp-wl">
            <?php do_action('group_cp_wl'); ?>
        </div>
    <?php
    }
    add_action('woocommerce_after_add_to_cart_form','ab_group_cp_wl', 5);
}
/* Quick view button */
if(get_theme_mod('ab_quickview','1')){
    function ab_add_button_quickview(){ ?>
        <span class="btn-quick-view" title="Xem nhanh" data-productID="<?php echo get_the_ID(); ?>"><i class="ab-font ab-icon-eye-5"></i></span>
    <?php
    }
    add_action('group_feature','ab_add_button_quickview', 10);
}
/* Add to cart button */
if(get_theme_mod('ab_add_to_cart_button','1')){
	/* Change position cart button */
	remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 10);
	add_action( 'group_feature', 'woocommerce_template_loop_add_to_cart', 40 );
}
else{
	remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 10);
}

if(get_theme_mod('ab_compare','1') || get_theme_mod('ab_quickview','1') || get_theme_mod('ab_wishlist','1') || get_theme_mod('ab_add_to_cart_button','1')){
    function ab_group_feature(){ ?>
        <div class="group-feature">
            <?php do_action('group_feature'); ?>
        </div>
    <?php
    }
}
//Style otions
if( get_theme_mod('style','product-default') == 'product-default'){
    add_action('woocommerce_before_shop_loop_item','ab_group_feature', 5);
}
if( get_theme_mod('style','product-default') == 'product-style-01'){
    add_action('woocommerce_after_shop_loop_item_title','ab_group_feature', 50);
}
if( get_theme_mod('style','product-default') != 'none'){
    if ( ! function_exists( 'ab_products_loop_start' ) ) {
        function ab_body_class($classes) {
            $classes[] = ' '.get_theme_mod('style','product-default');
            return $classes;
        }
    }
    add_filter('body_class', 'ab_body_class');
}

/* Tính năng hover image */
if(get_theme_mod('ab_hover_image','1')){
    function woocommerce_get_product_thumbnail( $size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0 ) {
        global $product;

        $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

        return $product ? $product->get_image( $image_size ).do_action('after_archive_thumbnail') : '';
        

    }
    function add_image_hover(){
        $gallery = get_post_meta(get_the_ID(), '_product_image_gallery', true);
        if (!empty($gallery)) {
            $gallery = explode(',', $gallery);
            $first_image_id = $gallery[0];
            echo wp_get_attachment_image($first_image_id, 'woocommerce_thumbnail', '', array('class' => 'hover-image'));
        } else {
            return false;
        }
    }
    add_action('after_archive_thumbnail','add_image_hover');
}
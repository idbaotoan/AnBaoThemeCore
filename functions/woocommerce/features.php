<?php
/**
 * Register theme Features
 *
 * @package     AB
 * @version     1.0
 * @author      AB
 * @link        http://wp-webdesign.site/
 * @copyright   Copyright (c) 2020 AB
 */
// Điều chỉnh size ảnh thumbnail
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width' => 200,
        'height' => 200,
        'crop' => 1,
    );
} );
//Hiển thị text liên hệ khi giá rỗng
add_filter( 'woocommerce_get_price_html', 'ab_price_free_zero_empty', 9999, 2 );
   
function ab_price_free_zero_empty( $price, $product ){
    if ( '' === $product->get_price() || 0 == $product->get_price() ) {
        $price = '<span class="woocommerce-Price-amount amount">Giá : liên hệ</span>';
    }  
    return $price;
}
// Thêm vào giở hàng
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
        
function woocommerce_ajax_add_to_cart() {
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $variations = $_POST['variations'];
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations) && 'publish' === $product_status) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        WC_AJAX :: get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));
        echo wp_send_json($data);
    }
    wp_die();
}

//Địa chỉ
add_action('wp_ajax_ab_ajax_address', 'ab_ajax_address_action');
add_action('wp_ajax_nopriv_ab_ajax_address', 'ab_ajax_address_action');

if (!function_exists('ab_ajax_address_action')) {
    function ab_ajax_address_action(){
        $value = $field = null;
        
        if(isset($_POST['field'])){
            $field = $_POST['field'];
        }
        if(isset($_POST['value'])){
            $value = $_POST['value'];
        }
        if(isset($_POST['filter'])){
            $filter = $_POST['filter'];
        }
        
        if ( is_front_page() ) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;   
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        }
        
        $args = array(
            'post_type'             => 'store',
            'post_status'           => array('publish'),
            'posts_per_page'        => -1,
            'stores' => strtolower(get_bloginfo('language')),
            'paged' => $paged,
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => $field,
                    'value'     => $value,
                    'compare'   => '='
                ),
            ),
        );
        $the_query = new WP_Query($args);
        $val_arr = array();
        if ($the_query->have_posts()):
            ob_start();
            while ($the_query->have_posts()):
                $the_query->the_post(); 
                $val_arr[] = get_field($filter, get_the_ID()); 
            endwhile;
            $val = array_unique($val_arr);
            ?>
            <?php foreach ($val as $val_value) {
                echo '<option value="'.$val_value.'">'.$val_value.'</option>';
            } ?>
            <?php
            $output = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            echo ent2ncr($output);
        endif;
        wp_die();
        
    }
}
//Show địa chỉ
add_action('wp_ajax_ab_ajax_address_show', 'ab_ajax_address_show_action');
add_action('wp_ajax_nopriv_ab_ajax_address_show', 'ab_ajax_address_show_action');

if (!function_exists('ab_ajax_address_show_action')) {
    function ab_ajax_address_show_action(){
        $value = $field = null;
        
        if(isset($_POST['value_city'])){
            $value_city = $_POST['value_city'];
        }
        if(isset($_POST['value_distric'])){
            $value_distric = $_POST['value_distric'];
        }
        if(isset($_POST['value_sub_distric'])){
            $value_sub_distric = $_POST['value_sub_distric'];
        }
        
        if ( is_front_page() ) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;   
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        }
        
        $args = array(
            'post_type'             => 'store',
            'post_status'           => array('publish'),
            'posts_per_page'        => -1,
            'stores' => strtolower(get_bloginfo('language')),
            'paged' => $paged,
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => 'city',
                    'value'     => $value_city,
                    'compare'   => '='
                ),
                array(
                    'key'       => 'distric',
                    'value'     => $value_distric,
                    'compare'   => '='
                ),
                array(
                    'key'       => 'sub_distric',
                    'value'     => $value_sub_distric,
                    'compare'   => '='
                ),
            ),
        );
        $the_query = new WP_Query($args);
        $val_arr = array();
        if ($the_query->have_posts()):
            ob_start();
            while ($the_query->have_posts()):
                $the_query->the_post(); ?>
                <article class="store-item">
                    <div class="store-item-inner">
                        <img src="<?php echo get_field('map_image', get_the_ID()) ?>">
                        <div class="summary">
                            <h2><span><?php echo __('Tên cửa hàng:','hello-elementor'); ?> </span> <?php echo the_title() ?></h2>
                            <div class="full-address">
                                <span><?php echo __('Địa chỉ:','hello-elementor'); ?> </span> 
                                <label class="store-address"><?php echo get_field('full_address', get_the_ID()) ?></label>
                            </div>
                            <div class="code">
                                <span><?php echo __('Mã:','hello-elementor'); ?> </span> 
                                <label class="store-code"><?php echo get_field('code', get_the_ID()) ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="map">
                        <?php echo get_field('map_iframe', get_the_ID()) ?>
                    </div>
                </article>
            <?php
            endwhile;
            
            $output = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            echo ent2ncr($output);
        endif;
        wp_die();
        
    }
}
// Tìm tên cửa hàng
add_action('wp_ajax_ab_find_store_name', 'ab_find_store_name_action');
add_action('wp_ajax_nopriv_ab_find_store_name', 'ab_find_store_name_action');

if (!function_exists('ab_find_store_name_action')) {
    function ab_find_store_name_action(){
        if(isset($_POST['name'])){
            $name = $_POST['name'];
        }
        $args = array(
            'post_type' => 'store',
            's' => $name,
            'stores' => strtolower(get_bloginfo('language')),
            'posts_per_page' => 1,
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()){
            ob_start();
            while ($the_query->have_posts()):
                $the_query->the_post(); ?>
                <article class="store-item">
                    <div class="store-item-inner">
                        <img src="<?php echo get_field('map_image', get_the_ID()) ?>">
                        <div class="summary">
                            <h2><span><?php echo __('Tên cửa hàng:','hello-elementor'); ?> </span> <?php echo the_title() ?></h2>
                            <div class="full-address">
                                <span><?php echo __('Địa chỉ:','hello-elementor'); ?> </span> 
                                <label class="store-address"><?php echo get_field('full_address', get_the_ID()) ?></label>
                            </div>
                            <div class="code">
                                <span><?php echo __('Mã:','hello-elementor'); ?> </span> 
                                <label class="store-code"><?php echo get_field('code', get_the_ID()) ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="map">
                        <?php echo get_field('map_iframe', get_the_ID()) ?>
                    </div>
                </article>
            <?php
            endwhile;
            
            $output = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            echo ent2ncr($output);
        }
        else{
                echo '<p style="color: red;">Tên cửa hàng không đúng xin vui lòng nhập tên khác.</p>';
            $output = ob_get_contents();
            ob_end_clean();
            echo ent2ncr($output);
        }
        wp_die();
        
    }
}
// Tìm mã cửa hàng
add_action('wp_ajax_ab_find_store_code', 'ab_find_store_code_action');
add_action('wp_ajax_nopriv_ab_find_store_code', 'ab_find_store_code_action');

if (!function_exists('ab_find_store_code_action')) {
    function ab_find_store_code_action(){
        if(isset($_POST['code'])){
            $code = $_POST['code'];
        }
        $args = array(
            'post_type'             => 'store',
            'post_status'           => array('publish'),
            'posts_per_page'        => -1,
            'stores' => strtolower(get_bloginfo('language')),
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => 'code',
                    'value'     => $code,
                    'compare'   => '='
                ),
            ),
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()){
            ob_start();
            while ($the_query->have_posts()):
                $the_query->the_post(); ?>
                <article class="store-item">
                    <div class="store-item-inner">
                        <img src="<?php echo get_field('map_image', get_the_ID()) ?>">
                        <div class="summary">
                            <h2><span><?php echo __('Tên cửa hàng:','hello-elementor'); ?> </span> <?php echo the_title() ?></h2>
                            <div class="full-address">
                                <span><?php echo __('Địa chỉ:','hello-elementor'); ?> </span> 
                                <label class="store-address"><?php echo get_field('full_address', get_the_ID()) ?></label>
                            </div>
                            <div class="code">
                                <span><?php echo __('Mã:','hello-elementor'); ?> </span> 
                                <label class="store-code"><?php echo get_field('code', get_the_ID()) ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="map">
                        <?php echo get_field('map_iframe', get_the_ID()) ?>
                    </div>
                </article>
            <?php
            endwhile;
            
            $output = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            echo ent2ncr($output);
        }
        else{
                echo '<p style="color: red;">Mã cửa hàng không đúng xin vui lòng nhập tên khác.</p>';
            $output = ob_get_contents();
            ob_end_clean();
            echo ent2ncr($output);
        }
        wp_die();
        
    }
}
// Bảo hành
add_action('wp_ajax_ab_bao_hanh', 'ab_bao_hanh_action');
add_action('wp_ajax_nopriv_ab_bao_hanh', 'ab_bao_hanh_action');

if (!function_exists('ab_bao_hanh_action')) {
    function ab_bao_hanh_action(){
        
        if(isset($_POST['code'])){
            $code = $_POST['code'];
        }
        
        $args = array(
            'post_type' => 'bao_hanh',
            'name' => $code,
            'posts_per_page' => 1,
        );
        $bao_hanh = new WP_Query($args);
        if ($bao_hanh->have_posts()){
            ob_start();
            echo '<ul class="bao-hanh-content">';
            while ($bao_hanh->have_posts()) {
                $bao_hanh->the_post(); 
                ?>
                <li class="code">
                    <label>Thông tin chi tiết mã bảo hành:&nbsp;</label><?php echo $code; ?>
                </li>
                <li class="name">
                    <label>Họ và tên:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_name',true); ?>
                </li>
                <li class="tel">
                    <label>Số điện thoại:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_tel',true); ?>
                </li>
                <li class="email">
                    <label>Email:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_email',true); ?>
                </li>
                <li class="address">
                    <label>Địa chỉ:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_address',true); ?>
                </li>
                <li class="product">
                    <label>Sản phẩm bảo hành:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_product',true); ?>
                </li>
                <li class="note_product">
                    <label>Ghi chú sản phẩm:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_note_product',true); ?>
                </li>
                <li class="start_date">
                    <label>Ngày mua:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_start_date',true); ?>
                </li>
                <li class="warranty_period">
                    <label>Hạn bảo hành:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_warranty_period',true); ?>
                </li>
                <li class="level">
                    <label>Cấp độ:&nbsp;</label><?php echo get_post_meta(get_the_ID(),'ab_level',true); ?>
                </li>
            <?php 
            }
            echo '</ul>';

            $output = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            echo ent2ncr($output);
        }
        else{
            ob_start();
                echo '<ul class="bao-hanh-content">';
                echo '<li><label style="color: red;">Mã bảo hành không chính xác, xin vui lòng nhập lại hoặc liên hệ với trung tâm hỗ trợ HNMAC để được tư vấn.</label></li>';
                echo '</ul>';
            $output = ob_get_contents();
            ob_end_clean();
            echo ent2ncr($output);
        }
        wp_die();
        
    }
}

//Quick View
if (!function_exists('ab_quick_view')) {
    function ab_quick_view()
    {
        global $post, $product;
        $product_id = $_POST['product_id'];
        $product = wc_get_product($product_id);
        $post = $product->post;
        setup_postdata($post);
        ob_start();?>
        <div id="ab-quickview-lb" class="ab-product-quick-view woocommerce">
            <div class="mash-color"></div>
            <a href="#" class="close-btn close-quickview" title="<?php esc_attr__('Close', 'ab') ?>"><i
                    class="cs-font clever-icon-close"></i> </a>
            <?php 
            if ( post_password_required() ) {
                echo get_the_password_form(); // WPCS: XSS ok.
                return;
            }
            
            wp_enqueue_script('photoswipe');
            ?>
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

                <?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>

                <div class="summary entry-summary">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     * @hooked WC_Structured_Data::generate_product_data() - 60
                     */
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                </div>
            </div>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        wp_reset_postdata();
        echo ent2ncr($output);
        exit;
    }
}
add_action('wp_ajax_ab_quick_view', 'ab_quick_view');
add_action('wp_ajax_nopriv_ab_quick_view', 'ab_quick_view');

/**
 * AJAX get compare products
 */
if (!function_exists('zoo_ajax_get_compare_items')) {
    function zoo_ajax_get_compare_items()
    {
        if (empty($_POST['compareItems'])) {
            wp_send_json(['html' => '<p>' . esc_html_e('No products to compare.', 'hello-elementor') . '</p>']);
        }

        global $post;

        $items = json_decode($_POST['compareItems']);

        $args = [
            'post_type' => ['product', 'product_variation'],
            'suppress_filters' => true,
            'no_found_rows' => true,
            'posts_per_page' => -1,
            'ignore_sticky_posts' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ];

        foreach ($items as $item_id) {
            $args['post__in'][] = absint($item_id);
        }

        $query = new WP_Query(apply_filters('woocommerce_products_compare_query', $args));

        ob_end_clean();

        ob_start();

        if ($query->have_posts()) : ?>
            <h2 class="products-compare-panel-title zoo-popup-panel-title">
                <?php echo esc_html__('BẢNG SO SÁNH', 'hello-elementor') ?>
            </h2>
            <div class="zoo-wrap-popup-content">
                <table class="products-compare-table">
                    <tbody>
                    <tr>
                        <th></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-remove products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <a href="#" class="remove-from-products-compare"
                                   title="<?php echo esc_attr__('Xóa', 'hello-elementor'); ?>"
                                   data-id="<?php echo esc_attr($post->ID); ?>"><i class="fas fa-times"></i></a>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-title products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-thumbnail products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Mã SP', 'hello-elementor') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-meta products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php
                                $item = wc_get_product($post->ID);
                                echo esc_html($item->get_sku());
                                unset($item);
                                ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Giá', 'hello-elementor') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="product-price products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php woocommerce_template_loop_price(); ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>     
                    <tr>
                        <th><?php esc_html_e('Mô tả', 'hello-elementor') ?></th>
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <td class="description products-compare-row-<?php echo esc_attr($post->ID); ?>">
                                <?php echo apply_filters('woocommerce_short_description', $post->post_excerpt) ?>
                            </td>
                        <?php endwhile; ?>
                    </tr>
                    <tr class="attribute-head-group-row">
                        <?php
                        $available_atts = [];
                        foreach ($query->posts as $post_object) {
                            $woo_product = wc_get_product($post_object);
                            $product_atts = $woo_product->get_attributes();
                            foreach ($product_atts as $product_att) {
                                $tmp_att_name = $product_att->get_name();
                                if (in_array($tmp_att_name, $available_atts))
                                    continue;
                                $available_atts[] = $tmp_att_name;
                            }
                        }
                        ?>
                        <th><?php esc_html_e('Đặc điểm', 'hello-elementor') ?></th>
                        <td colspan="<?php echo esc_attr(count($query->posts)) ?>">
                            <?php echo implode(', ', array_map('wc_attribute_label', $available_atts)); ?>
                        </td>
                    </tr>
                    <?php
                    foreach ($available_atts as $att_name) {
                        echo '<tr class="attribute-group-row">';
                        echo '<th>' . wc_attribute_label($att_name) . '</th>';
                        while ($query->have_posts()) : $query->the_post();
                            $product_has_att = false;
                            $product_atts_with_key = [];
                            $woo_product = wc_get_product($post);
                            $product_atts = $woo_product->get_attributes();
                            foreach ($product_atts as $product_att) {
                                $product_att_name = $product_att->get_name();
                                $product_atts_with_key[$product_att_name] = $product_att;
                                if ($product_att_name === $att_name)
                                    $product_has_att = true;
                            }
                            if ($product_has_att) {
                                $product_att = $product_atts_with_key[$att_name];
                                echo '<td class="products-compare-row-' . esc_attr($post->ID) . '">';
                                $values = [];
                                if ($product_att->is_taxonomy()) {
                                    $attribute_taxonomy = $product_att->get_taxonomy_object();
                                    $attribute_values = wc_get_product_terms($woo_product->get_id(), $att_name, ['fields' => 'all']);
                                    foreach ($attribute_values as $attribute_value) {
                                        $value_name = esc_html($attribute_value->name);
                                        if ($attribute_taxonomy->attribute_public) {
                                            $values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $att_name)) . '">' . $value_name . '</a>';
                                        } else {
                                            $values[] = $value_name;
                                        }
                                    }
                                } else {
                                    $values = $product_att->get_options();
                                    foreach ($values as $value) {
                                        $value = make_clickable(esc_html($value));
                                    }
                                }
                                echo implode(', ', $values);
                                echo '</td>';
                            } else {
                                echo '<td class="products-compare-row-' . esc_attr($post->ID) . '">&#9867;</td>';
                            }
                        endwhile;
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php
        endif;

        wp_send_json(['html' => ob_get_clean()]);
    }

    add_action('wp_ajax_zoo_get_compare_products', 'zoo_ajax_get_compare_items');
    add_action('wp_ajax_nopriv_zoo_get_compare_products', 'zoo_ajax_get_compare_items');
}

/**
 * AJAX get Wishlist products
 */
if (!function_exists('zoo_ajax_get_wishlist_items')) {
    function zoo_ajax_get_wishlist_items()
    {
        if (empty($_POST['wishlistItems'])) {
            wp_send_json(['html' => '<p>' . esc_html_e('Wishlist is empty.', 'hello-elementor') . '</p>']);
        }

        $items = json_decode($_POST['wishlistItems']);

        $args = [
            'post_type' => ['product', 'product_variation'],
            'suppress_filters' => true,
            'no_found_rows' => true,
            'posts_per_page' => -1,
            'ignore_sticky_posts' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ];

        foreach ($items as $item_id) {
            $args['post__in'][] = absint($item_id);
        }

        $query = new WP_Query(apply_filters('woocommerce_wishlist_products_query', $args));

        ob_end_clean();

        ob_start();

        if ($query->have_posts()) : ?>
            <h2 class="wishlist-panel-title zoo-popup-panel-title"><?php echo esc_html__('SẢN PHẨM YÊU THÍCH', 'hello-elementor') ?></h2>
            <div class="zoo-wrap-popup-content">
                <table class="wishlist-items-table">
                    <thead>
                    <tr>

                        <th class="product-thumbnail">
                        </th>
                        <th class="product-title" colspan="1">
                            <?php esc_html_e('Sản Phẩm', 'hello-elementor'); ?>
                        </th> 
                        <th class="product-price" colspan="1">
                            <?php esc_html_e('Giá', 'hello-elementor'); ?>
                        </th>
                        <th class="product-add-to-cart" colspan="1">
                            <?php esc_html_e('Thêm Vào Giở', 'hello-elementor'); ?>
                        </th>
                        <th class="product-remove-wishlist">
                            <?php esc_html_e('Xóa', 'hello-elementor'); ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($query->have_posts()) : $query->the_post();
                        $product = wc_get_product(get_the_ID());
                        $stock_status = $product->get_stock_status();
                        ?>
                        <tr id="wishlist-item-row-<?php echo esc_attr(get_the_ID()); ?>">
                            <td class="product-thumbnail">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            </td>
                            <td class="product-title product-loop-title">
                                <a href="<?php the_permalink(); ?>"
                                   title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                            </td>
                            <td class="product-price">
                                    <?php woocommerce_template_loop_price(); ?>
                            </td>
                            <td class="product-title product-loop-title">
                                <div class="wrap-product-add-cart">
                                    <?php
                                    if ($product) {
                                        $args = array(
                                            'quantity' => 1,
                                            'class' => implode(' ', array_filter(array(
                                                'button',
                                                'product_type_' . $product->get_type(),
                                                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                                $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                                            ))),
                                            'attributes' => array(
                                                'data-product_id' => get_the_ID(),
                                                'data-product_sku' => $product->get_sku(),
                                                'aria-label' => $product->add_to_cart_description(),
                                                'rel' => 'nofollow',
                                            ),
                                        );
                                        $args['attributes']['aria-label'] = strip_tags($args['attributes']['aria-label']);
                                        wc_get_template('loop/add-to-cart.php', $args);
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="product-remove-wishlist">
                                <a href="#" class="remove-from-wishlist"
                                   title="<?php echo esc_attr__('Remove', 'hello-elementor'); ?>"
                                   data-id="<?php echo esc_attr(get_the_ID()); ?>">
                                    <i class="fas fa-times"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php
        endif;

        wp_send_json(['html' => ob_get_clean()]);
    }

    add_action('wp_ajax_zoo_get_wishlist_products', 'zoo_ajax_get_wishlist_items');
    add_action('wp_ajax_nopriv_zoo_get_wishlist_products', 'zoo_ajax_get_wishlist_items');
}
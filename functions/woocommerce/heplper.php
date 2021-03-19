<?php
//Single Product Tabs
add_filter( 'woocommerce_product_tabs', 'ab_custom_tabs', 100 );
function ab_custom_tabs( $tabs ) {
    unset( $tabs['reviews'] );            // Remove the reviews tab
    unset( $tabs['additional_information'] );     // Remove the additional information tab
    return $tabs;
}

//Checkout
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
add_filter( 'woocommerce_checkout_fields' , 'ab_remove_woo_checkout_fields' );
function ab_remove_woo_checkout_fields( $fields ) {

    // remove billing fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
   
    // remove shipping fields 
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_postcode']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_state']);
    
    return $fields;
}
/**
* Chỉ hiển thị free shipping nếu đủ điều kiện free shipping
 */
function my_hide_shipping_when_free_is_available( $rates ) {
    $free = array();
    foreach ( $rates as $rate_id => $rate ) {
        if ( 'free_shipping' === $rate->method_id ) {
            $free[ $rate_id ] = $rate;
            break;
        }
    }
    return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'my_hide_shipping_when_free_is_available', 100 );

/*
* Thêm tính năng chọn cửa hàng
*/

if( get_bloginfo('language') == "zh-TW" ){

    add_action('woocommerce_checkout_billing', 'add_check_store', 30);
    function add_check_store(){
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if (is_front_page() && !empty($wp_query->query['paged'])) {
            $paged = $wp_query->query['paged'];
        }
        $args = array(
            'post_type'             => 'store',
            'post_status'           => array('publish'),
            'posts_per_page'        => -1,
            'stores' => strtolower(get_bloginfo('language')),
            'paged' => $paged,
        );
        $the_query = new WP_Query($args);
        $city_arr = $distric_arr = $sub_distric_arr = array();
        if ($the_query->have_posts()):
            while ($the_query->have_posts()):
                $the_query->the_post();  
                $city_arr[] = get_field('city', get_the_ID());
            endwhile;
        endif;
        $city = array_unique($city_arr);
        ?>

        <div class="head-store"><img src="https://s3-ap-southeast-1.amazonaws.com/static.shoplineapp.com/web/v1/img/seven-eleven.png">
            <?php echo esc_html__('Nhận tại hệ thống 7-11','hello-elementor');?>
        </div>
        <a href="#" class="btn-show-store-infomation"><?php echo __('Tìm cửa hàng','hello-elementor'); ?></a>
        <div class="check-store">
            <div class="mash"></div>
            <div class="store-infomation">
                <span class="close-panel"><label><?php echo __('Chọn','hello-elementor'); ?></label><i class="eicon-close"></i></span>
                <div class="map-7-11">
                    <div class="logo"><img src="https://emap.pcsc.com.tw/ecmap/images/logo.png"></div>
                    <ul class="items">
                        <li class="item choose-address active" data-tab="address"><?php echo __('Địa chỉ','hello-elementor'); ?></li>
                        <li class="item choose-name" data-tab="name"><?php echo __('Tên cửa hàng','hello-elementor'); ?></li>
                        <li class="item choose-code" data-tab="code"><?php echo __('Mã 7-11','hello-elementor'); ?></li>
                    </ul>
                </div>
                <div class="store-infomation-inner">
                    <div class="list-panel">
                        <div class="select select-address" data-tab="address">
                            <select class="city active" data-field="city" data-filter="distric">
                                <option value="#"><?php echo __('Chọn Thành Phố / Tỉnh','hello-elementor'); ?> </option>
                                <?php foreach ($city as $city_value) {
                                    echo '<option value="'.$city_value.'">'.$city_value.'</option>';
                                } ?>
                            </select>
                            <select class="distric un-active" data-field="distric" data-filter="sub_distric">   
                                <option class="default" value="#"><?php echo __('Chọn Quận / Huyện','hello-elementor'); ?> </option>
                            </select>
                            <select class="sub_distric un-active" data-field="sub_distric">
                                <option class="default" value="#"><?php echo __('Chọn Phường / Xã','hello-elementor'); ?> </option>
                            </select>
                        </div>
                        <div class="select select-name" data-tab="name">
                            <input class="name" type="text" value="" placeholder="<?php echo __('Nhập tên cửa hàng','hello-elementor'); ?>">
                            <button type="submit" class="btn-name">
                               <?php  echo __('Tra cứu','hello-elementor'); ?>
                            </button>
                        </div>
                        <div class="select select-code" data-tab="code">
                            <input class="code" type="text" value="" placeholder="<?php echo __('Nhập mã cửa hàng','hello-elementor'); ?>">
                            <button type="submit" class="btn-code">
                                <?php  echo __('Tra cứu','hello-elementor'); ?>
                            </button>
                        </div>
                        <div class="load"><img src="<?php echo get_stylesheet_directory_uri().'/assets/images/loading.gif'; ?>"></div>
                    </div>
                    <div class="show-address">
                        <div class="hello">
                            <h4 class="welcome"><?php echo __('Quý khách vui lòng chọn cửa hàng gần nhất của chúng tôi.','hello-elementor'); ?></h4>
                            <p class="mess-city"><i aria-hidden="true" class="ab-font ab-icon-undo-1"></i><?php echo __(' Đầu tiên, xin quý khách vui lòng chọn: Thành Phố hoặc Tỉnh','hello-elementor'); ?></p>
                            <p class="mess-distric"><i aria-hidden="true" class="ab-font ab-icon-undo-1"></i><?php echo __(' Tiếp theo, xin quý khách vui lòng chọn: Quận hoặc Huyện','hello-elementor'); ?></p>
                            <p class="mess-sub-distric"><i aria-hidden="true" class="ab-font ab-icon-undo-1"></i><?php echo __(' Cuối cùng, xin quý khách vui lòng chọn: Phường hoặc Xã','hello-elementor'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    //Thêm trường hiển thị dữ liệu chọn cửa hàng
    /*
    Addition of WooCommerce Custom Checkout Field
    */
    function ab_custom_checkout_fields($fields){
        $fields['ab_extra_fields'] = array(
            'ab_address_field' => array(
                'type' => 'text',
                'required'      => true,
                'label' => __( 'Địa chỉ cửa hàng','hello-elementor' ),
                'placeholder' => __('Tự động nhập','hello-elementor') ,
            ),
            'ab_code_field' => array(
                'type' => 'text',
                'required'      => true,
                'label' => __( 'Mã 7-11:','hello-elementor' ),
                'placeholder' => __('Tự động nhập','hello-elementor') ,
            ),
               
        );
        return $fields;
    }
    add_filter( 'woocommerce_checkout_fields', 'ab_custom_checkout_fields' );
    function ab_extra_checkout_fields(){
        $checkout = WC()->checkout(); ?>
        <div class="extra-fields">
        <h3><?php _e( 'Thông tin cửa hàng bạn chọn','hello-elementor' ); ?></h3>
        <?php
           foreach ( $checkout->checkout_fields['ab_extra_fields'] as $key => $field ) : ?>
                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
            <?php endforeach; ?>
        </div>
    <?php }
    add_action( 'woocommerce_checkout_after_customer_details' ,'ab_extra_checkout_fields' );

    /*
    Save the Data of Custom Checkout WooCommerce Fields
    */
    function ab_save_extra_checkout_fields( $order_id, $posted ){
        // don't forget appropriate sanitization if you are using a different field type
        if( isset( $posted['ab_address_field'] ) ) {
            update_post_meta( $order_id, '_ab_address_field', sanitize_text_field( $posted['ab_address_field'] ) );
        }
        if( isset( $posted['ab_code_field'] ) ) {
            update_post_meta( $order_id, '_ab_code_field', sanitize_text_field( $posted['ab_code_field'] ) );
        }
    }
    add_action( 'woocommerce_checkout_update_order_meta', 'ab_save_extra_checkout_fields', 10, 2 );

    /*
    Display  the Data of  WooCommerce Custom Fields to User
    */

    function ab_display_order_data( $order_id ){  ?>
        <h2 class="woocommerce-column__title"><?php _e( 'Thông tin cửa hàng bạn chọn','hello-elementor' ); ?></h2>
        <table class="shop_table shop_table_responsive additional_info">
            <tbody>
                <tr>
                    <th><?php _e( 'Địa chỉ cửa hàng:','hello-elementor' ); ?></th>
                    <td><?php echo get_post_meta( $order_id, '_ab_address_field', true ); ?></td>
                </tr>
                <tr>
                    <th><?php _e( 'Mã 7-11:','hello-elementor' ); ?></th>
                    <td><?php echo get_post_meta( $order_id, '_ab_code_field', true ); ?></td>
                </tr>
            </tbody>
        </table>
    <?php }
    add_action( 'woocommerce_thankyou', 'ab_display_order_data', 20 );
    add_action( 'woocommerce_view_order', 'ab_display_order_data', 20 );

    /*
    Display WooCommerce Admin Custom Order Fields
    */
    function ab_display_order_data_in_admin( $order ){  ?>
        <div class="order_data_column">

            <h4><?php _e( 'Thông tin cửa hàng giao hàng', 'woocommerce' ); ?><a href="#" class="edit_address"><?php _e( 'Edit', 'woocommerce' ); ?></a></h4>
            <div class="address">
            <?php
                echo '<p><strong>' . __( 'Địa chỉ cửa hàng', 'hello-elementor' ) . ':</strong>' . get_post_meta( $order->id, '_ab_address_field', true ) . '</p>';
                echo '<p><strong>' . __( 'Mã 7-11:', 'hello-elementor' ) . ':</strong>' . get_post_meta( $order->id, '_ab_code_field', true ) . '</p>';
             ?>
            </div>
            <div class="edit_address">
                <?php woocommerce_wp_text_input( array( 'id' => '_ab_address_field', 'label' => __( 'Địa chỉ cửa hàng','hello-elementor' ), 'wrapper_class' => '_billing_company_field' ) ); ?>
                <?php woocommerce_wp_text_input( array( 'id' => '_ab_code_field', 'label' => __( 'Mã 7-11:','hello-elementor' ), 'wrapper_class' => '_billing_company_field' ) ); ?>
            </div>
        </div>
    <?php }
    add_action( 'woocommerce_admin_order_data_after_order_details', 'ab_display_order_data_in_admin' );

    /*
    Add WooCommerce Custom Fields to Order Emails
    */
    function ab_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
        $fields['address'] = array(
            'label' => __( '<h3>Địa chỉ cửa hàng bạn đã chọn</h3>','hello-elementor' ).'',
            'value' => get_post_meta( $order->id, '_ab_address_field', true ),
        );
        $fields['phone'] = array(
            'label' => __( 'Mã 7-11:','hello-elementor' ),
            'value' => get_post_meta( $order->id, '_ab_code_field', true ),
        );

        return $fields;
    }
    add_filter('woocommerce_email_order_meta_fields', 'ab_email_order_meta_fields', 10, 3 );
}
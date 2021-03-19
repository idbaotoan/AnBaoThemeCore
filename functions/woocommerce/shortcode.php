<?php
/**
/*Shortcode
 */

//woocommerce_catalog_ordering
add_shortcode( 'catalog_ordering', function( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 1,
    ), $atts, 'catalog_ordering' );
    ob_start();?>
    <div class="toggle-catalog-ordering">
        <lebel><?php echo esc_html__('Sort by:','woocommerce'); ?></lebel><span class="ab-font ab-icon-down"></span>
        <?php 
        global $wp; 
        $current_url =  home_url( $wp->request ); ?>
        <ul class="catalog-ordering">
            <li><a href="<?php echo $current_url.'/?orderby=popularity' ?>">
                <?php echo esc_html__('Sort by popularity','woocommerce'); ?></a></li>
            <li><a href="<?php echo $current_url.'/?orderby=rating' ?>">
                <?php echo esc_html__('Sort by average rating','woocommerce'); ?></a></li>
            <li><a href="<?php echo $current_url.'/?orderby=date' ?>">
                <?php echo esc_html__('Sort by latest','woocommerce'); ?></a></li>
            <li><a href="<?php echo $current_url.'/?orderby=price' ?>">
                <?php echo esc_html__('Sort by price: low to high','woocommerce'); ?></a></li>
            <li><a href="<?php echo $current_url.'/?price-desc' ?>">
                <?php echo esc_html__('Sort by price: high to low','woocommerce'); ?></a></li>
        </ul> 
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
});
//woocommerce_result_count
add_shortcode( 'woocommerce_result_count', function( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 2,
    ), $atts, 'woocommerce_result_count' );
    ob_start(); 
    woocommerce_result_count();
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
});

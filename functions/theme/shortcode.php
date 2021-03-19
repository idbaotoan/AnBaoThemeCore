<?php
/**
/*Shortcode
 */

//Sidebar Toggle button
add_shortcode( 'sidebar_toggle_button', function( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 3,
    ), $atts, 'sidebar_toggle_button' );
    ob_start();?>
    <div class="sidebar-toggle-button">
        <span class="button-toggle ab-font ab-icon-filter-2"></span>
        <div class="mash"></div>
        <span class="close-button ab-font ab-icon-close-1"></span>
    </div>
    
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
});

// Slide down (kết hợp button + shortcode)
add_shortcode( 'slidedown_button_content', function( $atts ) {
    $atts = shortcode_atts( array(
        'icon' 		=> 'ab-font ab-icon-icon-box',
        'text' 		=> 'Toggle slide',
        'shortcode'	=> '',
        'show'		=> 'no'
    ), $atts, 'slidedown_button_content' );
    ob_start();?>
    <div class="slidedown-toggle-button">
        <div class="button-slidedown <?php echo esc_attr($atts['icon']); ?>">
        	<label><?php echo esc_attr($atts['text']); ?></label>
        </div>
        <div class="content-slidedown <?php echo esc_attr($atts['show']); ?>">
        	<?php echo do_shortcode('[elementor-template id='.esc_attr($atts["shortcode"]).']'); ?>
    	</div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
});
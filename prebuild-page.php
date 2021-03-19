<?php
/**
 * Template Name: Prebuild Page
 * Template Post Type: page
 * The template for displaying RevSlider on a blank page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php $viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' ); ?>
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
hello_elementor_body_open();
?>
<div id="append-wrap"></div>
<div id="prebuild-page" data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
	<div class="header-templates">
		<ul class="header-list">
			<li data-header='[elementor-template id=20]' class="template active">
				<img src="<?php echo get_stylesheet_directory_uri().'/assets/images/header-1.jpg' ?>"></li>

			<li data-header='[elementor-template id=607]' class="template">
				<img src="<?php echo get_stylesheet_directory_uri().'/assets/images/header-2.jpg' ?>"></li>

			<li data-header='[elementor-template id=589]' class="template">
				<img src="<?php echo get_stylesheet_directory_uri().'/assets/images/header-1.jpg' ?>"></li>

			<li data-header='[elementor-template id=598]' class="template">
				<img src="<?php echo get_stylesheet_directory_uri().'/assets/images/header-2.jpg' ?>"></li>
		</ul>
	</div>
	<div class="body-templates" data-body=""></div>
	<div class="footer-templates" data-footer=""></div>
	<p class=""><a href="#" class="build">Xây Dựng Trang</a></p>
</div>
<div id="mapoid">
	<?php wp_enqueue_script('ab-mapoid'); ?>
	<img alt="img" data-bg_fill="rgba(244,124,67,0.5)" class="map" src="<?php echo get_stylesheet_directory_uri().'/assets/images/map-01.jpg' ?>" usemap="#map" style="display: block;" />

	<map name="map">
		<area class="harea tooltip" data-id="1" alt="01" title="01" data-img="" href="#" data-ref="" shape="poly" coords="205,204,331,204,332,303,301,305,299,278,236,278,237,305,205,304" >
		<area class="harea tooltip" data-id="2" alt="02" title="02" data-img="" href="#" data-ref="" shape="poly" coords="333,203,332,305,470,305,472,204" >
	</map>
	
</div>

<?php wp_footer(); ?>

</body>
</html>

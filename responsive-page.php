<?php
/**
 * Template Name: Responsive Page
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

$before_url = $url = null;
if(!empty($_SERVER['HTTP_REFERER'])){
	$before_url = $_SERVER['HTTP_REFERER'];
}
if(isset($_GET['link']) && $_GET['link']){
	$url = $_GET['link'];
}
else{
	$url = $before_url;
}

wp_enqueue_script('ab-screenshot');
?>

<div id="top-sidebar-prebuild">
	<div class="back">
		<a href="<?php echo esc_url($before_url); ?>"><i class="fas fa-backward"></i><?php echo esc_html__('Trở về','ab'); ?></a>
	</div>
    <ul class="responsive-devices">
        <li class="mobile" data-device="mobile"><i class="fas fa-mobile-alt"></i> <lable><?php echo esc_html__('Điện thoại','ab'); ?></lable></li>
        <li class="tablet" data-device="tablet"><i class="fas fa-tablet-alt"></i> <lable><?php echo esc_html__('Máy tính bảng','ab'); ?></lable></li>
        <li class="desktop" data-device="desktop"><i class="fas fa-desktop"></i> <lable><?php echo esc_html__('Máy tính','ab'); ?></lable></li>

    </ul>
    <div id="btnSave">
    	<div class="btn-inner">
    		<i class="fas fa-camera-retro"></i>
    		<lable><?php echo esc_html__('Chụp ảnh màn hình','ab'); ?></lable>
    	</div>
    	<img src="<?php echo get_stylesheet_directory_uri().'/assets/images/loading.gif'; ?>">
    </div>
</div>
<div id="output-image"></div>
<div id="show-device" class="desktop">
	<div class="device-inner">
		<div class="inner-mash">
			<iframe src="<?php echo esc_url($url); ?>"></iframe>
		</div>
	</div>
</div>
<?php wp_footer(); ?>

</body>
</html>
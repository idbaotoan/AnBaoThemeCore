<?php
/**
 * Template Name: Store Page
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
	<meta name="google-site-verification" content="sErgOpS7xZPmppHQl_JhOaghxRjicpi9We0DtCakdis" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
hello_elementor_body_open();
?>
<h1>Đây là trang chọn cửa hàng</h1>
<?php 
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if (is_front_page() && !empty($wp_query->query['paged'])) {
    $paged = $wp_query->query['paged'];
}
$args = array(
    'post_type'             => 'store',
    'post_status'           => array('publish'),
    'posts_per_page'        => -1,
    'stores' => 'viet-nam',
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
<a href="#" class="btn-show-store-infomation">Chọn vị trí cửa hàng</a>
<div class="check-store">
	<div class="mash"></div>
	<div class="store-infomation">
		<i class="close-panel">Đóng</i>
		<div class="select-address">
			<select class="city active" data-field="city" data-filter="distric">
				<option value="#">Chọn Thành Phố / Tỉnh </option>
				<?php foreach ($city as $city_value) {
					echo '<option value="'.$city_value.'">'.$city_value.'</option>';
				} ?>
			</select>
			<select class="distric un-active" data-field="distric" data-filter="sub_distric">	
				<option value="#">Chọn Quận / Huyện </option>
			</select>
			<select class="sub_distric un-active" data-field="sub_distric">
				<option value="#">Chọn Phường / Xã </option>
			</select>
		</div>
		<div class="show-address"><img class="load" src="<?php echo get_stylesheet_directory_uri().'/assets/images/loading.gif'; ?>"></div>
	</div>
</div>
<?php wp_footer(); ?>

</body>
</html>

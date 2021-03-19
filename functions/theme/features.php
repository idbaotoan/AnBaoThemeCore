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
if (!function_exists('ab_get_excerpt')) {
    function ab_get_excerpt($length = 55)
    {
        $post = get_post(null);
        $text = $post->post_excerpt ?: $post->post_content;
        $text = do_shortcode($text);
        $text = strip_shortcodes($text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = wp_trim_words($text, $length, false);

        return $text . '...';
    }
}
// Ajax template content
add_action( 'wp_ajax_content', 'content_action' );
add_action( 'wp_ajax_nopriv_content', 'content_action' );
function content_action() {
    $code = null; 
    if(isset($_POST['id'])){
        $code = $_POST['id'];
    }
    
    ob_start();
    $code = '[elementor-template id="'.$code.'"]';
    echo do_shortcode($code); 
    
    wp_reset_postdata();
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
 
    wp_die();
}
// Ajax template porfolio
add_action( 'wp_ajax_portfolio', 'portfolio_action' );
add_action( 'wp_ajax_nopriv_portfolio', 'portfolio_action' );
function portfolio_action() {
    $post_type = $posts_per_page = $cat = $ignore_sticky_posts = $post_ids = $paged = $orderby = $order = $offset = $image_size = $taxonomy = null;
    if(isset($_POST['post_type'])){
        $post_type = $_POST['post_type'];
    }
    if(isset($_POST['posts_per_page'])){
        $posts_per_page = $_POST['posts_per_page'];
    }
    if(isset($_POST['cat'])){
        $cat = $_POST['cat'];
        if($cat == 'all'){
            $cat = '';
        }
    }
    if(isset($_POST['ignore_sticky_posts'])){
        $ignore_sticky_posts = $_POST['ignore_sticky_posts'];
    }
    if(isset($_POST['post_ids'])){
        $post_ids = $_POST['post_ids'];
    }
    if(isset($_POST['paged'])){
        $paged = $_POST['paged'];
    }
    if(isset($_POST['orderby'])){
        $orderby = $_POST['orderby'];
    }
    if(isset($_POST['order'])){
        $order = $_POST['order'];
    }
    if(isset($_POST['offset'])){
        $offset = $_POST['offset'];
    }
    if(isset($_POST['image_size'])){
        $image_size = $_POST['image_size'];
    }
    if(isset($_POST['taxonomy'])){
        $taxonomy = $_POST['taxonomy'];
    }
    ob_start();
    
    $args = array(
        'post_type'             => $post_type,
        'post_status'           => array('publish'),
        'posts_per_page'        => $posts_per_page,
        $taxonomy               => $cat,
        'ignore_sticky_posts'   => $ignore_sticky_posts,
        'post__in'              => $post_ids,
        'paged'                 => $paged,
        'orderby'               => $orderby,
        'order'                 => $order, 
        'offset'                => $offset,
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()){
        while ($the_query->have_posts()):
            $the_query->the_post(); ?>
            <article <?php echo post_class('ab-post-item ab-col') ?>>
                <div class="ab-post-inner">
                <?php
                if (has_post_thumbnail()) { ?>
                    <div class="ab-wrap-media">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>" target="_blank">
                            <div class="media"><?php the_post_thumbnail($settings['image_size']); ?></div>
                            <div class="mash"></div>
                        </a>
                        <div class="feature">
                            <a href="<?php echo esc_url(get_site_url()) ?>/responsive-page?link=<?php echo get_post_meta(get_the_ID(),'ab_link', true); ?>" target="_blank" rel="nofollow">
                                <i class="far fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="ab-wrap-content">
                        <h3 class="entry-title title-post">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>" target="_blank">
                            <?php the_title(); ?>
                        </a></h3>
                    </div>
                </div>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
    }
    
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
 
    wp_die();
}
// Ajax Post
add_action( 'wp_ajax_posts', 'posts_action' );
add_action( 'wp_ajax_nopriv_posts', 'posts_action' );
function posts_action() {
    $post_type = $posts_per_page = $cat = $ignore_sticky_posts = $post_ids = $paged = $orderby = $order = $offset = $image_size = $taxonomy = $show_cat_post = $show_author_post = $show_date_post = $output_type = $excerpt_length = $show_read_more = $read_more_text = null;
    if(isset($_POST['post_type'])){
        $post_type = $_POST['post_type'];
    }
    if(isset($_POST['posts_per_page'])){
        $posts_per_page = $_POST['posts_per_page'];
    }
    if(isset($_POST['cat'])){
        $cat = $_POST['cat'];
        if($cat == 'all'){
            $cat = '';
        }
    }
    if(isset($_POST['ignore_sticky_posts'])){
        $ignore_sticky_posts = $_POST['ignore_sticky_posts'];
    }
    if(isset($_POST['post_ids'])){
        $post_ids = $_POST['post_ids'];
    }
    if(isset($_POST['paged'])){
        $paged = $_POST['paged'];
    }
    if(isset($_POST['orderby'])){
        $orderby = $_POST['orderby'];
    }
    if(isset($_POST['order'])){
        $order = $_POST['order'];
    }
    if(isset($_POST['offset'])){
        $offset = $_POST['offset'];
    }
    if(isset($_POST['image_size'])){
        $image_size = $_POST['image_size'];
    }
    if(isset($_POST['taxonomy'])){
        $taxonomy = $_POST['taxonomy'];
    }
    if(isset($_POST['show_cat_post'])){
        $show_cat_post = $_POST['show_cat_post'];
    }
    if(isset($_POST['show_author_post'])){
        $show_author_post = $_POST['show_author_post'];
    }
    if(isset($_POST['show_date_post'])){
        $show_date_post = $_POST['show_date_post'];
    }
    if(isset($_POST['output_type'])){
        $output_type = $_POST['output_type'];
    }

    if(isset($_POST['excerpt_length'])){
        $excerpt_length = $_POST['excerpt_length'];
    }
    if(isset($_POST['show_read_more'])){
        $show_read_more = $_POST['show_read_more'];
    }
    if(isset($_POST['read_more_text'])){
        $read_more_text = $_POST['read_more_text'];
    }
    ob_start();
    
    $args = array(
        'post_type'             => 'post',
        'post_status'           => array('publish'),
        'posts_per_page'        => $posts_per_page,
        'category_name'         => $cat,
        'ignore_sticky_posts'   => $ignore_sticky_posts,
        'post__in'              => $post_ids,
        'paged'                 => $paged,
        'orderby'               => $orderby,
        'order'                 => $order, 
        'offset'                => $offset,
    );
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()){
        while ($the_query->have_posts()):
            $the_query->the_post(); ?>
            <article <?php echo post_class('ab-post-item ab-col') ?>>
                <div class="ab-post-inner">
                <?php
                if (has_post_thumbnail()) { ?>
                <div class="ab-wrap-media">
                    <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute() ?>">
                        <?php the_post_thumbnail($image_size); ?></a>
                    </div>
                <?php } ?>
                <div class="ab-wrap-content">
                    <?php the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>'); ?>
                    <ul class="ab-post-info">
                        <?php
                        if ($show_cat_post) { ?>
                            <li class="cat">
                                <i class="<?php echo esc_attr($settings['icon_cate']) ?>"></i>
                                <?php echo get_the_term_list(get_the_ID(), 'category', '', ' , ', ''); ?>
                            </li>
                            <?php
                        }
                        if ($show_author_post) { ?>
                            <li class="author">
                                <i class="<?php echo esc_attr($settings['icon_author']) ?>"></i>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                                    title="<?php echo esc_attr(get_the_author()) ?>">
                                    <?php echo esc_html(get_the_author()) ?>
                                </a>
                            </li>
                            <?php
                        }
                        if ($show_date_post) { ?>
                            <li class="date">
                                <i class="<?php echo esc_attr($settings['icon_date']) ?>"></i>
                                <?php
                                echo esc_html(get_the_date()); ?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                    if ($output_type != 'none') {
                        ?>
                        <div class="entry-content">
                            <?php
                            if ($output_type == 'excerpt') {
                                echo ab_get_excerpt($excerpt_length);
                            } else {
                                the_content();
                            } ?>
                        </div>
                        <?php
                    }
                    if ($show_read_more == 'true') { ?>
                        <div class="wrap-readmore"><a href="<?php the_permalink(); ?>"
                            class="readmore"><?php echo ent2ncr($read_more_text); ?></a></div>
                        <?php } ?>
                    </div>
                </div>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
    }
    
    $output = ob_get_contents();
    ob_end_clean();
    echo ent2ncr($output);
 
    wp_die();
}
// Ajax search
add_action('wp_ajax_ab_ajax_search', 'ab_ajax_search_action');
add_action('wp_ajax_nopriv_ab_ajax_search', 'ab_ajax_search_action');

if (!function_exists('ab_ajax_search_action')) {
    function ab_ajax_search_action(){
        $post_type = $cate_name = $keyword = null;
        
        if(isset($_POST['post_type'])){
            $post_type = $_POST['post_type'];
        }
        if(isset($_POST['taxonomy'])){
            $taxonomy = $_POST['taxonomy'];
        }
        if(isset($_POST['cate_name'])){
            $cate_name = $_POST['cate_name'];
        }
        if(isset($_POST['keyword'])){
            $keyword = $_POST['keyword'];
        }
        if ( is_front_page() ) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;   
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
        }
        
        $args = array(
            'post_type' => $post_type,
            'search_prod_title' => $keyword,
            $taxonomy=> $cate_name,
            'posts_per_page' => -1,
            'orderby' => 'date',
            'paged' => $paged,
        );
        add_filter( 'posts_where', 'title_filter', 10, 2 );
        $product_query = new WP_Query($args);
        remove_filter( 'posts_where', 'title_filter', 10, 2 );
        if ($product_query->have_posts()):
            
            ob_start();
            echo '<ul class="ajax-search-result">';
            while ($product_query->have_posts()) {
                $product_query->the_post(); 
                $n 
                ?>
                <li class="search-item">
                    <a href="<?php echo esc_url(get_the_permalink()); ?>" target="_blank" rel="nofollow">
                        <div class="image">
                            <?php echo get_the_post_thumbnail(get_the_ID(),'thumbnail');  ?>
                        </div>
                        <h3 class="entry-title title-post">
                            <?php the_title(); ?>
                        </h3>
                    </a>
                </li>
            <?php 
            }
            echo '</ul>';

            $output = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            echo ent2ncr($output);
        endif;
        wp_die();
        
    }
}
function title_filter( $where, &$wp_query ){
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
    }
    return $where;
}
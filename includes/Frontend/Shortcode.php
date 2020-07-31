<?php

namespace JWP\JPE\Frontend;

/**
 * Shortcode handler class
 */
class Shortcode {

    const shortcode = 'jwp-post-excerpt';

   /**
     * Shortcode class constructor
     */
    function __construct() {
        add_shortcode( self::shortcode, [ $this, 'render_shortcode' ] );
    }

    /**
     * Renders shortcode
     *
     * @param array $atts
     * @param string $content
     * 
     * @return string
     */
    public function render_shortcode( $atts, $content = '' ) {
        global $wp;
        $url          = home_url( $wp->request );
        $number_posts = 10;
        $category     = [];
        $order        = '';
        $order_by     = '';
        $meta_key     = '';

        $terms = get_terms( array(
            'taxonomy'   => 'category',
            'hide_empty' => false,
        ) );

        if ( isset( $atts['category'] ) ) {
            $cats = explode( ",", $atts['category'] );
            foreach ( $cats as $cat ) {
                $term = get_term_by( 'name', $cat, 'category' );
                $category[] = $term->term_id;
            }
        }

        if ( isset( $atts['order'] ) ) {
            $order    = $atts['order'];
            $order_by = 'meta_value_num';
            $meta_key = 'post_views_count';
        } 

        if ( isset( $_POST['jwp-pe-submit'] ) ) {
            $number_posts = isset( $_POST['numberposts'] ) ? $_POST['numberposts']: $number_posts;
            $order        = isset( $_POST['order'] ) ? $_POST['order']            : $order;
            $order_by     = isset( $_POST['order'] ) ? 'meta_value_num'           : $order_by;
            $meta_key     = isset( $_POST['order'] ) ? 'post_views_count'         : $meta_key;
            $category     = isset( $_POST['category'] ) ? $_POST['category']      : $category;
        }
        
        $defaults = array(
            'numberposts'  => $number_posts,
            'category__in' => $category,
            'orderby'      => $order_by,
            'order'        => $order,
            'meta_key'     => $meta_key,
        );

        $args  = shortcode_atts( $defaults, $atts );
        $posts = get_posts( $args );

        if ( isset( $atts['ids'] ) ) {
            $marked_ids = explode( ",", $atts['ids'] );
            $marked_ids = array_map( function( $value ) {
                return (int) $value;
            }, $marked_ids );

        } else {
            foreach ( $posts as $post ) {
                $marked_ids[] = $post->ID;
            }
        }

        $total_post = wp_count_posts()->publish;

        ob_start();
        
        if ( $posts ) {
            $form = __DIR__ . '/views/form.php';

            if ( file_exists( $form ) ) {
                include $form;
            }

            foreach ( $posts as $post ) {
                $excerpt = '';

                if ( in_array( $post->ID, $marked_ids ) ) {
                    
                    if ( has_excerpt( $post ) ) {
                        $excerpt = get_the_excerpt( $post );
                    } else {
                        $excerpt = jwp_pe_custom_excerpt( $post, 200 );
                    }
                }
                $view_count = jwp_pe_emphasize_text( jwp_pe_get_post_view_count( $post ), 'Views' );
                
                ?>
                
                <div class="jwp-pe-box">
                    <h2>
                        <a class ="jwp-pe-a" href="<?php echo get_permalink( $post ); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo $post->post_title; ?>
                        </a>
                    </h2>
                    <span><?php echo $view_count; ?></span>
                </div>
                <p><?php echo $excerpt; ?></p>
                <hr>
                
                <?php 
            }
        }

        $content = ob_get_clean();
        return $content;
    }
}
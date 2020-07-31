<?php

namespace JWP\JPE\Auth;

/**
 * Meta box handler class
 */
class Meta_Box {

    /**
     * Class constructor
     */
    function __construct() {
        add_action( 'add_meta_boxes', [ self::class, 'add' ] );
        add_action( 'save_post', [ self::class, 'save' ] );
    }

    /**
     * Adds custom meta box
     *
     * @return void
     */
    public static function add() {
        $screens = [ JWP_PE_META_TYPE, 'wporg_cpt' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'jwp_pe_metabox',
                __( 'Post Excerpt', 'jwp-post-excerpt' ),
                [ self::class, 'render' ],
                $screen 
            );
        }
    }
 
    /**
     * Saves the meta box value in the database
     *
     * @param int $post_id
     * 
     * @return void
     */
    public static function save( $post_id ) {

        if ( array_key_exists( 'jwp-pe-metabox', $_POST ) ) {

            $new_data = array(
                'ID'           => $post_id,
                'post_excerpt' => wp_kses_post( esc_html( $_POST['jwp-pe-metabox'] ) ),
            );

            // update post_excerpt value in the post table
            wp_update_post( $new_data );
        }
    }
 
    /**
     * Renders the HTML field
     *
     * @param object $post
     * 
     * @return void
     */
    public static function render( $post ) {
        $excerpt = '';

        if ( has_excerpt( $post ) ) {
            $excerpt = get_the_excerpt( $post );
        }
        
        ?>
        <textarea name="jwp-pe-metabox" id="jwp-pe-metabox" class="jwp-pe-metabox" cols="100" rows="10"><?php echo $excerpt; ?></textarea>
        <?php
    }
}
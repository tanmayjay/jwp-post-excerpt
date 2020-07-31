<?php

/**
 * Retrieves the view counts for each post
 *
 * @param object $post
 * 
 * @return int
 */
function jwp_pe_get_post_view_count( $post ) {
    $count_key = 'post_views_count';
    $count     = get_post_meta( $post->ID, $count_key, true );

    if ( '' == $count && 'page' != $post->post_type ) {
        $count = 0;
        delete_post_meta( $post->ID, $count_key );
        add_post_meta( $post->ID, $count_key, $count );
    }

    return $count;
}

/**
 * Counts and sets the views for each post
 *
 * @return void
 */
function jwp_pe_set_post_view_count() {
    
    if ( is_single() ) {
        $count_key = 'post_views_count';
        $count     = get_post_meta( get_the_ID(), $count_key, true );

        if( $count == '' ) {
            $count = 0;
            delete_post_meta( get_the_ID(), $count_key );
            add_post_meta( get_the_ID(), $count_key, $count );
        } else {
            $count++;
            update_post_meta( get_the_ID(), $count_key, $count );
        }

    }
}

/**
 * Emphasizes texts with <em> tag
 *
 * @param mixed $value
 * @param mixed $extension = null
 * 
 * @return string
 */
function jwp_pe_emphasize_text( $value, $extension = null ) {
    ob_start();

    ?>
    <em><?php echo "$value $extension"; ?></em>
    <?php
    
    $result = ob_get_clean();
    return $result;
}

/**
 * Customizes the post excerpt
 *
 * @param object $post
 * @param int $length
 * 
 * @return string
 */
function jwp_pe_custom_excerpt( $post, $length = 200 ) {
    $excerpt = strip_tags( $post->post_content );
    
    if ( strlen( $excerpt )  > $length ) {
        $excerpt  = substr( $excerpt, 0, $length );
        $excerpt  = substr( $excerpt, 0, strrpos( $excerpt, ' ' ) );
        $excerpt .= '...';
    }

    return $excerpt;
}
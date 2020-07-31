<?php

/**
 * Plugin Name:       JWP Post Excerpt
 * Plugin URI:        https://github.com/tanmayjay/wordpress/tree/master/5-Post-Metadata/jwp-post-excerpt
 * Description:       A plugin to show post excerpt using a customizable shortcode.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Tanmay Kirtania
 * Author URI:        https://linkedin.com/in/tanmay-kirtania
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       jwp-post-excerpt
 * 
 * 
 * Copyright (c) 2020 Tanmay Kirtania (jktanmay@gmail.com). All rights reserved.
 * 
 * This program is a free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see the License URI.
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class JWP_Post_Excerpt {
    
    /**
     * Static class object
     *
     * @var object
     */
    private static $instance;

    const version   = '1.0.0';

    /**
     * Private class constructor
     */
    private function __construct() {
        $this->define_constants();
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Private class cloner
     */
    private function __clone() {}

    /**
     * Initializes a singleton instance
     * 
     * @return \JWP_Post_Excerpt
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Defines the required constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'JWP_PE_VERSION', self::version );
        define( 'JWP_PE_FILE', __FILE__ );
        define( 'JWP_PE_PATH', __DIR__ );
        define( 'JWP_PE_URL', plugins_url( '', JWP_PE_FILE ) );
        define( 'JWP_PE_ASSETS', JWP_PE_URL . '/assets' );
    }

    /**
     * Updates info on plugin activation
     *
     * @return void
     */
    public function activate() {
        $activator = new JWP\JPE\Activator();
        $activator->run();
    }

    /**
     * Initializes the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        new JWP\JPE\Frontend();

        if ( is_user_logged_in() ) {
            if ( current_user_can( 'edit_posts' ) ) {
                new JWP\JPE\Auth();
            }
        }
    }

    /**
     * Includes the stylesheet
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'jwp-pe-styles', JWP_PE_ASSETS . '/css/style.css', '', '1.0.0' );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \JWP_Post_Excerpt
 */
function jwp_post_excerpt() {
    return JWP_Post_Excerpt::get_instance();
}

//kick off the plugin
jwp_post_excerpt();
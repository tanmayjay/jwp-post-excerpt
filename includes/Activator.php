<?php

namespace JWP\JPE;

/**
 * Plugin activator class
 */
class Activator {

    /**
     * Runs the activator
     *
     * @return void
     */
    public function run() {
        $this->add_info();
    }

    /**
     * Adds activation info
     *
     * @return void
     */
    public function add_info() {
        $activated = get_option( 'jwp_pe_installed' );

        if ( ! $activated ) {
            update_option( 'jwp_pe_installed', time() );
        }

        update_option( 'jwp_pe_version', JWP_PE_VERSION );
    }
}
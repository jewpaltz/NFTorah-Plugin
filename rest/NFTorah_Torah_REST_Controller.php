<?php

use Monolog\Logger;

class NFTorah_Torah_REST_Controller extends WP_REST_Controller {


    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $namespace = 'NFTorah/v1';
        $base = 'torah';
        register_rest_route( $namespace, '/' . $base . '/parshiot', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this, 'get_parshiot' ),
                'permission_callback' => fn()=> true
            ),
        ) );
    }

    public function get_parshiot(){
        return NFTorah\Torah::GetParshiot();
    }
}
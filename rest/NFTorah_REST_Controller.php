<?php 


class NFTorah_REST_Controller extends WP_REST_Controller {


/**
 * Register the routes for the objects of the controller.
 */
public function register_routes() {
    $namespace = 'NFTorah/v1';
    $base = 'purchases';
    register_rest_route( $namespace, '/' . $base, array(
        array(
            'methods'         => WP_REST_Server::READABLE,
            'callback'        => array( $this, 'get_items' ),
            'permission_callback' => array( $this, 'get_items_permissions_check' ),
            'args'            => array( $this->get_collection_params() ),
        ),
        array(
            'methods'         => WP_REST_Server::CREATABLE,
            'callback'        => array( $this, 'create_item' ),
            'permission_callback' => array( $this, 'create_item_permissions_check' ),
            'args'            => $this->get_endpoint_args_for_item_schema( true ),
        ),
    ) );
    register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
        array(
            'methods'         => WP_REST_Server::READABLE,
            'callback'        => array( $this, 'get_item' ),
            'permission_callback' => array( $this, 'get_item_permissions_check' ),
            'args'            => array(
                'context'          => array(
                    'default'      => 'view',
                    'required'     => true,
                ),
                'params' => array(
                    'required'     => false,
                    'team_id' => array(
                        'description'        => 'The id(s) for the team(s) in the search.',
                        'type'               => 'integer',
                        'default'            => 1,
                        'sanitize_callback'  => 'absint',
                    ),
                ),
                $this->get_collection_params()
            ),
        ),
        array(
            'methods'         => WP_REST_Server::EDITABLE,
            'callback'        => array( $this, 'update_item' ),
            'permission_callback' => array( $this, 'update_item_permissions_check' ),
            'args'            => $this->get_endpoint_args_for_item_schema( false ),
        ),
        array(
            'methods'  => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_item' ),
            'permission_callback' => array( $this, 'delete_item_permissions_check' ),
            'args'     => array(
                'force'    => array(
                    'default'      => false,
                ),
            ),
        ),
    ) );

    /*
    register_rest_route( $namespace, '/' . $base . '/schema', array(
        'methods'         => WP_REST_Server::READABLE,
        'callback'        => array( $this, 'get_public_item_schema' ),
    ) );
    */
    //print_r( $this->get_collection_params() );
}

/**
 * Get a collection of items
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
public function get_items( $request ) {
    return ['Purchase 1', 'Purchase 2'];
    
}

/**
 * Get one item from the collection
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
public function get_item( $request ) {
    
}

/**
 * Create one item from the collection
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
public function create_item( $request ) {
    //print_r($request);
    $purchase = $request['purchase'];
    $letters = $request['letters'];
    //print_r([ 'purchase'=> $purchase, 'data'=> $letters]);
    return NFTorah::PurchaseFormSave($purchase, $letters, $request->get_header('X-WP-Nonce'));
}

/**
 * Update one item from the collection
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Response
 */
public function update_item( $request ) {

}

/**
 * Delete one item from the collection
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|WP_REST_Request
 */
public function delete_item( $request ) {
    
}

/**
 * Check if a given request has access to get items
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|bool
 */
public function get_items_permissions_check( $request ) {
    return true;
}

/**
 * Check if a given request has access to get a specific item
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|bool
 */
public function get_item_permissions_check( $request ) {
    return $this->get_items_permissions_check( $request );
}

/**
 * Check if a given request has access to create items
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|bool
 */
public function create_item_permissions_check( $request ) {
    return true;
}

/**
 * Check if a given request has access to update a specific item
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|bool
 */
public function update_item_permissions_check( $request ) {
    return $this->create_item_permissions_check( $request );
}

/**
 * Check if a given request has access to delete a specific item
 *
 * @param WP_REST_Request $request Full data about the request.
 * @return WP_Error|bool
 */
public function delete_item_permissions_check( $request ) {
    return $this->create_item_permissions_check( $request );
}

/**
 * Prepare the item for create or update operation
 *
 * @param WP_REST_Request $request Request object
 * @return WP_Error|object $prepared_item
 */
protected function prepare_item_for_database( $request ) {

}

/**
 * Prepare the item for the REST response
 *
 * @param mixed $item WordPress representation of the item.
 * @param WP_REST_Request $request Request object.
 * @return mixed
 */
public function prepare_item_for_response( $item, $request ) {

}

/**
 * Get the query params for collections
 *
 * @return array
 */
public function get_collection_params() {
    
}

/**
 * Get the Entry schema, conforming to JSON Schema.
 *
 * @since  2.0-beta-1
 * @access public
 *
 * @return array
 */
public function get_item_schema() {
    
}

} ?>
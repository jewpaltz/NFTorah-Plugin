<?php

function NFTorah_register_post_type() {
 
    // movies
 
    $labels = array( 
 
        'name' => __( 'Torahs' , 'NFTorah' ),
 
        'singular_name' => __( 'Torah' , 'NFTorah' ),
 
        'add_new' => __( 'New Torah' , 'NFTorah' ),
 
        'add_new_item' => __( 'Add New Torah' , 'NFTorah' ),
 
        'edit_item' => __( 'Edit Torah' , 'NFTorah' ),
 
        'new_item' => __( 'New Torah' , 'NFTorah' ),
 
        'view_item' => __( 'View Torah' , 'NFTorah' ),
 
        'search_items' => __( 'Search Torah' , 'NFTorah' ),
 
        'not_found' =>  __( 'No Torahs Found' , 'NFTorah' ),
 
        'not_found_in_trash' => __( 'No Torahs found in Trash' , 'NFTorah' ),
 
    );
 
    $args = array(
 
        'labels' => $labels,
 
        'has_archive' => true,
 
        'public' => true,
 
        'hierarchical' => false,
 
        'supports' => array(
 
            'title', 
 
            'editor', 
 
            'excerpt', 
 
            'custom-fields', 
 
            'thumbnail',
 
            'page-attributes'
 
        ),
 
        'rewrite'   => array( 'slug' => 'torah' ),
 
        'show_in_rest' => true
 
    );

    register_post_type( "NFTorah", $args );
    
}
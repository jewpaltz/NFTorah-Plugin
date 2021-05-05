<?php
/*
    B"H 
*/

namespace NFTorah;

class OptionsPage {

    public static function AddMenu(){
        $hookname = add_menu_page(
                'NFTorah Options',
                'NFTorah Options',
                'manage_options',
                'NFTorah',
                [__CLASS__, 'HTML'],
                plugin_dir_url(__FILE__) . 'images/icon_wporg.png',
                20
            );
         
            add_action( 'load-' . $hookname, ['OptionsPage', 'OnSubmit'] );
    }

    public static function HTML(){
        wp_enqueue_style( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.css' );
        $data = Purchases::GetList();
        //print_r($data);
        require_once __DIR__ . '/../partials/display-list.php';
    }

    public static function OnSubmit(){
        if('POST' === $_SERVER['REQUEST_METHOD']){ // Only process on submit

        }    
    }



}
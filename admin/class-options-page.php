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
                ['OptionsPage', 'HTML'],
                plugin_dir_url(__FILE__) . 'images/icon_wporg.png',
                20
            );
         
            add_action( 'load-' . $hookname, ['OptionsPage', 'OnSubmit'] );
    }

    public static function HTML(){

    }

    public static function OnSubmit(){
        if('POST' === $_SERVER['REQUEST_METHOD']){ // Only process on submit

        }    
    }

    public static function AddSettingsType(){
     
    }

    public static function section_developers_html(){
 
    }

}
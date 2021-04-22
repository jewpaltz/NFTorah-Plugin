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
        // check if the user have submitted the settings
        // WordPress will add the "settings-updated" $_GET parameter to the url
        if ( isset( $_GET['settings-updated'] ) ) {
            // add settings saved message with the class of "updated"
            add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
        }
    
        // show error/update messages
        settings_errors( 'wporg_messages' );
        ?>

        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting "wporg_options"
                settings_fields( 'jpfs_options' );
                // output setting sections and their fields
                // (sections are registered for "wporg", each field is registered to a specific section)
                do_settings_sections( 'jpfs' );
                // output save settings button
                submit_button( __( 'Save Settings', 'textdomain' ) );
                ?>
            </form>
        </div>
        <?php

    }

    public static function OnSubmit(){
        if('POST' === $_SERVER['REQUEST_METHOD']){ // Only process on submit

        }    
    }

    public static function AddSettingsType(){
        register_setting( 'jpfs', 'jpfs_options' );
 
        add_settings_section(
            'jpfs_section_developers',
            __( 'The Matrix has you.', 'jpfs' ),
            ['OptionsPage', 'section_developers_html'],
            'jpfs'
        );
     
        // Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
        add_settings_field(
            'jpfs_field_pill', // As of WP 4.6 this value is used only internally.
                                    // Use $args' label_for to populate the id inside the callback.
                __( 'Pill', 'jpfs' ),
            'jpfs_field_pill_cb',
            'jpfs',
            'jpfs_section_developers',
            array(
                'label_for'         => 'wporg_field_pill',
                'class'             => 'wporg_row',
                'jpfs_custom_data' => 'custom',
            )
        );
    
    }

    public static function section_developers_html(){
        ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
        <?php

    }

}
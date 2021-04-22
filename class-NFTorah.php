<?php
/*
    B"H
*/
require_once __DIR__ . '/admin/class-options-page.php';

    class NFTorah{

        public static function Activate(){
            
        }

        public static function Deactivate(){
            
        }

        public static function Init(){
            require_once __DIR__ . '/register_post_type.php';
            NFTorah_register_post_type();

            self::RegisterPublicHooks();      
        }

        public static function AdminInit(){
            NFTorah\OptionsPage::AddSettingsType();
        }

        public static function AdminMenu(){
            NFTorah\OptionsPage::AddMenu();
        }

        public static function RegisterPublicHooks(){
            add_shortcode( 'NFTorah_purchase_form', [__CLASS__, 'PurchaseFormHtml'] );
        }

        public static function RegisterAdminHooks(){

        }

        public static function PurchaseFormHtml(){
            self::PurchaseFormSaveIfSubmitted();
            wp_enqueue_style( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.css' );
            wp_enqueue_script( 'vue', 'https://unpkg.com/vue' );
            wp_enqueue_script( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.js' );
            ob_start();
            require __DIR__ . '/partials/purchase-form.php';    
            return ob_get_clean();       
        }
        public static function PurchaseFormSaveIfSubmitted(){
            
            if ( !isset($_POST['title']) ) {
                return;
            }
        
            // Check that the nonce was set and valid
            if( !wp_verify_nonce($_POST['_wpnonce'], 'wps-frontend-post') ) {
                echo 'Did not save because your form seemed to be invalid. Sorry';
                return;
            }
        
            // Do some minor form validation to make sure there is content
            if (strlen($_POST['title']) < 3) {
                echo 'Please enter a title. Titles must be at least three characters long.';
                return;
            }
            if (strlen($_POST['content']) < 100) {
                echo 'Please enter content more than 100 characters in length';
                return;
            }
        
            // Add the content of the form to $post as an array
            $post = array(
                'post_title'    => $_POST['title'],
                'post_content'  => $_POST['content'],
                'post_category' => $_POST['cat'], 
                'tags_input'    => $_POST['post_tags'],
                'post_status'   => 'draft',   // Could be: publish
                'post_type' 	=> 'post' // Could be: `page` or your CPT
            );
            wp_insert_post($post);
            echo 'Saved your post successfully! :)';
        
        }

        public static function set_locale(){
            /*
                This is definitely in the works. Even though I can't work on it right now.
            */
        }

    }
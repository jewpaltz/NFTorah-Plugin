<?php
/*
    B"H
*/
require_once __DIR__ . '/admin/class-options-page.php';
require_once __DIR__ . '/setup/class-setup.php';

    class NFTorah{

        public static function Activate(){

        }

        public static function Deactivate(){
            do_action( 'qm/debug', 'NFTorah Deactivated' );
            
        }

        public static function Init(){
            NFTorah_setup::update_001_create_original_tables();

            require_once __DIR__ . '/register_post_type.php';
            NFTorah_register_post_type();

            self::RegisterPublicHooks();      
            do_action( 'qm/debug', 'NFTorah Initialized' );        }

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
            wp_enqueue_style( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.css' );
            wp_enqueue_script( 'vue', 'https://unpkg.com/vue', [], '0.1', true );
            wp_enqueue_script( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.js', ['vue'], '0.1', true );
            wp_enqueue_script( 'purchase-form', plugins_url( '', __FILE__ ) . '/assets/js/purchase-form.js', ['buefy'], '0.1', true );

            if(!self::IsPurchaseFormSubmitted()){
                ob_start();
                require __DIR__ . '/partials/purchase-form.php';    
                return ob_get_clean();       
            }else{
                self::PurchaseFormSave();
                return self::DownloadNFTHtml();
            }
        }

        public static function IsPurchaseFormSubmitted(){
            return isset($_POST['title']);
        }
        
        public static function PurchaseFormSave(){
            
        
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

        public static function DownloadNFTHtml(){
            ob_start();
            require __DIR__ . '/partials/download-nft.php';    
            return ob_get_clean();       
        }

         public static function set_locale(){
            /*
                This is definitely in the works. Even though I can't work on it right now.
            */
        }

    }
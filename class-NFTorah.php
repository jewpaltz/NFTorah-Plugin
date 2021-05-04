<?php
/*
    B"H
*/
require_once __DIR__ . '/admin/class-options-page.php';
require_once __DIR__ . '/setup/class-setup.php';
require_once __DIR__ . '/rest/NFTorah_REST_Controller.php';

    class NFTorah{

        public static function Activate(){

        }

        public static function Deactivate(){
            do_action( 'qm/debug', 'NFTorah Deactivated' );
            
        }

        public static function Init(){
            NFTorah_setup::update_001_create_original_tables();
            NFTorah_setup::update_003_allow_exp_and_cvv_null();

            require_once __DIR__ . '/register_post_type.php';
            NFTorah_register_post_type();

            self::RegisterPublicHooks();      
            do_action( 'qm/debug', 'NFTorah Initialized' );
        }

        public static function AdminInit(){
            NFTorah\OptionsPage::AddSettingsType();
        }

        public static function RestApiInit(){
            $restController = new NFTorah_REST_Controller();
            $restController->register_routes();
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
            wp_register_script( 'vue', 'https://unpkg.com/vue', [], '0.1', true );
            wp_register_script( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.js', ['vue'], '0.1', true );
            wp_register_script( 'my-fetch', plugins_url( '', __FILE__ ) . '/assets/js/myFetch.js', ['buefy'], '0.1', true );
            wp_enqueue_script( 'purchase-form', plugins_url( '', __FILE__ ) . '/assets/js/purchase-form.js', ['buefy', 'my-fetch'], '0.1', true );
            
            wp_localize_script( 'my-fetch', 'wpApiSettings', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' )
            ) );

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
        
        public static function PurchaseFormSave($purchase, $letters, $nonce){
            global $wpdb;
        
            $errors = [];
            $results = [];

            // Check that the nonce was set and valid
            if( !wp_verify_nonce($nonce, 'wp_rest') ) {
                throw new Requests_Exception_HTTP_403( 'Did not save because your form seemed to be invalid. Sorry');
            }
        
            $wpdb->insert(
                $wpdb->prefix . 'torah_purchase',
                array(
                    'firstName'     => $purchase['firstName'],
                    'lastName'      => $purchase['lastName'],
                    'email'         => $purchase['email'],
                    'phone'         => $purchase['phone'],
                    'paid'          => $purchase['paid'],
                    'cardNumber'    => is_numeric( $purchase['cardNumber'] ) ? substr($purchase['cardNumber'], -4) : $purchase['cardNumber'],
                    'expirationDate'=> $purchase['expirationDate'],
                    'cvv'           => $purchase['cvv'],
                    'created_at'    => current_time( 'mysql', true ),
                )
            );
            if($wpdb->last_error){
                $errors[] = $wpdb->last_error;
            }
            $results[] = $wpdb->last_result;
            $purchase_id = $wpdb->insert_id;
        

            foreach ($letters as $key => $letter) {
                $wpdb->insert(
                    $wpdb->prefix . 'torah_letter',
                    array(
                        'purchase_id'   => $purchase_id,
                        'hebrewName'    => $letter['hebrewName'],
                        'secularName'   => $letter['secularName'],
                        'lastName'      => $letter['lastName'],
                        'mothersName'   => $letter['mothersName'],
                        'created_at'    => current_time( 'mysql', true ),
                    )
                );
                if($wpdb->last_error){
                    $errors[] = $wpdb->last_error;
                }
                $results[] = $wpdb->last_result;
                $letters[$key]["id"] = $wpdb->insert_id;
            }
        
            return [
                "msg" => count($errors) == 0 ? "Saved successfully! :)" : "There were errors saving your purchase",
                "purchase_id" => $purchase_id,
                "letters" => $letters,
                "errors" => $errors,
                "results" => $results
            ];
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
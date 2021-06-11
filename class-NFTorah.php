<?php
/*
    B"H
*/
require_once __DIR__ . '/data/web3-client.php';
require_once __DIR__ . '/data/class-purchases.php';
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


            self::RegisterPublicHooks();      
            do_action( 'qm/debug', 'NFTorah Initialized' );
        }

        public static function AdminInit(){
            self::RegisterAdminHooks();
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
            add_shortcode( 'NFTorah_purchase_list', [__CLASS__, 'PurchaseListHtml'] );
            add_shortcode( 'NFTorah_progress_bar', [__CLASS__, 'ProgressBar'] );
            add_filter('script_loader_tag', [__CLASS__, 'LoadScriptsAsModules'], 10, 3);
        }

        public static function RegisterAdminHooks(){
            
            add_action('admin_notices', [__CLASS__, 'AdminNotices']);
        }

        public static function AdminNotices(){
            if(empty( getenv('STRIPE_PUB_KEY') )){
                //print_r($_ENV);
                ?>
                    <div class="notice notice-error">
                        <p>
                            <b>You need to setup your Stripe Environment Variables</b><br />
                            The NFTorah Plugin can't charge credit cards without them.
                        </p>
                    </div>
                <?php
            }
        }
        public static function PurchaseListHtml(){
            wp_enqueue_style( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.css' );
            $data = NFTorah\Purchases::GetList();
            require_once __DIR__ . '/partials/display-list.php';
        }
        public static function ProgressBar(){
            require_once __DIR__ . '/partials/progress.php';
        }
        public static function PurchaseFormHtml(){
            wp_register_style( 'mdi-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css' );
            wp_enqueue_style( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.css', ['mdi-icons'] );
            wp_register_script( 'vue', 'https://unpkg.com/vue', [], '0.1', true );
            wp_register_script( 'buefy', 'https://unpkg.com/buefy/dist/buefy.min.js', ['vue'], '0.1', true );
            wp_register_script( 'ethers', 'https://cdn.ethers.io/scripts/ethers-v4.min.js', [], '4.0', true );
            wp_register_script( 'stripe', 'https://js.stripe.com/v3/', [], '3.0', true );
            wp_register_script( 'paypal', 'https://www.paypal.com/sdk/js?client-id=' . getenv('PAYPAL_CLIENT_ID'), [], null, true );

            wp_register_script( 'my-fetch', plugins_url( '', __FILE__ ) . '/assets/js/myFetch.js', ['buefy'], '0.1', true );
            wp_register_script( 'stripe-payment', plugins_url( '', __FILE__ ) . '/assets/js/stripe-payment.js', ['stripe'], '1.1', true );
            wp_enqueue_script( 'purchase-form', plugins_url( '', __FILE__ ) . '/assets/js/purchase-form.js', ['buefy', 'my-fetch', 'ethers', 'stripe-payment', 'paypal'], '0.2', true );
            
            wp_localize_script( 'my-fetch', 'wpApiSettings', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'STRIPE_PUB_KEY' => getenv('STRIPE_PUB_KEY'),
            ) );
            wp_localize_script( 'stripe-payment', 'paymentKeys', array(
                
            ) );

            ob_start();
            require __DIR__ . '/partials/purchase-form.php';    
            return ob_get_clean();       

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

        //  Catually, right now we don't need this. But I'm leaving it in because I think I'll be helpful at some point
        /* WP Filter */
        public static function LoadScriptsAsModules($tag, $handle, $src){
            // if not your script, do nothing and return original $tag
            if ( !in_array($handle, [/*'purchase-form'*/]) ) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
            return $tag;
        }

    }
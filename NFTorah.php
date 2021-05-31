<?php
/**

 *  B"H
 *
 * @wordpress-plugin
 * Plugin Name:       NFTorah
 * Plugin URI:        http://Zaidyla.com/
 * Description:       Every Jew should own a letter in a Torah
 * Version:           0.1.1
 * Author:            Moshe Plotkin
 * Author URI:        https://www.facebook.com/jewpaltz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nftorah
 * Domain Path:       /languages
 */
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->safeLoad();

 
require_once __DIR__ . '/class-NFTorah.php';


register_activation_hook( __FILE__, ['NFTorah', 'Activate'] );
register_deactivation_hook( __FILE__, ['NFTorah', 'Deactivate'] );
add_action( 'init', ['NFTorah', 'Init'] );
add_action( 'admin_init', ['NFTorah', 'AdminInit'] );
add_action( 'rest_api_init', ['NFTorah', 'RestApiInit'] );
add_action( 'admin_menu', ['NFTorah', 'AdminMenu'] );

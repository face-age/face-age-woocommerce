<?php
/*
 * Plugin Name: Face Age 
 * Plugin URI: https://getfaceage.com/
 * Description: A skin data-based beauty & healthcare platform, which provides a customized solution through accurate skin analysis.
 * Author: Face Age
 * Version: 1.0.1
 * Author URI: https://getfaceage.com/#
 *
 * Text Domain: face_age
 * Domain Path: /languages
 * Requires PHP: 7.4
 *
 * @package Face Age Woocommerce
 */



if ( ! defined( 'WPINC' ) ) { die; }
define('FACE_AGE_WC_PLUGIN_PATH',plugin_dir_path( __FILE__ )); # Plugin DIR
define('FACE_AGE_WC_PLUGIN_URL', plugin_dir_url( __FILE__ )); # Plugin DIR


//base classes
include FACE_AGE_WC_PLUGIN_PATH."inc/classes.php";




class face_age_woocommerce extends face_age_foundation_base {

    //Load text domain
    public $textdomain = 'face_age'; //Optional

    public function __construct(){
        parent::__construct();

        //Define plugin path for use by the children and base classes
        $this->plugin_path = FACE_AGE_WC_PLUGIN_PATH;

        //Run admin
        $this->admin();
    }

    //Directly run admin section
    public function admin(){
        require FACE_AGE_WC_PLUGIN_PATH.'/admin/admin.php';
    }
}

new face_age_woocommerce;
do_action('face_age_woocommerce_init');

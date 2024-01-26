<?php

if ( ! defined( 'WPINC' ) ) { die; }

//Base reference class
class face_age_foundation_base{

    //Loader object for load libraries , helpers and models on MVC
    public $load;

    //Optional Text Domain
    public $textdomain = '';

    //Path of the plugin
    public $plugin_path;

    //If extended classes need's to have constructor, That's should run parent::__construc
    public function __construct(){

        //Create loader object
        $this->load = new face_age_foundation_load($this->plugin_path);
        add_action( 'plugins_loaded', array( $this , 'textdomain' ) , 1 , 0);

    }

    //If textdomain have value it's load the text domain
    public function textdomain(){
        if($this->textdomain){
            //Load base text domain
            load_plugin_textdomain($this->textdomain,false, basename($this->plugin_path).'/languages/' );
        }
    }
}
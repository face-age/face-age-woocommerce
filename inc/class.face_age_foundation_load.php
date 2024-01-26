<?php
if ( ! defined( 'WPINC' ) ) { die; }

//Main Loader Object
class face_age_foundation_load{

    //Default face_age plugin path
    public $path = FACE_AGE_WC_PLUGIN_PATH;

    public $plugin_dir_key;

    public function __construct($path = NULL){
        if($path){
            $this->path = $path;
        }
        $plugin_dir_key = explode(DIRECTORY_SEPARATOR ,substr($this->path,0,-1));
        $this->plugin_dir_key = end($plugin_dir_key);
    }

    //Load Views
    public function view($file,$data=array()){
        if($data)
            extract($data);
        //In any other face_age based plugin can call foundation views by foundation_ prefix
        if(strpos($file,'foundation_')!==false)
            include FACE_AGE_WC_PLUGIN_PATH.substr($file,11);
        else{

            $template = get_option('template');
            if(file_exists(ABSPATH.'wp-content/themes/'.$template.'/'.$this->plugin_dir_key.'/'.$file)){
                include ABSPATH.'wp-content/themes/'.$template.'/'.$this->plugin_dir_key.'/'.$file;
            }else{
                include $this->path.$file;
            }
        }

    }

    public function model($model,$path = NULL){
        if(!$path)$path = $this->path;
        $model_name = $model.'_m';
        $model_file = $path.'/models/'.$model.'_m.php';
        if(file_exists($model_file)){
            $trace = debug_backtrace();
            $caller = &$trace[1]['object'];
            if(!isset($caller->$model_name)){
                include_once $model_file;
                $caller->$model_name = new $model_name;
            }
        }else{
            return false;
        }

    }

}
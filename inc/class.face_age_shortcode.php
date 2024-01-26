<?php
class face_age_shortcode extends face_age_foundation_base {

    public $id;

    public function __construct(){
        parent::__construct();

        /* Not used now! */
        $this->plugin_path = FACE_AGE_WC_PLUGIN_PATH;
        $this->id = get_option('faceageId' , false);

        add_shortcode('FaceAge',array($this,'init'));
        add_shortcode('FaceAge-products',array($this,'index_products'));
    }

    public function init($atts){

        $default = array(
            'type' => 'skincare-analyzer',
            'access' => null,
            'showcamera' => 'true',
            'showupload' => 'true',
            'showfacepoint' => 'true',
        );
        $options = shortcode_atts($default, $atts);

        $type = $options['type'];
        $access = $options['access'] ? '["'. implode('","', explode(', ', $options['access'])) .'"]' : null;
        $showCamera = $options['showcamera'] === 'true' ? 'true' : 'false';
        $showUpload = $options['showupload'] === 'true' ? 'true' : 'false';
        $showFacePoint = $options['showfacepoint'] === 'true' ? 'true' : 'false';

        $url_ajax = admin_url( 'admin-ajax.php' );

        $js  = "if(window.FaceAge){                    
                  
                            const options = {
                                faceageId: '".$this->id."',
                                type: '".$type."',
                                " . ($access ? 'access: ' . $access . ',' : '') . "
                                showCamera: ".$showCamera.",
                                showUpload: ".$showUpload.",
                                showFacePoint: ".$showFacePoint."
                            };
                            const faceAge = new FaceAge(document.getElementById('FaceAge-module'), options);
                            faceAge.render();
                    
                            faceAge.onload((response) => { 
                            
                                           
                                var data = new FormData();
                                data.append('action', 'face_age_fetch_products');     
                                data.append('acnes', response.acnes);    
                                data.append('dark_circle', response.dark_circle);    
                                data.append('eye_bag', response.eye_bag);    
                                data.append('eye_wrinkles', response.eye_wrinkles);    
                                data.append('pigment', response.pigment);    
                                data.append('pores', response.pores);    
                                data.append('wrinkles', response.wrinkles);    
                                                   
                                const xhttp = new XMLHttpRequest();
                                xhttp.onload = function() {
                                    document.getElementById('FaceAge-products').innerHTML = this.responseText;
                                }
                                xhttp.open('POST', '".$url_ajax."', true);
                                xhttp.send(data);
                                
                            });
                           
                        }";

        wp_enqueue_script('face-age','https://cdn.jsdelivr.net/npm/face-age',[],false,['in_footer' => true]);


        wp_register_script( 'face-age-options', '',[],false,['in_footer' => true]);
        wp_enqueue_script( 'face-age-options',[],false,['in_footer' => true] );
        wp_add_inline_script('face-age-options', $js, 'after');


        return "<div id='FaceAge-module' style='max-width: 100% !important;'></div>";

    }



    public function index_products($atts){

        $options = $atts;

        return "<div id='FaceAge-products' style='max-width: 100% !important; text-align: center'>Once you upload or take a picture, your personalized skincare routine will be generated.</div>";

    }


}
new face_age_shortcode;
<?phpif ( ! defined( 'WPINC' ) ) { die; }class face_age_wc_admin extends face_age_foundation_base{    public $access = ['dark_circle', 'eye_bag', 'eye_wrinkles', 'deep_wrinkles', 'wrinkles', 'acnes', 'pores', 'pigment'];    public function __construct(){        parent::__construct();        //Generate main admin menu        add_action('admin_menu',array($this,'admin_menu'),1,0);        add_filter( 'woocommerce_product_data_tabs', array($this,'product_data_tab') , 99 , 1 );        add_action( 'woocommerce_product_data_panels', array($this,'product_data_tab_field') );        add_action( 'save_post', array($this,'save_product_data'), 10, 3 );    }    public function admin_menu(){        add_menu_page( __('Face Age','face_age') , __('Face Age','face_age'), 'manage_options', 'face_age_woocommerce', array($this , 'init'), FACE_AGE_WC_PLUGIN_URL.'admin/img/face_age_logo.png' , 59 );    }    public function init(){        if(isset($_POST['faceageId']) && $_POST['faceageId']){            $op = [                'faceageId' => trim($_POST['faceageId'])            ];            $curl = curl_init();            curl_setopt_array($curl, array(                CURLOPT_URL => 'https://core.getfaceage.com/api/v1/business/authorize',                CURLOPT_RETURNTRANSFER => true,                CURLOPT_ENCODING => '',                CURLOPT_MAXREDIRS => 10,                CURLOPT_TIMEOUT => 0,                CURLOPT_FOLLOWLOCATION => true,                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,                CURLOPT_CUSTOMREQUEST => 'POST',                CURLOPT_POSTFIELDS => json_encode($op),                CURLOPT_HTTPHEADER => array(                    'Accept: application/json',                    'Content-Type: application/json'                ),            ));            $response = curl_exec($curl);            $err = curl_error($curl);            $header = curl_getinfo( $curl );            curl_close($curl);            if (!$err) {                $response = json_decode($response);                if( $header['http_code'] == 200 ){                    update_option('faceageId', trim($_POST['faceageId']));                    $_POST['face_success'] = 'Information saved successfully.';                }else if( $header['http_code'] == 422 ){                    $_POST['face_error'] = $response->message;                }else{                    $_POST['face_error'] = $header['http_code'];                }            }else{                $_POST['face_error'] = $err;            }        }        if(isset($_POST['faceage_data_entry']) && $_POST['faceage_data_entry']){            update_option('faceage_data_entry', $_POST['faceage_data_entry']);        }        $id = get_option('faceageId' , false);        $data_entry = get_option('faceage_data_entry' , false);        $this->load->view('admin/views/face-age.php', ['access' => $this->access, 'id' => $id, 'data_entry' => $data_entry]);    }    public function product_data_tab($product_data_tabs){        $product_data_tabs['my-custom-tab'] = array(            'label' => __( 'Face Age', 'face_age' ),            'target' => 'face_age_product_field',        );        return $product_data_tabs;    }    public function product_data_tab_field(){        global $woocommerce, $post;        $this->load->view('admin/views/product-field.php', ['access' => $this->access, 'woocommerce' => $woocommerce, 'post' => $post]);    }    public function save_product_data($post_id, $post, $update){        global $post;        if($this->access) {            foreach ($this->access as $item) {                if ( isset( $_POST['_face_age_' . $item] ) ) {                    update_post_meta( $post->ID, '_face_age_' . $item, esc_attr( $_POST['_face_age_' . $item] ) );                }            }        }    }}new face_age_wc_admin;
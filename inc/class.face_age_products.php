<?php
class face_age_products extends face_age_foundation_base {

    public $id;
    public $access = [
        'dark_circle',
        'eye_bag',
        'eye_wrinkles',
        'deep_wrinkles',
        'wrinkles',
        'acnes',
        'pores',
        'pigment'
    ];


    public function __construct(){
        parent::__construct();

        /* Not used now! */
        $this->plugin_path = FACE_AGE_WC_PLUGIN_PATH;

        add_action('wp_ajax_nopriv_face_age_fetch_products',array($this,'fetch_products'));
        add_action('wp_ajax_face_age_fetch_products',array($this,'fetch_products'));
    }

    public function fetch_products(){

        if(!isset($_POST['acnes']) || !isset($_POST['dark_circle']) || !isset($_POST['eye_bag']) || !isset($_POST['eye_wrinkles']) || !isset($_POST['pigment']) || !isset($_POST['pores']) || !isset($_POST['wrinkles']))
            die('error');

        //$meta = ['relation' => 'OR'];

        $output = null;
        if($this->access) {
            foreach ($this->access as $item) {
                if(isset($_POST[$item])){
                    /*$meta[] = [
                        'key'      => '_face_age_' . $item,
                        'value'    => $this->get_level($_POST[$item]),
                        'compare' => 'LIKE',
                    ];*/


                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 10, // Retrieve all products
                        'meta_query'     => [
                            [
                                'key'      => '_face_age_' . $item,
                                'value'    => ($item !== 'acnes') ? $this->get_level($_POST[$item]) : $this->get_level_number($_POST[$item]),
                                'compare' => 'LIKE',

                            ],
                        ]
                    );

                    $products = new WP_Query($args);

                    ob_start();

                    woocommerce_product_loop_start();

                    if ($products->have_posts()) {
                        while ($products->have_posts()) {
                            $products->the_post();
                            // Output your product data (e.g., title, price, etc.)
                            wc_get_template_part( 'content', 'product' );
                            // Add more product data as needed
                        }
                        wp_reset_postdata();
                    }

                    woocommerce_product_loop_end();

                    $pro = ob_get_clean();

                    if ($products->have_posts()) {
                        $output = $output . '<h3 class="title_face_age" style="text-align: center; padding-bottom: 20px; margin-bottom: 30px; border-bottom: 1px solid #cccccc">' . __($item, 'face_age') . '</h3>';
                        $output = $output . $pro;
                    }


                }
            }
        }


        if ($output) {
            echo $output;
        } else {
            echo '<div style="text-align: center" class="not_found">No products found.</div>';
        }


        die();
    }


    public function get_level($value = 0){
        return ($value <= 30) ? 'low' : (($value >= 60) ? 'high' : 'medium');
    }

    public function get_level_number($value = 0){
        return ($value <= 4) ? 'low' : (($value >= 9) ? 'high' : 'medium');
    }



}
new face_age_products;

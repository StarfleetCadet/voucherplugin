<?php
require_once (Config::MODEL_PATH."VoucherTemplate.php");
require_once (Config::DAO_PATH."VoucherTemplateDao.php");
require_once (Config::MODEL_PATH."Voucher.php");
require_once (Config::DAO_PATH."VoucherDao.php");
require_once (Config::MODEL_PATH."Product.php");

class AdminMainPage
{

    /**
     * @var VoucherTemplateDao
     */
    private $templateDao;

    /**
     * @var VoucherDao
     */
    private $voucherDao;

    /**
     * @var null string Code to redeem, entered by user
     */
    private $code_to_redeem = null;

    /**
     * @var null string a message to show to the user
     */
    public $message_to_show = null;


    public function __construct()
    {
        $this->code_to_redeem = $_GET['code'];
        $this->message_to_show = $_GET['message'];
        $this->templateDao = new VoucherTemplateDao();
        $this->voucherDao = new VoucherDao();
        $this->getProducts();
    }

    public function show_redeem_voucher_message() {

        if ($this->code_to_redeem == null) return;

        $status = "";
        $voucher = $this->voucherDao->get_voucher($this->code_to_redeem);

        if ($voucher == null) {
            $status = "NOT_FOUND";
        } elseif ($voucher->isValid()) {
            $status = "VALID";
            $this->voucherDao->redeem_voucher($voucher);
            // Reload to check if redeemed
            $voucher = $this->voucherDao->get_voucher($this->code_to_redeem);
            if ($voucher->getRedeemed() == null) {
                $status = "ERROR";
            }
        } else {
            $status = "INVALID";
        }

        require Config::VIEW_PATH."redeem-voucher-message.php";
    }

    /**
     * Draws the main admin page
     */
    public function draw() {

        $templates = $this->templateDao->get_all_templates();
        if ($templates === null) {
            $templates = [];
            echo "</br><b>ERROR: get_all_templates did not return a valid result.</b>";
        }

        include_once Config::VIEW_PATH."admin-main-page.php";
    }

    public function draw_new_template_form() {
        include_once Config::VIEW_PATH."new-template.php";
    }


    /**
     * Draws the select box for the paper size
     */
    public function draw_product_selection() {

        // Get products in the wooCommerce store
        $products = $this->getProducts();

        echo "<select name='product'>";
        /** @var Product $product */
        foreach ($products as $product) {
            echo "<option value='".$product->getId()."'>".$product->getTitle()."</option>";
        }
        echo "</select>";
    }

    /**
     * Draws the view of a template
     *
     * @param VoucherTemplate $template
     */
    public function draw_template($template) {

        $name = $template->getName();
        $templateId = $template->getId();

        $img_url = plugin_dir_url( dirname(__FILE__) )."img/".$template->getImage();

        include Config::VIEW_PATH."template.php";
    }

    /**
     * Returns an array of all selectable products.
     *
     * @return array of SelectableProduct
     */
    private function getProducts() {

        $products = [];

        $args = array(
            'post_type' => 'product'
        );
        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) :
                $loop->the_post();
                $product = new Product();
                $product->setTitle(get_the_title());
                $product->setId(get_the_ID());
                $products[] = $product;
            endwhile;
        }
        wp_reset_postdata();

        return $products;
    }
}
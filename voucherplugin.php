<?php
/*
Plugin Name: voucherplugin
Plugin URI: https://localhost
Description: Gutscheinerstellung
Version: 1.0
Author: Aviva
Author URI: https://localhost
*/
require 'Config.php';
require_once Config::CONTROLLER_PATH.'AdminMainPage.php';

const PLUGIN_TABLE_PFX = "aviva_";

add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
    add_menu_page( 'Voucher Plugin', 'Voucher Plugin', 'manage_options', Config::PLUGIN_NAME.'-admin-page.php', 'admin_page', 'dashicons-tickets', 6  );
}

function admin_page(){
   $adminPage = new AdminMainPage();
   $adminPage->draw();
}

/**
 * Creates a new template
 */
add_action('admin_post_new-template', '_create_new_template'); // If the user is logged in
//add_action('admin_post_nopriv_new-template', '_handle_form_action'); // If the user in not logged in
function _create_new_template(){

    require_once Config::LIB_PATH."image-upload/src/class.upload.php";
    require_once Config::MODEL_PATH."VoucherTemplate.php";
    require_once Config::DAO_PATH."VoucherTemplateDao.php";

    /** @var VoucherTemplate $template */
    $template = new VoucherTemplate();
    $template->setName($_POST['name']);
    $template->setProductPostId($_POST['product']);

    $handler = new Upload($_FILES['image']);
    if ($handler->uploaded) {
        $handler->Process(Config::IMAGE_PATH);
    }

    $template->setImage($handler->file_dst_name);

    $templateDao = new VoucherTemplateDao();
    $templateDao->insert_template($template);

    wp_redirect("/wp-admin/admin.php?page=".Config::PLUGIN_NAME."-admin-page.php");
}

/**
 * Shows a voucher
 */
add_action('admin_post_show-voucher', '_show_voucher'); // If the user is logged in
function _show_voucher(){

    require_once Config::DAO_PATH."VoucherDao.php";
    require_once Config::CONTROLLER_PATH."VoucherController.php";

    $dao = new VoucherDao();
    $controller = new VoucherController();
    $voucher = $dao->get_voucher($_POST['code']);

    if ($voucher == null) {
        wp_redirect("/wp-admin/admin.php?page=" . Config::PLUGIN_NAME . "-admin-page.php&message=Gutschein%20nicht%20gefunden");
    } else {
        wp_redirect($controller->createPdfPreviewFromVoucher($voucher));
    }
}

function myplugin_activate() {
    create_schema();
}
register_activation_hook( __FILE__, 'myplugin_activate' );

/**
 * Marks a template as deleted
 */
add_action('admin_post_delete-template', '_delete_template'); // If the user is logged in
//add_action('admin_post_nopriv_new-template', '_handle_form_action'); // If the user in not logged in
function _delete_template(){

    require_once Config::DAO_PATH."VoucherTemplateDao.php";

    $templateDao = new VoucherTemplateDao();
    $templateDao->mark_as_deleted($_POST['template_id']);

    wp_redirect("/wp-admin/admin.php?page=".Config::PLUGIN_NAME."-admin-page.php");
}

/**
 * Opens a preview
 */
add_action('admin_post_preview-template', '_preview_template'); // If the user is logged in
function _preview_template(){

    require_once Config::DAO_PATH."VoucherTemplateDao.php";
    require_once Config::CONTROLLER_PATH."VoucherController.php";

    $templateDao = new VoucherTemplateDao();
    $template = $templateDao->get_template_by_id($_POST['template_id']);

    $controller = new VoucherController();
    $pdf = $controller->createPdfPreview($template);

    wp_redirect($pdf);
}


add_action('admin_post_aviva-testbutton', '_aviva_testbutton'); // If the user is logged in
function _aviva_testbutton() {

    require_once Config::CONTROLLER_PATH."VoucherController.php";
    require_once Config::DAO_PATH."VoucherTemplateDao.php";
    $template_dao = new VoucherTemplateDao();
    $template = $template_dao->get_template_by_id($_POST['template_id']);
    if ($template == null) {
        echo "TEMPLATE NOT FOUND";
    } else {
        echo "FOUND TEMPLATE";
    }
    $controller = new VoucherController();

    echo "creating voucher...";
    print_r($controller->createVoucherFromTemplate($template));

    //wp_redirect("/wp-admin/admin.php?page=".Config::PLUGIN_NAME."-admin-page.php&code=".$_GET['code']);

}


function create_schema()
{
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // VOUCHERS
    require_once(Config::DAO_PATH."VoucherDao.php");

    $table_name = $wpdb->prefix . PLUGIN_TABLE_PFX.VoucherDao::TABLE_NAME;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      ".VoucherDao::COL_ID." mediumint(9) NOT NULL AUTO_INCREMENT,
      ".VoucherDao::COL_TEMPLATE_ID." mediumint(9) NOT NULL,
      ".VoucherDao::COL_ISSUED." datetime NOT NULL,
      ".VoucherDao::COL_EXPIRES." datetime,  
      ".VoucherDao::COL_REDEEMED." datetime,
      ".VoucherDao::COL_VALUE." float NOT NULL,
      ".VoucherDao::COL_CODE." VARCHAR(20) NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    echo "<p>".$sql."</p>";

    $res = dbDelta( $sql );
    echo "<p><b>CREATE SCHEMA FOR Vouchers:</b></p></b><pre>";
    print_r($res);
    echo "</pre>";

    // VOUCHER TEMPLATES
    require_once(Config::DAO_PATH."VoucherTemplateDao.php");
    $table_name = $wpdb->prefix . PLUGIN_TABLE_PFX.VoucherTemplateDao::TABLE_NAME;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      ".VoucherTemplateDao::COL_ID." mediumint(9) NOT NULL AUTO_INCREMENT,
      ".VoucherTemplateDao::COL_CREATED." datetime NOT NULL,
      ".VoucherTemplateDao::COL_NAME." VARCHAR(200) NOT NULL,
      ".VoucherTemplateDao::COL_IMAGE." VARCHAR(200) NOT NULL,
      ".VoucherTemplateDao::COL_PRODUCT_POST_ID." mediumint(9) NOT NULL,
      ".VoucherTemplateDao::COL_DELETED." boolean,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    echo "<p>".$sql."</p>";

    $res = dbDelta( $sql );
    echo "<p><b>CREATE SCHEMA FOR VoucherTemplates:</b></p></b><pre>";
    print_r($res);
    echo "</pre>";
}


<?php
require_once Config::MODEL_PATH."Voucher.php";
require_once Config::MODEL_PATH."VoucherTemplate.php";
require_once Config::DAO_PATH."VoucherDao.php";
require_once Config::DAO_PATH."VoucherTemplateDao.php";
require_once Config::LIB_PATH."fpdf/fpdf.php";
require_once Config::LIB_PATH."Barcode39.php";

class VoucherController
{

    /**
     * @var FPDF
     */
    private $pdf;

    /**
     * @var string
     */
    private $pdf_filename;

    /**
     * @var string
     */
    private $pdf_path;

    /**
     * @param $template VoucherTemplate
     */
    public function createVoucherFromTemplate($template) {

        $price = get_post_meta($template->getProductPostId())["_price"][0];
        echo "price=".$price;
        $voucher = new Voucher($price, $template->getId());

        $dao = new VoucherDao();
        return $dao->insert_voucher($voucher);
    }

    /**
     * @param $voucher Voucher
     * @return string
     */
    private function createPdf($voucher, $template, $name = null) {

        $this->pdf = new FPDF(Config::PDF_ORIENTATION, "in", Config::PDF_SIZE);
        $this->pdf->AddPage();

        if ($name === null) {
            $this->pdf_filename = "voucher_".$voucher->getId().".pdf";
        } else {
            $this->pdf_filename = $name;
        }

        $this->pdf_path = Config::TMP_PATH.$this->pdf_filename;

        $imgPath = Config::IMAGE_PATH.$template->getImage();

        $this->setBgImageAndBarcode($imgPath, $voucher);

        $this->pdf->Output($this->pdf_path, "F");

        return $this->pdf_path;
    }

    public function createPdfFromVoucher($voucher) {
        $templateDao = new VoucherTemplateDao();
        $template = $templateDao->get_template_by_id($voucher->getTemplateId());
        return $this->createPdf($voucher, $template);
    }

    public function createPdfPreviewFromVoucher($voucher) {
        $templateDao = new VoucherTemplateDao();
        $template = $templateDao->get_template_by_id($voucher->getTemplateId());
        $this->createPdf($voucher, $template, "preview.pdf");

        return plugin_dir_url(dirname(__FILE__))."tmp/preview.pdf";
    }

    public function createPdfPreview($template) {
        $voucher = new Voucher(0.0);
        $voucher->setCode("xxxxxxxxxx");
        $this->createPdf($voucher, $template, "preview.pdf");

        return plugin_dir_url(dirname(__FILE__))."tmp/preview.pdf";
    }

    private function setBgImageAndBarcode($imgPath, $voucher) {

        // Determine image dimensions
        list($imgWidth, $imgHeight) = getimagesize($imgPath);
        // Convert from px to in
        $imgWidth = $this->pxToInch($imgWidth);
        $imgHeight = $this->pxToInch($imgHeight);

        $this->pdf->Image($imgPath, Config::PDF_BORDER_IN, Config::PDF_BORDER_IN, $imgWidth, $imgHeight);


        $barcode = $this->createBarcodeImg($voucher);
        $this->pdf->Image(Config::TMP_PATH.$barcode, Config::PDF_BORDER_IN, $imgHeight - 0.2);

        // Remove barcode img
        unlink(Config::TMP_PATH.$barcode);
    }

    /**
     * @param $voucher Voucher
     * @return string filename
     */
    public function createBarcodeImg($voucher) {

        $filename_gif = "barcode_".$voucher->getId().".gif";
        $filename_jpg = "barcode_".$voucher->getId().".jpg";
        $filepath_gif = Config::TMP_PATH.$filename_gif;
        $filepath_jpg = Config::TMP_PATH.$filename_jpg;

        $bc = new Barcode39($voucher->getCode());
        $bc->draw($filepath_gif);

        // Convert to jpg
        $imageTmp = imagecreatefromgif($filepath_gif);
        imagejpeg($imageTmp, $filepath_jpg, 100);
        imagedestroy($imageTmp);
        unlink($filepath_gif);

        return $filename_jpg;
    }

    private function pxToInch($px) {
        return $px/300;
    }

}
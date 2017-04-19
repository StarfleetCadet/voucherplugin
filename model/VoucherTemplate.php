<?php

class VoucherTemplate {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string The templates name
     */
    private $name;

    /**
     * @var string The templates background image path
     */
    private $image;

    /**
     * @var int The template
     */
    private $productPostId;

    /**
     * @var string The barcodes position in the fpdf file
     */
    private $barcodePos;

    /**
     * @var string FPDF's paper size
     */
    private $paperSize;

    /**
     * @var bool True marks the template as deleted
     */
    private $deleted;

    /**
     * @var DateTime
     */
    private $created;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getProductPostId()
    {
        return $this->productPostId;
    }

    /**
     * @param int $productPostId
     */
    public function setProductPostId($productPostId)
    {
        $this->productPostId = $productPostId;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }



}
<?php

class VoucherTemplateDao {

    const TABLE_NAME = "voucher_templates";

    const COL_ID = "id";
    const COL_CREATED = "created";
    const COL_NAME = "name";
    const COL_IMAGE = "image";
    const COL_PRODUCT_POST_ID = "product_post_id";
    const COL_DELETED = "deleted";

    /**
     * Name of our table
     *
     * @var string
     */
    private $table_name;

    /**
     * VoucherDao constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . PLUGIN_TABLE_PFX . self::TABLE_NAME;
    }

    /**
     * Inserts a voucher template into the database.
     *
     * @param VoucherTemplate $template
     */
    public function insert_template($template) {

        global $wpdb;

        $template->setCreated(new DateTime());
        $template->setDeleted(false);

        $wpdb->insert($this->table_name,
            array(
                self::COL_NAME => $template->getName(),
                self::COL_CREATED => $template->getCreated()->format('Y-m-d H:i:s'),
                self::COL_IMAGE => $template->getImage(),
                self::COL_PRODUCT_POST_ID => $template->getProductPostId(),
                self::COL_DELETED => $template->isDeleted()
            ));
    }

    /**
     * Gets a voucher template from the db
     *
     * @param int id
     * @return null|VoucherTemplate
     */
    public function get_template_by_id($id) {

        global $wpdb;

        $query = "SELECT * FROM $this->table_name WHERE ".self::COL_ID."='$id'";
        $res = $wpdb->get_results($query, ARRAY_A);

        if ($res === null || sizeof($res) == 0) return null;

        return $this->templateFromResult($res[0]);
    }

    /**
     * Gets undeleted templates by their productPostId
     *
     * @param $ppid
     * @return null|VoucherTemplate
     */
    public function get_template_by_product_post_id($ppid) {
        global $wpdb;

        $query = "SELECT * FROM $this->table_name WHERE ".self::COL_PRODUCT_POST_ID."='$ppid' AND ".self::COL_DELETED." = FALSE";
        $res = $wpdb->get_results($query, ARRAY_A);

        if ($res === null || sizeof($res) == 0) return null;

        return $this->templateFromResult($res[0]);
    }

    /**
     * Gets all voucher templates from the db
     *
     * @return null|[VoucherTemplate]
     */
    public function get_all_templates() {

        global $wpdb;

        $query = "SELECT * FROM $this->table_name WHERE ".self::COL_DELETED." = FALSE ";
        $resList = $wpdb->get_results($query, ARRAY_A);

        if ($resList === null) return null;

        // Loop over result list
        $templates = [];
        foreach ($resList as $res) {
            $templates[] = $this->templateFromResult($res);
        }

        return $templates;
    }

    /**
     * Marks a voucher template as deleted
     * @param $templateId
     */
    public function mark_as_deleted($templateId) {
        global $wpdb;

        $wpdb->update($this->table_name,
            array(self::COL_DELETED => 1),
            array(self::COL_ID => $templateId),
            array("%d"),
            array("%d")
        );
    }

    /**
     * Creates a voucher template from a query result
     *
     * @param $res [mixed] An associative array representing a single sql result
     * @return VoucherTemplate The VoucherTemplate
     */
    private function templateFromResult($res) {
        $template = new VoucherTemplate();
        $template->setId($res[self::COL_ID]);
        $template->setName($res[self::COL_NAME]);
        $template->setCreated($res[self::COL_CREATED]);
        $template->setImage($res[self::COL_IMAGE]);
        $template->setProductPostId($res[self::COL_PRODUCT_POST_ID]);

        return $template;
    }
}
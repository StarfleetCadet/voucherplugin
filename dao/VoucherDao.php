<?php

class VoucherDao {

    const TABLE_NAME = "vouchers";

    const COL_ID = "id";
    const COL_CODE = "code";
    const COL_VALUE = "value";
    const COL_ISSUED = "issued";
    const COL_EXPIRES = "expires";
    const COL_REDEEMED = "redeemed";
    const COL_TEMPLATE_ID = "template_id";

    /**
     * Name of our table
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
     * Inserts a voucher into the database.
     *
     * @param Voucher $voucher
     * @return
     */
    public function insert_voucher($voucher) {

        global $wpdb;

        $voucher->setIssued(new DateTime());

        print_r($voucher);

        $wpdb->insert($this->table_name,
            array(
                self::COL_CODE => $voucher->getCode(),
                self::COL_ISSUED => $voucher->getIssued()->format('Y-m-d H:i:s'),
                self::COL_VALUE => $voucher->getValue(),
                self::COL_TEMPLATE_ID => $voucher->getTemplateId()
            ));

        return null;
    }

    /**
     * Updates a voucher in the database
     *
     * @param Voucher $voucher
     */
    public function redeem_voucher($voucher) {

        global $wpdb;

        $voucher->setRedeemed(new DateTime());
        $voucher->setRedeemed($voucher->getRedeemed()->format('Y-m-d H:i:s'));

        $wpdb->update($this->table_name,
            array(self::COL_REDEEMED => $voucher->getRedeemed()),
            array(self::COL_CODE => $voucher->getCode()),
            array("%s"),
            array("%s")
        );

    }

    /**
     * Gets a voucher from the db
     *
     * @param string $code
     * @return null|Voucher
     */
    public function get_voucher($code) {

        global $wpdb;

        $query = "SELECT * FROM $this->table_name WHERE code='$code'";
        $res = $wpdb->get_results($query, ARRAY_A);

        if ($res === null || sizeof($res) == 0) return null;
        $res = $res[0];

        $voucher = new Voucher();
        $voucher->setId($res[self::COL_ID]);
        $voucher->setIssued($res[self::COL_ISSUED]);
        $voucher->setRedeemed($res[self::COL_REDEEMED]);
        $voucher->setExpires($res[self::COL_EXPIRES]);
        $voucher->setCode($res[self::COL_CODE]);
        $voucher->setValue($res[self::COL_VALUE]);
        $voucher->setTemplateId($res[self::COL_TEMPLATE_ID]);

        return $voucher;
    }
}
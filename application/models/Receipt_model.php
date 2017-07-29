<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Receipt model
 *
 * @author Kaba N'faly
 * @since 07/15/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/models
 * @filesource receipt_model.php
 */
class Receipt_model extends CI_Model {

    /**
     * Receipt table name
     * @var String
     */
    public static $TABLE_NAME = 'receipt';

    /**
     * Receipt table primary key
     * @var String
     */
    public static $PK = 'id_receipt';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * retrieves all receipts if the input parameter ($id_receipt) is false, or 
     * retrieves the receipt identified by the input parameter value
     * @param type $id_receipt
     * @return type array
     */
    public function get_receipts($id_receipt = false) {
        if ($id_receipt === false) {

            $query = $this->db->get(self::$TABLE_NAME);
            return $query->result_array();
        }

        $query = $this->db->get_where(self::$TABLE_NAME, array(self::$PK => $id_receipt));
        return $query->row_array();
    }

    /**
     * Finds a receipt by gen_id
     * 
     * @param String $gen_id
     * @return mixed
     */
    public function find_gen_id($gen_id) {

        $query = $this->db->get_where(self::$TABLE_NAME, array('gen_id' => $gen_id));
        return $query->row_array();
    }

    /**
     * Saves a receipt if it doesn't exist
     * 
     * @param array $data
     * @return boolean
     */
    public function save($data) {
        return $this->db->insert(self::$TABLE_NAME, $data);
    }

    /**
     * Updates a receipt
     * 
     * @param array $data
     * @param array $where
     * @return boolean
     */
    public function update($data, $where) {
        // do update
        return $this->db->update(self::$TABLE_NAME, $data, $where);
    }

    /**
     * Delete a receipt
     * 
     * @param array $where
     * @return boolean
     */
    public function delete($where) {
        return $this->db->delete(self::$TABLE_NAME, $where);
    }

}

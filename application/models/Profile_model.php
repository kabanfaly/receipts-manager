<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Profile model
 *
 * @author Kaba N'faly
 * @since 07/15/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/models
 * @filesource profile_model.php
 */
class Profile_model extends CI_Model {

    /**
     * Profile table name
     * @var String
     */
    public static $TABLE_NAME = 'rcpt_profile';

    /**
     * Profile table primary key
     * @var String
     */
    public static $PK = 'id_profile';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * retrieves all profiles if the input parameter (id_profile) is false, or 
     * retrieves the profile identified by the input parameter value
     * @param type $id_profile
     * @return type array
     */
    public function get_profiles($id_profile = false) {
        if ($id_profile === false) {

            $query = $this->db->get(self::$TABLE_NAME);
            return $query->result_array();
        }

        $query = $this->db->get_where(self::$TABLE_NAME, array(self::$PK => $id_profile));
        return $query->row_array();
    }

    /**
     * Finds a profile by name
     * 
     * @param String $name
     * @return mixed
     */
    public function find_by_name($name) {

        $query = $this->db->get_where(self::$TABLE_NAME, array('name' => $name));
        return $query->row_array();
    }

    /**
     * Saves a profile if it doesn't exist
     * 
     * @param array $data
     * @return boolean
     */
    public function save($data) {
        //find city
        if ($this->find_by_name($data['name']) === NULL) {
            return $this->db->insert(self::$TABLE_NAME, $data);
        }
        return FALSE;
    }

    /**
     * Updates a profile
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
     * Deletes a profile
     * 
     * @param array $where
     * @return boolean
     */
    public function delete($where) {
        return $this->db->delete(self::$TABLE_NAME, $where);
    }

}

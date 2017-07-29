<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * User model
 *
 * @author Kaba N'faly
 * @since 07/15/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/models
 * @filesource User_model.php
 */
class User_model extends CI_Model {

    /**
     * User (user) table name
     * @var String
     */
    public static $TABLE_NAME = 'rcpt_user';

    /**
     * User table primary key
     * @var String
     */
    public static $PK = 'id_user';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * retrieve user and his profile by his login and password
     * @param String $login
     * @param String $password
     * @return array
     */
    public function get_user_profile($login, $password) {
        return $this->get_user($login, $password);
    }

    /**
     * Get user by login
     * @param type $login
     * @return type
     */
    public function get_user_profile_by_login($login) {

        return $this->get_user($login);
    }

    private function get_user($login, $password = FALSE) {

        $this->db->select('u.*, p.name as profile, droits_operations as authorized_operations');
        $this->db->join('rcpt_profile p', 'u.id_profile = p.id_profile', 'INNER');

        $where = array('login' => $login);
        if ($password) {
            $where['password'] = $password;
        }

        $query = $this->db->get_where(self::$TABLE_NAME . ' u', $where);
        return $query->row_array();
    }

    /**
     * retrieves all users if the input parameter (id_user) is false, or 
     * retrieves the user identified by the input parameter value
     * @param type $id_user
     * @return type array
     */
    public function get_users($id_user = false) {
        $this->db->select('u.*, p.name as profile');
        $this->db->join('rcpt_profile p', 'u.id_profile = p.id_profile', 'INNER');

        if ($id_user === false) {
            $query = $this->db->get(self::$TABLE_NAME . ' u');
            return $query->result_array();
        }

        $query = $this->db->get_where(self::$TABLE_NAME . ' u', array('u.' . self::$PK => $id_user));
        return $query->row_array();
    }

    /**
     * Updates a user info
     * 
     * @param array $data
     * @param array $where
     * @return boolean
     */
    public function update($data, $where) {

        //do update with uniq login
        if (isset($data['login'])) {
            //find user
            $user = $this->find_by_login($data['login']);

            //check whether the login is not defined for another user
            if ($user !== NULL) {
                // same user
                if ($user['id_user'] == $where['id_user']) {
                    // do update
                    return $this->db->update(self::$TABLE_NAME, $data, $where);
                } else {
                    return FALSE;
                }
            }
        }
        // do update
        return $this->db->update(self::$TABLE_NAME, $data, $where);
    }

    /**
     * Deletes an user
     * 
     * @param array $where
     * @return boolean
     */
    public function delete($where) {
        return $this->db->delete(self::$TABLE_NAME, $where);
    }

    /**
     * Saves a supplier if it doesn't exist
     * 
     * @param array $data
     * @return boolean
     */
    public function save($data) {
        //find user
        if ($this->find_by_login($data['login']) === NULL) {
            return $this->db->insert(self::$TABLE_NAME, $data);
        }
        return FALSE;
    }

    /**
     * find a user identified by its login
     * @param String $login
     * @return mixed
     */
    public function find_by_login($login) {
        $query = $this->db->get_where(self::$TABLE_NAME, array('login' => $login));
        return $query->row_array();
    }


}

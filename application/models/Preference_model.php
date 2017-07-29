<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Preference model
 *
 * @author Kaba N'faly
 * @since 07/27/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/controllers
 * @filesource preference_model.php
 */
class Preference_model extends CI_Model
{

    /**
     * Preference (preference) table name
     * @var String
     */
    public static $TABLE_NAME = 'pa_preference';

    /**
     * Preference (preference) table primary key
     * @var String
     */
    public static $PK = 'id_preference';
    public static $DEFAULT_PREFERENCE = array(
        'PHONE' => 'Tel: (+1) 438-346-4321',
        'EMAIL' => 'nfalykaba@gmail.com',
        'ADDRESS' => '2382 Rue rousseau <br>H8N 1K8 LaSalle, Québec',
        'COMPANY' => 'NafadjiTech Inc.'
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    /**
     * get all preference. Saves default preference if any parameter is found
     * @return array
     */
    public function get_preference()
    {
        // get preference
        $result = $this->find_by_name('preference');
        
        if ($result !== NULL)
        {
            return json_decode($result['valeur'], true);
        } else
        {   

            // save default preference
            $data = array('name' => 'preference', 'valeur' => json_encode(self::$DEFAULT_PREFERENCE));
            $this->save($data);
            
            return self::$DEFAULT_PREFERENCE;
        }
        return self::$DEFAULT_PREFERENCE;
    }

     /**
     * Finds a preference by name
     * 
     * @param String $name
     * @return mixed
     */
    public function find_by_name($name)
    {

        $query = $this->db->get_where(self::$TABLE_NAME, array('name' => $name));
        return $query->row_array();
    }
    /**
     * Saves a preference if it doesn't exist
     * 
     * @param array $data
     * @return boolean
     */
    public function save($data)
    {

        if ($this->find_by_name($data['name']) === NULL)
        {
            return $this->db->insert(self::$TABLE_NAME, $data);
        }
        return FALSE;
    }

    /**
     * Updates a preference
     * 
     * @param array $data
     * @param array $where
     * @return boolean
     */
    public function update_preference($data)
    {
        // do update
        return $this->db->update(self::$TABLE_NAME, $data, array('name' => 'preference'));
    }


}

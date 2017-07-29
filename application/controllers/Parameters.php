<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Parameters controller
 *
 * @author Kaba N'faly
 * @since 07/27/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/controllers
 * @filesource Parameters.php
 */
include_once 'Common_Controller.php';

class Parameters extends Common_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('preference_model');
    }

    /**
     * Displays parameters
     * 
     * @param String $msg message to display
     * @param boolean $error if $msg is an error message
     */
    public function index($msg = '', $error = FALSE)
    {
        $data = array(
            'title' => lang('PARAMETERS'),
            'msg' => $msg,
            'error' => $error,
            'active' => 'parameters',
            'configuration' => TRUE,
            'form_action' => site_url('parameters/update')
        );

        $this->display($data, 'parameters/index');
    }

    /**
     * Saves a parameters
     */
    public function update()
    {
        //checks session
        if ($this->connected())
        {
            //get inputs
            $company = trim($this->input->post('COMPANY'));
            $phone = trim($this->input->post('PHONE'));
            $email = trim($this->input->post('EMAIL'));
            $address = trim($this->input->post('ADDRESS'));

            // build parameters array
            $parameters = array('COMPANY' => $company, 'PHONE' => $phone, 'EMAIL' => $email, 'ADDRESS' => $address);

            $data = array('valeur' => json_encode($parameters));

            if ($this->preference_model->update_parameters($data) !== FALSE)
            {
                $this->session->set_userdata('parameters', $parameters);
                redirect('parameters/index/' . lang('SAVING_PARAMETERS_SUCCESS'));
            }
        }
    }

}

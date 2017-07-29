<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Receipt controller
 *
 * @author Kaba N'faly
 * @since 07/15/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/controllers
 * @filesource Receipt.php
 */

include_once 'Common_Controller.php';

class Receipt extends Common_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('receipt_model');
    }

    /**
     * get receipts
     * 
     * @param String $msg message to display
     * @param boolean $error if $msg is an error message
     */
    public function index($msg = '', $error = FALSE) {

        $data = array(
            'trucks' => $this->receipt_model->get_receipts(),
            'title' => lang('RECEIPT_MANAGEMENT'),
            'msg' => $msg,
            'error' => $error,
            'active' => 'receipt',
            'form_link' => site_url('receipt/edit')
        );

        $this->display($data, 'receipt/index');
    }

}

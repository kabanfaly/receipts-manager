<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Connection controller
 *
 * @author Kaba N'faly
 * @since 07/19/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/controllers
 * @filesource Connection.php
 */
class Connection extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    /**
     * Displays connection page
     */
    public function index()
    {

        $data = array(
            'title' => lang('CONNECTION')
        );

        $this->display($data, 'connection/index');
    }

    /**
     * Log user in
     */
    public function login()
    {
        $data = array(
            'title' => lang('CONNECTION'),
            'err_msg' => ''
        );

        $login = $this->input->post('login');
        $password = md5($this->input->post('password'));
        //get user
        $user = $this->user_model->get_user_profile($login, $password);
        
        if ($user !== NULL)
        {
            // user account is disabled
            if ($user['statut'] == 0)
            {
                $data['err_msg'] = lang('DISABLED_ACCOUNT');
                $this->display($data, 'connection/index');
            } else
            {
                unset($user['mot_de_passe']);
                // save user's info in session
                $this->session->set_userdata('user', $user);

                // save parameters in session
                $parameters = $this->preference_model->get_parameters();

                $this->session->set_userdata('parameters', $parameters);
                redirect('dechargement/index');
            }
        } else
        {
            $data['err_msg'] = lang('INVALID_LOGIN_PASSWORD');
            $this->display($data, 'connection/index');
        }
    }

    /**
     * Log user out
     */
    public function logout()
    {
        // clear session
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('parameters');
        $data = array(
            'title' => lang('CONNECTION'),
            'err_msg' => ''
        );

        $this->display($data, 'connection/index');
    }

    /**
     * Render page
     * @param array $data
     * @param string $page concerning page
     */
    private function display($data, $page)
    {
        $data['active'] = 'receipt';
        $this->load->view('templates/header', $data);
        $this->load->view($page, $data);
        $this->load->view('templates/footer');
    }

}

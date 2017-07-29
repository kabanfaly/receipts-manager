<?php

/**
 * Description of Common
 *
 * @author kaba
 */
class Common_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    /**
     * Render page
     * @param array $data
     * @param string $page concerning page
     */
    public function display($data, $page)
    {
        //checks admin session
        if (!$this->connected())
        {
            $data = array(
                'title' => lang('CONNECTION')
            );
            $page = 'connection/index';
        }

        $this->load->view('templates/header', $data);
        $this->load->view($page, $data);
        $this->load->view('templates/footer');
    }

    /**
     * Checks if a session is set
     * @return type
     */
    public function connected()
    {

        return $this->session->has_userdata('user');
    }

    /**
     * Updates current user's session infos
     */
    public function update_user_session()
    {

        if ($this->connected())
        {
            //get user
            $user = $this->user_model->get_user_profile_by_login($_SESSION['user']['login']);
            unset($user['mot_de_passe']);
            $this->session->set_userdata('user', $user);
        }
    }

}

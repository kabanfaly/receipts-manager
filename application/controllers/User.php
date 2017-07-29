<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * User controller
 *
 * @author Kaba N'faly
 * @since 07/19/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/controllers
 * @filesource user.php
 */
include_once 'Common_Controller.php';

class User extends Common_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'user');
        $this->load->model('profil_model', 'profile');
    }

    /**
     * get users
     * 
     * @param String $msg message to display
     * @param boolean $error if $msg is an error message
     */
    public function index($msg = '', $error = FALSE)
    {

        $data = array(
            'users' => $this->user->get_users(),
            'title' => lang('USERS_MANAGEMENT'),
            'msg' => $msg,
            'error' => $error,
            'active' => 'user',
            'form_link' => site_url('user/edit')
        );

        $this->display($data, 'user/index');
    }

    /**
     * Displays a form to add or edit a user
     * 
     * @param int $id_user user id to modify
     */
    public function edit($id_user = NULL)
    {

        $data = array(
            'title' => lang('ADD_USER'),
            'form_name' => 'user',
            'profiles' => $this->profile->get_profiles(),
            'form_action' => site_url('user/save')
        );

        //checks session
        if (!$this->connected())
        {
            $data = array(
                'title' => lang('CONNECTION')
            );
            $this->load->view('templates/header', $data);
            $this->load->view('connection/index', $data);
            $this->load->view('templates/footer');
        } else
        {
            // preset data for modification form
            if ($id_user !== NULL)
            {

                //get user by id
                $user = $this->user->get_users($id_user);

                //merge row data with $data
                $data = array_merge_recursive($data, $user);

                $data['title'] = lang('EDIT_USER');
                $data['form_action'] = site_url('user/update');
            }
            $this->load->view('templates/form_header', $data);
            $this->load->view('user/form', $data);
            $this->load->view('templates/form_footer', $data);
        }
    }


    /**
     * display user account page
     * @param type $id_user
     */
    public function my_account($id_user, $msg = '', $error = FALSE)
    {
        $data = array(
            'title' => lang('MY_PROFILE'),
            'form_name' => 'user',
            'active' => 'user',
            'configuration' => true,
            'profiles' => $this->profile->get_profiles(),
            'form_action' => site_url('user/save'),
            'msg' => $msg,
            'error' => $error
        );

        //checks admin session
        if (!$this->connected())
        {
            $data = array(
                'title' => lang('CONNECTION')
            );
            $this->load->view('templates/header', $data);
            $this->load->view('connection/index', $data);
            $this->load->view('templates/footer');
        } else
        {
            // preset data for modification form
            if ($id_user !== NULL)
            {

                //get user by id
                $user = $this->user->get_users($id_user);

                //merge row data with $data
                $data = array_merge_recursive($data, $user);

                $data['title'] = lang('MY_ACCOUNT');
                $data['form_action'] = site_url('user/update');
                $data['myaccount_username_change_form'] = site_url('user/update_name');
                $data['myaccount_login_change_form'] = site_url('user/update_login');
                $data['myaccount_password_change_form'] = site_url('user/update_password');
                $data['myaccount_profile_change_form'] = site_url('user/update_profile');
            }
            $this->load->view('templates/header', $data);
            $this->load->view('user/compte', $data);
            $this->load->view('templates/footer', $data);
        }
    }

    /**
     * Update user name 
     */
    public function update_name()
    {
        //checks session
        if ($this->connected())
        {
            $data['nom'] = ucfirst(strtolower(trim($this->input->post('nom'))));
            $data['prenom'] = ucfirst(strtolower(trim($this->input->post('prenom'))));

            $id_user = $this->input->post('id_user');

            $this->update_user_info($id_user, $data, lang('UPDATING_USER_NAME_OK'));
        }
    }

    /**
     * Update user login 
     */
    public function update_login()
    {
        //checks session
        if ($this->connected())
        {
            $data['login'] = trim($this->input->post('login'));
            $data['mot_de_passe'] = md5($this->input->post('mot_de_passe'));

            $id_user = $this->input->post('id_user');

            //check user password
            $user = $this->user->get_users($id_user);
            if ($user['mot_de_passe'] === md5($data['mot_de_passe']))
            {
                if (!$this->update_user_info($id_user, $data, lang('UPDATING_USER_LOGIN_OK')))
                {
                    redirect('user/my_account/' . $id_user . '/' . lang('USER_LOGIN_EXISTS') . ': ' . $data['login'] . '/' . TRUE);
                }
            } else
            {
                redirect('user/my_account/' . $id_user . '/' . lang('WRONG_PASSWORD') . '/' . TRUE);
            }
        }
    }

    /**
     * Update user password 
     */
    public function update_password()
    {
        //checks session
        if ($this->connected())
        {
            $current_password = md5($this->input->post('mot_de_passe'));
            $data['mot_de_passe'] = md5($this->input->post('new_mot_de_passe'));
            $id_user = $this->input->post('id_user');

            //check user current password
            $user = $this->user->get_users($id_user);

            if ($user['mot_de_passe'] === $current_password)
            {
                $this->update_user_info($id_user, $data, lang('UPDATING_USER_PASSWORD_OK'));
            } else
            {
                redirect('user/my_account/' . $id_user . '/' . lang('WRONG_CURRENT_PASSWORD') . '/' . TRUE);
            }
        }
    }

    /**
     * Update user profile 
     */
    public function update_profile()
    {
        //checks session
        if ($this->connected())
        {
            $data['id_profil'] = $this->input->post('id_profil');
            $id_user = $this->input->post('id_user');

            $this->update_user_info($id_user, $data, lang('UPDATING_USER_PROFILE_OK'));
        }
    }

    /**
     * update user account info
     * @param int $id_user
     * @param array $data
     */
    public function update_user_info($id_user, $data, $msg = '', $error = FALSE)
    {
        //checks session
        if ($this->connected())
        {

            $where = array(User_model::$PK => $id_user);

            // update
            if ($this->user->update($data, $where) !== FALSE)
            {
                //update current user's session
                $this->update_user_session();
                redirect('user/my_account/' . $id_user . '/' . $msg . '/' . $error);
            } else
            {
                return FALSE;
            }
        }
    }

    /**
     * builds inputs value in an array
     * @return array
     */
    private function get_inputs()
    {

        // get input values
        $data['nom'] = ucfirst(strtolower(trim($this->input->post('nom'))));
        $data['prenom'] = ucfirst(strtolower(trim($this->input->post('prenom'))));
        $data['login'] = trim($this->input->post('login'));
        $pwd = $this->input->post('mot_de_passe');
        if (!empty($pwd))
        {
            $data['mot_de_passe'] = md5($pwd);
        }
        $data['id_profil'] = $this->input->post('id_profil');
        $data['statut'] = $this->input->post('statut');

        return $data;
    }

    /**
     * Saves a user
     */
    public function save()
    {
        //get inputs
        $data = $this->get_inputs();

        // save if the user number doesn't exist
        if ($this->user->save($data) !== FALSE)
        {
            redirect('user/index/' . lang('SAVING_USER_SUCCESS'));
        } else
        {
            redirect('user/index/' . lang('USER_LOGIN_EXISTS') . ': ' . $data['login'] . '/' . TRUE);
        }
    }

    /**
     * Updates a user
     */
    public function update()
    {
        //checks session
        if ($this->connected())
        {
            // get input values
            $data = $this->get_inputs();

            $id_user = $this->input->post('id_user');

            $where = array(User_model::$PK => $id_user);

            // update
            if ($this->user->update($data, $where) !== FALSE)
            {
                redirect('user/index/' . lang('UPDATING_USER_SUCCESS'));
            } else
            {
                redirect('user/index/' . lang('USER_LOGIN_EXISTS') . ': ' . $data['login'] . '/' . TRUE);
            }
        }
    }

    /**
     * Delete a user
     * @param int $id_user
     */
    public function delete($id_user)
    {
        //checks session
        if ($this->connected())
        {
            if ($this->user->delete(array(User_model::$PK => $id_user)) !== FALSE)
            {
                redirect('user/index/' . lang('USER_DELETION_SUCCESS'));
            } else
            {
                redirect('user/index/' . lang('DELETION_FAILED') . '/' . TRUE);
            }
        }
    }

    /**
     * Change user status to either one
     * @param int $id_user user id
     * @param int $status new status
     */
    public function set_status($id_user, $status)
    {
        //checks session
        if ($this->connected())
        {
            $data = array('statut' => $status);
            $where = array(User_model::$PK => $id_user);
            // set status
            $this->user->update($data, $where);

            redirect('user/index');
        }
    }

}

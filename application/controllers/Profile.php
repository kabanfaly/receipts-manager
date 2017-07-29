<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Profile controller
 *
 * @author Kaba N'faly
 * @since 07/19/17
 * @version 1.0
 * @package receipt
 * @subpackage receipt/application/controllers
 * @filesource Profile.php
 */
include_once 'Common_Controller.php';

class Profile extends Common_Controller
{

    public static $OPERATIONS_RIGHTS = array(
      'add' => false,
      'edit' => false,
      'delete' => false
    );
    public function __construct()
    {
        parent::__construct();
        $this->load->model('profile_model');
    }

    /**
     * get profiles
     * 
     * @param String $msg message to display
     * @param boolean $error if $msg is an error message
     */
    public function index($msg = '', $error = FALSE)
    {
        $data = array(
            'profiles' => $this->profil_model->get_profiles(),
            'title' => lang('PROFILES_MANAGEMENT'),
            'msg' => $msg,
            'error' => $error,
            'active' => 'profil',
            'form_link' => site_url('profile/edit'),
            'operations_rights_link' => site_url('profile/operations_rights')
        );

        $this->display($data, 'profile/index');
    }

    /**
     * Displays a form to add or edit a profile
     * 
     * @param int $id_profile profile id to modify
     */
    public function edit($id_profile = NULL)
    {

        $data = array(
            'title' => lang('ADD_PROFILE'),
            'form_name' => 'profile',
            'form_action' => site_url('profile/save')
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
            if ($id_profile !== NULL)
            {

                //get profile by id
                $profile = $this->profil_model->get_profiles($id_profile);

                //merge row data with $data
                $data = array_merge_recursive($data, $profile);

                $data['title'] = lang('EDIT_PROFILE');
                $data['form_action'] = site_url('profile/update');
            }

            $this->load->view('templates/form_header', $data);
            $this->load->view('profile/form', $data);
            $this->load->view('templates/form_footer', $data);
        }
    }

    
    /**
     * retrieve profile restrictions on unloading table
     * @param type $id_profil
     */
    public function operations_rights($id_profil)
    {
        $data = array(
            'title' => lang('EDIT_OPERATIONS'),
            'form_name' => 'operation_right',
            'form_action' => site_url('profile/update_operations_rights')
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
            // Retreive profile's rights (restrictions)
            $profile = $this->profil_model->get_profiles($id_profil);
            
            //merge row data with $data
            $data = array_merge_recursive($data, $profile);

            if (empty($profile['droits_operations']))
            {
                $data['operations_rights'] = self::$OPERATIONS_RIGHTS;
            } else
            {
                $profile_operations_rights = json_decode($profile['droits_operations'], TRUE);
                // merging columns 
                $data['operations_rights'] = array_merge(self::$OPERATIONS_RIGHTS, $profile_operations_rights);
            }
            
        }
        $this->load->view('templates/form_header', $data);
        $this->load->view('profile/operation_right', $data);
        $this->load->view('templates/form_footer', $data);
    }

    
    /**
     * Update profile rights on unloading table's columns
     */
    public function update_operations_rights()
    {
        //checks session
        if ($this->connected())
        {
            $id_profil = $this->input->post('id_profil');

            // get authorized columns (checked)
            $authorized_operations = $this->input->post('authorized_operations');

            // get restrictions
            $profile_rights = self::$OPERATIONS_RIGHTS;

            // set to true authorized columns
            foreach ($authorized_operations as $au)
            {
                $profile_rights[$au] = TRUE;
            }

            $data['droits_operations'] = json_encode($profile_rights);
            $where = array(Profil_model::$PK => $id_profil);

            // update
            if ($this->profil_model->update($data, $where) !== FALSE)
            {   
                //update current user's session
                $this->update_user_session();
                redirect('profile/index/' . lang('UPDATING_PROFILE_OPERATIONS_RIGHTS_SUCCESS'));
            } else
            {
                redirect('profile/index/' . lang('UPDATING_PROFILE_OPERATIONS_RIGHTS_FAILED') . '/' . TRUE);
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
        $name = str_replace(' ', '', ucfirst(strtolower($this->input->post('name'))));

        $data = array('name' => $name);

        return $data;
    }

    /**
     * Saves a profile
     */
    public function save()
    {
        //checks session
        if ($this->connected())
        {
            //get inputs
            $data = $this->get_inputs();

            // save if the profil number doesn't exist
            if ($this->profil_model->save($data) !== FALSE)
            {
                redirect('profile/index/' . lang('SAVING_PROFILE_SUCCESS'));
            } else
            {
                redirect('profile/index/' . lang('PROFILE_EXISTS') . ': ' . $data['name'] . '/' . TRUE);
            }
        }
    }

    /**
     * Updates a profile
     */
    public function update()
    {
        //checks session
        if ($this->connected())
        {
            // get input values
            $data = $this->get_inputs();

            $id_profil = $this->input->post('id_profil');

            $where = array(Profil_model::$PK => $id_profil);

            // update
            if ($this->profil_model->update($data, $where) !== FALSE)
            {
                redirect('profile/index/' . lang('UPDATING_PROFILE_SUCCESS'));
            } else
            {
                redirect('profile/index/' . lang('UPDATING_FAILED') . '/' . TRUE);
            }
        }
    }

    /**
     * Delete a profile
     * @param int $id_profil
     */
    public function delete($id_profil)
    {
        //checks session
        if ($this->connected())
        {
            if ($this->profil_model->delete(array(Profil_model::$PK => $id_profil)) !== FALSE)
            {
                redirect('profile/index/' . lang('PROFILE_DELETION_SUCCESS'));
            } else
            {
                redirect('profile/index/' . lang('DELETION_FAILED') . '/' . TRUE);
            }
        }
    }

}

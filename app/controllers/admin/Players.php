<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 4/11/2019
 * Time: 9:26 PM
 */

class Players extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }

        $this->permission_details = $this->site->checkPermissions();
        $this->lang->admin_load('customers', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('players_model');
        $this->load->admin_model('auth_model');
    }

    function add()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['players_add'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $this->data['title'] = "Add Player";
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $this->form_validation->set_rules('dob', lang("dob"), 'trim|required');
        $this->form_validation->set_rules('bcp', lang("bcp"), 'trim|required');
        $this->form_validation->set_rules('gender', lang("gender"), 'trim|required');

        if ($this->form_validation->run() == true) {

            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->auth_model->hash_password($this->input->post('password'));
            $notify = $this->input->post('notify');
            $ip_address = $this->input->ip_address();
            $salt = $this->store_salt ? $this->salt() : FALSE;
            $image=null;

            $user_data = array(
                'first_name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '2',
                'warehouse_id' => $this->input->post('school_id'),
                'view_right' => $this->input->post('view_right'),
                'edit_right' => $this->input->post('edit_right'),
                'status' => $this->input->post('status'),
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'ip_address' => $ip_address,
                'created_on' => time(),
                'last_login' => time(),
                'active' => 1
            );
            $players_data = array(
                'name' => $this->input->post('name'),
                'dob' => $this->sma->fsd($this->input->post('dob')),
                'bcp' => $this->input->post('bcp'),
                'school_id' => $this->input->post('school_id'),
                'gender' => $this->input->post('gender'),
                'year' => $this->input->post('year'),
                'sessions' => $this->input->post('year'),
                'username' => $username,
                'is_tagged' => 0,
                'created_date' => date('Y-m-d h:i:s'),
                'created_by' => $this->session->userdata('user_id')
            );

        }
        if ($this->form_validation->run() == true && $this->players_model->addPlayers($players_data, $user_data)) {
            $this->session->set_flashdata('message', 'Data successfully updated.');
            admin_redirect("auth/users");

        } else {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['schools'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('Add_Player')));
            $meta = array('page_title' => lang('Players'), 'bc' => $bc);
            $this->page_construct('players/add_player', $meta, $this->data);
        }
    }


}
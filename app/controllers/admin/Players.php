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

    function index()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['players_index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }


        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Players')));
        $meta = array('page_title' => lang('Players'), 'bc' => $bc);
        $this->page_construct('players/index', $meta, $this->data);
    }

    function getPlayers()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['players_index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('users').".id as id,concat(". $this->db->dbprefix('users').".first_name,' ', ". $this->db->dbprefix('users').".last_name) as u_name, ". $this->db->dbprefix('players').".gender,  " . $this->db->dbprefix('players') . ".dob, floor(datediff(curdate()," . $this->db->dbprefix('players') . ".dob) / 365) as ages," . $this->db->dbprefix('players') . ".bcp, " . $this->db->dbprefix('warehouses') . ".name, ". $this->db->dbprefix('players').".year,  "  .$this->db->dbprefix('players') . ".team_id,". $this->db->dbprefix('players') . ".is_tagged")
            ->from("users")
            ->join('players', 'users.username=players.username', 'inner')
            ->join('warehouses', 'players.school_id=warehouses.id', 'inner')
            ->group_by('users.id')
            ->edit_column('active', '$1__$2', 'active, id')
//            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('players/edit/$1') . "' class='tip' title='" . lang("Edit_Player") . "'><i class=\"fa fa-edit\"></i></a></div>", "id");
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('players') . "' class='tip' title='" . lang("Edit_Player") . "'><i class=\"fa fa-edit\"></i></a></div>", "id");

        if (!$this->Owner) {
            $this->datatables->unset_column('id');
        }
        echo $this->datatables->generate();
    }

    function getUserLogins($id = NULL)
    {
        if (!$this->ion_auth->in_group(array('owner', 'admin'))) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            admin_redirect('welcome');
        }
        $this->load->library('datatables');
        $this->datatables
            ->select("login, ip_address, time")
            ->from("user_logins")
            ->where('user_id', $id);

        echo $this->datatables->generate();
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
        $this->form_validation->set_rules('username', lang("username"), 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[players.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('first_name', lang("first_name"), 'trim|required');
        $this->form_validation->set_rules('last_name', lang("last_name"), 'trim|required');
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
            $image = null;

            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '2',
                'warehouse_id' => $this->input->post('school_id'),
                'view_right' => $this->input->post('view_right'),
                'edit_right' => $this->input->post('edit_right'),
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'ip_address' => $ip_address,
                'created_on' => time(),
                'last_login' => time(),
                'active' => $this->input->post('status')
            );
            $players_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
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
            if ($notify) $msg = $this->sendEmail($this->input->post('name'), $email, $this->input->post('password'));
            $this->session->set_flashdata('message', 'Data successfully updated.');
            admin_redirect("players");

        } else {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['schools'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('players'), 'page' => lang('Players')), array('link' => '#', 'page' => lang('Add_Player')));
            $meta = array('page_title' => lang('Players'), 'bc' => $bc);
            $this->page_construct('players/add_player', $meta, $this->data);
        }
    }


    function sendEmail($name, $email, $password)
    {
        $message = "";
        $this->load->library('parser');
        $parse_data = array(
            'client_name' => $name,
            'site_link' => site_url(),
            'site_name' => $this->Settings->site_name,
            'email' => $email,
            'password' => $password,
            'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
        );

        $msg = file_get_contents('./themes/' . $this->Settings->theme . '/admin/views/email_templates/credentials.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = $this->lang->line('New_User_Created') . ' - ' . $this->Settings->site_name;
        try {
            $this->sma->send_email($email, $subject, $message);
            $message = "And user login credentials sent to email,please check and login";
        } catch (Exception $e) {
            $message = "But email sent time get exception," . $e->getMessage();
        }
        return $message;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 4/26/2019
 * Time: 6:04 PM
 */

class Teams extends MY_Controller
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
        $this->load->admin_model('teams_model');
        $this->load->admin_model('auth_model');


    }


    function index()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['teams-index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }


        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Team_Managers')));
        $meta = array('page_title' => lang('Team_Managers'), 'bc' => $bc);
        $this->page_construct('teams/index', $meta, $this->data);
    }

    function getTeams()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['teams-index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $user_id = null;
        if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $user_id = $this->session->userdata('user_id');
        }

        $warehouse_id = null;
        if (!$this->Owner && !$this->Admin) {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('teams') . ".id as ids," . $this->db->dbprefix('users') . ".avatar," . $this->db->dbprefix('teams') . ".tfl as ref," . $this->db->dbprefix('teams') . ".name as u_name, "  . $this->db->dbprefix('warehouses') . ".name as nam, " . $this->db->dbprefix('categories') . ".name as name1s,". $this->db->dbprefix('teams') . ".zone")
            ->from("users");
        if ($user_id) {
            $this->datatables->where('users.id', $user_id);
        }
        if ($warehouse_id) {
            $this->datatables->where('users.warehouse_id', $warehouse_id);
        }
        $this->datatables->join('teams', 'users.username=teams.username', 'inner')
            ->join('categories', 'teams.division=categories.id', 'left')
            ->join('warehouses', 'teams.school_id=warehouses.id', 'inner');
        $this->datatables->group_by('users.id');

        $this->datatables->edit_column('active', '$1', 'ids')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('teams/edit/$1') . "' class='tip' title='" . lang("Edit_Team") . "'><i class=\"fa fa-edit\"></i></a>&nbsp;<a href='#' class='po' title='<b>" . lang("Delete_Team") . "</b>' data-content=\"<p>"
                . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('teams/delete/$1') . "'>"
                . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "ids");
        echo $this->datatables->generate();
    }

    function add()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['teams-add'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $this->data['title'] = "Add Team";
        $this->form_validation->set_rules('username', lang("username"), 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $this->form_validation->set_rules('school_id', lang("school_id"), 'trim|required');
        $this->form_validation->set_rules('zone', lang("zone"), 'trim|required');
        $this->form_validation->set_rules('division', lang("division"), 'trim|required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->auth_model->hash_password($this->input->post('password'));
            $notify = $this->input->post('notify');
            $ip_address = $this->input->ip_address();
            $salt = $this->store_salt ? $this->salt() : FALSE;
            $image = null;

            $user_data = array(
                'first_name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '7',
                'warehouse_id' => $this->input->post('school_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'view_right' => 0,
                'edit_right' => 0,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'ip_address' => $ip_address,
                'created_on' => time(),
                'last_login' => time(),
                'active' => $this->input->post('status')
            );
            $team_data = array(
                'name' => $this->input->post('name'),
                'school_id' => $this->input->post('school_id'),
                'username' => $username,
                'created_date' => date('Y-m-d h:i:s'),
                'created_by' => $this->session->userdata('user_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'other_information' => $this->input->post('other_information'),
            );

        }
        if ($this->form_validation->run() == true && $this->teams_model->addTeam($team_data, $user_data)) {
//        if ($this->form_validation->run() == true) {
            if ($notify) $msg = $this->sendEmail(($this->input->post('name')), $email, $this->input->post('password'));
            $this->session->set_flashdata('message', 'Data successfully updated.');
            admin_redirect("teams");

        } else {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['schools'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['zones'] = $this->site->getAllZone();
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('teams'), 'page' => lang('Team_Managers')), array('link' => '#', 'page' => lang('Add_Team_Manager')));
            $meta = array('page_title' => lang('Team_Managers'), 'bc' => $bc);
            $this->page_construct('teams/add_team', $meta, $this->data);
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

    function modal_view($id = NULL)
    {
        $this->sma->checkPermissions('index', TRUE);
        $pr_details = $this->teams_model->getTeamsByID($id);
        if (!$id || !$pr_details) {
            $this->session->set_flashdata('error', lang('Team_Not_Found'));
            $this->sma->md();
        }
        $this->data['team'] = $pr_details;
        $this->data['user'] = $this->teams_model->getUsersByID($pr_details->username);
        $this->data['warehouses'] = $this->teams_model->getWarehouseByID($pr_details->school_id);
        $this->data['category'] = $this->teams_model->getCategoryByID($pr_details->division);
        $this->load->view($this->theme . 'teams/modal_view', $this->data);
    }


    function edit($id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['teams-edit'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->teams_model->getteamsByID($id);
        $usr_details = $this->teams_model->getUsersByID($pr_details->username);
//        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $this->form_validation->set_rules('school_id', lang("school_id"), 'trim|required');
        $this->form_validation->set_rules('zone', lang("zone"), 'trim|required');
        $this->form_validation->set_rules('division', lang("division"), 'trim|required');

        if ($usr_details->email != strtolower($this->input->post('email'))) {
            $this->form_validation->set_rules('email', lang("email"), 'trim|required|is_unique[users.email]');
        }

        if ($this->form_validation->run() == true) {
            $user_data = array(
                'first_name' => $this->input->post('name'),
                'email' => strtolower($this->input->post('email')),
                'phone' => $this->input->post('phone'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'warehouse_id' => $this->input->post('school_id')
            );
            $coach_data = array(
                'name' => $this->input->post('name'),
                'school_id' => $this->input->post('school_id'),
                'updated_date' => date('Y-m-d h:i:s'),
                'updated_by' => $this->session->userdata('user_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'other_information' => $this->input->post('other_information'),
            );
        }
        if ($this->form_validation->run() == true && $this->teams_model->updateTeam($id, $coach_data, $usr_details->id, $user_data)) {
            $this->session->set_flashdata('message', lang("Info_Updated_Successfully."));
            admin_redirect("teams");
        } else {
            $this->data['team'] = $pr_details;
            $this->data['user'] = $usr_details;
            $this->data['schools'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['zones'] = $this->site->getAllZone();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('teams'), 'page' => lang('Team_Managers')), array('link' => '#', 'page' => lang('Edit_Team_Managers')));
            $meta = array('page_title' => lang('Team_Managers'), 'bc' => $bc);
            $this->page_construct('teams/edit_team', $meta, $this->data);
        }
    }


    function delete($id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['teams-delete'])) {
                $this->sma->send_json(array('error' => 1, 'msg' => lang("access_denied")));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

//        @todo
//        need to check when player implemented

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->teams_model->getteamsByID($id);
        $usr_details = $this->teams_model->getUsersByID($pr_details->username);
        if ($this->teams_model->deleteTeam($id, $usr_details->id)) {
            unlink('assets/uploads/avatars/' . $usr_details->avatar);
            unlink('assets/uploads/avatars/thumbs/' . $usr_details->avatar);
            $this->sma->send_json(array('error' => 0, 'msg' => lang("Info_Deleted_Successfully")));
        } else {
            $this->sma->send_json(array('error' => 1, 'msg' => lang("Operation_Not_Success")));
        }
    }


}
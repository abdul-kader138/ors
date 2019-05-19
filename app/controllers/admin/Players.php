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
            if ((!$get_permission['players-index'])) {
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
            if ((!$get_permission['players-index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $user_id = null;
        if (!$this->Owner && !$this->Admin && !$this->Coaches && !$this->Teams && !$this->session->userdata('view_right') ) {
            $user_id = $this->session->userdata('user_id');
        }

        $warehouse_id = null;
        if (!$this->Owner && !$this->Admin) {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }

        $user_details=$this->site->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;

        $category_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->division) $category_id = $user_details->division;

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('players') . ".id as ids," . $this->db->dbprefix('users') . ".avatar," . $this->db->dbprefix('players') . ".ssfl as ref,concat(" . $this->db->dbprefix('users') . ".first_name,' ', " . $this->db->dbprefix('users') . ".last_name) as u_name, " . $this->db->dbprefix('players') . ".gender,  " . $this->db->dbprefix('players') . ".dob, floor(datediff(curdate()," . $this->db->dbprefix('players') . ".dob) / 365) as ages," . $this->db->dbprefix('players') . ".bcp, " . $this->db->dbprefix('warehouses') . ".name, " . $this->db->dbprefix('players') . ".sea_year,  " . $this->db->dbprefix('categories') . ".name as names")
            ->from("users");
        $this->datatables->join('players', 'users.username=players.username', 'inner')
            ->join('categories', 'players.division=categories.id', 'left')
            ->join('warehouses', 'players.school_id=warehouses.id', 'inner');

        if ($user_id) $this->datatables->where('users.id', $user_id);
        if ($warehouse_id) $this->datatables->where('users.warehouse_id', $warehouse_id);
        if ($category_id) $this->datatables->where('categories.id', $category_id);
        if ($zone_id) $this->datatables->where('users.zone', $zone_id);
        $this->datatables->group_by('users.id');

        $this->datatables->edit_column('active', '$1', 'ids')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('players/edit/$1') . "' class='tip' title='" . lang("Edit_Player") . "'><i class=\"fa fa-edit\"></i></a>&nbsp;<a href='#' class='po' title='<b>" . lang("Delete_Player") . "</b>' data-content=\"<p>"
                . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('players/delete/$1') . "'>"
                . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "ids");
        echo $this->datatables->generate();
    }

    function add()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['players-add'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $this->data['title'] = "Add Player";
        $this->form_validation->set_rules('username', lang("username"), 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[players.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|required|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('first_name', lang("first_name"), 'trim|required');
        $this->form_validation->set_rules('last_name', lang("last_name"), 'trim|required');
        $this->form_validation->set_rules('school_id', lang("school_id"), 'trim|required');
        $this->form_validation->set_rules('dob', lang("dob"), 'trim|required');
        $this->form_validation->set_rules('bcp', lang("bcp"), 'trim|required');
        $this->form_validation->set_rules('gender', lang("gender"), 'trim|required');
        $this->form_validation->set_rules('zone', lang("zone"), 'trim|required');
        $this->form_validation->set_rules('division', lang("division"), 'trim|required');
        $this->form_validation->set_rules('pey', lang("pey"), 'trim|required');
        $this->form_validation->set_rules('pc', lang("pc"), 'trim|required');
        $this->form_validation->set_rules('sea_year', lang("sea_year"), 'trim|required');
        $this->form_validation->set_rules('trs', lang("trs"), 'trim|required');
        $this->form_validation->set_rules('last_attend_school', lang("last_attend_school"), 'trim|required');

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
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '5',
                'warehouse_id' => $this->input->post('school_id'),
                'view_right' => 0,
                'edit_right' => 0,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'ip_address' => $ip_address,
                'created_on' => time(),
                'last_login' => time(),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'active' => $this->input->post('status')
            );
            $players_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'dob' => $this->sma->fsd($this->input->post('dob')),
                'bcp' => $this->input->post('bcp'),
                'school_id' => $this->input->post('school_id'),
                'gender' => $this->input->post('gender'),
                'sessions' => $this->input->post('year'),
                'username' => $username,
                'is_tagged' => 0,
                'created_date' => date('Y-m-d h:i:s'),
                'created_by' => $this->session->userdata('user_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'pey' => $this->input->post('pey'),
                'pc' => $this->input->post('pc'),
                'sea_year' => $this->input->post('sea_year'),
                'trs' => $this->input->post('trs'),
                'last_attend_school' => $this->input->post('last_attend_school'),
                'address' => $this->input->post('address'),
                'jersey_number' => $this->input->post('jersey_number'),
                'sea_number' => $this->input->post('sea_number')
            );

        }
        if ($this->form_validation->run() == true && $this->players_model->addPlayers($players_data, $user_data)) {
            if ($notify) $msg = $this->sendEmail(($this->input->post('first_name').' '.$this->input->post('last_name')), $email, $this->input->post('password'));
            $this->session->set_flashdata('message', 'Data successfully updated.');
            admin_redirect("players");

        } else {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['schools'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['zones'] = $this->site->getAllZone();
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('players'), 'page' => lang('Players')), array('link' => '#', 'page' => lang('Add_Player')));
            $meta = array('page_title' => lang('Players'), 'bc' => $bc);
            $this->page_construct('players/add_player', $meta, $this->data);
        }
    }

    function edit($id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['players-edit'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->players_model->getPlayersByID($id);
        $usr_details = $this->players_model->getUsersByID($pr_details->username);
//        if ($this->input->post('phone') != $usr_details->phone) {
//            $this->form_validation->set_rules('phone', lang("phone"), 'is_unique[users.phone]');
//        }
        $this->form_validation->set_rules('first_name', lang("first_name"), 'trim|required');
        $this->form_validation->set_rules('last_name', lang("last_name"), 'trim|required');
        $this->form_validation->set_rules('dob', lang("dob"), 'trim|required');
        $this->form_validation->set_rules('bcp', lang("bcp"), 'trim|required');
        $this->form_validation->set_rules('gender', lang("gender"), 'trim|required');
        $this->form_validation->set_rules('school_id', lang("school_id"), 'trim|required');
        $this->form_validation->set_rules('zone', lang("zone"), 'trim|required');
        $this->form_validation->set_rules('division', lang("division"), 'trim|required');
        $this->form_validation->set_rules('pey', lang("pey"), 'trim|required');
        $this->form_validation->set_rules('pc', lang("pc"), 'trim|required');
        $this->form_validation->set_rules('sea_year', lang("sea_year"), 'trim|required');
        $this->form_validation->set_rules('trs', lang("trs"), 'trim|required');
        $this->form_validation->set_rules('last_attend_school', lang("last_attend_school"), 'trim|required');
        if ($usr_details->email != strtolower($this->input->post('email'))) {
            $this->form_validation->set_rules('email', lang("email"), 'trim|required|is_unique[users.email]');
        }

        if ($this->form_validation->run() == true) {
            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => strtolower($this->input->post('email')),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'warehouse_id' => $this->input->post('school_id')
            );
            $players_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'dob' => $this->sma->fsd($this->input->post('dob')),
                'bcp' => $this->input->post('bcp'),
                'school_id' => $this->input->post('school_id'),
                'gender' => $this->input->post('gender'),
                'year' => $this->input->post('year'),
                'updated_date' => date('Y-m-d h:i:s'),
                'updated_by' => $this->session->userdata('user_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'pey' => $this->input->post('pey'),
                'pc' => $this->input->post('pc'),
                'sea_year' => $this->input->post('sea_year'),
                'trs' => $this->input->post('trs'),
                'last_attend_school' => $this->input->post('last_attend_school'),
                'address' => $this->input->post('address'),
                'jersey_number' => $this->input->post('jersey_number'),
                'sea_number' => $this->input->post('sea_number')
            );
        }
        if ($this->form_validation->run() == true && $this->players_model->updatePlayers($id, $players_data, $usr_details->id, $user_data)) {
            $this->session->set_flashdata('message', lang("Info_Updated_Successfully."));
            admin_redirect("players");
        } else {
            $this->data['player'] = $pr_details;
            $this->data['user'] = $usr_details;
            $this->data['schools'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['zones'] = $this->site->getAllZone();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('players'), 'page' => lang('Players')), array('link' => '#', 'page' => lang('Edit_Player')));
            $meta = array('page_title' => lang('Players'), 'bc' => $bc);
            $this->page_construct('players/edit_player', $meta, $this->data);
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
        $pr_details = $this->players_model->getPlayersByID($id);
        if (!$id || !$pr_details) {
            $this->session->set_flashdata('error', lang('Players_Not_Found'));
            $this->sma->md();
        }
        $this->data['players'] = $pr_details;
        $this->data['user'] = $this->players_model->getUsersByID($pr_details->username);
        $this->data['warehouses'] = $this->players_model->getWarehouseByID($pr_details->school_id);
        $this->data['category'] = $this->players_model->getCategoryByID($pr_details->division);
        $this->load->view($this->theme . 'players/modal_view', $this->data);
    }


    function delete($id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['players-delete'])) {
                $this->sma->send_json(array('error' => 1, 'msg' => lang("access_denied")));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

//        @todo
//        need to check when player implemented

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->players_model->getPlayersByID($id);
        $usr_details = $this->players_model->getUsersByID($pr_details->username);
        if ($this->players_model->deletePlayer($id, $usr_details->id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("Info_Deleted_Successfully")));
        } else {
            $this->sma->send_json(array('error' => 1, 'msg' => lang("Operation_Not_Success")));
        }
    }

}
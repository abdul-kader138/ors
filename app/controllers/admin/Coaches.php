<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 4/25/2019
 * Time: 9:36 PM
 */

class Coaches extends MY_Controller
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
        $this->load->admin_model('coaches_model');
        $this->load->admin_model('auth_model');
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif';
        $this->allowed_file_size = '5048';
        $this->upload_path = 'assets/uploads/coaches/';


    }

    function index()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['coaches-index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }


        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Coaches')));
        $meta = array('page_title' => lang('Coaches'), 'bc' => $bc);
        $this->page_construct('coaches/index', $meta, $this->data);
    }

    function getCoaches()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['coaches-index'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }
        $payments_link=null;
        $user_id = null;
        if (!$this->Owner && !$this->Admin && !$this->Teams && !$this->session->userdata('view_right'))
            $user_id = $this->session->userdata('user_id');

        $warehouse_id = null;
        if (!$this->Owner && !$this->Admin) $warehouse_id = $this->session->userdata('warehouse_id');

        $user_details=$this->site->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;

        $category_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->division) $category_id = $user_details->division;

        if ($this->Owner || $this->Admin) $payments_link = anchor('admin/coaches/update_status/$1', '<i class="fa fa-check-square"></i>' . lang(''), 'data-toggle="modal" class="tip" title="' . lang('Update_Status') . '" data-target="#myModal"');
        $do_upload__link = anchor('admin/coaches/upload_document/$1', '<i class="fa fa-upload"></i>' . lang(''), 'data-toggle="modal" class="tip" title="' . lang('Upload_Document') . '" data-target="#myModal"');



        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('coaches') . ".id as ids," . $this->db->dbprefix('users') . ".avatar," . $this->db->dbprefix('coaches') . ".cfl as ref,concat(" . $this->db->dbprefix('users') . ".first_name,' ', " . $this->db->dbprefix('users') . ".last_name) as u_name, " . $this->db->dbprefix('coaches') . ".gender,  " . $this->db->dbprefix('warehouses') . ".name, " . $this->db->dbprefix('categories') . ".name as names," . $this->db->dbprefix('coaches') . ".zone,". $this->db->dbprefix('coaches') . ".c_status")
            ->from("users");

        $this->datatables->join('coaches', 'users.username=coaches.username', 'inner')
            ->join('categories', 'coaches.division=categories.id', 'left')
            ->join('warehouses', 'coaches.school_id=warehouses.id', 'inner');

        if ($user_id)  $this->datatables->where('users.id', $user_id);
        if ($warehouse_id) $this->datatables->where('users.warehouse_id', $warehouse_id);
        if ($category_id) $this->datatables->where('categories.id', $category_id);
        if ($zone_id) $this->datatables->where('users.zone', $zone_id);

        $this->datatables->group_by('users.id');

        $this->datatables->edit_column('active', '$1', 'ids')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('coaches/edit/$1') . "' class='tip' title='" . lang("Edit_Coach") . "'><i class=\"fa fa-edit\"></i></a>&nbsp;<a href='#' class='po' title='<b>" . lang("Delete_Coach") . "</b>' data-content=\"<p>"
                . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('coaches/delete/$1') . "'>"
                . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>&nbsp;" .$payments_link . " &nbsp;".$do_upload__link."</a></div>", "ids");
        echo $this->datatables->generate();
    }


    function add()
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['coaches-add'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $this->data['title'] = "Add Coach";
        $this->form_validation->set_rules('username', lang("username"), 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('username', lang("username"), 'trim|required|is_unique[coaches.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('first_name', lang("first_name"), 'trim|required');
        $this->form_validation->set_rules('last_name', lang("last_name"), 'trim|required');
        $this->form_validation->set_rules('school_id', lang("school_id"), 'trim|required');
        $this->form_validation->set_rules('gender', lang("gender"), 'trim|required');
        $this->form_validation->set_rules('zone', lang("zone"), 'trim|required');
        $this->form_validation->set_rules('avatar', lang("avatar"), 'trim');
        $this->form_validation->set_rules('division', lang("division"), 'trim|required');

        $image = null;
        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->auth_model->hash_password($this->input->post('password'));
            $notify = $this->input->post('notify');
            $ip_address = $this->input->ip_address();
            $salt = $this->store_salt ? $this->salt() : FALSE;
            if ($_FILES['avatar']['size'] > 0) $image = $this->image_upload();
            else $this->form_validation->set_rules('avatar', lang("avatar"), 'required');

            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '6',
                'warehouse_id' => $this->input->post('school_id'),
                'view_right' => 0,
                'edit_right' => 0,
                'username' => $username,
                'password' => $password,
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'email' => $email,
                'ip_address' => $ip_address,
                'avatar' => $image,
                'created_on' => time(),
                'last_login' => time(),
                'active' => $this->input->post('status')
            );
            $coach_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'school_id' => $this->input->post('school_id'),
                'gender' => $this->input->post('gender'),
                'username' => $username,
                'is_tagged' => 0,
                'created_date' => date('Y-m-d h:i:s'),
                'created_by' => $this->session->userdata('user_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'address' => $this->input->post('address'),
            );

        }
        if ($this->form_validation->run() == true && $this->coaches_model->addCoach($coach_data, $user_data)) {
//        if ($this->form_validation->run() == true) {
            if ($notify) $msg = $this->sendEmail(($this->input->post('first_name') . ' ' . $this->input->post('last_name')), $email, $this->input->post('password'));
            $this->session->set_flashdata('message', 'Data successfully updated.');
            admin_redirect("coaches");

        } else {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['schools'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['zones'] = $this->site->getAllZone();
            if ($image) {
                unlink('assets/uploads/avatars/' . $image);
                unlink('assets/uploads/avatars/thumbs/' . $image);
            }
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('coaches'), 'page' => lang('Players')), array('link' => '#', 'page' => lang('Add_Coach')));
            $meta = array('page_title' => lang('Coaches'), 'bc' => $bc);
            $this->page_construct('coaches/add_coach', $meta, $this->data);
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
        $pr_details = $this->coaches_model->getCoachesByID($id);
        if (!$id || !$pr_details) {
            $this->session->set_flashdata('error', lang('Coach_Not_Found'));
            $this->sma->md();
        }
        $updated_by=null;
        $this->data['coach'] = $pr_details;
        $this->data['user'] = $this->coaches_model->getUsersByID($pr_details->username);
        if ($pr_details->status_updated_by) $updated_by = $this->site->getUserByID($pr_details->status_updated_by);
        $this->data['warehouses'] = $this->coaches_model->getWarehouseByID($pr_details->school_id);
        $this->data['category'] = $this->coaches_model->getCategoryByID($pr_details->division);
        $this->data['updated_by'] = $updated_by;
        $this->load->view($this->theme . 'coaches/modal_view', $this->data);
    }


    function edit($id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['coaches-edit'])) {
                $this->session->set_flashdata('warning', lang('access_denied'));
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->coaches_model->getCoachesByID($id);
        $usr_details = $this->coaches_model->getUsersByID($pr_details->username);
//        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('first_name', lang("first_name"), 'trim|required');
        $this->form_validation->set_rules('last_name', lang("last_name"), 'trim|required');
        $this->form_validation->set_rules('school_id', lang("school_id"), 'trim|required');
        $this->form_validation->set_rules('gender', lang("gender"), 'trim|required');
        $this->form_validation->set_rules('zone', lang("zone"), 'trim|required');
        $this->form_validation->set_rules('division', lang("division"), 'trim|required');

        if ($usr_details->email != strtolower($this->input->post('email'))) {
            $this->form_validation->set_rules('email', lang("email"), 'trim|required|is_unique[users.email]');
        }

        if ($this->form_validation->run() == true) {
            $user_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => strtolower($this->input->post('email')),
                'phone' => $this->input->post('phone'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'gender' => $this->input->post('gender'),
                'warehouse_id' => $this->input->post('school_id')
            );
            $coach_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'school_id' => $this->input->post('school_id'),
                'gender' => $this->input->post('gender'),
                'updated_date' => date('Y-m-d h:i:s'),
                'updated_by' => $this->session->userdata('user_id'),
                'zone' => $this->input->post('zone'),
                'division' => $this->input->post('division'),
                'address' => $this->input->post('address'),
            );
        }
        if ($this->form_validation->run() == true && $this->coaches_model->updateCoach($id, $coach_data, $usr_details->id, $user_data)) {
            $this->session->set_flashdata('message', lang("Info_Updated_Successfully."));
            admin_redirect("coaches");
        } else {
            $this->data['coach'] = $pr_details;
            $this->data['user'] = $usr_details;
            $this->data['schools'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['zones'] = $this->site->getAllZone();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => admin_url('home'), 'page' => lang('home')), array('link' => admin_url('coaches'), 'page' => lang('Coach')), array('link' => '#', 'page' => lang('Edit_Coach')));
            $meta = array('page_title' => lang('Players'), 'bc' => $bc);
            $this->page_construct('coaches/edit_coach', $meta, $this->data);
        }
    }


    function delete($id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $get_permission = $this->permission_details[0];
            if ((!$get_permission['coaches-delete'])) {
                $this->sma->send_json(array('error' => 1, 'msg' => lang("access_denied")));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

//        @todo
//        need to check when player implemented

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->coaches_model->getCoachesByID($id);
        $usr_details = $this->coaches_model->getUsersByID($pr_details->username);
        if ($this->coaches_model->deleteCoach($id, $usr_details->id)) {
            unlink('assets/uploads/avatars/' . $usr_details->avatar);
            unlink('assets/uploads/avatars/thumbs/' . $usr_details->avatar);
            unlink($this->upload_path . $pr_details->document);
            $this->sma->send_json(array('error' => 0, 'msg' => lang("Info_Deleted_Successfully")));
        } else {
            $this->sma->send_json(array('error' => 1, 'msg' => lang("Operation_Not_Success")));
        }
    }


    function image_upload()
    {
        $this->load->library('upload');
        $config['upload_path'] = 'assets/uploads/avatars';
        $config['allowed_types'] = 'gif|jpg|png';
        //$config['max_size'] = '500';
        $config['max_width'] = $this->Settings->iwidth;
        $config['max_height'] = $this->Settings->iheight;
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $config['max_filename'] = 25;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('avatar')) {

            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $photo = $this->upload->file_name;

        $this->load->helper('file');
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = 'assets/uploads/avatars/' . $photo;
        $config['new_image'] = 'assets/uploads/avatars/thumbs/' . $photo;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 200;
        $config['height'] = 200;

        $this->image_lib->clear();
        $this->image_lib->initialize($config);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        return $photo;
    }


    public function update_status($id)
    {

        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['coaches'] = $this->coaches_model->getCoachesByID($id);
        $this->load->view($this->theme . 'coaches/update_status', $this->data);
    }


    public function update_coaches_status($id)
    {

        $this->form_validation->set_rules('status', lang("sale_status"), 'required');

        if ($this->form_validation->run() == true) {
            $status = $this->input->post('status');
            $status_updated_date = date('Y-m-d h:i:s');
            $status_updated_by = $this->session->userdata('user_id');
        } elseif ($this->input->post('update')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'coaches');
        }

        if ($this->form_validation->run() == true && $this->coaches_model->updateStatus($id, $status, $status_updated_by, $status_updated_date)) {
            $this->session->set_flashdata('message', lang('status_updated'));
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'coaches');
        } else {
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'coaches');
        }
    }

    public function upload_document($id)
    {

        $this->data['coaches'] = $this->coaches_model->getCoachesByID($id);
        $this->load->view($this->theme . 'coaches/upload_document', $this->data);
    }

    function upload_coaches_document($id)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('document', lang("document"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            $coach=$this->coaches_model->getCoachesByID($id);
            if($coach->document) unlink($this->upload_path . $coach->document);

            $note=$this->input->post('note');
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['max_size'] = $this->allowed_file_size;
                $config['allowed_types'] = $this->digital_file_types;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $doc_name = $this->upload->file_name;
                $this->db->update('coaches', array('document' => $doc_name,'note' => $note), array('id' => $id));
            }
            $this->session->set_flashdata('message', lang('Document_Uploaded_Successfully.'));
            redirect($_SERVER["HTTP_REFERER"]);

        } elseif ($this->input->post('upload_document')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            admin_redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'coaches');
        }
    }


}
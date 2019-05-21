<?php defined('BASEPATH') OR exit('No direct script access allowed');

class System_settings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }

        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('admin');
        }
        $this->lang->admin_load('settings', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->admin_model('settings_model');
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif';
        $this->allowed_file_size = '1024';
    }

    function index()
    {
        $this->form_validation->set_rules('site_name', lang('site_name'), 'trim|required');
        $this->form_validation->set_rules('dateformat', lang('dateformat'), 'trim|required');
        $this->form_validation->set_rules('timezone', lang('timezone'), 'trim|required');
        $this->form_validation->set_rules('mmode', lang('maintenance_mode'), 'trim|required');
        $this->form_validation->set_rules('email', lang('default_email'), 'trim|required');
        $this->form_validation->set_rules('warehouse', lang('default_warehouse'), 'trim|required');
        $this->form_validation->set_rules('theme', lang('theme'), 'trim|required');
        $this->form_validation->set_rules('rows_per_page', lang('rows_per_page'), 'trim|required');
        $this->form_validation->set_rules('protocol', lang('email_protocol'), 'trim|required');
        $this->form_validation->set_rules('iwidth', lang('image_width'), 'trim|numeric|required');
        $this->form_validation->set_rules('iheight', lang('image_height'), 'trim|numeric|required');
        $this->form_validation->set_rules('twidth', lang('thumbnail_width'), 'trim|numeric|required');
        $this->form_validation->set_rules('theight', lang('thumbnail_height'), 'trim|numeric|required');

        if ($this->input->post('protocol') == 'smtp') {
            $this->form_validation->set_rules('smtp_host', lang('smtp_host'), 'required');
            $this->form_validation->set_rules('smtp_user', lang('smtp_user'), 'required');
            $this->form_validation->set_rules('smtp_pass', lang('smtp_pass'), 'required');
            $this->form_validation->set_rules('smtp_port', lang('smtp_port'), 'required');
        }
        if ($this->input->post('protocol') == 'sendmail') {
            $this->form_validation->set_rules('mailpath', lang('mailpath'), 'required');
        }
        $this->form_validation->set_rules('decimals', lang('decimals'), 'trim|required');
        $this->form_validation->set_rules('decimals_sep', lang('decimals_sep'), 'trim|required');
        $this->form_validation->set_rules('thousands_sep', lang('thousands_sep'), 'trim|required');

        if ($this->form_validation->run() == true) {

            $language = 'english';

            if ((file_exists(APPPATH.'language'.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'sma_lang.php') && is_dir(APPPATH.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$language)) || $language == 'english') {
                $lang = $language;
            } else {
                $this->session->set_flashdata('error', lang('language_x_found'));
                admin_redirect("system_settings");
                $lang = 'english';
            }

            $tax1 = ($this->input->post('tax_rate') != 0) ? 1 : 0;
            $tax2 = ($this->input->post('tax_rate2') != 0) ? 1 : 0;

            $data = array('site_name' => DEMO ? 'Stock Manager Advance' : $this->input->post('site_name'),
                'rows_per_page' => $this->input->post('rows_per_page'),
                'dateformat' => $this->input->post('dateformat'),
                'timezone' => DEMO ? 'Asia/Kuala_Lumpur' : $this->input->post('timezone'),
                'mmode' => trim($this->input->post('mmode')),
                'default_email' => DEMO ? 'noreply@tecdiary.com' : $this->input->post('email'),
                'default_warehouse' => $this->input->post('warehouse'),
                'theme' => trim($this->input->post('theme')),
                'restrict_calendar' => $this->input->post('restrict_calendar'),
                'captcha' => $this->input->post('captcha'),
                'protocol' => DEMO ? 'mail' : $this->input->post('protocol'),
                'mailpath' => $this->input->post('mailpath'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_user' => $this->input->post('smtp_user'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_crypto' => $this->input->post('smtp_crypto') ? $this->input->post('smtp_crypto') : NULL,
                'decimals' => $this->input->post('decimals'),
                'decimals_sep' => $this->input->post('decimals_sep'),
                'thousands_sep' => $this->input->post('thousands_sep'),
                'iwidth' => $this->input->post('iwidth'),
                'iheight' => $this->input->post('iheight'),
                'twidth' => $this->input->post('twidth'),
                'theight' => $this->input->post('theight'),
                'rtl' => $this->input->post('rtl'),
            );
            if ($this->input->post('smtp_pass')) {
                $data['smtp_pass'] = $this->input->post('smtp_pass');
            }
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {
            if ( ! DEMO && TIMEZONE != $data['timezone']) {
                if ( ! $this->write_index($data['timezone'])) {
                    $this->session->set_flashdata('error', lang('setting_updated_timezone_failed'));
                    admin_redirect('system_settings');
                }
            }

            $this->session->set_flashdata('message', lang('setting_updated'));
            admin_redirect("system_settings");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['settings'] = $this->settings_model->getSettings();
            $this->data['date_formats'] = $this->settings_model->getDateFormats();
            $this->data['warehouses'] = $this->settings_model->getAllWarehouses();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
            $meta = array('page_title' => lang('system_settings'), 'bc' => $bc);
            $this->page_construct('settings/index', $meta, $this->data);
        }
    }

    function change_logo()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            $this->sma->md();
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('site_logo', lang("site_logo"), 'xss_clean');
        $this->form_validation->set_rules('login_logo', lang("login_logo"), 'xss_clean');
        $this->form_validation->set_rules('biller_logo', lang("biller_logo"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if ($_FILES['site_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('site_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $site_logo = $this->upload->file_name;
                $this->db->update('settings', array('logo' => $site_logo), array('setting_id' => 1));
            }

            if ($_FILES['login_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('login_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $login_logo = $this->upload->file_name;
                $this->db->update('settings', array('logo2' => $login_logo), array('setting_id' => 1));
            }

            if ($_FILES['biller_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('biller_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
            }

            $this->session->set_flashdata('message', lang('logo_uploaded'));
            redirect($_SERVER["HTTP_REFERER"]);

        } elseif ($this->input->post('upload_logo')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/change_logo', $this->data);
        }
    }

    public function write_index($timezone)
    {

        $template_path = './assets/config_dumps/index.php';
        $output_path = SELF;
        $index_file = file_get_contents($template_path);
        $new = str_replace("%TIMEZONE%", $timezone, $index_file);
        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable($output_path)) {
            if (fwrite($handle, $new)) {
                @chmod($output_path, 0644);
                return true;
            } else {
                @chmod($output_path, 0644);
                return false;
            }
        } else {
            @chmod($output_path, 0644);
            return false;
        }
    }


    function backups()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $this->data['files'] = glob('./files/backups/*.zip', GLOB_BRACE);
        $this->data['dbs'] = glob('./files/backups/*.txt', GLOB_BRACE);
        krsort($this->data['files']); krsort($this->data['dbs']);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('backups')));
        $meta = array('page_title' => lang('backups'), 'bc' => $bc);
        $this->page_construct('settings/backups', $meta, $this->data);
    }

    function backup_database()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
            'filename' => 'sma_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup =& $back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->session->set_flashdata('messgae', lang('db_saved'));
        admin_redirect("system_settings/backups");
    }

    function backup_files()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $name = 'file-backup-' . date("Y-m-d-H-i-s");
        $this->sma->zip("./", './files/backups/', $name);
        $this->session->set_flashdata('messgae', lang('backup_saved'));
        admin_redirect("system_settings/backups");
        exit();
    }

    function restore_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $file = file_get_contents('./files/backups/' . $dbfile . '.txt');
        // $this->db->conn_id->multi_query($file);
        mysqli_multi_query($this->db->conn_id, $file);
        $this->db->conn_id->close();
        admin_redirect('logout/db');
    }

    function download_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $this->load->library('zip');
        $this->zip->read_file('./files/backups/' . $dbfile . '.txt');
        $name = $dbfile . '.zip';
        $this->zip->download($name);
        exit();
    }

    function download_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $this->load->helper('download');
        force_download('./files/backups/' . $zipfile . '.zip', NULL);
        exit();
    }

    function restore_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        $file = './files/backups/' . $zipfile . '.zip';
        $this->sma->unzip($file, './');
        $this->session->set_flashdata('success', lang('files_restored'));
        admin_redirect("system_settings/backups");
        exit();
    }

    function delete_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        unlink('./files/backups/' . $dbfile . '.txt');
        $this->session->set_flashdata('messgae', lang('db_deleted'));
        admin_redirect("system_settings/backups");
    }

    function delete_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            admin_redirect("welcome");
        }
        unlink('./files/backups/' . $zipfile . '.zip');
        $this->session->set_flashdata('messgae', lang('backup_deleted'));
        admin_redirect("system_settings/backups");
    }

    function email_templates($template = "credentials")
    {

        $this->form_validation->set_rules('mail_body', lang('mail_message'), 'trim|required');
        $this->load->helper('file');
        $temp_path = is_dir('./themes/' . $this->theme . 'email_templates/');
        $theme = $temp_path ? $this->theme : 'default';
        if ($this->form_validation->run() == true) {
            $data = $_POST["mail_body"];
            if (write_file('./themes/' . $this->theme . 'email_templates/' . $template . '.html', $data)) {
                $this->session->set_flashdata('message', lang('message_successfully_saved'));
                admin_redirect('system_settings/email_templates#' . $template);
            } else {
                $this->session->set_flashdata('error', lang('failed_to_save_message'));
                admin_redirect('system_settings/email_templates#' . $template);
            }
        } else {

            $this->data['credentials'] = file_get_contents('./themes/' . $this->theme . 'email_templates/credentials.html');
            $this->data['sale'] = file_get_contents('./themes/' . $this->theme . 'email_templates/sale.html');
            $this->data['quote'] = file_get_contents('./themes/' . $this->theme . 'email_templates/quote.html');
            $this->data['purchase'] = file_get_contents('./themes/' . $this->theme . 'email_templates/purchase.html');
            $this->data['transfer'] = file_get_contents('./themes/' . $this->theme . 'email_templates/transfer.html');
            $this->data['payment'] = file_get_contents('./themes/' . $this->theme . 'email_templates/payment.html');
            $this->data['forgot_password'] = file_get_contents('./themes/' . $this->theme . 'email_templates/forgot_password.html');
            $this->data['activate_email'] = file_get_contents('./themes/' . $this->theme . 'email_templates/activate_email.html');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('email_templates')));
            $meta = array('page_title' => lang('email_templates'), 'bc' => $bc);
            $this->page_construct('settings/email_templates', $meta, $this->data);
        }
    }

    function create_group()
    {

        $this->form_validation->set_rules('group_name', lang('group_name'), 'required|alpha_dash|is_unique[groups.name]');

        if ($this->form_validation->run() == TRUE) {
            $data = array('name' => strtolower($this->input->post('group_name')), 'description' => $this->input->post('description'));
        } elseif ($this->input->post('create_group')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/user_groups");
        }

        if ($this->form_validation->run() == TRUE && ($new_group_id = $this->settings_model->addGroup($data))) {
            $this->session->set_flashdata('message', lang('group_added'));
            admin_redirect("system_settings/permissions/" . $new_group_id);

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('description'),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_group', $this->data);
        }
    }

    function edit_group($id)
    {

        if (!$id || empty($id)) {
            admin_redirect('system_settings/user_groups');
        }

        $group = $this->settings_model->getGroupByID($id);

        $this->form_validation->set_rules('group_name', lang('group_name'), 'required|alpha_dash');

        if ($this->form_validation->run() === TRUE) {
            $data = array('name' => strtolower($this->input->post('group_name')), 'description' => $this->input->post('description'));
            $group_update = $this->settings_model->updateGroup($id, $data);

            if ($group_update) {
                $this->session->set_flashdata('message', lang('group_udpated'));
            } else {
                $this->session->set_flashdata('error', lang('attempt_failed'));
            }
            admin_redirect("system_settings/user_groups");
        } else {


            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['group'] = $group;

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_name', $group->name),
            );
            $this->data['group_description'] = array(
                'name' => 'group_description',
                'id' => 'group_description',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_description', $group->description),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_group', $this->data);
        }
    }

    function permissions($id = NULL)
    {

        $this->form_validation->set_rules('group', lang("group"), 'is_natural_no_zero');
        if ($this->form_validation->run() == true) {

            $data = array(

                'bulk_actions' => $this->input->post('bulk_actions'),

                'players-index' => $this->input->post('players-index'),
                'players-edit' => $this->input->post('players-edit'),
                'players-add' => $this->input->post('players-add'),
                'players-delete' => $this->input->post('players-delete'),

                'coaches-index' => $this->input->post('coaches-index'),
                'coaches-edit' => $this->input->post('coaches-edit'),
                'coaches-add' => $this->input->post('coaches-add'),
                'coaches-delete' => $this->input->post('coaches-delete'),

                'teams-index' => $this->input->post('teams-index'),
                'teams-edit' => $this->input->post('teams-edit'),
                'teams-add' => $this->input->post('teams-add'),
                'teams-delete' => $this->input->post('teams-delete')
            );

            if (POS) {
                $data['pos-index'] = $this->input->post('pos-index');
            }

            //$this->sma->print_arrays($data);
        }


        if ($this->form_validation->run() == true && $this->settings_model->updatePermissions($id, $data)) {
            $this->session->set_flashdata('message', lang("group_permissions_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['id'] = $id;
            $this->data['p'] = $this->settings_model->getGroupPermissions($id);
            $this->data['group'] = $this->settings_model->getGroupByID($id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('group_permissions')));
            $meta = array('page_title' => lang('group_permissions'), 'bc' => $bc);
            $this->page_construct('settings/permissions', $meta, $this->data);
        }
    }

    function user_groups()
    {

        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            admin_redirect('auth');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['groups'] = $this->settings_model->getGroups();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('groups')));
        $meta = array('page_title' => lang('groups'), 'bc' => $bc);
        $this->page_construct('settings/user_groups', $meta, $this->data);
    }

    function delete_group($id = NULL)
    {

        if ($this->settings_model->checkGroupUsers($id)) {
            $this->session->set_flashdata('error', lang("group_x_b_deleted"));
            admin_redirect("system_settings/user_groups");
        }

        if ($this->settings_model->deleteGroup($id)) {
            $this->session->set_flashdata('message', lang("group_deleted"));
            admin_redirect("system_settings/user_groups");
        }
    }


    function categories()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('categories')));
        $meta = array('page_title' => lang('categories'), 'bc' => $bc);
        $this->page_construct('settings/categories', $meta, $this->data);
    }

    function getCategories()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('categories')}.id as id, {$this->db->dbprefix('categories')}.image, {$this->db->dbprefix('categories')}.code, {$this->db->dbprefix('categories')}.name", FALSE)
            ->from("categories")
            ->join("categories c", 'c.id=categories.parent_id', 'left')
            ->group_by('categories.id')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('system_settings/edit_category/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_category") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_category") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('system_settings/delete_category/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_category()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');
        $this->form_validation->set_rules('description', lang("description"), 'trim|required');

        if ($this->form_validation->run() == true) {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'parent_id' => $this->input->post('parent'),
                );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'left';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            }

        } elseif ($this->input->post('add_category')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategory($data)) {
            $this->session->set_flashdata('message', lang("category_added"));
            admin_redirect("system_settings/categories");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['categories'] = $this->settings_model->getParentCategories();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_category', $this->data);

        }
    }

    function edit_category($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|required');
        $pr_details = $this->settings_model->getCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("category_code"), 'required|is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("category_name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');
        $this->form_validation->set_rules('description', lang("description"), 'trim|required');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'slug' => $this->input->post('slug'),
                'description' => $this->input->post('description'),
                'parent_id' => $this->input->post('parent'),
                );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'left';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            }

        } elseif ($this->input->post('edit_category')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCategory($id, $data)) {
            $this->session->set_flashdata('message', lang("category_updated"));
            admin_redirect("system_settings/categories");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['category'] = $this->settings_model->getCategoryByID($id);
            $this->data['categories'] = $this->settings_model->getParentCategories();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_category', $this->data);

        }
    }

    function delete_category($id = NULL)
    {

        if ($this->site->getSubCategories($id)) {
            $this->sma->send_json(array('error' => 1, 'msg' => lang("category_has_subcategory")));
        }

        if ($this->settings_model->deleteCategory($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("category_deleted")));
        }
    }

    function category_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCategory($id);
                    }
                    $this->session->set_flashdata('message', lang("categories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('slug'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('image'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('parent_category'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getCategoryByID($id);
                        $parent_actegory = '';
                        if ($sc->parent_id) {
                            $pc = $this->settings_model->getCategoryByID($sc->parent_id);
                            $parent_category = $pc->code;
                        }
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->slug);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sc->image);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $parent_category);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }


    function customer_groups()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('customer_groups')));
        $meta = array('page_title' => lang('customer_groups'), 'bc' => $bc);
        $this->page_construct('settings/customer_groups', $meta, $this->data);
    }

    function getCustomerGroups()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name, percent")
            ->from("customer_groups")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('system_settings/edit_customer_group/$1') . "' class='tip' title='" . lang("edit_customer_group") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_customer_group") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('system_settings/delete_customer_group/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_customer_group()
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|is_unique[customer_groups.name]|required');
        $this->form_validation->set_rules('percent', lang("group_percentage"), 'required|numeric');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'),
                'percent' => $this->input->post('percent'),
            );
        } elseif ($this->input->post('add_customer_group')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/customer_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCustomerGroup($data)) {
            $this->session->set_flashdata('message', lang("customer_group_added"));
            admin_redirect("system_settings/customer_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_customer_group', $this->data);
        }
    }

    function edit_customer_group($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|required');
        $pg_details = $this->settings_model->getCustomerGroupByID($id);
        if ($this->input->post('name') != $pg_details->name) {
            $this->form_validation->set_rules('name', lang("group_name"), 'required|is_unique[tax_rates.name]');
        }
        $this->form_validation->set_rules('percent', lang("group_percentage"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'percent' => $this->input->post('percent'),
            );
        } elseif ($this->input->post('edit_customer_group')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/customer_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCustomerGroup($id, $data)) {
            $this->session->set_flashdata('message', lang("customer_group_updated"));
            admin_redirect("system_settings/customer_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['customer_group'] = $this->settings_model->getCustomerGroupByID($id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_customer_group', $this->data);
        }
    }

    function delete_customer_group($id = NULL)
    {
        if ($this->settings_model->deleteCustomerGroup($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("customer_group_deleted")));
        }
    }


    function warehouses()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => admin_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('warehouses')));
        $meta = array('page_title' => lang('warehouses'), 'bc' => $bc);
        $this->page_construct('settings/warehouses', $meta, $this->data);
    }

    function getWarehouses()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('warehouses')}.id as id, map, code, {$this->db->dbprefix('warehouses')}.name as name,  phone, email, address,principal,zonal_representative,home_ground")
            ->from("warehouses")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . admin_url('system_settings/edit_warehouse/$1') . "' class='tip' title='" . lang("edit_warehouse") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_warehouse") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . admin_url('system_settings/delete_warehouse/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_warehouse()
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[warehouses.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');
        $this->form_validation->set_rules('userfile', lang("map_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    admin_redirect("system_settings/warehouses");
                }

                $map = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/' . $map;
                $config['new_image'] = 'assets/uploads/thumbs/' . $map;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 76;
                $config['height'] = 76;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            } else {
                $map = NULL;
            }
            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'principal' => $this->input->post('principal'),
                'gcp' => $this->input->post('gcp'),
                'zonal_representative' => $this->input->post('zonal_representative'),
                'home_ground' => $this->input->post('home_ground'),
                'address' => $this->input->post('address'),
                'price_group_id' => $this->input->post('price_group'),
                'map' => $map,
            );
        } elseif ($this->input->post('add_warehouse')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/warehouses");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addWarehouse($data)) {
            $this->session->set_flashdata('message', lang("warehouse_added"));
            admin_redirect("system_settings/warehouses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
//            $this->data['price_groups'] = $this->settings_model->getAllPriceGroups();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_warehouse', $this->data);
        }
    }

    function edit_warehouse($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $wh_details = $this->settings_model->getWarehouseByID($id);
        if ($this->input->post('code') != $wh_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'required|is_unique[warehouses.code]');
        }
        $this->form_validation->set_rules('address', lang("address"), 'required');
        $this->form_validation->set_rules('map', lang("map_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'principal' => $this->input->post('principal'),
                'gcp' => $this->input->post('gcp'),
                'zonal_representative' => $this->input->post('zonal_representative'),
                'home_ground' => $this->input->post('home_ground'),
                'address' => $this->input->post('address'),
                'price_group_id' => $this->input->post('price_group'),
            );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    admin_redirect("system_settings/warehouses");
                }

                $data['map'] = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/' . $data['map'];
                $config['new_image'] = 'assets/uploads/thumbs/' . $data['map'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 76;
                $config['height'] = 76;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            }
        } elseif ($this->input->post('edit_warehouse')) {
            $this->session->set_flashdata('error', validation_errors());
            admin_redirect("system_settings/warehouses");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateWarehouse($id, $data)) { //check to see if we are updateing the customer
            $this->session->set_flashdata('message', lang("warehouse_updated"));
            admin_redirect("system_settings/warehouses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouse'] = $this->settings_model->getWarehouseByID($id);
//            $this->data['price_groups'] = $this->settings_model->getAllPriceGroups();
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_warehouse', $this->data);
        }
    }

    function delete_warehouse($id = NULL)
    {
        if ($this->settings_model->deleteWarehouse($id)) {
            $this->sma->send_json(array('error' => 0, 'msg' => lang("warehouse_deleted")));
        }
    }

    function warehouse_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteWarehouse($id);
                    }
                    $this->session->set_flashdata('message', lang("warehouses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('warehouses'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('city'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $wh = $this->settings_model->getWarehouseByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $wh->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $wh->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $wh->address);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $wh->city);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'warehouses_' . date('Y_m_d_H_i_s');
                    $this->load->helper('excel');
                    create_excel($this->excel, $filename);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_warehouse_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }


    function import_categories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {
                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    admin_redirect("system_settings/categories");
                }
                $csv = $this->upload->file_name;
                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $updated = '';
                $categories = $subcategories = [];
                foreach ($arrResult as $key => $value) {
                    $code = trim($value[0]);
                    $name = trim($value[1]);
                    $pcode = isset($value[4]) ? trim($value[4]) : null;
                    if ($code && $name && trim($value[2])) {
                        $category = [
                            'code' => $code,
                            'name' => $name,
                            'slug' => isset($value[2]) ? trim($value[2]) : $code,
                            'image' => isset($value[3]) ? trim($value[3]) : 'no_image.png',
                            'parent_id' => $pcode,
                            'description' => isset($value[5]) ? trim($value[5]) : null,
                        ];
                        if (!empty($pcode) && ($pcategory = $this->settings_model->getCategoryByCode($pcode))) {
                            $category['parent_id'] = $pcategory->id;
                        }
                        if ($c = $this->settings_model->getCategoryByCode($code)) {
                            $updated .= '<p>'.lang('category_updated').' ('.$code.')</p>';
                            $this->settings_model->updateCategory($c->id, $category);
                        } else {
                            if ($category['parent_id']) {
                                $subcategories[] = $category;
                            } else {
                                $categories[] = $category;
                            }
                        }
                    }
                }
            }

            // $this->sma->print_arrays($categories, $subcategories);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategories($categories, $subcategories)) {
            $this->session->set_flashdata('message', lang("categories_added").$updated);
            admin_redirect('system_settings/categories');
        } else {
            if ((isset($categories) && empty($categories)) || (isset($subcategories) && empty($subcategories))) {
                if ($updated) {
                    $this->session->set_flashdata('message', $updated);
                } else {
                    $this->session->set_flashdata('warning', lang('data_x_categories'));
                }
                admin_redirect('system_settings/categories');
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_categories', $this->data);

        }
    }

    function import_subcategories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    admin_redirect("system_settings/categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('code', 'name', 'category_code', 'image');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                $rw = 2;
                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getSubcategoryByCode(trim($csv_ct['code']))) {
                        if ($parent_actegory = $this->settings_model->getCategoryByCode(trim($csv_ct['category_code']))) {
                            $data[] = array(
                                'code' => trim($csv_ct['code']),
                                'name' => trim($csv_ct['name']),
                                'image' => trim($csv_ct['image']),
                                'category_id' => $parent_actegory->id,
                                );
                        } else {
                            $this->session->set_flashdata('error', lang("check_category_code") . " (" . $csv_ct['category_code'] . "). " . lang("category_code_x_exist") . " " . lang("line_no") . " " . $rw);
                            admin_redirect("system_settings/categories");
                        }
                    }
                    $rw++;
                }
            }

            // $this->sma->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addSubCategories($data)) {
            $this->session->set_flashdata('message', lang("subcategories_added"));
            admin_redirect('system_settings/categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_subcategories', $this->data);

        }
    }


}

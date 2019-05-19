<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    //abdul Kader
    public function getTotalUsers()
    {

        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        $division_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;
        if (!$this->Owner && !$this->Admin && $user_details->division) $division_id = $user_details->division;

        $this->db->select('count(id) as total', FALSE);
        if (!$this->Owner && !$this->Admin) $this->db->where('users.warehouse_id', $this->session->userdata('warehouse_id'));
        if($zone_id) $this->db->where('users.zone',$zone_id);
        if($division_id) $this->db->where('users.division',$division_id);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getTotalPlayers()
    {

        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        $division_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;
        if (!$this->Owner && !$this->Admin && $user_details->division) $division_id = $user_details->division;

        $this->db->select('count(id) as total', FALSE);
        if (!$this->Owner && !$this->Admin)  $this->db->where('players.school_id', $this->session->userdata('warehouse_id'));
        if($zone_id) $this->db->where('players.zone',$zone_id);
        if($division_id) $this->db->where('players.division',$division_id);
        $q = $this->db->get('players');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getLatestUsers()
    {
        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        $division_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;
        if (!$this->Owner && !$this->Admin && $user_details->division) $division_id = $user_details->division;


        $this->db->select('*')
            ->from('users')
//            ->join('companies', 'sales.customer_id = companies.id', 'left')
            ->limit(5);
        $this->db->order_by('users.id', 'desc');
        if (!$this->Owner && !$this->Admin) $this->db->where('users.warehouse_id', $this->session->userdata('warehouse_id'));
        if($zone_id) $this->db->where('users.zone',$zone_id);
        if($division_id) $this->db->where('users.division',$division_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

    }

    public function getLatestPlayers()
    {

        $warehouse_id = null;
        if (!$this->Owner || !$this->Admin) {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }

        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        $division_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;
        if (!$this->Owner && !$this->Admin && $user_details->division) $division_id = $user_details->division;

        $this->db->select('players.*,warehouses.name as wname')
            ->from('players')
            ->join('users', 'users.username = players.username', 'inner')
            ->join('warehouses', 'warehouses.id = players.school_id', 'inner')
            ->limit(5);
        if ($warehouse_id) $this->db->where('users.warehouse_id', $warehouse_id);
        if($zone_id) $this->db->where('users.zone',$zone_id);
        if($division_id) $this->db->where('users.division',$division_id);
        $this->db->order_by('users.id', 'desc');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

    }

    public function getTotalCoaches()
    {

        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        $division_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;
        if (!$this->Owner && !$this->Admin && $user_details->division) $division_id = $user_details->division;
        $this->db->select('count(id) as total', FALSE);
        if (!$this->Owner && !$this->Admin) $this->db->where('coaches.school_id', $this->session->userdata('warehouse_id'));
        if($zone_id) $this->db->where('coaches.zone',$zone_id);
        if($division_id) $this->db->where('coaches.division',$division_id);
        $q = $this->db->get('coaches');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getLatestCoaches()
    {

        $warehouse_id = null;
        if (!$this->Owner || !$this->Admin) {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }

        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        $division_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) $zone_id = $user_details->zone;
        if (!$this->Owner && !$this->Admin && $user_details->division) $division_id = $user_details->division;

        $this->db->select('coaches.*,warehouses.name as wname')
            ->from('coaches')
            ->join('users', 'users.username = coaches.username', 'inner')
            ->join('warehouses', 'warehouses.id = coaches.school_id', 'inner')
            ->limit(5);
        if ($warehouse_id) $this->db->where('users.warehouse_id', $warehouse_id);
        if($zone_id) $this->db->where('users.zone',$zone_id);
        if($division_id) $this->db->where('users.division',$division_id);

        $this->db->order_by('users.id', 'desc');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

    }


    public function getTotalSchools()
    {

        $this->db->select('count(id) as total', FALSE);
        if (!$this->Owner && !$this->Admin) {
            $this->db->where('warehouses.id', $this->session->userdata('warehouse_id'));
        }
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getLatestSchools()
    {

        $warehouse_id = null;
        if (!$this->Owner || !$this->Admin) {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }
        $this->db->select('*')
            ->from('warehouses')
            ->limit(5);
        if ($warehouse_id) $this->db->where('warehouses.id', $warehouse_id);
        $this->db->order_by('warehouses.id', 'desc');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

    }

    public function getUserByID($id)
    {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getZoneMaleHistory($start_date = NULL, $end_date = NULL)
    {
//        if (!$start_date) {
//            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
//        }
//        if (!$end_date) {
//            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
//        }
        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) {
            $zone_id = $user_details->zone;
        }
        if($zone_id) $this->db->where('zone',$zone_id);
        $this->db
            ->select("zone,COUNT(id) as id")
//            ->select_count('id')
            ->from('players')
            ->where('gender','Male')
            ->group_by('zone')
            ->order_by('zone', 'asc')
            ->limit(10);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getZoneFemaleHistory($start_date = NULL, $end_date = NULL)
    {
//        if (!$start_date) {
//            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
//        }
//        if (!$end_date) {
//            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
//        }
        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        if (!$this->Owner && !$this->Admin && $user_details->zone) {
            $zone_id = $user_details->zone;
        }
        if($zone_id) $this->db->where('zone',$zone_id);
        $this->db
            ->select("zone,COUNT(id) as id")
//            ->select_count('id')
            ->from('players')
            ->where('gender','Female')
            ->group_by('zone')
            ->order_by('zone', 'asc')
            ->limit(10);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 4/25/2019
 * Time: 10:36 PM
 */

class Coaches_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addCoach($data = array(),$user_data=array())
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->insert('users', $user_data);
        $this->db->insert('coaches', $data);
        $last_id = $this->db->insert_id();
        $ref='C-'.date("Y").sprintf("%04d", $last_id);
        $this->db->where('id',$last_id);
        $this->db->update('coaches', array('cfl'=>$ref));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        return true;
    }


    public function getCoachesByID($id)
    {
        $q = $this->db->get_where('coaches', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUsersByID($username)
    {
        $q = $this->db->get_where('users', array('username' => $username), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getWarehouseByID($id)
    {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUserByID($id)
    {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateCoach($player_id, $coach_data = array(),$user_id,$user_data = array())
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->where('id',$player_id);
        $this->db->update('coaches', $coach_data);
        $this->db->where('id',$user_id);
        $this->db->update('users', $user_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        else return true;
    }


    public function deleteCoach($player_id,$user_id)
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->where('id',$player_id);
        $this->db->delete('coaches');
        $this->db->where('id',$user_id);
        $this->db->delete('users');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        else return true;
    }

    public function getCategoryByID($id)
    {
        $q = $this->db->get_where("categories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getAllZone()
    {
        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $zone_id = null;
        if (!$this->Owner && !$this->Admin && $user_details) {
            $zone_id = $user_details->zone;
        }
        if($zone_id) $this->db->where('name',$zone_id);
        $q = $this->db->get('brands');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function getAllCategories()
    {
        $user_details=$this->getUserByID($this->session->userdata('user_id'));
        $category_id = null;
        if (!$this->Owner && !$this->Admin && $user_details) {
            $category_id = $user_details->division;
        }
        if($category_id) $this->db->where('id',$category_id);
        $this->db->where('parent_id', NULL)->or_where('parent_id', 0)->order_by('name');
        $q = $this->db->get("categories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateStatus($player_id,  $status, $status_updated_by,$status_updated_date)
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->update('coaches', array('c_status' => $status, 'status_updated_by' => $status_updated_by,'status_updated_date' => $status_updated_date), array('id' => $player_id));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        else return true;
    }



}
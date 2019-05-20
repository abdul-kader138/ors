<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 4/26/2019
 * Time: 6:06 PM
 */

class Teams_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function addTeam($data = array(),$user_data=array())
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->insert('users', $user_data);
        $this->db->insert('teams', $data);
        $last_id = $this->db->insert_id();
        $ref='T-'.date("Y").sprintf("%04d", $last_id);
        $this->db->where('id',$last_id);
        $this->db->update('teams', array('tfl'=>$ref));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        return true;
    }


    public function getTeamsByID($id)
    {
        $q = $this->db->get_where('teams', array('id' => $id), 1);
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

    public function updateTeam($team_id, $team_data = array(),$user_id,$user_data = array())
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->where('id',$team_id);
        $this->db->update('teams', $team_data);
        $this->db->where('id',$user_id);
        $this->db->update('users', $user_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        else return true;
    }


    public function deleteTeam($player_id,$user_id)
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->where('id',$player_id);
        $this->db->delete('teams');
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



}
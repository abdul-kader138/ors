<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 4/11/2019
 * Time: 9:28 PM
 */

class Players_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function addPlayers($data = array(),$user_data=array())
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->insert('users', $user_data);
        $this->db->insert('players', $data);
        $last_id = $this->db->insert_id();
        $ref=date("Y").sprintf("%04d", $last_id);
        $this->db->where('id',$last_id);
        $this->db->update('players', array('ssfl'=>$ref));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        return true;
    }


    public function getPlayersByID($id)
    {
        $q = $this->db->get_where('players', array('id' => $id), 1);
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


    public function updatePlayers($player_id, $player_data = array(),$user_id,$user_data = array())
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->where('id',$player_id);
        $this->db->update('players', $player_data);
        $this->db->where('id',$user_id);
        $this->db->update('users', $user_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        else return true;
    }


    public function deletePlayer($player_id,$user_id)
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->where('id',$player_id);
        $this->db->delete('players');
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

    public function getPlayersByName($username)
    {
        $q = $this->db->get_where('players', array('username' => $username), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateStatus($player_id,  $status, $status_updated_by,$status_updated_date)
    {
        $this->db->trans_strict(TRUE);
        $this->db->trans_start();
        $this->db->update('players', array('p_status' => $status, 'status_updated_by' => $status_updated_by,'status_updated_date' => $status_updated_date), array('id' => $player_id));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        else return true;
    }


}
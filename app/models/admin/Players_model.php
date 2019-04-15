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
        $this->db->insert('players', $data);
        $this->db->insert('users', $user_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) return false;
        return true;
    }

}
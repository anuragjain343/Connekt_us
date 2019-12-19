<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    function contactUs($data,$table){
        $query = $this->db->insert($table,$data);
        if($query){
            return TRUE;
        }
        return FALSE;
    }
    
}
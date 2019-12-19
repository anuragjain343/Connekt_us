<?php
class Options_model extends CI_Model {

    function getTNC($where,$table){
        $query =$this->db->select('option_value')
        ->where($where)->get($table);
        if($query->num_rows()){
           $row = $query->row();
           //pr($data);

           $link['link'] = base_url('uploads/pdf/').$row->option_value;
           return $link;
        }
            return FALSE;
       }

        
}//ENd Class
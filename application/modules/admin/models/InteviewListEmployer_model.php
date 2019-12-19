<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InteviewListEmployer_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = REQUESTS;
    var $column_order = array('request.requestId','request.request_by','request.request_for','interview.interviewer_name','interview.location','interview.date','is_finished'); //set
    var $column_search = array('request.request_by','request.request_for','interview.interviewer_name','interview.location','interview.date','is_finished'); //set column field database for datatable searchable
    var $order = array('request.requestId' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }
   
    //prepare post list query
    private function posts_get_query($userId,$userType){
     $emplyer_id = decoding($this->uri->segment(4));
        $sel_fields = array_filter($this->column_order);
       // echo $emplyer_id;
        //$this->db->select($sel_fields);

       $this->db->select('`interview`.`interviewer_name`,`interview`.`location`,`interview`.`date`,`interview`.`time`,`request`.`requestId`,`request`.`is_finished`,`request`.`request_by`,`request`.`request_for`,`user`.`fullName`,`user_for`.`fullName` as Name,`user`.`userType`');
       $this->db->join('`users` `user`','`user`.`userId` = `request`.`request_by`');
       $this->db->join('`users` `user_for` ','`user_for`.`userId` = `request`.`request_for`');
       $this->db->join('`interviews` `interview`','`interview`.`request_id` = `request`.`requestId`');
        $this->db->from('`requests` `request`');
        if($userType == 'business'){
        $this->db->where('request_by',$userId);
        }else{
        $this->db->where('request_for',$userId);
        }
        $i = 0;

        foreach ($this->column_search as $emp) // loop column 
        {
            if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
                $_POST['search']['value'] = $_POST['search']['value'];
            } else
                $_POST['search']['value'] = '';

            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start();
                    $this->db->like(($emp), $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like(($emp), $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
            }
            if(!empty($this->where))
                $this->db->where($this->where); 

            $count_val = count($_POST['columns']);
            for($i=1;$i<=$count_val;$i++){ 

                if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                }else if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                } 
            }
            if(isset($_POST['order'])) // here order processing
            {
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            } 
            else if(isset($this->order))
            {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
    }

    function get_list($userId,$userType)
    {
        $this->posts_get_query($userId,$userType);
		if(isset($_POST['length']) && $_POST['length'] < 1) {
			$_POST['length']= '10';
		} else
		$_POST['length']= $_POST['length'];
		
		if(isset($_POST['start']) && $_POST['start'] > 1) {
			$_POST['start']= $_POST['start'];
		}
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function count_filtered($userId,$userType)
    {
        $this->posts_get_query($userId,$userType);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function activeInactive($table,$data){
        $where = array('companyId'=>$data['id']);
        $this->db->select('*');  
        $this->db->where($where);
        $sql = $this->db->get($table)->row();
        if($sql->status == 0){
            $this->db->update($table,array('status'=> '1'),$where);
            return TRUE;
        }else{
            $this->db->update($table,array('status'=> '0'),$where);
            return FALSE;
        }
    }

}
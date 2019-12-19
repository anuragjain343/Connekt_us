<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Job_viewers_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = JOBS;

    var $column_order = array('`u`.`profileImage`,`u`.`userId`,`u`.`fullName`'); //set

    var $column_search = array('u.fullName'); 
    //set column field database for datatable searchable
    var $order = array('jv.crd' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }
   
    //prepare post list query
    private function posts_get_query(){
        //pr($_POST);
        $sel_fields = array_filter($this->column_order);
        //$this->db->select($sel_fields);
       // pr($sel_fields);
        $profileImg = base_url().USER_THUMB;
        $defaultImg = base_url().DEFAULT_USER;
       $this->db->select('`u`.`userId`,`u`.`fullName`,(case 
                when( u.profileImage = "" OR u.profileImage IS NULL) 
                THEN "'.$defaultImg.'"
                ELSE
                concat("'.$profileImg.'",u.profileImage) 
               END ) as profileImage');

       $this->db->join('`job_views` `jv`','`jv`.`viewed_by_user_id` = `u`.`userId`');
       //$this->db->join('`specializations` `sp` ','`j`.`industry` = `sp`.`specializationId`');
     
       //$this->db->join('`users` `u`','`u`.`userId` = `j`.`posted_by_user_id`');
        $this->db->from('`users` `u`');
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
            if(!empty($this->where)){

                $this->db->where($this->where); 
            }
            if(!empty($_POST['job_id'])){
              $this->db->where('job_id',$_POST['job_id']); 
            }
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

    function get_list()
    {
        $this->posts_get_query();
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

    function count_filtered()
    {
        $this->posts_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

 

 

}
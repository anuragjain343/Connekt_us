<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saved_jobs_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = JOBS;

    var $column_order = array('j.jobId','j.job_type','j.job_title_id','j.job_location','j.industry','j.status'); //set

    var $column_search = array('jt.jobTitleName','sp.specializationName','j.job_location'); //set column field database for datatable searchable
    var $order = array('j.jobId' => 'DESC');  // default order
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
       $this->db->select('`j`.`jobId`,`j`.`job_type`,`j`.`job_title_id`,`j`.`job_location`,`j`.`industry`,`j`.`status`,`jt`.`jobTitleId`,`jt`.`jobTitleName`,`sp`.`specializationId`,`sp`.`specializationName`');
       $this->db->join('`job_titles` `jt`','`j`.`job_title_id` = `jt`.`jobTitleId`');
       $this->db->join('`specializations` `sp` ','`j`.`industry` = `sp`.`specializationId`');
        $this->db->join('`save_jobs` `sj` ','`sj`.`job_id` = `j`.`jobId`');
       //$this->db->join('`interviews` `interview`','`interview`.`request_id` = `request`.`requestId`');
        $this->db->from('`jobs` `j`');
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

            $this->db->where('save_by_user_id',$_POST['userId']); 
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
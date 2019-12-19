<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applied_jobList_model extends CI_Model {

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
       $this->db->select('`j`.`jobId`,,`ja`.`job_application_status`,`j`.`job_type`,`j`.`job_title_id`,`j`.`job_location`,`j`.`industry`,`j`.`status`,`jt`.`jobTitleId`,`jt`.`jobTitleName`,`sp`.`specializationId`,`sp`.`specializationName`,`ja`.`applied_by_user_id`');
       $this->db->join('`job_titles` `jt`','`j`.`job_title_id` = `jt`.`jobTitleId`');
       $this->db->join('`specializations` `sp` ','`j`.`industry` = `sp`.`specializationId`');
        $this->db->join('`job_applicants` `ja` ','`ja`.`job_id` = `j`.`jobId`');
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

            $this->db->where('applied_by_user_id',$_POST['userId']); 
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

    function jobDetail($jobId,$job_type){
        $fields='';
        $default_logo = base_url().COMPANY_LOGO_DEFAULT;  
        $default_video_thumb = '';  
        $logo = base_url().COMPANY_LOGO; 
        $logoThumb = base_url().'uploads/screening_video_thumb/'; 
    


        if($job_type==2){

            $this->db->select('j.jobId,j.status,u.businessName,jt.jobTitleName,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,,s.specializationName as industry,s.specializationId as industry_id,pj.why_they_should_join,pj.question_description,pj.is_job_video_screening,pj.is_video_url,pj.video,pj.video_thumb_image,(case 
            when( um.company_logo = "" OR um.company_logo IS NULL) 
            THEN "'.$default_logo.'"
            ELSE
            concat("'.$logo.'",um.company_logo) 
            END ) as company_logo,
            (case 
            when( pj.video_thumb_image = "0" OR pj.video_thumb_image IS NULL OR pj.video_thumb_image = "") 
            THEN "'.$default_video_thumb.'"
            ELSE
            concat("'.$logoThumb.'",pj.video_thumb_image) 
            END ) as video_thumb_image');
        }   

        if($job_type==1){
            
            $this->db->select('j.jobId,j.status,u.businessName,jt.jobTitleName,j.job_type,j.posted_by_user_id,j.job_title_id,j.job_location,j.job_city,j.job_state,j.job_country,j.job_latitude,j.job_longitude,j.employment_type,salary_from,salary_to,j.explain_opportunity,j.crd,,s.specializationName as industry,s.specializationId as industry_id,(case 
            when( um.company_logo = "" OR um.company_logo IS NULL) 
            THEN "'.$default_logo.'"
            ELSE
            concat("'.$logo.'",um.company_logo) 
            END ) as company_logo');
        }

        $this->db->from(JOBS .' as j');
        $this->db->join(JOB_TITLES. ' as jt', "jt.jobTitleId = j.job_title_id","left");

        if($job_type==2){    
            $this->db->join(PREMIUM_JOBS. ' as pj', "pj.job_id = j.jobId","left"); 
        }

        $this->db->join(USER_META. ' as um', "user_id = j.posted_by_user_id","left"); 
        $this->db->join(USERS. ' as u', "u.userId = j.posted_by_user_id","left"); 
        $this->db->join(SPECIALIZATIONS. ' as s', "s.specializationId = j.industry","left");
        $this->db->where('j.jobid',$jobId);
        $result = $this->db->get(); 
        $res = $result->row(); 
        if($job_type==2){

            if (filter_var($res->video, FILTER_VALIDATE_URL)){ 
                    $res->video = $res->video;
            }else{
                $res->video = base_url().'/uploads/screening_video/'.$res->video;
            }
        }
       //$res->applicants     = $this->get_applicants($res->jobId,$select='');
       //$res->shortlisted    = array();
       //$res->total_view     = $this->common_model->get_total_count(JOB_VIEWS, $where=array('job_id'=>$jobId));
       return $res;
    }

}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('id')) {
            redirect('admin'); 
        }
        
    }

    public function index(){

        $data['userType'] = '';
        $data['title'] = 'Jobs';
        $data['count'] = $this->common_model->get_total_count(JOBS,'');

        $this->load->admin_render('jobsList', $data, '');
    }



    //For user listing via ajax
    public function getJobsList() {

      $this->load->model('jobs_model');
     //$this->users_model->set_data(array('userType'=>$userType)); 

        $userId     = $this->input->post('userId');
        $userType   = $this->input->post('user_type');
        $job_type   = $this->input->post('job_type');
        // pr($job_type);
        if(!empty($job_type)){    
          $where = array('job_type'=>$job_type);
          $this->jobs_model->set_data($where);
        }
        // $this->jobs_model->set_data($where);
        // $jobType ='1';
        //echo $userId;die;
        // echo $this->uri->segment(4);
        $list = $this->jobs_model->get_list($userId,$userType); 
        //pr($list);
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
            $row[] = display_placeholder_text($get->jobId);
            $row[] = display_placeholder_text($get->businessName);
            $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = display_placeholder_text($get->specializationName);
            if($get->job_type==1){
                $get->job_type ='Basic';
            }else{
                $get->job_type ='Premium';
            }
            $row[] = display_placeholder_text($get->job_type);
             $row[] = display_placeholder_text($get->fullName);

            $row[] = display_placeholder_text($get->job_location);
           // $row[] = display_placeholder_text($get->status);
            
            $encoded = encoding($get->jobId);
            if($get->status == 1){
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Aactive';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
          
                 $class = 'fa fa-check';
            }
           
            $viewUrl = base_url().'admin/jobs/JobDetail/'.$encoded;

            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
            $dltUrl = base_url()."admin/users/deleteJob";
            $clkDelete = "deleteFn('".JOBS."','jobId','".$encoded."','Job','".$dltUrl."')" ;
            //$newctrl = base_url().'admin/jobs/editJob/'.$encoded.'/'.$get->job_type;
            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';
           //$action .= '<a href="'.$newctrl.'" title="edit" onclick=""  class="on-default edit-row table_action">'.'<i class="fa fa-edit" aria-hidden="true"></i>'.'</a>';
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->jobs_model->count_all(),
            "recordsFiltered" => $this->jobs_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);

    }//End function

       public function JobDetail(){
        $this->load->model('jobs_model');
        $id = decoding($this->uri->segment('4'));
        //pr($id);
        $this->load->model('users_model');
        $jobs = $this->common_model->getsingle(JOBS, $where=array('jobId'=>$id), $fld = NULL, $order_by = '', $order = '') ;

        $data['detail'] = $this->jobs_model->jobDetail($id,$jobs->job_type);
      
        $data['admin_scripts'] = array('custom/js/review.js');
        $this->load->admin_render('JobDetail',$data);

    }//End function 

  /*  public function editJob(){
      $this->load->model('jobs_model');
      $id = decoding($this->uri->segment('4'));
      $type = $this->uri->segment('5');
      $this->load->model('users_model');
      $jobs = $this->common_model->getsingle(JOBS, $where=array('jobId'=>$id), $fld = NULL, $order_by = '', $order = '') ;
      $data['detail'] = $this->jobs_model->jobDetail($id,$jobs->job_type);
      $data['admin_scripts'] = array('custom/js/review.js','plugins/ionslider/ion.rangeSlider.min.js','plugins/bootstrap-slider/bootstrap-slider.js');

      $data['jobTitleData']= $this->jobs_model->getJobTitle_data('');
      $data['specilizationData']= $this->jobs_model->getSpecilization_data('');
      $data['employement_type'] = employement_type();
   // pr($data['employement_type']);

      if($type=='Premium'){
        $this->load->admin_render('editPremiumJob',$data);
      }else if($type=='Basic'){
        $this->load->admin_render('editBasicJob',$data);
      }else{
        redirect('/admin');
      }
      
    }*/




}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('id')) {
            redirect('admin'); 
        }
        
    }

    public function allBusiness(){

        $data['userType'] = 'business';
        $data['title'] = 'Business';
        $data['count'] = $this->common_model->get_total_count(USERS,array('userType'=>'business')); 
        $this->load->admin_render('usersList', $data, '');
    }

    //to get all individual user who favourites the business user
    public function getFavouritesList(){

        $this->load->model('favourites_list_model'); 
        $userType = $this->input->post('userType');
        $userId = $this->input->post('userId');
        $this->favourites_list_model->set_data($userId,$userType);
        $list = $this->favourites_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<img src="'.$get->profileImage.'" class="ListImage">';
            $row[] = display_placeholder_text($get->fullName);
            $encoded = encoding($get->userId);
            $viewUrl = base_url().'admin/users/businessDetail/'.$encoded;
            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->favourites_list_model->count_all(),
                "recordsFiltered" => $this->favourites_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }


    //to get all individual user who recommended the business user
    public function getRecommendsList(){

        $this->load->model('recommends_list_model'); 
        $userType = $this->input->post('userType');
        $userId = $this->input->post('userId');
        $this->recommends_list_model->set_data($userId,$userType);
        $list = $this->recommends_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<img src="'.$get->profileImage.'" class="ListImage">';
            $row[] = display_placeholder_text($get->fullName);
            $encoded = encoding($get->userId);
            $viewUrl = base_url().'admin/users/businessDetail/'.$encoded;
            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->recommends_list_model->count_all(),
                "recordsFiltered" => $this->recommends_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }


    public function userDetail($userId){

        $this->load->model('users_model');
        $id = decoding($userId);
        $userData = $this->common_model->getsingle(USERS, array('userId'=>$id));
        $data['admin_scripts'] = array('custom/js/review.js');
        if($userData->userType == 'individual'){
            $data['detail'] = $this->users_model->getIndividualDetail($id);
            $this->load->admin_render('individualDetail',$data);
        }else{
            $data['detail'] = $this->users_model->getBusinessDetail($id);
            $this->load->admin_render('businessDetail',$data);
        }
    }

    public function requests(){
        $data['title'] = 'Requests';
        $data['count'] = $this->common_model->get_total_count(REQUESTS); 
         $this->load->admin_render('requestList',$data);
    } 


    public function contactUs(){
     	$data['title'] = 'Requests';
        $data['count'] = $this->common_model->get_total_count(CONTACT_US); 
         $this->load->admin_render('ContactUS',$data);
    }


    //For business Detail
    public function businessDetail(){

        $id = decoding($this->uri->segment('4'));
        $this->load->model('users_model');
        $data['detail'] = $this->users_model->getBusinessDetail($id);
        //pr($data);
        $data['admin_scripts'] = array('custom/js/review.js');
        $this->load->admin_render('businessDetail',$data);

    }//End function 


    public function contactDetail(){

        $id = decoding($this->input->post('id'));
        //echo $id;
        $this->load->model('users_model');
        $data['detail'] = $this->common_model->getsingle(CONTACT_US,array('contactUsId'=>$id));
        //$data['admin_scripts'] = array('custom/js/review.js');
        $this->load->view('contactDetail',$data);

    }//End function

    //to get all individual user who review the business user
    function getReviewsList(){

        $this->load->model('reviews_list_model');
        $offset = $this->input->post('offset');
        $limit = $this->input->post('limit');
        $data['userId'] = $this->input->post('userId');
        $data['userType'] = $this->input->post('userType');
        

        $data['total_count'] = $this->reviews_list_model->countReview($data);
        $data['review_list'] = $this->reviews_list_model->reviewDetail($data,$limit,$offset);
        //pr($data['review_list']);
        //set offset for show hide div
        $data['offset'] = $offset;
        //load view for grid view
        $this->load->view('review_list',$data);
        
    }


    function getReviewsListOpposite(){

        $this->load->model('reviews_list_model');
        $offset = $this->input->post('offset');
        $limit = $this->input->post('limit');
        $data['userId'] = $this->input->post('userId');
        $data['userType'] = $this->input->post('userType');
    
        $data['total_count'] = $this->reviews_list_model->countReviewOpposite($data);
        $data['review_list'] = $this->reviews_list_model->reviewDetailOpposite($data,$limit,$offset);
        //pr($data['review_list']);
        //set offset for show hide div
        $data['offset'] = $offset;
        //load view for grid view
        $this->load->view('review_list',$data);
        
    }



    public function allIndividual(){
        $data['userType'] = 'individual';
        $data['title'] = 'Individuals';
        $data['count'] = $this->common_model->get_total_count(USERS,array('userType'=>'individual')); 
        $this->load->admin_render('usersList', $data, '');
    }

	public function progress(){
		 $this->load->model('Users_model');
        $data['title'] = 'progress';
        $requestId = decoding($this->uri->segment(4));
        $data['requestData'] = $this->Users_model->getInterviewRequest(array('requestId'=>$requestId));

        $data['count'] = $this->common_model->get_total_count(USERS,array('userType'=>'individual')); 
        //pr($data['requestData']);
        $this->load->admin_render('progress', $data, '');
	}

    //For user listing via ajax
    public function getUsersList() {

        $this->load->model('users_model');
        $userType = $this->input->post('userType');
        $this->users_model->set_data(array('userType'=>$userType)); 
        $list = $this->users_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            if(!empty($get->profileImage)){
                $imgPath = base_url().USER_THUMB.$get->profileImage;
            }else{
                $imgPath = base_url().DEFAULT_USER;
            }
            $row[] = $no;
            $row[] = '<img src="'.$imgPath.'" class="ListImage">';
            if($userType == 'business'){
                $row[] = display_placeholder_text(wordwrap($get->businessName,25,"<br>\n"));

            }
             if($get->isActive == 0){
                $showText = "(<span style='color:red;'>Deactivated</span>)";
             }else{
                $showText = "";
             }
            $row[] = display_placeholder_text(wordwrap($get->fullName,25,"<br>\n")).' '.$showText;
            $row[] = display_placeholder_text($get->email);

            if($userType == 'individual'){
                $row[] = display_placeholder_text($get->value);
                $row[] = display_placeholder_text($get->strength);
            }
            $encoded = encoding($get->userId);
            if($get->status){
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-check';
            }
            if($userType == 'business'){
                $viewUrl = base_url().'admin/users/businessDetail/'.$encoded;
            }else{
                $viewUrl = base_url().'admin/users/individualDetail/'.$encoded;
            }
            $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';


            $dltUrl = base_url()."admin/users/deleteUser";
            $clkDelete = "deleteFn('".USERS."','userId','".$encoded."','user','".$dltUrl."')" ;
          

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

          
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->users_model->count_all(),
            "recordsFiltered" => $this->users_model->count_filtered(),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
    }//End function


    public function getInterviewList() {

        $this->load->model('inteviewList_model');
        //$this->users_model->set_data(array('userType'=>$userType)); 
        $list = $this->inteviewList_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
           $row[] = '<b><a href="'.base_url().'admin/users/businessDetail/'.encoding($get->request_by).'">'.display_placeholder_text($get->fullName).'</a></b>';
            $row[] = '<b><a href="'.base_url().'admin/users/businessDetail/'.encoding($get->request_for).'">'.display_placeholder_text($get->Name).'</a></b>';

            $row[] = display_placeholder_text($get->date.' '.$get->time);
            $row[] = display_placeholder_text($get->interviewer_name);
           // $row[] = display_placeholder_text($get->location);
            $encoded = encoding($get->requestId);
            $dltUrl = base_url()."admin/users/deleteRequest";
            $clkDelete = "deleteFn('".REQUESTS."','requestId','".$encoded."','Request','".$dltUrl."')" ;
            
            if($get->is_finished == 1){
                 $req = status_color($get->is_finished); 
                 $status = '<span style="color:'.$req.'">'.'Completed'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }elseif($get->is_finished == 2){
                 $req = status_color($get->is_finished); 
                 $status = '<span style="color:'.$req.'">'.'Deleted'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
          
                 $class = 'fa fa-check';
            }else{
                $req = status_color($get->is_finished); 
                 $status = '<span style="color:'.$req.'">'.'Running'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-check';
            }

             $viewUrl = base_url().'admin/users/progress/'.$encoded;
            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
            $dltUrl = base_url()."admin/users/deleteUser";
            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';
           
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->inteviewList_model->count_all(),
            "recordsFiltered" => $this->inteviewList_model->count_filtered(),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
    }//End function



    public function getContactUsList() {
        $this->load->model('contactList_model');
        //$this->users_model->set_data(array('userType'=>$userType)); 

        $list = $this->contactList_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = display_placeholder_text($get->name);
            $row[] = display_placeholder_text($get->email);

            $row[] = display_placeholder_text($get->subject);
            //$row[] = display_placeholder_text($get->interviewer_name);
           // $row[] = display_placeholder_text($get->location);
            $encoded = encoding($get->contactUsId);
            $dltUrl = base_url()."admin/users/deleteContact";
            $clkDelete = "deleteFn('".CONTACT_US."','contactUsId','".$encoded."','Contact','".$dltUrl."')" ;
            
            

            $viewUrl = "viewModel('admin/users','contactDetail','".$encoded."')";

            /*$Clkview = "contactUs('".CONTACT_US."','contactUsId','".$encoded."','Contact','".$viewUrl."')" ;*/

            $action .= '<a href="javascript:void(0)" class="on-default edit-row table_action" onclick="'.$viewUrl.'"  title="View detail">'.VIEW_ICON.'</a>';

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';
           
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->contactList_model->count_all(),
            "recordsFiltered" => $this->contactList_model->count_filtered(),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
    }//End function


    public function getInterviewListByEmployer(){

        $this->load->model('inteviewListEmployer_model');
        //$this->users_model->set_data(array('userType'=>$userType)); 

        $userId = $this->input->post('userId');
        $userType = $this->input->post('user_type');
        //echo $userId;die;
       // echo $this->uri->segment(4);
        $list = $this->inteviewListEmployer_model->get_list($userId,$userType); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<b><a href="'.base_url().'admin/users/businessDetail/'.encoding($get->request_for).'">'.display_placeholder_text($get->Name).'</a></b>';
            $row[] = display_placeholder_text($get->date.' '.$get->time);
            $row[] = display_placeholder_text($get->location);
            $row[] = display_placeholder_text($get->interviewer_name);
           // $row[] = display_placeholder_text($get->location);
            
            $encoded = encoding($get->requestId);
            if($get->is_finished == 1){
                 $req = status_color($get->is_finished); 
                 $status = '<span style="color:'.$req.'">'.'Completed'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }elseif($get->is_finished == 2){
                 $req = status_color($get->is_finished); 
                 $status = '<span style="color:'.$req.'">'.'Deleted'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
          
                 $class = 'fa fa-check';
            }else{
                $req = status_color($get->is_finished); 
                 $status = '<span style="color:'.$req.'">'.'Running'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-check';
            }
           
            $viewUrl = base_url().'admin/users/progress/'.$encoded;
            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
            $dltUrl = base_url()."admin/users/deleteUser";
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->inteviewListEmployer_model->count_all(),
            "recordsFiltered" => $this->inteviewListEmployer_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
    }//End function
    
    public function getEmployerJobList(){
       // pr($_POST);
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
            $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = display_placeholder_text($get->specializationName);
            if($get->job_type==1){
                $get->job_type ='Basic';
            }else{
                $get->job_type ='Premium';
            }
            $row[] = display_placeholder_text($get->job_type);

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

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';
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

    }

    function appliedJobList(){
      $this->load->model('applied_jobList_model');
     //$this->users_model->set_data(array('userType'=>$userType)); 
        $userId     = $this->input->post('userId');
        $userType   = $this->input->post('user_type');
        $job_type   = $this->input->post('job_type');
        // pr($job_type);
        if(!empty($job_type)){    
          $where = array('`j`.`job_type`'=>$job_type);
          $this->applied_jobList_model->set_data($where);
        }
        // $this->jobs_model->set_data($where);
        // $jobType ='1';
        //echo $userId;die;
        // echo $this->uri->segment(4);
        $list = $this->applied_jobList_model->get_list($userId,$userType); 
        
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
            $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = display_placeholder_text($get->specializationName);
            if($get->job_type==1){
                $get->job_type ='Basic';
            }else{
                $get->job_type ='Premium';
            }
            $row[] = display_placeholder_text($get->job_type);

            $row[] = display_placeholder_text($get->job_location);
           // $row[] = display_placeholder_text($get->status);
            
            $encoded = encoding($get->jobId);
            if($get->job_application_status == 0){
                 $req = status_color($get->status); 
                 $status = '<span style="color:#FFA500">'.'Applied'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else if($get->job_application_status == 1){
                 $req = status_color($get->status); 
                 $status = '<span style="color:green">'.'Shortlisted'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else if($get->job_application_status == 2){
                 $req = status_color($get->status); 
                 $status = '<span style="color:green">'.'Rejected'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else if($get->job_application_status == 3){
                 $req = status_color($get->status); 
                 $status = '<span style="color:green">'.'Request'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else if($get->job_application_status == 4){
                 $req = status_color($get->status); 
                 $status = '<span style="color:green">'.'Accept Request'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else if($get->job_application_status == 5){
                 $req = status_color($get->status); 
                 $status = '<span style="color:green">'.'Decline Request'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else if($get->job_application_status == 6){
                 $req = status_color($get->status); 
                 $status = '<span style="color:green">'.'Offered'.'</span>';
                 $row[] = $status;
                 $title = 'Applied';
                 //$clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','User')" ;
                 $class = 'fa fa-times';
            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Not Offered'.'</span>';
                 $row[] = $status;
                 $title = 'Hired';
          
                 $class = 'fa fa-check';
            }
           
            $viewUrl = base_url().'admin/jobs/JobDetail/'.$encoded;

            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
            /*$dltUrl = base_url()."admin/users/deleteJob";
            $clkDelete = "deleteFn('".JOBS."','jobId','".$encoded."','Job','".$dltUrl."')" ;

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';*/
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->applied_jobList_model->count_all(),
            "recordsFiltered" => $this->applied_jobList_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output); 
    }

 

  
    // delete user
    public function deleteUser(){

        $id = decoding($this->input->post('id'));
        $where = array('userId'=>$id);
        $res = $this->common_model->deleteData(USERS,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;
    }

    function deleteJob(){

        $id = decoding($this->input->post('id'));
        $where = array('jobId'=>$id);
        $res = $this->common_model->deleteData(JOBS,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }

    public function deleteReview(){

        $id = decoding($this->input->post('id'));
        $where = array('reviewId'=>$id);
        $res = $this->common_model->deleteData(REVIEWS,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;
    }
  


    //For user active inactive option
    public function userStatus(){
       
        $id = $this->uri->segment(4);
        $where = array('userId'=>$id);
        $res = $this->common_model->changeStatus(USERS,$where);
        if($res==1){
            $this->session->set_flashdata('success', 'User inactivated successfully');
        } elseif($res==2){
            $this->session->set_flashdata('success', 'User activated successfully');
        }else{
            $this->session->set_flashdata('warning', 'Please try again');
        }
        redirect('admin/users/allUsers');
       
    }//End function


    //For individual Detail
    public function individualDetail(){

        $id = decoding($this->uri->segment('4'));
        $this->load->model('users_model');
        $data['detail'] = $this->users_model->getIndividualDetail($id);
        $data['admin_scripts'] = array('custom/js/review.js?v=6');
        $this->load->admin_render('individualDetail',$data);

    }//End function



    //For adding a strength after form submit
    public function addStrength(){

        $this->form_validation->set_rules('strengthName','Strength name','required|callback__alpha_spaces_check',array('required'=>'Please enter strength '));
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{
            
            $strengthName = ucwords($this->input->post('strengthName'));
            $where = array('strengthName'=>$strengthName);
            $isExist = $this->common_model->is_data_exists(STRENGTHS,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Strength name already exist'); //error msg 
            }else{
                $dataVal['strengthName'] = $strengthName;
                $isUpdate = $this->common_model->insertData(STRENGTHS,$dataVal);
                $response = array('status' => 1, 'message' => 'Strength added successfully', 'url' => base_url('admin/users/allStrength')); //success msg
            }
        }
        echo json_encode($response); 

    }//End function


    //For list all strengths
    public function allStrength(){

         $data['count'] = $this->common_model->get_total_count(STRENGTHS);
         $this->load->admin_render('strengthList', $data);

    }//End function


   //For strength listing via ajax
    public function getStrengthList() {

        $this->load->model('strength_list_model'); 
        $list = $this->strength_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = display_placeholder_text($get->strengthName);
            $encoded = encoding($get->strengthId);
            $dltUrl = base_url()."admin/users/deleteStrength";
            $clkDelete = "deleteFn('".STRENGTHS."','strengthId','".$encoded."','Strength','".$dltUrl."')" ;
            $clkEdit =  "editFn('admin/users','editStrengthData','".$encoded."')" ;
            $req = status_color($get->status);
            if($get->status){
                 $title = 'Inactive';
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $class = 'fa fa-times';
            }else{
                 $title = 'Active';
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $class = 'fa fa-check';
            }
             
            $row[] = $status;
            $clkStatus = "statusFn('".STRENGTHS."','strengthId','".$encoded."','$get->status','Strength')";
            $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Edit" onclick="'.$clkEdit.'" class="on-default edit-row table_action">'.'<i class="fa fa-pencil" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->strength_list_model->count_all(),
                "recordsFiltered" => $this->strength_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }//End function


    //For deleting a strength
    public function deleteStrength(){

        $id = decoding($this->input->post('id'));
        $where = array('strengthId'=>$id);
        $res = $this->common_model->deleteData(STRENGTHS,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }//End function

    
    //For getting strength data for edit
    public function editStrengthData(){

        $id = decoding($this->input->post('id')); 
        $where = array('strengthId'=>$id); 
        $user_data = $this->common_model->getsingle(STRENGTHS, $where); 
        if(!empty($user_data)){

            $data['results'] = $user_data; 
            $this->load->view('editStrength', $data);
        }

    }//End function

    //For updating a strength after form submit
    public function updateStrength(){

        $this->form_validation->set_rules('strengthName','Strength name','required|callback__alpha_spaces_check',array('required'=>'Please enter strength '));
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $strengthName = ucwords($this->input->post('strengthName'));
            $strengthId = $this->input->post('strengthId');

            $where = array('strengthName'=>$strengthName,'strengthId !='=>$strengthId);
            $isExist = $this->common_model->is_data_exists(STRENGTHS,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Strength name already exist'); //error msg 
            }else{
           
                $dataVal['strengthName'] = $strengthName;

                $w = array('strengthId'=>$strengthId);
                $isUpdate = $this->common_model->updateFields(STRENGTHS,$dataVal,$w);
                $response = array('status' => 1, 'message' => 'Strength updated successfully', 'url' => base_url('admin/users/allStrength')); //success ms
            }
        }
        echo json_encode($response); 

    }//End function


    //For adding a value after form submit
    public function addValue(){

        $this->form_validation->set_rules('valueName','Value name','required|callback__alpha_spaces_check',array('required'=>'Please enter value'));
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{
            
            $valueName = ucwords($this->input->post('valueName'));
            $where = array('valueName'=>$valueName);
            $isExist = $this->common_model->is_data_exists(VALUE,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Value already exist'); //error msg 
            }else{
                $dataVal['valueName'] = $valueName;
                $isUpdate = $this->common_model->insertData(VALUE,$dataVal);
                $response = array('status' => 1, 'message' => 'Value added successfully', 'url' => base_url('admin/users/allValue')); //success msg
            }
        }
        echo json_encode($response); 
    }//End function


    //For list all value
    public function allValue(){

         $data['count'] = $this->common_model->get_total_count(VALUE);
         $this->load->admin_render('valueList', $data);

    }//End function


   //For value listing via ajax
    public function getvalueList() {

        $this->load->model('value_list_model'); 
        $list = $this->value_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = display_placeholder_text($get->valueName);
            $encoded = encoding($get->valueId);
            $dltUrl = base_url()."admin/users/deleteValue";
            $clkDelete = "deleteFn('".VALUE."','valueId','".$encoded."','Value','".$dltUrl."')" ;
            $clkEdit =  "editFn('admin/users','editValueData','".$encoded."')" ;
            $req = status_color($get->status);
            if($get->status){
                 $title = 'Inactive';
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $class = 'fa fa-times';
            }else{
                 $title = 'Active';
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $class = 'fa fa-check';
            }
             
            $row[] = $status;
            $clkStatus = "statusFn('".VALUE."','valueId','".$encoded."','$get->status','Value')";
            $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Edit" onclick="'.$clkEdit.'" class="on-default edit-row table_action">'.'<i class="fa fa-pencil" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->value_list_model->count_all(),
                "recordsFiltered" => $this->value_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }//End function

    //For deleting a value
    public function deleteValue(){

        $id = decoding($this->input->post('id'));
        $where = array('valueId'=>$id);
        $res = $this->common_model->deleteData(VALUE,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }//End function

    
    //For getting value data for edit
    public function editValueData(){

        $id = decoding($this->input->post('id')); 
        $where = array('valueId'=>$id); 
        $user_data = $this->common_model->getsingle(VALUE, $where); 
        if(!empty($user_data)){

             $data['results'] = $user_data; 
             $this->load->view('editValue', $data);
         }

    }//End function

    //For updating a value after form submit
    public function updateValue(){

        $this->form_validation->set_rules('valueName','value name','required|callback__alpha_spaces_check');
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $valueName = ucwords($this->input->post('valueName'));
            $valueId = $this->input->post('valueId');

            $where = array('valueName'=>$valueName,'valueId !='=>$valueId);
            $isExist = $this->common_model->is_data_exists(VALUE,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Value name already exist'); //error msg 
            }else{
           
                $dataVal['valueName'] = $valueName;

                $w = array('valueId'=>$valueId);
                $isUpdate = $this->common_model->updateFields(VALUE,$dataVal,$w);
                $response = array('status' => 1, 'message' => 'Value updated successfully', 'url' => base_url('admin/users/allValue')); //success msg
            }
        }
        echo json_encode($response); 

    }//End function


     //For adding a specialization after form submit
    public function addSpecialization(){

        $this->form_validation->set_rules('specializationName','specialization name','required|callback__alpha_spaces_check',array('required'=>'Please enter specialization '));
        $this->form_validation->set_rules('userType','user type','required',array('required'=>'Please select user type'));
        if($this->form_validation->run() == FALSE){
            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{
            $specializationName = ucwords($this->input->post('specializationName'));
            $userType = $this->input->post('userType');
            $where = array('specializationName'=>$specializationName,'userType'=>$userType);
            $isExist = $this->common_model->is_data_exists(SPECIALIZATIONS,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Specialization already exist'); //error msg 
            }else{
                $dataVal['specializationName'] = $specializationName;
                $dataVal['userType'] = $userType;
                $isUpdate = $this->common_model->insertData(SPECIALIZATIONS,$dataVal);
                $response = array('status' => 1, 'message' => 'Specialization added successfully', 'url' => base_url('admin/users/allSpecialization')); //success msg
            }
        }
        echo json_encode($response); 

    }//End function


    //For list all specialization
    public function allSpecialization(){

        $data['count'] = $this->common_model->get_total_count(SPECIALIZATIONS);
        $this->load->admin_render('specializationList', $data);

    }//End function


   //For specialization listing via ajax
    public function getSpecializationList() {

        $this->load->model('specialization_list_model'); 
        $list = $this->specialization_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = display_placeholder_text($get->specializationName);
            if($get->userType == 'both'){
                $userType = "Both (Individual & Business)";
            }else{
                $userType = $get->userType;
            }
            $row[] = display_placeholder_text(ucfirst($userType));
            $encoded = encoding($get->specializationId);
            $dltUrl = base_url()."admin/users/deleteSpecialization";
            $clkDelete = "deleteFn('".SPECIALIZATIONS."','specializationId','".$encoded."','Specialization','".$dltUrl."')" ;
            $clkEdit =  "editFn('admin/users','editSpecializationData','".$encoded."')" ;
            $req = status_color($get->status);
            if($get->status){
                 $title = 'Inactive';
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $class = 'fa fa-times';
            }else{
                 $title = 'Active';
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $class = 'fa fa-check';
            }
             
            $row[] = $status;
            $clkStatus = "statusFn('".SPECIALIZATIONS."','specializationId','".$encoded."','$get->status','Specialization')";
            $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Edit" onclick="'.$clkEdit.'" class="on-default edit-row table_action">'.'<i class="fa fa-pencil" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->specialization_list_model->count_all(),
                "recordsFiltered" => $this->specialization_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }//End function

    //For deleting a specialization
    public function deleteSpecialization(){

        $id = decoding($this->input->post('id'));
        $where = array('specializationId'=>$id);
        $res = $this->common_model->deleteData(SPECIALIZATIONS,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }//End function

    
    //For getting specialization data for edit
    public function editSpecializationData(){

        $id = decoding($this->input->post('id')); 
        $where = array('specializationId'=>$id); 
        $user_data = $this->common_model->getsingle(SPECIALIZATIONS, $where); 
        if(!empty($user_data)){

             $data['results'] = $user_data; 
             $this->load->view('editSpecialization', $data);
         }

    }//End function

    //For updating a specialization after form submit
    public function updateSpecialization(){

        $this->form_validation->set_rules('specializationName','specialization name','required|callback__alpha_spaces_check',array('required'=>'Please enter specialization '));
        $this->form_validation->set_rules('userType','user type','required',array('required'=>'Please select user type'));
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $specializationName = ucwords($this->input->post('specializationName'));
            $specializationId = $this->input->post('specializationId');
            $userType = $this->input->post('userType');
            $where = array('specializationName'=>$specializationName,'specializationId !='=>$specializationId,'userType'=>$userType);
            $isExist = $this->common_model->is_data_exists(SPECIALIZATIONS,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Specialization name already exist'); //error msg 
            }else{
           
                $dataVal['specializationName'] = $specializationName;
                $dataVal['userType'] = $userType;
                $w = array('specializationId'=>$specializationId);
                $isUpdate = $this->common_model->updateFields(SPECIALIZATIONS,$dataVal,$w);
                $response = array('status' => 1, 'message' => 'Specialization updated successfully', 'url' => base_url('admin/users/allSpecialization')); //success msg
            }
        }
        echo json_encode($response); 

    }//End function

     //For adding a jobTitle after form submit
    public function addJobTitle(){

        $this->form_validation->set_rules('jobTitleName','Job title','required',array('required'=>'Please enter job title'));
        $this->form_validation->set_rules('userType','user type','required',array('required'=>'Please select user type'));
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{
            
            $jobTitleName = ucwords($this->input->post('jobTitleName'));
            $userType = $this->input->post('userType');
            $where = array('jobTitleName'=>$jobTitleName,'userType'=>$userType);
            $isExist = $this->common_model->is_data_exists(JOB_TITLES,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Job Title already exist'); //error msg 
            }else{
                $dataVal['jobTitleName'] = $jobTitleName;
                $dataVal['userType'] = $userType;
                $isUpdate = $this->common_model->insertData(JOB_TITLES,$dataVal);
                $response = array('status' => 1, 'message' => 'Job title added successfully', 'url' => base_url('admin/users/allJobTitle')); //success msg
            }
        }
        echo json_encode($response); 
    }//End function


    //For list all jobTitle
    public function allJobTitle(){

         $data['count'] = $this->common_model->get_total_count(JOB_TITLES);
         $this->load->admin_render('jobTitleList', $data);

    }//End function


   //For jobTitle listing via ajax
    public function getjobTitleList() {

        $this->load->model('jobTitle_list_model'); 
        $list = $this->jobTitle_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = display_placeholder_text($get->jobTitleName);
            if($get->userType == 'both'){
                $userType = "Both (Individual & Business)";
            }else{
                $userType = $get->userType;
            }
            $row[] = display_placeholder_text(ucfirst($userType));
            $encoded = encoding($get->jobTitleId);
            $dltUrl = base_url()."admin/users/deletejobTitle";
            $clkDelete = "deleteFn('".JOB_TITLES."','jobTitleId','".$encoded."','Job title','".$dltUrl."')" ;
            $clkEdit =  "editFn('admin/users','editJobTitleData','".$encoded."')" ;
            $req = status_color($get->status);
            if($get->status){
                 $title = 'Inactive';
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $class = 'fa fa-times';
            }else{
                 $title = 'Active';
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $class = 'fa fa-check';
            }
             
            $row[] = $status;
            $clkStatus = "statusFn('".JOB_TITLES."','jobTitleId','".$encoded."','$get->status','Job title')";
            $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

            $action .= '<a href="javascript:void(0)" title="Edit" onclick="'.$clkEdit.'" class="on-default edit-row table_action">'.'<i class="fa fa-pencil" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->jobTitle_list_model->count_all(),
                "recordsFiltered" => $this->jobTitle_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }//End function


    //For deleting a jobTitle
    public function deleteJobTitle(){

        $id = decoding($this->input->post('id'));
        $where = array('jobTitleId'=>$id);
        $res = $this->common_model->deleteData(JOB_TITLES,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }//End function 

    public function deleteRequest(){

        $id = decoding($this->input->post('id'));
        $where = array('requestId'=>$id);
        $res = $this->common_model->deleteData(REQUESTS,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }//End function


    public function deleteContact(){

        $id = decoding($this->input->post('id'));
        $where = array('contactUsId'=>$id);
        $res = $this->common_model->deleteData(CONTACT_US,$where);
        if($res){
            $response = 200;  
        }else{
            $response = 400;
        }
        echo $response;

    }//End function

    
    //For getting jobTitle data for edit
    public function editUserReview(){

        $id = decoding($this->input->post('id')); 
        $where = array('reviewId'=>$id); 
        $user_data = $this->common_model->getsingle(REVIEWS, $where); 
        if(!empty($user_data)){

             $data['results'] = $user_data; 
             $this->load->view('editUserReview', $data);
         }

    }//End function

   
  //For getting strength data for edit
    public function editJobTitleData(){
        $id = decoding($this->input->post('id')); 
        $where = array('jobTitleId'=>$id); 
        $user_data = $this->common_model->getsingle(JOB_TITLES, $where); 
        if(!empty($user_data)){

            $data['results'] = $user_data; 
            $this->load->view('editJobTitle', $data);
        }
    }//End function

    //For updating a jobTitle after form submit
    public function updateJobTitle(){

        $this->form_validation->set_rules('jobTitleName','Job title','required',array('required'=>'Please enter job title'));
        $this->form_validation->set_rules('userType','user type','required',array('required'=>'Please select user type'));
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $jobTitleName = ucwords($this->input->post('jobTitleName'));
            $jobTitleId = $this->input->post('jobTitleId');
            $userType = $this->input->post('userType');
            $where = array('jobTitleName'=>$jobTitleName,'jobTitleId !='=>$jobTitleId,'userType'=>$userType);
            $isExist = $this->common_model->is_data_exists(JOB_TITLES,$where);
            if($isExist){
                $response = array('status' => 0, 'message' =>'Job Title already exist'); //error msg 
            }else{
           
                $dataVal['jobTitleName'] = $jobTitleName;
                $dataVal['userType'] = $userType;
                $w = array('jobTitleId'=>$jobTitleId);
                $isUpdate = $this->common_model->updateFields(JOB_TITLES,$dataVal,$w);
                $response = array('status' => 1, 'message' => 'Job title updated successfully', 'url' => base_url('admin/users/allJobTitle')); //success msg
            }
        }
        echo json_encode($response); 

    }//End function


    public function updateReview(){

        $this->form_validation->set_rules('reviewText','Comment','required',array('required'=>'Please enter Comment'));
        
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $review = $this->input->post('reviewText');
            $upperLetter = first_letter_capital($review);
            $rating = $this->input->post('rating');
            $reviewId = $this->input->post('reviewId');
            $userType = $this->input->post('userType');
            $where = array('reviewId'=>$reviewId);
            $dataVal['comments'] = $upperLetter;
            $dataVal['rating'] = $rating;
            $isUpdate = $this->common_model->updateFields(REVIEWS,$dataVal,$where);
            $response = array('status' => 1, 'message' => 'Review updated successfully', 'url' => base_url('admin/users/allIndividual')); //success msg
        }
        echo json_encode($response); 

    }//End function


    function _alpha_spaces_check($string){

        if(alpha_spaces($string)){
            return true;
        }
        else{
            $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
            return FALSE;
        }
    }

    function sendNotification(){
        $data='';
       $this->load->admin_render('sendNotification', $data, '');     
    }

    function sendNotificationToAll(){
       $this->form_validation->set_rules('title','Notifucation title','required',array('required'=>'Please enter title'));
        $this->form_validation->set_rules('type','User type','required',array('required'=>'Please select user type'));
        $this->form_validation->set_rules('discription','Description','required',array('required'=>'Please enter description'));
        if($this->form_validation->run() == FALSE){
            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
            echo json_encode($response); die();
        }else{
            $title           = trim($this->input->post('title'));
            $type            = $this->input->post('type');
            $discription     = trim($this->input->post('discription'));

            if(strlen($discription)>400){
             
                $response = array('status' => 0, 'message' =>'Description should not be greater then 400.'); //error msg
             echo json_encode($response); die();
            }
            if(empty($type)){
             
             $response = array('status' => 0, 'message' =>'Please select user type.'); //error msg
             echo json_encode($response); die();
            }

            $this->send_admin_notification($title,$type,$discription);
             $response = array('status' => 1, 'message' =>'Notification send successfully.'); 
             echo json_encode($response); die();

        }
        echo json_encode($response); 

    }

     function send_admin_notification($title,$type,$discription){
        $dataInsert['title']            = $title;
        $dataInsert['userType']         = $type;
        $dataInsert['discription']      = $discription;
        $dataInsert['crd']              = datetime();
        
        $lastId = $this->common_model->insertData(ADMIN_BROADCAST_MSG,$dataInsert);

        shell_exec(SHELL_EXEC_PATH." bg_Notification send_broadcast_notification '".$lastId."' >>/home4/conneckt/public_html/dev.uconnekt.com.au/notification_log.txt &");
    }


   function getApplicatnsList(){
    // pr($_POST);
      $this->load->model('jobs_applicants_model');
        $userId     = $this->input->post('userId');
        $userType   = $this->input->post('user_type');
        $job_type   = $this->input->post('job_type');
        $job_id     = $this->input->post('job_id');
        if(!empty($job_type)){    
          $where = array('job_type'=>$job_type);
          $this->jobs_applicants_model->set_data($where);
        }
       
        $list = $this->jobs_applicants_model->get_list($userId,$userType,$job_id); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
           // $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = '<img src="'.$get->profileImage.'" class="ListImage">';
            $row[] = display_placeholder_text($get->fullName);
            if($get->job_application_status==0){
                $status = '<span style="color:#FFA500">'.'Applied'.'</span>';
                $get->job_application_status = $status;
            }else if($get->job_application_status==1){
                $status = '<span style="color:green">'.'Shortlisted'.'</span>';
                $get->job_application_status =$status;
            }else if($get->job_application_status==2){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==3){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==4){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==5){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==6){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==7){
                $get->job_application_status ='Shortlisted';
            }else{
               $get->job_application_status ='offered'; 
            }
            $row[] = display_placeholder_text($get->job_application_status);
            $encoded = encoding($get->userId);
          /*  if($get->status == 1){
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
            }*/
           
            $viewUrl = base_url().'admin/users/individualDetail/'.$encoded;

            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

           /* $dltUrl = base_url()."admin/users/deleteJob";
            $clkDelete = "deleteFn('".JOBS."','jobId','".$encoded."','Job','".$dltUrl."')" ;
            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';*/

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->jobs_applicants_model->count_all(),
            "recordsFiltered" => $this->jobs_applicants_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
   }

  function getShortlistedUser(){
      // pr($_POST);
      $this->load->model('jobs_applicants_model');
        $userId     = $this->input->post('userId');
        $userType   = $this->input->post('user_type');
        $job_type   = $this->input->post('job_type');
        $job_id     = $this->input->post('job_id');
        $job_status     = $this->input->post('job_status');
        if(!empty($job_type)){    
          $where = array('job_type'=>$job_type);
          $this->jobs_applicants_model->set_data($where);
        }
       
        $list = $this->jobs_applicants_model->get_list($userId,$userType,$job_id,$job_status); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
           // $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = '<img src="'.$get->profileImage.'" class="ListImage">';
            $row[] = display_placeholder_text($get->fullName);
            if($get->job_application_status==0){
                $status = '<span style="color:#FFA500">'.'Applied'.'</span>';
                $get->job_application_status = $status;
            }else if($get->job_application_status==1){
                $status = '<span style="color:green">'.'Shortlisted'.'</span>';
                $get->job_application_status =$status;
            }else if($get->job_application_status==2){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==3){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==4){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==5){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==6){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==7){
                $get->job_application_status ='Shortlisted';
            }else{
               $get->job_application_status ='offered'; 
            }
            $row[] = display_placeholder_text($get->job_application_status);
            $encoded = encoding($get->userId);
    
           
            $viewUrl = base_url().'admin/users/individualDetail/'.$encoded;

            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

         
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->jobs_applicants_model->count_all(),
            "recordsFiltered" => $this->jobs_applicants_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
  }

  // job viewr list 
   function getjobViewsUser(){
      // pr($_POST);
      $this->load->model('job_viewers_model');
        $userId     = $this->input->post('userId');
        $userType   = $this->input->post('user_type');
        $job_type   = $this->input->post('job_type');
        $job_id     = $this->input->post('job_id');
        $job_status = $this->input->post('job_status');

        if(!empty($job_type)){    
          $where = array('job_type'=>$job_type);
          $this->job_viewers_model->set_data($where);
        }
       
        $list = $this->job_viewers_model->get_list($job_id,$job_status); 
       // pr($list);
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
           // $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = '<img src="'.$get->profileImage.'" class="ListImage">';
            $row[] = display_placeholder_text($get->fullName);

           /* if($get->job_application_status==0){
                $status = '<span style="color:#FFA500">'.'Applied'.'</span>';
                $get->job_application_status = $status;
            }else if($get->job_application_status==1){
                $status = '<span style="color:green">'.'Shortlisted'.'</span>';
                $get->job_application_status =$status;
            }else if($get->job_application_status==2){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==3){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==4){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==5){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==6){
                $get->job_application_status ='Shortlisted';
            }else if($get->job_application_status==7){
                $get->job_application_status ='Shortlisted';
            }else{
               $get->job_application_status ='offered'; 
            }
            $row[] = display_placeholder_text($get->job_application_status);*/
            $encoded = encoding($get->userId);
    
           
            $viewUrl = base_url().'admin/users/individualDetail/'.$encoded;

            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

         
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->job_viewers_model->count_all(),
            "recordsFiltered" => $this->job_viewers_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
  }





    function saveProfilesList(){
     // pr($_POST);
        $this->load->model('profiles_save_model');
        $userId     = $this->input->post('user_id');
        $list = $this->profiles_save_model->get_list($userId); 
       
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
            $row[] = '<img src="'.$get->profileImage.'" class="ListImage">';
            $row[] = display_placeholder_text($get->fullName);
            if($get->status==0){
                $status = '<span style="color:#FFA500">'.'Inactive'.'</span>';
                $get->status = $status;
            }else{
               $get->status ='Active'; 
            }
            $row[] = display_placeholder_text($get->status);
            $encoded = encoding($get->userId);
          /*  if($get->status == 1){
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
            }*/
           
            $viewUrl = base_url().'admin/users/individualDetail/'.$encoded;

            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

          /*  $dltUrl = base_url()."admin/users/deleteJob";
            $clkDelete = "deleteFn('".JOBS."','jobId','".$encoded."','Job','".$dltUrl."')" ;

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';*/
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->profiles_save_model->count_all(),
            "recordsFiltered" => $this->profiles_save_model->count_filtered($userId),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
  }

     function getSavedJobList(){
     //pr($_POST);
      $this->load->model('saved_jobs_model');

        $userId     = $this->input->post('userId');
        $userType   = $this->input->post('user_type');
        $job_type   = $this->input->post('job_type');
        // pr($job_type);
        if(!empty($job_type)){    
          $where = array('job_type'=>$job_type);
          $this->saved_jobs_model->set_data($where);
        }
        // $this->jobs_model->set_data($where);
        // $jobType ='1';
        //echo $userId;die;
        // echo $this->uri->segment(4);
        $list = $this->saved_jobs_model->get_list($userId,$userType); 
        //pr($list);
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            $action ='';
            $no++;
            $row = array();
            //$row[] = $no;
            $row[] = display_placeholder_text($get->jobTitleName);
            $row[] = display_placeholder_text($get->specializationName);
            if($get->job_type==1){
                $get->job_type ='Basic';
            }else{
                $get->job_type ='Premium';
            }
            $row[] = display_placeholder_text($get->job_type);
             

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
          /*  $dltUrl = base_url()."admin/users/deleteJob";
            $clkDelete = "deleteFn('".JOBS."','jobId','".$encoded."','Job','".$dltUrl."')" ;

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';*/
            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
            "draw" => $_POST['draw'], 
            "recordsTotal" => $this->saved_jobs_model->count_all(),
            "recordsFiltered" => $this->saved_jobs_model->count_filtered($userId,$userType),
            "data" => $data
        );
       //output to json format
       echo json_encode($output);
   }

}
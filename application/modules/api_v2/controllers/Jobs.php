<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Jobs extends CommonService{
    
    public function __construct(){
        parent::__construct();
         $this->load->model('Jobs_model'); 
         //load user model
       
    }
    //CREATE BOTH TYPE JOB PREMIUM AND BASIC 
    function createJob_post(){
        $this->check_service_auth();
        $this->form_validation->set_rules('job_title_id', 'Job title', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Job Type', 'trim|required');
        $this->form_validation->set_rules('job_location', 'Job location', 'trim|required');
        $this->form_validation->set_rules('job_city', 'City', 'trim|required');
        $this->form_validation->set_rules('job_state', 'State', 'trim|required');
        $this->form_validation->set_rules('job_country', 'Country', 'trim|required');
        $this->form_validation->set_rules('job_latitude', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('job_longitude', 'Longitude', 'trim|required');
        $this->form_validation->set_rules('industry', 'Industry', 'trim|required');
        $this->form_validation->set_rules('employment_type', 'Employment Type', 'trim|required');
        $this->form_validation->set_rules('salary_from', 'Salary from', 'trim|required');
        $this->form_validation->set_rules('salary_to', 'Salary to ', 'trim|required');
        $this->form_validation->set_rules('explain_opportunity', 'Explain opportunity', 'trim|required');

        if($this->post('job_type')==2){ 

            $this->form_validation->set_rules('why_they_should_join', 'Why they should join', 'trim|required');
            /*$this->form_validation->set_rules('job_purchase_amount', 'Job Purchase Amount', 'trim|required');*/
            //$this->form_validation->set_rules('currency', 'Currency', 'trim|required');
            //$this->form_validation->set_rules('video', 'Video', 'trim|required');
           
        }
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='business'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            $title      = $this->post('job_title_id');
            $where      = array('jobTitleName'=>trim($title));
            $jobtitle   = $this->common_model->getsingle(JOB_TITLES,$where,$fld =NULL,$order_by = '',$order = '');
            if(!empty($jobtitle)){

                $data['job_title_id'] = $jobtitle->jobTitleId;

            }else{
                $data1['jobTitleName']      = trim($title);
                $data1['userType']          = 'both';
                $data1['type']              = 1;
                $data1['addedById']         = $this->authData->userId;
                $data['job_title_id']       = $this->common_model->insertData(JOB_TITLES, $data1);
            }

            $job_type                       = $this->post('job_type');

            if($job_type==2){

                $currency     = '$';
                $whereoption  = array('option_name'=>'purchase_amount');
                $purchaseData   = $this->common_model->getsingle(OPTIONS,$whereoption,$fld =NULL,$order_by = '',$order = '');

                if(empty($purchaseData)){
                    $response = array('status' =>FAIL,'message'=>'Invalide purchase amount.');
                    $this->response($response);die();
                }

                $job_purchase_amount            = $purchaseData->option_value;
                $whereBilling  = array('user_id'=>$this->authData->userId);

                $billingeData   = $this->Jobs_model->getBillingInfo($this->authData->userId);
                //pr($billingeData );
                if(empty($billingeData)){
                    $response = array('status' =>FAIL,'message'=>'You need to first complete your billing info.');
                    $this->response($response);die();
                }

            }

            if($job_type==1){

                $data['job_type']=1; 

            }else if($job_type==2){

                 $data['job_type']=2; 
                 $data['job_purchase_amount']= $job_purchase_amount; 

            }else{
                $response = array('status' => FAIL, 'message' => 'Invalide job type.');
                $this->response($response);die();
            }
                            

            if(isset($_POST['job_status'])==1 OR isset($_POST['job_status']) ==0){
                $data['status'] = $this->post('job_status');
            }
            if(!isset($_POST['job_status'])){
             $data['status'] =1 ;  
            }

              $specializationName = ucwords($this->post('industry'));
       
            if(!empty($specializationName)){

                $where    = array('specializationName'=>trim($specializationName));

                $jobspecializationName = $this->common_model->getsingle(SPECIALIZATIONS,$where,$fld =NULL,$order_by = '',$order = '');

                if(!empty($jobspecializationName)){

                    $data['industry'] = $jobspecializationName->specializationId;

                }else{

                    $datasp['specializationName']      = trim($specializationName);
                    $datasp['userType']                = 'both';
                    $datasp['type']                    = 1;
                    $datasp['addedById']               = $this->authData->userId;
                    $title_id                         = $this->common_model->insertData(SPECIALIZATIONS,$datasp);
                    $data['industry']  = $title_id;
                } 

            }
            
            $data['job_location']           = $this->post('job_location');
            $data['job_city']               = $this->post('job_city');
            $data['job_state']              = $this->post('job_state');
            $data['job_country']            = $this->post('job_country');
            $data['job_latitude']           = $this->post('job_latitude');
            $data['job_longitude']          = $this->post('job_longitude');
            //$data['industry']               = $this->post('industry');
            $data['employment_type']        = $this->post('employment_type');
            $salFrom                        = $this->post('salary_from');
            $salTo                          = $this->post('salary_to');
            $data['explain_opportunity']    = $this->post('explain_opportunity');
            $data['job_city']               = $this->post('job_city');
            $data['posted_by_user_id']      = $this->authData->userId;
            $data['crd']                    = $data['upd']=datetime();

            if($salFrom < $salTo){
                $data['salary_from']    =   $salFrom; 
                $data['salary_to']      =   $salTo; 

            }else{
                $response = array('status' => FAIL, 'message' => 'Invalide salary range.');
                $this->response($response);die();
            }
            $job_id = $this->common_model->insertData(JOBS, $data);//insert data

            //SEDN EMAIL AND PUSH NOTIFICATION TO ALL JOB SEEKAR WHICH IS UNDER 25 K.M OF REDIUS
             $userId = $this->authData->userId;
            if($job_id){
             // SELECT JOBSEKAR THOSE UNDER 25 K.M OF REDIOUS 
            //$s = shell_exec(SHELL_EXEC_PATH." notification bg_notification job_post_notify '".$job_id."' '".$userId."' >> " . PROJECT_PATH . "bg_notification_log.txt &");


          /* $s = shell_exec(SHELL_EXEC_PATH." notification Bg_notification job_post_notify '".$job_id."' '".$userId."' >> /home4/conneckt/public_html/dev.uconnekt.com.au/index.php");*/

            }
            //END OF SEND EMAIL AND NOTIFICATION MODULE
            if($job_id AND $this->post('job_type')==1){
                $response = array('status' => SUCCESS, 'message' => 'Job successfully posted.');
                $this->response($response);die();
            }

            if($job_id AND $this->post('job_type')==2){
                pr('f');
                $this->load->model('Image_model');
                $primiumData = array();
                $primiumData['job_id']               = $job_id;
                $primiumData['why_they_should_join'] = $this->post('why_they_should_join');
                $is_screening                        = $this->post('is_job_video_screening');

                if($is_screening==1){
                    
                    if(empty($this->post('question_description'))){

                        $response = array('status' => FAIL,'message'=>'Please add screening questions.');
                        $this->response($response);die();
                    }
            
                    $primiumData['is_job_video_screening']  =  1;
                    $primiumData['question_description']    =  $this->post('question_description');
                }


                $is_video_url                           = $this->post('is_video_url');
                if($is_video_url==1){
                    $primiumData['is_video_url']        = $is_video_url;
                    $isvideo                            = $this->post('video');

                    if(empty($isvideo)){
                        $response = array('status' => FAIL,'message'=>'Please select video url.');
                        $this->response($response);die(); 
                    }

                    $primiumData['video']                = $isvideo;  
                    $primiumData['video_thumb_image']    = '0';  
                    $primiumData['crd']                  = $primiumData['upd'] = datetime();  
                    $primium_job_id = $this->common_model->insertData(PREMIUM_JOBS,$primiumData);
                    if($primium_job_id){
                        $response = array('status' => SUCCESS, 'message' => 'Job successfully posted.');
                        $this->response($response);die(); 
                    }
                }else{

                    if(!empty($_FILES['video']['name'])){
                        $folder = 'screening_video';
                        $screeningVideo = $this->Media_upload_model->upload_video('video',$folder);
                        if(is_array($screeningVideo) && array_key_exists("error",$screeningVideo)){
                            $response = array('status' => FAIL, 'message' => strip_tags($screeningVideo['error']));
                            $this->response($response);
                        }

                    }else{
                         $screeningVideo =0;
                    }
                    
                    if(!empty($_FILES['video_thumb_image']['name'])){
                        $hieght = 768 ;
                        $width = 1024 ;
                        $folder = 'screening_video_thumb';
                        $video_thumb =$this->Image_model->updateMedia('video_thumb_image',$folder);

                        if(is_array($video_thumb) && array_key_exists("error",$video_thumb)){
                            $response = array('status' => FAIL, 'message' => strip_tags($video_thumb['error']));
                            $this->response($response);
                        }
                    }else{
                        $video_thumb= 0;
                    }

                    if($job_type==2){

                    /*else{
                        $response = array('status' => FAIL, 'message' =>'Please select video thumb image.');
                        $this->response($response);die(); 
                    }*/
                    /*elseif(filter_var($this->post('video_thumb_image'), FILTER_VALIDATE_URL)) {
                        $video_thumb = $this->post('video_thumb_image');  
                    }*/

                    //SEND EMAIL TO BILLING EMAIL

                    //$billingeData->billing_email;
                    $jobDetail    = $this->Jobs_model->getjobTitleAndAreaOfSpacility($job_id);

                    if(!empty($jobDetail) AND !empty($billingeData)){
                     $send= array();
                    
                    /* $send['profile_name']      = ucfirst($billingeData->fullName);
                     $send['business_name']     = ucfirst($billingeData->businessName);
                     $send['billing_entity']    = $billingeData->billing_entity;
                     $send['billing_abn']       = $billingeData->abn;
                     $send['billing_address']   = $billingeData->billing_address;
                     $send['email']             = $billingeData->billing_email;

                     $send['job_title']             = $jobDetail->jobTitleName;
                     $send['job_area_of_spacility'] = $jobDetail->specializationName;
                     $send['job_posted_date']       = date("d-m-Y", strtotime($jobDetail->crd));

                     $send['msg']               = 'A new premium job has been posted for '.$jobDetail->jobTitleName;
                     $send['sub_msg']           = '';*/
                
                     //$message = $this->load->view('email/email_invoice', $send, true);
                    $messages1 = '<br> Profile Name :-'.ucfirst($billingeData->fullName).' <br> Profile Business Name :-'.ucfirst($billingeData->businessName).' <br> Billing Entity :-'.$billingeData->billing_entity. ' <br> ABN/ACN Number:-'.$billingeData->abn.' <br> Billing Address :-' .$billingeData->billing_address. ' <br> Billing Email Address:- ' .$billingeData->billing_email.'<br> Job Title of job posted :- '.$jobDetail->jobTitleName.' <br> Area of Speciality :-'.$jobDetail->specializationName.' <br> Date :-' .date("d-m-Y", strtotime($jobDetail->crd));
                    
                     $this->load->library('Smtp_email');
                     $subject = 'Premium Job Ad Posted';
                   
                     $send_to=  'accounts@connektus.com.au';
                  
                     $isSend1 = $this->smtp_email->send_mail($send_to,$subject,$messages1);
                       
                     
                     

                    }
                   

                        $purchaseHistory['job_id'] = $job_id;
                        $purchaseHistory['user_id']= $this->authData->userId;
                        $purchaseHistory['currency']= $currency;
                        $purchaseHistory['amount']= $job_purchase_amount;
                        $purchaseHistory['crd']= datetime();
                        $purchaseHistorydata = $this->common_model->insertData(JOB_PURCHASE_HISTORY,$purchaseHistory);
                    }

                    $primiumData['is_video_url']         = 0;
                    $primiumData['video']                = $screeningVideo;  
                    $primiumData['video_thumb_image']    = $video_thumb;  
                    $primiumData['crd']                  = $primiumData['upd'] = datetime(); 
                    $primium_job_id = $this->common_model->insertData(PREMIUM_JOBS,$primiumData);

                    if($primium_job_id){
                        $response = array('status' => SUCCESS, 'message' => 'Job successfully posted.');
                        $this->response($response);die(); 
                    }
                }
            }
        }   
           
    }

    //job Detail business side
    function jobDetails_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Job type', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{
            $job_id    = $this->post('job_id');
            $job_type  = $this->post('job_type');
            $userId    = $this->authData->userId;
            $where     = array('jobId'=>$job_id,'job_type'=>$job_type);
            $jobexits  = $this->common_model->getsingle(JOBS,$where,$fld =NULL,$order_by = '',$order = '');

            if(empty($jobexits)){

                $response = array('status' => FAIL, 'message' =>'No data found.');
                $this->response($response);die();

            }else if(!empty($jobexits) AND $jobexits->posted_by_user_id!=$userId){

                $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die();
            }else{

                $detail= $this->Jobs_model->jobDetail($job_id,$job_type);
                $response = array('status' => SUCCESS, 'message' =>200,'data'=>$detail);
                $this->response($response);die();
            }

        }
    }


    //JOB LISTING INDIVIDUAL SIDE ACCORDING TO PREMIUIM AND BASIC JOBS 

    function jobListing_post(){

        $this->check_service_auth();
        $job_title      = $this->post('job_title');
        $company_name   = $this->post('company_name');
        $industry       = $this->post('industry');
        $employment_type= $this->post('employment_type');
        $location       = $this->post('location');
        $latitude       = $this->post('latitude');
        $longitude      = $this->post('longitude');
        $salaryFrom     = $this->post('salary_from');
        $salaryTo       = $this->post('salary_to');
        $job_type       = $this->post('job_type');
        $is_filter      = $this->post('is_filter');

        $job_city      = $this->post('job_city');
        $job_state     = $this->post('job_state');
        $job_country   = $this->post('job_country');

        if($this->post('pagination') == 1){
        $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
        $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }  
    
        if($this->authData->userType!='individual'){

            $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
            $this->response($response);die();

        }
        $whereuser     = array('user_id'=>$this->authData->userId);
        $userDetail    = $this->common_model->getsingle(USER_META,$whereuser,$fld =NULL,$order_by = '',$order = '');

        if(!empty($location) AND !empty($latitude) AND !empty($longitude)){
            $default_location = '';
            $default_latitude = '';
            $default_longitude ='';
            
        }else{
            $location   =   '';
            $latitude   =   '';
            $longitude  =   '';

            $default_location   = $userDetail->address;
            $default_latitude   = $userDetail->latitude;
            $default_longitude  = $userDetail->longitude;
        }
        

        $jobList= $this->Jobs_model->get_job_list_individual($job_title,$company_name,$industry,$employment_type,$location,$latitude,$longitude,$salaryFrom,$salaryTo,$offset,$limit,$job_type,$is_filter,$default_location,$default_latitude,$default_longitude,$job_city,$job_state,$job_country);
        $response = array('status' => SUCCESS, 'message' =>200,'data'=>$jobList);
        $this->response($response);die();
        

    }


    function jobListingBusiness_post(){

        $this->check_service_auth();
        $job_title      = $this->post('job_title');
        $company_name   = $this->post('company_name');
        $industry       = $this->post('industry');
        $employment_type= $this->post('employment_type');
        $location       = $this->post('location');
        $latitude       = $this->post('latitude');
        $longitude      = $this->post('longitude');
        $salaryFrom     = $this->post('salary_from');
        $salaryTo       = $this->post('salary_to');
        $job_type       = $this->post('job_type');
        $job_status     = $this->post('job_status');

        $job_city      = $this->post('job_city');
        $job_state     = $this->post('job_state');
        $job_country   = $this->post('job_country');
        
        if($this->post('pagination') == 1){
        $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
        $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }
          
  
        if($this->authData->userType!='business'){

            $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
            $this->response($response);die();

        }
        
        $jobList= $this->Jobs_model->get_job_list_business($job_title,$company_name,$industry,$employment_type,$location,$latitude,$longitude,$salaryFrom,$salaryTo,$offset,$limit,$job_type,$job_status,$job_city,$job_state,$job_country);
        $response = array('status' => SUCCESS, 'message' =>200,'data'=>$jobList);
        $this->response($response);die();
        
    }

    // delete job
    function deleteJob_post(){

        $this->check_service_auth();
        $job_id      = $this->post('job_id');
        $userId    = $this->authData->userId;
        if($this->authData->userType!='business'){
            $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
            $this->response($response);die();
        }
        // check if applicants available on this job ,job cant be deleted 
        $whereApplicants = array('job_id'=>$job_id);
        $jobapplicants   = $this->common_model->getsingle(JOB_APPLICANTS,$whereApplicants,$fld =NULL,$order_by = '',$order = '');

        if(!empty($jobapplicants)){

           $response = array('status' => FAIL, 'message' =>'You can not delete this job ad. Applicants already applied for this job.');
            $this->response($response);die(); 
        }
        //end of applicants check

        $where1     = array('jobId'=>$job_id,'posted_by_user_id'=>$userId);
        $jobexits  = $this->common_model->getsingle(JOBS,$where1,$fld =NULL,$order_by = '',$order = '');
        if($jobexits){
            if($jobexits->job_type==1){
                $isdelete  = $this->common_model->deleteData(JOBS,$where1);
            }else{
                $where2    = array('job_id'=>$job_id);
                $isdelete  = $this->common_model->deleteData(JOBS,$where1);
                $isdelete  = $this->common_model->deleteData(PREMIUM_JOBS,$where2);
            }
            $response = array('status' => SUCCESS, 'message' =>'Job successfully deleted.');
            $this->response($response);die();
        }else{
             $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
        }
        $this->response($response);die(); 
    }
    //end of delete job

    //expired job 

    function expiredJob_post(){

        $this->check_service_auth();
        $job_id      = $this->post('job_id');
        $userId    = $this->authData->userId;
        if($this->authData->userType!='business'){
            $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
            $this->response($response);die();
        }
       
        $whereApplicants = array('job_id'=>$job_id);
       
        $where1     = array('jobId'=>$job_id,'posted_by_user_id'=>$userId);
        $jobexits  = $this->common_model->getsingle(JOBS,$where1,$fld =NULL,$order_by = '',$order = '');
       
        if(!empty($jobexits)){
            if($jobexits->is_expired ==0){

                if($jobexits->status==0){
                     $data = array('is_expired'=>1,'status'=>1);
                }else{
                     $data = array('is_expired'=>1);
                }
               
                $isdelete  = $this->common_model->updateFields(JOBS,$data,$where1);
                $response = array('status' => SUCCESS, 'message' =>'Job successfully expired.');
                $this->response($response);die();

            }else{
                $response = array('status' => FAIL, 'message' =>'This Job already expired.');
            }
        }else{
            $response = array('status'=>FAIL,'message' =>'You are not authorised for this action.');
            $this->response($response);die();
        }   

        $this->response($response);die(); 
    }


    function editJob_post(){
        
        $this->check_service_auth();
        $this->form_validation->set_rules('job_title_id', 'Job title', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Job Type', 'trim|required');
        $this->form_validation->set_rules('job_location', 'Job location', 'trim|required');
        $this->form_validation->set_rules('job_city', 'City', 'trim|required');
        $this->form_validation->set_rules('job_state', 'State', 'trim|required');
        $this->form_validation->set_rules('job_country', 'Country', 'trim|required');
        $this->form_validation->set_rules('job_latitude', 'Latitude', 'trim|required');
        $this->form_validation->set_rules('job_longitude', 'Longitude', 'trim|required');
        $this->form_validation->set_rules('industry', 'Industry', 'trim|required');
        $this->form_validation->set_rules('employment_type', 'Employment Type', 'trim|required');
        $this->form_validation->set_rules('salary_from', 'Salary from', 'trim|required');
        $this->form_validation->set_rules('salary_to', 'Salary to ', 'trim|required');
        $this->form_validation->set_rules('explain_opportunity', 'Explain opportunity', 'trim|required');
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');

       
        if($this->post('job_type')==2){
            //$this->form_validation->set_rules('job_purchase_amount','Job Purchase Amount','trim|required');
            //$this->form_validation->set_rules('currency', 'Currency', 'trim|required');

            //$this->form_validation->set_rules('why_they_should_join', 'Why they should join', 'trim|required');
           // $this->form_validation->set_rules('is_job_video_screening', 'Is job video screening', 'trim|required');
            //$this->form_validation->set_rules('video', 'Video', 'trim|required');
           
        }
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{
             $wherej     = array('jobId'=>$this->post('job_id'));   
             $basicjob   = $this->common_model->getsingle(JOBS,$wherej,$fld =NULL,$order_by = '',$order = '');

             if($basicjob->job_type!=$this->post('job_type')){

                 $response = array('status' => FAIL, 'message'=>'Please select proper job type.');
                $this->response($response);die(); 

             }
            if($this->authData->userType!='business'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            // check if applicants available on this job ,job cant be deleted 
            /*$whereApplicants = array('job_id'=>$job_id);
            $jobapplicants   = $this->common_model->getsingle(JOB_APPLICANTS,$whereApplicants,$fld =NULL,$order_by = '',$order = '');

            if(!empty($jobapplicants)){

            $response = array('status' => FAIL, 'message' =>'You can not edit this job ad. Applicants already applied for this job ad.');
            $this->response($response);die(); 
            }*/
            //end of applicants check

            $title      = $this->post('job_title_id');
            $is_delete      = $this->post('is_delete');
            $where      = array('jobTitleName'=>trim($title));
            $jobtitle   = $this->common_model->getsingle(JOB_TITLES,$where,$fld =NULL,$order_by = '',$order = '');
            if(!empty($jobtitle)){

                $data['job_title_id'] = $jobtitle->jobTitleId;

            }else{
                $data1['jobTitleName']      = trim($title);
                $data1['userType']          = 'both';
                $data1['type']              = 1;
                $data1['addedById']         = $this->authData->userId;
                $data['job_title_id']       = $this->common_model->insertData(JOB_TITLES, $data1);
            }
            $job_type                       = $this->post('job_type');

            if($job_type==2){

                $job_purchase_amount            = 30;
                $currency                       = '$';
                $whereoption  = array('option_value'=>trim($job_purchase_amount),'option_name'=>'purchase_amount');
                $purchaseData   = $this->common_model->getsingle(OPTIONS,$whereoption,$fld =NULL,$order_by = '',$order = '');

                if(empty($purchaseData)){

                    $response = array('status' =>FAIL,'message'=>'Invalide purchase amount.');
                    $this->response($response);die();
                }

                
                $whereBilling  = array('user_id'=>$this->authData->userId);
                $billingeData   = $this->common_model->getsingle(USER_BLLLING_INFO,$whereBilling,$fld =NULL,$order_by = '',$order = '');

                if(empty($billingeData)){
                    $response = array('status' =>FAIL,'message'=>'You need to first complete your billing info.');
                    $this->response($response);die();
                }

            }
            if($job_type==1){

                $data['job_type']=1; 
            }
            elseif($job_type==2){
                 $data['job_type']=2; 
                 $data['job_purchase_amount']= 30; 
                 

            }else{
                $response = array('status' => FAIL, 'message' => 'Invalide job type.');
                $this->response($response);die();
            }
            $specializationName = ucwords($this->post('industry'));
       
            if(!empty($specializationName)){

                $where    = array('specializationName'=>trim($specializationName));

                $jobspecializationName = $this->common_model->getsingle(SPECIALIZATIONS,$where,$fld =NULL,$order_by = '',$order = '');

                if(!empty($jobspecializationName)){

                    $data['industry'] = $jobspecializationName->specializationId;

                }else{

                    $datasp['specializationName']      = trim($specializationName);
                    $datasp['userType']                = 'both';
                    $datasp['type']                    = 1;
                    $datasp['addedById']               = $this->authData->userId;
                    $title_id                         = $this->common_model->insertData(SPECIALIZATIONS,$datasp);
                    $data['industry']  = $title_id;
                } 

            }
            $data['job_location']           = $this->post('job_location');
            $data['job_city']               = $this->post('job_city');
            $data['job_state']              = $this->post('job_state');
            $data['job_country']            = $this->post('job_country');
            $data['job_latitude']           = $this->post('job_latitude');
            $data['job_longitude']          = $this->post('job_longitude');
            //$data['industry']               = $this->post('industry');
            $data['employment_type']        = $this->post('employment_type');
            $salFrom                        = $this->post('salary_from');
            $salTo                          = $this->post('salary_to');
            $data['explain_opportunity']    = $this->post('explain_opportunity');
            $data['job_city']               = $this->post('job_city');
            $data['posted_by_user_id']      = $this->authData->userId;;
            $data['upd']                    =  datetime();
            $job_id                         = $this->post('job_id');
            $whereupd                       = array('jobId'=>$job_id);
            if($salFrom < $salTo){
                $data['salary_from']    =   $salFrom; 
                $data['salary_to']      =   $salTo; 

            }else{
                $response = array('status' => FAIL, 'message' => 'Invalide salary range.');
                $this->response($response);die();
            }
            $update = $this->common_model->updateFields(JOBS,$data,$whereupd);//update data

            if($update AND $this->post('job_type')==1){
                $response = array('status' => SUCCESS, 'message' => 'Basic Job successfully updated.');
                $this->response($response);die();
            }
                

            if($job_id AND $this->post('job_type')==2){ 
                $wherejob = array('job_id'=>$job_id);
                $premiumjob   = $this->common_model->getsingle(PREMIUM_JOBS,$wherejob,$fld =NULL,$order_by = '',$order = '');

                $this->load->model('Image_model');
                $primiumData = array();
                $whereupd1                              = array('job_id'=>$job_id);
                //$primiumData['job_id']                 = $job_id;
                $primiumData['why_they_should_join']    = $this->post('why_they_should_join');
                $is_screening                           = $this->post('is_job_video_screening');

                if($is_screening==1){

                    $primiumData['is_job_video_screening'] = 1;

                     if(empty($this->post('question_description'))){
                        $response = array('status' => FAIL, 'message' => 'Please add screening questions.');
                        $this->response($response);die();
                    }else{

                        $primiumData['question_description']   =  $this->post('question_description');
                    }

                }else{
                    $primiumData['is_job_video_screening'] = 0; 
                    $primiumData['question_description']   ='';
                }

                $is_video_url                           = $this->post('is_video_url');
                if($is_video_url==1){
                    $primiumData['is_video_url']         = $is_video_url;
                    $isvideo                             =$this->post('video');
                    if(empty($isvideo)){
                        /*$response = array('status' => FAIL, 'message' =>'Please select video.');
                        $this->response($response);die(); */

                        if($is_delete == 0){
                            $isvideo =  $premiumjob->video;
                        }else{
                            $isvideo =  0;
                        }
                        
                    }
                    $primiumData['video']                = $isvideo;  
                    $primiumData['video_thumb_image']    = '0';  
                    $primiumData['crd']                  = $primiumData['upd'] = datetime();  
                    //$primium_job_id = $this->common_model->insertData(PREMIUM_JOBS,$primiumData);
                    $primium_job_id = $this->common_model->updateFields(PREMIUM_JOBS,$primiumData,$whereupd1);

                    if($primium_job_id){
                        $response = array('status' => SUCCESS, 'message' => 'Premium Job successfully updated.');
                        $this->response($response);die(); 
                    }
                    
                }else{
                  

                    if(!empty($_FILES['video']['name'])){
                        $folder = 'screening_video';
                        $screeningVideo = $this->Media_upload_model->upload_video('video',$folder);
                        if(is_array($screeningVideo) && array_key_exists("error",$screeningVideo)){
                            $response = array('status' => FAIL, 'message' => strip_tags($screeningVideo['error']));
                            $this->response($response);
                        }

                    }else{
                        if($is_delete == 0){
                             $screeningVideo =  $premiumjob->video;
                        }else{
                             $screeningVideo =  0;
                        }
                       
                    }
                    /*else if(filter_var($this->post('video'),FILTER_VALIDATE_URL)){
                        $screeningVideo = $this->post('video');  //for social profile image url
                    }*/

                    if(!empty($_FILES['video_thumb_image']['name'])){
                        $hieght = 768 ;
                        $width = 1024 ;
                        $folder = 'screening_video_thumb';
                        $video_thumb = $this->Image_model->updateMedia('video_thumb_image',$folder);

                        if(is_array($video_thumb) && array_key_exists("error",$video_thumb)){
                            $response = array('status' => FAIL, 'message' => strip_tags($video_thumb['error']));
                            $this->response($response);
                        }
                    }else{
                        if($is_delete == 0){
                            $video_thumb =  $premiumjob->video_thumb_image;
                        }else{
                            $video_thumb =  '';
                        }
                         
                        /*$response = array('status' => FAIL, 'message' =>'Please select video thumb image.');
                        $this->response($response);die(); */
                    }
                    /*elseif(filter_var($this->post('video_thumb_image'), FILTER_VALIDATE_URL)) {
                        $video_thumb = $this->post('video_thumb_image');  
                    }*/
                    $primiumData['is_video_url']         = 0;
                    $primiumData['video']                = $screeningVideo;  
                    $primiumData['video_thumb_image']    = $video_thumb;  
                    $primiumData['upd'] = datetime();  
                    //$primium_job_id = $this->common_model->insertData(PREMIUM_JOBS,$primiumData);
                    $whereupd                       = array('job_id'=>$job_id);
                    $primium_job_id = $this->common_model->updateFields(PREMIUM_JOBS,$primiumData,$whereupd);//update data
                    if($primium_job_id){
                        $response = array('status' => SUCCESS, 'message' => 'Premium Job successfully updated');
                        $this->response($response);die(); 
                    }
                }
            }
        }   
           
    }


    function apply_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Job Type', 'trim|required');
        $userId     = $this->authData->userId;
        $wherep     = array('job_id'=>$this->post('job_id')); 
        $premiumjob = $this->common_model->getsingle(PREMIUM_JOBS,$wherep,$fld =NULL,$order_by= '',$order = '');

       /* if($this->post('job_type')==2 AND $premiumjob->is_job_video_screening ==1){
            $this->form_validation->set_rules('video', 'Video', 'trim|required');
            $this->form_validation->set_rules('video_thumb_image', 'Video Thumb', 'trim|required');
        }*/
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='individual'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            // CHECK JOBSEEKAR PROFILE COMPLITE OR NOT
            $wherePofile         = array('user_id'=>$userId);   
            $isProfilecomplite   = $this->common_model->getsingle(USER_EXPERIENCE,$wherePofile,$fld =NULL,$order_by = '',$order = '');

            if(empty($isProfilecomplite)){
                $response = array('status' => FAIL, 'message'=>'To apply for this job, you will need to complete the required fields in your profile.');
                $this->response($response);die(); 
            }

            //END OF FUNCTION PROFILE COMPLITE PROFILE CHECK
            $wherej     = array('jobId'=>$this->post('job_id'));   
            $basicjob   = $this->common_model->getsingle(JOBS,$wherej,$fld =NULL,$order_by = '',$order = '');
            $wherep     = array('job_id'=>$this->post('job_id')); 
            $premiumjob = $this->common_model->getsingle(PREMIUM_JOBS,$wherep,$fld =NULL,$order_by= '',$order = '');

            if(empty($basicjob)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 

            }

            if($basicjob->job_type!=$this->post('job_type')){

                $response = array('status' => FAIL, 'message'=>'Please select proper job type.');
                $this->response($response);die(); 

            }

            $job_id           = $this->post('job_id');
            $where            = array('job_id'=>$job_id,'applied_by_user_id'=>$userId);
            $jobapplicants    = $this->common_model->getsingle(JOB_APPLICANTS,$where,$fld =NULL,$order_by = '',$order = '');

            if(!empty($jobapplicants)){

                $response = array('status' => FAIL, 'message'=>'You  already applied for this job.');
                $this->response($response);die();
            }

            if($this->post('job_type')==2){

                $data['job_type']                = 2; 
                $data['job_id']                  = $job_id; 
                $data['applied_by_user_id']      = $this->authData->userId;
                $data['job_application_status']  = 0;
                $data['crd']                     = $data['upd']=datetime();

                if($premiumjob->is_job_video_screening ==1){


                    if(!empty($_FILES['video']['name'])){
                        $folder = 'answer_screening_video';
                        $screeningVideo = $this->Media_upload_model->upload_video('video',$folder);

                        if(is_array($screeningVideo) && array_key_exists("error",$screeningVideo)){

                            $response = array('status' => FAIL, 'message' => strip_tags($screeningVideo['error']));
                            $this->response($response);die();
                        }
                    }else{

                        $response = array('status' => FAIL, 'message' =>'Please select video.');
                        $this->response($response);die(); 
                    }

                }else{

                    $screeningVideo = 0;
                }

                if($premiumjob->is_job_video_screening ==1){

                    if(!empty($_FILES['video_thumb_image']['name'])){
                        $hieght = 768 ;
                        $width = 1024 ;
                        $folder = 'answer_screening_video_thumb';
                        $video_thumb = $this->Image_model->updateMedia('video_thumb_image',$folder);

                        if(is_array($video_thumb) && array_key_exists("error",$video_thumb)){
                            $response = array('status' => FAIL, 'message' => strip_tags($video_thumb['error']));
                                $this->response($response);die();
                        }
                    }else{
                        $response = array('status' => FAIL, 'message' =>'Please select video thumb image.');
                        $this->response($response);die(); 
                    }
                }else{
                   $video_thumb =0; 
                }

                $data['video']         =  $screeningVideo;
                $data['video_thumb']   =  $video_thumb;

            }else{

                $data['job_id']                  = $job_id; 
                $data['job_type']                = 1; 
                $data['applied_by_user_id']      = $this->authData->userId;
                $data['job_application_status']  = 0;
                $data['crd']                     = $data['upd']=datetime();
            }
          
            $job_applicants = $this->common_model->insertData(JOB_APPLICANTS,$data);
            //insert data

            if($job_applicants){

                $notification_for = $basicjob->posted_by_user_id;
                $current_user_id  = $this->authData->userId;
                $user_info        = $this->common_model->getsingle(USERS, array('userId'=>$notification_for),'userId, 
                    deviceToken,isNotify,isNotifyEmail,userType,fullName,email,businessName');

                $userType = $user_info->userType;
                $wherejobTitle = array('jobId'=>$job_id);
                $job_title = $this->common_model->getsingle(JOBS,$wherejobTitle,$fld =NULL,$order_by = '',$order = '');


                $wheretitle = array('jobTitleId'=>$job_title->job_title_id);
                $jobtitle = $this->common_model->getsingle(JOB_TITLES,$wheretitle,$fld =NULL,$order_by = '',$order = '');

                if(!empty($user_info)){

                    $registrationIds[] = $user_info->deviceToken;
                    //$title = "Apply for job";
                    $title = "Application received!";
                    if(!empty($this->authData->profileImage)){
                        $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                    }else{
                        $profileImage = base_url().DEFAULT_USER;
                    }
                  
                   $body_send  = ucfirst($this->authData->fullName).' has applied for your '.$jobtitle->jobTitleName.' role';
                
                    $body_save          = '[UNAME] has applied for your '.$jobtitle->jobTitleName.' role'; 
                    $notif_type         = 'apply_job';
                    if($user_info->isNotify==1){

                        $notif_msg          = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $job_id, $notif_type,$userType,$profileImage,$basicjob->job_type,$current_user_id);
                        //log_event($notif_msg);
                        $notif_msg['body']           = $body_save; 
                        $notif_msg['reference_type']  = $basicjob->job_type; 
                        $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$notification_for,'reference_id'=>$job_id,'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                        $this->notification_model->save_notification(NOTIFICATIONS,$insertdata);
                    }
                    //SEND EMAIL TO EMPLOYER  JOB_TITLESucfirst()

                     $send= array();
                     $send['email']             = $user_info->email;
                     $send['fullName']          = ucfirst($user_info->fullName);
                     $send['request_by']        = $this->authData->fullName;
                     //$send['msg']               = 'You have received a job application for '.$jobtitle->jobTitleName;
                     $send['msg']               = 'Great news! You have received an application from ' .$this->authData->fullName. ' for your ' .$jobtitle->jobTitleName. ' position.';

                     $send['sub_msg']           = 'We have made the hiring process simple! To view applicants, please login to our app and click on the specific job in your job list!';
                     $send['interviewer_name']  = '';
                     $send['new_image']  = 'Resume.png';
                     $send['date']              = '';
                     $send['time']              = '';
                     $send['location']          = '';

                     if($user_info->isNotifyEmail==1){

                        $message = $this->load->view('email/email_interview_request', $send, true);
                        $this->load->library('Smtp_email');
                        $subject = $this->authData->fullName.' applied for your '.$jobtitle->jobTitleName.' position';
                        $isSend = $this->smtp_email->send_mail($send['email'],$subject,$message);
                    }

                 //END OF SEND EMAIL FUNCTIONLY 
                }
                $response = array('status' => SUCCESS, 'message' => 'You have successfully applied for this job');
                $this->response($response);die(); 
            }

            $response = array('status' => SUCCESS, 'message' => 'Somthing awent wromg.');
            $this->response($response);die(); 
       
        }   
           
    }

    function applicationViewed_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Job Type', 'trim|required');
        $this->form_validation->set_rules('applied_by_user_id', 'Applied by user id', 'trim|required');
        $userId = $this->authData->userId;
       
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{
               
                if($this->authData->userType!='business'){
                   $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                    $this->response($response);die(); 
                }

                $wherej     = array('jobId'=>$this->post('job_id'),'posted_by_user_id'=>$userId);   
                $basicjob   = $this->common_model->getsingle(JOBS,$wherej,$fld =NULL,$order_by = '',$order = '');
           

                if($basicjob->job_type!=$this->post('job_type')){

                    $response = array('status' => FAIL, 'message'=>'Please select proper job type.');
                    $this->response($response);die(); 

                }

                $job_id                       = $this->post('job_id');
                $applied_by_user_id           = $this->post('applied_by_user_id');
                $where                        = array('job_id'=>$job_id,'applied_by_user_id'=>$applied_by_user_id);
                $jobapplicants    = $this->common_model->getsingle(JOB_APPLICANTS,$where,$fld =NULL,$order_by ='',$order = '');

                if(empty($jobapplicants)){

                    $response = array('status' => FAIL, 'message'=>'No applicants applyed yet on this job.');
                    $this->response($response);die();

                }else{

                    if($jobapplicants->job_application_status==0){

                        $where_jobapplicatnt = array('job_id'=>$job_id,'applied_by_user_id'=>$applied_by_user_id);
                        $set['job_application_status'] = 9;
                        $jobupdate = $this->common_model->updateFields(JOB_APPLICANTS,$set,$where_jobapplicatnt);

                        $notification_for = $applied_by_user_id;
                        $current_user_id  = $this->authData->userId;
                        $user_info        = $this->common_model->getsingle(USERS, array('userId'=>$notification_for),'userId, 
                        deviceToken,isNotify,isNotifyEmail,userType,fullName,email,businessName');

                        $user_buniness_info  = $this->common_model->getsingle(USERS, array('userId'=>$this->authData->userId),'userId,deviceToken,isNotify,isNotifyEmail,userType,fullName,email,businessName');

                        $userType = $user_info->userType;
                        $wherejobTitle = array('jobId'=>$job_id);
                        $job_title = $this->common_model->getsingle(JOBS,$wherejobTitle,$fld =NULL,$order_by = '',$order = '');


                        $wheretitle = array('jobTitleId'=>$job_title->job_title_id);
                        $jobtitle = $this->common_model->getsingle(JOB_TITLES,$wheretitle,$fld =NULL,$order_by = '',$order = '');

                        if(!empty($user_info)){

                            $registrationIds[] = $user_info->deviceToken;
                            $title = "Your application has been viewed!";

                            if(!empty($this->authData->profileImage)){
                                $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                            }else{
                                $profileImage = base_url().DEFAULT_USER;
                            }
                      
                            //$body_send          = ucfirst($this->authData->fullName).' has recently viewed your application!';
                            $body_send     = 'Update! Your application has been viewed by '.ucfirst($this->authData->fullName).' for the '.$jobtitle->jobTitleName.' position.';

                            $body_save      = 'Update! Your application has been viewed by [UNAME] for the '.$jobtitle->jobTitleName.' position.';

                            $notif_type         = 'viewd_job';

                            if($user_info->isNotify==1){

                                $notif_msg          = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $job_id, $notif_type,$userType,$profileImage,$basicjob->job_type,$current_user_id);
                            
                                $notif_msg['body']           = $body_save; 
                                $notif_msg['reference_type']  = $basicjob->job_type; 
                                $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$notification_for,'reference_id'=>$job_id,'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                                $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);

                            }

                            $send= array();
                            $send['email']             = $user_info->email;
                            $send['fullName']          = ucfirst($user_info->fullName);
                            $send['request_by']        = ucfirst($this->authData->fullName);
                            $send['subject']           = 'Your job application has been reviewed!';
                            //$send['msg']  = 'Your job application has been viewed by the '.$user_buniness_info->businessName.'.';
                            $send['msg']  = 'Great news! Your job application has been reviewed by '.$user_buniness_info->businessName.' for the ' .$jobtitle->jobTitleName. ' position!';

                            $send['sub_msg'] = 'We have made the hiring process simple! To view your progress, please login to our, find your job in your applied jobs screen and click on the tracking icon!';

                            $send['interviewer_name']  = '';
                            $send['date']              = '';
                            $send['time']              = '';
                            $send['location']          = '';
                            
                            if($user_info->isNotifyEmail==1){

                                $message = $this->load->view('email/email_interview_request', $send, true);
                                $this->load->library('Smtp_email');
                                $subject = $send['subject'];
                                $isSend = $this->smtp_email->send_mail($send['email'],$send['subject'],$message);
                            }

                            $response = array('status' => SUCCESS, 'message' => 'You have successfully viewed this job');
                            $this->response($response);die(); 

                        }
                     
                    }
               }
            }

            $response = array('status' => SUCCESS, 'message' => 'Somthing went wromg.');
            $this->response($response);die(); 

    }


    function jobDetailIndividualSide_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_type', 'Job type', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{
            $job_id    = $this->post('job_id');
            $job_type  = $this->post('job_type');
            $userId    = $this->authData->userId;
            $where     = array('jobId'=>$job_id,'job_type'=>$job_type);
            $jobexits  = $this->common_model->getsingle(JOBS,$where,$fld =NULL,$order_by = '',$order = '');

            if(empty($jobexits)){

                $response = array('status' => FAIL, 'message' =>'No data found.');
                $this->response($response);die();

            }else if(!empty($jobexits) AND $this->authData->userType!='individual'){

                $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die();
            }else{

                $detail= $this->Jobs_model->get_job_detail_individualSide($job_id,$job_type);
                $response = array('status' => SUCCESS, 'message' =>200,'data'=>$detail);
                $this->response($response);die();
            }

        }
    }

    //job Applicant Detail - Business Side
    function jobApplicantDetail_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{
            $job_id     = $this->post('job_id');
            $user_id    = $this->post('user_id');
            $userId     = $this->authData->userId;
            $where      = array('jobId'=>$job_id,'posted_by_user_id'=>$userId);
            $jobs       = $this->common_model->getsingle(JOBS,$where,$fld =NULL,$order_by = '',$order = '');

            if(empty($jobs)){

               $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            $where     = array('job_id'=>$job_id,'applied_by_user_id'=>$user_id);
            $jobexits  = $this->common_model->getsingle(JOB_APPLICANTS,$where,$fld =NULL,$order_by = '',$order = '');

            if(empty($jobexits)){

                $response = array('status' => FAIL, 'message' =>'No data found.');
                $this->response($response);die();

            }else if(!empty($jobexits) AND $this->authData->userType!='business'){

                $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die();
            }else{

                $detail= $this->Jobs_model->get_job_applicant_detail($job_id,$user_id,$jobs->job_type);
                $response = array('status' => SUCCESS, 'message' =>200,'data'=>$detail);
                $this->response($response);die();
            }

        }

    }

    // shortlist// reject / /interview request / make offer/ not offered/ - business side 
    function acceptRejectJobSeekar_post(){
        
        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_application_status', 'Job Status', 'trim|required');
        $this->form_validation->set_rules('applied_by_user_id', 'User Id', 'trim|required');
        $userId =$this->authData->userId;
        if($this->post('job_application_status')==3){

            $this->form_validation->set_rules('interviewer_name', 'interviewer name', 'trim|required');
            $this->form_validation->set_rules('date', 'date', 'trim|required');
            $this->form_validation->set_rules('time', 'Time', 'trim|required');
            $this->form_validation->set_rules('interview_location', 'Location', 'trim|required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
    
        } 
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='business'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

             $wherej     = array('jobId'=>$this->post('job_id'));   
             $basicjob   = $this->common_model->getsingle(JOBS,$wherej,$fld =NULL,$order_by = '',$order = '');

             
            if(empty($basicjob)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 

            }else if($basicjob->posted_by_user_id != $this->authData->userId){

                $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 

            }else if($basicjob->is_expired == 1 OR $basicjob->status == 0){

                $response = array('status' => FAIL, 'message'=>'You can not perform any action on this job');
                $this->response($response);die(); 

            }

    
            $job_id           = $this->post('job_id');
            $where            = array('job_id'=>$job_id,'applied_by_user_id'=>$this->post('applied_by_user_id'));
            $jobapplicants    = $this->common_model->getsingle(JOB_APPLICANTS,$where,$fld =NULL,$order_by = '',$order = '');

            if(empty($jobapplicants)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==1 AND $this->post('job_application_status')==1){

                $response = array('status' => FAIL, 'message'=>'Applicant already shortlisted for this job.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==2 AND $this->post('job_application_status')==2){

                $response = array('status' => FAIL, 'message'=>'Applicant already not rejected for this job.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==3 AND $this->post('job_application_status')==3){

                $response = array('status' => FAIL, 'message'=>'Interview request already sent to applicant.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==6 AND $this->post('job_application_status')==6){

                $response = array('status' => FAIL, 'message'=>'Alredy Job offered.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==7 AND $this->post('job_application_status')==7){

                $response = array('status' => FAIL, 'message'=>'Alredy Job offered.');
                $this->response($response);die();

            }else{
                    $wherejobTitle = array('jobId'=>$this->post('job_id'));
                    $job_title = $this->common_model->getsingle(JOBS,$wherejobTitle,$fld =NULL,$order_by = '',$order = '');

                    $wheretitle = array('jobTitleId'=>$job_title->job_title_id);
                    $jobtitle = $this->common_model->getsingle(JOB_TITLES,$wheretitle,$fld =NULL,$order_by = '',$order = '');

                if($this->post('job_application_status')==1 OR $this->post('job_application_status')==2 OR $this->post('job_application_status')== 3 OR $this->post('job_application_status')==6 OR $this->post('job_application_status')== 7){

                    $data['job_application_status']  =   $this->post('job_application_status');
                    $data['applied_by_user_id']      =   $this->post('applied_by_user_id');
                    $data['upd']     = datetime();
                    $notification_for = $data['applied_by_user_id'];
                    $current_user_id  = $this->authData->userId;

                    $user_info        = $this->common_model->getsingle(USERS, array('userId'=>$notification_for),'userId, 
                    deviceToken,isNotify,isNotifyEmail,userType,email,fullName,businessName');

                    $userInfo = $this->common_model->getsingle(USERS, array('userId'=>$current_user_id),'userId, 
                    deviceToken,isNotify,isNotifyEmail,userType,email,fullName,businessName');

                    $userType =  $user_info->userType;

                    /*$where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$data['applied_by_user_id']);
                    $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where);*/

                    $job_applicants =1;

                    if(!empty($user_info)){

                        $registrationIds[] = $user_info->deviceToken;
                        if(!empty($this->authData->profileImage)){
                            $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                        }else{
                            $profileImage = base_url().DEFAULT_USER;
                        }

                       if($job_applicants AND $this->post('job_application_status')==1){

                            $title            = 'Congratulations, you have been shortlisted for the'.$jobtitle->jobTitleName.'';
                            //$body_send          =  ucfirst($this->authData->fullName).' shortlisted your application for the '.$jobtitle->jobTitleName.' position'; 
                            $body_send  = 'Great news! '.ucfirst($this->authData->fullName).' just shortlisted you for the '.$jobtitle->jobTitleName.' Position!'; 
                            $body_save = 'Great news! [UNAME] just shortlisted you for the '.$jobtitle->jobTitleName. ' role! We’ll keep you posted on any progress.'; 
                            $notif_type         = 'shortlisted_job';   

                            //SEND EMAIL TO EMPLOYER  JOB_TITLES businessName
                             $send= array();
                             $send['email']             = $user_info->email;
                             $send['fullName']          = ucfirst($user_info->fullName);
                             $send['request_by']        = $this->authData->fullName;
                             $send['subject']           = 'You have been shortlisted for the '.$jobtitle->jobTitleName.' position';

                            // $send['msg']  = 'Congratulations, this is to officially inform you that your application has been shortlisted  for the position of '.$jobtitle->jobTitleName.' with '.$userInfo->businessName.' .if selected you will have been invited for further screening';
                             $send['msg']  = 'Guess what!? You have been shortlisted for the '.$jobtitle->jobTitleName.' position! We have our fingers crossed for you!';

                             $send['sub_msg']           = 'We have made the hiring process simple! To view your progress, please login to our, find your job in your applied jobs screen and click on the tracking icon!';
                             $send['interviewer_name']  = '';
                             $send['date']              = '';
                             $send['time']              = '';
                             $send['location']          = '';
                             
                             //END OF SEND EMAIL FUNCTIONLY
                            $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$data['applied_by_user_id']);
                            $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where); 
                            $response = array('status' => SUCCESS, 'message'=>'Applicant shortlisted successfully for this job');

                       }else if($job_applicants AND $this->post('job_application_status')==2){
                        

                        $title              = "Your application is unsuccessful";

                       // $body_send          =  ucfirst($this->authData->fullName).' Rejected your application for the '.$jobtitle->jobTitleName.' position';


                       $body_send     = 'We are sorry! It appears you have been unsuccessful regarding the '.$jobtitle->jobTitleName.' with '.$userInfo->businessName.'. Keep trying, the right job is out there for you!'; 

                       //$body_save          = '[UNAME] rejected your job'; 
                           $body_save   = ' We are sorry! It appears you have been unsuccessful regarding the '.$jobtitle->jobTitleName.' with '.$userInfo->businessName.'. Keep trying, the right job is out there for you!'; 


                            $notif_type         = 'rejected_job';   

                            //SEND EMAIL TO EMPLOYER  JOB_TITLES businessName
                            $send= array();
                            $send['email']             = $user_info->email;
                            $send['fullName']          = ucfirst($user_info->fullName);
                            $send['request_by']        = $this->authData->fullName;
                            //$send['subject']            = 'Rejected job application';
                            $send['subject']            = 'We are sorry that you are unsuccessful';

                            //$send['msg']  = 'This is to officially inform you that your application has been declined for the position of  '.$jobtitle->jobTitleName.' with '.$userInfo->businessName.'. Although you were most encouraging in outlining future advancement possibilities.';

                            //$send['msg']  = 'We’re sorry but it appears you’ve been unsuccessful in your application for the '.$jobtitle->jobTitleName.' position with '.$userInfo->businessName.'. Don’t let this discourage you! The right job is out there for you! Just don’t give up until you find it!';
                            $send['msg']  = 'We are sorry that you are unsuccessful';

                             $send['sub_msg']           = 'You can view more jobs and employer profiles via out app! Simply login and start searching! It is that simple!';
                             $send['interviewer_name']  = '';
                             $send['date']              = '';
                             $send['time']              = '';
                             $send['location']          = '';
                             
                             //END OF SEND EMAIL FUNCTIONLY
                           
                            $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$data['applied_by_user_id']);
                            //$job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where); 
                            $job_applicantsd = $this->common_model->deleteData(JOB_APPLICANTS,$where); 

                            $response = array('status' => SUCCESS, 'message'=>'Application rejected successfully for this job');

                       }else if($job_applicants AND $this->post('job_application_status')==3){

                             // WHEN BUSINESS SIDE INTERVIEW REQUEST TO APPLICANTS 
                               
                                    $is_job_request = $this->common_model->getsingle(JOB_INTERVIEWS,array('job_applicant_id'=>$jobapplicants->jobApplicantId),'');
                                    $jobInterview = array();
                                   
                                    if(!empty($is_job_request)){
                                        $where_job_interview = array('jobInterviewId'=>$is_job_request->jobInterviewId);
                                        $jobInterview['job_applicant_id']   = $jobapplicants->jobApplicantId;
                                        $jobInterview['interviewer_name']   = $this->post('interviewer_name');
                                        $jobInterview['date']               = $this->post('date');
                                        $jobInterview['time']               = $this->post('time');
                                        $jobInterview['interview_location'] = $this->post('interview_location');
                                        $jobInterview['latitude']           = $this->post('latitude');
                                        $jobInterview['longitude']          = $this->post('longitude');
                                        $jobInterview['upd']                = datetime();
                                        $jobInterview1 = $this->common_model->updateFields(JOB_INTERVIEWS,$jobInterview,$where_job_interview);

                                        //$response = array('status' => FAIL,'message'=>'Alredy sent interview request.');
                                        //$this->response($response);die();

                                    }else{
                                        $jobInterview['job_applicant_id']   = $jobapplicants->jobApplicantId;
                                        $jobInterview['interviewer_name']   = $this->post('interviewer_name');
                                        $jobInterview['date']               = $this->post('date');
                                        $jobInterview['time']               = $this->post('time');
                                        $jobInterview['interview_location'] = $this->post('interview_location');
                                        $jobInterview['latitude']           = $this->post('latitude');
                                        $jobInterview['longitude']          = $this->post('longitude');
                                        $jobInterview['crd']                = datetime();
                                        $jobInterview['upd']                = datetime();
                                        $jobInterview = $this->common_model->insertData(JOB_INTERVIEWS,$jobInterview);
                                    }
                               
                            $title              = "Request interview";

                            $body_send    = ucfirst($this->authData->fullName).' you have been requested to interview for the '.$jobtitle->jobTitleName.' role'; 
                            //$body_send    = ucfirst($this->authData->fullName).' has requested an interview on '.$jobInterview['date'].''.$jobInterview['time'].' At '.$jobInterview['interview_location']; 

                            $body_save    = '[UNAME] you have been requested to interview for the '.$jobtitle->jobTitleName.' role'; 
                            //$body_save   = '[UNAME] has requested an interview on '.$jobInterview['date'].''.$jobInterview['time'].' At '.$jobInterview['interview_location']; 


                            $notif_type         = 'interview_request'; 


                             $send= array();
                             $send['email']             = $user_info->email;
                             $send['fullName']          = ucfirst($user_info->fullName);
                             $send['request_by']        = $this->authData->fullName;
                             //$send['subject']           = 'Request for interview';

                             $send['subject']           = 'You have received an interview request!';

                             //$send['msg']  = 'We would like to invite you to attend an interview. At this interview you will be meeting with '.ucfirst($this->post('interviewer_name')).' on';

                             $send['msg']  = 'it is time to get excited! You have received an interview request to meet with '.ucfirst($this->post('interviewer_name')).' for the '.$jobtitle->jobTitleName.' position on the '.$this->post('date'). ' at ' .$this->post('time'). ' at ' .$this->post('interview_location');

                             $send['sub_msg']           = 'To review, accept or decline the interview request, please login to our app and view your notifications. Clicking on the interview notification will take you where you need to go! Alternatively, if you click on the specific job, you’ll see it here.';
                             $send['interviewer_name']  = '';
                             $send['date']              = '';
                             $send['time']              = '';
                             $send['location']          = '';


                            $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$data['applied_by_user_id']);
                            $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where);
                            $response = array('status' => SUCCESS, 'message'=>'Interview request sent successfully to applicant');

                       }else if($job_applicants AND $this->post('job_application_status')==6){



                            $title              = "Job offered";
                            //$body_send          = ucfirst($userInfo->fullName).' offered job to you for the position of '.$jobtitle->jobTitleName.''; 
                            $body_send          = 'Congratulations! '.ucfirst($userInfo->fullName).' has would like to offer you the role as '.$jobtitle->jobTitleName.' with '.$userInfo->businessName; 
                            //$body_save          = '[UNAME] got Job offered'; 
                            $body_save          = 'Congratulations! [UNAME] has would like to offer you the role as '.$jobtitle->jobTitleName.' with '.$userInfo->businessName; 
                            $notif_type         = 'job_offered'; 

                             $send= array();
                             $send['email']             = $user_info->email;
                             $send['fullName']          = ucfirst($user_info->fullName);
                             $send['request_by']        = ucfirst($this->authData->fullName);
                             //$send['subject']           = 'Congratulations! on your new role/ your new employment contract';
                            $send['subject']   = 'Congratulations! You have been successful in obtaining the '.$jobtitle->jobTitleName.' position';

                             //$send['msg']  = 'I am pleased to offer you employment at '.ucfirst($userInfo->businessName).' for the position of '.ucfirst($jobtitle->jobTitleName).' .We are very pleased with your candidacy & we believe you will be a valuable addition to our team.';

                             $send['msg']  = 'Congratulations! The word in the grapevine is that you have been successful for the '.$jobtitle->jobTitleName.' role with '.$userInfo->businessName.'! On behalf of ConnektUs & ' .$userInfo->businessName. ', we wish you all the very best!';

                             $send['sub_msg']           = 'To review your progress, please login to our app and view your notifications. Clicking on the interview notification will take you where you need to go! Alternatively, if you click on the specific job, you will see it here.';
                             $send['interviewer_name']  = '';
                             $send['date']              = '';
                             $send['time']              = '';
                             $send['location']          = '';


                            $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$data['applied_by_user_id']);
                            $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where); 
                            $response = array('status' => SUCCESS, 'message'=>'Job offered successfuly to applicant');

                       }else{

                        $title              = "Job Not offered";
                        //$body_send    = ucfirst($userInfo->fullName).' you have not been selected for the position of '.ucfirst($jobtitle->jobTitleName).''; 
                        $body_send    = 'We are sorry to inform you that you have been unsuccessful regarding the '.ucfirst($jobtitle->jobTitleName).' role you recently applied for'; 
                            //$body_save          = '[UNAME] job Not offered to you'; 
                            $body_save          = 'We are sorry to inform you that you have been unsuccessful regarding the '.ucfirst($jobtitle->jobTitleName).' role you recently applied for'; 
                            $notif_type         = 'job_offered_reject'; 

                            $send= array();
                            $send['email']             = $user_info->email;
                            $send['fullName']          = ucfirst($user_info->fullName);
                            $send['request_by']        = ucfirst($this->authData->fullName);
                            //$send['subject']           = 'Well tried! '.ucfirst($user_info->fullName).' you have not been selected for the position of '.ucfirst($jobtitle->jobTitleName);
                            $send['subject']           = 'We are sorry to inform you that your application has been unsuccessful';

                            $send['msg']  = 'You have not been selected for the position of '.ucfirst($jobtitle->jobTitleName).' at '.ucfirst($userInfo->businessName).'. The interview team appreciates the time you spent coming to the company for your recent interview. We wish you success with your continuous job search.';
                            $send['sub_msg']           = '';
                            $send['interviewer_name']  = '';
                            $send['date']              = '';
                            $send['time']              = '';
                            $send['location']          = '';

                            $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$data['applied_by_user_id']);
                            $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where); 
                            $response = array('status' => SUCCESS, 'message'=>'Job offered Not sent to applicant');

                       }

                        if($this->post('job_application_status')==3 OR $this->post('job_application_status')==2 OR $this->post('job_application_status')==1 OR $this->post('job_application_status')==6 OR $this->post('job_application_status')==7){

                        if($user_info->isNotify==1){   
                        $notif_msg = $this->send_push_notification($this->authData->fullName,$registrationIds,$title,$body_send,$job_id,$notif_type,$userType,$profileImage,$basicjob->job_type,$current_user_id);

                             $notif_msg['body']  = $body_save; 
                             $notif_msg['reference_type']  = $basicjob->job_type;
                             $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$notification_for,'reference_id'=>$job_id, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                            $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);  
                        }

                            //SEND EMAIL
                            if($user_info->isNotifyEmail==1){

                             $message = $this->load->view('email/email_interview_request', $send, true);
                             $this->load->library('Smtp_email');
                             $subject = $send['subject'];
                             $isSend = $this->smtp_email->send_mail($send['email'],$send['subject'],$message);
                            }  
                        }    


                    }

                   $this->response($response);die(); 
                }

            }
           
        }   
               
    }

    // accept reject jobseekar side (accept interview request and reject interview request)
    function acceptRejectbusiness_post(){
        
        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_application_status', 'Job Status', 'trim|required');
        //$this->form_validation->set_rules('applied_by_user_id', 'User Id', 'trim|required');
        $userId =$this->authData->userId;
         
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='individual'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            $wherej     = array('jobId'=>$this->post('job_id'));   
            $basicjob   = $this->common_model->getsingle(JOBS,$wherej,$fld =NULL,$order_by = '',$order = '');
 
            if(empty($basicjob)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 

            }

            $wherejobTitle = array('jobId'=>$this->post('job_id'));
            $job_title = $this->common_model->getsingle(JOBS,$wherejobTitle,$fld =NULL,$order_by = '',$order = '');

            $wheretitle = array('jobTitleId'=>$job_title->job_title_id);
            $jobtitle = $this->common_model->getsingle(JOB_TITLES,$wheretitle,$fld =NULL,$order_by = '',$order = '');
    
            $job_id           = $this->post('job_id');
            $where            = array('job_id'=>$job_id,'applied_by_user_id'=>$userId);
            $jobapplicants    = $this->common_model->getsingle(JOB_APPLICANTS,$where,$fld =NULL,$order_by = '',$order = '');
            if(!empty($jobapplicants)){
            $where            = array('job_applicant_id'=>$jobapplicants->jobApplicantId);
            $jobInterview     = $this->common_model->getsingle(JOB_INTERVIEWS,$where,$fld =NULL,$order_by = '',$order = '');
            }

            if(empty($jobapplicants)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==4 AND $this->post('job_application_status')==4){

                $response = array('status' => FAIL, 'message'=>'Applicant already accept job request.');
                $this->response($response);die();

            }else if($jobapplicants->job_application_status ==5 AND $this->post('job_application_status')==5){

                $response = array('status' => FAIL, 'message'=>'Applicant already decline job request.');
                $this->response($response);die();

            }else{

                if($this->post('job_application_status')==4 OR $this->post('job_application_status')==5){

                   $data['job_application_status']  =   $this->post('job_application_status');
                   $data['applied_by_user_id']      =   $userId;

                    $notification_for = $basicjob->posted_by_user_id;
                    $current_user_id  = $this->authData->userId;
                    $user_info        = $this->common_model->getsingle(USERS, array('userId'=>$notification_for),'userId, 
                    deviceToken,isNotify,isNotifyEmail,userType,email,fullName,businessName');

                   
                    if(!empty($user_info)){

                        $registrationIds[] = $user_info->deviceToken;
                        if(!empty($this->authData->profileImage)){
                            $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                        }else{
                            $profileImage = base_url().DEFAULT_USER;
                        }

                       if($this->post('job_application_status')==4){

                        $title         = "Interview request";
                        //$body_send     = ucfirst($this->authData->fullName).' accepted your job interview request'; 
                        $body_send  = 'Great news! '.ucfirst($this->authData->fullName).' accepted your interview request about the '.ucfirst($jobtitle->jobTitleName).' position!'; 
                        //$body_save          = '[UNAME] accept  Your job request'; 
                        //$body_save          = '[UNAME] accepted your job interview request'; 
                       $body_save  = 'Great news! [UNAME] accepted your interview request about the '.ucfirst($jobtitle->jobTitleName).' position!'; 
                        $notif_type         = 'accept_interview_request';  

                        $send= array();
                        $send['email']             = $user_info->email;
                        $send['fullName']          = ucfirst($user_info->fullName);
                        $send['request_by']        = ucfirst($this->authData->fullName);
                        //$send['subject']           = 'Accepted your job interview request';
                        $send['subject']           = ucfirst($this->authData->fullName).' has accepted your interview request!';

                        //$send['msg']  = 'Thank you very much for the invitation to interview for the '.ucfirst($jobtitle->jobTitleName).' position. i appreciate the opportunity, and i look forward to meeting with '.ucfirst($jobInterview->interviewer_name);

                        $send['msg']  = 'Great news! Your interview request has been accepted by '.$this->authData->fullName.' for the '.$jobtitle->jobTitleName.' position on the '.$jobInterview->date.' at '.$jobInterview->time.' at '.$jobInterview->interview_location;

                        $send['sub_msg']           = 'To review the interview request, please login to our app and view your notifications. Clicking on the interview notification will take you where you need to go!';
                        $send['interviewer_name']  = '';
                        $send['date']              = '';
                        $send['time']              = '';
                        $send['location']          = '';

                        //SEND EMAIL
                        if($user_info->isNotifyEmail==1){

                            $message = $this->load->view('email/email_interview_request', $send, true);
                            $this->load->library('Smtp_email');
                            $subject = $send['subject'];
                            $isSend = $this->smtp_email->send_mail($send['email'],$send['subject'],$message);
                        }
                        if($user_info->isNotify==1){  

                            $notif_msg  = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $job_id, $notif_type,$user_info->userType,$profileImage,$basicjob->job_type,$current_user_id);
                             $notif_msg['body']  = $body_save; 
                             $notif_msg['reference_type']  = $basicjob->job_type;
                            $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$notification_for,'reference_id'=>$job_id, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                            $this->notification_model->save_notification(NOTIFICATIONS, $insertdata); 
                       }

                    $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$userId);
                    $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where);

                    $response = array('status' => SUCCESS, 'message'=>'You have successfully accepted the request.');

                       }else if($this->post('job_application_status')==5){

                        $title              = "Interview request";
                        $body_send          = ucfirst($this->authData->fullName).' rejected your job interview request'; 
                        //$body_save          = '[UNAME] decline  Your job request'; 
                        $body_save          = '[UNAME] rejected your job interview request'; 
                        $notif_type         = 'decline_interview_request'; 

                        $send= array();
                        $send['email']             = $user_info->email; 
                        $send['fullName']          = ucfirst($user_info->fullName);
                        $send['request_by']        = ucfirst($this->authData->fullName);
                        //$send['subject']           = 'Rejected your job interview request';
                        $send['subject']           = 'Your interview request has been declined';

                        //$send['msg']  = 'Thank you very much for considering me to interview for the position of '.ucfirst($jobtitle->jobTitleName).' & for inviting me to interview with the '.ucfirst($jobInterview->interviewer_name).' with '.ucfirst($user_info->businessName).'. However,i would like to withdraw my application for the job.Again thank you for your consideration';

                        $send['msg']  = 'Sorry, but it appears ' .$this->authData->fullName. ' has declined your interview request with '.$jobInterview->interviewer_name.' on '.$jobInterview->date.' at '.$jobInterview->date.' at ' .$jobInterview->interview_location. '! Perhaps the time did not suit! Try reach out to '.$user_info->fullName.' for an alternate time! Good Luck!';

                        $send['sub_msg']           = 'To review or send another interview request, please login to our app and view the candidates application via the job ad.';

                        $send['interviewer_name']  = '';
                        $send['date']              = $jobInterview->date;
                        $send['time']              = $jobInterview->time;
                        $send['location']          = $jobInterview->interview_location;

                        //SEND EMAIL
                        if($user_info->isNotifyEmail==1){

                            $message = $this->load->view('email/email_interview_request', $send, true);
                            $this->load->library('Smtp_email');
                            $subject = $send['subject'];
                            $isSend = $this->smtp_email->send_mail($send['email'],$send['subject'],$message);
                        }

                        if($user_info->isNotify==1){  

                            $notif_msg  = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $job_id, $notif_type,$user_info->userType,$profileImage,$basicjob->job_type,$current_user_id);
                            $notif_msg['body']  = $body_save; 
                            $notif_msg['reference_type']  = $basicjob->job_type;
                            $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$notification_for,'reference_id'=>$job_id, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                            $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
                        }
                        $data['job_application_status'] = 1;
                        $where = array('job_id'=>$this->post('job_id'),'applied_by_user_id'=>$userId);
                        $job_applicants = $this->common_model->updateFields(JOB_APPLICANTS, $data, $where);


                        $response = array('status' => SUCCESS, 'message'=>'You have rejected the interview request.');

                       }

                     
                      
                    }

                   $this->response($response);die(); 
                }

            }
           
        } 

    }

    // change job status (draft or active)
    function changeJobStatus_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('job_status', 'Job Status', 'trim|required');
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='business'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

             $wherejob     = array('jobId'=>$this->post('job_id'),'posted_by_user_id'=>$userId);
             $basicjob   = $this->common_model->getsingle(JOBS,$wherejob,$fld =NULL,$order_by = '',$order = '');

            if(empty($basicjob)){
                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 
            }
            if(!empty($basicjob->is_expired==1)){
                $response = array('status' => FAIL, 'message'=>'This job is already expired.');
                $this->response($response);die(); 
            }
           

            $job_id           = $this->post('job_id');
            $data['status']   = $this->post('job_status');  
            $where            = array('jobId'=>$this->post('job_id'));
            $job_status   = $this->common_model->updateData(JOBS,$data,$where);
            if($job_status){

                if($data['status']==1){
                $response = array('status' => SUCCESS, 'message'=>'Job active successfully.','job_status'=>1);
                }else{
                $response = array('status' =>SUCCESS, 'message'=>'Job inactive successfully.','job_status'=>0);    
                }
                $this->response($response);die(); 
            }
                      
        } 

    }


    // SAVE JOBS BY JOBSEEKAR 
    function saveJobsIndividualSide_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='individual'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            $wherejobs     = array('jobId'=>$this->post('job_id'));
            $job           = $this->common_model->getsingle(JOBS,$wherejobs,$fld =NULL,$order_by = '',$order = '');

            if(empty($job)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 
            }

            $whereJobId  = array('job_id'=>$this->post('job_id'),'save_by_user_id'=>$userId);
            $is_jobs = $this->common_model->getsingle(SAVE_JOBS,$whereJobId,$fld =NULL,$order_by = '',$order='');

            if($is_jobs){

                $response = array('status' => FAIL, 'message'=>'Job already saved.');
                $this->response($response);die(); 
            }

            $data['job_id']                = $this->post('job_id');
            $data['save_by_user_id']       = $userId;  
            $data['crd']                   = datetime();  
            $data['upd']                   = datetime();  
            $saveJobs                  = $this->common_model->insertData(SAVE_JOBS,$data);
            if($saveJobs){
               $response = array('status' => SUCCESS, 'message'=>'Job application has been saved successfully.');
              $this->response($response);die(); 
            }
                      
        } 
    }
    // END OF FUNCTION

    //REMOVE JOB WHICH IS SAVED BY USERS- JOBSEEKAR
    function removeSavedJobs_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='individual'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }

            $wherejobs  = array('job_id'=>$this->post('job_id'),'save_by_user_id'=>$userId);
            $is_jobs   = $this->common_model->getsingle(SAVE_JOBS,$wherejobs,$fld =NULL,$order_by = '',$order = '');

            if(empty($is_jobs)){

                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 
            }

            $is_delete   = $this->common_model->deleteData(SAVE_JOBS,$wherejobs);

            if($is_delete){
               $response = array('status' => SUCCESS, 'message'=>'Job application has been removed successfully.');
              $this->response($response);die(); 
            }

                      
        } 
    }
    //END OF THE FUNCTION

    //SAVE JOB LIST WHICH IS SAVED BY USERS- JOBSEEKAR
    function savedJobList_post(){
        $this->check_service_auth();
        $job_title      = $this->post('job_title');
        if($this->post('pagination') == 1){
            $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
            $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }  
        if($this->authData->userType!='individual'){
            $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
            $this->response($response);die();
        }
        
        $jobList    = $this->Jobs_model->get_save_job_list_individual($limit,$offset,$job_title);
        $response   = array('status' => SUCCESS, 'message' =>200,'data'=>$jobList);
        $this->response($response);die();
      
    }
    //END OF THE FUNCTION

    // get job title and job title count registered with job
    function getJobTitle_post(){
        $this->check_service_auth();
        if(!empty($this->post('search'))){
            $search =$this->post('search');
        }else{
            $search ='';
        }
        $result = $this->Jobs_model->getJobTitle_data($search);
        $response = array('status' => SUCCESS, 'data'=>$result);
        $this->response($response);  
    }

    // job tracking individual side (like individual request shortlisted ,interview request etc)
    function jobTrackProcess_post(){
        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='individual'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }
             $jobId         = $this->post('job_id');
             $wherejob      = array('jobId'=>$jobId);
             $basicjob      = $this->common_model->getsingle(JOBS,$wherejob,$fld =NULL,$order_by = '',$order = '');
            if(empty($basicjob)){
                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 
            }

            $jobTrackStatus = $this->Jobs_model->get_job_track_process($jobId,$userId);
             if(!empty($jobTrackStatus)){
                 $response   = array('status' => SUCCESS, 'message' =>200,'data'=>$jobTrackStatus);
            }else{
                $response   = array('status' => FAIL, 'message' =>'No Track Record Found.');
            }
          

            $this->response($response);die();
                      
        } 
    }

    //APPLY JOBS INDIVIDUAL SIE
    function appliedJob_post(){
        $this->check_service_auth();
        $job_title      = $this->post('job_title');
        if($this->post('pagination') == 1){
            $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
            $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }  
        if($this->authData->userType!='individual'){
            $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
            $this->response($response);die();
        }
        
        $jobList    = $this->Jobs_model->get_applide_job_list_individual($limit,$offset,$job_title);
        $response   = array('status' => SUCCESS, 'message' =>200,'data'=>$jobList);
        $this->response($response);die();    
    }


    // ACTIVE JOBS INDIVIDUAL SIDE
    function activeJobs_post(){

        $this->check_service_auth();
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        $userId =$this->authData->userId;

        if($this->form_validation->run() == FALSE){

           $response = array('status' => FAIL,'message' =>preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
          $this->response($response);die();

        }else{
            if($this->post('pagination') == 1){
                $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
                $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
            }else{
                $offset ='';
                $limit =20000;
            }  
           /* if($this->authData->userType!='individual'){
                $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die();
            }*/
            $userId     =  $this->post('user_id');
            $jobList    = $this->Jobs_model->get_active_job_list_individual($limit,$offset, $userId);
            $response   = array('status' => SUCCESS, 'message' =>200,'data'=>$jobList);
            $this->response($response);die();  
        }  
    }

     // VIEW LIST API BUSINESSS SIDE 
    function jobsViewerList_post(){
        $this->check_service_auth();

        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();
        }else{

            if($this->authData->userType!='business'){
                $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die();
            }

            $jobId     =  $this->post('job_id');
            $wherejob  = array('jobId'=>$jobId,'posted_by_user_id'=>$userId);
            $jobexits  = $this->common_model->getsingle(JOBS,$wherejob,$fld =NULL,$order_by = '',$order = '');

            if(empty($jobexits)){
                $response = array('status' => FAIL, 'message' =>'You are not authorised for this action.');
                $this->response($response);die();
            }

        if($this->post('pagination') == 1){
            $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
            $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }  
    
        $jobList    = $this->Jobs_model->get_job_viewer_list($limit,$offset,$jobId);
        $response   = array('status' => SUCCESS, 'message' =>200,'data'=>$jobList);
        $this->response($response);die();  


      }  
    }

    //JOB TRACK PROCESS BUSINESS SIDE
    function jobTrackProcessBusniessSide_post(){
        $this->check_service_auth();
        $this->form_validation->set_rules('job_id', 'Job id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        //$userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='business'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }
             $jobId         = $this->post('job_id');
             $userId        = $this->post('user_id');
             $wherejob      = array('jobId'=>$jobId);
             $basicjob      = $this->common_model->getsingle(JOBS,$wherejob,$fld =NULL,$order_by = '',$order = '');
            if(empty($basicjob)){
                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 
            }

            $jobTrackStatus = $this->Jobs_model->get_job_track_process_business_side($jobId,$userId);
             
            if(!empty($jobTrackStatus)){
                 $response   = array('status' => SUCCESS, 'message' =>200,'data'=>$jobTrackStatus);
            }else{
                $response   = array('status' => FAIL, 'message' =>'No Track Record Found.');
            }
           
            $this->response($response);die();
                      
        } 
    }


    function badge_count_unread_jobs_get(){
        $this->check_service_auth();
        $userId  = $this->authData->userId;
        $userType  = $this->authData->userType;
        $where = array('isViewed'=>0,'notification_for'=>$userId);
        if($userType == 'individual'){
            $get = array('shortlisted_job','interview_request','rejected_job','job_offered_reject','job_offered','new_job');
            $total_count = $this->Jobs_model->get_total_counts($get,$where);
        }else{
            $get = array('accept_interview_request','apply_job','decline_interview_request');
            $total_count = $this->Jobs_model->get_total_counts($get,$where);
        }
        $res = $this->Jobs_model->get_count_viewed_data(NOTIFICATIONS,$get,$userId);
        $count =0;
        $count = $res[0]->accept_interview_request+ $res[1]->apply_job+$res[2]->decline_interview_request;
    
        $response = array('status'=>SUCCESS,'message'=>'count','total'=>$count,'data'=>$res);
        $this->response($response);
    }


    function updateUserBillingInfo_post(){

        $this->check_service_auth();
        //$this->form_validation->set_rules('billing_entity',  'Billing Entity', 'trim|required');
        $address_same_as_profile           = $this->post('address_same_as_profile');

        if($address_same_as_profile!=1){

            $this->form_validation->set_rules('billing_address', 'Billing Address', 'trim|required');
            $this->form_validation->set_rules('billing_city',    'Blling City', 'trim|required');
            $this->form_validation->set_rules('billing_state',   'Billing State', 'trim|required');
            $this->form_validation->set_rules('billing_country', 'Billing Country', 'trim|required');
            $this->form_validation->set_rules('billing_latitude','Billing Latitude', 'trim|required');
            $this->form_validation->set_rules('billing_longitude','Billing Longitude', 'trim|required');

        }
        $this->form_validation->set_rules('abn', 'Abn', 'trim|required');
        $this->form_validation->set_rules('billing_email', 'Billing Email', 'trim|required');
        //$this->form_validation->set_rules('user_id', 'User id', 'trim|required');
       
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){

            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

            if($this->authData->userType!='business'){
               $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
                $this->response($response);die(); 
            }
            $data['billing_entity']            = $this->post('billing_entity');
            $data['billing_address']           = $this->post('billing_address');
            $data['billing_city']              = $this->post('billing_city');
            $data['billing_state']             = $this->post('billing_state');
            $data['billing_country']           = $this->post('billing_country');
            $data['billing_latitude']          = $this->post('billing_latitude');
            $data['billing_longitude']         = $this->post('billing_longitude');
            $data['abn']                       = $this->post('abn');
            $data['billing_email']             = $this->post('billing_email');
            $data['upd']                       = datetime();
            

            $whereemail  = array('billing_email'=>$data['billing_email'],'user_id!=' =>$userId);
            $billingEmailInfo = $this->common_model->getsingle(USER_BLLLING_INFO,$whereemail,$fld =NULL,$order_by = '',$order = '');
            if(!empty($billingEmailInfo)){

                $response = array('status' => FAIL, 'message'=>'Billing email walready exit.');
                $this->response($response);die(); 
            }
            if(!is_numeric($data['abn'])){

                $response = array('status' => FAIL, 'message'=>'ABN/ACN should content only numeric value.');
                $this->response($response);die(); 

            }else if(count($data['abn'])>=7 AND count($data['abn'])<=9 AND count($data['abn'])!=8){

                 $response = array('status' => FAIL, 'message'=>'Please provide valid lenght for ABN/ACN.');
                $this->response($response);die(); 
            }

            if($address_same_as_profile==1){

                $whereuser = array('user_id'=>$userId);
                $userinfo      = $this->common_model->getsingle(USER_META,$whereuser,$fld =NULL,$order_by ='',$order='');
                $data['billing_address']    = $userinfo->address; 
                $data['billing_city']       = $userinfo->city;    
                $data['billing_state']      = $userinfo->state;   
                $data['billing_country']    = $userinfo->country; 
                $data['billing_latitude']   = $userinfo->latitude;
                $data['billing_longitude']  = $userinfo->longitude;

            }
            
            $wherebilling  = array('user_id'=>$userId);
            $billingInfo      = $this->common_model->getsingle(USER_BLLLING_INFO,$wherebilling,$fld =NULL,$order_by = '',$order = '');

            if(empty($billingInfo)){

                $data['user_id']     = $userId;
                $data['crd']         = datetime();
                $billingdetails      = $this->common_model->insertData(USER_BLLLING_INFO,$data);

            }else{

                $billingdetails      = $this->common_model->updateFields(USER_BLLLING_INFO,$data,$wherebilling);
            }

            if(empty($billingdetails)){
                $response = array('status' => FAIL, 'message'=>'Somthing went wong.');
                $this->response($response);die(); 
            }
           
        
            $billingInfomation    = $this->common_model->getsingle(USER_BLLLING_INFO,$wherebilling,$fld =NULL,$order_by = '',$order = '');
              $billingInfomation->is_billing_complete =1;
            $response   = array('status' => SUCCESS, 'message' =>'Billing details successfully updated','data'=>$billingInfomation);
            $this->response($response);die();
                      
        } 

    }

    function getUserBillingInfo_get(){
        $this->check_service_auth();
        $userId =$this->authData->userId;

        if($this->authData->userType!='business'){

           $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
           $this->response($response);die(); 

        }

        $wherebilling  = array('user_id'=>$userId);
        $billingInfo      = $this->common_model->getsingle(USER_BLLLING_INFO,$wherebilling,$fld =NULL,$order_by = '',$order = '');

        if(!empty($billingInfo)){

            $response   = array('status' => SUCCESS, 'message' =>200 ,'data'=>$billingInfo);
            $this->response($response);die();

        }else{

            $response = array('status' => FAIL, 'message'=>'No data found.');
           $this->response($response);die(); 

        }
    }


    function getUserPurchaseHistory_get(){
        $this->check_service_auth();
        $userId =$this->authData->userId;

        if($this->get('pagination') == 1){
            $offset = !empty($this->get('offset'))? ($this->get('offset')):''; 
            $limit = !empty($this->get('limit'))? ($this->get('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }  

        if($this->authData->userType!='business'){

           $response = array('status' => FAIL, 'message'=>'You are not authorised for this action.');
           $this->response($response);die(); 

        }

        $wherebilling  = array('user_id'=>$userId);
        $billingInfo   = $this->Jobs_model->get_user_purchase_history($wherebilling,$limit,$offset);
        $totalpurchaseamt =0;  
        $toalpurchase =0;  

        if(!empty($billingInfo)){

            $toalpurchase = count($billingInfo);
            foreach ($billingInfo as $key => $value){

                $totalpurchaseamt = $totalpurchaseamt+ $value->amount;
            }
        }  

        
        if(!empty($billingInfo)){

            $response   = array('status' => SUCCESS, 'message' =>200 ,'data'=>$billingInfo,'total_amount'=>$totalpurchaseamt,'total_purchase'=>$toalpurchase);
            $this->response($response);die();

        }else{

            $response = array('status' => FAIL, 'message'=>'No data found.');
           $this->response($response);die(); 

        }
    }

    function emailNotificationOnOff_post(){
        
        $this->check_service_auth();
        $this->form_validation->set_rules('is_email_notifiy', 'Billing Email', 'trim|required');
        $userId =$this->authData->userId;
        if($this->form_validation->run() == FALSE){

            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die();

        }else{

           $msg= '';
           $is_email_notifiy = $this->post('is_email_notifiy');
            if($is_email_notifiy==1){

                $data['isNotifyEmail'] = $is_email_notifiy;
                $msg ='Email notification enable successfuly.';
                $msg1='Email notification already enable.';

            }else if($is_email_notifiy==0){

                $data['isNotifyEmail'] = $is_email_notifiy;
                $msg ='Email notification disable successfuly.';
                $msg1 ='Email notification already disable.';

            }else{

                $response = array('status' => FAIL, 'message'=>'Somthing went wrong.');
                $this->response($response);die();
           }
            $wherebilling = array('userId'=>$userId);
            $userInfo    = $this->common_model->getsingle(USERS,$wherebilling,$fld =NULL,$order_by = '',$order = '');

            if($userInfo->isNotifyEmail==$data['isNotifyEmail']){

                $response = array('status' => SUCCESS, 'message'=>$msg1);
                $this->response($response);die();
            }
          
           $isNotify    = $this->common_model->updateFields(USERS,$data,$wherebilling);
           if($isNotify){

                $response = array('status' => SUCCESS, 'message'=>$msg,'isNotifyEmail'=>$is_email_notifiy);
                $this->response($response);die();

            }else{
              $response = array('status' => FAIL, 'message'=>'Somthing went wrong.');
              $this->response($response);die();
           } 
        }
       
    }


}//End Class 

     
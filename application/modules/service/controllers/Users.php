<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Users extends CommonService{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model'); //load user model
        $this->list_limit = 20;  //limit record

        // Give message for job profile completed, so mobile application will // show this when user lands on profile section after signup
        $this->jobProfileCompletedMsg = 'Please complete your experience so employers can find you.';
    }
    
    function getDropdownList_get(){

        //check for auth
        $this->check_service_auth();
        $offset = $this->get('offset'); $limit = $this->get('limit');
        /*if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }*/
        
        $where = array('userType'=>$this->authData->userType,'status'=>1);
        //,$offset,$limit
        $result = $this->Users_model->getDropdown_data($where);
        $response = array('status' => SUCCESS, 'result'=>$result, 'jobProfileCompletedMsg'=>$this->jobProfileCompletedMsg);
        $this->response($response);  
    }


    //validation callback for checking alpha_spaces
    function _alpha_spaces_check($string){
        if(alpha_spaces($string)){
            return true;
        }
        else{
            $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
            return FALSE;
        }
    }


    //update or insert business profile here
    function updateBusinessProfile_post(){
        log_event(json_encode($_POST));
         //check for auth
        $this->check_service_auth();
        
        $current_user_id = $this->authData->userId; 

        $this->form_validation->set_rules('area_of_specialization', 'Area of specialization', 'trim|required'); 
       
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[2]|max_length[200]');
        //$this->form_validation->set_rules('bio', 'Bio', 'trim|required|min_length[2]|max_length[200]');
        $this->form_validation->set_rules('job_title', 'Job title', 'trim|required');


        //validation here
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); die;
        }

        if(empty($_FILES['company_logo']['name'])){
            $response = array('status' => FAIL, 'message' => 'Company logo is requried');
            $this->response($response); die;;
        }

        $company_logo = array();
        if(!empty($_FILES['company_logo']['name'])){
            
            $folder = 'company_logo';
            $this->load->model('Image_model');
            $company_logo = $this->Image_model->updateMedia('company_logo',$folder);

        }
        if(is_array($company_logo) && !empty($company_logo['error']))
        {
            $response = array('status' => FAIL, 'message' =>$company_logo['error']);
            $this->response($response);
        }


        $set = array('address','city','state','country', 'bio','latitude', 'longitude','description');
        foreach ($set as $key => $val) {
            $post= $this->post($val);
            if(!empty($post))
                $insert_data[$val] = $post;
        }

        if(is_string($company_logo) && !empty($company_logo)){

            $insert_data['company_logo']       = $company_logo;
        }
        
        elseif (filter_var($this->input->post('company_logo'), FILTER_VALIDATE_URL)) {
            $insert_data['company_logo'] = $this->input->post('company_logo');
        }
       
        $insert_data['user_id'] = $current_user_id;  // user id
        $insert_data['jobTitle_id'] = $this->post('job_title');
        $where  = array('user_id'=>$current_user_id);
      
        $is_exist = $this->common_model->is_data_exists(USER_META, $where);
        if($is_exist){
            $phone = $this->post('phone');
            $this->common_model->updateData(USERS,array('upd'=>datetime(),'phone'=>$phone),array('userId'=>$current_user_id));
            $this->common_model->updateFields(USER_META, $insert_data,$where);
            $insert_images =  $this->common_model->getsingle(USER_META, $where,'company_logo');
            

        }else{
            
            $meta_id = $this->common_model->insertData(USER_META, $insert_data);  //insert data
            $where = array('metaID'=>$meta_id);
            $insert_images =  $this->common_model->getsingle(USER_META, $where,'company_logo');
            if(!$meta_id){ 

                $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            }
            $this->common_model->updateFields(USERS,array('isProfile'=>1,'upd'=>datetime()),array('userId'=>$current_user_id));
            
        }
        
    
        $exist = $this->common_model->is_data_exists(USER_SPECIALIZATION_MAPPING, array('user_id'=>$current_user_id));
        $speciality_data = array('user_id'=>$current_user_id, 'specialization_id'=>$this->input->post('area_of_specialization'));
        if($exist){

            $this->common_model->updateFields(USER_SPECIALIZATION_MAPPING, $speciality_data,array('user_id'=>$current_user_id));
        }else{
            $speciality_id = $this->common_model->insertData(USER_SPECIALIZATION_MAPPING, $speciality_data);
            if(!$speciality_id){
                $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            }
        }
        if(!empty($insert_images)) {
            $dataImage = base_url().COMPANY_LOGO.$insert_images->company_logo;
        }
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(125),'company_logo'=>$dataImage);
        $this->response($response);
    }


    //update or insert indivisual's basic info here
    function updateBasicInfo_post(){
        log_event(json_encode($_POST));
         //check for auth
        $this->check_service_auth();

        $current_user_id = $this->authData->userId;

        $this->form_validation->set_rules('area_of_specialization', 'Area of specialization', 'trim|required');
       
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[2]|max_length[200]');
        //$this->form_validation->set_rules('bio', 'Bio', 'trim|required|min_length[2]|max_length[200]');
        $this->form_validation->set_rules('value', 'value', 'trim|required');
        $this->form_validation->set_rules('strength', 'strength', 'trim|required');
        
        
        //validation here
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); die;
        }
        $set = array('address','city','state','country', 'bio','latitude', 'longitude');
        foreach ($set as $key => $val) {
            $post= $this->post($val);
            if(!empty($post))
                $insert_data[$val] = $post;
        }
        $insert_data['user_id'] = $current_user_id;  // user id
        $where  = array('user_id'=>$current_user_id);
       
        $is_exist = $this->common_model->is_data_exists(USER_META, $where );
        if($is_exist){
            $phone = $this->post('phone');
            $this->common_model->updateData(USERS,array('upd'=>datetime(),'phone'=>$phone),array('userId'=>$current_user_id));
            $this->common_model->updateData(USER_META, $insert_data,$where );
        }else{
            
            $meta_id = $this->common_model->insertData(USER_META, $insert_data);  //insert data
            if(!$meta_id){
                $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            }
            $this->common_model->updateData(USERS,array('upd'=>datetime()),array('userId'=>$current_user_id ));
            $this->common_model->updateFields(USERS,array('isProfile'=>1),array('userId'=>$current_user_id));
        } 
       
        //check whether value empty or not, if not (delete all previous values and insert new)
        
        $this->common_model->deleteData(USER_VALUE_MAPPING,$where );

         //insert multiple values here
        foreach (explode(',', $this->post('value'))  as  $value) {

            $this->common_model->insertData(USER_VALUE_MAPPING, array('user_id'=>$current_user_id, 'value_id'=>$value));
       
        }
       
      
        //check whether strength empty or not, if not (delete all previous strength and insert new)
        $this->common_model->deleteData(USER_STRENGTH_MAPPING,$where);

        //insert multiple strengths here
        foreach (explode(',', $this->post('strength'))  as  $val) {
           
            $this->common_model->insertData(USER_STRENGTH_MAPPING,array('user_id'=>$current_user_id, 'strength_id'=>$val));
       
        }
        
        
        //check whether area_of_specialization empty or not, if not (delete all previous area_of_specialization and insert new)

        $this->common_model->deleteData(USER_SPECIALIZATION_MAPPING,$where);
        //insert area_of_specialization data
        $this->common_model->insertData(USER_SPECIALIZATION_MAPPING, array('user_id'=>$current_user_id, 'specialization_id'=>$this->post('area_of_specialization')));
       
    
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(125));
   
        $this->response($response);
    } //End function



    function updateResume_post(){

         //check for auth
        $this->check_service_auth();

        $current_user_id = $this->authData->userId;
        $resume = array();
        if(!empty($_FILES['resume']['name'])){
            
            $folder = 'user_resume';
            $this->load->model('Image_model');
            $resume = $this->Image_model->updateContent('resume',$folder);

        }
        if(is_array($resume) && !empty($resume['error']))
        {
            $response = array('status' => FAIL, 'message' =>strip_tags($resume['error']));
            $this->response($response);
        }

        if(is_string($resume) && !empty($resume)){

            $insert_data['user_resume']       = $resume;
        }elseif (filter_var($this->input->post('resume'), FILTER_VALIDATE_URL)) {
            $insert_data['user_resume'] = $this->post('resume');
        }

        //upload user's cv here
        $cv = array();
        if(!empty($_FILES['cv']['name'])){
            
            $folder = 'user_cv';
            $this->load->model('Image_model');
            $cv = $this->Image_model->updateContent('cv',$folder);

        }
        if(is_array($cv) && !empty($cv['error']))
        {

            $response = array('status' => FAIL, 'message' =>strip_tags($cv['error']));
            $this->response($response);
        }

        if(is_string($cv) && !empty($cv)){

            $insert_data['user_cv']       = $cv;
        }elseif (filter_var($this->input->post('cv'), FILTER_VALIDATE_URL)) {
            $insert_data['user_cv'] = $this->post('cv');
        }

        $where  = array('user_id'=>$current_user_id);
        $this->common_model->updateData(USERS,array('upd'=>datetime()),array('userId'=>$current_user_id));
        $is_exist = $this->common_model->is_data_exists(USER_META, $where); 

        if(!$is_exist){
            $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            $this->response($response); 
        }
        
        if(!empty($insert_data )){

            $this->common_model->updateFields(USER_META, $insert_data,$where);
        }else{

            $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(142)); //fail- something went wrong
            $this->response($response); 
        }

        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(143));
        $this->response($response);
         
    } //End function


    //update or insert user experience here
    function updateExperience_post(){
        //check for auth
        $this->check_service_auth();
        //log_event(json_encode($_POST));
        $current_user_id = $this->authData->userId;
        $experience = $this->post('expectedSalary');
        $explodeExpected = explode('-',$experience);;
        $expectedFrom = $explodeExpected[0];
        $expectedTo = !empty($explodeExpected[1]) ? $explodeExpected[1]:'';
        $set = array('current_job_title','current_company','current_description','next_availability','next_speciality','next_location','employementType','currentExperience');
        foreach ($set as $key => $val) {
            $post= trim($this->post($val));
            if(!empty($post))
                $exp_data[$val] = $post;
        }

        $exp_data['expectedSalaryFrom'] = $expectedFrom;
        $exp_data['expectedSalaryTo'] = $expectedTo;

        // Check current job title
        $jobProfileCompleted = 0;
        if(isset($exp_data['current_job_title']) && !empty($exp_data['current_job_title'])){
            $jobProfileCompleted = 1;
        }

         if(empty($this->post('current_finish_date'))){
            $exp_data['current_finish_date'] = '';
        }

        $where = array('user_id'=>$current_user_id);
        $exp_data['user_id'] = $current_user_id;  // user id

        $is_exist = $this->common_model->is_data_exists(USER_EXPERIENCE, $where);
        if($is_exist){
            $is_profile_id = $this->common_model->getsingle(USER_EXPERIENCE, $where);

            
            $previous = $this->post('previous_role');
            $decode = json_decode($previous);
            
            foreach($decode as $value){
                $privious_up['previousRoleId'] = $value->previous_role_id;
                $privious_exp['previous_job_title_id'] = $value->previous_job_title;
                $privious_exp['previousCompanyName'] = $value->previous_company_name;
                $privious_exp['previousDescription'] = $value->previous_description;
                $privious_exp['experience'] = $value->experience;
                 $where_exp = array('previousRoleId'=>$privious_up['previousRoleId']);
                if(!empty($privious_up['previousRoleId'])){
                    $this->common_model->updateData(PREVIOUS_EXPERIENCE, $privious_exp,$where_exp);
                }else{
                   
                    $privious_exp['user_id'] = $current_user_id;
                    $this->common_model->insertData(PREVIOUS_EXPERIENCE, $privious_exp);
                }
            }
           
            $getTotalExperience = $this->Users_model->get_total_experience(PREVIOUS_EXPERIENCE,$where);

            $current_experience = !empty($this->post('currentExperience'))?$this->post('currentExperience'):0;
             $get_experience =   $getTotalExperience + $current_experience;
            $exp_data['totalExperience'] = $get_experience;
            $this->common_model->updateData(USER_EXPERIENCE, $exp_data,$where);

        }else{
            $is_profile_id = $this->common_model->getsingle(USER_EXPERIENCE, $where );
            $previous = $this->post('previous_role');
            $decode = json_decode($previous);
           
            foreach($decode as $value){
                $privious_exp[' previous_job_title_id'] = $value->previous_job_title;
                $privious_exp['previousCompanyName'] = $value->previous_company_name;
                $privious_exp['previousDescription'] = $value->previous_description;
                $privious_exp['experience'] = $value->experience;
                 $privious_exp['user_id'] = $current_user_id;
                $where_exp = array('user_id'=>$current_user_id,'previous_job_title_id'=>$privious_exp[' previous_job_title_id']);
              
               
                $this->common_model->insertData(PREVIOUS_EXPERIENCE, $privious_exp);
            }
              $exp_id = $this->common_model->insertData(USER_EXPERIENCE, $exp_data);  //insert data
            $is_profile_id = $this->common_model->getsingle(USER_EXPERIENCE, array('user_id'=>$exp_id) );
            $getTotalExperience = $this->Users_model->get_total_experience(PREVIOUS_EXPERIENCE,$where);
            $current_experience = !empty($this->post('currentExperience'))?$this->post('currentExperience'):0;
            $get_experience =   $getTotalExperience + $current_experience;
            $update_exper['totalExperience'] = $get_experience;
            
            $this->common_model->updateData(USER_EXPERIENCE, $update_exper,$where);    

            if(!$exp_id){
                $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response); 
            }   
        }

        // Update job profile status flag
        $user_upd_data = array('jobProfileCompleted'=> $jobProfileCompleted);
        $this->common_model->updateData(USERS, $user_upd_data, array('userId'=>$current_user_id));

        $privious_exp = $this->Users_model->get_previous_experience($where);
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(125),'previous_role'=>!empty($privious_exp)?$privious_exp:[]);
        $this->response($response);
        
    } //End function



    function getUserProfile_get(){

        //check for auth
        $this->check_service_auth();
        
        $current_user_id = $this->authData->userId;
        $user_type = $this->authData->userType;
        if($user_type == 'business'){
            $business_data = $this->Users_model->get_business_user(array('um.user_id'=>$current_user_id));
            $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(125),'business_profile'=>$business_data);
            $this->response($response); 
        }
        $where = array('user_id'=>$current_user_id);
        $basic_info = $this->Users_model->get_indivisual_basic_info(array('um.user_id'=>$current_user_id));
        $experience = $this->Users_model->get_user_experience($where);
        //$resume = $this->Users_model->get_user_resume($where);
        $resume =  !$this->Users_model->get_user_resume($where) ? '' : $this->Users_model->get_user_resume($where);

        // add job profile completed status to check individual has filled 
        // current experience data
        $basic_info->jobProfileCompleted = $this->authData->jobProfileCompleted;
        $basic_info->jobProfileCompletedMsg = $this->jobProfileCompletedMsg;
        
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'basic_info'=>$basic_info,'experience'=>$experience,'resume'=>$resume);
        $this->response($response); 
    
    } //End function


    //add review here
    function addReview_post(){

        //check for auth
        $this->check_service_auth();

        $current_user_id = $this->authData->userId;
        
        $this->form_validation->set_rules('rating', 'rating', 'trim|required|numeric');
        $this->form_validation->set_rules('comments', 'comment', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('review_for', 'Review', 'required', array('required'=>'Please select user to review for'));
        $this->form_validation->set_rules('is_anonymous', 'is_anonymous', 'trim|required');
        
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => spreg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); 
        }
        
            $is_id_exist = $this->common_model->is_id_exist(USERS,'userId',$this->post('review_for'));
            if(!$is_id_exist){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }

            $set = array('review_for', 'rating', 'comments','is_anonymous');
            foreach ($set as $key => $val) {
                $post= $this->post($val);
                if(!empty($post))
                    $insert_data[$val] = $post;
            }
            $insert_data['review_by'] = $current_user_id;
            $insert_data['created_on'] = datetime();
       
            $review_id = $this->common_model->insertData(REVIEWS, $insert_data);  //insert new post data
            if(!$review_id){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response); 
            }

            //send notification
            $review_for = $insert_data['review_for'];
            $review_data = $this->Users_model->get_user_reviews($review_for, 1, $offset=0); //get last inserted review data

            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$review_for), 'userId, deviceToken,isNotify,userType');
            if(!empty($user_info)){

                $last_review = $this->common_model->getsingle(REVIEWS, array('reviewId'=>$review_id));
                
                //prepare notification payload
                $registrationIds[] = $user_info->deviceToken; $title = "Reviewed your profile";
                $userType = $user_info->userType;
             
                if($last_review->is_anonymous == 1){
                    $body_send = 'Anonymously posted a review';  //body to be sent with current notification
                    $profileImage = base_url().DEFAULT_USER;
                }else{

                    if(!empty($this->authData->profileImage)){
                        $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                    }else{
                        $profileImage = base_url().DEFAULT_USER;
                    }

                    $body_send = $this->authData->fullName.' posted a review';  //body to be sent with current notification
                }
                
                $body_save = '[UNAME] posted a review'; //body to be saved in DB
                $notif_type = 'user_review';

                if($user_info->isNotify == 1){
                    $notif_msg = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $review_for, $notif_type,$userType,$profileImage);
                }
                
                //save notification into DB
                
                $notif_msg['body'] = $body_save; //replace body text with placeholder text
                //save notification
                $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$review_for, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
               
            }
       
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(125), 'reviewDetail'=>$review_data);        
        
            $this->response($response);

    } //End function


    function addFavourites_post(){

        //check for auth
        $this->check_service_auth();

        $current_user_id = $this->authData->userId;
        
        
        $this->form_validation->set_rules('favourite_for', 'favourite', 'required', array('required'=>'Please select user to favourite'));
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); 
        }

            $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$this->post('favourite_for'),'status'=>1));
            if(!$is_id_exist){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }
        
            $insert_data['favourite_for'] = $this->post('favourite_for') ;
            $insert_data['favourite_by'] = $current_user_id;
            $insert_data['created_on'] = datetime();
    
            $where = array('favourite_by'=>$current_user_id,'favourite_for'=>$insert_data['favourite_for']);
            //check if user added to favourites or not. if not, insert into favourites
            $is_exist = $this->common_model->is_data_exists(FAVOURITES,$where);
            if($is_exist){

                //remove from favourites if it already exist
                $this->common_model->deleteData(FAVOURITES,$where);
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(138),'isFavourite'=>0);
                $this->response($response); 
            }
       
            $fav_id = $this->common_model->insertData(FAVOURITES, $insert_data);  //insert new  data
            if(!$fav_id){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }

            //send notification
            $favourite_for = $insert_data['favourite_for'];
           
            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$favourite_for), 'userId, deviceToken,isNotify,userType');
            if(!empty($user_info)){
                //prepare notification payload
                $registrationIds[] = $user_info->deviceToken; $title = "Added to favourites";
                $userType = $user_info->userType;

                if(!empty($this->authData->profileImage)){
                    $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                }else{
                    $profileImage = base_url().DEFAULT_USER;
                }

                $body_send = $this->authData->fullName.' added to favourites';  //body to be sent with current notification
                $body_save = '[UNAME] added to favourites'; //body to be saved in DB
                $notif_type = 'user_favourites';

                if($user_info->isNotify == 1){
                    $notif_msg = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $favourite_for, $notif_type,$userType,$profileImage);
                }
                
                //save notification into DB
                
                $notif_msg['body'] = $body_save; //replace body text with placeholder text
                //save notification
                $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$favourite_for, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
            }
    
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(129),'isFavourite'=>1);  
            $this->response($response);

    } //End function

     //recommend  user here
    function addRecommends_post(){

        //check for auth
        $this->check_service_auth();

        $current_user_id = $this->authData->userId;
        
        
        $this->form_validation->set_rules('recommend_for', 'recommend', 'required', array('required'=>'Please select user to recommend'));
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); 
        }
        
            $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$this->post('recommend_for'),'status'=>1));
            if(!$is_id_exist){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }
        
            $insert_data['recommend_for'] = $this->post('recommend_for') ;
            $insert_data['recommend_by'] = $current_user_id;
            $insert_data['created_on'] = datetime();
    
            $where = array('recommend_by'=>$current_user_id,'recommend_for'=>$insert_data['recommend_for']);
            //check if user added to recommends or not. if not, insert into recommends
            $is_exist = $this->common_model->is_data_exists(RECOMMENDS,$where);
            if($is_exist){

                //remove from recommends if it already exist
                $this->common_model->deleteData(RECOMMENDS,$where);
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(140),'isRecommend'=>0);
                $this->response($response); 
            }
       
            $rec_id = $this->common_model->insertData(RECOMMENDS, $insert_data);  //insert new  data
            if(!$rec_id){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }

            //send notification 
            $recommend_for = $insert_data['recommend_for'];
           
            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$recommend_for), 'userId, deviceToken,isNotify,userType');
            if(!empty($user_info)){
                //prepare notification payload
                $registrationIds[] = $user_info->deviceToken; $title = "Recommended you";
                $userType = $user_info->userType;

                if(!empty($this->authData->profileImage)){
                    $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                }else{
                    $profileImage = base_url().DEFAULT_USER;
                }

                $body_send = $this->authData->fullName.' recommended you';  //body to be sent with current notification
                $body_save = '[UNAME] recommended you'; //body to be saved in DB
                $notif_type = 'user_recommends';

                if($user_info->isNotify == 1){
                    $notif_msg = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send, $recommend_for, $notif_type,$userType,$profileImage);
                }
                
                //save notification into DB
                
                $notif_msg['body'] = $body_save; //replace body text with placeholder text
                //save notification
                $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$recommend_for, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
            }

            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(139),'isRecommend'=>1);  
            $this->response($response);

    } //End function

    //get user reviews by user ID
    function getUserReviews_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id = $this->get('user_id');

        $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$user_id,'status'=>1));
        if(!$is_id_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
            $this->response($response); 
        }

        $user_reviews = $this->Users_model->get_user_reviews_list($user_id, $limit, $offset, $check_status=true);
        $response = array('status' => SUCCESS, 'reviewList'=>$user_reviews); //success msg
        $this->response($response);

    } //End function


     //get user recommends by user ID
    function getUserRecommends_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id = $this->get('user_id');
        $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$user_id,'status'=>1));
        if(!$is_id_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
            $this->response($response); 
        }
        $user_recommends = $this->Users_model->get_user_recommend_list($user_id, $limit, $offset, $check_status=true);
        $response = array('status' => SUCCESS, 'recommendList'=>$user_recommends); //success msg
        $this->response($response);

    } //End function


    //get user favourites by user ID
    function getUserFavourites_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id = $this->get('user_id');
        $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$user_id,'status'=>1));
        if(!$is_id_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
            $this->response($response); 
        }
        $checkType = $this->common_model->getsingle(USERS,array('userId'=>$user_id));
        $userType = $checkType->userType;
        $user_favourites = $this->Users_model->get_user_favourite_list($user_id, $userType, $limit, $offset, $check_status=true);   

        $response = array('status' => SUCCESS, 'favouritesList'=>$user_favourites); //success msg
        $this->response($response);

    } //End function


    //get user favourites by my ID
    function getMyFavourites_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id = $this->authData->userId;
      
        $checkType = $this->common_model->getsingle(USERS,array('userId'=>$user_id));
        $userType = $checkType->userType; 
        $user_favourites = $this->Users_model->get_my_favourites($user_id, $userType, $limit, $offset, $check_status=true);  
        

        $response = array('status' => SUCCESS, 'favouritesList'=>$user_favourites); //success msg
        $this->response($response);

    } //End function



    //get user recommands by my ID
    function getMyRecommands_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id = $this->authData->userId;
        $checkType = $this->common_model->getsingle(USERS,array('userId'=>$user_id));
        $userType = $checkType->userType; 
        $user_favourites = $this->Users_model->get_my_Recommands($user_id, $userType, $limit, $offset, $check_status=true);  
        

        $response = array('status' => SUCCESS, 'RecommandsList'=>$user_favourites); //success msg
        $this->response($response);

    } //End function



    //get indivisual user list according to filters
    function getIndivisualSearchList_post(){
        $this->check_service_auth();
         log_event(json_encode($_POST));
        if($this->post('pagination') == 1){
        $offset = !empty($this->post('offset'))? ($this->post('offset')):''; 
        $limit = !empty($this->post('limit'))? ($this->post('limit')):20000;
        }else{
            $offset ='';
            $limit =20000;
        }
    
        $search = array();
        $userInfo = $this->common_model->getsingle(USER_META, array('user_id'=> $this->authData->userId)); 
        //pr($this->db->last_query());
        $address = $userInfo->address;
        $city = $userInfo->city;
        $state = $userInfo->state;
        $country = $userInfo->country;

        if($this->post('isFilter') == 1){
        $set = array('immediate','speciality_id','company', 'job_title','availability', 'strength','value','location','city','state','country','employementType','experienceFrom','experienceTo','expectedSalaryTo','expectedSalaryFrom');

        foreach ($set as $key => $val){
            $post= $this->post($val);

            if(isset($post)){
               $search[$val] = $post;
 
            }

        }
        }else{
            $search = '';
        }
        //pr($search);
    
        //log_event($_POST['country']);    
        //log_event('param:::::: '.json_encode($search));
        $search_list = $this->Users_model->get_indivisual_search_list($search, $limit, $offset,$address,$city,$state,$country);
        //search records for city only- This is required becuse at app end they need to show message based on records exits or not
        //log_event($this->db->last_query());

      $search_city = $city; //current user city(in case no filter is applied)

        if(!empty($search['location'])){
            $search_city = ''; //location filter is applide but we are not sure city is filled

            if(!empty($search['city']))
                $search_city = $search['city']; //when location filter is applied, we expect city from location
            //log_event($search['country']);

            if(!empty($search['country']))
                $search_country = $search['country'];
        }
        //log_event($search['location']);
        //log_event($search_city);   

        $recordFound = 0; //initially set to 0, in case city is empty
        if(!empty($search_city)){
            $recordFound = $this->Users_model->searchCityRecord(array('um.city'=>$search_city,'u.userType'=>'individual'));
        }

        if(empty($search_city) AND !empty($search_country)){

            $recordFound = $this->Users_model->searchCountryRecord(array('um.country'=>$search_country,'u.userType'=>'individual'));
            //log_event($this->db->last_query());
        }


        $response = array('status' => SUCCESS,'searchList'=>$search_list,'recordFound'=>$recordFound); //success msg
        $this->response($response);

    } //End function 

      //get business user list according to filteres
    function getBusinessSearchList_post(){
       log_event(json_encode($_POST));
        //check for auth
        $this->check_service_auth();
        
        if($this->post('pagination') == 1){
            $offset = $this->post('offset'); $limit = $this->post('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; 
            $offset = 0;
        }
        }else{
            $offset ='';
            $limit =20000;
        }
      
        $search = array();
        $userInfo = $this->common_model->getsingle(USER_META, array('user_id'=> $this->authData->userId));
        $address = $userInfo->address;
        $city = $userInfo->city;
        $state = $userInfo->state;
        $country = $userInfo->country;
        
        $set = array('immediate','speciality_id','rating', 'company','location','city','state','country');
        foreach ($set as $key => $val) {
            $post= $this->post($val);
            if(!empty($post)){
                $search[$val] = $post;
            }
        }
        
        //log_event('For biz---'. json_encode($search));
        $search_list = $this->Users_model->get_business_search_list($search, $limit, $offset,$address,$city,$state,$country);
        //search records for city only- This is required becuse at app end they need to show message based on records exits or not
        //log_event($this->db->last_query());
        $search_city = $city; //current user city(in case no filter is applied)

        if(!empty($search['location'])){
            $search_city = ''; //location filter is applide but we are not sure city is filled

            if(!empty($search['city']))
                $search_city = $search['city']; //when location filter is applied, we expect city from location
            //log_event($search['country']);

            if(!empty($search['country']))
                $search_country = $search['country'];
        }
        //log_event($search['location']);
        //log_event($search_city);   

        $recordFound = 0; //initially set to 0, in case city is empty
        if(!empty($search_city)){
            $recordFound = $this->Users_model->searchCityRecord(array('um.city'=>$search_city,'u.userType'=>'business'));
        }

        if(empty($search_city) AND !empty($search_country)){

            $recordFound = $this->Users_model->searchCountryRecord(array('um.country'=>$search_country,'u.userType'=>'business'));
            //log_event($this->db->last_query());
        }

        $response = array('status' => SUCCESS,'searchList'=>$search_list,'recordFound'=>$recordFound); //success msg
        $this->response($response);

    } //End function 


    
    //to get my profile details
    function getMyProfile_get(){

        //check for auth
        $this->check_service_auth();

        $user_id = $this->authData->userId;
        $user_type = $this->authData->userType;
        $where = array('um.user_id'=>$user_id);

        //get total review count by user_id
        $review_count    = $this->Users_model->get_count('COUNT(reviewId) as reviews_count',array('review_for'=>$user_id),REVIEWS);

        //get total favourite count by user_id
        $favourite_count = $this->Users_model->get_count('COUNT(favouriteId) as favourite_count',array('favourite_for'=>$user_id),FAVOURITES);
        
        //get total recommend count by user_id
        $recommend_count = $this->Users_model->get_count('COUNT(recommendId) as recommend_count',array('recommend_for'=>$user_id),RECOMMENDS);

        //get total view count by user_id
        /*$view_count = $this->Users_model->get_count('COUNT(viewId) as view_count',array('view_for'=>$user_id),VIEW);*/
        $view_count = $this->common_model->getsingle(USERS,array('userId'=>$user_id),'visit_counts');

        if($user_type == 'business'){

            $business_profile = $this->Users_model->get_my_business_profile($where);

            $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'business_profile'=>$business_profile,'review_count'=>$review_count->reviews_count,'favourite_count'=>$favourite_count->favourite_count,'recommend_count'=>$recommend_count->recommend_count,'view_count'=>$view_count->visit_counts);
            $this->response($response);
        }
        
        $where_in = array('user_id'=>$user_id);
        $indivisual_profile   = $this->Users_model->get_indivisual_profile($where);
        $basic_info  = $this->Users_model->get_indivisual_basic_info($where);
        $experience  = $this->Users_model->get_user_experience($where_in);


        $resume      = $this->Users_model->get_user_resume($where_in);
        $totalExperience      = $this->common_model->getSingle(USER_EXPERIENCE,$where_in,'totalExperience');

        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'indivisual_profile'=>$indivisual_profile,'basic_info'=>$basic_info,'experience'=>!empty($experience)?$experience:'','resume'=>$resume,'review_count'=>$review_count->reviews_count,'favourite_count'=>$favourite_count->favourite_count,'recommend_count'=>$recommend_count->recommend_count,'total_experience'=>!empty($totalExperience->totalExperience)?$totalExperience->totalExperience:'','view_count'=>!empty($view_count->visit_counts) ? $view_count->visit_counts :"0");
       
        $this->response($response); 
    } //End function


    //to get user's profile details with review list for public view according to user_id
  
    function getPublicProfile_get(){
        //log_event(json_encode($_POST));
        //check for auth
        $this->check_service_auth();
        $offset = $this->post('offset'); $limit = $this->post('limit'); 
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id  = $this->get('user_id');
        if(empty($user_id)){
            $response = array('status'=>FAIL, 'message'=>ResponseMessages::getStatusCodeMessage(152));
            $this->response($response); 
        }
        $is_favourite_exist = $this->common_model->is_data_exists(FAVOURITES, array('favourite_for'=>$user_id,'favourite_by'=>$this->authData->userId));

        $is_recommend_exist = $this->common_model->is_data_exists(RECOMMENDS, array('recommend_for'=>$user_id,'recommend_by'=>$this->authData->userId));

       
        //get total review count by user_id
        $review_count    = $this->Users_model->get_count('COUNT(reviewId) as reviews_count',array('review_for'=>$user_id),REVIEWS);

        //get total favourite count by user_id
        $favourite_count = $this->Users_model->get_count('COUNT(favouriteId) as favourite_count',array('favourite_for'=>$user_id),FAVOURITES);
        
        //get total recommend count by user_id
        $recommend_count = $this->Users_model->get_count('COUNT(recommendId) as recommend_count',array('recommend_for'=>$user_id),RECOMMENDS);

        //get total view count by user_id
        $view_count = $this->common_model->getsingle(USERS,array('userId'=>$user_id),'visit_counts');

        //get user review list by user id
        $user_reviews = $this->Users_model->get_user_reviews_list($user_id, $limit, $offset, $check_status=true);

        $check_type = $this->common_model->getsingle(USERS,array('userId'=>$user_id));
        $where = array('um.user_id'=>$user_id);
        //pr($check_type);
        if($check_type->userType == 'business'){
            $public_profile = $this->Users_model->get_my_business_profile($where);
             //log_event($this->db->last_query());
        }else{
            $public_profile = $this->Users_model->get_my_individual_profile($where);
            //log_event($this->db->last_query());
        }

        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'profile'=>$public_profile,'review_list'=>$user_reviews,'review_count'=>$review_count->reviews_count,'favourite_count'=>$favourite_count->favourite_count,'recommend_count'=>$recommend_count->recommend_count,'is_favourite'=>$is_favourite_exist,'is_recommend'=>$is_recommend_exist,'view_count'=>!empty($view_count->visit_counts)?$view_count->visit_counts:"0");

        $this->response($response); 
        
      
    } //End function


    //to send interview request to individual by user_id
    function interviewRequest_post(){

         //check for auth
        $this->check_service_auth();
        $current_user_id = $this->authData->userId; 

        $this->form_validation->set_rules('type', 'Type', 'trim|required',array('required'=>'Please select type.'));
        $this->form_validation->set_rules('interviewer_name','Interviewer name','trim|required');
        $this->form_validation->set_rules('location','Location','trim|required');
        $this->form_validation->set_rules('latitude','Latitude','trim|required');
        $this->form_validation->set_rules('longitude','Longitude','trim|required');
        $this->form_validation->set_rules('date','Date','trim|required');
        $this->form_validation->set_rules('time','Time','trim|required');
        $this->form_validation->set_rules('interview_for','Interview request','trim|required',array('required'=>'Please select user to interview_for'));

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' =>preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); 
        }

        $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$current_user_id,'status'=>1));
        if(!$is_id_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
            $this->response($response); 
        }


        $set = array('type', 'interviewer_name', 'interviewer_name','location','latitude','longitude','date','time');
        foreach ($set as $key => $val) {
            $post= $this->post($val);
            if(!empty($post))
            $dataInsert[$val] = $post;
        }

        $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$this->post('interview_for'),'status'=>1));
        if(!$is_id_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
            $this->response($response); 
        }
        
        $interview_data['interview_for'] = $this->post('interview_for');
        $interview_data['interview_by'] = $current_user_id;
        $interview_data['created_on'] = datetime();

        $interview_id =  $this->common_model->insertData(INTERVIEW, $interview_data);
        if(!$interview_id){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            $this->response($response); 
        }

        $dataInsert['created_on'] = datetime();
        $dataInsert['interview_id'] = $interview_id;
        $req_id = $this->common_model->insertData(INTERVIEW_REQUEST, $dataInsert);
        if(!$req_id){

            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            $this->response($response); 
        }

        $progress_data['request_id'] = $req_id;

        $progress_id = $this->common_model->insertData(REQUEST_PROGRESS, $progress_data);
        if(!$progress_id){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            $this->response($response); 
        }

        $request_data = $this->Users_model->get_request_data(array('requestId'=>$req_id));
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(144),'request_data'=>$request_data); //successfully inserted
        $this->response($response); 

    } //End function

    //to update request status by request_id
    function updateRequestStatus_post(){

        //check for auth
        $this->check_service_auth();
        
        $this->form_validation->set_rules('request_id','request_id','trim|required');
        $this->form_validation->set_rules('status','status','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' =>preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);
        }

        $is_exists = $this->common_model->is_data_exists(INTERVIEW_REQUEST, array('requestId'=>$this->post('request_id')));
        if(!$is_exists){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            $this->response($response);
        }

        $req_data['status']  = $this->post('status');

        $request_progress = $this->common_model->updateFields(REQUEST_PROGRESS, $req_data, array('request_id'=>$this->post('request_id')));

        if(!$request_progress){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
            $this->response($response);
        }

        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(145)); //successfully updated
        $this->response($response);

    } //End function

    //to delete interview request, when jobseeker decline the request



        //user profile update
    function profileUpdate_post(){
        
        //check for auth
        $this->check_service_auth();

        $this->form_validation->set_rules('fullName', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__alpha_spaces_check');
        
        $this->form_validation->set_rules('area_of_specialization', 'Area of specialization', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[2]|max_length[200]');
        $this->form_validation->set_rules('job_title', 'Job title', 'trim|required');

        $this->form_validation->set_rules('businessName', 'Business name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('phone', 'Phone No.', 'trim|required|max_length[50]');
      
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);
        }
            //update company logo here
            if(!empty($_FILES['company_logo']['name'])){
                
                $folder = 'company_logo';
                $this->load->model('Image_model');
                $company_logo = $this->Image_model->updateMedia('company_logo',$folder);

                if(is_array($company_logo) && !empty($company_logo['error']))
                {
                    $response = array('status' => FAIL, 'message' =>$company_logo['error']);
                    $this->response($response);
                }


                if(is_string($company_logo) && !empty($company_logo)){

                    $data['company_logo']       = $company_logo;
                }
                
                elseif (filter_var($this->input->post('company_logo'), FILTER_VALIDATE_URL)) {

                    $data['company_logo'] = $this->input->post('company_logo');
                }

            }
            
            //added description here  -- By Manish
            $set = array('address','city','state','country','latitude', 'longitude','bio', 'description');
            foreach ($set as $key => $val) {
                $post= $this->post($val);
                $data[$val] = (isset($post) && !empty($post)) ? $post :''; 
            }

            $data['jobTitle_id'] = $this->input->post('job_title');
            $phone = $this->post('phone');
            //update user's meta information here
            $this->common_model->updateData(USERS,array('upd'=>datetime(),'phone'=>$phone),array('userId'=>$this->authData->userId));
            $update_meta = $this->common_model->updateData(USER_META, $data,array('user_id'=>$this->authData->userId));

            if(!$update_meta){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response);
            }

            $profileImage = array();
            //update user's profile image here
            if(!empty($_FILES['profileImage']['name'])){
                
                $folder = 'profile';
                $this->load->model('Image_model');
                $profileImage = $this->Image_model->updateMedia('profileImage',$folder);

            }
            
            if(is_array($profileImage) && !empty($profileImage['error']))
            {
                $response = array('status' => FAIL, 'message' =>$profileImage['error']);
                $this->response($response);
            }

            $data_val = array();
            $set = array('fullName','businessName');
            foreach ($set as $key => $val) {
                $post= $this->post($val);
                $data_val[$val] = (isset($post) && !empty($post)) ? $post :''; 
            }

            if(is_string($profileImage) && !empty($profileImage)){

                $data_val['profileImage']       = $profileImage;
            }
            
            elseif (filter_var($this->input->post('profileImage'), FILTER_VALIDATE_URL)) {
                $data_val['sprofileImage'] = $this->input->post('profileImage');
                $data_val['upd'] = datetime();
            }

            //update user's name,business name and profile image here
            $update_user = $this->common_model->updateData(USERS, $data_val,array('userId'=>$this->authData->userId));

            if(!$update_user){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response);
            }

            //update user's speciality here
            $speciality_data = array('specialization_id'=>$this->input->post('area_of_specialization'));
        
            $updated_speciality = $this->common_model->updateData(USER_SPECIALIZATION_MAPPING, $speciality_data,array('user_id'=>$this->authData->userId));

            if(!$updated_speciality){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response);
            }
        
            $user_data = $this->Users_model->get_business_list($this->authData->userId);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(148),'user_profile'=>$user_data); //successfully updated
            $this->response($response);

    } //End function

        //to get company name
    function getCompany_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(empty($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
    
        $result = $this->Users_model->getComapny($limit, $offset);
        $response = array('status' => SUCCESS, 'company'=>$result); //success msg
        $this->response($response);
    }


    function addView_post(){

         //check for auth
        $this->check_service_auth();

        $current_user_id = $this->authData->userId;
        
        
        $this->form_validation->set_rules('view_for', 'view', 'required', array('required'=>'Please select user to view profile'));
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response); 
        }

            $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$this->post('view_for'),'status'=>1));
            if(!$is_id_exist){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }
        
            $insert_data['view_for'] = $this->post('view_for') ;
            $insert_data['view_by'] = $current_user_id;

            $where = array('view_by'=>$current_user_id,'view_for'=>$insert_data['view_for']);
            
            $is_exist = $this->common_model->is_data_exists(VIEW,$where);

            if($is_exist){
                $this->common_model->updateFields(VIEW,array('upd'=>datetime()),array('view_for'=>$insert_data['view_for'],'view_by'=>$insert_data['view_by']));
               
            }
            $view_for = $insert_data['view_for'];
            if($current_user_id != $view_for){//stop add count when see your own profile.
                $userData = $this->common_model->getsingle(USERS,array('userId'=>$insert_data['view_for']));
                $count = $userData->visit_counts + 1;
                $this->common_model->updateFields(VIEW,array('upd'=>datetime()),array('view_for'=>$insert_data['view_for'],'view_by'=>$insert_data['view_by']));
                $this->common_model->updateFields(USERS,array('visit_counts'=>$count),array('userId'=>$insert_data['view_for']));
            if(!$is_exist){

             $fav_id = $this->common_model->insertData(VIEW, $insert_data);  //insert new  data
            if(!$fav_id){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
                $this->response($response); 
            }
            }
            }
            //send notification
            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$view_for), 'userId, deviceToken,isNotify,userType');
            if(!empty($user_info)){
                //prepare notification payload
                $registrationIds[] = $user_info->deviceToken; $title = "Viewed your profile";
                $userType = $user_info->userType;

                if(!empty($this->authData->profileImage)){
                    $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                }else{
                    $profileImage = base_url().DEFAULT_USER;
                }

                $body_send = $this->authData->fullName.' viewed your profile';  //body to be sent with current notification
                $body_save = '[UNAME] viewed your profile'; //body to be saved in DB
                $notif_type = 'profile_view';
                if($current_user_id != $view_for){
                if($user_info->isNotify == 1){
                    $notif_msg = $this->send_push_notification($this->authData->fullName,$registrationIds, $title, $body_send,$current_user_id, $notif_type,$userType,$profileImage);
                }
                
                //save notification into DB
                
                $notif_msg['body'] = $body_save; //replace body text with placeholder text
                //save notification
                $insertdata = array('notification_by'=>$current_user_id, 'notification_for'=>$view_for, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
                }
            }
    
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(151),'isView'=>1);  
            $this->response($response);
    }

    /*function update_experience_by_current_date($where,$user_id){
       $experience = $this->Users_model->get_user_experience($where);
        if(!empty($experience)){
        $experience_start_date = $experience['current_role']['current_start_date'];
        $experience_end_date = !empty($experience['current_role']['current_finish_date'])?$experience['current_role']['current_finish_date']:'';
            if(empty($experience_end_date)){
            $experience = $this->Users_model->get_user_total_experience($user_id);
            //echo $experience->privious_exp;
            $date1= $experience_start_date;
            $date_exp = date('Y-m-d',strtotime($date1));

            $current_job_year = strtok($date_exp, '-');
            $current_year= date('Y');
            $current_month= date('m');
            $exp_month  = date('m',strtotime($date_exp));

            $year1 = $current_job_year;
            $year2 = $current_year;

            $month1 = $exp_month;
            $month2 = $current_month;

            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
            $diffrence = $current_year - $current_job_year;
            if($diff == 12 OR 24 OR 36 OR 48 OR 60 OR 72 OR 84 OR 96 OR 108 OR 120){
            $updateDate = date($experience_start_date, strtotime('+1 years'));
            $this->common_model->updateFields(USER_EXPERIENCE,array('totalExperience' => $experience->privious_exp + $diffrence,'currentExperience'=> $diffrence),array('user_id'=> $user_id));
            }
            }else{
                $experience = $this->Users_model->get_user_total_experience($user_id);
                $date1= $experience_start_date;
                $date_exp = date('Y-m-d',strtotime($date1));
                $date2= $experience_end_date;
                $date_exp_end = date('Y-m-d',strtotime($date2));
                $date1C = new DateTime($date_exp);
                $date2C = new DateTime($date_exp_end);
                $diffC = $date1C->diff($date2C);
                $diff = $diffC->y;
                if($diff > 0){

                    $this->common_model->updateFields(USER_EXPERIENCE,array('totalExperience' => $experience->privious_exp + $diff,'currentExperience'=>$diff),array('user_id'=> $user_id));
                }
            }

        }
    }
*/
    //for getting job seeker's basicinfo,experience and resume information
    function getUserPersonalInfo_get(){

        $this->check_service_auth();
        $user_id  = $this->get('user_id');
        $where = array('user_id'=>$user_id);
        $basic_info = $this->Users_model->get_indivisual_basic_info(array('um.user_id'=>$user_id));
        $experience = $this->Users_model->get_user_experience($where);
        //$updateExperience = $this->update_experience_by_current_date($where,$user_id);
        $resume =  !$this->Users_model->get_user_resume($where) ? '' : $this->Users_model->get_user_resume($where);

        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(126),'basic_info'=>$basic_info,'experience'=>$experience,'resume'=>$resume);
        $this->response($response); 
    }


        //for updating indivisual's profile
    function updateIndivisualProfile_post(){

        //check for auth
        $this->check_service_auth();

        $this->form_validation->set_rules('fullName', 'Name', 'trim|required|min_length[2]|max_length[50]|callback__alpha_spaces_check');
        $this->form_validation->set_rules('area_of_specialization', 'Area of specialization', 'trim|required');
        $this->form_validation->set_rules('value', 'Value', 'trim|required');
        $this->form_validation->set_rules('strength', 'Strength', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[2]|max_length[200]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
      
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);
        }
        
            $data = array();
            $set = array('address','city','state','country','latitude', 'longitude','bio');
            foreach ($set as $key => $val) {
                $post= $this->post($val);
                $data[$val] = (isset($post) && !empty($post)) ? $post :''; 
            }

            //update user's meta information here
             $this->common_model->updateData(USERS,array('upd'=>datetime(),'phone'=>$this->post('phone')),array('userId'=>$this->authData->userId));
            $update_meta = $this->common_model->updateData(USER_META, $data,array('user_id'=>$this->authData->userId)); 

            if(!$update_meta){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response);
            }

            $profileImage = array();
            //update user's profile image here
            if(!empty($_FILES['profileImage']['name'])){
                
                $folder = 'profile';
                $this->load->model('Image_model');
                $profileImage = $this->Image_model->updateMedia('profileImage',$folder);

            }
            if(is_array($profileImage) && !empty($profileImage['error']))
            {
                $response = array('status' => FAIL, 'message' =>$profileImage['error']);
                $this->response($response);
            }

            $data_val = array();
            $set = array('fullName');
            foreach ($set as $key => $val) {
                $post= $this->post($val);
                $data_val[$val] = (isset($post) && !empty($post)) ? $post :''; 
            }

            if(is_string($profileImage) && !empty($profileImage)){

                $data_val['profileImage']       = $profileImage;
            }
            
            elseif (filter_var($this->input->post('profileImage'), FILTER_VALIDATE_URL)) {
                $data_val['profileImage'] = $this->input->post('profileImage');
            }
            $data_val['phone']  = $this->post('phone');  

            //update user's name,business name and profile image here
            $update_user = $this->common_model->updateData(USERS, $data_val,array('userId'=>$this->authData->userId));

            if(!$update_user){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response);
            }

            //update user's speciality here
            $speciality_data = array('specialization_id'=>$this->input->post('area_of_specialization'));
        
            $updated_speciality = $this->common_model->updateData(USER_SPECIALIZATION_MAPPING, $speciality_data,array('user_id'=>$this->authData->userId));

            $where  = array('user_id'=>$this->authData->userId);
            //check whether value empty or not, if not (delete all previous values and insert new)
            $this->common_model->deleteData(USER_VALUE_MAPPING,$where );

             //insert multiple values here
            foreach (explode(',', $this->post('value'))  as  $value) {

                $this->common_model->insertData(USER_VALUE_MAPPING, array('user_id'=>$this->authData->userId, 'value_id'=>$value));
           
            }
           
          
            //check whether strength empty or not, if not (delete all previous strength and insert new)
            $this->common_model->deleteData(USER_STRENGTH_MAPPING,$where);
            //insert multiple strengths here
            foreach (explode(',', $this->post('strength'))  as  $val) {
               
                $this->common_model->insertData(USER_STRENGTH_MAPPING,array('user_id'=>$this->authData->userId, 'strength_id'=>$val));
           
            }
            if(!$updated_speciality){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118)); //fail- something went wrong
                $this->response($response);
            }
        
            $user_data = $this->Users_model->get_business_list($this->authData->userId);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(148),'user_profile'=>$user_data); //successfully updated
            $this->response($response);
    }


     //get user views by user ID
    function getUserView_get(){

        //check for auth
        $this->check_service_auth();

        $offset = $this->get('offset'); $limit = $this->get('limit');
        if(!isset($offset) || empty($limit)){
            $limit= $this->list_limit; $offset = 0;
        }
        $user_id = $this->get('user_id');
        $is_id_exist = $this->common_model->is_data_exists(USERS, array('userId'=>$user_id,'status'=>1));
        if(!$is_id_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(141)); //fail- User doesn't exist
            $this->response($response); 
        }
        $user_views = $this->Users_model->get_user_view_list($user_id, $limit, $offset, $check_status=true);
        $response = array('status' => SUCCESS, 'viewList'=>$user_views); //success msg
        $this->response($response);

    } //End function

      //for notification on/off in setting tab
    function sendNotification_get(){

        //check for auth
        $this->check_service_auth();
        $user_id = $this->authData->userId;
        
        $is_notify = $this->common_model->is_data_exists(USERS, array('userId'=>$user_id,'isNotify'=>1));
        if($is_notify){
            $notify = $this->common_model->updateFields(USERS,array('isNotify'=>0),array('userId'=>$user_id));
        }else{
            $notify = $this->common_model->updateFields(USERS,array('isNotify'=>1),array('userId'=>$user_id));
        }
       
        $result =  $this->common_model->getsingle(USERS,  array('userId'=>$user_id,'status'=>1));
        $response = array('status' => SUCCESS, 'isNotify'=>$result->isNotify); //success msg
        $this->response($response);
    }

     function changePassword_post(){
      
        //check for auth
        $this->check_service_auth();  
        //set validation rule
        $this->form_validation->set_rules('old_pass', 'current password', 'required|min_length[6]');
        $this->form_validation->set_rules('new_pass', 'new password', 'required|min_length[6]');
        //if validation rule fail set msg s
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);
        }else{
            //get user id from auth data
            $userId  = $this->authData->userId;
            $where    = array('userId'=>$userId);
            //get data
            $oldPassword = $this->post('old_pass');
            $newPassword = $this->post('new_pass');
            $newPasswordHash = password_hash($this->post('new_pass') , PASSWORD_DEFAULT);
            //get user data from table
            $user = $this->common_model->getsingle(USERS,$where,'password');
            //password verify
            if(password_verify($oldPassword, $user->password)){
                //check curent and new password are same
                if(password_verify($newPassword, $user->password)){
                    //set msg for new password are same 
                    $response = array('status'=>FAIL,'message'=>'Current password and New Password are same');
                    $this->response($response);
                }
                //set data for update 
                $updatedata = array('password'=>$newPasswordHash);
                //update password
                $result = $this->common_model->updateFields(USERS, $updatedata, $where);
                //check password update
                if(!$result){
                    //if not set msg
                    $response = array('status'=>FAIL,'message'=>'Your password not updated ');
                    $this->response($response);
                }
                //set msg for success
                $response = array('status'=>SUCCESS,'message'=>'Your password has been updated successfully');
                $this->response($response);
            }else{
                //set msg for password not match from curent password
                $response = array('status'=>FAIL,'message'=>'Your password does not matched with old password');
                $this->response($response);
            }
        }
    }

     //user log out
    function logout_get(){

        //check for auth
        $this->check_service_auth();  
       //get user id from auth data
        $userId  = $this->authData->userId;
        //empty device token on when user logged out
        $res = $this->common_model->getsingle(USERS,array('userId'=>$userId));
        $logout = $this->common_model->updateFields(USERS, array('deviceToken' =>'','authToken'=>'','upd'=>$res->upd),array('userId'=>$userId ));
        //set msg for success
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(154));
        $this->response($response);
    }

    function salary_dropDown_get(){
        $this->check_service_auth();
        $response = array('status'=>SUCCESS,'message'=>'List of salary range','data'=>get_salary_drop_down());
        $this->response($response);
    }

    function experience_drop_down_get(){
        $this->check_service_auth();
        $response = array('status'=>SUCCESS,'message'=>'List of experience drop down','data'=>get_experience_drop_down());
        $this->response($response);
    }

    function badge_count_unread_views_get(){
        $this->check_service_auth();
        $userId  = $this->authData->userId;
        $userType  = $this->authData->userType;
        $where = array('isViewed'=>0,'notification_for'=>$userId);
        if($userType == 'individual'){
            $get = array('profile_view','user_recommends','user_favourites');
            $total_count = $this->Users_model->get_total_counts($get,$where);
        }else{
            $get = array('user_recommends','user_favourites','user_review');
            $total_count = $this->Users_model->get_total_counts($get,$where);
        }
        $res = $this->Users_model->get_count_viewed_data(NOTIFICATIONS,$get,$userId);
        $response = array('status'=>SUCCESS,'message'=>'count','total'=>$total_count,'data'=>$res);
        $this->response($response);
    }

    function update_notification_post(){
        $this->check_service_auth();
        $id =  $this->authData->userId;
        $notiy =  $this->post('notify_type');
        $where = array('notification_for'=>$id,'notification_type'=>$notiy);
        $res = $this->common_model->updateFields(NOTIFICATIONS,array('isViewed'=>1),$where);
        if($res == true){
        $response = array('status'=>SUCCESS,'message'=>'Updated to viewed','is_update'=>1);
        }else{
            $response = array('status'=>FAIL,'message'=>'Not updated to viewed','is_update'=>0);
        }
        $this->response($response);

    }

    function profile_inactive_user_get(){
        $this->check_service_auth();
        /*$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
         if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);
        }else{
            
            $password = $this->post('password');*/
            //print_r($this->post('password'));die();
         /*   $incriptd_password = $this->post('password');*/
            $id =  $this->authData->userId;
            $res = $this->Users_model->inactive_profile(USERS,$id);
            if($res['type'] == 'SU'){
            $response = array('status'=>SUCCESS,'message'=>'Profile Inactivated successfully','isActive'=>0);
            }elseif($res['type'] == 'UNF'){
                $response = array('status'=>FAIL,'message'=>'User not found');
            }elseif($res['type'] == 'ADU'){
                $response = array('status'=>FAIL,'message'=>'Already deactive user','isActive'=>0);
            }else{
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }
        $this->response($response);
    }

     function verifiedEmail_post(){//email verification function
        
        $verify_link = $this->post('token');//token genrated by us given to us by app end
        $verify_email = decoding($this->post('email'));//decode email id for verification
        $verify_id = $this->post('id');
        $is_browser = $this->post('browser');
        if(empty($verify_link) AND empty($this->post('email')) AND !empty($verify_id)){
            $ress = $this->common_model->getsingle(USERS,array('userId'=>$verify_id),array('userId','isVerified'));
            if($ress->isVerified == 1){
                $response = array('status'=>SUCCESS,'message'=>'Email verified successfully','  verified'=>1);
            }else{
                $response = array('status'=>FAIL,'message'=>'Email verification fail','verified'=>0);
            }
        }else{
           
            $res = $this->common_model->getsingle(USERS,array('email'=>$verify_email,'verifiedLink'=>$verify_link),array('userId','isVerified'));
            if(!empty($res)){
            if($res->isVerified == 0){
            $resp = $this->common_model->updateFields(USERS,array('isVerified'=>1,'verifiedLink'=>''),array('userId'=>$res->userId));
            if($resp == true){
                $response = array('status'=>SUCCESS,'message'=>'Email verified successfully','verified'=>1,'alreadyVerified'=>0);
            }else{
             $response = array('status'=>FAIL,'message'=>'Email verification fail','verified'=>0);
            }
            }else{
                $response = array('status'=>SUCCESS,'message'=>'Email verified successfully','verified'=>1,'alreadyVerified'=>1);
            }
            }else{
            $response = array('status'=>FAIL,'message'=>'Email verification fail,Link has been expired','verified'=>0);
            }
           
        }
        $this->response($response);
       /* }
       $send['link'] = base_url().'home/mobile_browser_email/'.$verify_link.'/'.$this->post('email').'/'.$verify_id;
        redirect($send['link']);*/
    }

    function addFeedback_post(){
        $this->check_service_auth();
        $data['subject'] = $this->post('subject');
        $data['message'] = $this->post('message');
        $data['rating']  = $this->post('rating');
        $data['name']    = $this->authData->fullName;
        $data['email']   = $this->authData->email;
        $data['type']     = 'feedback';
        $response = $this->Users_model->contactUs($data,CONTACT_US);
        if($response == TRUE){
            $message = $this->load->view('email/feedback_email', $data, true);
            $this->load->library('Smtp_email');
            $subject = $data['subject'];
            $send['email'] = 'info@connektus.com.au';
            $isSend = $this->smtp_email->send_mail($send['email'],$subject,$message);
            $response = array('status'=>SUCCESS,'message'=>'Feedback recorded successfully');
        }else{
            $response = array('status'=>FAIL,'message'=>'We cant complete your request right now.');
        }
        $this->response($response);
    }

    function resendEmailVerifyLink_get(){
        $this->check_service_auth();
        if($this->authData->isVerified == 0){        
        $id = $this->authData->userId;
        $data['verifiedLink'] = md5(uniqid());
        $data['fullName'] = $this->authData->fullName;
        $result = $this->Users_model->resend_mail($id,$data);
        if(is_array($result)){
            switch ($result['regType']){
                case "NR":
                $response = array('status'=>SUCCESS,'message'=>'Verification mail has been sent to your mail id.');
                break; 
                case "NRMU":
                $response = array('status'=>SUCCESS,'message'=>'Not able to send mail due to some technical issue');
                break;
                default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());
            }
        }
        else{
            $response = array('status'=>FAIL,'message'=>'Unable to resend verify link.');
        }
        }else{
             $response = array('status'=>FAIL,'message'=>'Email Already Verified.');
        }
        $this->response($response);
    }

    function getUserVerifiedStatus_get(){//get current status of user email verification.
         $this->check_service_auth();
         if($this->authData->isVerified == 0){
            $response = array('status'=>SUCCESS,'message'=>'Email not verified.','isVerified'=>0);
         }else{
            $response = array('status'=>SUCCESS,'message'=>'Email verified.','isVerified'=>1);
         }
        $this->response($response);
    }
    

    

}//End Class 


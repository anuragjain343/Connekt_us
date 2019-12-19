<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 
class Requests extends CommonService{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Request_model'); //load user model
        $this->list_limit = 20;  //limit record
    }
    
    function makeRequest_post(){ //make interview request..
        $this->check_service_auth();
        $this->form_validation->set_rules('requestedFor', 'Requested to', 'trim|required',array('required'=>'Please provide requested to id.')); 
        $this->form_validation->set_rules('interviewerName','Interviewier', 'trim|required',array('required'=>'Please provide interviewer name.'));
        $this->form_validation->set_rules('date','Date', 'trim|required',array('required'=>'Please provide interviewe date.'));
        $this->form_validation->set_rules('time','Time', 'trim|required',array('required'=>'Please provide interviewe time.'));
        $this->form_validation->set_rules('location','Location', 'trim|required',array('required'=>'Please provide interviewe location.'));
        $requestedBy    = $this->authData->userId;
        $requestedFor   = $this->post('requestedFor');
        if ($this->form_validation->run() == FALSE)
        { 
            $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
            $this->response($responseArray);
        }
        else 
        {       
            //$dataRequest['request_for']      = $this->post('requestedFor');
            $dataRequest['interviewer_name'] = $this->post('interviewerName');
            $dataRequest['date'] = $this->post('date');
            $dataRequest['time'] = $this->post('time');
            $dataRequest['location'] = $this->post('location');
            $dataRequest['latitude'] = $this->post('latitude');
            $dataRequest['longitude'] = $this->post('longitude');
            $dataRequest['type']    = $this->post('type');
            date_default_timezone_set('Asia/Kolkata');
            $dataRequest['created_on']   = date('Y-m-d H:i:s',time());
            
            $res = $this->Request_model->submitRequest($dataRequest,$requestedBy,$requestedFor);
            //pr($res['data']->interviewer_name);
            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$requestedFor), 'userId, deviceToken,isNotify,userType,fullName');
            $title = "Interview request.";
            $registrationIds = $user_info->deviceToken;
            //pr($registrationIds);
            
            $body_send = $this->authData->fullName.' '.'sent you an interview request';
            $notif_type = 'interview_request.';
            $userType =    $user_info->userType;
            if(!empty($this->authData->profileImage)){
                    $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
            }else{
                    $profileImage = base_url().DEFAULT_USER;
            }

            if($user_info->isNotify == 1){
               //pr($requestedFor);
            $notif_msg = $this->send_push_notification($this->authData->fullName,array($registrationIds), $title, $body_send, $this->authData->userId, $notif_type,$userType,$profileImage);
             //pr($notif_msg);           
            $body_save = '[UNAME] Request for interview';
            $notif_msg['body'] = $body_save; 
            $insertdata = array('notification_by'=>$this->authData->userId, 'notification_for'=>$requestedFor, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
            $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
            }
            switch ($res['type']){
            case "SE":
                $response = array('status'=>SUCCESS,'message'=>$this->authData->fullName.' '.'sent you an interview request','data'=>$res['data']); break; 
            case "APD":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(156)); break;
            case "APD":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(157)); break;
            default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121));
            }
        }
        $this->response($response);
    }//end of function..

    function acceptDeclinedRequest_post(){
        $this->check_service_auth();

        $this->form_validation->set_rules('action', 'Action', 'trim|required',array('required'=>'Please provide action 1 for accept 0 for declined.'));  
        $this->form_validation->set_rules('interviewId', 'Interview id', 'trim|required',array('required'=>'Please provide interview id.')); 
        if ($this->form_validation->run() == FALSE)
        { 
            $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
            $this->response($responseArray);
        }
        else 
        {
          $action  = $this->post('action');
          if($action == 1){
            $dataAccept['interview_status'] = 1;
          }if($action == 2){
            $dataAccept['interview_status'] = 2;
          }
          date_default_timezone_set('Asia/Kolkata');
          $dataAccept['upd']          = date('Y-m-d H:i:s',time());

            //$user_id = $this->authData->userId;
            $where['interviewId'] = $this->post('interviewId');
            $request_id =  $this->common_model->getsingle(INTERVIEWS, array('interviewId'=>$this->post('interviewId')), 'request_id,date,time');
            /*if(date('Y-m-d') > strtotime($request_id->date) AND  ){*/
            $res = $this->Request_model->update_interview($dataAccept,$where);

            $request_for =  $this->common_model->getsingle(REQUESTS, array('requestId'=>$request_id->request_id ), 'request_by,request_for');
            //echo $request_for->request_for;
            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$request_for->request_by), 'userId, deviceToken,isNotify,userType');

            if(!empty($this->authData->profileImage)){
                    $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
            }else{
                    $profileImage = base_url().DEFAULT_USER;
            }

            $title = "Action on request.";
            $registrationIds = $user_info->deviceToken;

            if($action == 1){
                $body_send       = $this->authData->fullName.' '.'accepted your interview request';
            }if($action == 2){
                $body_send       = $this->authData->fullName.' '.'declined your interview request';
            }
            $notif_type      =  'Request_action.';
            $userType        =    $user_info->userType;


            if($user_info->isNotify == 1 AND !empty($request_for)){

                $notif_msg = $this->send_push_notification($this->authData->fullName,array($registrationIds), $title, $body_send,$request_for->request_for, $notif_type,$userType,$profileImage);
                $body_save = '[UNAME] Action by reciever.';
                $notif_msg['body'] = $body_save; 

                $insertdata = array('notification_by'=>$this->authData->userId, 'notification_for'=>$request_for->request_by, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());

            $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
            }
            switch ($res['type']){
            case "AC":
                $response = array('status'=>SUCCESS,'message'=>$this->authData->fullName.' '.'accepted your interview request','data'=>$res['data']); break; 
            case "DE":
                $response = array('status'=>SUCCESS,'message'=>$this->authData->fullName.' '.'declined your interview request'); break;
            case "CU":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(160)); break;
            case "IND":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(164)); break;
            case "NRF":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(164)); break;
            case "IRC":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(166)); break;
            default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121));
            }
            /*}else{
                $response = array('status'=>FAIL,'message'=>'Interview date passed');
            }*/
        }
        $this->response($response);
    }//end function..

    function deleteRequest_post(){ //delete request
        $this->check_service_auth();
        $user_id = $this->authData->userId;
        $interviewId['interviewId'] = $this->post('interviewId');
        $is_delete['is_finished'] = 2;
        $res = $this->Request_model->deleteRequest($is_delete,$user_id,$interviewId);

        if($res['type'] == "DR"){
            $request_id =  $this->common_model->getsingle(INTERVIEWS, array('interviewId'=>$this->post('interviewId')), 'request_id');

            $request_for =  $this->common_model->getsingle(REQUESTS, array('requestId'=>$request_id->request_id), 'request_for,request_by');

            $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$request_for->request_for), 'userId, deviceToken,isNotify,userType,profileImage');

            $title = "Action on request.";
            $registrationIds = $user_info->deviceToken;

            $body_send       =   'Interview request has been deleted by'.' '.$this->authData->businessName;
            $notif_type      = 'Interview_request_delete.';
            $userType        =   $user_info->userType;

            if(!empty($request_for->profileImage)){
                $profileImage = base_url().USER_THUMB.$request_for->profileImage;
            }else{
                $profileImage = base_url().DEFAULT_USER;
            }

            if($user_info->isNotify == 1 AND !empty($request_for)){

                $notif_msg = $this->send_push_notification($this->authData->fullName,array($registrationIds), $title, $body_send, $request_for->request_by, $notif_type,$userType,$profileImage);
                $body_save = '[UNAME] Action by sender.';
                $notif_msg['body'] = $body_save; 

                $insertdata = array('notification_by'=>$request_for->request_by, 'notification_for'=>$this->authData->userId, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
            }
        }
        switch ($res['type']){
            case "DR":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(161)); break; 
            case "NMR":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(163)); break;
            case "AD":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(162)); break;
            default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121));
        } 
        $this->response($response);    
    }//end of function..  

    function processData_post(){ //delete request
        $this->check_service_auth();
        $user_id = $this->authData->userId;
         $this->form_validation->set_rules('requestBy', 'Request by', 'trim|required',array('required'=>'Please provide request by id.'));  
        $this->form_validation->set_rules('requestFor', 'Request id', 'trim|required',array('required'=>'Please provide request for id.')); 
        if ($this->form_validation->run() == FALSE)
        { 
            $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
            $this->response($responseArray);
        }
        else 
        {
        $dataProcess['request_by'] = $this->post('requestBy');
        $dataProcess['request_for'] = $this->post('requestFor');
        $res = $this->Request_model->processData_get($dataProcess);
        switch ($res['type']){
            case "DR":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(167),'data'=>$res['data']); break; 
            default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(168));
        }
        } 
        $this->response($response);    
    }//end of function..  

     function offerdNotOfferd_post(){
        $this->check_service_auth();
        $this->form_validation->set_rules('action', 'Action', 'trim|required',array('required'=>'Please provide action 1 for accept 0 for declined.'));  
        $this->form_validation->set_rules('interviewId', 'Interview id', 'trim|required',array('required'=>'Please provide interview id.')); 
        if ($this->form_validation->run() == FALSE)
        { 
            $responseArray = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
            $this->response($responseArray);
        }
        else 
        {
          $action  = $this->post('action');
          if($action == 1){
            $dataAccept['request_offer_status'] = 1;
            $dataAccept['is_finished'] = 1;
          }if($action == 2){
            $dataAccept['request_offer_status'] = 2;
            $dataAccept['is_finished'] = 1;
          }
            //$user_id = $this->authData->userId;
            $where['interviewId'] = $this->post('interviewId');
            $res = $this->Request_model->update_offerd($dataAccept,$where);

            if($res['type'] == "AC"){

                $request_id =  $this->common_model->getsingle(INTERVIEWS, array('interviewId'=>$this->post('interviewId')), 'request_id');

                $request_for =  $this->common_model->getsingle(REQUESTS, array('requestId'=>$request_id->request_id), 'request_for,request_by');

                $user_info =  $this->common_model->getsingle(USERS, array('userId'=>$request_for->request_for), 'userId, deviceToken,isNotify,userType,fullName');

                $title = "Action on request.";
                $registrationIds = $user_info->deviceToken;
                if($action == 1){
                    $body_send       = 'Congratulation ! Job offered by'.' '.$this->authData->businessName;
                }if($action == 2){
                    $body_send       =  $user_info->fullName.' '.'Job not offered.';
                }
                $notif_type      = 'Interview_offered_action.';
                $userType        =   $user_info->userType;
                
                if(!empty($this->authData->profileImage)){
                    $profileImage = base_url().USER_THUMB.$this->authData->profileImage;
                }else{
                    $profileImage = base_url().DEFAULT_USER;
                }

                if($user_info->isNotify == 1 AND !empty($request_for)){
                    $notif_msg = $this->send_push_notification($this->authData->fullName,array($registrationIds), $title, $body_send,$request_for->request_by, $notif_type,$userType,$profileImage);
        
                    $body_save = '[UNAME] Job offered/Not offered.';
                    $notif_msg['body'] = $body_save; 

                    $insertdata = array('notification_by'=>$this->authData->userId, 'notification_for'=>$request_for->request_for, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
                    $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
                }
            }
            switch ($res['type']){
            case "AC":
                $response = array('status'=>SUCCESS,'message'=>'Job offered by'.' '.$this->authData->fullName); break; 
            case "DE":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(170)); break;
            case "CU":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(160)); break;
            case "IND":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(164)); break;
            case "NRF":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(164)); break;
            case "IRC":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(166)); break;
            default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121));
            }
        }
        $this->response($response);
    }//end function..

}//End Class 


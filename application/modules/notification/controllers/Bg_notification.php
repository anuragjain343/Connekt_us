<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bg_Notification extends CommonService {

	public function __construct() {
        parent::__construct();
        $this->load->model('api_v2/Jobs_model');
        
    }

    //Send Push on job Post $jobId,$currentUserId
    public function job_post_notify_get($jobId,$currentUserId){
        //Get all nearby job seekers list here and loop through list, send push and save them in DB
        /*$jobId = 27;
        $currentUserId= 201;*/

    	$nearByuser = $this->Jobs_model->getNearByUser($currentUserId,$jobId);
        $jobDetails = $this->Jobs_model->getDetailsForNotification($jobId);
        $user_info   = $this->common_model->getsingle(USERS, array('userId'=>$currentUserId),'userId,deviceToken,isNotify,userType,fullName,email,businessName');
        //pr($user_info);

    	
        foreach ($nearByuser as $key => $value){
            $registrationIds = array();
            $registrationIds[]  = $value->deviceToken;
            $noti_for           = $value->user_id;
            if(!empty($value->profileImage)){
            $profileImage = base_url().USER_THUMB.$value->profileImage;
            }else{
            $profileImage = base_url().DEFAULT_USER;
            }
           
        
            $title              = "create new job";
            $body_send          = ucfirst($user_info->fullName).' create new job';
            $body_save          = '[UNAME] create new job'; 
            $notif_type         = 'new_job';
            $notif_msg          = $this->send_push_notification($user_info->fullName,$registrationIds,$title,$body_send,$jobId,$notif_type,$user_info->userType,$profileImage,$jobDetails->job_type);
           
            $notif_msg['body']           = $body_save; 
            $notif_msg['reference_type'] = $jobDetails->job_type; 

            $insertdata = array('notification_by'=>$user_info->userId, 'notification_for'=>$noti_for,'reference_id'=>$jobId,'notification_message'=>json_encode($notif_msg), 'notification_type'=>$notif_type,'created_on'=>datetime());
            //pr($insertdata);
            $this->notification_model->save_notification(NOTIFICATIONS, $insertdata);
        }    
        $this->send_emails_to_all($nearByuser,$jobDetails,$user_info);
    }

    public function send_emails_to_all($nearByuser,$jobDetails,$user_info){

        $this->load->library('Smtp_email');
        foreach ($nearByuser as $key => $value) {
            
         
         $send['fullName']          = ucfirst($value->fullName);

         $send['request_by']        = ucfirst($user_info->fullName);
         $send['msg']               = 'here are the latest jobs for you may be interested in "'.$jobDetails->jobTitleName.'"';
         $send['interviewer_name']  = '';
         $send['new_image']         = '';
         $send['date']              = '';
         $send['time']              = '';
         $send['location']          = '';
         
         $message = $this->load->view('email/email_interview_request',$send,true);
         
         $subject = 'New jobs for you';

         $isSend = $this->smtp_email->send_mail($value->email,$subject,$message);

       }

    }
}
?>
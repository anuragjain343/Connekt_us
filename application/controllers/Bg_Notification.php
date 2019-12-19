<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bg_Notification extends MX_Controller {

	public function __construct()
    {
    	parent::__construct();
    	$this->load->model('notification_model');
    	$this->notify_log = FCPATH.'notification_log.txt';

    }
    

    function send_broadcast_notification($id){

        //$userDeviceTokens[] = 'cddh6rXMrXw:APA91bFW6QAsJahxoe5GuhxuwMPEFkVMK8o7oF6kDIFOsC3CJc_SOQhCUuONuEBePPqnuhHZdq1JgubtMcsXCwZ-4tBH9Nh1KTnmC9cURVeIJ-WOjl5BnZo4a-9LqT--rAZ-W8v7-7NJ';

        // dev $userDeviceTokens[] = 'csdh6rXMrXw:APA91bFW6QAsJahxoe5GuhxuwMPEFkVMK8o7oF6kDIFOsC3CJc_SOQhCUuONuEBePPqnuhHZdq1JgubtMcsXCwZ-4tBH9Nh1KTnmC9cURVeIJ-WOjl5BnZo4a-1LqT--rAZ-W8v7-5NJ';

        //get data from admin brocast table
        
       // $discp[]='test notification';
       // $notif_msg = $this->notification_model->send_notification($userDeviceTokens,$discp);*/

       // $notif_msg = $this->notification_model->send_push_notification($userDeviceTokens,'test des', 'test title','11', 'admin_message','ashish','');

        $i=0;
    	$admin_brodcast_msg = $this->common_model->getsingle(ADMIN_BROADCAST_MSG,array('id'=>$id));
      
        $admin_brodcast_msg->title;
        $admin_brodcast_msg->userType;
        $admin_brodcast_msg->discription;

        $total = $this->common_model->user_count_number($admin_brodcast_msg->userType);
        $totalUsers = $total->user_count;
        $userDeviceTokens = $this->common_model->get_all_users_lists($admin_brodcast_msg->userType);

       
        foreach ($userDeviceTokens as $value) {

            $tokenArr[]                              = $value->deviceToken;
            $title                                   = $admin_brodcast_msg->title;
            $insertdata[$i]['notification_by']       = 0;
            $insertdata[$i]['notification_for']      = $value->userId;
            $insertdata[$i]['notification_message']  = $admin_brodcast_msg->discription;
            $insertdata[$i]['notification_type']     = 'admin_message';
           
            $i++;

       
            if($i%500 == 0 || $totalUsers == $i){

               
                $notifmsg = $this->notification_model->send_push_notification($tokenArr,$admin_brodcast_msg->discription,$admin_brodcast_msg->title,$value->userId,'admin_message',$value->fullName,'');

             

                
                $saveArray = $tokenArr = array();

                sleep(2);
            }
                $notif_msg['body'] = $insertdata[$i]['notification_message']; //
                $notif_msg = array('title'=>$admin_brodcast_msg->title,'body'=>$admin_brodcast_msg->discription, 'click_action'=>'admin_active', 'sound'=>'default','reference_id'=>$value->userId,'type'=>'admin_message','username'=>$value->fullName);

                $insertdata1 = array('notification_by'=>'0', 'notification_for'=>$value->userId, 'notification_message'=>json_encode($notif_msg), 'notification_type'=>'admin_message','created_on'=>datetime());

              
                $this->notification_model->save_notification(NOTIFICATIONS,$insertdata1);

        }         
    	
    }

}
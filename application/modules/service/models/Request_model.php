<?php
class Request_model extends CI_Model {

	   function submitRequest($data,$requestedBy,$requestedFor){
        $request['request_by'] = $requestedBy;
        $request['request_for'] = $requestedFor;
        $selectrequest = $this->db->select('requestId,request_for,request_by,is_finished')->where($request)->order_by('requestId','DESC')->limit(1)->get(REQUESTS);
        if($selectrequest->num_rows()){
           $datarequest =  $selectrequest->row();
           //pr($datarequest);
            if($datarequest->is_finished == 1 OR $datarequest->is_finished == 2){
                $this->db->insert(REQUESTS,$request);//request will be created from here...
                $request_id = $this->db->insert_id();//request id we will get from here..
                if(!empty($request_id))
                {
                    $data['request_id'] = $request_id;
                   $this->db->insert(INTERVIEWS,$data);//interview process insert from here..
                   $interviewId = $this->db->insert_id();//interview id we will get from here..
                   if(!empty($interviewId))
                   {
                        $res =  $this->getRequestData(array('interviewId'=>$interviewId));//repose function will select data will select from here..
                        return array('type'=>'SE','data'=>$res);
                   }
                }
                else
                {
                    return array('type'=>"RCA");
                }
            }else
            {
                return array('type'=>'APD');
            }
        }else
        {
            $this->db->insert(REQUESTS,$request);//request will be created from here...
            $request_id = $this->db->insert_id();//request id we will get from here..
            if(!empty($request_id))
            {
                $data['request_id'] = $request_id;
               $this->db->insert(INTERVIEWS,$data);//interview process insert from here..
               $interviewId = $this->db->insert_id();//interview id we will get from here..
               if(!empty($interviewId))
               {
                    $res =  $this->getRequestData(array('interviewId'=>$interviewId));
                    return array('type'=>'SE','data'=>$res);
               }
            }else
            {
                return array('type'=>"RCA");
            }
        }
        return FALSE;        
    }//end of function

    function getRequestData($where)
    {
       $this->db->select('`interview`.`interviewId`,
            `interview`.`request_id`,
            `interview`.`type`,
            `interview`.`interviewer_name`,
            `interview`.`location`,
            `interview`.`latitude`,
            `interview`.`longitude`,`interview`.`date`,
            `interview`.`time`,
            `interview`.`interview_status`,
            `request`.`request_for`,
            `request`.`request_by`,
            `request`.`request_offer_status`,
            `request`.`is_finished`');
        $this->db->join('`requests` `request`','`interview`.`request_id` = `request`.`requestId`');
        $this->db->where($where);
        $query =$this->db->get('`interviews` `interview`');
        if($query->num_rows()){
            $result = $query->row();
            /*if($result->interview_status == 0){
                $result->interview_status = 'request sent';
            } if($result->interview_status == 1){
                $result->interview_status = 'for accept';
            }if($result->interview_status == 2){
                $result->interview_status = ' for declined';
            }*/
            return $result;
        }
        return FALSE;
    }

    function update_interview($data, $where)
    {//update interview status when accept and declined..
       $resp =  $this->db->select('`interviews`.`request_id`,`interviews`.`type`,`requests`.`is_finished`')->join('`requests`','`interviews`.`request_id` = `requests`.`requestId`')->where($where)->get(INTERVIEWS);
       if($resp->num_rows()){
        $getRes = $resp->row();
        $userType['type'] = $getRes->type;
        if($getRes->is_finished == 1 ){
            return array('type'=> "IRC");
        }
        if($getRes->is_finished == 0 ){
        if($data['interview_status'] == 1 OR $data['interview_status'] == 2){
            
        $this->db->set($data);
        $this->db->where($where);
        $this->db->update('interviews');
        $res = $this->db->affected_rows();
            if($res == 1)
            {
                if($data['interview_status'] == 1)
                {
                $select = $this->common_model->getsingle(INTERVIEWS,$where);    
                    if($userType['type']  == 'Recruiter'){
                        $insert['request_id'] = $select->request_id;
                        $insert['interviewer_name'] = $select->interviewer_name;
                        $insert['location'] = $select->location;
                        $insert['latitude'] = $select->latitude;
                        $insert['longitude'] = $select->longitude;
                        $insert['date'] = $select->date;
                        $insert['time'] = $select->time;
                        //$insert['interview_status'] = $select->interview_status;
                        $insert['upd'] = $select->upd;
                        $insert['type'] = 'Employer';
                        $this->db->insert(INTERVIEWS,$insert);
                        $interviewId = $this->db->insert_id();
                        $select = $this->common_model->getsingle(INTERVIEWS,array('interviewId'=>$interviewId)); 
                        
                        return array('type' => 'AC','data'=>$select);
                    }

                    return array('type' => 'AC','data'=>$select);
                }
                if($data['interview_status'] == 2)
                {

                    $this->db->set('is_finished',1);
                    $this->db->where('requestId',$getRes->request_id);
                    $this->db->update('requests');
                    
                    return array('type' => 'DE');
                }

            }else{
                return array('type'=> "CU");
            }
        }
        }else{
            return array('type'=> "IND");
        }
        }else{
             return array('type'=> "NRF");
        }
        return FALSE;
    }//end of function 

    function deleteRequest($data,$userid,$interviewId){//function for delete request.
        $query = $this->db->select('request_by')
        ->where('request_by',$userid)
        ->get(REQUESTS);
        if($query->num_rows()){
            date_default_timezone_set('Asia/Kolkata');
            $data['upd']          = date('Y-m-d H:i:s',time());

            $this->db->set($data);
            $this->db->where('request_by',$userid);
            $this->db->update('requests');
            $affected = $this->db->affected_rows();
            if($affected){
                $this->db->set('is_delete',1);
                $this->db->where($interviewId);
                $this->db->update('interviews');
                return array('type'=>"DR");
            }else{
                return array('type'=>"AD");
            }
        }else{
            return array("type"=>'NMR');
        }
    }//end of function.. 

    function update_offerd($data, $where)
    {//update interview status when accept and declined..
       $resp =  $this->db->select('`interviews`.`request_id`,`requests`.`is_finished`')->join('`requests`','`interviews`.`request_id` = `requests`.`requestId`')->where($where)->get(INTERVIEWS);
       if($resp->num_rows()){
        $getRes = $resp->row();
        if($getRes->is_finished == 1 ){
            return array('type'=> "IRC");
        }
        //echo "hello";
        if($getRes->is_finished == 0 ){
        if($data['request_offer_status'] == 1 OR $data['request_offer_status'] == 2){
        date_default_timezone_set('Asia/Kolkata');
        $data['upd']          = date('Y-m-d H:i:s',time());
        $this->db->set($data);
        $this->db->where('requestId',$getRes->request_id);
        $this->db->update('requests');

        $res = $this->db->affected_rows();
            if($res == 1)
            {
                if($data['request_offer_status'] == 1)
                {
                    return array('type' => 'AC');
                }
                if($data['request_offer_status'] == 2)
                {
                    return array('type' => 'DE');
                }

            }else{
                return array('type'=> "CU");
            }
        }
        }else{
            return array('type'=> "IND");
        }
        }else{
             return array('type'=> "NRF");
        }
        return FALSE;
    }//end of function    

    function processData_get($data){
            //echo $get_id;
        $this->db->select('`request`.`requestId`,`request`.`request_offer_status`,
            `request`.`is_finished`, 
            `request`.`created_on` as Created Date,(SELECT COUNT(`request_id`) FROM `interviews` WHERE `request_id` = `request`.`requestId`) as count'
        );
        $this->db->join('`interviews` `interview`','`interview`.`request_id` = `request`.`requestId`');
        $this->db->where($data);
        //$this->db->where('`interview`.`request_token`',$get_id->request_token);
        //$this->db->where('`interview`.`is_delete`',0);
        $this->db->order_by('`request`.`requestId`','DESC');
        $this->db->limit(1);
        $res = $this->db->get('`requests` `request`');

        //echo $this->db->last_query();
        if($res->num_rows()){
            $result = $res->row();
            $result->interviewData = $this->interviewData(array('request_id'=>$result->requestId));
            return array('type'=>'DR','data'=>$result);
        }
        return FALSE;
    }

    function interviewData($data){
        $this->db->select('`interview`.`interviewId`,`interview`.`type`,`interview`.`is_delete`,
            `interview`.`interview_status`,`interview`.`date`,`interview`.`time`,`interview`.`location`,`interview`.`latitude`,`interview`.`longitude`,
            `interview`.`upd`'
        );
        $this->db->where($data);
        $res = $this->db->get('`interviews` `interview`');
        if($res->num_rows()){
            $result = $res->result();
            return $result;
        }
        return false;
    }
    

        
}//ENd Class
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favourites_list_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    
    var $column_order =array(null,'u.fullName','u.profileImage'); //set column field database for datatable orderable
    var $column_search = array('u.fullName'); //set column field database for datatable searchable
    var $order = array('fav.favouriteId' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($userId='',$userType =''){

        $this->userType = $userType;
        $this->userId = $userId;
    }

    function prepare_query(){

        $defaultImg = base_url().DEFAULT_USER;
        $user_image = base_url().USER_THUMB;
        if($this->userType == 'business'){
            $where['fav.favourite_for'] =  $this->userId;
        }else{
            $where['fav.favourite_by'] =  $this->userId;
        }
        $this->db->select('fav.*,u.userId, u.fullName,u.businessName,NOW() as today_datetime,(case 
                    when( u.profileImage = "" OR u.profileImage IS NULL) 
                    THEN "'.$defaultImg.'"
                    ELSE
                    concat("'.$user_image.'",u.profileImage) 
                   END ) as profileImage
                  
                   ');
        $this->db->from(FAVOURITES .' as fav'); //favourites
        $this->userType == 'business' ? $this->db->join(USERS. ' as u', "fav.favourite_by = u.userId") : $this->db->join(USERS. ' as u', "fav.favourite_for = u.userId"); 
        
        $this->db->where($where);
        
    }
   
    //prepare post list query
    private function posts_get_query(){

        $this->prepare_query();
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
            if(!empty($this->where))
                $this->db->where($this->where); 

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

    function get_list(){

        $this->posts_get_query();
        if(isset($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length']= '10';
        } else
        $_POST['length']= $_POST['length'];
        
        if(isset($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start']= $_POST['start'];
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        //print_r($_POST);die;
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered(){

        $this->posts_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all(){

        $this->prepare_query();
        return $this->db->count_all_results();
    }

}
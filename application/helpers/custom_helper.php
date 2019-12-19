<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Custom Helper
* Author: Manish Pareek
* version: 1.0
*/

/**
 * [To print array]
 * @param array $arr
*/
if ( ! function_exists('pr')) {
  function pr($arr)
  {
    echo '<pre>'; 
    print_r($arr);
    echo '</pre>';
    die;
  }
}

/**
 * [To print last query]
*/
if ( ! function_exists('lq')) {
  function lq()
  {
    $CI = & get_instance();
    echo $CI->db->last_query();
    die;
  }
}


/**
 * [make img url from name or check if string already has url ]
 */
if (!function_exists('make_img_url')) {
    function make_user_img_url($img_str) {
        if (!empty($img_str)) { 
            //check if image consists url- happens in social login case
            if (filter_var($img_str, FILTER_VALIDATE_URL)) { 
                $img_src = $img_str;
            }
            else{
                $img_src = base_url().USER_THUMB.$img_str;
            }
        }
        else{
            $img_src = base_url().DEFAULT_USER; //return default image if image is empty
        }
        
        return $img_src;
    }
}


/**
 * [To get database error message]
*/
if ( ! function_exists('db_err_msg')) {
  function db_err_msg()
  {
    $CI = & get_instance();
    $error = $CI->db->error();
    if(isset($error['message']) && !empty($error['message'])){
      return 'Database error - '.$error['message'];
    }else{
      return FALSE;
    }
  }
}

/**
 * [To parse html]
 * @param string $str
*/
if (!function_exists('parseHTML')) {
  function parseHTML($str) {
    $str = str_replace('src="//', 'src="https://', $str);
    return $str;
  }
}

/**
 * [To create directory]
 * @param string $folder_path
*/
if (!function_exists('make_directory')) {
  function make_directory($folder_path) {
    if (!file_exists($folder_path)) {
        mkdir($folder_path, 0777, true);
    }
  }
}

if (!function_exists('emoji_decode')) {
  function emoji_decode($str) {
        $decode = urldecode($str);
        return $decode;
  }
}


/**
 * [To get current datetime]
*/
if ( ! function_exists('datetime')) {
  function datetime($default_format='Y-m-d H:i:s')
  {
    $datetime = date($default_format);
    return $datetime;
  }
}



/**
 * [To encode string]
 * @param string $str
*/
if ( ! function_exists('encoding')) {
  function encoding($str){
      $one = serialize($str);
      $two = @gzcompress($one,9);
      $three = addslashes($two);
      $four = base64_encode($three);
      $five = strtr($four, '+/=', '-_.');
      return $five;
  }
}

/**
 * [To decode string]
 * @param string $str
*/
if ( ! function_exists('decoding')) {
  function decoding($str){
    $one = strtr($str, '-_.', '+/=');
      $two = base64_decode($one);
      $three = stripslashes($two);
      $four = @gzuncompress($three);
      if ($four == '') {
          return "z1"; 
      } else {
          $five = unserialize($four);
          return $five;
      }
  }
}


/**
 * [To check number is digit or not]
 * @param int $element
*/
if ( ! function_exists('is_digits')) {
  function is_digits($element){ // for check numeric no without decimal
      return !preg_match ("/[^0-9]/", $element);
  }
}

/**
 * [To get all months list]
*/
if ( ! function_exists('getMonths')) {
  function getMonths(){
    $monthArr = array('January','February','March','April','May','June','July','August','September','October','November','December');
    return $monthArr ;
  }
}

/**
 * [To upload all files]
 * @param string $subfolder
 * @param string $ext
 * @param int $size
 * @param int $width
 * @param int $height
 * @param string $filename
*/
if ( ! function_exists('fileUploading')) {
  function fileUploading($subfolder,$ext,$size="",$width="",$height="",$filename)
  {
      $CI = & get_instance();
      $config['upload_path']   = 'uploads/'.$subfolder.'/'; 
      $config['allowed_types'] = $ext; 
      if($size){
        $config['max_size']   = 100; 
      }
      if($width){
        $config['max_width']  = 1024; 
      }
      if($height){
        $config['max_height'] = 768;  
      }
      $CI->load->library('upload', $config);
      if (!$CI->upload->do_upload($filename)) {
        $error = array('error' => strip_tags($CI->upload->display_errors())); 
        return $error;
      }
     else { 
        $data = array('upload_data' => $CI->upload->data()); 
        return $data;
     } 
  }
}



if (!function_exists('fileUpload')) {

    function fileUpload($filename, $subfolder, $ext, $size = "", $width = "", $height = "") {
        $CI = & get_instance();
        $config['upload_path'] = 'uploads/' . $subfolder . '/';
        $config['allowed_types'] = $ext;
        if ($size) {
            $config['max_size'] = 10000;
        }
        if ($width) {
            $config['max_width'] = 102400;
        }
        if ($height) {
            $config['max_height'] = 76800;
        }
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        if (!$CI->upload->do_upload($filename)) {
            $error = array('error' => $CI->upload->display_errors());
            return $error;
        } else {
            $data = array('upload_data' => $CI->upload->data());
            return $data;
        }
    }

}
/**
 * [To check null value]
 * @param string $value
*/
if ( ! function_exists('null_checker')) {
  function null_checker($value,$custom="")
  {
    $return = "";
    if($value != "" && $value != NULL){
      $return = ($value == "" || $value == NULL) ? $custom : $value;
      return $return;
    }else{
      return $return;
    }
  }
}

/**
* [To get user image thumb]
* @param  [string] $filepath
* @param  [string] $subfolder
* @param  [int] $width
* @param  [int] $height
* @param  [int] $min_width
* @param  [int] $min_height
*/
if (!function_exists('get_image_thumb'))
{
  function get_image_thumb($filepath,$subfolder,$width,$height,$min_width="",$min_height="")
  {

    if(empty($min_width))
    {
      $min_width = $width;
    }
    if(empty($min_height))
    {
      $min_height = $height;
    }
    /* To get image sizes */
    $image_sizes = getimagesize($filepath);
    if(!empty($image_sizes))
    {
      $img_width  = $image_sizes[0];
      $img_height = $image_sizes[1];
      if($img_width <= $min_width && $img_height <= $min_height)
      {
        return $filepath;
      }
    }

    $ci   = &get_instance();
    /* Get file info using file path */
    $file_info = pathinfo($filepath);
    if(!empty($file_info)){
      $filename = $file_info['basename'];
      $ext      = $file_info['extension'];
      $dirname  = $file_info['dirname'].'/';
      $path     = $dirname.$filename;
      $file_status = @file_exists($path);
      if($file_status){
          $config['image_library']  = 'gd2';
          $config['source_image']   = $path;
          $config['create_thumb']   = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['width']          = $width;
          $config['height']         = $height;
          $ci->load->library('image_lib', $config);
          $ci->image_lib->initialize($config);
          if(!$ci->image_lib->resize()) {
              return $path;
          } else {
            @chmod($path, 0777);
            $thumbnail = preg_replace('/(\.\w+)$/im', '', urlencode($filename)) . '_thumb.' . $ext;
              return 'uploads/'.$subfolder.'/'.$thumbnail;
          }
      }else{
        return $filepath;
      }
    }else{
      return $filepath;
    }
  }
}



/**
* [To delete file from directory]
* @param  [string] $filename
* @param  [string] $filepath
*/
if (!function_exists('delete_file'))
{
  function delete_file($filename,$filepath)
  {
    /* Send file path last slash */
    $file_path_name = $filepath.$filename;
    if(!empty($filename) && @file_exists($file_path_name) && @unlink($file_path_name)){
      return TRUE;
    }else{
      return FALSE;
    }
  }
}

/**
 * [for loading css on specific pages ]
 */
if (!function_exists('load_css')) {

    function load_css($css){
        
        if(empty($css))
            return;

        $css_base_path = '';
        if(is_array($css)){

            foreach($css as $script_src){

                if(strpos($script_src, 'http://') === false && strpos($script_src, 'https://') === false){

                    $css_base_path = base_url() . 'frontend_asset/css/';
                }

                echo "<link href=\"{$css_base_path}{$script_src}\" rel=\"stylesheet\">\n";
            }
        }
        else{
            if(strpos($css, 'http://') === false && strpos($css, 'https://') === false){
                $css_base_path = base_url() . 'frontend_asset/css/';
            }
            echo "<link href=\"{$css_base_path}{$css}\" rel=\"stylesheet\">";
        }
    }
}

/**
 * [for loading css in ADMIN on specific pages ]
 */
if (!function_exists('load_amdin_css')) {

    function load_admin_css($css=''){
        
        if(empty($css))
            return;

        $css_base_path = '';
        if(is_array($css)){
            foreach($css as $script_src){
                if(strpos($script_src, 'http://') === false && strpos($script_src, 'https://') === false){
                    $css_base_path = base_url() . 'themes/admin/css/';
                }
                echo "<link href=\"{$css_base_path}{$script_src}\" rel=\"stylesheet\">\n";
            }
        }
        else{
            if(strpos($css, 'http://') === false && strpos($css, 'https://') === false){
                $css_base_path = base_url() . 'themes/admin/css/';
            }
            echo "<link href=\"{$css_base_path}{$css}\" rel=\"stylesheet\">";
        }
    }
}

/**
 * [for loading js on specific pages ]
 */
if (!function_exists('load_js')) {

    function load_js($js=''){
        
         if(empty($js))
            return;

        $js_base_path = '';
        if(is_array($js)){

            foreach($js as $script_src){

                if(strpos($script_src, 'http://') === false && strpos($script_src, 'https://') === false){

                    $js_base_path = base_url() . 'frontend_asset/js/';
                }

                echo "<script src=\"{$js_base_path}{$script_src}\"></script>\n";
            }
        }
        else{

            if(strpos($js, 'http://') === false && strpos($js, 'https://') === false){
                $js_base_path = base_url() . 'frontend_asset/js/';
            }
            echo "<script src=\"{$js_base_path}{$js}\"></script>";
        }
    }
}

/**
 * [for loading js in ADMIN on specific pages ]
 */
if (!function_exists('load_admin_js')) {

    function load_admin_js($js=''){
        
        if(empty($js))
            return;

        $js_base_path = '';
        if(is_array($js)){
            foreach($js as $script_src){
                if(strpos($script_src, 'http://') === false && strpos($script_src, 'https://') === false){
                    $js_base_path = base_url() . ADMIN_THEME;
                }
                echo "<script src=\"{$js_base_path}{$script_src}\"></script>\n";
            }
        }
        else{
            if(strpos($js, 'http://') === false && strpos($js, 'https://') === false){
                $js_base_path = base_url() . ADMIN_THEME;
            }
            echo "<script src=\"{$js_base_path}{$js}\"></script>";
        }
    }
}


/**
 * [for making alias of title or statement ]
 */
if (!function_exists('make_alias')) {

    function make_alias($string){
        $string = strtolower(str_replace(' ', '_', $string));
        $alias = preg_replace('/[^A-Za-z0-9]/', '', $string);
        $CI = get_instance();  
        
        
    }
}

/**
 * [for making alias of title or statement ]
 */
if (!function_exists('alpha_spaces')) {

    function alpha_spaces($string){
        if (preg_match('/^[a-zA-Z ]*$/', $string)) {
            return TRUE;
        }
        else{
            return FALSE; //match failed(string contains characters other than aplhabets and spaces)
        }
    }
}


if (!function_exists('user_types')) {

    function user_types(){
       $arr =  array('Individual'=>'individual','Business'=>'business','Both (Individual & Business)'=>'both');
       return $arr;
    }
}

/**
 * [to display placeholder text when actual text is empty ]
 */
if (!function_exists('display_placeholder_text')) {

    function display_placeholder_text($string=''){
        if (empty($string)) {
            return 'NA'; //if string is empty return pleacholder text
        }
        else{
            return $string;  //if not return string as it is;
        }
    }
}

function time_elapsed_string($datetime, $full = false) {
          $now = new DateTime;
          $ago = new DateTime($datetime);
          $diff = $now->diff($ago);

          $diff->w = floor($diff->d / 7);
          $diff->d -= $diff->w * 7;

          $string = array(
              'y' => 'year',
              'm' => 'month',
              'w' => 'week',
              'd' => 'day',
              'h' => 'hour',
              'i' => 'minute',
              's' => 'second',
          );
          foreach ($string as $k => &$v) {
              if ($diff->$k) {
                  $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
              } else {
                  unset($string[$k]);
              }
          }

          if (!$full) $string = array_slice($string, 0, 1);
          return $string ? implode(', ', $string) . ' ago' : 'just now';
       }//End Function

    if(!function_exists('status_color')){ 
       function status_color($status){

          $status_arr = array( '0' => '#ff5722', '1' => '#4caf50', '2' => '#ff5722' ); 
          if(array_key_exists($status, $status_arr)){
            return $status_arr[$status]; 
          }
          else{
           return 'NA'; 
          } 
        }
    }


     //for notification
    if (!function_exists('log_event')) {
      function log_event($msg, $file_path='') {
        if(empty($file_path)){
          $file_path = FCPATH.'common_log.txt';
        }
        
        $perfix = '['.datetime().'] '; //add current date time
        $msg = $perfix.$msg."\r\n"; //create new line
        error_log($msg,3, $file_path);
      }
    }

    

    if(!function_exists('star_color')){ 
       function star_color($points){

          if($points <= 9){
            $iconName = "gray_star.png";
          }elseif($points <= 24){
              $iconName = "brown_star.png";

          }elseif($points <= 49){
              $iconName = "black_star.png";

          }elseif($points <= 99){
              $iconName = "blue_star.png";

          }elseif($points <= 249){
              $iconName = "green_star.png";

          }else{
              $iconName = "yellow_star.png";
          }
          $url = base_url().ADMIN_THEME.'custom/images/star_icons/'.$iconName;
          return $url;
        }
    }



    if(!function_exists('get_availability')){ 
        function get_availability(){

            return array('immediate'=>'Immediate','1-4'=>'1-4 weeks','5-8'=>'5-8 weeks','9-12'=>'9-12 weeks','12+'=>'12+ weeks');
        }
    }

    if(!function_exists('get_salary_drop_down')){
      function get_salary_drop_down(){
        return array('any'=>'Any','0-20000'=>'$0 - $20,000','20000-40000'=>'$20,000 - $40,000','40000-60000'=>'$40,000- $60,000','60000-80000'=>'$60,000 - $80,000','80000-100000'=>'$80,000 - $100,000','100000-120000'=>'$100,000 - $120,000','120000-150000'=>'$120,000 - $150,000','150000-200000'=>'$150,000 - $200,000','200000-99999999'=>'$200,000+');
      }
    }

    if(!function_exists('get_experience_drop_down')){
      function get_experience_drop_down(){
        return array('any'=>'Any','1'=>'1+ years','2'=>'2+ years','3'=>'3+ years','4'=>'4+ years','5'=>'5 years','6'=>'6 years','7'=>'7 years','8'=>'8 years','9'=>'9 years','10'=>'10 years');
      }
    }

    if(!function_exists('employement_type')){
      function employement_type(){
        return array('full_time'=>'Full time','part_time'=>'Part time','casual'=>'Casual','contract'=>'Contract/Temp');
      }
    }

    if(!function_exists('rating_show')){//this function is made to get starts and show prefiled and its dynamic can select.
      function rating_show(){
        $ratingInput = array(
          0=>array('input'=>array('id'=>'star5','value'=>'5','name'=>'rating'),'label'=>array('title'=>'Awesome - 5 stars','class'=>'full')),
          1=>array('input'=>array('id'=>'star4half','value'=>'4.5','name'=>'rating'),'label'=>array('title'=>'Pretty good - 4.5 stars','class'=>'half')),
          2=>array('input'=>array('id'=>'star4','value'=>'4','name'=>'rating'),'label'=>array('title'=>'Pretty good - 4 stars','class'=>'full')),
          3=>array('input'=>array('id'=>'star3half','value'=>'3.5','name'=>'rating'),'label'=>array('title'=>'Meh - 3.5 stars','class'=>'half')),
          4=>array('input'=>array('id'=>'star3','value'=>'3','name'=>'rating'),'label'=>array('title'=>'Meh - 3 stars','class'=>'full')),
          5=>array('input'=>array('id'=>'star2half','value'=>'2.5','name'=>'rating'),'label'=>array('title'=>'Kinda bad - 2.5 stars','class'=>'half')),
          6=>array('input'=>array('id'=>'star2','value'=>'2','name'=>'rating'),'label'=>array('title'=>'Kinda bad - 2 stars','class'=>'full')),
          7=>array('input'=>array('id'=>'start1half','value'=>'1.5','name'=>'rating'),'label'=>array('title'=>'big time - 1.5 star','class'=>'half')),
          8=>array('input'=>array('id'=>'star1','value'=>'1','name'=>'rating'),'label'=>array('title'=>'big time - 1 star','class'=>'full')), 
          9=>array('input'=>array('id'=>'starhalf','value'=>'.5','name'=>'rating'),'label'=>array('title'=>'Very bad - 0.5 stars','class'=>'half')));
        return $ratingInput;
      }
    }


    if(!function_exists('get_checked')){//this function is made to get starts and show prefiled and its dynamic can select.
      function get_checked($val ='',$rating=''){
            if($val == $rating AND $rating == '.5'){ 
              $checked = 'checked';
            }elseif($val == $rating AND $rating == '1'){
                 $checked = 'checked';
            }elseif($val == $rating AND $rating == '1.5'){
                 $checked = 'checked';
            }elseif($val == $rating AND $rating == '2'){
                 $checked = 'checked';
            } elseif($val == $rating AND $rating == '2.5'){
                 $checked = 'checked';
            } elseif($val == $rating AND $rating == '3'){
                 $checked = 'checked';
            }  elseif($val == $rating AND $rating == '3.5'){
                 $checked = 'checked';
            }  elseif($val == $rating AND $rating == '4'){
                 $checked = 'checked';
            }  elseif($val == $rating AND $rating == '4.5'){
                 $checked = 'checked';
            }  elseif($val == $rating AND $rating == '5'){
                 $checked = 'checked';
            } else{
                $checked = '';
            }
            return $checked; 
    }
  }

 if(!function_exists('first_letter_capital')){//this function make first letter of first word and after fulll stop first character upper case.
  function first_letter_capital($str){

    $output = preg_replace_callback('/([.!?])\s*(\w)/', function ($str) {
    return strtoupper($str[1] . ' ' . $str[2]);
    }, ucfirst(strtolower($str)));
    return $output;

  } 
}

?>
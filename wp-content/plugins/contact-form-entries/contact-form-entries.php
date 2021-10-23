<?php
/**
* Plugin Name: Contact Form Entries
* Description: Save form submissions to the database from <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>, <a href="https://wordpress.org/plugins/ninja-forms/">Ninja Forms</a>, <a href="https://elementor.com/widgets/form-widget/">Elementor Forms</a> and <a href="https://wordpress.org/plugins/wpforms-lite/">WP Forms</a>.
* Version: 1.2.3
* Requires at least: 3.8
* Tested up to: 5.8
* Author URI: https://www.crmperks.com
* Plugin URI: https://www.crmperks.com/plugins/contact-form-plugins/crm-perks-forms/
* Author: CRM Perks
*/
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_form' ) ):


class vxcf_form {
    

  public $domain = 'vxcf-leads';
  public  $fields = null;
  
  public static $id = 'vxcf_leads';
  public static $type = "vxcf_form";
  public static $path = ''; 

  public static  $version = '1.2.3';
  public static $upload_folder = 'crm_perks_uploads';
  public static $db_version='';  
  public static $base_url='';  
  public static $vx_plugins;  
  public static $note;
  public static $feeds_res;        
  public static $tooltips;    
  public static $plugin;    
  public static $pages;    
  public static $show_screen_options=false;
  public static $sql_join='';
  public static $sql_where='';    
  public static $sql_field_name='';    
  public static $sql_select_fields='';    
  public static $sql_order_by='';    
  public static $forms;    
  public static $form_id;    
  public static $user_id;    
  public static $is_pr;    
  public static $form_id_string;    
  public static $form_fields;
  public static $form_fields_temp;
  //data object
  public static $data = null;
  //settings    
  public static $meta = null;    

public function instance(){

add_action( 'plugins_loaded', array( $this, 'setup_main' ) );
register_deactivation_hook(__FILE__,array($this,'deactivate'));
register_activation_hook(__FILE__,(array($this,'activate')));
self::$path=$this->get_base_path(); 
self::$base_url=vxcf_form::get_base_url();
add_action('init', array($this,'init'));
add_filter('crmperks_forms_field_validation_message',array($this,'validate_crmperks_field'),10,4);
}


public  function init(){
    //save screen and url for all forms
add_action('wp_footer', array($this,'footer_js'),33);
wp_register_script( 'vx-tablesorter-js', self::$base_url. 'js/jquery.tablesorter.js',array('jquery') );
wp_register_script( 'vx-tablepager-js', self::$base_url. 'js/jquery.tablesorter.pager.js',array('jquery') );
wp_register_script( 'vx-tablewidgets-js', self::$base_url. 'js/jquery.tablesorter.widgets.js',array('jquery') );

if(!empty($_GET['vx_crm_form_action']) && $_GET['vx_crm_form_action'] == 'download_csv'){
  $key=$this->post('vx_crm_key');
   $form_ids=get_option('vx_crm_forms_ids'); 
   if(is_array($form_ids)){ 
     $form_id=array_search($key,$form_ids);
     if(!empty($form_id)){
         vxcf_form::set_form_fields($form_id);
         self::download_csv($form_id,array('vx_links'=>'false'));
         die();
     }  
   } 
}
//$form=vxcf_form::get_form_fields('el_2669e21_5190');
//$form=cfx_form::get_form('1'); var_dump($form); die();

}

public  function setup_main(){ 
  //handling post submission.
//  add_action("gform_entry_created", array($this, 'gf_entry_created'), 40, 2);
// add_filter('wpcf7_mail_components', array($this, 'submission'), 999, 3);
// add_filter('wpcf7_posted_data', array($this, 'entry_created'));
// wordpress sets current user to 0 here wp-includes/rest-api.php rest_cookie_check_errors function 
 add_action('rest_api_init', array($this, 'verify_logged_in_user'),10); 
  add_filter('wpcf7_before_send_mail', array($this, 'create_entry_cf'),10);
  //add_action('fsctf_mail_sent', array($this, 'create_entry_fscf'));
  add_action("gform_entry_created", array($this, 'create_entry_gf'), 30, 2);
  //formidable
  add_action('frm_after_create_entry', array($this, 'create_entry_fd'), 30, 2);
  //add_action('ninja_forms_post_process', array($this, 'create_entry_na'),30);
  add_action('ninja_forms_after_submission', array($this, 'create_entry_na'),30);
  add_action('iphorm_post_process', array($this, 'create_entry_qu'), 30);
  add_action('caldera_forms_submit_post_process_end', array($this, 'create_entry_ca'), 10, 3);
  add_action('cforms2_after_processing_action', array(&$this, 'create_entry_c2'),30);
  add_action('cntctfrm_get_mail_data', array(&$this, 'create_entry_be'),30);
  add_action('ufbl_email_send', array(&$this, 'create_entry_ul'),30);
  add_action('grunion_pre_message_sent', array(&$this, 'create_entry_jp'),30,3);
  add_filter('crmperks_forms_new_submission', array(&$this, 'create_entry_vf'),40,3);
  //add_action( 'woocommerce_checkout_update_order_meta',array(&$this,'create_entry_wc'), 30, 2 );
  add_action( 'wpforms_process_entry_save',array(&$this,'create_entry_wp'), 30, 4 );
 //   add_action('cntctfrm_get_attachment_data', array(&$this, 'create_entry_be'),30);
// add_filter('si_contact_email_fields_posted', array($this, 'test'),10,2);
//elemntor form
 add_action( 'elementor_pro/forms/new_record', array($this,'create_entry_el'), 10 );
// add_action('wpcf7_submit', array($this, 'submit'),10, 2);
//add_action('wpcf7_init', array($this, 'create_entry'));
//$this->create_entry();
add_shortcode('vx-entries', array($this, 'entries_shortcode'));  

  if(is_admin()){
load_plugin_textdomain('contact-form-entries', FALSE,  self::plugin_dir_name(). '/languages/' );     
  self::$db_version=get_option(vxcf_form::$type."_version");
  if(self::$db_version != self::$version && current_user_can( 'manage_options' )){
  
  $this->install_plugin();    
  
/*  $install_data=get_option(vxcf_form::$type."_install_data");
  if(empty($install_data)){
  update_option(vxcf_form::$type."_install_data", array('time'=>current_time( 'timestamp' , 1 )));
  }*/
$meta=$this->get_meta();  
  if(!empty($meta['save_forms'])){
 $forms=vxcf_form::get_forms();
 $forms_arr=vxcf_form::forms_list($forms);
$new_ids=array_diff_key($forms_arr,$meta['save_forms']);
if(!empty($new_ids)){
   $disable=array();
    foreach($new_ids as $k=>$v){
     $disable[$k]='yes';   
    }
    $meta['disable_track']=$disable;
    unset($meta['save_forms']);
    self::$meta=$meta;
 update_option(vxcf_form::$id.'_meta',$meta);   
}
} }
//plugin api
$this->plugin_api(true);
require_once(self::$path . "includes/crmperks-cf.php");
require_once(self::$path . "includes/plugin-pages.php");   
self::$pages=new vxcf_form_pages(); 
$pro_file=self::$path . 'pro/pro.php';
if(file_exists($pro_file)){ include_once($pro_file); self::$is_pr='1'; }
$pro_file=self::$path . 'pro/add-ons.php';
if(file_exists($pro_file)){ include_once($pro_file); }
$pro_file=self::$path . 'wp/crmperks-notices.php';
if(file_exists($pro_file)){ include_once($pro_file); }
//$forms=vxcf_form::get_forms();  
}

}


public function plugin_api($start_instance=false){
$file=self::$path . "pro/plugin-api.php";
if( file_exists($file)){   
if(!class_exists('vxcf_plugin_api')){    include_once($file); }
if(class_exists('vxcf_plugin_api')){
 $update_id = "400001";
 $title='Contact Form Entries Plugin';
 $slug=self::get_slug();
 $settings_link=self::link_to_settings();
 $is_plugin_page=self::is_crm_page(); 
self::$plugin=new vxcf_plugin_api(self::$id,self::$version,self::$type,$this->domain,$update_id,$title,$slug,self::$path,$settings_link,$is_plugin_page);
if($start_instance){
self::$plugin->instance();
} }
} 
}

public function install_plugin(){
$data=vxcf_form::get_data_object();
$data->update_table();
if(empty(self::$path)){   self::$path=$this->get_base_path(); }
  require_once(self::$path . "includes/install.php"); 
  $install=new vxcf_form_install();
  $install->create_roles();   
  $install->create_upload_dir();
  update_option(vxcf_form::$type."_version", self::$version);
}
public function entries_shortcode($atts){
 
  $form_id='';
  if(!empty($atts['form-id'])){
   $form_id=$atts['form-id'];   
  }
  if(!empty($atts['form-name'])){
  $forms_arr=get_option('vxcf_all_forms',array()); 
  if(is_array($forms_arr) && count($forms_arr)>0){
   foreach($forms_arr as $form_key=>$form_type){
        if(!empty($form_type['forms']) && is_array($form_type['forms']) && count($form_type['forms'])>0){
   foreach($form_type['forms'] as $k=>$v){
   if($v == $atts['form-name']){ 
   $form_id=$form_key.'_'.$k;    
   }
   }
        } 
   }   
  }     
  }
$fields=vxcf_form::get_form_fields($form_id);
$fields['created']=array('name'=>'created','_id'=>'created', 'label'=> __('Created', 'contact-form-entries'));

  $col_end=count($fields);
  if(!empty($atts['cols'])){
   $col_end=(int)$atts['cols'];   
  }  
    $col_start=0;
  if(!empty($atts['col-start'])){
   $col_start=(int)$atts['col-start'];   
  
  }
  
    if(!empty($atts['col-labels'])){
     $col_labels=array_map('trim',array_map('strtolower',explode(',',$atts['col-labels'])));   
   if(is_array($fields) && count($fields)>0){
    foreach($fields as $k=>$v){ 
      if(isset($v['label'] ) && !in_array( strtolower($v['label']),$col_labels)){
      unset($fields[$k]);    
      }  
    }   
   }   
  }else{
  $fields=array_splice($fields,$col_start,$col_end);    
  } 

vxcf_form::$form_fields=$fields;
    $css='';
    if(!empty($atts['font-size'])){
    // $atts['font-size']='x-small'; 
      $css=' style="font-size: '.$atts['font-size'].'"';     
    }
  

      $class='vx_entries_table ';
    if(!empty($atts['class'])){
     $class.=$atts['class'];   
    }
   $class=' class="'.$class.'"';   
  
      $table_id='';
    if(!empty($atts['id'])){
   $table_id='id="'.$atts['font-size'].'"';   
  }
  //var_dump($fields);
  $limit='20';
    if(!empty($atts['limit'])){
   $limit=$atts['limit'];   
  }  
  $start='0';
    if(!empty($atts['start'])){
   $start=$atts['start'];   
  }
  $search=$export='';
  if($this->do_actions() ){
   if(!empty($atts['search'])){
   $search=$atts['search'];   
  }  
  if(!empty($atts['export'])){
   $form_ids=get_option('vx_crm_forms_ids');
     if(!is_array($form_ids)){ $form_ids=array(); }
     if(!isset($form_ids[$form_id])){
      $form_ids[$form_id]=rand(99999,999999999).uniqid().time().rand(999,9999999).uniqid();  
     update_option('vx_crm_forms_ids',$form_ids); 
     }
   $export=$form_ids[$form_id];   
  } 
  }
    $page_size='3';
    if(!empty($atts['per-page'])){
   $page_size=$atts['per-page'];   
  }  
 $offset=$this->time_offset(); 
  $req=array('start'=>$start,'vx_links'=>'false');
    if(isset($atts['user-id'])){
   $req['user_id']=!empty($atts['user-id']) ? (int)$atts['user-id'] : get_current_user_id();   
  } 
 $data=vxcf_form::get_data_object(); 
$entries=$data->get_entries($form_id,$limit,$req); 
$leads=array();
if(!empty($entries['result'])){
$leads=$entries['result'];    
}

$base_url=vxcf_form::get_base_url();
 if(!empty($atts['sortable'])){
wp_enqueue_script( 'vx-tablesorter-js');
wp_enqueue_script( 'vx-tablewidgets-js');
 if(!empty($atts['pager'])){
wp_enqueue_script( 'vx-tablepager-js');
 }
wp_enqueue_style('vx-tablesorter-css');
 }
 $leads_table=apply_filters('crmperks_entries_template',self::$path . "templates/leads-table.php");
  /* foreach($leads as $lead){

  foreach($fields as $field){  

if($field['name'] == 'time'){
  $field['name']='created';  
}

$field_label='';
if(isset($lead[$field['name']])){
 $field_label=maybe_unserialize($lead[$field['name']]);   

if(is_array($field_label)){ 
  $field_label=implode(', ',$field_label);  
}else if($field['name'] == 'created'){
   $field_label=strtotime($field_label)+$offset;
$field_label= date('M-d-Y H:i:s',$field_label);   
}

}

  }
 
  } die('-----------');*/
  ob_start();
include($leads_table);
return ob_get_clean();
}

public function verify_logged_in_user(){
   self::$user_id=get_current_user_id();
}
public function create_entry_auto($entry=""){

/*
     $submission = WPCF7_Submission::get_instance();  
     $uploaded_files = $submission->uploaded_files();
     $val = $submission->get_posted_data($name);

 
*/
/*$data_json='{"_wpcf7":"69" ,"your-name" :"touseef ahmad","your-email" :"admin@localhost.com","your-subject":"subject test"}';
$files_json='{"your-file":"C:/wamp/www/wp19/wp-content/uploads/wpcf7_uploads/0845175440/cookies.txt"}';
$data_arr=json_decode($data_json,true);
$id=uniqid();
$data_arr['your-name']=$id.' name';
$data_arr['your-subject']=$id.' subject';
$data_arr['your-message']=$id.' '.$data_arr['your-subject'].' '.$id.' '.$data_arr['your-subject'].' '.$id.' '.$data_arr['your-subject'].' '.$id.' '.$data_arr['your-subject'].' '.$id.' '.$data_arr['your-subject'].' '.$id.' '.$data_arr['your-subject'];
$data_arr['your-emai']=$id.'_email@gmail.com';
$files=json_decode($files_json,true);
$data=array_merge($data_arr,$files);*/
//
      $form_id=0;
      if(!empty($data['_wpcf7'])){
    $form_id=$data['_wpcf7'];    
     }
//     
$tags=vxcf_form::get_form_fields($form_id);  
$arr=array();
if(is_array($tags)){
  foreach($tags as $k=>$v){
   if(!empty($k) && isset($data[$k])){
   $arr[$k]=$data[$k];    

   }   
  }  
}
if(is_array($arr) && count($arr)>0){
  $data=vxcf_form::get_data_object();
  $lead=$data->create_lead($arr,$form_id);
}
 //var_dump($tags,$arr); die();
}

public function create_entry($lead,$form,$type,$info='',$save=true,$entry_id=''){
if(!is_array($info)){ $info=array(); }

if(is_array($lead) && count($lead)>0){
  $data=vxcf_form::get_data_object();
  $form_id=$type.'_'.$form['id'];
  $main=array('form_id'=>$form_id);

  $forms=vxcf_form::get_forms(); //var_dump($form_id,$forms,$type,$form['id']); die('----------');
  if(!isset($forms[$type]['forms'][$form['id']]) ){
      return;
  }
  $meta=get_option(vxcf_form::$id.'_meta',array());

  if(empty($meta['ip'])){
  $main=$this->get_lead_info($main,$info);
  }else{
   $url_temp=$this->get_lead_info(array());
   if(!empty($url_temp['url'])){
    $main['url']=$url_temp['url'];   
   }   
  }
  if(!empty(self::$user_id)){
    $main['user_id']=self::$user_id;
}
 $fields=vxcf_form::get_form_fields($form_id);  
 
if(!empty($fields)){
foreach($lead  as $k=>$v){
    $type=isset($fields[$k]['type']) ? $fields[$k]['type'] :'';
    if( in_array($type,array('textarea'))){
    $lead[$k]=sanitize_textarea_field($v);   
    }else if(!in_array($type,array('file','mfile'))){
  $lead[$k]=$this->post($k,$lead);      
    }    
}
}
  $main=apply_filters('vxcf_entries_plugin_before_saving_lead_main',$main,$lead,$entry_id);
//var_dump($main); die();
  //set self::$form_fields_temp

$lead=apply_filters('vxcf_entries_plugin_before_saving_lead',$lead,$main); 
$vis_id=''; 
if($save){
if(empty($meta['cookies']) && empty($entry_id)){
$vis_id=$this->vx_id();
$entry_id=$data->get_vis_info_of_day($vis_id,$form_id,'1');
} 
$main['type']='0'; $main['is_read']='0';
$entry_id=$this->create_update_lead($lead,$main,$entry_id);

}

/*
  //  var_dump($detail,$lead,$entry_id); die();
 $forms_arr=get_option('vxcf_all_forms',array()); 
if(!isset($forms_arr[$type]['label'])){
 $forms_arr[$type]['label']=$forms[$type]['label'];   
}
//if stored form does not matches new form
if(!isset($forms_arr[$type]['forms'][$form['id']]) || $forms[$type]['forms'][$form['id']] != $forms_arr[$type]['forms'][$form['id']] ){
 $forms_arr[$type]['forms'][$form['id']]=$forms[$type]['forms'][$form['id']];         
update_option('vxcf_all_forms',$forms_arr,false); 
}
//update form fields
$forms_fields=get_option('vxcf_all_fields',array()); 
 if( (!empty($forms_fields[$type]['fields'][$form['id']]) && !empty(self::$form_fields_temp[$form_id]) && $forms_fields[$type]['fields'][$form['id']] == self::$form_fields_temp[$form_id]) == false){
     
$forms_fields[$type]['fields'][$form['id']]=self::$form_fields_temp[$form_id];      
update_option('vxcf_all_fields',$forms_fields,false);     
}
*/
$main['id']=$entry_id;
$lead['__vx_entry']=$main;
if($this->do_actions()){
do_action('vx_addons_save_entry',$entry_id,$lead,'cf',$form);
}
$lead=apply_filters('vxcf_after_saving_addons',$lead,$entry_id,$type,$form);
/*$upload=vxcf_form::get_upload_dir();
foreach($lead as $k=>$v){
 if(isset($form['fields'][$k]['type']) && $form['fields'][$k]['type'] == 'file' && !empty($v)){
   if(is_string($v)){ $v=array($v); }
   $files=array();
   foreach($v as $f){
 if(filter_var($f,FILTER_VALIDATE_URL) === false){
    $base_url=$upload['url'];       
  $f=$base_url.$f;     
    }
   $files[]=$f;    
   }
$lead[$k]=$files;     
 }   
}*/
//var_dump($lead);

$form['form_id']=$form['id']=$form_id; 
do_action('vxcf_entry_created',$lead,$entry_id,$form);
}
return $entry_id;
} 
public function create_update_lead($detail,$lead,$entry_id=''){
    $data=vxcf_form::get_data_object();
  if(empty($entry_id)){ //no partial entry
$entry_id=$data->update_lead('',$detail,'',$lead);  

}else{
$detail_db= $data->get_lead_detail($entry_id);
$update=$insert=array();
if(!empty($detail)){
    foreach($detail as $k=>$v){
   if(isset($detail_db[$k]['value'])){
if($detail_db[$k]['value'] != $v){
  $update[$detail_db[$k]['id']]=$v;  
}       
   }else{
  $insert[$k]=$v;      
   }     
} }  
$data->update_lead($update,$insert,$entry_id,$lead);
}

return $entry_id;
}
public static function update_entry_meta($entry_id,$meta_key,$meta){
if(!empty($entry_id) && !empty($meta) && is_array($meta)){
 $entry=vxcf_form::get_entry($entry_id);
 $detail=!empty($entry['meta']) ? json_decode($entry['meta'],true) : array();
 $data=vxcf_form::get_data_object();
 if(!empty($detail[$meta_key]) && is_array($detail[$meta_key])){
     $meta=array_merge($detail[$meta_key],$meta);
 }
 $detail[$meta_key]=$meta;
 $data->update_lead('','',$entry_id,array('meta'=>json_encode($detail)));
}
}
public function get_lead_info($info,$meta_info=array()){

$info['user_id']=get_current_user_id();
if(!empty($meta_info['ip'])){
$ip=$meta_info['ip'];
}else{
$ip=$this->get_ip();   
}
$info['ip']=substr(vxcf_form::clean($ip),0,50);
$resolution="";
if(isset($_POST['vx_width'])){
$width=vxcf_form::post('vx_width');
$height=vxcf_form::post('vx_height');
 $resolution=$width." x ".$height;
$info['screen']=$resolution;
}
$user_agent=!empty($meta_info['user_agent']) ? $meta_info['user_agent'] : '';
$bro_info=self::browser_info($user_agent); 
//get page url
$page_url="//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_REQUEST['vx_url'])){
$page_url=vxcf_form::post('vx_url');
}
if(!empty($meta_info['url'])){
 $page_url=vxcf_form::clean($meta_info['url']);  
}
$page_url=substr($page_url,0,250);
$info['url']=vxcf_form::clean($page_url); 
$info['browser']=substr(vxcf_form::clean($bro_info['name']),0,50);
$info['os']=substr(vxcf_form::clean($bro_info['platform']),0,50);
if(!empty($meta_info['vis_id'])){
$info['vis_id']=$meta_info['vis_id'];
}else{
$info['vis_id']=$this->vx_id();
}
$info['vis_id']=vxcf_form::clean($info['vis_id']);
return $info;
}
public function create_entry_vf($entry_id,$entry,$form){
//$track=$this->track_form_entry('vf');
$track= empty($form['settings']['disable_db']);

return $this->create_entry($entry,$form,'vf','',$track,$entry_id);
} 
public function create_entry_wp($fields, $entry, $form_id, $form_data){
$track=$this->track_form_entry('wp',$form_id);

$upload_files=$lead=array();
if(!empty($fields)){
    foreach($fields as $v){
if($v['type'] == 'file-upload'){
  $upload_files[$v['id']]=$v['value'];  
}else{
$val=$v['value'];
if(in_array($v['type'],array('payment-select','payment-multiple'))){
 $val=$v['amount'];   
}else if($v['type'] == 'checkbox'){
  $val=array_map('trim',explode("\n",$val));     
}
$lead[$v['id']]=$val;
}    } 
if($track){
  $upload_files=$this->copy_files($upload_files); 
}  
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } }
          
$form_arr=array('id'=>$form_data['id'],'name'=>'WP Forms','fields'=>$form_data['fields']);
if(!empty($form_data['fields']['settings']['form_title'])){
    $form_arr['name']=$form_data['fields']['settings']['form_title'];
}
$this->create_entry($lead,$form_arr,'wp','',$track); 
}
//var_dump($fields); die();
}
public function create_entry_el( $record){
    if(empty(self::$is_pr)){ return; }
    $data=$record->get_formatted_data();
    $form_id_p=$this->post('form_id');
    $post_id_p=$this->post('post_id');
    
    $form_id=$form_id_p.'_'.$post_id_p;
    $track=$this->track_form_entry('el',$form_id);
    $fields=self::get_form_fields('el_'.$form_id);
$upload_files=$lead=array();
if(!empty($fields)){
    foreach($fields as $v){
    if(isset($data[$v['label']])){    
$val=$data[$v['label']];
if($v['type'] == 'upload'){
  $upload_files[$v['id']]=$val;  
}else{

 if(in_array($v['type'],array('checkbox','multiselect'))){
  $val=array_map('trim',explode(',',$val));     
}
$lead[$v['id']]=$val;
}    } }
if($track){
  $upload_files=$this->copy_files($upload_files); 
}  
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } }
 //var_dump($lead,$data);  die();        
$form_arr=array('id'=>$form_id,'name'=>'Elementor Forms','fields'=>$fields);

$all_forms=get_option('vxcf_all_forms',array());
if(!isset($all_forms['el'])){
 $all_forms['el']=array('label'=>'Elementor Forms','forms'=>array());
}

 if(!isset($all_forms['el']['forms'][$form_id])){
   $all_forms['el']['forms'][$form_id]='Post '.$post_id_p.' Form #'.$form_id_p;  
 update_option('vxcf_all_forms',$all_forms);
 } 

$this->create_entry($lead,$form_arr,'el','',$track); 

}
//var_dump($fields); die();
}
public function create_entry_wc($id,$posted){
   $track=$this->track_form_entry('wc','1');

$tags=vxcf_form::get_form_fields('wc_1'); 
  $order=get_post_meta($id);
  $_order=new WC_Order($id);

  $order['qty']=$_order->get_item_count();
  $order['order_note']=$_order->customer_note;
  $order['order_id']=$id;
 $lead=array();
if(!empty($tags)){
  foreach($tags as $v){ 
  if(isset($order[$v['id']])){ 
  $k=$v['id']; $val=$order[$k];        
  $lead[$k]=is_array($val) && isset($val[0]) ? $val[0] : $val;
  }          
  }  
}
//var_dump($lead,$order,$tags); die();
$form_arr=array('id'=>'1','name'=>'WooCommerce','fields'=>$tags);
$this->create_entry($lead,$form_arr,'wc','',$track); 

}
public function create_entry_cf($form){ 

$form_id=$form->id();
$track=$this->track_form_entry('cf',$form_id);

$submission = WPCF7_Submission::get_instance();      
$uploaded_files = $submission->uploaded_files();

if($track){
$uploaded_files=$this->copy_files($uploaded_files);
}
$form_title=$form->title();
$tags=vxcf_form::get_form_fields('cf_'.$form_id); 


 $lead=array();
if(is_array($tags)){
  foreach($tags as $k=>$v){
      $name=$v['name'];
     
$val=$submission->get_posted_data($name);
 if(isset($uploaded_files[$name])){
  $val=$uploaded_files[$name];
   }
   if( !empty($val) && isset($v['basetype']) && $v['basetype'] == 'mfile' && function_exists('dnd_get_upload_dir') ){
      $dir=dnd_get_upload_dir(); 
     $f_arr=array();
      foreach($val as $file){
     $file_name=explode('/',$file);
     if(count($file_name)>1){
      $f_arr[]=$dir['upload_url'].'/'.$file_name[1];    
     }
      }
        
   $val=$f_arr;   
   }  
    if(!isset($uploaded_files[$name])){
     $val=wp_unslash($val);   
    }        
  $lead[$k]=$val;          
  }  
}

$form_arr=array('id'=>$form_id,'name'=>$form_title,'fields'=>$tags);
$this->create_entry($lead,$form_arr,'cf','',$track);

}
public function create_entry_na($data){ 

    $form_id=$data['form_id'];
    $track=$this->track_form_entry('na',$form_id);
    
if(empty($data['form_id'])){
    return;
}

$form_title=$data['settings']['title'];
$lead=$upload_files=array();
if(!empty($data['fields'])){
  foreach($data['fields'] as $v){
      $field_id=$v['id'];
     if(!empty($v['value'])){
         if($v['type'] == 'file_upload'){
        $upload_files[$field_id]=$v['value'];     
         }else{
         $lead[$field_id]=$v['value']; 
         }
     } 
  }
if($track){
$upload_files=$this->copy_files($upload_files); 
} 
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } 
       }  
$form_arr=array('id'=>$form_id,'name'=>$form_title,'fields'=>$data['fields']);
$this->create_entry($lead,$form_arr,'na','',$track);  
    
}
}
public function create_entry_qu($form){
$form_id=$form->getId(); 
    $track=$this->track_form_entry('qu',$form_id);
      if(empty($form)){
            return;
        }
             
           $vals= $form->getValues(); 
     $fields=vxcf_form::get_form_fields('qu_'.$form_id);

   $lead=$upload_files=array();
    $field_text='iphorm_'.$form_id.'_';    
    if(is_array($fields) && count($fields)>0){
     foreach($fields as $field){

           
      if(isset($field['id']) && !empty($vals[$field_text.$field['id']])){
          $type=$field['type'];
          $id=$field['id'];
          $val=$vals[$field_text.$field['id']];
          $files=array();
          if($type == 'file'){
            if(is_array($val) && count($val)>0){
             foreach($val as $file){
                 if(isset($file['fullPath'])){
             $files[]=$file['fullPath'];    
                 }
             }   
            }
         $upload_files[$id]=$files;  
          }else{
     $lead[$id]=$val;
          }     
      }   
     }
     if($track){
          $upload_files=$this->copy_files($upload_files); 
     }
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } 
       }
     if(count($lead)>0){  
    $form_arr=array('id'=>$form_id,'name'=>$form->getName(),'fields'=>$fields);
$this->create_entry($lead,$form_arr,'qu','',$track);
     }
    }


}
public function create_entry_ca($form){ 
    $form_id=$form['ID'];
    $track=$this->track_form_entry('ca',$form_id);

    global $processed_data;
 
      if(empty($form)){
            return;
        }
             
           $vals= $processed_data[$form_id]; 
           
     $fields=vxcf_form::get_form_fields('ca_'.$form_id);
//var_dump($fields,$vals); //die();
   $lead=array();
      $upload_files=array();
    if(is_array($fields) && count($fields)>0){
     foreach($fields as $field){

           
      if(isset($field['name']) && isset($vals[$field['name']])){
          $type=$field['type'];
          $id=$field['name'];
          $val=$vals[$field['name']];
          $files=array();
          if($type == 'file'){
            if(!is_array($val) && !empty($val)){
            $val=array($val);    
            }
          if(is_array($val) && count($val)>0){
     $upload_files[$id]=$val;
            }
          }
     $lead[$id]=$val;     
      }   
     }
     if($track){
     $upload_files=$this->copy_files($upload_files); 
     }
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       }  
   }  
     if(count($lead)>0){  
    $form_arr=array('id'=>$form_id,'name'=>$form['name'],'fields'=>$fields);
$this->create_entry($lead,$form_arr,'ca','',$track);
     }
    }

}
public function create_entry_be(){
     $track=$this->track_form_entry('be','1');

     global $cntctfrm_path_of_uploaded_file;
     $fields=vxcf_form::get_form_fields('be_');
    $lead=array();
    if(is_array($fields) && count($fields)>0){
        foreach($fields as $k=>$field){
          if(isset($_POST['cntctfrm_contact_'.$k])){
           $lead[$k]=vxcf_form::post('cntctfrm_contact_'.$k);   
          }else if($field['type'] == 'file' && !empty($cntctfrm_path_of_uploaded_file)){
              $files=array($k=>$cntctfrm_path_of_uploaded_file);
              if($track){
                $files=$this->copy_files($files );
              }
          if(isset($files[$k])){
          $lead[$k]=$files[$k];    
          }
          }
            
        }
    }
        if(count($lead)>0){  
    $form_arr=array('id'=>'','name'=>'BestSoft Contact Form','fields'=>$fields);
$this->create_entry($lead,$form_arr,'be','',$track);
     }  
}
public function create_entry_ul($to_email){

                  $entry=array();
     if(!empty($_POST['form_data'])){
         $form_data=vxcf_form::post('form_data');
         foreach($form_data as $k=>$v){
             $id=$v['name'];
             if(strpos($id,'[') !== false){
                 $id=substr($id,0,strlen($id)-2);
             }

             $value=$v['value'];
             if(isset($entry[$id])){
                       $value=$entry[$id];
                 if(!is_array($value)){
                  $value=array($value);   
                 }
                 $value[]=$v['value'];
             }
            $entry[$id]=$value; 
         }
     }

      if(empty($entry['form_id'])){
        return;  
      }
      $track=$this->track_form_entry('ul',$entry['form_id']);     


             $form_id=$entry['form_id'];

     $fields=vxcf_form::get_form_fields('ul_'.$form_id);   
     $lead=array();
     if(is_array($entry) && count($entry)>0){
         foreach($entry as $k=>$v){
          
            if(isset($fields[$k])){     
             $lead[$k]=$v;  
            }else{
          //  echo $k.'<hr>';    
            }
           
         }
     }


 
     if(count($lead)>0){
     $form= UFBL_Model::get_form_detail($form_id);  
    $form_arr=array('id'=>$form_id,'name'=>$form['form_title'],'fields'=>$fields);
$this->create_entry($lead,$form_arr,'ul','',$track);
     }
    


}

public function create_entry_c2($data){ 
if(empty($data)){ return; }
$form_id=$data['id'];
$track=$this->track_form_entry('c2',$form_id);
if($track === false){
    return;
}
 $entry= $data['data']; 
 $fields=vxcf_form::get_form_fields('c2_'.$form_id);   
   $vals=array();
     if(is_array($entry) && count($entry)>0){
         foreach($entry as $k=>$v){
          
            if(strpos($k,'$$$') === 0 && isset($entry[$v])){    
             $k=substr($k,3);   
             $vals[$k]=$entry[$v];  
            }
           
         }
     }

   $lead=array();
      $upload_files=array();
    if(is_array($fields) && count($fields)>0){
     foreach($fields as $field){

           
      if(isset($field['name']) && isset($vals[$field['name']])){
          $type=$field['type'];
          $id=$field['name'];
          $val=$vals[$field['name']];
          $files=array();

          if($type == 'file'){
        //   $settings = get_option('cforms_settings');  //cforms_upload_dir

         //  if(!empty($settings['form'.$form_id]['cforms_upload_dir'])){
         //    $upload_dir=explode('$#$',$settings['form'.$form_id]['cforms_upload_dir']); 
         //    $val=trim($upload_dir[0],'/').'/'.$val;
              
           //}
             
      $upload_files[]=array('id'=>$id,'val'=>$val);

          
          }else{
              if(isset($field['values'])){
              $val=explode(',',$val);    
              }
     $lead[$id]=$val;
          }     
      }   
     }
     $files=array();
    if(is_array($upload_files) && isset($data['uploaded_files']) && is_array($data['uploaded_files'])){
       foreach($upload_files as $k=>$v){
        if(isset($data['uploaded_files'][$k]['name'])){
      $files[$v['id']]=$data['uploaded_files'][$k]['name'];      
        }
       }  
   }
     $uploaded_files=$this->copy_files($files); 
       if(is_array($uploaded_files) && count($uploaded_files)>0){
       foreach($uploaded_files as $k=>$v){
       $lead[$k]=$v;    
       }  
   }  
     if(count($lead)>0){  
    $form_arr=array('id'=>$form_id,'name'=>$form['name'],'fields'=>$fields);
$this->create_entry($lead,$form_arr,'c2');
     }
    }


}
public function create_entry_jp($post_i, $all_values, $extra_values){
    $post_id=get_the_ID(); 
    $track=$this->track_form_entry('jp',$post_id);


$title=get_the_title(); 
     $fields=vxcf_form::get_form_fields('jp_'.$post_id); 
                  if(!is_array($all_values)){
                      $all_values=array();
                  }
             /*     if(is_array($extra_values)){
       $all_values=array_merge($all_values,$extra_values);               
                  } */
                  $lead=array();
              if(count($all_values)>0){
               foreach($all_values as $k=>$v){
                $k=explode('_',$k);
                   
                if(isset($fields[$k[1]])){  

                 $lead[$k[1]]=$v;   
                }   
               }
                         if(count($lead)>0){  
    $form_arr=array('id'=>$post_id,'name'=>$title,'fields'=>$fields);
$this->create_entry($lead,$form_arr,'jp','',$track);
     }   
              }    

        //    var_dump($fields,$post_id, $all_values, $lead); die();

      if(empty($data)){
            return;
        }
             $form_id=$data['id'];
           $entry= $data['data']; 
     $fields=vxcf_form::get_form_fields('c2_'.$form_id);   
   $vals=array();
     if(is_array($entry) && count($entry)>0){
         foreach($entry as $k=>$v){
          
            if(strpos($k,'$$$') === 0 && isset($entry[$v])){    
             $k=substr($k,3);   
             $vals[$k]=$entry[$v];  
            }
           
         }

     }

   $lead=array();
      $upload_files=array();
    if(is_array($fields) && count($fields)>0){
     foreach($fields as $field){

           
      if(isset($field['name']) && isset($vals[$field['name']])){
          $type=$field['type'];
          $id=$field['name'];
          $val=$vals[$field['name']];
          $files=array();

          if($type == 'file'){
        //   $settings = get_option('cforms_settings');  //cforms_upload_dir

         //  if(!empty($settings['form'.$form_id]['cforms_upload_dir'])){
         //    $upload_dir=explode('$#$',$settings['form'.$form_id]['cforms_upload_dir']); 
         //    $val=trim($upload_dir[0],'/').'/'.$val;
              
           //}
             
      $upload_files[]=array('id'=>$id,'val'=>$val);

          
          }else{
              if(isset($field['values'])){
              $val=explode(',',$val);    
              }
     $lead[$id]=$val;
          }     
      }   
     }
     $files=array();
    if(is_array($upload_files) && isset($data['uploaded_files']) && is_array($data['uploaded_files'])){
       foreach($upload_files as $k=>$v){
        if(isset($data['uploaded_files'][$k]['name'])){
      $files[$v['id']]=$data['uploaded_files'][$k]['name'];      
        }
       }  
   }
     $uploaded_files=$this->copy_files($files); 
       if(is_array($uploaded_files) && count($uploaded_files)>0){
       foreach($uploaded_files as $k=>$v){
       $lead[$k]=$v;    
       }  
   }  
     if(count($lead)>0){  
    $form_arr=array('id'=>$form_id,'name'=>$form['name'],'fields'=>$fields);
$this->create_entry($lead,$form_arr,'c2');
     }
    }


}
public function create_entry_fd($entry_id,$form_id){ 
$track=$this->track_form_entry('fd',$form_id);

$fields=vxcf_form::get_form_fields('fd_'.$form_id);    
global $wpdb;
$table=$wpdb->prefix.'frm_item_metas';
$sql=$wpdb->prepare("Select * from $table where item_id=%d",$entry_id);
$entry=$wpdb->get_results($sql,ARRAY_A);
 $detail=array();
if(is_array($entry) && count($entry)>0){
    foreach($entry as $v){
   $detail[$v['field_id']]=$v['meta_value'];     
    }
} 
//var_dump($tags); die();
 $lead=array();
if(is_array($fields)){
    $uploaded_files_form=array();
  foreach($fields as $k=>$v){
      
      $name=$v['name'];
     if(isset($detail[$name])){
         $val=$detail[$name];
     if($v['type'] == 'file'){
          $val= wp_get_attachment_url($val) ;
             $base_url=get_site_url();
              $val=str_replace($base_url,trim(ABSPATH,'/'),$val);
    $uploaded_files_form[$name]=$val;   
     }     
  $lead[$name]=$detail[$name];          
     }
  }  
//
if($track){
  $uploaded_files_form=$this->copy_files($uploaded_files_form);
}
   if(is_array($uploaded_files_form)){
       foreach($uploaded_files_form as $k=>$v){
       $lead[$k]=$v;    
       }  
   } 
}
global $wpdb;
$table=$wpdb->prefix.'frm_forms';
$sql=$wpdb->prepare("Select name from $table where id=%d",$form_id);
$form_name=$wpdb->get_var($sql);
$form_arr=array('id'=>$form_id,'name'=>$form_name,'fields'=>$fields);
$this->create_entry($lead,$form_arr,'fd','',$track);

}
public function create_entry_gf($entry,$form){ 
$track=$this->track_form_entry('gf',$form['id']);

$fields=vxcf_form::get_form_fields('gf_'.$form['id']);        
$uploaded_files_form =$lead=array();
if( is_array($fields)){
foreach($fields as $field){
              $id=$field['id']; 
                $is_name=false;
                if(isset($field['type']) && in_array($field['type'],array('name','address'))){
                $id=(string)$id; $is_name=true;
              }  
if(isset($entry[$id])){   
                  $val=$entry[$id];
          if(isset($field['type']) ){
              if($field['type'] == 'file'){  
$file_arr=json_decode($val,true);
if(is_array($file_arr)){
 $val=$file_arr;   
}
    $uploaded_files_form[$id]=$val;        
              }else   if(in_array($field['type'],array('radio','multiselect'))){
                $val=explode(',',$val);  
              }
          }
          if(!empty($val)){
      $lead[$id]=$val;     
          }   
}else if(!$is_name){
// This is for checkboxes
  $elements = array();
  foreach($entry as $key => $value) {
      if(is_numeric($key) && floor($key) == floor($id) && !empty($value)) { 
          $elements[] = htmlspecialchars($value);
      }}
      $lead[$id]=$elements;    
} }
}
  

   if($track){
  $uploaded_files_form=$this->copy_files($uploaded_files_form);
   }
   if(is_array($uploaded_files_form)){
       foreach($uploaded_files_form as $k=>$v){
       $lead[$k]=$v;    
       }  
   }          

$form_arr=array('id'=>$form['id'],'name'=>$form['title'],'fields'=>$form['fields']);
$this->create_entry($lead,$form_arr,'gf','',$track);
//  var_dump($lead);   die();
}
public function create_entry_fscf($data){
    if(!isset($data->posted_data)){
      return ;  
    }
$form_id=$data->form_number; 
    $track=$this->track_form_entry('fs',$form_id);
if($track === false){
    return;
}

$form_title=$data->title;
$post=$data->posted_data;
$files=$data->uploaded_files;
//
$uploaded_files=$this->copy_files($files);

$fields=vxcf_form::get_form_fields('fs_'.$form_id);  

 $lead=array();
if(is_array($fields)){
  foreach($fields as $k=>$v){
      $name=$v['name'];
  $val='';     
 if(isset($uploaded_files[$name])){
  $val=$uploaded_files[$name];
   }else if(isset($_POST[$name])){
  $val=vxcf_form::post($name);
   }
  

 if(!empty($val)){           
  $lead[$name]=$val;
 }          
  }  
}

$form_arr=array('id'=>$form_id,'name'=>$form_title,'fields'=>$fields);
$this->create_entry($lead,$form_arr,'fs');


}
public function copy_files($uploaded_files_form){
    $uploaded_files=array();
            if(is_array($uploaded_files_form) && count($uploaded_files_form)>0){
$upload=self::get_upload_dir();
$upload_path=$upload['path'];
$folder=$upload['folder'];
        if($upload_path){
            foreach($uploaded_files_form as $k=>$file_arr){
                  if(empty($file_arr)){
                      continue;
                  }
                if(!is_array($file_arr)){
                 $file_arr=array($file_arr);   
                }
                $files=array();
                foreach($file_arr as $file){
                  $base_url=get_site_url();
                  if(strpos($file,$base_url) === 0){
                  $file=str_replace($base_url,trim(ABSPATH,'/'),$file);     
                  } 
                $file_name_arr=explode('/',$file);
               $file_name=$file_name_arr[count($file_name_arr)-1]; 
               $file_name=sanitize_file_name($file_name);
               $file_name = wp_unique_filename( $upload_path, $file_name );
            $dest=$upload_path.'/'.$file_name;
             
           $copy=copy($file,$dest);
           chmod($dest, 0644);
           $uploaded_file=$file;
           $path='';
           if($copy){
            $uploaded_file=$folder.'/'.$file_name;
            $files[]=$uploaded_file;    
           }
                }
            $uploaded_files[$k]=$files;
            }
        }
  }
  return $uploaded_files;
}
public function get_forms_jetpack(){
     return  get_posts( array(
            'numberposts' => -1,
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_type' => 'jetpack'
             ) );
}
public function get_meta(){
if(is_null(self::$meta)){
self::$meta=get_option(vxcf_form::$id.'_meta',array());
 }
return self::$meta;   
}
public function track_form_entry($type,$form_id){
$meta=$this->get_meta();
$res=true;
if(!empty($meta['save_forms']) && empty($meta['save_forms'][$type.'_'.$form_id])){
 $res=false; 
}else if(!empty($meta['disable_track']) && !empty($meta['disable_track'][$type.'_'.$form_id])){
 $res=false;   
}
return $res;
}
public function get_form_jetpack($id=''){
     return  get_post($id);
}
public function get_fields_jetpack($post){
 $text=$post->post_content;   
    $pattern = '/\[(\[?)(contact-field)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/';
preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
$fields=array();
if(is_array($matches) && count($matches)>0){
  foreach($matches as $m){
      if(isset($m[3])){ 
      $str=trim($m[3]);
   $fields[]=shortcode_parse_atts(trim($m[3]));     
     
      }
  }  
}
return $fields;
}
public function get_forms_fscf(){
         //fast secure form
    $global=get_option( 'fs_contact_global');
    $forms=array();
    if(isset($global['form_list'])){
        $forms=$global['form_list'];
    }
  return $forms;  
}
public function get_fields_fscf($form_id){
   $fields_arr=array();
    if(method_exists('FSCF_Util','get_form_options')){
$options=FSCF_Util::get_form_options($form_id, true); 
   if(isset($options['fields']) && is_array($options['fields'])){
       $fields=$options['fields'];
   foreach($fields as $field){
    $field['name']=$field['slug'];
  $fields_arr[]=$field;     
   }
   }
    }
    return $fields_arr;
}  
public function validate_crmperks_field($err_msg,$field_val,$field,$form){
if(empty($err_msg) && !empty($field_val) && !empty($field['dup_check']) && !empty($form['id'])){ 
      $data=self::get_data_object();
      $row=$data->search_lead_detail($field_val,'vf_'.$form['id']);   
      //varify no duplicate fields
   if(!empty($row)){
    if($field['valid_err_msg']!=""){ 
   $err_msg=str_replace(array("%field_value%"),array($field_val),$field['valid_err_msg']);
    }else{
   $err_msg=sprintf(__("%s Already Exists",'contact-form-entries'),$field_val);     
    }       
   }     
}

return $err_msg;  
}
public static function file_link($file_url,$base_url=''){
        if(filter_var($file_url,FILTER_VALIDATE_URL) === false){
            if(empty($base_url)){
$upload=vxcf_form::get_upload_dir();
    $base_url=$upload['url'];
            }   
  $file_url=$base_url.$file_url;     
    } 
     if(filter_var($file_url,FILTER_VALIDATE_URL)){
          $file_arr=explode('/',$file_url);
    $file_name=$file_arr[count($file_arr)-1];
$file_url="<div><a href='$file_url' target='_blank'>".$file_name."</a></div>";
     }
     return $file_url;
}
public function get_ip(){
    $ip='';
     if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
//$ip='103.255.6.72';
return $ip;
}
/**
  * all form fields + addon fields
  * 
  * @param mixed $form_id
  */
public function get_all_fields($form_id){
      if($this->fields ){
     return $this->fields;     
      }
$tags=vxcf_form::get_form_fields($form_id); 
if(is_array($tags)){
  foreach($tags as $id=>$tag){
   $fields[$id]=array('id'=>$id,'label'=>$tag);    
   }     
}  

  $this->fields=$fields=apply_filters('vx_mapping_standard_fields',array('gf'=>array("title"=>__('Contact Form Fields','contact-form-entries'),"fields"=>$fields)));
  ///var_dump($fields); die();
  return $fields;
  } 
/**
  * Create or edit crm feed page
  * 
  */
public function print_page(){  
if(!current_user_can(vxcf_form::$id."_edit_settings")){
        die();
}
  $id=vxcf_form::post('id'); 
  $form_id=vxcf_form::post('form_id'); 
  $ids=array();
  if(!empty($id)){
  $ids=explode(',',$id);    
  }
  
  self::$data=vxcf_form::get_data_object();
$msgs=array(); $is_valid=true;
$fields=$leads=array(); 

$include_notes=isset($_GET['notes']) && $_GET['notes'] == '1' ? true : false;
   
         if(is_array($ids) && count($ids)>0){
     foreach($ids as $id){
       $id=(int)$id;
       if(!empty($id)){      
         $entry=apply_filters('vxcf_entries_print_lead',self::$data->get_lead_detail($id),$id);   
        if(!empty($entry)){
         $lead=array();
         $lead['lead']=$entry;
          if($include_notes){     
           $lead['notes']=self::$data->get_lead_notes($id);
          }
      $leads[$id]=$lead;
        }
       }  
     }   
    }

   if(empty($leads)){
   _e('No Entry Found', 'contact-form-entries');  
   }

if(!empty(self::$form_fields)){
$fields=self::$form_fields;    
}else{
$fields=vxcf_form::get_form_fields($form_id); 
}

//var_dump($leads,$fields);die();                              
include_once(self::$path . "templates/print.php");
exit;  
}
/**
  * gravity forms field select options
  * 
  * @param mixed $form_id
  * @param mixed $selected_val
  */
public function form_fields_options($form_id,$selected_val=""){
  if($this->fields == null){
  $this->fields=$this->get_all_fields($form_id);
  } 
  $sel="<option value=''></option>";
  $fields=$this->fields; 
  if(is_array($fields)){
  foreach($fields as $key=>$fields_arr){
if(is_array($fields_arr['fields'])){
    $sel.="<optgroup label='".$fields_arr['title']."'>";
      foreach($fields_arr['fields'] as $k=>$v){
          $option_k=$k;
          $option_name=$v;

    $option_name=$v['label'];  

          $select="";
  if($selected_val == $option_k){
  $select='selected="selected"';

  }
  $sel.='<option value="'.$option_k.'" '.$select.'>'.$option_name.'</option>';    
  }    }
  }}  
  return $sel;    
  }   
/**
  * uninstall plugin
  * 
  */
public function uninstall(){
  //droping all tables
 require_once(self::$path . "includes/install.php"); 
  $install=new vxg_install_nimble();
    do_action('plugin_status_'.$install->id);
  $install->remove_data();
  $install->remove_roles();
  }

  /**
  * deactivate
  * 
  * @param mixed $action
  */
  public function deactivate($action="deactivate"){ 
  do_action('plugin_status_'.vxcf_form::$type,$action);
  }
  /**
  * activate plugin
  * 
  */
  public function activate(){ 
$this->plugin_api(true);
  $this->install_plugin();    
do_action('plugin_status_'.vxcf_form::$type,'activate');  
  }
/**
  * display admin notice
  * 
  * @param mixed $type
  * @param mixed $message
  * @param mixed $id
  */
public static function display_msg($type,$message,$id=""){
  //exp 
  global $wp_version;
  $ver=floatval($wp_version);
  if($type == "admin"){
  ?>
  <div class="error vx_notice below-h2 notice is-dismissible" data-id="<?php echo $id ?>"><p><span class="dashicons dashicons-megaphone"></span> <b><?php _e('Contact Form Entries Plugin','contact-form-entries') ?>. </b> <?php echo wp_kses_post($message);?> </p>
  </div>    
  <?php
  }else{
  ?>
  <tr class="plugin-update-tr"><td colspan="5" class="plugin-update">
  <style type="text/css"> .vx_msg a{color: #fff; text-decoration: underline;} .vx_msg a:hover{color: #eee} </style>
  <div style="background-color: rgba(224, 224, 224, 0.5);  padding: 9px; margin: 0px 10px 10px 28px "><div style="background-color: #d54d21; padding: 5px 10px; color: #fff" class="vx_msg"> <span class="dashicons dashicons-info"></span> <?php echo wp_kses_post($message) ?>
</div></div></td></tr>
  <?php
  }   
  }
 
public function do_actions(){
     if(!is_object(self::$plugin) ){ $this->plugin_api(); }
      if(is_object(self::$plugin) && method_exists(self::$plugin,'valid_addons')){
       return self::$plugin->valid_addons();  
      }
    
   return false;   
}

  
  
public static function get_upload_folder(){
      $folder=get_option('crm_perks_upload_folder','');  
      if(empty($folder)){
     $folder=uniqid().rand(999999,999999999).rand(9999,9999999);
     update_option('crm_perks_upload_folder', $folder);     
     }
     return self::$upload_folder.'/'.$folder;
}
public static function get_upload_dir(){
          $upload_dir=wp_upload_dir();
          $plugin_folder=self::get_upload_folder();
         $time = current_time( 'mysql' );
        $y    = substr( $time, 0, 4 );
        $m    = substr( $time, 5, 2 );
        $folder=$y.'/'.$m;
        $upload_path=$upload_dir['basedir'].'/'.$plugin_folder.'/'.$folder;
        if(!file_exists($upload_path)){
        $dir=wp_mkdir_p($upload_path);
        if(!$dir){$upload_path=''; }else{
$files=array($upload_path.'/index.html',$upload_dir['basedir'].'/'.$plugin_folder.'/'.$y.'/index.html');  
        foreach($files as $file_name){
          if ( ! file_exists( $file_name ) ) {
                if ( $file_handle = @fopen( $file_name, 'w' ) ) {
                    fwrite( $file_handle, '' );
                    fclose( $file_handle );
                }
            } }
        } 
        }
     return array('path'=>$upload_path,'folder'=>$folder,'folder_name'=>self::$upload_folder,'url'=>$upload_dir['baseurl'].'/'.$plugin_folder.'/','dir'=>$upload_dir['basedir'].'/'.$plugin_folder.'/','basedir'=>$upload_dir['basedir']);   
}
    
/**
  * Returns true if the current page is an Feed pages. Returns false if not
  * 
  * @param mixed $page
  */
public static function is_crm_page($page=""){
  if(empty($page)) {
  $page = vxcf_form::post("page");
  }
$tab= vxcf_form::post('tab');
if($page == vxcf_form::$id){
   if($tab=='entries'){
    return true;
}else if($tab == 'settings'){
  $ret=true;
  if(!empty($_GET['section']) && $_GET['section'] != 'entries_settings'){
   $ret=false;    
  }
return $ret;    
} }
  return false;
} 
public static function get_entry($lead_id){ 
  $data=self::get_data_object();
 return $data->get_lead($lead_id);   
}
public static function get_entry_detail($lead_id){
  $data=self::get_data_object();
 return $data->get_lead_detail($lead_id);   
}

public static function get_forms(){
      //    function submission($components, $contact_form, $mail)
    //prepare list of contact forms --
    /// *NOTE* CF7 changed how it stores forms at some point, support legacy?
 $all_forms_db=get_option('vxcf_all_forms',array()); //disable saving forms
 $all_forms=array(); 

 if(!is_array($all_forms)){
  $all_forms=array();
 }
    if(class_exists('WPCF7_ContactForm')){
    if( !function_exists('wpcf7_contact_forms') ) {
        $cf_forms = get_posts( array(
            'numberposts' => -1,
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_type' => 'wpcf7_contact_form' ) );
    }
    else {
        $forms = wpcf7_contact_forms();
        $cf_forms=array();
        if(count($forms)>0){
            foreach($forms as $k=>$f){
             $v=new stdClass();
               if( isset( $f->id ) ) {
                    $v->ID = $f->id;    // as serialized option data
                } 
                 if( isset( $f->title ) ) {
                    $v->post_title = $f->title;    // as serialized option data
                }   
            $cf_forms[]=$v;
            }
        }
    }

  $forms_arr=isset($all_forms['cf']['forms']) && is_array($all_forms['cf']['forms']) ? $all_forms['cf']['forms'] :  array(); //do not show deleted forms

    if(is_array($cf_forms) && count($cf_forms)>0){
        $forms_arr=array();
 foreach($cf_forms as $form){
     if(!empty($form->post_title)){
  $forms_arr[$form->ID]=$form->post_title;       
     }
 } 
        $all_forms['cf']=array('label'=>'Contact Form 7','forms'=>$forms_arr); 
    } 
 ///////   
    }
        if(class_exists('cfx_form')){

$forms =cfx_form::get_forms();
       // $forms = vx_form_admin_pages::get_forms();
        $forms_arr=array();
    
    if(is_array($forms) && count($forms)>0){
 foreach($forms as $form){
     if(!empty($form['id'])){
  $forms_arr[$form['id']]= !empty($form['name'] ) ? $form['name'] : '#'.$form['id'];       
     }
 }

        $all_forms['vf']=array('label'=>'CRM Perks Forms','forms'=>$forms_arr); 
    } 
 ///////   
    }
    
    if(!empty($all_forms_db['el'])){
        $all_forms['el']=$all_forms_db['el'];
    }
   if(class_exists('GFFormsModel')){
     $gf_forms=GFFormsModel::get_forms();
      $forms_arr=array();
    if(is_array($gf_forms) && count($gf_forms)>0){
 foreach($gf_forms as $form){
     if(!empty($form->title)){
  $forms_arr[$form->id]=$form->title;       
     }
 } 
        $all_forms['gf']=array('label'=>'Gravity Forms','forms'=>$forms_arr); 
    } 
    }
    //formidable
        if(class_exists('FrmForm')){
     $gf_forms=FrmForm::getAll(array('status'=>'published','is_template'=>'0'));  
      $forms_arr=isset($all_forms['fd']['forms']) && is_array($all_forms['fd']['forms']) ? $all_forms['fd']['forms'] :  array();
    if(is_array($gf_forms) && count($gf_forms)>0){
 foreach($gf_forms as $form){
     if(!empty($form->id)){
  $forms_arr[$form->id]=$form->name;       
     }
 } 
        $all_forms['fd']=array('label'=>'Formidable Forms','forms'=>$forms_arr); 
    } 
    }
     
        if(class_exists('siContactForm')){
              //fast secure form
    $global=get_option( 'fs_contact_global');
    $fs_forms=array();
    if(isset($global['form_list'])){
        $fs_forms=$global['form_list'];
    }
      $forms_arr=isset($all_forms['fs']['forms']) && is_array($all_forms['fs']['forms']) ? $all_forms['fs']['forms'] :  array();
    if(is_array($fs_forms) && count($fs_forms)>0){
 foreach($fs_forms as $k=>$v){
  $forms_arr[$k]=$v;       

 } 
        $all_forms['fs']=array('label'=>'Fast Secure Contact Forms','forms'=>$forms_arr); 
    } 
    }
   
            if(class_exists('Grunion_Contact_Form_Plugin')){
            global $wpdb;    
            $sql="Select * from {$wpdb->postmeta} where meta_key='_g_feedback_shortcode' limit 300";
            $posts=$wpdb->get_results($sql,ARRAY_A);

      $forms_arr=isset($all_forms['jp']['forms']) && is_array($all_forms['jp']['forms']) ? $all_forms['jp']['forms'] :  array();
    if(is_array($posts) && count($posts)>0){
 foreach($posts as $k=>$v){
     $title=get_the_title($v['post_id']);
     if(!empty($title)){
  $forms_arr[$v['post_id']]=$title;       
     }     

 } 
        $all_forms['jp']=array('label'=>'Jetpack Contact Forms','forms'=>$forms_arr); 
    } 
    }
           
                if(class_exists('Ninja_Forms') && method_exists(Ninja_Forms(),'form')){
//$forms = Ninja_Forms()->forms()->get_all();
 $forms_arr=isset($all_forms['na']['forms']) && is_array($all_forms['na']['forms']) ? $all_forms['na']['forms'] :  array();
  global $wpdb;
  $sql = "SELECT `id`, `title`, `created_at` FROM `{$wpdb->prefix}nf3_forms` ORDER BY `title`";
  $nf_forms = $wpdb->get_results($sql, ARRAY_A);    
        //  die();
//$nf_forms = nf_get_objects_by_type( 'form' );
  if(is_array($nf_forms) && count($nf_forms)>0){
    foreach($nf_forms as $form){
     if(!empty($form['id'])){
     // $title = Ninja_Forms()->form( $form['id'] )->get_setting( 'form_title' );
      $forms_arr[$form['id']]=$form['title'];   
     }   
    }
     $all_forms['na']=array('label'=>'Ninja Forms','forms'=>$forms_arr); 
  }
 
    }       
    
          if(function_exists('iphorm_get_all_forms')){

$nf_forms = iphorm_get_all_forms();
  $forms_arr=isset($all_forms['qu']['forms']) && is_array($all_forms['qu']['forms']) ? $all_forms['qu']['forms'] :  array();

  if(is_array($nf_forms) && count($nf_forms)>0){
                 foreach($nf_forms as $form){
     if(!empty($form['id'])){
      $forms_arr[$form['id']]=$form['name'];   
     }   
    }
     $all_forms['qu']=array('label'=>'Quform Forms','forms'=>$forms_arr); 
  }
 
    }     
    
         if(function_exists('cforms2_insert')){

 $settings = get_option('cforms_settings');  //cforms_upload_dir   
  $count=$settings['global']['cforms_formcount'];
  $forms_arr=isset($all_forms['c2']['forms']) && is_array($all_forms['c2']['forms']) ? $all_forms['c2']['forms'] :  array();
for ($i=1; $i<=$count; $i++){
    $j   = ( $i > 1 )?$i:'';

$forms_arr[$j]=stripslashes($settings['form'.$j]['cforms'.$j.'_fname']);
}

     $all_forms['c2']=array('label'=>'CForms2 Forms','forms'=>$forms_arr); 
 
    }    
          if(class_exists('Caldera_Forms_Forms')){

$nf_forms = Caldera_Forms_Forms::get_forms(true,true);
$forms_arr=isset($all_forms['ca']['forms']) && is_array($all_forms['ca']['forms']) ? $all_forms['ca']['forms'] :  array();

  if(is_array($nf_forms) && count($nf_forms)>0){
                 foreach($nf_forms as $form){
     if(!empty($form['ID'])){
      $forms_arr[$form['ID']]=$form['name'];   
     }   
    }
     $all_forms['ca']=array('label'=>'Caldera Forms','forms'=>$forms_arr); 
  }
 
    }
    if(class_exists('UFBL_Model')){
$forms_arr=isset($all_forms['ul']['forms']) && is_array($all_forms['ul']['forms']) ? $all_forms['ul']['forms'] :  array();
        $ul_forms=UFBL_Model::get_all_forms(); 
        if(is_array($ul_forms) && count($ul_forms)>0){
            foreach($ul_forms as $k=>$v){
                $forms_arr[$v->form_id]=$v->form_title;
            }
        }
     $all_forms['ul']=array('label'=>'Ultimate Contact Form Builder','forms'=>$forms_arr);
    }
    if(class_exists('Woocommerce')){ //disable woo
   //  $all_forms['wc']=array('label'=>'WooCommerce','forms'=>array('1'=>'Woocommerce'));
    }    
    if(function_exists('cntctfrm_settings')){
        
     $all_forms['be']=array('label'=>'BestSoft Contact Forms','forms'=>array(''=>'Default Contact Form'));     
    }
    
if(function_exists('wpforms') && method_exists(wpforms()->form,'get')){
$forms_arr=wpforms()->form->get( '' );
if(!empty($forms_arr)){
$forms=array();
foreach($forms_arr as $v){
    $forms[$v->ID]=$v->post_title;
}
$all_forms['wp']=array('label'=>'WP Forms','forms'=>$forms);
//$forms=json_decode($forms->post_content,true);
}
}
 
ksort($all_forms);   
return apply_filters('vx_entries_plugin_forms',$all_forms);
} 
public static function forms_list($forms){
     $forms_arr=array();
     foreach($forms as $k=>$v){
     if(in_array($k,array('vf'))){ continue; }
     if(!empty($v['forms'])){
   foreach($v['forms'] as $form_id=>$form_title){
     $forms_arr[$k.'_'.$form_id]=$v['label'].' - '.$form_title;    
   }       
     }
 }
 return $forms_arr;
}   
/**
  * form fields
  * 
  * @param mixed $form_id
  */
public static function get_form_fields($form_id){      
$form_arr=explode('_',$form_id);
$type=$id='';
$fields = array();
if(isset($form_arr[0])){
$type=$form_arr[0];
}
if(isset($form_arr[1])){
$id=$form_arr[1];
}

switch($type){
    case'cf':    
    if(method_exists('WPCF7_ShortcodeManager','get_instance') || method_exists('WPCF7_FormTagsManager','get_instance')){

         $form_text=get_post_meta($id,'_form',true); 
         
if(method_exists('WPCF7_FormTagsManager','get_instance')){
    $manager=WPCF7_FormTagsManager::get_instance(); 
$contents=$manager->scan($form_text); 
$tags=$manager->get_scanned_tags(); 

}else if(method_exists('WPCF7_ShortcodeManager','get_instance')){ //
 $manager = WPCF7_ShortcodeManager::get_instance();
$contents=$manager->do_shortcode($form_text);
$tags=$manager->get_scanned_tags();    
}
if(isset($_GET['vx_crm_key'])){
    
}
if(is_array($tags)){
  foreach($tags as $tag){
     if(is_object($tag)){ $tag=(array)$tag; }
     
   if(!empty($tag['name'])){
 
       $id=str_replace(' ','',$tag['name']);
       $field=array('name'=>$id);
       $field['label']=ucwords(str_replace(array('-','_')," ",$tag['name']));
       $field['type_']=$tag['type'];
       $field['type']=$tag['basetype'];
       $field['req']=strpos($tag['type'],'*') !==false ? 'true' : '';
       
        if($field['type'] == 'select' && !empty($tag['options']) && array_search('multiple',$tag['options'])!== false){
          $field['type']='multiselect'; 
       }
       if(!empty($tag['raw_values'])){
          $ops=array();
           foreach($tag['raw_values'] as $v){
               if(strpos($v,'|') !== false){
                $v_arr=explode('|',$v); 
                if(!isset($v_arr[1])){ $v_arr[1]=$v_arr[0]; }
                $ops[]=array('label'=>$v_arr[0],'value'=>$v_arr[1]);  
               }else{
               $ops[]=array('label'=>$v,'value'=>$v);      
               }
           }
         $field['values']=$ops;  
       }
   $fields[$id]=$field;    
   }   
  }  
}
    }
break;
case'fs':
    if(method_exists('FSCF_Util','get_form_options')){
$options=FSCF_Util::get_form_options($id, true); 
   if(isset($options['fields']) && is_array($options['fields'])){
       $fs_fields=$options['fields'];
   foreach($fs_fields as $field){
    $field['name']=$field['slug'];
    if($field['type'] == 'attachment'){
     $field['type']='file';   
    }else if($field['type'] == 'checkbox-multiple'){
     $field['type']='checkbox';   
    }else if($field['type'] == 'select-multiple'){
     $field['type']='multiselect';   
    }
    if(isset($field['options'])){
        $opts_array = explode("\n",$field['options']);
    $options_arr=array();  $i=0;
   foreach($opts_array as $k=>$v){
                       $i++;
       if($field['type'] == 'select' && preg_match('/^\[(.*)]$/', $v, $matches)){
          $v=$matches[1];  $i=0;
       }else if ( preg_match('/^(.*)(==)(.*)$/', $v, $matches) ) {
                 // is this key==value set? Just display the value
        $v = $matches[3];
   }
   ////////
 $options_arr[]=array('text'=>$v,'value'=>$i);     
   }
 $field['values']=$options_arr;  
    }
    
  $fields[]=$field;     
   }
   }
    }
break;
case'jp':
$text=get_post_meta($id,'_g_feedback_shortcode',true);
$pattern = '/\[(\[?)(contact-field)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/';
preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
if(is_array($matches) && count($matches)>0){
  foreach($matches as $m){
      if(isset($m[3])){ 
      $str=trim($m[3]);
      $field=shortcode_parse_atts(trim($m[3])); 
      
      $field['req']=$field['required'] == '1' ? 'true' : '';
      if(isset($field['type'])){
          
           $field['values']=array(array('text'=>'','value'=>'Yes')); 
         if($field['type'] == 'checkbox-multiple'){
         $field['type']='checkbox'; 
         }
      }
      if(!empty($field['options'])){
         $field['values']=explode(',',$field['options']); 
         }
         $field['name']=$field['label'];
   $fields[$field['label']]=$field;    
     
      }
  }  
}
break;
case'na':
if(class_exists('Ninja_Forms')){

$form_fields = Ninja_Forms()->form( $id )->get_fields();
foreach ($form_fields as $obj) {
$field=array();
if( is_object( $obj ) ) {
$field = $obj->get_settings();
$field['id']= $obj->get_id();
}

$arr=array('name'=>$field['id']);
 $type=$field['type']; 
 if($type == 'textbox'){ $type='text'; }
 if($type == 'starrating'){ $type='text'; }
 if($type == 'file_upload'){ $type='file'; }
 if(in_array($type,array('spam','confirm')) || !isset($field['required']) ){ continue; }
  if($type == 'checkbox'){
 $arr['values']=array(array('text'=>$field['label'],'value'=>'1'));     
 }
 if(in_array($type,array('listmultiselect','listcheckbox','listradio','listselect'))){
     $type=ltrim($type,'list');
     $vals=array();
   if(!empty($field['options'])){
    foreach($field['options'] as $v){
  $vals[]=array('text'=>$v['label'],'value'=>$v['value']);      
    }   
   }
$arr['values']=$vals;     
 }

 $arr['type']=$type;
 $arr['label']=$field['label'];
$arr['req']=!empty($field['required']) ? 'true' : 'false';
 $fields[$field['id']]=$arr; 
 }     
}   
break;
case'fd':
global $wpdb;
$table=$wpdb->prefix.'frm_fields';
$sql=$wpdb->prepare("Select * from $table where form_id=%d",$id);
$fields_arr=$wpdb->get_results($sql,ARRAY_A);
if(count($fields_arr)>0){
    foreach($fields_arr as $field){
        $field['label']=$field['name'];
        $field['name']=$field['id'];
        if(!empty($field['options'])){
           $field['values']=maybe_unserialize($field['options']); 
        }
        $fields[]=$field;
    }
}
break;
case'na_test':
    global  $ninja_forms_fields; var_dump($ninja_forms_fields); die();
    if(is_array($ninja_forms_fields) && count($ninja_forms_fields)>0){
    foreach($ninja_forms_fields as $field){
     //   $field['label']=$field['name'];
     //   $field['name']=$field['id'];
     $field['type']=trim($field['type'],'_');
        if(!empty($field['options'])){
           $field['values']=maybe_unserialize($field['options']); 
        }
        $fields[]=$field;
    }
}
break;
case'c2':
 $settings = get_option('cforms_settings');
  $count=$settings['global']['cforms_formcount'];
   $forms=array();
 for($i=1; $i<500; $i++){
     if(isset($settings['form'.$id]['cforms'.$id.'_count_field_'.$i])){
      $field_str=stripslashes($settings['form'.$id]['cforms'.$id.'_count_field_'.$i]);

              $field_stat = explode('$#$', $field_str);


        $field_name       = $field_stat[0];
        $field_type       = $field_stat[1];
        $field_required   = $field_stat[2] == '1' ? 'true' : '';
        $field=array('req'=>$field_required);
         
 if (  in_array($field_type,array('multiselectbox','selectbox','radiobuttons','checkbox','checkboxgroup','ccbox','emailtobox'))  ){
 $field_name_arr=explode('#',$field_name);           
   $field_name=$field_name_arr[0];
    unset($field_name_arr[0]);
  $options=array();
   if(count($field_name_arr)>0){
 
      foreach($field_name_arr as $v){
          $v=explode('|',$v);
      $option['value']=$option['label']=$v[0];
 
      if(isset($v[1]) && $field_type!='selectbox'){
      $option['value']=$v[1];    
      }
      $options[]=$option;      
      
      } 
   }  
$field['values']=$options;   
 }
 if (  in_array($field_type,array('checkbox','checkboxgroup'))  ){
 $field_type='checkbox';
 }else  if (  in_array($field_type,array('selectbox','ccbox','emailtobox'))  ){
 $field_type='select';
 }else  if (  in_array($field_type,array('multiselectbox'))  ){
 $field_type='multiselect';
 }else  if (  in_array($field_type,array('radiobuttons'))  ){
 $field_type='radio';
 }else  if (  in_array($field_type,array('upload'))  ){
 $field_type='file';
 } 
 if(!empty($field_name)){
       $field_name=explode('|',$field_name);
       $field_name =$field_name[0];
$field['label']=$field_name; 
$field['name']=$i; 
$field['type']=$field_type; 
$fields[$i]=$field; 
 } 
     }else{
         break;
     }
 }
break;
case'ca':
if(class_exists('Caldera_Forms')){
$field_types=Caldera_Forms::get_field_types();
    $form=get_option($id);
    if(isset($form['fields']) && is_array($form['fields']) && count($form['fields'])>0){
             foreach($form['fields'] as $field){
                   $type=$field['type'];
                   $field_id=$field['ID'];
                   if(isset($field_types[$type])){
                         if(!isset($form['fields'][$field_id]) || !isset($field_types[$form['fields'][$field_id]['type']])){
                continue;
            }

            if(isset($field_types[$form['fields'][$field_id]['type']]['setup']['not_supported'])){
                if(in_array('entry_list', $field_types[$form['fields'][$field_id]['type']]['setup']['not_supported'])){
                    continue;
                }
            }  
            if($type == 'paragraph'){
                $type='textarea';
            }else if($type == 'filtered_select2'){
                $type='select';
            }else if($type == 'advanced_file'){
                $type='file';
            }
                $req=false;
      if(isset($field['data']['required'])){
          $req=$field['data']['required'] == 1 ? 'true': 'false';
      }
     $field['req']=$req;
            if(isset($field['config']['option']) && is_array($field['config']['option'])){
                     $options=array();
                     foreach($field['config']['option'] as $k=>$v){
                        if($v['value'] == ''){
                         $v['value']=$v['label'];   
                        } 
                     $options[]=$v;
                     }
            $field['values']=$options;    
            }
            $field['type']=$type;
            $field['name']=$field_id;
$fields[$field_id]=$field;
                   }
             }
    }
}
break;
case'qu':
/*$form=iphorm_get_form(1);
$elems=$form->getElements();
foreach($elems as $k=>$v){
 var_dump($v);   
} */
if(function_exists('iphorm_get_form_config')){
  $form = iphorm_get_form_config($id);
if(isset($form['elements']) && is_array($form['elements'])){

    foreach($form['elements'] as $k=>$v){
      if(isset($v['save_to_database']) && $v['save_to_database'] == true){
          if(isset($v['options'])){
            $v['values']=$v['options'];  
          }
          $v['req']= isset($v['required']) && $v['required'] == true ? 'true' : 'false';
              $v['name']=$v['id'];    
          $fields[]=$v;   
      }          
       }
}
}
break;
case'be':
$be_fields=array('name'=>'Name','email'=>'Email','address'=>'Address','phone'=>'Phone Number','subject'=>'Subject','message'=>'Message','file'=>'Attachment');
$fields=array();
foreach($be_fields as $k=>$v){
    $type='text';
    if(in_array($k,array('subject','address'))){
    $type='textarea';    
    }else if($k == 'file'){
     $type='file';   
    }
  $fields[$k]=array('name'=>$k,'label'=>$v,'type'=>$type);  
}
break;
case'vxad':
 global $vxcf_crm;
  if(method_exists($vxcf_crm,'get_form_fields')){
 $fields=$vxcf_crm->get_form_fields(true);
  }
  

break;
case'el':

if(isset($form_arr[2])){
$post_id=$form_arr[2];
$forms=get_post_meta($post_id,'_elementor_data',true);
$forms=json_decode($forms,true);
if(!empty($forms)){
$form=self::find_el_form($forms,$id);   
$fields=array();
if(!empty($form['form_fields'])){
  foreach($form['form_fields'] as $tag){
   if(!empty($tag['custom_id']) && !in_array($tag['field_type'],array('html','step','honeypot','recaptcha','recaptcha_v3'))){
       $field=array('id'=>$tag['custom_id']);
       $field['name']=$tag['custom_id'];
       $field['label']=$tag['field_label'];
       $field['type']=$tag['field_type'];
       $field['req']=!empty($tag['required']) ? 'true' : '';
  if(!empty($tag['allow_multiple']) ){
  $field['type']='multiselect';   
  }
  if($field['type'] == 'acceptance'){
      $field['type']='checkbox';
  }
  if($field['type'] == 'upload'){
      $field['type']='file';
  }
if(!empty($tag['field_options'])){
$opts_array=explode("\n",$tag['field_options']);
$ops=array();
foreach($opts_array as $v){
$v_arr=explode('|',$v); 
if(!isset($v_arr[1])){ $v_arr[1]=$v_arr[0]; }
$ops[]=array('label'=>$v_arr[0],'value'=>$v_arr[1]);  
}
$field['values']=$ops;  
   }
   $fields[$tag['custom_id']]=$field;    
   }   
  }  
} 
}

}
break;
case'vxad':
 global $vxcf_crm;
  if(method_exists($vxcf_crm,'get_form_fields')){
 $fields=$vxcf_crm->get_form_fields(true);
  }
  

break;
case'vf':
  if(method_exists('cfx_form','get_form')){
$fields=array();
$form= cfx_form::get_form($id,true); 
if(!empty($form['fields'])){
  foreach($form['fields'] as $f_id=>$tag){
   if(!empty($tag['label'])){//var_dump($tag);
       $field=array('id'=>$f_id);
       $field['name']=$f_id;
       $field['label']=$tag['label'];
       $field['type']=$tag['type'];
       $field['req']=!empty($tag['required']) ? 'true' : '';
//$tag['field_val']=trim($tag['field_val']);
   if(!empty($tag['options'])){
$field['values']=$tag['options'];  
   }
   $fields[$f_id]=$field;    
   }   
  }  
} 
  }
break;
case'ul':
if(method_exists('UFBL_Model','get_form_detail')){
         $form= UFBL_Model::get_form_detail($id);
         if(!empty($form['form_detail'])){
         $ul_fields=maybe_unserialize($form['form_detail']);  //var_dump($ul_fields['field_data']); die();
         if(is_array($ul_fields['field_data']) && count($ul_fields['field_data'])>0){
             foreach($ul_fields['field_data'] as $k=>$field){
                 if(isset($field['error_message'])){
             $type=$field['field_type'];
              if($type == 'dropdown'){
                  $type='select';
                  if(isset($field['multiple']) && $field['multiple'] == '1'){
                  $type='multiselect';   
                  }
              }
             $field['type']=$type;    
             $field['name']=$k;    
             $field['label']=$field['field_label']; 
             $field['req']=isset($field['required']) && $field['required'] == '1' ? 'true' : ''; 
             if(isset($field['option'])){
                 $field['values']=$field['option'];
             }
           $fields[$k]=$field;      
                 }   
             }
         }
         }
}
break;
case'gf':
if(method_exists('RGFormsModel','get_form_meta')){
$form = RGFormsModel::get_form_meta($id);
///var_dump( $form['fields'] ); 
$fields=array();
if(isset($form['fields']) && is_array($form['fields']) && count($form['fields'])>0){
  foreach($form['fields'] as $field){ 
  $tag=array('id'=>$field->id,'name'=>$field->id.'','label'=>$field->label);
  $type=$field->type;
  if($type == 'fileupload'){
     $type='file';   
    }else if($type == 'text'){
     $type='textarea';   
    }else if($type == 'website'){
     $type='url';   
    }else if($type == 'phone'){
     $type='tel';   
    }else if($type == 'list'){
     $type='textarea';   
    }
     $tag['req']=$field->isRequired !==false ? 'true' : '';
     if(isset($field->choices)){
        $tag['values']=$field->choices; 
     }
    $tag['type']=$type; 
  if(in_array($type,array('name','address')) && isset($field->inputs) && count($field->inputs)>0){
          foreach($field->inputs as $k=>$v){
              if(isset($v['isHidden'])){
                  continue;
              }
              $v['name']=(string)$v['id'];
              $v['type']=$field['type'];
        if(isset($v['choices']) && is_array($v['choices']) && count($v['choices'])>0){
                            $v['type']='select';
                            $v['values']=$v['choices']; 
        }       
              $fields[]=$v;   
          }
}else{
   $fields[]=$tag;     
}
  
  }  
}
}
break;
case'wc':
  $json='{"billing_first_name":"First name","billing_last_name":"Last name","billing_company":"Company name","billing_country":"Country","billing_address_1":"Address","billing_address_2":"Address 2","billing_city":"Town \/ City","billing_state":"State \/ County","billing_postcode":"Postcode \/ ZIP","billing_phone":"Phone","billing_email":"Email address","shipping_first_name":"First name","shipping_last_name":"Last name","shipping_company":"Company name","shipping_country":"Country","shipping_address_1":"Address","shipping_address_2":"Address 2","shipping_city":"Town \/ City","shipping_state":"State \/ County","shipping_postcode":"Postcode \/ ZIP","order_note":"Order Note","order_id":"Order ID","_customer_user":"User ID","qty":"Quantity","_order_total":"Order Total"}';
  $arr=json_decode($json,true);
  $fields=array();
  foreach($arr as $k=>$v){
      $label='Shipping ';
      if(strpos($k,'billing') !== false){ $label='Billing '; }
      $field=array('id'=>'_'.$k,'name'=>'_'.$k,'label'=>$label.$v,'type'=>'text');
      if($k == 'billing_email'){
          $field['type']='email';
      }else if($k == 'billing_phone'){
          $field['type']='tel';
      }else if(in_array($k,array('billing_address_1','billing_address_2','shipping_address_1','shipping_address_2','order_note'))){
          $field['type']='textarea';
      }else if(in_array($k,array('billing_state','shipping_state'))){
          $field['type']='state';
      }else if(in_array($k,array('billing_country','shipping_country'))){
          $field['type']='country';
      }
 $fields[]=$field;     
  }

break;
case'wp':
if(function_exists('wpforms') && method_exists(wpforms()->form,'get')){
$forms_arr=wpforms()->form->get( $id ); 
if(!empty($forms_arr)){
$form=json_decode($forms_arr->post_content,true);
$fields=array();
foreach($form['fields'] as $v){
    $type=$v['type'];
    if($type == 'name'){ $type='text'; }
    if($type == 'payment-select'){ $type='select'; }
    if($type == 'payment-multiple'){ $type='radio'; }
    if($type == 'payment-single'){ $type='text'; }
    if($type == 'file-upload'){ $type='file'; }
    if($type == 'date-time'){ $type='date'; }
    if($type == 'address'){ $type='textarea'; }
    if($type == 'phone'){ $type='tel'; }

  //  if(in_array($type,array('text','textarea','email','number','hidden','select','checkbox','radio','url','password','tel','date','file','number-slider'))){
          $field=array('id'=>$v['id'],'name'=>$v['id'],'label'=>$v['label'],'type'=>$type); 
  $field['req']=!empty($v['required']) ? true : false; 
        if(in_array($type,array('radio','checkbox','select'))){
        $is_val=false;
        if(in_array($v['type'],array('payment-select','payment-multiple'))){ $is_val=true; }
    $choices=array();
    if(!empty($v['choices'])){
     foreach($v['choices'] as $c){
         $c_val=$is_val ? $c['value'] : $c['label'];
     $choices[]=array('text'=>$c['label'],'value'=>$c_val);    
     }   
    }   
  $field['values']=$choices;   
        }
        $fields[]=$field; 
  //  }
    
}
} } //var_dump($form['fields']);
break;
} 

//allow custom form fields
if(empty($fields)){
    $fields=apply_filters('vx_entries_plugin_form_fields',$fields,$id,$type);
}

if(empty($fields)){
    //try from stored option
     $option=get_option('vxcf_all_fields',array());

    if(!empty($option[$type]['fields'][$id]) && is_array($option[$type]['fields'][$id])){
    $fields=$option[$type]['fields'][$id];    
    }     
}

$fields_a=array();
if(is_array($fields) && count($fields)>0){
    foreach($fields as $k=>$v){
      if(isset($v['name']) && $v['name'] != ''){ 
          $v['_id']=$form_id.'-vxvx-'.preg_replace("/[^a-zA-Z0-9]+/", "", $v['name']);
      $fields_a[$v['name']]=$v;    
      }  
    }
}
$fields_b=apply_filters('vxcf_entries_plugin_fields', $fields_a,$form_id);

self::$form_fields_temp[$form_id]=$fields_b;

return $fields_b;
}
public static function check_option_value($options,$value){
    $arr=array();
  if(!is_array($value)){$value=array($value); }
 foreach($value as $v){
   $arr[$v]=$v;  
 }
 if(!empty($options)){
  foreach($value as $val){
      foreach($options as $option){
      if(is_array($option)){
        if(isset($option['text'])){
       $label=$option['text'];     
        }else if(isset($option['label'])){
       $label=$option['label'];     
        }
       if(isset($option['value']) && $option['value'] == $val){
       $arr[$val]=$label;    
       }   
      }    
      }
  }  }
return array_values($arr);  
} 

public static function get_entries($form_id,$per_page,$page='',$req=array()){
$data=array(); 
$data_obj=vxcf_form::get_data_object();
if(!empty($form_id)){
$data= $data_obj->get_entries($form_id,$per_page,$req);
if(!empty($data['result'])){
$data['result']=apply_filters('vxcf_entries_plugin_leads_table',$data['result'],$form_id,$page);
} }
return $data;
}
/**
  * admin_screen_message function.
  * 
  * @param mixed $message
  * @param mixed $level
  */
public static function screen_msg( $message, $level = 'updated') {
  echo '<div class="'. esc_attr( $level ) .' fade below-h2 notice is-dismissible"><p>';
  echo $message ;
  echo '</p></div>';
  }
/**
  * settings link
  * 
  * @param mixed $escaped
  */
public static function link_to_settings( $tab='entries' ) {
  $q=array('page'=>vxcf_form::$id);
  if(!empty($tab)){
   $q['tab']=$tab;   
  }
  $url = admin_url('admin.php?'.http_build_query($q));
  
  return  $url;
  }
public static function set_form_fields($form_id=''){
   
       if(empty(self::$form_fields)){
        self::$forms=$forms=vxcf_form::get_forms();
        if(empty($form_id)){
         $form_id=vxcf_form::post('form_id');   
        }
  self::$form_id=esc_sql($form_id);   
if(empty(self::$form_id) && !empty(self::$forms)){
      $form_key=key($forms);
      if(isset($forms[$form_key]['forms']) && is_array($forms[$form_key]['forms'])){
     $form_i=key($forms[$form_key]['forms']);     
      }
   self::$form_id=$form_key.'_'.$form_i;   
}   
          
if(!empty(self::$form_id)){
   $fields_arr=vxcf_form::get_form_fields(self::$form_id); 
$fields_arr['vxbrowser']=array('_id'=>self::$form_id.'-vxvx-vxbrowser','name'=>'vxbrowser','label'=>__('System','contact-form-entries'),'is_main'=>'true');  
$fields_arr['vxurl']=array('_id'=>self::$form_id.'-vxvx-vxurl','name'=>'vxurl','label'=>__('Source','contact-form-entries'),'is_main'=>'true');             
           
$fields_arr['vxscreen']=array('_id'=>self::$form_id.'-vxvx-vxscreen','name'=>'vxscreen','label'=>__('Screen','contact-form-entries'),'is_main'=>'true');             

$fields_arr['vxupdated']=array('_id'=>self::$form_id.'-vxvx-vxupdated','name'=>'vxupdated','label'=>__('Updated','contact-form-entries'),'hide_in_search'=>'true','is_main'=>'true');       

$fields_arr['vxcreated']=array('_id'=>self::$form_id.'-vxvx-vxcreated','name'=>'vxcreated','label'=>__('Created','contact-form-entries'),'hide_in_search'=>'true','is_main'=>'true'); 

  self::$form_fields=$fields_arr;
  }
       }
   
}
public function footer_js(){
?>
<script type="text/javascript">
window.addEventListener("load", function(event) {
jQuery(".cfx_form_main,.wpcf7-form,.wpforms-form,.gform_wrapper form").each(function(){
var form=jQuery(this); 
var screen_width=""; var screen_height="";
 if(screen_width == ""){
 if(screen){
   screen_width=screen.width;  
 }else{
     screen_width=jQuery(window).width();
 }    }  
  if(screen_height == ""){
 if(screen){
   screen_height=screen.height;  
 }else{
     screen_height=jQuery(window).height();
 }    }
form.append('<input type="hidden" name="vx_width" value="'+screen_width+'">');
form.append('<input type="hidden" name="vx_height" value="'+screen_height+'">');
form.append('<input type="hidden" name="vx_url" value="'+window.location.href+'">');  
}); 

});
</script> 
<?php
} 
/**
  * Send CURL Request
  * 
  * @param mixed $body
  * @param mixed $path
  * @param mixed $method
  */
public static function request($path="",$method='POST',$body="",$head=array()) {

        $args = array(
            'body' => $body,
            'headers'=> $head,
            'method' => strtoupper($method), // GET, POST, PUT, DELETE, etc.
            'sslverify' => false,
            'timeout' => 20,
        );

       $response = wp_remote_request($path, $args);
       $result=wp_remote_retrieve_body($response);
        return $result;
    }
/**
  * Get variable from array
  *  
  * @param mixed $key
  * @param mixed $arr
  */
public static function post($key, $arr="") {
  if($arr!=""){
  return isset($arr[$key])  ? self::clean($arr[$key]) : "";
  }
  return isset($_REQUEST[$key]) ? self::clean($_REQUEST[$key]) : "";
}
public static function clean($var){
    if ( is_array( $var ) ) {
        return array_map( array('self','clean'), $var );
    } else {
        return  sanitize_text_field(wp_unslash($var));
    }
}
  /**
  * Get WP Encryption key
  * @return string Encryption key
  */
  public static  function get_key(){
  $k='Wezj%+l-x.4fNzx%hJ]FORKT5Ay1w,iczS=DZrp~H+ve2@1YnS;;g?_VTTWX~-|t';
  if(defined('AUTH_KEY')){
  $k=AUTH_KEY;
  }
  return substr($k,0,30);        
  }
    /**
     * Parse User Agent to get Browser and OS
     * @param  string $u_agent (optional) User Agent
     * @return array Browser Informations
     */
public static function browser_info($u_agent=""){ 
    $bname = '';
    $platform = '';
    $version= ""; $ub='';
if($u_agent == "" && !empty($_SERVER['HTTP_USER_AGENT'])){
$u_agent=$_SERVER['HTTP_USER_AGENT'];
}
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
    ////further refine platform
     if (preg_match('/iphone/i', $u_agent)) {
                $platform    =   "iPhone";
            } else if (preg_match('/android/i', $u_agent)) {
                $platform    =   "Android";
            } else if (preg_match('/blackberry/i', $u_agent)) {
                $platform    =   "BlackBerry";
            } else if (preg_match('/webos/i', $u_agent)) {
                $platform    =   "Mobile";
            } else if (preg_match('/ipod/i', $u_agent)) {
                $platform    =   "iPod";
            } else if (preg_match('/ipad/i', $u_agent)) {
                $platform    =   "iPad";
            }
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
      elseif(preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    }
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    }  
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }  
    // see how many we have
    $i = count($matches['browser']);
    if ($i > 1) {
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else if ($i > 0){
        $version= $matches['version'][0];
    }  
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}  
    return array(
        'userAgent' => $u_agent,
        'full_name'      => $bname,
        'name'      => $ub,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

public static function download_csv($form_id,$req=array()){

header('Content-disposition: attachment; filename='.date("Y-m-d",current_time('timestamp')).'.csv');
header("Content-Transfer-Encoding: binary");

    $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2000 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
        header('Content-Type: text/html; charset=UTF-8');
        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
  $data=vxcf_form::get_entries($form_id,'all','',$req);
  $leads=$data['result'];
$meta=get_option(vxcf_form::$id.'_meta',array());
$sep=','; if(!empty($meta['sep'])){ $sep=trim($meta['sep']); }

$extra_keys=array('vxbrowser'=>'browser','vxurl'=>'url','vxscreen'=>'screen','vxcreated'=>'created','vxupdated'=>'updated');
  $fields=vxcf_form::$form_fields;
//echo json_encode($fields);  die();   echo json_encode($leads);
 $field_titles=array('#');
  if(is_array($fields)){
      foreach($fields as $field){
       $field_titles[]=$field['label'];   
      }
  }
 // $field_titles[]=__('Created','contact-form-entries');
 
  $fp = fopen('php://output', 'w');
 // fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
  fputcsv($fp, $field_titles,$sep);
  $sno=0;
  foreach($leads as $lead_row){
      $row=!empty($lead_row['detail']) ? $lead_row['detail'] : array();
  $sno++;
  $_row=array($sno);
  foreach($fields as $k=>$field){
      $val=''; 
  if(isset($field['name']) && isset($row[$field['name'].'_field'])){
      $val=maybe_unserialize($row[$field['name'].'_field']); 
  }
  if(isset($extra_keys[$k]) && isset($lead_row[$extra_keys[$k]])){
      if($k == 'vxbrowser'){
    $val=isset($lead_row['browser']) ?  $lead_row['browser'].' ' : '';     
    $val.=isset($lead_row['os']) ?  $lead_row['os'] : '';     
      }else{
   $val=$lead_row[$extra_keys[$k]];
      }   
  }
    if(is_array($val)){
      $val=implode(' - ',$val);    
      }
     /*  if(function_exists('mb_convert_encoding')){
       $val=mb_convert_encoding($val, 'UTF-8');
       } */
      $_row[]=$val; 
    
  }

  $_row[]=$lead_row['created'];

  fputcsv($fp, $_row,$sep);    
  }
  fclose($fp);

}
public static function find_el_form($var,$key=''){

if(is_array($var) && isset($var[0]) ){        
    foreach($var as $v){
     if (!empty($v['elements']) &&  is_array( $v['elements'] ) ) {
  $se=self::find_el_form($v['elements'],$key);
  if(!empty($se)){ return $se; }
    } 
         if($v['id'] == $key){  // var_dump($v);   echo '----<hr>';
          return  $v['settings'];
        } 
    }
    
} 
}
public function vx_id(){
      $vx_id='';
 if(!empty($_COOKIE['vx_user'])){
     $vx_id=$_COOKIE['vx_user'];
 }else{
     $vx_id=uniqid().time().rand(9,99999999);
   $_COOKIE['vx_user']=$vx_id;  
 setcookie('vx_user', $vx_id, time()+25920000, '/');   
 }

 return $vx_id;
}  
    /**
  * Get time Offset 
  * 
  */
  public static function time_offset(){
 $offset = (int) get_option('gmt_offset');
  return $offset*3600;
  } 
  /**
  * Get variable from array
  *  
  * @param mixed $key
  * @param mixed $key2
  * @param mixed $arr
  */
  public static function post2($key,$key2, $arr="") {
  if(is_array($arr)){
  return isset($arr[$key][$key2])  ? $arr[$key][$key2] : "";
  }
  return isset($_REQUEST[$key][$key2]) ? $_REQUEST[$key][$key2] : "";
  }
  /**
  * Get variable from array
  *  
  * @param mixed $key
  * @param mixed $key2
  * @param mixed $arr
  */
  public static function post3($key,$key2,$key3, $arr="") {
  if(is_array($arr)){
  return isset($arr[$key][$key2][$key3])  ? $arr[$key][$key2][$key3] : "";
  }
  return isset($_REQUEST[$key][$key2][$key3]) ? $_REQUEST[$key][$key2][$key3] : "";
  }
  /**
  * get base url
  * 
  */
  public static function  get_base_url(){
  return plugin_dir_url(__FILE__);
  }
    /**
  * get plugin direcotry name
  * 
  */
  public static function plugin_dir_name(){
  $path=self::get_base_path(); 
  return basename($path);
  }
  /**
  * get plugin slug
  *  
  */
  public static function  get_slug(){
  return plugin_basename(__FILE__);
  }

  /**
  * Returns the physical path of the plugin's root folder
  * 
  */
  public static function get_base_path(){
  return plugin_dir_path(__FILE__);
  }


    /**
  * get data object
  * 
  */
  public static function  get_data_object(){ 
  require_once(self::$path . "includes/data.php");     
  if(!is_object(self::$data))
  self::$data=new vxcf_form_data();
  return self::$data;
  }



}

endif;
$vxcf_form=new vxcf_form();
$vxcf_form->instance();
if(!isset($vx_cf)){ $vx_cf=array(); } 
$vx_cf['vxcf_form']='vxcf_form';

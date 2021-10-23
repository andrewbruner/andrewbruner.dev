<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_form_pages' ) ) {

/**
* Main class
*
* @since       1.0.0
*/
class vxcf_form_pages{
public $entry_title='Form Entries of';
public $entry_title_small='Entries';
public $entry_title_single='Entry';
public $data;
public static $tab='';
public static $related_leads;
/**
* initialize plugin hooks
*  
*/
public function __construct(){

$this->data=vxcf_form::get_data_object();

global $pagenow; 
  if(in_array($pagenow, array("admin-ajax.php"))){
add_action('wp_ajax_actions_'.vxcf_form::$id, array($this, 'ajax_actions')); 
add_action('wp_ajax_print_'.vxcf_form::$id, array($this, 'print_page')); 
  }
//update previous form's hidden fields
add_filter( 'update_user_metadata',array($this,'update_hidden_cols'), 10, 5 );
add_filter( 'set-screen-option', array($this,'set_per_page'), 10, 3 );  

add_action( 'admin_notices', array( $this, 'admin_notices' ) ); 
add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2); 

add_action( 'vx_cf_meta_boxes_right', array($this,'related_entries_cf'),10,3 );
add_action( 'vx_cf_add_meta_box_right', array($this,'add_meta_box'),9,2 );
  //creates the subnav left menu
add_filter("admin_init", array($this, 'setup_plugin'), 2);
add_filter("admin_menu", array($this, 'main_menu'), 27);
add_filter('vx_entries_plugin_tabs', array($this, 'create_menu'), 16);
add_filter('vx_entries_plugin_tab_sections', array($this, 'settings_section'), 10); 
//crmperks forms table (add entries and unread)
add_filter('crmperks_forms_table_fields', array($this, 'crmperks_forms_table_add_unread_field'), 40); 
add_filter('crmperks_forms_table_data', array($this, 'crmperks_forms_table_add_unread'), 40); 
 //add_action('template_redirect', array($this, 'setup_plugin'));
add_filter('crmperks_forms_fields_classes',array($this,'crmperks_forms_duplicate_field_class'),10,2);
add_action('crmperks_forms_field_html',array($this,'crmperks_forms_duplicate_field'),10,2);
add_action('crmperks_forms_step3_html',array($this,'crmperks_disable_entry_check'),40);
//form stats
add_action('crmperks_entries_stats_end',array($this,'add_forms_stats'),40);
add_action('wp_dashboard_setup', array($this,'add_forms_stats_dashboard'),30 );
//entries link in all forms
add_filter( 'crmperks_forms_row_actions', array($this,'entries_link'),40,2);
add_filter('wp_privacy_personal_data_exporters',array($this,'export_personal_data'));
add_filter('wp_privacy_personal_data_erasers',array($this,'remove_personal_data'));

}


public function mapping_page_settings(){
    
    $sections=apply_filters('vx_entries_plugin_tab_sections',array()); 
           $section=vxcf_form::post('section');
          $ul=array(); $no=0;  $link=admin_url('admin.php?page=vxcf_leads&tab=settings&section='); 
            foreach($sections as $k=>$v){
               if(isset($v['label'])){ 
               if(empty($section) && $no == 0){$section=$k;} $no++;
               $class=$section == $k ? 'current' : '';
                   $ul[]='<li><a href="'.$link.$k.'" class="'.$class.'">'.$v['label'].'</a>';
               } 
            }
           if(count($ul)>1){     
        ?>
       <div> <ul class="subsubsub">
        <?php echo implode(" | </li>",$ul); ?>
   </ul> <div class="clear"></div></div>
        <?php   
   
           }
                if(isset($sections[$section]['function'])){
         call_user_func($sections[$section]['function']);
     }
    
}  

public function related_entries_cf($boxes,$lead,$detail){ 
if(!empty($lead['vis_id'])){ 
$res=$this->data->get_related_leads($lead['vis_id'],$lead['id']);
$entries=array(); 
$entries=apply_filters('crmperks_related_leads',$entries,$lead);

$link=vxcf_form::link_to_settings('entries');
if(!empty($res)){
    foreach($res as $v){ 
   $entries['cf_'.$v['id']]=array('title'=>date('M d,Y @ H:i:s',strtotime($v['created'])),'link'=>$link.'&id='.$v['id'],'type'=>'cf','id'=>$v['id']);     
    }
}

if( !empty($entries) ) { 
    self::$related_leads=$entries;
 $boxes['related_leads']=array('title'=>'<i class="fa fa-user"></i> '.__('Entries by Same User', 'contact-form-entries'),'callback'=>array($this,'meta_box_html'));  
} 
}
return $boxes; 
}
public function meta_box_html($lead,$detail){
if(!empty(self::$related_leads)){
//echo '<p>'.__('The user who created this entry also submitted the entries below.','contact-form-entries').'</p>';
    echo '<ol>';
     foreach(self::$related_leads as $v){
  
   echo '<li>';
     
         if($v['type'] == 'vxa'){
        $id=$v['title'];     
         }else{
             $id='# '.$v['id'];
      echo    $v['title'];       
         }
   if(!empty($v['link'])){
  echo '<a href="'.$v['link'].'"> '.$id.'</a>';     
   }else{
    echo ' '.$id;   
   }
   echo '</li>';     
     }

echo '</ol>';
}
}
public function ajax_actions(){  
     check_ajax_referer("vx_crm_ajax","vx_crm_ajax"); 
      if(current_user_can(vxcf_form::$id."_edit_settings")){
        $action=vxcf_form::post('action2'); 
        if($action == 'toggle_star'){
          $status_p=vxcf_form::post('status');
          $id=(int)vxcf_form::post('id');
          $status='0';
          if($status_p == 1){ $status='1'; }
         $data=vxcf_form::get_data_object();
     
echo $data->lead_actions(array('is_star'=>$status),array($id));   
        }  
if($action == 'add_note'){

$id=(int)vxcf_form::post('entry_id');
$note=sanitize_textarea_field(wp_unslash($_POST['note']));
$color=(int)vxcf_form::post('note_color');
$data=vxcf_form::get_data_object();
$user= wp_get_current_user();
$note_arr=array('lead_id'=>$id,'note'=>$note,'color'=>$color,'created'=>current_time('mysql'),'user_id'=>$user->ID);

if(!empty($_REQUEST['note_email'])){
$note_arr['email']=vxcf_form::post('note_email');    
}
$note_id=(int)vxcf_form::post('id');
$note_id=$data->add_note($note_arr,$note_id);
do_action('vx_cf7_post_note_added',$note_id,$id,$note);
$note_arr['id']=$note_id;
$note_arr['display_name']=$user->data->display_name;
if($note_id){
$this->note_template($note_arr);
if(!empty($_REQUEST['note_email'])){
$subject=vxcf_form::post('note_subject');
$email_from=$user->user_email;
$from_name=$user->display_name;
//$headers = array('Content-Type: text/plain; charset=UTF-8');
$headers = "From: \"$from_name\" <$email_from> \r\n";
wp_mail(trim($_REQUEST['note_email']),$subject, $note,$headers);    
         }   
}      
}
       if($action == 'update_print_note'){
           update_option('print_note_'.vxcf_form::$id,vxcf_form::post('status'));
       }
       if($action == 'delete_note'){
          $id=(int)vxcf_form::post('id');
          $data=vxcf_form::get_data_object();
  
          $note=$data->get_note($id);
          
          if(!empty($note)){
          do_action('vx_cf7_pre_note_deleted',$note['id'],$note['lead_id']);

         $data=vxcf_form::get_data_object();
         $note_id=$data->delete_note($id);
          }      
}
 
      }
 die();
 }
/**
  * Create or edit crm feed page
  * 
  */
public  function print_page(){  
if(!current_user_can(vxcf_form::$id."_edit_settings")){
        die();
}
  $id=vxcf_form::post('id'); 
  $form_id=vxcf_form::post('form_id'); 
  $ids=array();
  if(!empty($id)){
  $ids=explode(',',$id);    
  }
  
  vxcf_form::$data=vxcf_form::get_data_object();
$msgs=array(); $is_valid=true;
$fields=$leads=array(); 

$include_notes=isset($_GET['notes']) && $_GET['notes'] == '1' ? true : false;
   
         if(is_array($ids) && count($ids)>0){
     foreach($ids as $id){
       $id=(int)$id;
       if(!empty($id)){      
         $entry=apply_filters('vxcf_entries_print_lead',vxcf_form::$data->get_lead_detail($id),$id);   
        if(!empty($entry)){
         $lead=array();
         $lead['lead']=$entry;
          if($include_notes){     
           $lead['notes']=vxcf_form::$data->get_lead_notes($id);
          }
      $leads[$id]=$lead;
        }
       }  
     }   
    }

   if(empty($leads)){
   _e('No Entry Found', 'contact-form-entries');  
   }

if(!empty(vxcf_form::$form_fields)){
$fields=vxcf_form::$form_fields;    
}else{
$fields=vxcf_form::get_form_fields($form_id); 
}

//var_dump($leads,$fields);die();                              
include_once(vxcf_form::$path . "templates/print.php");
exit;  
} 
public function note_template($note){
$note_color=''; 
 if($note['color'] == '1'){ $note_color='vx_note_green';  }       
 if($note['color'] == '2'){ $note_color='vx_note_red';  }       
?>
<div class="crm_note vx_note_temp <?php echo $note_color ?>" data-id="<?php echo $note['id'] ?>" data-color="<?php echo $note['color'] ?>">
<div class="crm_note_img" title="<?php echo $note['display_name'] ?>">
<?php echo get_avatar($note['user_id'], 60 ); ?>
</div>
<div class="crm_arrow_left">
<div class="crm_note_text"><?php echo nl2br($note['note']);?></div>
<a href="#" class="vx_edit_note_btn" title="<?php _e('Edit Note', 'contact-form-entries'); ?>"><i class="fa fa-pencil"></i></a>
<div class="key_info">
<span class="posted_by" title="<?php _e('Note created by', 'contact-form-entries'); ?>" ><i class="fa fa-user"></i> <span class="post_user"><?php echo $note['display_name']?></span></span>

<span class="post_time" title="<?php _e('Note created at', 'contact-form-entries'); ?>"><i class="fa fa-clock-o"></i> <span class="date_time"><?php echo date("d/M/y H:i:s",strtotime($note['created']))?></span></span>
<?php
if(!empty($note['email'])){
?>  
<span class="post_time" title="<?php _e('Email sent to', 'contact-form-entries'); ?>">
<i class="fa fa-envelope"></i> <?php echo $note['email']; ?></span>
<?php
}
?>   
  
<span class="del_note vx_del_link" data-id="<?php echo $note['id']?>"> <a href="#" title="<?php _e("Delete", 'contact-form-entries'); ?>" class="reg_ok"><?php _e("Delete", 'contact-form-entries'); ?> </a><span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> <?php _e("Deleting ...", 'contact-form-entries'); ?></span></span>
</div> </div>
<div class="crm_clear"></div>
</div>
<?php
}
   /**
* Add Customer information box
*   
*/
public function add_meta_box($lead,$detail){
if(self::$tab != 'entries'){ return; }
    $lead_id=isset($lead['id']) ? $lead['id'] : ""; 
    $form_id=isset($lead['form_id']) ? $lead['form_id'] : ""; 
    $admin_url=admin_url( 'admin-ajax.php' ).'?action=print_'.vxcf_form::$id;
if(isset($_GET['tab']) && $_GET['tab'] == 'contacts'){
    $print_link= $admin_url.'&id='.$lead_id;
}else{
    $print_link= $admin_url.'&id='.$lead_id.'&form_id='.$form_id;
}
include_once(vxcf_form::$path."templates/crm-entry-box.php");
 
}
   /**
  * Display custom notices
  * show salesforce response
  * 
  */
public function admin_notices(){
      
  if(isset($_GET[vxcf_form::$id."_logs"]) && current_user_can(vxcf_form::$id.'_read_settings')){
      $msg=__('Error While Clearing Entries Logs','contact-form-entries');
      $level="error";
      if(!empty($_GET[vxcf_form::$id."_logs"])){
      $msg=__('Entries Logs Cleared Successfully','contact-form-entries');   
      $level="updated";
      }
      vxcf_form::screen_msg($msg,$level);
  }
  if(isset($_REQUEST[vxcf_form::$id.'_msg'])){ //send to crm in order page message
  $msg=get_option(vxcf_form::$id.'_msg');
  update_option(vxcf_form::$id.'_msg','');
  if(isset($msg['class'])){
      vxcf_form::screen_msg($msg['msg'],$msg['class']);
  }
  }
  }
    /**
  * Add settings and support link
  * 
  * @param mixed $links
  * @param mixed $file
  */
public function plugin_action_links( $links, $file ) {
   $slug=vxcf_form::get_slug();
      if ( $file == $slug ) {
          $settings_link=vxcf_form::link_to_settings('settings');
            array_unshift( $links, '<a href="' .$settings_link. '&section=entries_settings">' . __('Settings', 'contact-form-entries') . '</a>' );
        }
        return $links;
   }
 public function settings_section($sections){ 
  $sections['entries_settings']=array('label'=>'Entries Settings','function'=>array($this, 'settings_page'));  
return $sections;
}
  /**
  * Creates left nav menu under Forms
  * 
  * @param mixed $menus
  */
  public  function create_menu($tabs){ 
       $tabs['entries']=array('label'=>'Entries','function'=>array($this, 'entries_mapping_page'));  
  $tabs['entries_stats']=array('label'=>__('Entries Stats','contact-form-entries'),'function'=>array($this,'entries_stats'));
     //  $tabs['view']=array('tab'=>'entries','function'=>array($this, 'view_page'));  
  $tabs['settings']=array('label'=>'Settings','function'=>array($this, 'mapping_page_settings'));  
return $tabs; 
  }
public function crmperks_forms_duplicate_field_class($classes,$form){
 if(is_array($classes)){
$classes['dup_div']=array("select","state","country","radio","checkbox"); //+input
 }
 return $classes;   
}
public function crmperks_forms_duplicate_field($id,$field){
?>
   <div class="cfx_field_dup_div cfx_field_input cfx_field_row" style="<?php if(in_array($field['type'],array('file','html','hr','star','range','hidden'))){echo 'display:none;';} ?>">
         <label class="crm_text_label" ><input type="checkbox" value="yes" class="valid_err_check" name="fields[<?php echo $id;?>][dup_check]" data-name="dup_check" <?php if(isset($field['dup_check']) && $field['dup_check'] == "yes") echo 'checked="checked"'?>> <?php _e('No Duplicates - ','contact-form-entries') ?>   </label>  <a href="javascript:void(0);" onclick="sf_colorbox('<?php _e('No Duplicate Fields Explanation','contact-form-entries') ?>','#sf_duplicate_fields_help');"><?php _e('Help','contact-form-entries') ?></a>
         
         
        <div class="valid_err_div" style="<?php if( empty($field['dup_check']) ){echo "display:none";} ?>">
        <label class="crm_text_label"><?php _e('No Duplicates Validation Message','contact-form-entries') ?></label>
           <div class="crm-panel-description">
           <?php _e('This message will be displayed to the visitor if duplicate found. You can use %field_value% to display the field value submitted by user.','contact-form-entries') ?></div>
      <input type="text" name="fields[<?php echo $id;?>][valid_err_msg]" data-name="valid_err_msg" placeholder="Enter No Duplicates Validation Error Message"  class="text" value="<?php echo $field['valid_err_msg'];?>" /></div> 
      </div>
<?php  
}  
public function crmperks_forms_table_add_unread($forms){
$data=vxcf_form::get_data_object();
$res=$data->get_leads_count_by_form();
$arr=array();
if(!empty($forms)){
foreach($forms as $k=>$v){
$form_id='vf_'.$v['id'];
if(isset($res['total'][$form_id])){
 $v['entries']=$res['total'][$form_id];   
} 
$u=0;
if(isset($res['unread'][$form_id])){
 $u=$res['unread'][$form_id];   
}
$v['unread']=$u; 
$arr[$k]=$v;   
}    
}
return $arr;
}
public function entries_link($links,$form){
    $del=$links['delete'];
    unset($links['delete']);
    $page_url=vxcf_form::link_to_settings();
    $links['entries']='<a href="'.$page_url.'&form_id=vf_'.$form['id'].'">'.__('Entries','contact-form-entries').'</a>';
    $links['delete']=$del;
    return $links;
}
public function add_forms_stats_dashboard(){
    if(current_user_can(vxcf_form::$id.'_read_settings')){
    wp_add_dashboard_widget('dashboard_widget', __('Contact Form Entries','contact-form-entries'), array($this,'dashboard_stats'));
    }
}
public function dashboard_stats(){
$this->forms_stats_table();
}
public function forms_stats_table(){
        $forms=vxcf_form::get_forms(); 
        $data=vxcf_form::get_data_object();
    $counts=$data->get_leads_count_by_form();
    $form=vxcf_form::post('form');
    if(!empty($forms)){ 
    ?>
<table class="entries_stats">
<thead><tr><th style="width: 55%;"><?php _e('Form Title','contact-form-entries') ?></th>
<th><?php _e('Entries','contact-form-entries') ?></th>
<th><?php _e('Unread','contact-form-entries') ?></th>
</tr></thead><tbody><?php
    foreach($forms as $type=>$forms_arr){
    if(!empty($forms_arr['forms'])){
        foreach($forms_arr['forms'] as $k=>$v){
            $form_id=$type.'_'.$k;
            if(!empty($form) && $form != $form_id){ continue; }
         $entries=isset($counts['total'][$form_id]) ? $counts['total'][$form_id] : '0';
         $unread=isset($counts['unread'][$form_id]) ? $counts['unread'][$form_id] : '0'; 
         $link=vxcf_form::link_to_settings().'&form_id='.$form_id;
  echo '<tr><td><a href="'.$link.'">'.$v.'</a></td><td class="td_number">'.$entries.'</td><td class="td_number">'.$unread.'</td></tr>';          
        }
    }    
    } ?></tbody> </table><?php }else{  ?>
<h1 style="text-align: center; margin: 50px 0;"><?php _e('No Data To Display','contact-form-entries') ?></h1>
<?php } ?> 
    <style type="text/css">
.entries_stats{
    width: 100%;
    border-collapse: collapse;
}
.entries_stats  a{
text-decoration: none;
}
    .entries_stats>tbody>:nth-child(odd){
    background-color: #f3f3f3;
}
.entries_stats .td_number{
    text-align: center;
    font-weight: bold;
    color: #666;
}
.entries_stats td{
    padding: 10px 12px ;
    border-bottom: 0px solid #999;
}
.entries_stats th{
    border-bottom: 2px solid #aaa;
    padding: 10px 14px;
}
</style>    
    <?php
}
public function add_forms_stats(){
?>
<div class="crm_panel">
<div class="crm_panel_head"><div class="crm_head_div"><span class="crm_head_text"><?php _e('Entries','contact-form-entries') ?></span></div>
<div class="crm_btn_div" title="Expand / Collapse"><i class="fa fa-minus crm_toggle_btn"></i></div><div class="crm_clear"></div></div>
<div class="crm_panel_content crm-block-content" style="display: block; height: auto;">
<?php 
$this->forms_stats_table();
 ?>
</div>
</div>

<?php    
}
public function crmperks_forms_table_add_unread_field($fields){
  if(isset($fields['conversion'])){
   $con= $fields['conversion'];
    unset($fields['conversion']);
    $fields['unread']=__('Unread','contact-form-entries'); 
    $fields['conversion']=$con; 
  }
   return $fields; 
}
public function crmperks_disable_entry_check($options){
    ?>
<div class="crm-panel-field">
<label ><input type="checkbox" name="vx_config[disable_db]" value="yes" <?php if( !empty($options['disable_db'] )){echo "checked='checked'";}?> autocomplete="off"> <?php _e('Disable storing entry information in WordPress.', 'contact-form-entries'); ?></label>
</div>
    <?php
}
public function entries_mapping_page(){ 

    wp_enqueue_style('fontawsome');
    if(isset($_GET['id'])){
        $this->view_page();
    }else{
     $this->entries_page();   
    }
}  
public  function main_menu($menus){ 
  // Adding submenu if user has access
$menu_id='vxcf_leads';  
if(isset($_GET['tab'])){self::$tab=$_GET['tab']; } 
if(empty($GLOBALS['admin_page_hooks'][$menu_id])){
$unread=$this->data->get_unread_total(); 
if($unread > 99){ $unread='99+'; }
$menu_title=$page_title =__('CRM Entries','contact-form-entries');
if(!empty($unread)){
$menu_title.=' <span class="update-plugins count-3"><span class="plugin-count">'.$unread.'</span></span>';
}
if(self::$tab == 'entries' && !empty($_GET['id'])){ 
$page_title =__('Edit Entry','contact-form-entries');
}
$capability = "vx_crmperks_view_plugins";
$icon='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNy4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMjRweCIgaGVpZ2h0PSIyNi45NzVweCIgdmlld0JveD0iMTA2LjQyMSAxMjIuNDAxIDI0IDI2Ljk3NSIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAxMDYuNDIxIDEyMi40MDEgMjQgMjYuOTc1Ig0KCSB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIGZpbGw9IiNGMkYyRjIiIGQ9Ik0xMjkuMzE0LDE0Mi4wMjFsLTEwLjc1Nyw2LjM2OGwtMTAuODkzLTYuMTMybC0wLjEzNi0xMi41bDEwLjc1Ny02LjM2OGwxMC44OTMsNi4xMzJMMTI5LjMxNCwxNDIuMDIxeg0KCSBNMTI2LjY3MSwxMzIuMzEyYzAtMC41MDgtMC4zOTEtMC45Mi0wLjg3NC0wLjkyaC0xNC44MWMtMC40ODMsMC0wLjg3NCwwLjQxMi0wLjg3NCwwLjkybDAsMGMwLDAuNTA4LDAuMzkxLDAuOTIsMC44NzQsMC45MmgxNC44MQ0KCUMxMjYuMjgsMTMzLjIzMiwxMjYuNjcxLDEzMi44MiwxMjYuNjcxLDEzMi4zMTJMMTI2LjY3MSwxMzIuMzEyeiBNMTIxLjIzMywxNDEuMDVjMC0wLjUwOC0wLjQxMi0wLjkyLTAuOTItMC45MmgtMy43NzENCgljLTAuNTA4LDAtMC45MiwwLjQxMi0wLjkyLDAuOTJsMCwwYzAsMC41MDgsMC40MTIsMC45MiwwLjkyLDAuOTJoMy43NzFDMTIwLjgyMSwxNDEuOTcsMTIxLjIzMywxNDEuNTU5LDEyMS4yMzMsMTQxLjA1DQoJTDEyMS4yMzMsMTQxLjA1eiBNMTIzLjQ1MSwxMzYuNjM1YzAtMC41MDgtMC40MzItMC45Mi0wLjk2Ni0wLjkyaC04LjE4N2MtMC41MzMsMC0wLjk2NiwwLjQxMi0wLjk2NiwwLjkybDAsMA0KCWMwLDAuNTA4LDAuNDMyLDAuOTIsMC45NjYsMC45Mmg4LjE4N0MxMjMuMDE5LDEzNy41NTUsMTIzLjQ1MSwxMzcuMTQzLDEyMy40NTEsMTM2LjYzNUwxMjMuNDUxLDEzNi42MzV6Ii8+DQo8L3N2Zz4NCg==';

$hook=add_menu_page($page_title,$menu_title,$capability,$menu_id,array( $this,'mapping_page'),$icon);
if(empty(self::$tab)){ self::$tab='entries'; }
} 
          $base_url=vxcf_form::get_base_url(); 
  wp_register_style('fontawsome', $base_url. 'css/font-awesome.min.css'); 
  wp_register_style('vx-datepicker', $base_url. 'css/jquery-ui.min.css');

 if(self::$tab == 'entries'){
 vxcf_form::$show_screen_options=true;
 } //var_dump(vxcf_form::$show_screen_options); die();       
if( vxcf_form::$show_screen_options ){ 
if( !isset($_GET['id']) ){
add_filter( 'manage_toplevel_page_'.vxcf_form::$id.'_columns', array($this,'screen_cols') );
  //add form fields , if form options do not exist
add_filter( 'get_user_option_managetoplevel_page_'.vxcf_form::$id.'columnshidden', array($this,'hide_cols') );
add_action("load-toplevel_page_vxcf_leads", array($this,'screen_options'));
if(!isset($_GET['tab']) || in_array($_GET['tab'],array('entries') )){
add_action("load-$hook", array($this,'screen_options')); //toplevel_page_vxcf_leads
} }
//sequence
//1=screen cols
//2=hide cols
//3=update_hidden_cols
}
//$this->setup_plugin();
  } 
  /**
  * plugin admin features
  * 
  */
  public function setup_plugin(){
        global $wpdb;

     $action=vxcf_form::post(vxcf_form::$id.'_action');
     if(isset($_REQUEST[vxcf_form::$id.'_action'])){   
          check_admin_referer('vx_action','vx_action');
 if(current_user_can(vxcf_form::$id."_edit_settings")){
$tab=isset($_GET['tab']) ? $_GET['tab'] : 'entries';
  $link=vxcf_form::link_to_settings($tab);
//
$status_str='';
if(isset($_GET['status'])){
 $status_str=$_GET['status'];   
}
 $ids=array();
if(isset($_POST['lead_id']) && is_array($_POST['lead_id']) && count($_POST['lead_id'])>0){
    foreach($_POST['lead_id'] as $id){
  $ids[]=(int)$id;   
 }   
}else if(isset($_GET['id'])){
    $id=(int)vxcf_form::post('id');
     $ids=array($id);
}
$entries=count($ids);
if($entries > 1){
   $entries.=' Entries'; 
}else{
    $entries.=' Entry'; 
}
$data=vxcf_form::get_data_object();
if(!empty($ids)){ 
$msg=__('Oops! Something went wrong.','contact-form-entries');
  $class='error';
    
  if($action=="trash"){ 
      global $wp_filter;

     do_action('vx_cf7_pre_trash_leads', $ids);
$res=$data->lead_actions(array('status'=>'1'),$ids);
if($res){
    $msg=sprintf(__('%s Moved to Trash','contact-form-entries'),$entries);
  $class='updated';      
}
 }
 
  if($action=="read"){
     
$res=$data->lead_actions(array('is_read'=>'1'),$ids);
if($res){
    $msg=sprintf(__('%s Marked Read','contact-form-entries'),$entries);
  $class='updated';      
}

 }

//
  if($action=="unread"){
     
$res=$data->lead_actions(array('is_read'=>'0'),$ids);
if($res){
    $msg=sprintf(__('%s Marked Unread','contact-form-entries'),$entries);
  $class='updated';      
}

 }
 
   if($action=="star"){
     
$res=$data->lead_actions(array('is_star'=>'1'),$ids);
if($res){
    $msg=sprintf(__('%s Starred','contact-form-entries'),$entries);
  $class='updated';      
}

 }
 
    if($action=="unstar"){
     
$res=$data->lead_actions(array('is_star'=>'0'),$ids);
if($res){
    $msg=sprintf(__('%s Unstarred','contact-form-entries'),$entries);
  $class='updated';      
}

 }
 
    if($action=="restore"){
    do_action('vx_cf7_pre_restore_leads', $ids); 
$res=$data->lead_actions(array('status'=>'0'),$ids);

if($res){
    $msg=sprintf(__('%s Restored','contact-form-entries'),$entries);
  $class='updated';      
}

 }
 
 //
 if($action=="delete"){
 do_action('vx_cf7_pre_delete_leads', $ids);
$res=$data->delete_leads($ids);
    $msg=sprintf(__('%s Deleted Permanently','contact-form-entries'),$entries);
  $class='updated';      
$status_str='trash';
 }
 
 
$this->add_msg($msg,$class);

if(isset($_GET['tab'])){
 //$link.='&tab='.$_GET['tab'];   
}

if(isset($_GET['id'])){
 //$link.='&id='.$_GET['id']; 
   
}
 if(isset($_GET['form_id'])){
 $link.='&form_id='.$_GET['form_id'];   
}

if(!empty($status_str)){
 $link.='&status='.$status_str;   
}
wp_redirect($link.'&msg=1');
die();

  
}
 //
  }
     }
 
   if(vxcf_form::post('vx_tab_action_'.vxcf_form::$id)=="export_log"){
  check_admin_referer('vx_nonce','vx_nonce');
  if(current_user_can(vxcf_form::$id."_edit_settings")){ 

// get charset
//$charset = get_bloginfo( 'charset' );     
//$this->data=vxcf_form::get_data_object();
vxcf_form::set_form_fields();
  $form_id=vxcf_form::$form_id;
vxcf_form::download_csv($form_id);
  die();
  }}
  
  }
 public function set_per_page( $save, $option, $value ){
      if ( $option == vxcf_form::$id.'_per_page' ) {
            $save = (int) $value; 
        }

        return $save;    
 }
 public function screen_options(){
     add_screen_option( 'per_page', array( 'label' => __( 'Entries', 'contact-form-entries' ), 'default' => 20, 'option' => vxcf_form::$id.'_per_page' ) );
       
 }
 public function add_msg($msg,$level='updated'){
   $option=get_option(vxcf_form::$id.'_msgs',array());
   if(!is_array($option)){
   $option=array();    
   }
   $option[]=array('msg'=>$msg,'class'=>$level);
 update_option(vxcf_form::$id.'_msgs',$option);  
 } 
  
/**
  * CRM menu page
  * 
  */
public  function mapping_page(){ 
       wp_enqueue_style('fontawsome');  
      $tab=$view = isset($_GET["tab"]) ? $_GET["tab"] : 'entries';
 $extra_tabs=array();

      $extra_tabs=apply_filters('vx_entries_plugin_tabs',$extra_tabs);

      if(!empty($extra_tabs)  && isset($extra_tabs[$view]['tab'])){ 
       $view=$extra_tabs[$view]['tab'];  
         }
 
      $last=$tabs=array();
      if(!empty($extra_tabs)){
          foreach($extra_tabs as $k=>$v){
if(empty($v['label'])){
    continue;
}
      if($k == 'settings'){
      $last[$k]=$v['label'];        
      }else{
       $tabs[$k]=$v['label'];
                } 
          }  
      
      if(count($last)>0){
          $tabs=array_merge($tabs,$last);
      }
      }
          ?>
<style type="text/css">
        .vx_wrap select.vx_sel_main{
    min-width: 200px;
    margin-left: 10px;
    font-weight: normal;
}   
.vx_head_i{
     background: #f5f5f5;font-size: 14px; font-weight: bold;
    border: 1px solid #dedede; 
    padding: 7px 20px;
   
} 
.vx_td_border_bottom{
    border-bottom: 1px dashed #ccc;
    padding: 8px 20px;
}
  .reg_proc{
      display: none;
  }
            </style>
<script type="text/javascript">
              function button_state(state,button){
var ok=button.find('.reg_ok');
var proc=button.find('.reg_proc');
     if(state == "ajax"){
          button.attr({'readonly':'readonly'});
          button.addClass('disabled');
ok.hide();
proc.show();
     }else{
         button.removeAttr('readonly');
         button.removeClass('disabled');
   ok.show();
proc.hide();      
     }
}
            </script>
    <div class="wrap">
          
    <h2 class="nav-tab-wrapper">
    <?php
    $link=admin_url('admin.php?page=vxcf_leads&tab=');
        foreach($tabs as  $k=>$v){
    ?>
        <a href="<?php echo $link.$k ?>" class="nav-tab <?php if($k == $view){echo 'nav-tab-active';} ?>"><?php echo $v; ?></a>
            
    <?php
        }
        ?>
        </h2>
 
    <div style="padding-top: 6px;">    
        <?php
  
   if(!empty($extra_tabs)  && isset($extra_tabs[$tab])){ 
      call_user_func($extra_tabs[$tab]['function']);
  
  }else{ 
 // $this->entries_page();
  }
  ?>
  </div>
  </div>
  <?php
 }
public function update_hidden_cols( $null, $object_id, $meta_key, $meta_value, $prev_value ) { 
    $name="managetoplevel_page_".vxcf_form::$id."columnshidden";

    if ( $name == trim($meta_key) ) {
        if ( empty( $prev_value ) ) {
                    $prev_value = get_metadata('user', $object_id, $meta_key, true);
        }
              $form_id=''; 
            if(is_array($meta_value) && count($meta_value)>0){
               foreach($meta_value as $k=>$v){
                $col_arr=explode('-vxvx-',$v);
                 if(isset($col_arr[1]) && $col_arr[1] == 'vxxx'){
              $form_id=$col_arr[0]; 
              //unset($meta_value[$k]); 
              break;       
                 }     
               } 
            } 
       //     var_dump($meta_value,$prev_value,$form_id);
//die($form_id.'-----------======');
        if(!empty($form_id) && is_array($prev_value) && count($prev_value)>0){
      
            $prev_fields=array(); 
        
            foreach($prev_value as $k=>$v){
             $col_arr=explode('-vxvx-',$v);
           //  echo $col_arr[0].'-----------'.$form_id.'<hr>';
             if( isset($col_arr[1])){
              if($col_arr[0] == $form_id ){ //remove previous fields
              continue;    
              }   
            $prev_fields[]=$v;    
             }    
             } //r_dump($prev_fields,$form_id,$meta_value);//die();
           $meta_value=array_merge($meta_value,$prev_fields); 
        }
          global $wpdb;
  $table=$wpdb->usermeta;
  $where = array( 'user_id' => $object_id, 'meta_key' => $name );
  
if(metadata_exists('user',$object_id,$name)){
  $wpdb->update( $table, array('meta_value'=>maybe_serialize( $meta_value )), $where );
}else{
    $where['meta_value']=maybe_serialize( $meta_value );
    $wpdb->insert( $table, $where);
}

        return true; // this means: stop saving the value into the database
    }
    

    return null; // this means: go on with the normal execution in meta.php

}
public function hide_cols($hidden){
    
//if new form then hide default fields
$form_id=vxcf_form::$form_id;
if(is_array($form_id)){ $form_id=vxcf_form::$form_id_string; }
$col_name=$form_id.'-vxvx-vxxx';
if(!is_array($hidden)){
    $hidden=array();
}


if(!in_array($col_name,$hidden) && is_array(vxcf_form::$form_fields) && count(vxcf_form::$form_fields)>5){

 $fields_arr=array_slice(vxcf_form::$form_fields,5,count(vxcf_form::$form_fields)-6); 

 $fields=array();
 foreach($fields_arr as $v){
  $fields[]=$v['_id'];   
 }
 $user_id = get_current_user_id();

  $hidden=array_merge($hidden,$fields);  
  
  $hidden[]=$col_name;
  update_user_option( $user_id, 'managetoplevel_page_{'.vxcf_form::$id.'}columnshidden', $hidden , true ); 
}
return $hidden;
}
public function screen_cols($cols){
/*    if(isset($_GET['tab']) && $_GET['tab'] != 'entries'){
       return; 
    }
   */
 vxcf_form::set_form_fields();
    if(!empty(vxcf_form::$form_fields) ){
        $i=0;
        foreach(vxcf_form::$form_fields as $k=>$v){
             if(!empty($v['hide_in_table'])){  continue; }
      if($i > 0){
       $cols[$v['_id']]=$v['label'];
      } $i++;     
        }
    }
    return $cols;
}
  
/**
  * Displays the crm feeds list page
  * 
  */
public  function entries_page(){
  
  if(!current_user_can(vxcf_form::$id.'_read_entries')){
  _e('You do not have permissions to access this page.','contact-form-entries');    
  return;
  }

$is_section=apply_filters('add_page_html_'.vxcf_form::$id,false);

if($is_section === true){
    return;
} 

  $log_ids=array();
  wp_enqueue_script('jquery-ui-datepicker' );
     wp_enqueue_style('vx-datepicker');
  $times=array("today"=>"Today","yesterday"=>"Yesterday","this_week"=>"This Week","last_7"=>"Last 7 Days","last_30"=>"Last 30 Days","this_month"=>"This Month","last_month"=>"Last Month","custom"=>"Select Range"); 
 
 // $forms=vxcf_form::get_forms(); 

  $forms=vxcf_form::$forms; 
  $form_id=vxcf_form::$form_id;

 $status=vxcf_form::post('status'); 
if(empty($status)){
    $status='all';
}

  $crm_order=$entry_order=$desc_order=$time_order="up"; 
  $crm_class=$entry_class=$desc_class=$time_class="vx_hide_sort";
  $order=vxcf_form::post('order');
    $order_icon= $order == "desc" ? "down" : "up"; 
    $order_by='';
  if(isset($_REQUEST['orderby'])){
    $order_by= $_REQUEST['orderby']; 
  switch($_REQUEST['orderby']){ 
  case"time": $time_order=$order_icon; $time_class="";   break;    
  }          
  }
    $bulk_actions=array(""=>__('Bulk Action','contact-form-entries'));

if($status == 'trash'){
   $bulk_actions['restore']= __('Restore','contact-form-entries');
   $bulk_actions['delete']= __('Delete Permanently','contact-form-entries');
}else{
$bulk_actions["read"]=__('Mark as Read','contact-form-entries');
$bulk_actions["unread"]=__('Mark as Unread','contact-form-entries');
$bulk_actions["star"]=__('Add Star','contact-form-entries');
$bulk_actions["unstar"]=__('Remove Star','contact-form-entries');
$bulk_actions["print"]=__('Print Entry','contact-form-entries');
$bulk_actions['print_notes']=__('Print Entry + Notes','contact-form-entries');
$bulk_actions["trash"]=__('Trash','contact-form-entries');
}
  $base_url=vxcf_form::get_base_url();
/* 
$entry_fields=get_post_meta($form_id,'_vx_entry_fields',true);
 //$entry_fields=json_decode($entry_fields,true);
if(isset($_POST[vxcf_form::$id.'_fields'])){
$entry_fields=$_POST['fields'];
 update_post_meta($form_id,'_vx_entry_fields',$entry_fields);   
}
$entry_fields = is_array($entry_fields) ? $entry_fields : array();
*/
 // $fields_arr=vxcf_form::get_form_fields($form_id); 
  $fields=array(); 
  if(!empty(vxcf_form::$form_fields)){
      foreach(vxcf_form::$form_fields as $k=>$v){
    if(isset($v['hide_in_table'])){ continue; }
     $fields[$k]=$v;     
      }
  } 
$screen = get_current_screen();
//$columns = get_column_headers( $screen );
$hidden = get_hidden_columns( $screen );
//var_dump($hidden); die();
//$entry_fields=array('your-name','your-email','your-country','your-fruit','your-browser');
/* $fields=array();
if(!empty($entry_fields)){
 if(is_array($fields_arr)){
   foreach($fields_arr as $k=>$v){
 // 
   if(isset($entry_fields[$v['name']])){
   $fields[$k]=$v;    
   }
   }  
 } 
}else{
 $fields=array_slice($fields_arr,0,4);   
}

$d=vxcf_form::get_data_object();
$table=$d->get_crm_table_name('detail');
$sql="select * from $table";
global $wpdb;
$res=$wpdb->get_results($sql,ARRAY_A);
var_dump($res);
*/
$per_page=get_user_option(vxcf_form::$id.'_per_page'); 
$data=array("min"=>0,"max"=>0,"items"=>array(),"links"=>'',"result"=>array());

if(!empty($form_id)){
$data=vxcf_form::get_entries($form_id,$per_page,'leads');
}
//echo json_encode(vxcf_form::$form_fields); die(); 
 // $data= $this->data->get_leads($form_id); 
$offset=vxcf_form::time_offset(); 
$counts= $this->data->get_lead_counts($form_id); 
$counts=apply_filters('vxcf_form_lead_counts',$counts,$form_id);
$items=count($data['result']);
$leads=$data['result']; //var_dump($leads); die();
$tab=!empty(self::$tab) ? self::$tab : 'entries'; 
$upload=vxcf_form::get_upload_dir();
$nonce=wp_create_nonce("vx_action");
$entries_link=vxcf_form::link_to_settings($tab);
$entries_link_form=$entries_link;
if(is_string($form_id)){$entries_link_form.='&form_id='.$form_id;}
$link=vxcf_form::link_to_settings($tab);
if(isset($_GET['msg'])){
$this->show_msgs();
}
$base_url=vxcf_form::get_base_url();
$main_fields=array('vxurl','vxscreen','vxbrowser','vxcreated','vxupdated');
if(is_array($form_id)){$form_id=vxcf_form::$form_id_string;} 
include_once(vxcf_form::$path . "templates/leads.php");
} 

public function entries_stats(){
wp_enqueue_style('vx-datepicker');
wp_enqueue_script('jquery-ui-datepicker' );
 $all_forms=vxcf_form::get_forms(); 
 $times=array("today"=>"Today","yesterday"=>"Yesterday","this_week"=>"This Week","last_7"=>"Last 7 Days","last_30"=>"Last 30 Days","this_month"=>"This Month","last_month"=>"Last Month","custom"=>"Select Range"); 
 
include_once(vxcf_form::$path.'templates/dashboard.php');   
}

/**
  * Settings page
  * 
  */
public  function settings_page(){ 
  if(!current_user_can(vxcf_form::$id.'_read_settings')){
_e('You do not have permissions to access this page.','contact-form-entries');   
  return;
  }
  $is_section=apply_filters('add_page_html_'.vxcf_form::$id,false);

  if($is_section === true){
    return;
}  
  $msgs=array(); 
  if(!empty($_POST[vxcf_form::$id."_uninstall"])){
  check_admin_referer("vx_nonce");
  if(!current_user_can(vxcf_form::$id."_uninstall")){
  return;
  }    
  $this->uninstall();
  $uninstall_msg=sprintf(__("Contact Form Entries Plugin has been successfully uninstalled. It can be re-activated from the %s plugins page %s.", 'contact-form-entries'),"<a href='plugins.php'>","</a>");
vxcf_form::screen_msg($uninstall_msg);
  return;
  }
  else if(!empty($_POST["save"])){ //var_dump($_REQUEST); die(); 
  check_admin_referer("vx_nonce");
  if(!current_user_can(vxcf_form::$id."_edit_settings")){ 
 vxcf_form::screen_msg(__('You do not have permissions to save settings.','contact-form-entries'));
  return;   
  }
    update_option(vxcf_form::$id.'_meta',vxcf_form::post('meta'));
  $msgs['submit']=array('class'=>'updated','msg'=>__('Settings Changed Successfully','contact-form-entries'));
  ////////////////////
  }                

  $meta=get_option(vxcf_form::$id.'_meta',array());

include_once(vxcf_form::$path . "templates/settings.php");
}
public function format_admin_field($row,$name){
    $field_label='';
   if( $name == 'url' && isset($row[$name]) && filter_var($row[$name],FILTER_VALIDATE_URL)){
       $url=trim($row[$name],'/');
 $field_label='<a href="'.$row[$name].'" target="_blank">...'.substr($url,strrpos($url,'/')).'</a>';   
}
if($name == 'browser'){
if(!empty($row['browser']) ){
$field_label.='<img src="'.vxcf_form::$base_url.'images/'.$row['browser'].'.png" class="icon_s"  title="'.$row['browser'].'">';
}
if(!empty($row['os'])){
$field_label.='<img src="'.vxcf_form::$base_url."images/".$row['os'].'.png" class="icon_s"  title="'.$row['os'].'">';
} } 
return $field_label;
} 
    /**
  * Create or edit crm feed page
  * 
  */
  public  function view_page(){  
  if(!current_user_can(vxcf_form::$id.'_read_entries')){
  _e('You do not have permissions to access this page.','contact-form-entries');    
  return;
  } 
    $is_section=apply_filters('add_page_html_'.vxcf_form::$id,false);

  if($is_section === true){
    return;
} 

  wp_enqueue_style('fontawsome');

  $id=vxcf_form::post('id'); 
$msgs=array(); $is_valid=true;
$fields=array(); $form_id=""; $form_name="";
$tab=vxcf_form::post('tab');
$lead=$this->data->get_lead($id);
   if(empty($lead)){
       return;
   }
   if($lead['status'] == 0){
       $actions=array('is_read'=>'1');
   $this->data->lead_actions($actions,array($id));    
   }
   if(empty($lead['form_id'])){
    $msgs['empty']=array('class'=>'error','msg'=>__('Entry not found', 'contact-form-entries'));  
   }else{
   
//  $config = $this->data->get_feed('new_form');
//  $feeds_link=vxcf_form::link_to_settings('feeds');  
//$new_feed_link=$feed_link.'&id='.$config['id'];  
 //$form_id=vxcf_form::post('id');
if($tab == 'entries'){
 $forms=vxcf_form::get_forms();
} 
     $form_id=$lead['form_id']; 
  if(empty($form_id) && is_array($forms)){
      $form_key=key($forms);
      if(isset($forms[$form_key]['forms']) && is_array($forms[$form_key]['forms'])){
     $form_i=key($forms[$form_key]['forms']);     
      }
   $form_id=$form_key.'_'.$form_i;   
  }     
   $fields=vxcf_form::get_form_fields($form_id); 

$detail= $this->data->get_lead_detail($id);
   }          
   //updating meta information
if(!empty($_POST[vxcf_form::$id.'_submit']) && !empty($_POST['lead'])  && !empty($form_id)){
   
  check_admin_referer("vx_nonce");
  if(!current_user_can(vxcf_form::$id.'_edit_entries')){
 vxcf_form::screen_msg(__('You do not have permissions to edit/save entry.','contact-form-entries')); 
  return;
  }
if(!empty($_POST['vxcf_leads_submit'])){
  $post=array();
 $lead_fields=array();
 if(is_array($fields) && count($fields)>0){
$upload=vxcf_form::get_upload_dir();
$upload_path=$upload['path'];
$folder=$upload['folder'];
$update=array(); $insert=array();
foreach($fields as $field){
if(!empty($field['name']) ){
$type=$field['type'];
$name=(string)$field['name']; 
$value='';
if($type == 'file' ){
//delete files
$files=array();
if(!empty($detail[$name]['value']) ){
        $db_files= maybe_unserialize($detail[$name]['value']);
        if(!empty($db_files) && !is_array($db_files)){
            $db_files=array($db_files);
        }
          if(is_array($db_files)){
          foreach($db_files as $k=>$file){
if(!isset($_POST['files_'.$name][$k])){   
    //delete old file
    if( file_exists($upload['dir'].$file)){
        
    @unlink($upload['dir'].$file);    
    }    
               }else{ 
             $files[]=$file;      
               }
          }
          }
}

if(!empty($_FILES)){
              if(isset($_FILES[$field['name']]['name']) && is_array($_FILES[$field['name']]['name'])){
          foreach($_FILES[$field['name']]['name'] as $k=>$file_name){
           
          $tmp_file=$_FILES[$field['name']]['tmp_name'][$k];    
      if(!empty($tmp_file)){
    $filename = sanitize_file_name( $file_name );
    $filename = wp_unique_filename( $upload_path, $filename );

    $new_file = trailingslashit( $upload_path ) . $filename;

    if ( false === @move_uploaded_file( $tmp_file, $new_file ) ) {
    $is_valid=false;
      $msgs['file']=array('class'=>'error','msg'=>__("File not uploaded.", 'contact-form-entries'));
      break;
    }else{
     $files[]=$folder.'/'.$filename;      
    }
      }
       
          }  
              }
}
$value=$files;    
}
else if(isset($_POST['lead'][$name])){
if($type == 'textarea'){
   $value=sanitize_textarea_field(wp_unslash($_POST['lead'][$name])); 
}else{
 $value=vxcf_form::clean($_POST['lead'][$name]);   
}              
}
               
     if(isset($detail[$name]['value'])){
         if(is_array($value)){
          $value=serialize($value);   
         }
//old serialized val is not rqual to new serialized value
         if($detail[$name]['value'] !=$value){   
   $update[$detail[$name]['id']]=$value;
  // $update[$name]=$value;
         }
}else if(!empty($value)){
   $insert[$name]=$value;
} }   
}
//var_dump($update,$insert,$id); die();
   $is_valid=$this->data->update_lead($update,$insert,$id);
  $detail= $this->data->get_lead_detail($id);
  $lead=$this->data->get_lead($id);
 } 
  if($is_valid){
      $feed_link=vxcf_form::link_to_settings($tab);
      if($tab == 'entries'){$feed_link.='&form_id='.$form_id; }
 $msgs['save']=array('class'=>'updated','msg'=>sprintf(__("Entry Updated. %sGo back to entries%s", 'contact-form-entries'), '<a href="'.$feed_link.'">', "</a>"));
  }
  else{
  $msgs['save']=array('class'=>'error','msg'=>__("Entry could not be updated.", 'contact-form-entries'));

  }
}
//var_dump($update,$insert,$post,$detail); die('-----------');
  //check uploaded files
  if($is_valid){ //var_dump($update,$insert,$id); die();

  do_action('vx_cf7_entry_updated',$detail,$id,$lead); 
  }

  } 

$nonce=wp_create_nonce("vx_action");
$link=vxcf_form::link_to_settings($tab);
$form_link=$link;
if($tab == 'entries'){$form_link.='&form_id='.$form_id; }

$trash_link=$form_link.'&id='.$id.'&'.vxcf_form::$id.'_action=trash&vx_action='.$nonce;
$del_link=$form_link.'&id='.$id.'&'.vxcf_form::$id.'_action=delete&vx_action='.$nonce;
$restore_link=$form_link.'&id='.$id.'&'.vxcf_form::$id.'_action=restore&vx_action='.$nonce;


$edit=$tab == 'edit' ? true : false;

if(!empty($msgs) || isset($_GET['msg'])){ 
  $this->show_msgs($msgs);  
}

$notes= $this->data->get_lead_notes($id);
if(empty($fields)){
?>
<div class="error below-h2"><p><?php _e('No fields found.','contact-form-entries') ?></p></div>
<?php
return '';
}

include_once(vxcf_form::$path . "templates/view.php");
  
  }
public function show_msgs($msgs=""){ 
 if(empty($msgs)){
   $option=get_option(vxcf_form::$id.'_msgs',array());
 }else{
     $option=$msgs;
 }
   if(is_array($option) && count($option)>0){
   foreach($option as $msg){
     vxcf_form::screen_msg($msg['msg'],$msg['class']);  
   }
  if(empty($msgs)){ 
  update_option(vxcf_form::$id.'_msgs',array());
  }  
   }  
 }  
    /**
     * Get Countries and States JSON
     * @return array countries and states
     */
private function get_country_states(){
           $states_json='{ "AL": "Alabama", "AK": "Alaska", "AS": "American Samoa", "AZ": "Arizona", "AR": "Arkansas", "CA": "California", "CO": "Colorado", "CT": "Connecticut", "DE": "Delaware", "DC": "District Of Columbia", "FM": "Federated States Of Micronesia", "FL": "Florida", "GA": "Georgia", "GU": "Guam", "HI": "Hawaii", "ID": "Idaho", "IL": "Illinois", "IN": "Indiana", "IA": "Iowa", "KS": "Kansas", "KY": "Kentucky", "LA": "Louisiana", "ME": "Maine", "MH": "Marshall Islands", "MD": "Maryland", "MA": "Massachusetts", "MI": "Michigan", "MN": "Minnesota", "MS": "Mississippi", "MO": "Missouri", "MT": "Montana", "NE": "Nebraska", "NV": "Nevada", "NH": "New Hampshire", "NJ": "New Jersey", "NM": "New Mexico", "NY": "New York", "NC": "North Carolina", "ND": "North Dakota", "MP": "Northern Mariana Islands", "OH": "Ohio", "OK": "Oklahoma", "OR": "Oregon", "PW": "Palau", "PA": "Pennsylvania", "PR": "Puerto Rico", "RI": "Rhode Island", "SC": "South Carolina", "SD": "South Dakota", "TN": "Tennessee", "TX": "Texas", "UT": "Utah", "VT": "Vermont", "VI": "Virgin Islands", "VA": "Virginia", "WA": "Washington", "WV": "West Virginia", "WI": "Wisconsin", "WY": "Wyoming" }';
         $countries_json='{"AF":"Afghanistan","AX":"Aland Islands","AL":"Albania","DZ":"Algeria","AS":"American Samoa","AD":"AndorrA","AO":"Angola","AI":"Anguilla","AQ":"Antarctica","AG":"Antigua and Barbuda","AR":"Argentina","AM":"Armenia","AW":"Aruba","AU":"Australia","AT":"Austria","AZ":"Azerbaijan","BS":"Bahamas","BH":"Bahrain","BD":"Bangladesh","BB":"Barbados","BY":"Belarus","BE":"Belgium","BZ":"Belize","BJ":"Benin","BM":"Bermuda","BT":"Bhutan","BO":"Bolivia","BA":"Bosnia and Herzegovina","BW":"Botswana","BV":"Bouvet Island","BR":"Brazil","IO":"British Indian Ocean Territory","BN":"Brunei Darussalam","BG":"Bulgaria","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodia","CM":"Cameroon","CA":"Canada","CV":"Cape Verde","KY":"Cayman Islands","CF":"Central African Republic static","TD":"Chad","CL":"Chile","CN":"China","CX":"Christmas Island","CC":"Cocos (Keeling) Islands","CO":"Colombia","KM":"Comoros","CG":"Congo","CD":"Congo, The Democratic Republic static of the","CK":"Cook Islands","CR":"Costa Rica","CI":"Cote D\"Ivoire","HR":"Croatia","CU":"Cuba","CY":"Cyprus","CZ":"Czech Republic static","DK":"Denmark","DJ":"Djibouti","DM":"Dominica","DO":"Dominican Republic static","EC":"Ecuador","EG":"Egypt","SV":"El Salvador","GQ":"Equatorial Guinea","ER":"Eritrea","EE":"Estonia","ET":"Ethiopia","FK":"Falkland Islands (Malvinas)","FO":"Faroe Islands","FJ":"Fiji","FI":"Finland","FR":"France","GF":"French Guiana","PF":"French Polynesia","TF":"French Southern Territories","GA":"Gabon","GM":"Gambia","GE":"Georgia","DE":"Germany","GH":"Ghana","GI":"Gibraltar","GR":"Greece","GL":"Greenland","GD":"Grenada","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernsey","GN":"Guinea","GW":"Guinea-Bissau","GY":"Guyana","HT":"Haiti","HM":"Heard Island and Mcdonald Islands","VA":"Holy See (Vatican City State)","HN":"Honduras","HK":"Hong Kong","HU":"Hungary","IS":"Iceland","IN":"India","ID":"Indonesia","IR":"Iran, Islamic Republic static Of","IQ":"Iraq","IE":"Ireland","IM":"Isle of Man","IL":"Israel","IT":"Italy","JM":"Jamaica","JP":"Japan","JE":"Jersey","JO":"Jordan","KZ":"Kazakhstan","KE":"Kenya","KI":"Kiribati","KP":"Korea, Democratic People\"S Republic static of","KR":"Korea, Republic static of","KW":"Kuwait","KG":"Kyrgyzstan","LA":"Lao People\"S Democratic Republic static","LV":"Latvia","LB":"Lebanon","LS":"Lesotho","LR":"Liberia","LY":"Libyan Arab Jamahiriya","LI":"Liechtenstein","LT":"Lithuania","LU":"Luxembourg","MO":"Macao","MK":"Macedonia, The Former Yugoslav Republic static of","MG":"Madagascar","MW":"Malawi","MY":"Malaysia","MV":"Maldives","ML":"Mali","MT":"Malta","MH":"Marshall Islands","MQ":"Martinique","MR":"Mauritania","MU":"Mauritius","YT":"Mayotte","MX":"Mexico","FM":"Micronesia, Federated States of","MD":"Moldova, Republic static of","MC":"Monaco","MN":"Mongolia","MS":"Montserrat","MA":"Morocco","MZ":"Mozambique","MM":"Myanmar","NA":"Namibia","NR":"Nauru","NP":"Nepal","NL":"Netherlands","AN":"Netherlands Antilles","NC":"New Caledonia","NZ":"New Zealand","NI":"Nicaragua","NE":"Niger","NG":"Nigeria","NU":"Niue","NF":"Norfolk Island","MP":"Northern Mariana Islands","NO":"Norway","OM":"Oman","PK":"Pakistan","PW":"Palau","PS":"Palestinian Territory, Occupied","PA":"Panama","PG":"Papua New Guinea","PY":"Paraguay","PE":"Peru","PH":"Philippines","PN":"Pitcairn","PL":"Poland","PT":"Portugal","PR":"Puerto Rico","QA":"Qatar","RE":"Reunion","RO":"Romania","RU":"Russian Federation","RW":"RWANDA","SH":"Saint Helena","KN":"Saint Kitts and Nevis","LC":"Saint Lucia","PM":"Saint Pierre and Miquelon","VC":"Saint Vincent and the Grenadines","WS":"Samoa","SM":"San Marino","ST":"Sao Tome and Principe","SA":"Saudi Arabia","SN":"Senegal","RS":"Serbia","ME":"Montenegro","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapore","SK":"Slovakia","SI":"Slovenia","SB":"Solomon Islands","SO":"Somalia","ZA":"South Africa","GS":"South Georgia and the South Sandwich Islands","ES":"Spain","LK":"Sri Lanka","SD":"Sudan","SR":"Suriname","SJ":"Svalbard and Jan Mayen","SZ":"Swaziland","SE":"Sweden","CH":"Switzerland","SY":"Syrian Arab Republic static","TW":"Taiwan, Province of China","TJ":"Tajikistan","TZ":"Tanzania, United Republic static of","TH":"Thailand","TL":"Timor-Leste","TG":"Togo","TK":"Tokelau","TO":"Tonga","TT":"Trinidad and Tobago","TN":"Tunisia","TR":"Turkey","TM":"Turkmenistan","TC":"Turks and Caicos Islands","TV":"Tuvalu","UG":"Uganda","UA":"Ukraine","AE":"United Arab Emirates","GB":"United Kingdom","US":"United States","UM":"United States Minor Outlying Islands","UY":"Uruguay","UZ":"Uzbekistan","VU":"Vanuatu","VE":"Venezuela","VN":"Viet Nam","VG":"Virgin Islands, British","VI":"Virgin Islands, U.S.","WF":"Wallis and Futuna","EH":"Western Sahara","YE":"Yemen","ZM":"Zambia","ZW":"Zimbabwe"}';
         return array("countries"=>$countries_json,"states"=>$states_json);
}

    /**
  * Tooltip image
  * 
  * @param mixed $str
  */
  public function tooltip($str){
  if($str == ""){return;}
  ?>
  <i class="vx_icons vxc_tips fa fa-question-circle" data-tip="<?php echo $str ?>"></i> 
  <?php  
  }
public function remove_personal_data( $exporters ) {
  $exporters[vxcf_form::$id] = array(
    'eraser_friendly_name' => __( 'Contact Form Entries' ),
    'callback' => array($this,'delete_personal_data'),
  );
  return $exporters;
}
public function delete_personal_data( $email_address, $page = 1 ) {
    
     $this->personal_data( $email_address , 'delete' );
   return array( 'items_removed' => true,
    'items_retained' => false, // always false in this example
    'messages' => array(), // no messages in this example
    'done' => true,
  ); 
}
public function export_personal_data( $exporters ) {
  $exporters[vxcf_form::$id] = array(
    'exporter_friendly_name' => __( 'Contact Form Entries' ),
    'callback' => array($this,'export_personal_data_callback'),
  );
  return $exporters;
}

public function export_personal_data_callback( $email_address, $page = 1 ) {
    $export_items=$this->personal_data( $email_address );
  // Tell core if we have more comments to work on still
  $done = true;
  return array(
    'data' => $export_items,
    'done' => $done,
  );
}
    public function personal_data( $email_address, $action='export' ) {
  $per_page = 50; // Limit us to avoid timing out
  $page = (int) $page;
$forms=vxcf_form::get_forms();
$fields=array(); $export_items=array();
if(!empty($forms)){
foreach($forms as $type=>$form_arr){
    if(!empty($form_arr['forms'])){
   foreach($form_arr['forms'] as $id=>$title){
   $form_id=$type.'_'.$id;     
    $form_fields=vxcf_form::get_form_fields($form_id);
    if(!empty($form_fields)){
        $email_fields=array();
        foreach($form_fields as $field){
            if($field['type'] == 'email'){
            $email_fields[]=$field['name'];    
            }
        }
    if($action == 'export'){    
   vxcf_form::$form_fields=$form_fields;
    }     
$entries_arr=vxcf_form::$data->get_entries($form_id,$per_page,array('search'=>$email_address,'field'=>$email_fields,'vx_links'=>'false')); 

if($action == 'delete' && !empty($entries_arr['result']) ){
$ids=array();
foreach($entries_arr['result'] as $v){
$ids[]=$v['id'];    
}
vxcf_form::$data->delete_leads($ids);    
}
if(!empty($entries_arr['result']) && $action == 'export'){
foreach($entries_arr['result'] as $v){
  if(!empty($v['detail'])){
  $entry=array();
      foreach($form_fields as $field){
   if(isset($v['detail'][$field['name'].'_field'])){
  $entry[]=array('name'=>$field['label'],'value'=>$v['detail'][$field['name'].'_field']);     
   }   
  }   
    $export_items[] = array(
        'group_id' => $form_id,
        'group_label' => $title,
        'item_id' => $v['id'],
        'data' => $entry,
      );    
  }  
} }
  //  $fields[$form_id]=$form_fields;
    }
   } }
} 
}
return $export_items;
}
  
}
}


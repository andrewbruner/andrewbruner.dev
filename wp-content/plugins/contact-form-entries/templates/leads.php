<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } 
  // echo json_encode($fields);
     ?>
 <style type="text/css">
  .vx_col{
  width: 16px; 
  }

   .widefat tr td.vx_icon_col img{
      margin-top: 2px;
  }
    .widefat tr th input{
margin-left: 0px;
  }
  .crm_status_img{
  width:18px;  display: block; margin: 1px auto; 
  }
  
  .crm_actions{
  padding: 12px 0px 10px 0px; clear: both;
  }
  .crm_input_inline{
  float: left; height: 28px; margin-right:5px; 
  }
  .vx_sort{
  cursor: pointer;
  }
  
  .vx_sort .vx_hide_sort{
  display: none;   
  }
  table .vx_icons{
      color: #888;
      font-size: 18px;
      cursor: pointer;
  }
  .vx_icons:hover{
      color: #333;
  }
  .vx_sort_icon{
  vertical-align: middle; margin-left: 5px;
  }
.wrap form  .vx_left_10{
    margin-left: 8px;
}
.entry_detail{
    border-top: 0px solid #ddd;
    border-bottom: 0px solid #ddd;
}
  @media screen and (max-width: 782px) {
  .crm_input_inline{
  float: left; height: 36px !important;
  }   
  }
    @media screen and (max-width: 1028px) {

  table .crm_panel_50{
      width: 98%;
  }   
  }
  /*********************crm panel******************/
   .crm_panel_content{
    border: 1px solid #ddd;
    border-top: 0px;
    display: none;
    padding: 16px;
    background: #fff;
}
.crm_panel * {
  -webkit-box-sizing: border-box; /* Safari 3.0 - 5.0, Chrome 1 - 9, Android 2.1 - 3.x */
  -moz-box-sizing: border-box;    /* Firefox 1 - 28 */
  box-sizing: border-box;  
}
.crm_panel_100{
margin: 10px 0;
}
.crm_panel_50{
    width: 48%;
    margin: 1%;
    min-width: 300px;
    float: left;
}
.crm_panel_head{
    background: linear-gradient(to bottom, rgba(255, 255, 255, 1) 0%, rgba(229, 229, 229, 1) 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid #ddd;  
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}
.crm_panel_head2{
    background: #f6f6f6;
    border: 1px solid #ddd;  
}
.crm_panel_head , .crm_head_text{
  font-size: 14px;  color:#666; font-weight: bold;
}
.crm_head_div{
 float: left;
 width: 80%;  padding: 8px 20px;   
}
.crm_panel_content{
    border: 1px solid #ddd;
    border-top: 0px;
    display: block;
    padding: 12px;
    background: #fff;
    overflow: auto;
}
.crm-block-content{
height: 200px;
overflow: auto;
}
.crm_btn_div{
 float: right;
 font-size: 18px;
 width:20%;  padding: 8px 20px; 
 text-align: right;
}
.crm_toggle_btn:hover{
    color: #333;
}
 .crm_toggle_btn{

     color: #999; cursor: pointer;
 }

.vx_input_100{
width: 100%;
}
.crm_clear{
    clear: both;
}
 .entry_row {
 margin: 7px auto;   
}
.entry_col1 {
    float: left;
    width: 25%;
    padding: 0px 7px;
    text-align: left;
}
 .entry_col2 {
    float: left;
    width: 75%;
    padding-left: 7px;
}
.vx_margin{
margin-top: 10px;
}
.vx_red{
color: #E31230;
}
.vx_label{
    font-weight: bold;
}
.vx_blue{
color: #1874CD;
}
.vx_val{
text-decoration: underline;
}
.vx_or{
font-style: italic;
}.vx_op{
font-style: italic;
}
.vx_u{
text-decoration: underline;
}
.vx_left_20{
margin-left: 8px;
}
.vx_error{
    background: #ca5952;
    padding: 10px;
    font-size: 14px;
    margin: 1% 2%;
    color: #fff;
}
.vx_yellow{
    background-color: #F9ECBE;
}
.vx_log_detail_footer{
    padding: 2px 10px;
    text-align: right;
}
.crm_star_black{
    color: #ccc;
}
.crm_star_yellow{
    color: #FF9800;
}
.vx_col_50{
    width: 50%;
}

.vx_lead_unread a , .vx_lead_unread td {
    font-weight: bold;
} 
.row-actions a{
    font-weight: normal;
}
.vx_wrap .crm_actions a.button{
    display:inline-block;
}
.icon_s {
    width: 15px;
    height: 15px;
    margin-left: 5px;
}
.wrap .striped tr.vx_lead_1{
    background-color: #fff8e4;
}
.tablenav .tablenav-pages a:focus,.tablenav .tablenav-pages a:hover{border-color:#5b9dd9;color:#fff;background:#00a0d2;box-shadow:none;outline:0}

.tablenav .tablenav-pages a,.tablenav-pages span.current{text-decoration:none;padding:3px 6px}

.tablenav .tablenav-pages a,.tablenav-pages-navspan{display:inline-block;min-width:17px;border:1px solid #ccc;padding:3px 5px 7px;background:#e5e5e5;font-size:16px;line-height:1;font-weight:400;text-align:center}
  </style>
  <div class="vx_wrap">
  <h2  class="vx_img_head"><?php echo $this->entry_title;
 
   if(!empty($forms) ){  ?> 
     <select name="form" id="entries_form" class="vx_sel_main">
  <?php
  foreach($forms as $f_key=>$platform){
     if(isset($platform['label'])){
      ?>
      <optgroup label="<?php echo $platform['label'] ?>">
      <?php
    if(isset($platform['forms']) && is_array($platform['forms'])){
    foreach($platform['forms'] as  $form_id_=>$form_title){  
  $sel="";
  $form_id_arr=$f_key.'_'.$form_id_;
  if($form_id == $form_id_arr)
  $sel="selected='selected'";
  echo "<option value='".$form_id_arr."' $sel>".$form_title."</option>"; 
    }      
  }
  ?>
  </optgroup>
  <?php
     } }
  ?>
  </select>
  <?php
  }
  do_action('vxcf_entries_table_title_end');
  ?>
   </h2>
  
  <div>
<div>
<div style="float: left;line-height: 30px;">
  <ul class="subsubsub" style="margin-top:0; ">
  <?php
  $k=0;
  
      foreach($counts as $label=>$count){
      $field='status'; $val=$label; $current='';
      if($label == $status){ $current='current'; }
      if(is_array($count)){ 
      $field=$count['field']; $val=$count['val']; $count=$count['count'];
      if(isset($_GET[$field]) && $_GET[$field] == $val){ $current='current';  }
      }    
          ?>
       <li>
       <?php
       if($k>0){
          echo " | ";   
        }
       $k++; 
       ?>
       <a href="<?php echo $entries_link_form.'&'.$field.'='.$val ?>" class="<?php echo $current ?>" title="<?php echo ucfirst($label) ?>"><?php echo ucfirst($label)?> <span class="count">(<?php echo $count ?>)</span></a> 
       </li>     
          <?php
      }
   $search_text=vxcf_form::post('search'); 
   $tab=vxcf_form::post('tab');
   $tab=empty($tab) ? 'entries' : $tab;  
  ?>
  </ul>
</div>
     <div style="float: right;">
<form id="vx_form" class="crm_form" method="get"><div>
    <input type="hidden" name="page" value="<?php echo vxcf_form::post('page') ?>" />
  <input type="hidden" name="form_id" value="<?php echo $form_id ?>" />
  
      <input type="hidden" name="status" value="<?php echo vxcf_form::post('status') ?>" />
  <input type="hidden" name="tab" value="<?php echo $tab; ?>" />
  <input type="text" placeholder="<?php _e('Search','contact-form-entries') ?>" value="<?php echo $search_text  ?>" name="search" class="crm_input_inline">
    <input type="hidden" name="order" value="<?php echo vxcf_form::post('order') ?>" />
  <input type="hidden" name="orderby" value="<?php echo vxcf_form::post('orderby') ?>" />

  <input type="hidden" data-name="vx_tab_action_<?php echo vxcf_form::$id ?>" id="vx_export_log" value="" autocomplete="off" />
  
  <input type="hidden" id="vx_nonce_field" value="<?php echo wp_create_nonce('vx_nonce'); ?>">

  <select name="field" class="crm_input_inline" style="max-width: 100px;">
  <option value=""><?php _e('All Fields','contact-form-entries') ?></option>
  <?php
  foreach($fields as $f_key=>$f_val){
      if(!empty($f_val['hide_in_search'])){ continue; }
  $sel="";
  if( isset($_REQUEST['field']) && $_REQUEST['field'] == $f_val['name'])
  $sel="selected='selected'";
  echo "<option value='".$f_val['name']."' $sel>".$f_val['label']."</option>";       
  }
  ?>
  </select>
<?php
    do_action('vxcf_entries_table_search_fields_end');
?>
  <select name="time" class="crm_time_select crm_input_inline" style="max-width: 100px;">
  <option value=""><?php _e('All Times','contact-form-entries') ?></option>
  <?php
  foreach($times as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['time']) && $_REQUEST['time'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".$f_key."' $sel>".$f_val."</option>";       
  }
  ?>
  </select>
  <span style="<?php if(vxcf_form::post('time') != "custom"){echo "display:none";} ?>" class="crm_custom_range"> 
  <input type="text" name="start_date" placeholder="<?php _e('From Date','contact-form-entries') ?>" value="<?php if(isset($_REQUEST['start_date'])){echo esc_attr($_REQUEST['start_date']);}?>" class="vxc_date crm_input_inline" style="width: 100px">
  <input type="text" class="vxc_date crm_input_inline" value="<?php if(isset($_REQUEST['end_date'])){echo esc_attr($_REQUEST['end_date']);}?>" placeholder="<?php _e('To Date','contact-form-entries') ?>" name="end_date"  style="width: 100px">
  </span>
 
  <button type="submit" title="<?php _e('Search','contact-form-entries') ?>" class="button-secondary button crm_input_inline"><i class="fa fa-search"></i> <?php _e('Search','contact-form-entries') ?></button> 
     
  </div>   </form> 
     <div style="clear: both;"></div> 
  </div>
  <div class="crm_clear"></div>
  </div>
  <form method="post">
  
  <div class="crm_actions tablenav">
  <div class="alignleft actions">
  <select name="<?php echo vxcf_form::$id ?>_action" id="vx_bulk_action" class="crm_input_inline" style="min-width: 100px; max-width: 250px;">
  <?php
   foreach($bulk_actions as $k=>$v){
   echo '<option value="'.$k.'">'.$v.'</option>';    
   }   
  ?>
  </select>
    <input type="hidden" name="vx_action" value="<?php echo $nonce ?>">   
  <button type="submit" class="button-secondary button crm_input_inline" title="<?php _e('Apply','contact-form-entries') ?>" id="vx_apply_bulk"><i class="fa fa-check"></i> <?php _e('Apply','contact-form-entries') ?></button>

  <?php   
         if($items>0){   
  ?>
  <button type="button" name="tab_action" title="<?php _e('Export as CSV','contact-form-entries') ?>" id="vx_export" class="button-secondary button crm_input_inline vx_left_10"><i class="fa fa-download"></i> <?php _e('Export as CSV','contact-form-entries') ?></button> 
  <?php
  }
  do_action('vxcf_entries_btns_end');
  ?>
  </div>
  <?php
if($items>0){
  ?>
  <div class="tablenav-pages"> <span id="paging_header" class="displaying-num"><?php _e('Displaying','contact-form-entries') ?> <span id="paging_range_min_header"><?php echo $data['min'] ?></span> - <span id="paging_range_max_header"><?php echo $data['max'] ?></span> of <span id="paging_total_header"><?php echo $data['items'] ?></span></span><?php echo $data['links'] ?></div>
 <?php
}
       ?>       
  </div>
  <input type="hidden" class="manage-column hidden" id="<?php echo $form_id.'-vxvx-vxxx' ?>"> 
<?php
  //  var_dump($leads); die();
?>
  <table class="widefat fixed striped sort" cellspacing="0" id="vx_entries_table">
  
  <thead>
  <tr>
  <th scope="col" id="active" class="manage-column vx_col"><input type="checkbox" class="crm_head_check"> </th>
  <th scope="col" class="manage-column vx_col"> </th>
<?php
$total_cols=2; $n=0;
  foreach($fields as $field){  
      $field_name=$field['name'];
      $sel=false;
      if(!empty($order_by) && $order_by == $field_name){
     $sel=true;     
      }
      $hide_col=''; $n++;
      if( $n>1 && in_array($field['_id'],$hidden)){
          $hide_col=' hidden';
      }else{
          $total_cols++;
      }
?>
  <th scope="col" class="manage-column vx_sort_sql vx_sort column-<?php echo $field['_id'].$hide_col; ?>" data-sort="string" id="<?php echo $field['_id']; ?>"  data-name="<?php echo $field['name']; ?>" <?php if(isset($field['vx_width'])){ echo 'style="width: '.$field['vx_width'].'"';} ?> ><?php echo $field['label'] ?>
  <i class="fa fa-caret-<?php if($sel){echo $order_icon;}else{echo $crm_order;} ?> vx_sort_icon <?php if(!$sel){echo 'vx_hide_sort';} ?>"></i>                          
  </th>
<?php
  }
?>

  </tr>
  </thead>
  
  <tfoot>
  <tr>
  <th scope="col" id="active" class="manage-column vx_col"><input type="checkbox" class="crm_head_check"> </th>
  <th scope="col" class="manage-column vx_col"> </th>
<?php $n=0;
  foreach($fields as $field){ 
        $hide_col=''; $n++;
      if( $n > 1 && in_array($field['_id'],$hidden)){
          $hide_col=' hidden';
      } 
?>
  <th scope="col" class="manage-column column-<?php echo $field['_id'].$hide_col ?> vx_sort"  data-name="crm_id"><?php echo $field['label'] ?>
  <i class="fa fa-caret-<?php echo $crm_order ?> vx_sort_icon <?php echo $crm_class ?>"></i>                          
  </th>
<?php
  }
?>


  </tr>
  
  </tfoot>
  <tbody class="list:user user-list">
  <?php
 
  if(is_array($leads) && !empty($leads)){
  $sno=0;
      foreach($leads as $lead){
  $sno++;
  ?>
  <tr class='<?php echo 'vx_lead_'.$lead['type']; if($lead['is_read'] == 0){echo ' vx_lead_unread';}  if($lead['type'] == '1'){echo '  vx_';} ?>' id="tr_<?php echo $lead['id']  ?>" data-id="<?php echo $lead['id'] ?>" >
  <td class="vx_check_col"><input type="checkbox" name="lead_id[]" value="<?php echo $lead['id'] ?>" class="crm_input_check"></td>
    <td class="vx_icon_col">
    <i class="fa fa-star toggle_star <?php echo $lead['is_star'] == 1 ? "crm_star_yellow" : "crm_star_black"; ?>"></i> </td>
    <?php
    $f_no=0;
    $status_str='';
    if(isset($_GET['status'])){
     $status_str='&status='.$_GET['status'];   
    }
  
  foreach($fields as $field_id=> $field){   
//if($field['name'] == 'time'){
//  $field['name']='created';  
//}
if(empty($field['vx_sticky_field'])){
  $f_no++;   
}

$field_label='';
if(isset($lead['detail'][$field['name'].'_field']) ){
 $field_label=maybe_unserialize($lead['detail'][$field['name'].'_field']);   
if(!empty($field['values'])){
 $field_label=vxcf_form::check_option_value($field['values'],$field_label);   
}
if(is_array($field_label)){
  $field_label=implode(', ',$field_label);  
}
if(isset($field['vx_filter'])){
///  $field_label=apply_filters('vxcf_entries_plugin_table_field_value',$field_label,$field);  
} }
if(in_array($field['name'],$main_fields)){
    $main_field=ltrim($field['name'],'vx');
if( in_array($main_field,array('created','updated') ) ){
$field_label=strtotime($lead[$main_field]); //+$offset
$field_label= date('M-d-Y H:i:s',$field_label);   
}
if($main_field == 'screen'){ $field_label=$lead['screen']; }
if( in_array($main_field,array('url','browser'))){
 $field_label=$this->format_admin_field($lead,$main_field);  
}
}
if(isset($field['type']) && $field['type'] == 'file'){
    if(filter_var($field_label,FILTER_VALIDATE_URL) === false){
  $field_label=$upload['url'].$field_label;     
    } 
     if(filter_var($field_label,FILTER_VALIDATE_URL)){
          $file_arr=explode('/',$field_label);
    $file_name=$file_arr[count($file_arr)-1];
$field_label="<div><a href='$field_label' target='_blank'>".$file_name."</a></div>";
     }
}     
if($f_no == 1){
    if(empty($field_label)){
        $field_label='N/A';
    }
$entry_link=$link.'&id='.$lead['id'];
$entry_link_f=$entries_link_form.'&id='.$lead['id'];
  $field_label='<a href="'.$entry_link.'">'.$field_label.'</a>'; 
  $mark_action='read'; $mark_label= __('Mark Read','contact-form-entries');
  if($lead['is_read'] == 1){
   $mark_action='unread'; $mark_label= __('Mark Unread','contact-form-entries');    
  }
$mark_link=$entry_link_f.'&'.vxcf_form::$id.'_action='.$mark_action.'&vx_action='.$nonce.$status_str;

$trash_link=$entry_link_f.'&'.vxcf_form::$id.'_action=trash&vx_action='.$nonce.$status_str;
$restore_link=$entry_link_f.'&'.vxcf_form::$id.'_action=restore&vx_action='.$nonce.$status_str;
$delete_link=$entry_link_f.'&'.vxcf_form::$id.'_action=delete&vx_action='.$nonce.$status_str;

}

      $hide_col='';
      if( $f_no>1 && in_array($field['_id'],$hidden)){
          $hide_col=' hidden';
      }

      ?>
               <td class="column-<?php echo $field['_id'].$hide_col ?>"><?php echo $field_label; 
      if($f_no == 1){
          ?>
          <div class="row-actions">
          <?php
              if($status == 'trash'){
          ?>
             <span class="edit"><a href="<?php echo $restore_link ?>" title="<?php _e('Restore','contact-form-entries') ?>"><?php _e('Restore','contact-form-entries') ?></a> | </span>
      
      <span class="trash"><a class="submitdelete" title="<?php _e('Delete','contact-form-entries') ?>" href="<?php echo $delete_link ?>"><?php _e('Delete','contact-form-entries') ?></a> </span>
          <?php
              }else{
          ?>
      <span class="edit"><a href="<?php echo $entry_link ?>" title="<?php _e('View','contact-form-entries') ?>"><?php _e('View','contact-form-entries') ?></a> | </span>
      
      <!--span class="inline"><a href="<?php echo $mark_link ?>"  title="<?php echo $mark_label ?>"><?php echo $mark_label ?></a> | </span--->
      
      <span class="trash"><a class="submitdelete" title="<?php _e('Trash','contact-form-entries') ?>" href="<?php echo $trash_link ?>"><?php _e('Trash','contact-form-entries') ?></a> </span>
      <?php
              }
      ?>
      </div>
          <?php
 
      } 
               ?></td>
  <?php
  }
  ?>

  </tr>
  <?php
  }
  }
  else {  
  ?>
  <tr>
    <td colspan="<?php echo $total_cols ?>" class="colspanchange">
        <?php _e("No Record(s) Found", 'contact-form-entries'); ?>
    </td>
  </tr>
  <?php
  }
  ?>
  </tbody>
  </table>

      <?php
  if($items>0){
  ?>
    <div class="crm_actions tablenav">
  <div class="tablenav-pages"> <span id="paging_header" class="displaying-num"><?php _e('Displaying','contact-form-entries') ?> <span id="paging_range_min_header"><?php echo $data['min'] ?></span> - <span id="paging_range_max_header"><?php echo $data['max'] ?></span> of <span id="paging_total_header"><?php echo $data['items'] ?></span></span><?php echo $data['links'] ?></div>
    </div>
  <?php
  }
  ?>
  </form>

 
  </div>
 




  </div>
<div style="display: none;">

<div id="vx_sel_cols_html">

<div class="vx_head_i"><?php _e('Select Columns','contact-form-entries') ?></div>
<form method="post" id="vx_fields_form">
<div style="max-height: 300px; overflow: auto;">
<?php
  if( !empty($fields_arr) && is_array($fields_arr) ){
  foreach($fields_arr as $field){  
?>
<div class="vx_td_border_bottom">
<label for='vx_check_<?php echo $field['name'] ?>'><input type="checkbox" <?php if(isset($entry_fields[$field['name']])){echo 'checked="checked"';} ?> name="fields[<?php echo $field['name'] ?>]" id='vx_check_<?php echo $field['name'] ?>' > <?php echo $field['label'] ?></label></div>
<?php
  }}
?>
</div>

<div style="padding: 20px 20px 0px 20px;">
  <button type="submit" title="<?php _e('Save','contact-form-entries') ?>" id="vx_colorbox_save" name="<?php echo vxcf_form::$id ?>_fields" class="button-primary button">
    <span class="reg_ok"><i class="fa fa-check"></i> <?php  _e('Save','contact-form-entries') ?></span>
  <span class="reg_proc"><i class="fa fa-refresh fa-spin"></i> <?php _e('Saving ...','contact-form-entries') ?></span>
  </button> 
    &nbsp; &nbsp; &nbsp;<button type="button" title="<?php _e('Cancel','contact-form-entries') ?>" name="<?php echo vxcf_form::$id ?>_cancel" id="vx_colorbox_close" class="button"><i class="fa fa-times"></i> <?php _e('Cancel','contact-form-entries') ?></button> 
</div>
</form>
</div>

</div>

 <script type="text/javascript">
    var vx_print_link='<?php echo admin_url( 'admin-ajax.php' ).'?action=print_'.vxcf_form::$id.'&form_id='.$form_id; ?>';
    var vx_crm_ajax='<?php echo wp_create_nonce("vx_crm_ajax") ?>';
    
  (function( $ ) {
  
  $(document).ready( function($) {
 
  $(".vx_sort_sql").click(function(){
  var orby=$(this).attr('data-name');  
  if(!orby || orby =="")
  return;
  var form=$("#vx_form");
  var order=form.find("input[name=order]");
  var orderby=form.find("input[name=orderby]");
  var or="asc";
  if(orderby.val() == orby && order.val() == "asc"){
  or="desc";   
  }
  order.val(or);   
  orderby.val(orby);
  form.submit();   
  });

 $("#entries_form").change(function(){
      var form_id=$(this).val();
     var link='<?php echo $entries_link ?>'; 
      if(form_id){
      link+='&form_id='+form_id; 
      }
      window.location.href=link;
  });
  
  $(".trash a").click(function(e){
if(!confirm('<?php _e('Are you sure you want to delete?','contact-form-entries') ?>')){
    e.preventDefault();
}
  });
  $(".crm_head_check").click(function(e){
if($(this).is(":checked")){
    $(".crm_input_check,.crm_head_check").attr('checked','checked');
}else{
    $(".crm_input_check,.crm_head_check").removeAttr('checked');
    }
});
  
  $(".crm_input_check").click(function(e){
var head_checked=$(".crm_head_check").eq(0).is(':checked');
      if(!head_checked && $(".crm_input_check:checked").length == $(".crm_input_check").length){
$(".crm_head_check").attr('checked','checked');
}else if(head_checked){
$(".crm_head_check").removeAttr('checked');
}
});
  
  $("#vx_export").click(function(e){
     e.preventDefault(); 
var log_btn=$("#vx_export_log");  
  log_btn.val('export_log');
  log_btn.attr('name',log_btn.data('name'));  
  $("#vx_nonce_field").attr('name','vx_nonce');  
  var form=$("#vx_form");
  form.attr({method:'post'}); 
  form.submit();  
  form.attr({method:'get'});
    $("#vx_export_log").val('');  
  $("#vx_nonce_field").removeAttr('name');  
// form[0].reset();  
  });
    
    $("#vx_apply_bulk").click(function(e){
        var sel=$("#vx_bulk_action");
        var action=sel.val();
if(action == ""){
    alert('<?php _e('Please Select Action','contact-form-entries') ?>');
      return false;
}
if($(".crm_input_check:checked").length == 0){ 
    alert('<?php _e('Please select at least one entry','contact-form-entries') ?>');
    return false;
}
if($.inArray(action,['print','print_notes']) !=-1){
 var ids=[];
  $('.crm_input_check:checked').each(function(){
    ids.push(this.value);  
  });
  ids_str=ids.join();
 var url=vx_print_link+'&id='+ids_str;
  if(action == 'print_notes'){
   url+='&notes=1';   
  }

 window.open(url,'printwindow');
 $('.crm_input_check:checked').removeAttr('checked');
 $('.crm_head_check:checked').removeAttr('checked');
 return false;   
}

if( $.inArray(action,["send_to_crm_bulk_force","send_to_crm_bulk"]) !=-1 && $(".crm_input_check:checked").length>4){
 if(!confirm('<?php _e('Exporting more than 4 entries may take too long.\\n Are you sure you want to continue?','contact-form-entries') ?>')){
  e.preventDefault();    
 }   
}
  })
  
  $(".vx_sort").hover(function(){
  $(this).find(".vx_hide_sort").show();
  },function(){
  $(this).find(".vx_hide_sort").hide();   
  })
      
  $(".vxc_date").datepicker({ changeMonth: true,
  changeYear: true,
  showButtonPanel: true,
  yearRange: "-100:+10",
  dateFormat: 'dd-M-yy'  });
  
  $(document).on("change",".crm_time_select",function(){
  var form=$(this).parents(".crm_form");
  var input=form.find(".crm_custom_range");
  if($(this).val() != "custom"){
  form.find(".vxc_date").val("");
  }
  if($(this).val() == "custom"){
  input.show();
  }else{
  input.hide();
  }   
  });

$(document).on("click",".toggle_star",function(e){
    e.preventDefault();
   var status=1;
 var star=jQuery(this);
 var id=star.parents('tr').data('id');
 if(star.hasClass("crm_star_yellow") ){ ///nake it un-star
 star.removeClass('crm_star_yellow');    
 star.addClass('crm_star_black');    
 status=0;    
 }else{
 star.addClass('crm_star_yellow');    
 star.removeClass('crm_star_black');        
 }
$.post(ajaxurl,{action:'actions_<?php echo vxcf_form::$id ?>',id:id,status:status,action2:'toggle_star',vx_crm_ajax:vx_crm_ajax},function(res){

  });
});


  });


  }(jQuery));


//  
  function vx_toggle_log_panel(elem){
    var panel=elem.parents(".crm_panel");
 var div=panel.find(".crm_panel_content");
 var btn=panel.find(".crm_toggle_btn");
 div.slideToggle('fast',function(){
  if(jQuery(this).is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus');     
  }else{
      btn.addClass('fa-plus');     
 btn.removeClass('fa-minus');     
  }   
 });
} 
  </script>
<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } // var_dump($fields); // die();
?><style type="text/css">
  label span.howto { cursor: default; }
  
  .vx_required{color:red;}
  .vx_wrap *{
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  }
  .vx_file_single{
      width: 100%;
      padding: 2px 2px 8px 2px;
      margin: 0 0 8px 0;
      border-bottom: 1px dashed #ccc;
  }
    .vx_div{
  padding: 10px 0px 0px 0px;
  }
  .vx_head{
  font-size: 14px;
  background: #eee;
  font-weight: bold;
  border: 1px solid #d9d9d9;
    -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  }
    .vx_group{
      border: 1px dashed #d0d0d0;
      border-top-width: 0px ;
      padding: 14px;
      background: #fff;
  }
.vx_detail .vx_group{
  padding: 12px 14px;  
}  
  .vx_row{
      padding: 6px 0px;
     /*  background: #6a99c7;*/
  }
    .crm_input_inline{
  float: left; height: 28px; margin-right:5px; 
  }
  .vx_col1{float:left; width: 25%; padding-right: 20px; font-weight: bold; word-wrap: break-word;}
  .vx_col2{float:left; width: 75%; padding-right: 20px; word-wrap: break-word;}
  .vx_col3{float:left; width: 30%; font-weight: bold; word-wrap: break-word;}
  .vx_col4{float:left; width: 70%; padding-left: 6px; word-wrap: break-word;}
  @media screen and (max-width: 782px) {
  .vx_col1{float:none; width: 100%;}
  .vx_col2{float:none; width: 100%; background-color: #f2f2f2;
    padding: 10px;}    
  }
  
  .alert_danger {
  background: #ca5952;
  padding: 5px;
  font-size: 14px;
  color: #fff;
  text-align: center;
  margin-top: 10px;
  }
  .crm_sel{
  min-width: 220px;
  }

  .vx_wrap{
  margin-right: 300px;

  }
  
  .vx_contents{
  float: left;
width: 100%;
  }
  .vx_detail{
      float: right;
      width: 280px;
      margin-right: -300px;
  }
  .vx_heading{
  font-size: 18px;
  padding: 10px 20px;
  border-bottom: 1px dashed #ccc;
  }


.crm_head_div{
 float: left;
 width: 80%;  padding: 8px 20px;   
}

.crm_btn_div{
 float: right;
 width:20%;  padding: 8px 20px; 
 text-align: right;
}
.vx_action_btn:hover{
    color: #333;
}
 .vx_action_btn{
     color: #777; cursor: pointer;
     vertical-align: middle;
     font-size: 16px;
     text-decoration: none;
 }
 .vx_remove_btn{
   margin-right: 7px;  
 }
.vx_input_100{
width: 100%;
}
.crm_clear{
    clear: both;
}
.vx_entry_table{
    display: table; width: 100%; border-collapse: collapse;
    table-layout: fixed;
}
.vx_group .entry_row:first-child {
 border-top: 1px solid #ddd;   
}
.vx_group .entry_row {
border-bottom: 1px solid #ddd;
border-left: 1px solid #ddd;
border-right: 1px solid #ddd;
margin: 0px;
display: table-row;
}
.entry_col1 {
    width: 25%;
    padding: 7px;
    text-align: left;
font-weight: bold;
background-color: #fff;
display: table-cell;
vertical-align: middle;
}
 .entry_col2 {
    width: 75%;
    padding: 7px;
 min-height: 36px;
     border-left: 2px solid #ddd;
background-color: #f1f8ff;
display: table-cell;
word-wrap: break-word;
vertical-align: middle;
 }
 .entry_col2 .vx_value{
     padding-top: 2px;
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
.vx_error{
    background: #ca5952;
    padding: 10px;
    font-size: 14px;
    margin: 1% 2%;
    color: #fff;
}
.crm_panel .vx_error{
    margin: 0;
}
.vx_check{
    margin-bottom: 2px;
}
.vx_edit{
    display: none;
}
.vx_check_label{
font-weight: bold;
}
.vx_del_link , .vx_del_link a{
    color: #a00;
    text-decoration: none;
}
.vx_del_link:hover , .vx_del_link a:hover{
    color: #D54E21;
    
}
.vx_float_right{
  float: right;  
}

/************notes***************/

.vx_wrap .crm_note_text_area{
    height: 60px; margin-bottom: 6px;
}

.vx_wrap .post_time{
 margin-left: 5%;   
}
.vx_wrap .del_note{
float: right;
}

.vx_wrap .crm_note{
    margin: 3px 0 16px 0;
}
.vx_wrap .crm_check{
  width: 30px; padding: 10px 0 4px 2px;  
}
.vx_wrap .crm_note_img img{
    width: 100%; height: 100%;
}
.vx_wrap .crm_note_img {
 float: left;
 max-width: 60px; max-height: 60px;  
 border: 1px solid #ddd; padding: 1px;  
}
.vx_wrap .crm_arrow_left {
    position: relative;
    background: #fff9de;
    border-left: 3px solid #fab41a;
    padding: 10px;
    margin:5px 0px 5px 75px;
color: #666;
}
.vx_wrap .crm_arrow_left:after, .vx_wrap .crm_arrow_left:before {
    right: 100%;
    top: 20px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.vx_wrap .crm_arrow_left:after {
    border-color: rgba(255, 255, 255, 0);
    border-right-color: #fff9de;
    border-width: 8px;
    margin-top: -8px;
}
.vx_wrap .crm_arrow_left:before {
    border-color: rgba(204, 204, 204, 0);
    border-right-color: #fab41a;
    border-width: 12px;
    margin-top: -12px;
}
.vx_wrap .vx_note_red .crm_arrow_left{
  background-color: #ffede8;  
  border-color: #FF5722;
}
.vx_wrap .vx_note_red .crm_arrow_left:before{
  border-right-color: #FF5722;
}
.vx_wrap .vx_note_red .crm_arrow_left:after{
  border-right-color: #ffede8;
}

.vx_wrap .vx_note_red .key_info{
  border-color: #FF5722;
}
.vx_wrap .vx_note_green .crm_arrow_left{
  background-color: #e9ffea;  
  border-color: #28b42d;
}
.vx_wrap .vx_note_green .crm_arrow_left:before{
  border-right-color: #28b42d;
}
.vx_wrap .vx_note_green .crm_arrow_left:after{
  border-right-color: #e9ffea;
}
.vx_wrap .vx_note_green .key_info{
  border-color: #28b42d;
}
.vx_edit_note_btn{
    position: absolute;
    top: 5px;
    right: 10px;
    padding: 6px;
    color: #999;
    display: none;
     text-decoration: none;
}
.vx_edit_note_btn:hover{
  color: #333;  
}
.crm_note:hover .vx_edit_note_btn{
  display: block;  
}
.crm_note.crm_note_edit .vx_edit_note_btn{
  display: none;  
}
.vx_cancel_note_btn{
    position: absolute;
    top: 5px;
    right: 10px;
    padding: 6px;
    color: #999;
    text-decoration: none;
}
.vx_cancel_note_btn:hover{
  color: #333;  
}
.crm_note_edit .crm_note_text{
   display: none;    
}
.crm_note_edit .crm_add_note{
    margin-top: 20px;
}

.crm_note_text{
    padding-right: 16px;
}
.vx_wrap .crm_img_div img{
    width: 100%; height: 100%;
  
}
.crm_note .key_info{
    border-top: 1px solid #fab41a;
    margin-top: 12px;
    padding-top: 7px;
}

.pic_wrap{
    width: 130px; height: 130px;
    border: 1px solid #ddd; padding: 5px;
}
.vx_wrap a.page-title-action{
    top: -1px;
}
.vx_wrap .vx_req_bg .vx_input{
 border-color: #e36551;
    -webkit-box-shadow: 0 0 2px rgba(255, 50, 4, 0.8);
    box-shadow: 0 0 2px rgba(255, 50, 4, 0.8);   
}
.entry_row a{
    text-decoration: none;
}
.vx_error_white{
    border: 1px solid #ddd;
    border-left-width: 4px;
}
.vx_wrap label{ cursor: text; }
.vx_wrap.vx_edit_entry label{ cursor: pointer; }
/*******crm box *************/
.vx_msg_div a{
    color: #fff;
}
.vx_msg_div{
    word-wrap: break-word;
    padding: 10px; color: #fff;
    margin-bottom: 12px;
}
.vx_msg_div a:hover{
    color: #eee;
    text-decoration: none;
}
.icon_s {
    width: 20px;
    height: 20px;
    margin-left: 5px;
}

@media screen and (max-width: 850px) {
    .vx_wrap{
        margin-right: 0;
    }
    .vx_detail{
        float: none;
    width: 100%;
     margin-right:0; 
    }
 
}
  </style>
  <div class="vx_wrap"> 
    <h2  class="vx_img_head"><?php echo $this->entry_title; 
    if(!empty($forms)){
    ?> 
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
  ?>
  <a class="button button-default" style="vertical-align: middle" href="<?php echo $form_link; ?>"><i class="fa fa-reply"></i> <?php echo sprintf(__('Back to %s','contact-form-entries'),$this->entry_title_small) ?></a>
  </h2>
<form method="post" enctype="multipart/form-data" id="vx_en_form" novalidate="novalidate">
 <?php wp_nonce_field('vx_nonce') ?>
  <div class="vx_contents">
    <div class="vx_div" id="vx_box_main">
      <div class="vx_head">
<div class="crm_head_div"><i class="fa fa-hand-o-right"></i> <?php echo $this->entry_title_single.' #'.$id; ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-entries') ?>">
<a href="#" class="fa crm_toggle_btn vx_action_btn fa-minus"></a>
</div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group">
  <div class="vx_entry_table">
<?php 
  $emails=array(); $date_field=''; 
  if(is_array($detail) && is_array($fields)){
  foreach($fields as $field){ //var_dump($field['basetype']); 
  
  if(!isset($field['name']) || !empty($field['vx_skip_edit'])){  continue; }
  $field_id=(string)$field['name'];
$date_class='';
      $lead_field=isset($detail[$field_id]) ? $detail[$field_id] : '';
      $value='';
      if(isset($lead_field['value'])){
         $value=maybe_unserialize($lead_field['value']);
      } 
  
     if (!filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
      $emails[]=$value;   
     } 
     $key_val=array();
     $is_key=false;   
     // $id=empty($lead_field['id']) ? $field['name'] : $lead_field['id']; 
      $f_name=$field['name']; 
      $type=$field['type'];  
      $req=isset($field['req']) && $field['req'] == 'true' ? 'required="required"' : '';
      ?>
  <div class="entry_row">
  <div class="entry_col1">
  <label for="vx_<?php echo $field['name']; ?>" class="left_header"><?php echo empty($field['label']) ? '&nbsp;' : $field['label']; ?></label>
  </div>
  <div class="entry_col2">
<div class="vx_edit">
<?php 
if( in_array($type,array('textarea','chat','comma') )){  
if(is_array($value)){
  $value=implode(',',$value);  
}
 ?>
  <textarea id="vx_<?php echo $field['name']; ?>" class="vx_input vx_input_100" name="lead[<?php echo $f_name ?>]" <?php echo $req ?>><?php echo htmlentities($value);  ?></textarea>
   <?php
   $value=nl2br($value);
}
else if(in_array($type,array('checkbox','radio'))){  
    $is_key=true;

   if(!empty($field['values']) && is_array($field['values'])){
   foreach($field['values']  as $v){
   $val=$text=$v;
   if(is_array($v)){
       if(isset($v['text'])){
    $text=$v['text'];   
       }else if(isset($v['label'])){
    $text=$v['label'];   
       }
        if(isset($v['value'])){
    $val=$v['value'];   
       }
   }   
   $n=$type == 'checkbox' ? "lead[{$f_name}][]" : "lead[$f_name]";
       $check='';   //var_dump($val,$value);
    // if(!empty($value)){ 
     if((is_array($value)&& in_array($val,$value)) || (!is_array($value) && $val == $value)){
     $check='checked="checked"'; 
     if(empty($text)){
      $text=$val;   
     }
     $key_val[]=$text;
     }
   //  } 
   ?>
   <div class="vx_check">
  <input type="<?php echo $type ?>" class="vx_input vx_check_100" name="<?php echo $n ?>" <?php echo $check ?> value="<?php echo $val ?>">
  <span class="vx_check_label"><?php echo $text;  ?></span>
  </div> <?php
   }}
}
else if(in_array($type,array('select','multiselect','state','country'))){ 
    if($type == 'state'){
        $json=$this->get_country_states();
    $field['values']=json_decode($json['states'],true);    
    }else if($type == 'country'){
        $json=$this->get_country_states();
    $field['values']=json_decode($json['countries'],true);    
    }
    $is_key=true;
    $multiple=''; $i_name="lead[$f_name]";
   if($type == 'multiselect'){$multiple='multiple="multiple"'; $i_name.='[]';} 
?>
<select name="<?php echo $i_name ?>" id="vx_<?php echo $field['name']; ?>" <?php  echo $multiple  ?> class="vx_input vx_input_100" <?php echo $req ?>>
<?php
 if(!empty($field['values']) && is_array($field['values'])){
 foreach($field['values'] as $v){
        $val=$text=$v;
   if(is_array($v)){
       if(isset($v['text'])){
    $text=$v['text'];   
       }else if(isset($v['label'])){
    $text=$v['label'];   
       }
        if(isset($v['value'])){
    $val=$v['value'];   
       }
   }
     $sel='';
     if(!empty($value)){
     if((is_array($value)&& in_array($val,$value)) || (!is_array($value) && $val == $value)){
     $sel='selected="selected"';     $key_val[]=$text;
     }
     }
 echo "<option value='{$val}' {$sel}>{$text}</option>";
 }
 }   
?>
</select>
<?php
}
else if($type == 'file'){
    if(!is_array($value)){
     $files_arr=array($value);   
    }else{
        $files_arr=$value;
    } 
    $value='';
foreach($files_arr as $k=>$val){
$value.=$file_value=vxcf_form::file_link($val);
    ?>
<div class="vx_file_single">
<?php echo $file_value; ?>  
  <div>
  <input type="hidden" name="files_<?php echo $f_name.'['.$k.']' ?>" value="<?php echo $val ?>" />
  <input type="file" id="vx_<?php echo $field['name']; ?>" <?php echo $req ?> class="vx_input" name="<?php echo $f_name.'[]' ?>" autocomplete="off">
 <a href="#" class="vx_del_link vx_float_right vx_remove_file"><?php _e('Remove','contact-form-entries') ?></a>
  </div>
  </div>
   <?php
    }
    ?>
   <div class="vx_file_single">
   <button type="button" class="button vx_add_file"><i class="fa fa-plus-circle"></i> <?php _e('Add File','contact-form-entries') ?></button>
   </div>
   <div class="vx_file_single_temp" style="display: none;">
   <div class="vx_file_single">
  <input type="file" class="vx_input" name="<?php echo $f_name.'[]' ?>" autocomplete="off">
 <a href="#" class="vx_del_link vx_float_right vx_remove_file"><?php _e('Remove','contact-form-entries') ?></a>
  </div>
  </div><?php
}
else if($type == 'html'){
  $editor_id = 'vx_'.$field['name'];
$settings = array("textarea_name"=>'lead['.$field['name'].']',"tinymce"=>array('forced_root_block'=>"div"),"textarea_rows"=>20,);
wp_editor($value,$editor_id,$settings); 
}
else{  
    
  if($type == 'date'){$date_field=$date_class=' vx_date_field '; } 
 $type=!in_array($type,array('email','url','tel')) ? 'text' : $type;
$value=is_array($value) ? implode(', ',$value) : $value;  
    ?>
  <input type="<?php echo $type ?>" id="vx_<?php echo $field['name']; ?>" <?php echo $req ?> class="vx_input vx_input_100 <?php echo $date_class ?>" name="lead[<?php echo $f_name ?>]" autocomplete="off" value="<?php echo htmlentities($value);  ?>" >
   <?php
  }
    $type=$field['type'];   
?>

</div>
<div class="vx_value">
  <?php
    if($is_key){$value=$key_val;}
//      
  if($type == 'email' && filter_var($value, FILTER_VALIDATE_EMAIL)){ //it is a url    
  ?>
  <a href="mailto:<?php echo $value ?>"><?php echo $value ?></a>
  <?php
  }else if($type == 'url' && filter_var($value, FILTER_VALIDATE_URL)){ //it is a url    
  ?>
  <a href="<?php echo $value ?>" target="_blank"><?php echo $value ?></a>
  <?php
  }else if($type == 'img' && filter_var($value, FILTER_VALIDATE_URL)){ //it is a image    
  ?>
  <a href="<?php echo $value ?>" target="_blank"><img src="<?php echo $value ?>" class="vx_img_thumb_field" /></a>
  <?php
  }else if($type == 'audio' && filter_var($value, FILTER_VALIDATE_URL) ){
    // $url_parts=parse_url($value);
  // $is_callrail_rec=!empty($url_parts['host']) && $url_parts['host'] == 'app.callrail.com' && !empty($url_parts['path']) && strpos($url_parts['path'],'/recording/') !==false ? true : false;
  // if(!empty($url_parts['path']) && in_array(strtolower(substr($url_parts['path'],-4)),array('.mp3','.wav','.ogg','.aac')) ){
     //it is recording file
     ?>
<audio controls src="<?php echo $value ?>" style="width: 100%" ></audio>     
     <?php  
 //  }   
  ?>
  <a href="<?php echo $value ?>" target="_blank"><?php echo $value ?></a>
  <?php    
  }else if($type == 'video' && filter_var($value, FILTER_VALIDATE_URL) ){
     ?>
<video controls src="<?php echo $value ?>" style="max-width: 100%" ></video>     
     <?php  
 //  }   
  ?>
  <a href="<?php echo $value ?>" target="_blank"><?php echo $value ?></a>
  <?php    
  }else{
   echo is_array($value) ? implode(', ',$value) : $value;   
  }
 
 ?>
 </div>
 <?php
      //}
 ?>
  </div>
  </div>
  <?php
  } }
 if(!empty($date_field) ){
 wp_enqueue_script('jquery-ui-datepicker' );
 wp_enqueue_style('vx-datepicker');
 } 
  ?>
  </div>
  </div>
  </div>
  <div class="vx_div">
        <div class="vx_head">
<div class="crm_head_div"><i class="fa fa-comments-o"></i> <?php _e('Notes', 'contact-form-entries'); ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-entries') ?>">
<a href="#" class="fa crm_toggle_btn vx_action_btn fa-minus"></a></div>
<div class="crm_clear"></div> 
  </div>

<div class="vx_group">
<div class="crm_notes_div">
<?php
    //getting entry notes
if(count($notes)>0){
    foreach($notes as $note){ 
$this->note_template($note);
    } }
 $colors=array('0'=>__('Yellow Note', 'contact-form-entries'),'1'=>__('Green Note', 'contact-form-entries'),'2'=>__('Red Note', 'contact-form-entries'));       
 ?>
</div>
<div class="vx_note_temp">
<div class="crm_add_note" id="crm_add_note">
<textarea class="crm_note_text_area vx_input_100" style="max-height: 500px; min-height: 80px;"></textarea>
<button type="button" class="button button-default add_note_btn crm_input_inline" title="<?php _e("Add Note", 'contact-form-entries'); ?>">
<span class="reg_ok"><i class="fa fa-plus-circle"></i> <?php _e("Add Note", 'contact-form-entries'); ?></span> 
<span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> <?php _e('Adding Note ...', 'contact-form-entries'); ?></span>
</button>

<select class="crm_input_inline vx_note_color" style="margin-left: 7px;">
<?php
 foreach($colors as $k=>$v){
  echo "<option value='$k'>$v</option>";   
 }   
?>
</select>
<?php
    if(count($emails)>0){
?>
<select class="crm_input_inline vx_note_email" style="margin-left: 7px;">
<option value=''><?php _e('Also Send Email To', 'contact-form-entries'); ?></option>
<?php
 foreach($emails as $email){
  echo "<option value='$email'>$email</option>";   
 }   
?>
</select>
<input type="text" placeholder="<?php _e('Subject', 'contact-form-entries'); ?>" class="crm_input_inline vx_note_subject" style="display: none; width: 30%;" />
<?php
    }
?>
<div class="clear"></div>
</div> </div>
  </div>
  </div>
 <?php 
do_action('vx_cf_add_meta_box',$lead,$detail);
 ?>
  </div>
<div class="vx_detail">
<div class="vx_div">
      <div class="vx_head">
<div class="crm_head_div"><i class="fa fa-hand-o-right"></i> <?php echo sprintf(__('Detail','contact-form-entries') ,$id); ?></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group" style="border-bottom-width: 0px; padding-top: 4px; padding-bottom: 4px;">
        <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php echo $this->entry_title_single.' Id'; ?></label>
  </div>
  <div class="vx_col4"><?php echo $lead['id']; ?></div>
  <div class="clear"></div>
  </div>
      <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e("Submitted", 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php echo date('M/d/Y H:i',strtotime($lead['created'])); ?></div>
  <div class="clear"></div>
  </div>
     <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e('Updated', 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php echo date('M/d/Y H:i',strtotime($lead['updated'])); ?></div>
  <div class="clear"></div>
  </div>
  <?php
      if(!empty($lead['browser']) || !empty($lead['os'])){
  ?>
     <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e('System', 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php echo $this->format_admin_field($lead,'browser');  ?></div>
  <div class="clear"></div>
  </div>
  <?php }
     if(!empty($lead['screen'])){
  ?>
     <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e('Screen', 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php echo $lead['screen'];  ?></div>
  <div class="clear"></div>
  </div>
  <?php } 
     $url=$this->format_admin_field($lead,'url');
     if(!empty($url)){
  ?>
     <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e('Source Url', 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php echo $url  ?></div>
  <div class="clear"></div>
  </div>
  <?php }
   if(!empty($lead['ip'])){
   ?>
       <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e("IP", 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php
  if(class_exists('vx_geo_location')){
  echo '<a href="#vx_ip_detail" title="'.__('Go to IP Detail', 'contact-form-entries').'">'.$lead['ip'].'</a>';    
  }else{ echo $lead['ip']; } ?></div>
  <div class="clear"></div>
  </div>
<?php
   }
//var_dump($lead);
    if(!empty($lead['user_id'])){
          $userdata = get_userdata( $lead['user_id'] );
  $user_link='<a href="'. get_edit_user_link( $lead['user_id'] ) .'" title="'.__('Entry Submitted By', 'contact-form-entries').'">'. esc_attr( $userdata->display_name ) .'</a>';
?>
       <div class="vx_row">
  <div class="vx_col3">
  <label class="left_header"><?php _e("User", 'contact-form-entries'); ?></label>
  </div>
  <div class="vx_col4"><?php echo $user_link; ?></div>
  <div class="clear"></div>
  </div>
  <?php
    }
       do_action('vxcf_entry_detail_box_end',$lead,$detail);   
  ?>
  <div class="vx_save_div" style="display: none;">
  <?php
      do_action('vxcf_entry_submit_btn',$lead);
  ?>
  </div>
  </div>
        <div class="vx_head" style="font-weight:normal; padding: 10px;">
<?php
    if($lead['status'] == 1){
        ?>
<div id="vx_edit_div">
<a href="<?php echo $restore_link; ?>" title="<?php _e('Restore','contact-form-entries') ?>"><?php _e('Restore','contact-form-entries') ?></a>
<a href="<?php echo $del_link; ?>" title="<?php _e('Delete Permanently','contact-form-entries') ?>" class="vx_del_link vx_del_lead vx_float_right"><?php _e('Delete Permanently','contact-form-entries') ?></a>
</div>
        <?php
    }else{
?>
<div class="vx_edit_div">
<button type="button" class="button button-primary" id="vx_edit_button" title="<?php _e('Edit','contact-form-entries') ?>"><i class="fa fa-pencil"></i> <?php _e('Edit','contact-form-entries') ?></button>

<a href="<?php echo $trash_link; ?>"  style="line-height: 26px;" title="<?php _e('Trash','contact-form-entries') ?>" class="vx_del_link vx_del_lead vx_float_right"><?php _e('Trash','contact-form-entries') ?></a>
</div>

<div class="vx_save_div" style="display: none;">
  <button type="submit" autocomplete="off" name="<?php echo vxcf_form::$id ?>_submit" value="yes" class="button button-primary" id="vx_save_lead" title="<?php  _e('Save','contact-form-entries') ?>">
  <span class="reg_ok"><i class="fa fa-check"></i> <?php  _e('Save','contact-form-entries') ?></span>
  <span class="reg_proc"><i class="fa fa-refresh fa-spin"></i> <?php _e('Saving ...','contact-form-entries') ?></span>
  </button>

<button type="button" id="vx_cancel_button" title="<?php _e('Cancel','contact-form-entries') ?>" class="button button-default  vx_float_right"><i class="fa fa-times"></i> <?php _e('Cancel','contact-form-entries') ?></button>
</div>
<?php
    }
?>
  </div>
  </div>
<?php 
$boxes=array();
if(!empty($lead['meta'])){
 $entry_meta=json_decode($lead['meta'],true);
$pay_labels=array('status'=>__('Status','contact-form-entries'),'total_text'=>__('Total','contact-form-entries'),'gateway'=>__('Gateway','contact-form-entries'),'id'=>__('Invoice ID','contact-form-entries'));
 $pays=array();
 foreach($pay_labels as $k=>$v){
 if(isset($entry_meta['payment'][$k])){
     $val=$entry_meta['payment'][$k];
 if(in_array($k,array('status','gateway'))){
   $val=ucfirst($val);  
 } $pays[$v]=$val; }    
 } 
$boxes=array('payment'=>array('title'=>__('Payment Details','contact-form-entries'),'vals'=>$pays));   
}

$boxes=apply_filters('vx_cf_meta_boxes_right',$boxes,$lead,$detail,'entry');
foreach($boxes as $k=>$v){
    ?>
<div class="vx_div" id="vxcf_<?php echo $k ?>">
        <div class="vx_head">
<div class="crm_head_div"><?php echo $v['title'] ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-entries') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
<div class="vx_group">
<?php
if(isset($v['callback'])){
 call_user_func($v['callback'],$lead,$detail);
}
if(!empty($v['vals'])){
 foreach($v['vals'] as $label=>$val){   
 ?>
<div class="vx_row">
  <div class="vx_col3"><?php echo $label ?></div>
  <div class="vx_col4"><?php echo $val; ?></div>
  <div class="clear"></div>
  </div>
<?php } } ?>  
 </div>
</div>     
    <?php
}
do_action('vx_cf_add_meta_box_right',$lead,$detail);
 ?>
</div>
</form>
  </div>
   <script type="text/javascript">
    var vx_crm_ajax='<?php echo wp_create_nonce("vx_crm_ajax") ?>';
    var vx_entry_id='<?php echo $id; ?>';
        var vx_ajax=false;
  jQuery(document).ready(function($) {
        var form_val=jQuery('#vx_en_form').serialize();    
            var is_submit=false; 

$(window).on('beforeunload',function(){
    if(!is_submit && $('.vx_save_div').is(':visible') &&  form_val != jQuery('#vx_en_form').serialize()){
return 'You have unsaved changes.'; 
    }
});

   $(document).on('click','.vx_remove_file',function(e){
       e.preventDefault();
       var elem=$(this).parents('.vx_file_single');
mark_del(elem);
   });
      $(document).on('click','.vx_add_file',function(e){
       e.preventDefault();
       var elem=$(this).parents('.vx_file_single');
       var html=elem.next('.vx_file_single_temp').html();
elem.before(html);
   });
   $("#vx_edit_button").click(function(){ 
       var lead=$("#vx_box_main");
       var span=lead.find('.vx_value');
       var input=lead.find('.vx_edit');
       input.show();
       span.hide();
       $(".vx_edit_div").hide();
       $(".vx_save_div").show();
   });
      $("#vx_cancel_button").click(function(){
         var lead=$("#vx_box_main");
       var span=lead.find('.vx_value');
       var input=lead.find('.vx_edit');
       input.hide();
       span.show();
       $(".vx_save_div").hide();
       $(".vx_edit_div").show();
     
   });   
   
         $(".vx_del_lead").click(function(e){
             if(!confirm('<?php _e('Are you sure to delete ?','contact-form-entries') ?>')){
             e.preventDefault();    
             }
   });  
    $("#entries_form").change(function(){
      var form_id=$(this).val();
     var link='<?php echo $link ?>'; 
      if(form_id){
      link+='&form_id='+form_id; 
      }
      window.location.href=link;
  });

  $('#vx_en_form').submit(function(){
        var button=$('#vx_save_lead');
        button_state('ajax',button);
        is_submit=true;
       // button.before('<input type="hidden" name="'+button.attr('name')+'" value="yes">');
      // console.info(button.parents('form'),button);
     //  alert('sssssssssssssss');
      //  button.parents('form').submit();
});    
<?php 
if(!empty($date_field)){
?>
  $(".<?php echo trim($date_field) ?>").datepicker({ changeMonth: true,
  changeYear: true,
  showButtonPanel: true,
  yearRange: "-100:+10",
  dateFormat: 'dd-M-yy'  });
  <?php
}
  ?>
  
  /************note js*****************/
$(document).on('click','.vx_cancel_note_btn',function(e){
       e.preventDefault(); 
var note_div=$(this).parents('.crm_note');
note_div.removeClass('crm_note_edit');
note_div.find('.crm_add_note').remove();
});
$(document).on('click','.vx_edit_note_btn',function(e){
       e.preventDefault(); 
var note_div=$(this).parents('.crm_note');
note_div.addClass('crm_note_edit');
var text_div=note_div.find('.crm_note_text');
var note_temp=$('#crm_add_note').clone();
note_temp.removeAttr('id');
note_temp.find('.vx_note_subject').hide();
note_temp.find('.vx_note_color').val(note_div.attr('data-color'));
var text_area=note_temp.find('.crm_note_text_area');
var note=$.trim(text_div.text());
text_area.val(note); //str.replace(/<br\s*[\/]?>/gi, "\n")
text_area.before('<a href="#" title="<?php _e('Cancel','contact-form-entries'); ?>" class="fa fa-times vx_cancel_note_btn"></a>');
note_temp.find('.reg_ok').html('<i class="fa fa-check"></i> <?php _e('Update Note','contact-form-entries')?>');
text_div.after(note_temp);
});   
$(document).on("change",".vx_note_email",function(){
    var note=$(this).parents('.vx_note_temp');
if($(this).val() == ''){ note.find('.vx_note_subject').hide();   
}else{note.find('.vx_note_subject').show(); } 
});
$(document).on("click",".add_note_btn",function(){
    var td=$(this).parents(".vx_note_temp");
    var note=td.find(".crm_note_text_area").val(); 
var button=$(this);
  if($.trim(note) == ""){
button.before('<div class="error vx_error_white vx_note_error"><p><?php _e('Note is empty','contact-form-entries'); ?></p></div>');
    return;   
}else{
td.find('.vx_note_error').remove(); 
}
  
button_state("ajax",button);
var subject=td.find('.vx_note_subject');
var email=td.find('.vx_note_email');
var note_subject=subject.val();
var note_email=email.val();
var note_color=td.find('.vx_note_color').val();
var arr={action:'actions_<?php echo vxcf_form::$id ?>',entry_id:vx_entry_id,note:note,note_color:note_color,action2:"add_note",note_email:note_email,note_subject:note_subject,vx_crm_ajax:vx_crm_ajax};
var note_id=td.attr('data-id');
if(note_id){ arr['id']=note_id; } 
$.post(ajaxurl,arr,function(res){
if(note_id){
td.replaceWith(res);    
}else{
td.parents('.vx_group').find(".crm_notes_div").append(res);  
td.find(".crm_note_text_area").val("");
subject.val('');
email.val('');
button_state("ok",button);
}
});  
});

$(document).on("click",".del_note",function(e){
        e.preventDefault();
        if($(this).hasClass('disabled')){ return; }
        if(!confirm("Are you sure to delete?")){
            return;
        }
var id=$(this).attr('data-id');
var button=$(this);
var note=$(this).parents(".crm_note");
button_state("ajax",button);
$.post(ajaxurl,{action:'actions_<?php echo vxcf_form::$id ?>',action2:"delete_note",id:id,vx_crm_ajax:vx_crm_ajax},function(res){
note.fadeOut('slow',function(){$(this).remove()});  
//button_state("ok",button);
  });  
});



    function mark_del(obj){
  obj.css({'opacity':'.5'});
  obj.fadeOut(500,function(){
  $(this).remove();
  });
  }

  //toggle boxes
  $(document).on("click",".crm_toggle_btn",function(e){
    e.preventDefault();
    var btn=jQuery(this);
    if(btn.hasClass("vx_btn_inner")){
    var panel=btn.parents(".crm_panel");
    var div=panel.find(".crm_panel_content");    
    }else{
    var panel=btn.parents(".vx_div");
    var div=panel.find(".vx_group");      
    }
   
 div.slideToggle('fast',function(){
  if(div.is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus');     
  }else{
      btn.addClass('fa-plus');     
 btn.removeClass('fa-minus');     
  }   
 });
});
$(document).on("dblclick",".vx_head,.crm_panel_head2",function(e){
    e.preventDefault();
    $(this).find('.crm_toggle_btn').trigger('click');
});
  
  });

  </script>
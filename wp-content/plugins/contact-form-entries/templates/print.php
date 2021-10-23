<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php _e('CRM Perks Entries > Print Entries','contact-form-entries');   ?></title>
</head>
<body onload="window.print();">
<style type="text/css">
     body{
         font-family: "Open Sans",sans-serif;
         font-size: 14px;
         margin: 0px;
     }
     .clear{
         clear: both;
     }
  label span.howto { cursor: default; }
  
  .vx_required{color:red;}
  .vx_wrap *{
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
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

  .vx_col1{float:left; width: 25%; padding: 10px; background: #f6f6f6; border:2px solid #fff; font-weight: bold;}
  .vx_col2{float:left; width: 75%; padding: 10px; background: #f8f8f8; border:2px solid #fff;}

  
  .vx_contents{
padding: 10px 20px;
width: 100%;
  }

  .vx_heading{
  font-size: 18px;
  padding: 10px 20px;
  border-bottom: 1px dashed #ccc;
  }


  .vx_icons{
  font-size: 16px;
  vertical-align: middle;
  cursor: pointer;
  }
  .vx_icons-s{
  font-size: 12px;
  vertical-align: middle;  
  }
  .vx_icons-h{
  font-size: 16px;
  line-height: 28px;
  vertical-align: middle; 
  cursor: pointer; 
  }
  .vx_icons:hover , .vx_icons-h:hover{
  color: #888;
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
    text-decoration: underline;
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
.vx_wrap .crm_note_img {
 float: left;
 max-width: 60px; max-height: 60px;    
}
.vx_wrap .crm_arrow_left {
    position: relative;
    background: #FFF6E2;
    border-left: 3px solid #E69F06;
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
    border-right-color: #FFF6E2;
    border-width: 8px;
    margin-top: -8px;
}
.vx_wrap .crm_arrow_left:before {
    border-color: rgba(204, 204, 204, 0);
    border-right-color: #E69F06;
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
.vx_wrap .crm_img_div img{
    width: 100%; height: 100%;
}
.crm_note .key_info{
    border-top: 1px solid #EAB84C;
    margin-top: 12px;
    padding-top: 7px;
}
.right_links , .right_links a{
    font-size: 14px;
}
.crm_btn_div{
    display: none;
}
.vx_wrap .del_note{
display: none;
}
.crm_head_div{
    padding: 10px 20px;
}
.reg_proc{
    display: none;
}
.hide_empty .reg_ok , .hide_empty .vx_empty_row{
  display: none;   
}
.hide_empty .reg_proc{
    display: initial;
}
.vx_col2 img{
    height: 70px;
}
.vx_edit_note_btn{
    display: none;
}
.img_label{
    height: 94px;
}
.topbar a:hover{
    color: #30aae1;
}
.topbar a{
    color: #21759B;
    text-decoration: none;
    display: inline;
    font-weight: 400;
    letter-spacing: normal;
}
.topbar{
    padding: 15px;
    border-bottom: 2px solid #C5D7F1;
    background-color: #DFEFFF;
    color: #18749D;
}
@media print{
  .topbar{
      display: none;
  }
  body {-webkit-print-color-adjust: exact;}  
}
  </style>
  <script type="text/javascript">
function toggle(){
   var element = document.getElementById('body');
   if(!element.classList){ return false; }
   if(element.classList.contains('hide_empty')){
    element.classList.remove("hide_empty");   
   }else{
    element.classList.add("hide_empty");   
   }
    
}
</script>
  <?php
  if(count($leads)>0){
  ?>
  <div class="vx_wrap" id="body"> 
<div class="topbar">
<div style="font-size: 20px; float: left;"><?php _e('Print Preview','contact-form-entries') ?></div>
<div style="font-size: 20px; float: right;" class="right_links" >
<a href="javascript:;" onclick="window.print();" title="<?php _e('Print This Page','contact-form-entries') ?>"><?php _e('Print This Page','contact-form-entries') ?></a> 
| <a href="javascript:toggle()" title="<?php _e('Toggle Empty Fields','contact-form-entries') ?>">
<span class="reg_ok"><?php _e('Hide Empty Fields','contact-form-entries') ?></span>
<span class="reg_proc"><?php _e('Show Empty Fields','contact-form-entries') ?></span>
</a>
| <a href="javascript:window.close()" title="<?php _e('Close Window','contact-form-entries') ?>"><?php _e('Close Window','contact-form-entries') ?></a></div>
<div style="clear: both;"></div>
</div>
<div class="vx_contents">
  <?php
       foreach($leads as $id=>$lead){  
     $notes=$detail=array();
     if(isset($lead['lead'])){
    $detail=$lead['lead'];     
     }
    if(isset($lead['notes'])){
    $notes=$lead['notes'];     
     }
  ?>
    <div class="vx_div">
      <div class="vx_head">
<div class="crm_head_div"> <?php echo sprintf(__('Entry # %d','contact-form-entries'),$id); ?></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group">
  <?php
  if(is_array($detail) && is_array($fields)){
  foreach($fields as $field){ //var_dump($field['basetype']); //continue;
      $lead_field=isset($detail[$field['name']]) ? $detail[$field['name']] : '';
      $value='';
      if(!empty($lead_field['value'])){
         $value=maybe_unserialize($lead_field['value']);
if(!empty($field['values'])){
 $value=vxcf_form::check_option_value($field['values'],$value);   
}
      }

     // $id=empty($lead_field['id']) ? $field['name'] : $lead_field['id']; 
      $f_name=$field['name']; 
     // $type=$field['basetype'];
      $req=strpos($field['type'],'*') !==false ? 'required="required"' : '';
  
      ?>
  <div class="vx_row <?php if($value == ''){ echo 'vx_empty_row'; } ?>">
  <div class="vx_col1">
  <label for="vx_<?php echo $field['name']; ?>" class="left_header"><?php echo $field['label']; ?></label>
  </div>
  <div class="vx_col2">
  <?php
  if($value == ''){ $value='&nbsp;'; }
    if(is_array($value)){$value=implode(', ',$value); } 
    if( filter_var($value, FILTER_VALIDATE_URL)){
?><a href="<?php echo $value ?>" target="_blank"><?php echo $value ?></a><?php     
}else if(filter_var($value, FILTER_VALIDATE_EMAIL)){
?><a href="mailto:<?php echo $value ?>"><?php echo $value ?></a><?php 
}else{
  echo $value;  
}

 ?>
  </div>
  <div class="clear"></div>
  </div>
  <?php
  } }
  ?>
  </div>
  </div>
  <?php
      if(count($notes)>0){
  ?>
  <div class="vx_div">
        <div class="vx_head">
<div class="crm_head_div"><?php _e('Notes.', 'contact-form-entries'); ?></div>
<div class="crm_clear"></div> 
  </div>

  <div class="vx_group">
    <div class="crm_notes_div">
<?php
    foreach($notes as $note){
$this->note_template($note);
    } 
 ?>
</div>

  </div>
  </div>
 <?php 
      }

 }
 ?>
   </div>
 </div>
 <?php
  }
  ?>

  </body>
  </html>
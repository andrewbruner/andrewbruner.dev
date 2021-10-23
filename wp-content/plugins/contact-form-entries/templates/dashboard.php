<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }
  ?>
<style type="text/css">
#crm-panel .crm_panel_head , #crm-panel .crm_head_text{
  font-size: 16px;  color:#666; font-weight: bold;
}
#crm-panel .crm_panel_head, #crm-panel .crm_fields_right_btn:hover{
    color: #333;
}
 #crm-panel .crm_toggle_btn{
     padding: 0px 6px; cursor: pointer;
 }
#crm-panel .crm_panel_head, #crm-panel .crm_fields_right_btn{
  font-size: 16px;  color: #999; cursor: pointer;
  line-height: 20px; 
}
#crm-panel .crm_sales_fields .crm_panel_content{
background: #fff;
}
#crm-panel .crm_panel_content{
    border: 1px solid #ddd;
    border-top: 0px;
    display: none;
    padding: 16px;
    background: #fff;
}
#crm-panel .crm_panel_head * , .vis_slide_div *{
  -webkit-box-sizing: border-box; /* Safari 3.0 - 5.0, Chrome 1 - 9, Android 2.1 - 3.x */
  -moz-box-sizing: border-box;    /* Firefox 1 - 28 */
  box-sizing: border-box;  
}
#crm-panel .crm_panel{
    margin: 10px auto;
}
#crm-panel .crm_panel_head{
    background: #f2f2f2;
    border: 1px solid #ddd;  
}
#crm-panel .crm_panel_head2{
    background: #f6f6f6;
    border: 1px solid #ddd;  
}
#crm-panel .crm_clear{
    clear:both;
}
#crm-panel .crm_btn_div{
    cursor:default;
}
#crm-panel .crm_btn_div, #crm-panel .crm_btn_div_e{
 float: right;
 width:20%;  padding: 8px 20px; 
 text-align: right;
}
#crm-panel .crm_head_div{
 float: left;
 width: 80%;  padding: 8px 20px;   
}
#crm-panel .crm-block-content{
    height: 390px;
}

</style> 
<h2 class="vx_top_head"><?php _e('Entries Stats','contact-form-entries') ?></h2>
<form method="get">
                <input type="hidden" name="page" value="<?php echo vxcf_form::post('page') ?>">
                <input type="hidden" name="tab" value="<?php echo vxcf_form::post('tab') ?>">
<select name="form" class="crm_input_inline">
                <option value=""><?php _e('All Forms','contact-form-entries') ?></option>
          <?php
     if(!empty($all_forms)){     
  foreach($all_forms as $f_key=>$platform){
     if(isset($platform['label'])){
      ?>
      <optgroup label="<?php echo $platform['label'] ?>">
      <?php
    if(isset($platform['forms']) && is_array($platform['forms'])){
    foreach($platform['forms'] as  $form_id_=>$form_title){  
  $sel="";
  $form_id_arr=$f_key.'_'.$form_id_;
  if(!empty($_REQUEST['form']) && $_REQUEST['form'] == $form_id_arr){
  $sel="selected='selected'";
  }
  echo "<option value='".$form_id_arr."' $sel>".$form_title."</option>"; 
    }      
  }
  ?>
  </optgroup>
  <?php
     } } }
  ?>
</select>
    <select name="time" class="crm_time_select crm_input_inline">
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
                
             <span style="<?php if(! (isset($_REQUEST['time']) && $_REQUEST['time'] == "custom")){echo "display:none";} ?>" class="crm_custom_range"> 
                <input type="text" name="start_date" placeholder="From Date" value="<?php echo vxcf_form::post('start_date');?>" class="sales_date crm_input_inline">
         <input type="text" class="sales_date crm_input_inline" value="<?php echo vxcf_form::post('end_date');?>" placeholder="To Date" name="end_date"></span>
            
  <button type="submit" class="button-secondary button"><?php _e('Apply','contact-form-entries') ?></button>
                
 </form> 
<div id="crm-panel">
<?php do_action('crmperks_entries_stats_end'); ?>
</div> 
<script type="text/javascript">
jQuery(document).ready(function($){

  $(document).on("click",".crm_toggle_btn",function(e){
    e.preventDefault();
    var btn=jQuery(this);
    var panel=btn.parents(".crm_panel");
    var div=panel.find(".crm_panel_content");    

   
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

  })
</script>

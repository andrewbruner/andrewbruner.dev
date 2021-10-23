<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } $print=get_option('print_note_'.vxcf_form::$id);  ?>
 <style type="text/css">
.vx_msg_div a{
    color: #fff;
}
.vx_msg_div{
    word-wrap: break-word;
}
.vx_msg_div a:hover{
    color: #eee;
    text-decoration: none;
}
</style>
   <div class="vx_div">
        <div class="vx_head">
<div class="crm_head_div"><i class="fa fa-print"></i> <?php _e('Print Entry', 'contact-form-entries'); ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-entries') ?>">
<a href="#" class="fa crm_toggle_btn vx_action_btn fa-minus"></a></div>
<div class="crm_clear"></div> 
  </div>

  <div class="vx_group" style="padding: 2px 12px; border-bottom-width: 0px;">
 <p>  <label><input type="checkbox" id="vx_include_notes" <?php if(!empty($print)){ echo 'checked="checked"'; } ?>> <?php _e('Include Notes','contact-form-entries') ?></label></p>

    </div>
      <div class="vx_head" style="font-weight: normal; padding: 10px;">
      <a href="javascript:;" class="button" id="vx_print_entry" title="<?php echo __('Print Entry','contact-form-entries')?>"><?php echo __('Print Entry','contact-form-entries')?></a>
      </div>
  </div>
<script type="text/javascript">

jQuery(document).ready(function($){
        var vx_crm_ajax='<?php echo wp_create_nonce("vx_crm_ajax") ?>';
 $('#vx_include_notes').on('change',function(e){
 $.post(ajaxurl,{action:'actions_<?php echo vxcf_form::$id ?>',action2:'update_print_note',status:$(this).is(':checked') ? '1' : '',vx_crm_ajax:vx_crm_ajax});
 });
 $('#vx_print_entry').on('click',function(e){
    e.preventDefault();
   var url='<?php echo $print_link ?>';
   if($('#vx_include_notes').is(':checked')){
    url+='&notes=1';   
   }
   window.open(url,'printwindow');  
 //  window.open('http://localhost/wp19/','printwin');  
 })   
})
</script>  
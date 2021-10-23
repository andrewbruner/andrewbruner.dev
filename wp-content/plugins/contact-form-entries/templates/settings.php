<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } 
 $forms=vxcf_form::get_forms();
 $forms_arr=vxcf_form::forms_list($forms);
?>
<style type="text/css">
  .crm_fields_table{
  width: 100%; margin-top: 30px;
  }.crm_fields_table .crm_field_cell1 label{
  font-weight: bold; font-size: 14px;
  }
  .crm_fields_table .clear{
  clear: both;
  }
  .crm_fields_table .crm_field{
  margin: 20px 0px;   
  }
  .crm_fields_table .crm_text{
  width: 100%;
  }
  .crm_fields_table .crm_field_cell1{
  width: 20%; min-width: 100px; float: left; display: inline-block;
  line-height: 26px;
  }
  .crm_fields_table .crm_field_cell2{
  width: 80%; float: left; display: inline-block;
  }
  .vxc_alert{
  padding: 10px 20px;
  }
  .vx_icons{
      color: #888;
  }
  .vx_green{
    color:rgb(0, 132, 0);  
  }
  #tiptip_content{
      max-width: 200px;
  }
  .vx_tr{
      display: table; width: 100%;
  }
  .vx_td{
      display: table-cell; width: 90%;
  }
  .vx_td2{
      display: table-cell; 
  }
 .crm_field .vx_td2 .vx_toggle_btn{
      margin: 0 0 0 10px; vertical-align: baseline; width: 80px;
  }

  </style> 
<script type="text/javascript">
  jQuery(document).ready(function($){

    $(document).on('click','.vx_toggle_key',function(e){
  e.preventDefault();  
  var key=$(this).parents(".vx_tr").find(".crm_text"); 
  if($(this).hasClass('vx_hidden')){
  $(this).text('<?php _e('Show Key','contact-form-entries') ?>');  
  $(this).removeClass('vx_hidden');
  key.attr('type','password');  
  }else{
  $(this).text('<?php _e('Hide Key','contact-form-entries') ?>');  
  $(this).addClass('vx_hidden');
  key.attr('type','text');  
  }
  });
  
  
    $(".vx_tabs_radio").click(function(){
  $(".vx_tabs").hide();   
  $("#tab_"+this.id).show();   
  }); 
$(".sf_login").click(function(e){
    if($("#vx_custom_app_check").is(":checked")){
    var client_id=$(this).data('id');
    var new_id=$("#app_id").val();
    if(client_id!=new_id){
          e.preventDefault();   
     alert("<?php _e('Entries Client ID Changed.Please save new changes first','contact-form-entries') ?>");   
    }    
    }
})
  $("#vx_custom_app_check").click(function(){ 
     if($(this).is(":checked")){
         $("#vx_custom_app_div").show();
     }else{
            $("#vx_custom_app_div").hide();
     } 
  });
  
    $(document).on('click','#vx_revoke',function(e){
  
  if(!confirm('<?php _e('Notification - Remove Connection?','contact-form-entries'); ?>')){
  e.preventDefault();   
  }
  });
            // Tooltips
  var tiptip_args = {
  'attribute' : 'data-tip',
  'fadeIn' : 50,
  'fadeOut' : 50,
  'defaultPosition': 'top',
  'delay' : 200
  };
 // $(".vxc_tips").tipTip( tiptip_args );
  });
  </script> 
<div class="vx_wrap">

<form action="" method="post">
  <?php wp_nonce_field("vx_nonce") ?>
  <h2>
  <?php esc_html_e("General and GDPR Settings", 'contact-form-entries') ?>
  </h2>
  <table class="form-table">
 <?php
      if(!empty($forms_arr)){
  ?> 
  <tr>
  <th scope="row"><label for="vx_form_cf">
  <?php _e('Do Not Track Forms', 'contact-form-entries'); ?>
  </label>
  </th>
  <td>
<?php
$saved_forms=!empty($meta['disable_track']) ? $meta['disable_track'] : array();

foreach($forms_arr as $k=>$v){

?>
<p><label for="vx_form_<?php echo $k ?>"><input type="checkbox" name="meta[disable_track][<?php echo $k ?>]" value="yes" <?php if(vxcf_form::post($k,$saved_forms) == "yes"){echo 'checked="checked"';} ?> id="vx_form_<?php echo $k ?>"><?php echo $v; ?></label></p>
<?php 
} 
?>
</td>
</tr>
<?php } ?> 


   
  <tr>
  <th scope="row"><label for="vx_cookies">
  <?php _e('Disable Cookies', 'contact-form-entries'); ?>
  </label>
  </th>
  <td>
<label for="vx_cookies"><input type="checkbox" name="meta[cookies]" value="yes" <?php if(vxcf_form::post('cookies',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_cookies"><?php _e('Check this to disable user tracking cookies. This will disable Related Entries and Form Abandonment feature','contact-form-entries'); ?></label>
  </td>
  </tr>
  
   <tr>
  <th scope="row"><label for="vx_ip">
  <?php _e('Disable User Details', 'contact-form-entries'); ?>
  </label>
  </th>
  <td>
<label for="vx_ip"><input type="checkbox" name="meta[ip]" value="yes" <?php if(vxcf_form::post('ip',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_ip"><?php _e('Check this to disable IP address and User Agent(Browser ) tracking.','contact-form-entries'); ?></label>
  </td>
  </tr>
  
  <tr>
  <th scope="row"><label for="vx_plugin_data">
  <?php _e("Plugin Data", 'contact-form-entries'); ?>
  </label>
  </th>
  <td>
<label for="vx_plugin_data"><input type="checkbox" name="meta[plugin_data]" value="yes" <?php if(vxcf_form::post('plugin_data',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_plugin_data"><?php _e('Remove all plugin data during plugin uninstall','contact-form-entries'); ?></label>
  </td>
  </tr>
  
    <tr>
  <th scope="row"><label for="vx_sep">
  <?php _e('CSV Separator ', 'contact-form-entries'); ?>
  </label>
  </th>
  <td>
<label for="vx_sep"><input type="text" name="meta[sep]" placeholder="<?php _e(',','contact-form-entries'); ?>" value="<?php echo vxcf_form::post('sep',$meta) ?>" id="vx_sep" class="crm_text"></label>
  </td>
  </tr>
  
  </table>
  <?php
  do_action('add_settings_section_'.vxcf_form::$id,$meta,$forms);
  ?> 
   <p class="submit">
   <button type="submit" value="save" class="button-primary" title="<?php _e('Save Changes','contact-form-entries'); ?>" name="save"><?php _e('Save Changes','contact-form-entries'); ?></button>
  <input type="hidden" name="vx_meta" value="1"> 
 </p>
  </form>

  <hr>
  <h2><?php _e('Short Codes', 'contact-form-entries'); ?></h2>
 <table class="form-table">
    <tr><th>[vx-entries]</th>
  <td>
  
    <p><strong><?php _e('Display form entries in a table', 'contact-form-entries'); ?></strong></p>
  <ul style="list-style: square; margin-left: 20px;">

  <li><code>form-id</code> - <?php _e('Specify form-id (e.g., form-id="cf_8")', 'contact-form-entries'); ?></li>
  <li><code>form-name</code> or <code>form-id</code> - <?php _e('At least one of these attributes is required, others are optional', 'contact-form-entries'); ?></li>
  <li><code>col-start</code> - <?php _e('Identify which column will start the table (e.g., col-start="2")', 'contact-form-entries'); ?></li>
  <li><code>cols</code> - <?php _e('Specify how many columns the table will display (e.g., cols="6"), Do not set this parameter if you are using col-labels', 'contact-form-entries'); ?></li>
  <li><code>col-labels</code> - <?php _e('Using labels, identify which columns the table will display (e.g., col-labels="Last name, First name")', 'contact-form-entries'); ?></li>
  <li><code>font-size</code> - <?php _e('List the table font size', 'contact-form-entries'); ?></li>
  <li><code>class</code> - <?php _e('Set the class name of the table', 'contact-form-entries'); ?></li>
  <li><code>id</code> - <?php _e('Specify the id of the table', 'contact-form-entries'); ?></li>
  
  <li><code>number-col</code> - <?php _e('If you\'d like to insert serial column numbers, set number-col to number-col="true"', 'contact-form-entries'); ?></li>
  
  <li><code>number-col-width</code> - <?php _e('Set the column width (e.g., number-col-width="30")', 'contact-form-entries'); ?></li>
  
  <li><code>start</code> - <?php _e('Specify which row will start the table (e.g., start="0")', 'contact-form-entries'); ?></li>
  
  <li><code>limit</code> - <?php _e('List the total number of records the table will contain (e.g., limit="300)', 'contact-form-entries'); ?></li>
  
  
  <li><code>per-page</code> - <?php _e('Specify how many records the table will have per page (e.g., per-page="20")', 'contact-form-entries'); ?></li>
  
  <li><code>sortable</code> - <?php _e('If you want table columns to be sortable, set sortable to sortable="1"', 'contact-form-entries'); ?></li>
  
  <li><code>pager</code> - <?php _e('If it is sortable, the table is also able to be paginated (e.g., pager="1")', 'contact-form-entries'); ?></li>
  <li><code>user-id</code> - <?php _e('show entries from specific user id , leave empty for logged in user (e.g., user-id="2" , user-id="")', 'contact-form-entries'); ?></li>
  <li><code>search</code> - <?php _e('Add search table field - premium feature (e.g., search="1")', 'contact-form-entries'); ?></li>
  <li><code>export</code> - <?php _e('Add search table field - premium feature (e.g., export="1")', 'contact-form-entries'); ?></li>

   <li> <p><strong><?php _e('Get ShortCode', 'contact-form-entries'); ?></strong></p>
 <p> 
<select id="entries_form_code" style="width: 100%;">
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
 // if($form_id == $form_id_arr)
 // $sel="selected='selected'";
  echo "<option value='".$form_id_arr."' $sel>".$form_title."</option>"; 
    }      
  }
  ?>
  </optgroup>
  <?php
     } }
  ?>
  </select></p>
  <p> <textarea style="width: 100%; height: 50px;" readonly="readonly" id="vx_entries_code_text"></textarea></p> </li>
  </ul>

  </td></tr> 

    
</table>
    <?php
  do_action('add_section_'.vxcf_form::$id);
  ?>  
        <script type="text/javascript">
    (function($){ 
        var id=$('#entries_form_code').val();
        add_code(id);

        $(document).on('change', '#entries_form_code', function(e) { 
            e.preventDefault();
var id=$(this).val();
        add_code(id);   
        });
        function add_code(id){
            var str='[vx-entries form-id="'+id+'" start="0" limit="300" per-page="30" cols="6" sortable="true" pager="true"]';
            $('#vx_entries_code_text').val(str);
        }

}(jQuery));
        </script>


  </div>

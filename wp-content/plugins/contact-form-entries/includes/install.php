<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_form_install' ) ):

class vxcf_form_install{
      public static $sending_req=false;
public function get_roles(){
      $roles=array(
      vxcf_form::$id."_read_entries" , 
      vxcf_form::$id."_read_license" , 
      vxcf_form::$id."_read_settings" , 
      vxcf_form::$id."_edit_entries" , 
      vxcf_form::$id."_edit_settings" 
      );
      return $roles;

}
public function create_roles(){
      global $wp_roles;
      if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }
$roles=$this->get_roles();
foreach($roles as $role){
  $wp_roles->add_cap( 'administrator', $role );
}
$wp_roles->add_cap( 'administrator', 'vx_crmperks_view_plugins' );
$wp_roles->add_cap( 'administrator', 'vx_crmperks_view_addons' );
$wp_roles->add_cap( 'administrator', 'vx_crmperks_edit_addons' );
}

public function remove_roles(){
      global $wp_roles;
      if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }
$roles=$this->get_roles();
foreach($roles as $role){
  $wp_roles->remove_cap( 'administrator', $role );
}
}
public function remove_data(){
    global $wpdb;

  //delete options
  delete_option(vxcf_form::$type."_version"); 
  delete_option(vxcf_form::$type."_updates");
  delete_option(vxcf_form::$type."_install_data");
  delete_option('vxcf_all_forms');
  delete_option('vxcf_all_fields');

  delete_option(vxcf_form::$id."_meta");
    $data=vxcf_form::get_data_object();
  $data->drop_tables();
  $this->remove_roles();
  

  $this->deactivate_plugin();
}
public function deactivate_plugin(){
        $slug=$this->get_slug();
          //deactivate 
  deactivate_plugins($slug); 
    update_option('recently_activated', array($slug => time()) + (array)get_option('recently_activated'));
}
public function create_upload_dir(){
$upload= vxcf_form::get_upload_dir();
   
$htaccess = <<<XML
# BEGIN CRM Perks
# Disable parsing of PHP for some server configurations.

<Files *>
  SetHandler none
  SetHandler default-handler
  Options -ExecCGI
  RemoveHandler .cgi .php .php3 .php4 .php5 .phtml .pl .py .pyc .pyo
</Files>
<IfModule mod_php5.c>
  php_flag engine off
</IfModule>
# END CRM Perks
XML;
     
         $files = array(
            array(
                'base'         => $upload['basedir'].'/'.$upload['folder_name'],
                'file'         => 'index.html',
                'content'     => ''
            ),
             array(
                'base'         => $upload['basedir'].'/'.$upload['folder_name'],
                'file'         => '.htaccess',
                'content'     => $htaccess
            ),
             array(
                'base'         => $upload['dir'],
                'file'         => 'index.html',
                'content'     => ''
            )
        );

        foreach ( $files as $file ) {
            if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
                if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
                    fwrite( $file_handle, $file['content'] );
                    fclose( $file_handle );
                }
            }
        }
}

}

endif;

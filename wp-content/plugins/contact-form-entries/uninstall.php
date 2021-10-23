<?php
/**
 * Uninstall
 */
 if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}
$path=plugin_dir_path(__FILE__);
include_once($path . "contact-form-entries.php");
 include_once($path . "includes/install.php");
   $install=new vxcf_form_install();
   $settings=get_option(vxcf_form::$id.'_meta',array());
if(!empty($settings['plugin_data'])){
  $install->remove_data();
}

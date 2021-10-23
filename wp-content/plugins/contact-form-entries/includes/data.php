<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_form_data' ) ) {

/**
* since 1.0
*/
class vxcf_form_data{
/**
* creates or updates tables
* 
*/
  public  function update_table(){
  global $wpdb;
  
  $wpdb->hide_errors();
  $table_name = $this->get_crm_table_name();
  
  require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    
 $charset_collate='';
        if ( $wpdb->has_cap( 'collation' ) ) {
            $charset_collate = $wpdb->get_charset_collate();
        }
  
  $sql = "CREATE TABLE $table_name (
  `id` bigint(20) unsigned not null auto_increment,
  `form_id` varchar(50) not null,
  `status` int(4) NOT NULL default 0,
  `type` int(4) NOT NULL DEFAULT 0,
  `is_read` BOOLEAN NOT NULL default 0,
  `is_star` BOOLEAN NOT NULL default 0,
  `user_id` bigint(20) unsigned NOT NULL default 0,
  `ip` varchar(50),
  `browser` varchar(50),
  `screen` varchar(50),
  `os` varchar(50),
  `vis_id` varchar(250),
  `url` varchar(250),
  `meta` text ,
  `created` datetime,
  `updated` datetime,
  KEY form_id (form_id),
  KEY status (status),
  KEY type (type),
  PRIMARY KEY (id)
  )$charset_collate; ";
  
  // dbDelta($sql);
   
  $table_name = $this->get_crm_table_name('detail');
  
  $sql.= "CREATE TABLE $table_name (
  `id` bigint(20) unsigned not null auto_increment,
  `lead_id` bigint(20) unsigned not null default 0,
  `name` varchar(250) not null,
  `value` longtext ,
 KEY lead_id (lead_id),
  KEY name (name(190)),
  PRIMARY KEY  (id)
  )$charset_collate;";
  
 //  dbDelta($sql);
   
           ////////////////notes table
     $table_name = $this->get_crm_table_name('notes');
     
    $sql.= "CREATE TABLE $table_name (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `lead_id` bigint(20) unsigned NOT NULL default 0,
        `user_id` bigint(20) unsigned NOT NULL default 0,
        `color` int(1) NOT NULL default 0,
        `email` varchar(150) NULL,   
        `note` text,
        `created` datetime,
        KEY lead_id (lead_id),
        PRIMARY KEY (id)
    ) $charset_collate;";
 
    dbDelta($sql);
  }
  public function create_lead($fields,$data){
          global $wpdb;
    $date=current_time( 'mysql' );
    $lead_arr=array('created'=>$date,'updated'=>$date,'form_id'=>$data['form_id'],'ip'=>$data['ip'],'user_id'=>$data['user_id']);
    $leads = $this->get_crm_table_name();
    $detail = $this->get_crm_table_name('detail');
  // $wpdb->show_errors();
   $lead=$wpdb->insert($leads,$lead_arr);
   $entry_id=0;
   if($lead){
   $entry_id=$wpdb->insert_id; 
   foreach($fields as $k=>$v){
      //insert field
   $field_arr=array('lead_id'=>$entry_id,'name'=>$k,'value'=>$v);
   $wpdb->insert($detail,$field_arr);    
   } 
   } 
   return $entry_id;
  }
public function update_lead($update,$insert,$lead_id='',$lead=array()){
          global $wpdb;
     $date=current_time( 'mysql' );
    $lead['updated']=$date;
    $leads= $this->get_crm_table_name();
    $detail= $this->get_crm_table_name('detail');
    if(empty($lead_id)){
        $lead['created']=$date;
   $wpdb->insert($leads,$lead);
    $lead_id=$wpdb->insert_id; 
    }else{
   $wpdb->update($leads,$lead,array('id'=>$lead_id));
    }

   if(!empty($update) && !empty($lead_id) ){
       foreach($update as $k=>$v){
 $v=is_array($v) ? serialize($v) : $v;
 $wpdb->update($detail,array('value'=>$v),array('id'=>$k,'lead_id'=>$lead_id));  
       }
   }

   if(!empty($insert) && !empty($lead_id)){
       foreach($insert as $k=>$v){
           $v=is_array($v) ? serialize($v) : $v;
           $arr=array('lead_id'=>$lead_id,'name'=>$k,'value'=>$v);
        $wpdb->insert($detail,$arr);   
       }
   }
 return $lead_id;
  }
  public function get_lead($lead_id){
          global $wpdb;
    $leads = $this->get_crm_table_name();

  $sql=$wpdb->prepare("Select * from {$leads} where id=%d limit 1",$lead_id);
  return $wpdb->get_row($sql,ARRAY_A);
 
  }
    public function get_lead_details_by_id($lead_ids){
          global $wpdb;
    $table= $this->get_crm_table_name();

    $ids=array();
    if(is_array($lead_ids) && count($lead_ids)>0){
     foreach($lead_ids as $id){
       $id=(int)$id;
       if(!empty($id)){
      $ids[]=$id;     
       }  
     }   
    }
  $leads=array();  
  if(count($ids)>0){  
   $sql="Select * from {$table} where id in(".implode(',',$ids).") limit 1";
 $leads=$wpdb->get_results($sql,ARRAY_A);
  }
 return $leads;
  }
public function get_lead_counts($form_id){
          global $wpdb;
    $leads = $this->get_crm_table_name();
    if(is_array($form_id)){
  $search=" l.form_id in ('".implode("','",$form_id)."')";      
    }else{
  $search=" l.form_id='".$form_id."'";      
    }
$res=array();
 $sql_all="SELECT count(distinct l.id) FROM {$leads} l where $search and l.status=0";
   $res['all']= $wpdb->get_var($sql_all); 
   
   $sql_star="SELECT count(distinct l.id) FROM {$leads} l where $search and l.is_star=1 and l.status=0";
   $res['starred']= $wpdb->get_var($sql_star);   
    
   $sql_star="SELECT count(distinct l.id) FROM {$leads} l where $search and l.is_star=0 and l.status=0";
  // $res['nonstarred']= $wpdb->get_var($sql_star); 
     
   $sql_unread="SELECT count(distinct l.id) FROM {$leads} l where $search and l.is_read=0 and l.status=0";
     $res['unread']= $wpdb->get_var($sql_unread);  
      
        $sql_trash="SELECT count(distinct l.id) FROM {$leads} l where $search and l.status=1";
    $res['trash']= $wpdb->get_var($sql_trash);            
return $res;
}
public function get_unread_total(){
         global $wpdb;
    $leads = $this->get_crm_table_name();
   $sql_unread="SELECT count(distinct l.id) FROM {$leads} l where l.is_read=0 and l.status=0";
   return $wpdb->get_var($sql_unread);   
}
public function get_leads_count_by_form($req=''){
    if(empty($req)){ $req=$_REQUEST; }
         global $wpdb;
$leads = $this->get_crm_table_name();
$sql_unread="SELECT count(distinct l.id) as total,form_id FROM {$leads} l where l.is_read=0 and l.status=0 ";
$time_key=vxcf_form::post('time',$req);
  // handle search
$sql_unread=$this->add_time_sql($sql_unread,$time_key);
$sql_unread.=' group by form_id';
$arr=array();
$arr['unread']=$wpdb->get_results($sql_unread,ARRAY_A);
$sql_unread="SELECT count(distinct l.id) as total,form_id FROM {$leads} l where l.status=0 ";
$sql_unread=$this->add_time_sql($sql_unread,$time_key);
$sql_unread.=' group by form_id';
$arr['total']=$wpdb->get_results($sql_unread,ARRAY_A);
$res=array();
foreach($arr as $k=>$forms){
  $forms_k=array();
    if(!empty($forms)){
foreach($forms as $v){
$forms_k[$v['form_id']]=$v['total'];
}  
$res[$k]=$forms_k;    
  }  }
return $res;      
}
public function get_entries($form_id,$per_page=20,$req=''){
if(empty($req)){ $req=$_REQUEST; }
          global $wpdb;
    $leads_table = $this->get_crm_table_name();
    $detail = $this->get_crm_table_name('detail');
$main_fields=array('vxurl','vxscreen','vxbrowser','vxcreated','vxupdated');
    do_action('vxcf_entries_before_query');
    
     $status=vxcf_form::post('status',$req); 
     if(!empty($form_id)){
         if(is_array($form_id)){
  $form_id_q=' l.form_id in ("'.implode('","',$form_id).'")';      
    }else{
      $form_id_q=" l.form_id ='".esc_sql($form_id)."'";
    }
     }else{ 
       $form_id_q=" l.form_id !=''"; 
     }
    $search=$form_id_q;
$status_f=0;
 if($status == 'trash'){
$status_f=1;    
}
  $search.=' and l.status ='.$status_f.''; 
if($status == 'unread'){
 $search.=' and l.is_read =0'; 
}

if($status == 'starred'){
 $search.=' and l.is_star =1'; 
}

if(isset($req['type'])){  $search.=' and l.type ='.(int)vxcf_form::post('type',$req);  }
if(isset($req['user_id'])){  $search.=' and l.user_id ='.(int)vxcf_form::post('user_id',$req);  }

  // handle search
$search=$this->add_time_sql($search,$req);   

    if(vxcf_form::post('search',$req)!=""){
          $search_s=esc_sql(vxcf_form::post('search',$req));
    if(!empty($req['field']) && in_array($req['field'],$main_fields)){
    $main_field=trim($req['field'],'vx');
   $search.=' and l.'.$main_field.' like "%'.$search_s.'%"';         
        }else{
$search_d='select distinct l.id from '.$leads_table.' l inner join '.$detail.' d on (l.id=d.lead_id) where '.$form_id_q;

$search_d.=' and d.value like "%'.$search_s.'%"';  
 
if(!empty($req['field'])){
    if(is_array($req['field']) ){
   $search_fields=array();
 foreach($req['field'] as $v){
  $search_fields[]=esc_sql($v);   
 }
 $search_d.=!empty(vxcf_form::$sql_field_name) ? vxcf_form::$sql_field_name : ' and d.name in( "'.implode('","',$search_fields).'") ';
        
    }else{
  $field=esc_sql(vxcf_form::post('field',$req));
$search_d.=!empty(vxcf_form::$sql_field_name) ? vxcf_form::$sql_field_name : ' and d.name = "'.$field.'"';  
    }
}
$search.=' and l.id in ('.$search_d.')';    
  
        } }
  
$order='DESC';
  if(vxcf_form::post('order',$req)!=""){
  if(vxcf_form::post('order',$req)!="" && in_array(vxcf_form::post('order',$req),array("asc","desc"))){
  $order=vxcf_form::post('order',$req); 
  }
  }
  if(!empty(vxcf_form::$sql_where)){
 $search.=vxcf_form::$sql_where;     
  }
  
 /*       $fields_sql=array();
    if(is_array($fields) && count($fields)>0){
        foreach($fields as $k=>$v){
     $fields_sql[]=" max(if(d.name='{$k}', d.value, null )) AS '{$k}' ";       
        }
    }
  //implode(', ',$fields_sql)*/
  $items='';   $start = 0; $pages=0;
  if(empty($req['vx_links'])){
$sql_t="SELECT count(*) FROM {$leads_table} l where $search";
  $items=$wpdb->get_var($sql_t);
  if(empty($per_page)){
    $per_page=20;
}

  if($per_page !='all'){
  $pages = ceil($items/$per_page);
  }
  }

  
  if(isset($_GET['page_id']))
  {
  $page=$_GET['page_id'];
  $start = $page-1;
  $start = $start*$per_page;
  }
  $start=max($start,0);
  $sql_fields='l.*';
if(!empty(vxcf_form::$sql_select_fields)){ $sql_fields.=vxcf_form::$sql_select_fields; }

$sql="SELECT  $sql_fields from $leads_table l ".vxcf_form::$sql_join;
     $order_by='l.id';
     if(!empty(vxcf_form::$sql_order_by)){
       $order_by=vxcf_form::$sql_order_by;  
     }else{
 if(!empty($_GET['orderby'])){
     $order_by='d.value';
     if(in_array($_GET['orderby'],$main_fields) ){
     $order_by='l.'.ltrim($_GET['orderby'],'vx');     
     }else{
 $sql.=" left join $detail d on (l.id = d.lead_id)";   
 $search.=!empty(vxcf_form::$sql_field_name) ? vxcf_form::$sql_field_name : ' and d.name="'.esc_sql(vxcf_form::post('orderby')).'" ';     
     }
 }  }
//$per_page=2; implode(', ',$fields_sql)
if(!empty($search)){
  $sql.=" where $search";  
}
$sql.=" group by l.id ";
$sql.=" order by $order_by $order "; 
//$per_page=20;
 if($per_page!='all'){
 $sql.=" LIMIT {$start},{$per_page}";  
 }
// $sql='SELECT l.* from wp_vxcf_leads l left join wp_vxcf_leads_detail d on (l.id = d.lead_id) left join tickets t on(l.id=t.entry_id) where l.form_id ="cf_6" and l.status ="0" and d.value like "%bioinfo35@gmail.com%" and d.name = "your-email" and t.status ="open" and t.priority="normal" group by d.lead_id order by l.id DESC LIMIT 0,20';
//echo $sql.'<hr>';            
$results=$wpdb->get_results($sql, ARRAY_A);  
//echo json_encode($results); die();   
 // $re = $wpdb->get_results('SELECT FOUND_ROWS();', ARRAY_A);     

             $leads=array();
if(isset($results) && is_array($results) && count($results)>0){
foreach($results as $v){ 
$ids[]=$v['id'];
   $leads[$v['id']]=$v;    
}
       
if(!empty(vxcf_form::$form_fields)){
 $sql_d="SELECT id,lead_id";   
 foreach(vxcf_form::$form_fields as $k=>$v){
     if(!empty($v['is_main'])){ continue; }
     $k=$v['name'];
 $sql_d.=", MAX(if(`name`='$k', `value`, null )) AS '".$k."_field'";   
 }
 $sql_d.=' FROM '.$detail.' where lead_id in('.implode(',',$ids).')  GROUP BY `lead_id` ';   

$res= $wpdb->get_results($sql_d, ARRAY_A);
//var_dump($res,vxcf_form::$form_fields);  
//echo $sql_d.'-----<hr>'.json_encode($res).'<-------'.$wpdb->last_error.'----<hr>'; //die();
if(!empty($res)){
    foreach($res as $v){
   if(!empty($v['lead_id']) && !empty($leads[$v['lead_id']])){
   $leads[$v['lead_id']]['detail']=$v;    
   }     
    }
}
}          
  /*     foreach($results as $v){ 
   $leads[]=$this->attach_lead_detail($v);    
       } */ 
}
 //var_dump($leads); die(); 
      $range_min=$range_max=$page_links='';
       if(empty($req['vx_links'])){

  $page_id=isset($_REQUEST['page_id'])&& $_REQUEST['page_id'] !="" ? $_REQUEST['page_id'] : "1";
  if(is_numeric($per_page) && !empty($per_page)){
  $range_min=(int)($per_page*($page_id-1))+1;
  $range_max=(int)($per_page*($page_id-1))+count($leads);
  }
  unset($_GET['page_id']);
  $query_h=http_build_query($_GET);
  $page_links = paginate_links( array(
  'base' =>  admin_url("admin.php")."?".$query_h."&%_%" ,
  'format' => 'page_id=%#%',
  'prev_text' =>'&laquo;',
  'next_text' =>'&raquo;',
  'total' => $pages,
  'current' => $page_id,
  'show_all' => false
  ));
       }
  return array("min"=>$range_min,"max"=>$range_max,"items"=>$items,"links"=>$page_links,'result'=>$leads);
  //
  }
public function add_time_sql($search,$req){
      if(is_array($req)){
    $time_key=vxcf_form::post('time',$req);
      }else{ $time_key=$req; }
  $time=current_time('timestamp');

    $offset = vxcf_form::time_offset();
  $start_date=""; $end_date="";
  switch($time_key){
  case"today": $start_date=strtotime('today',$time);  break;
  case"this_week": $start_date=strtotime('last sunday',$time);  break;
  case"last_7": $start_date=strtotime('-7 days',$time);  break;
  case"last_30": $start_date=strtotime('-30 days',$time); break;
  case"this_month": $start_date=strtotime('first day of 0 month',$time);  break;
  case"yesterday": 
  $start_date=strtotime('yesterday',$time);
  $end_date=strtotime('today',$time);  

  break;
  case"last_month": 
  $start_date=strtotime('first day of -1 month',$time); 
  $end_date=strtotime('last day of -1 month',$time); 

  break;
  case"custom":
  $start_date='';
   if(!is_array($req)){
     $req=$_GET;  
   }
  if(!empty($req['start_date'])){ 
  $start_date=strtotime(vxcf_form::post('start_date',$req).' 00:00:00');
  }
   if(!empty($req['end_date'])){
  $end_date=strtotime(vxcf_form::post('end_date',$req).' 23:59:59');
   } 
  break;
  }
  
  if($start_date!=""){
    // $start_date-=$offset;
  $search.=' and l.created >="'.date('Y-m-d H:i:s',$start_date).'"';   
  }
  if($end_date!=""){
      //  $end_date-=$offset;
      if($time_key == "yesterday"){
  $search.=' and l.created <"'.date('Y-m-d H:i:s',$end_date).'"';
      }else{
  $search.=' and l.created <="'.date('Y-m-d H:i:s',$end_date).'"';
      }   
  }
return $search;
}
public function get_lead_notes($id){
         global $wpdb;
 $table_n= $this->get_crm_table_name('notes');
  $table_u= $wpdb->base_prefix . 'users';
  $id=(int)$id;
  $sql="select n.id,n.note,n.color,n.email,n.created,n.user_id,u.display_name from $table_n n left join $table_u u on(n.user_id=u.ID) where n.lead_id='$id' limit 300";
  return $wpdb->get_results( $sql,ARRAY_A );
  }
  public function add_note($note,$id){
           global $wpdb;
    $notes_table= $this->get_crm_table_name('notes');
 if(!empty($id)){
     $wpdb->update($notes_table, $note,array('id'=>$id));   
    }else{
  $res=$wpdb->insert($notes_table, $note);   
 $id=$wpdb->insert_id;
    }
 return $id;   
 }
   public function get_note($id){
          global $wpdb;
 $table = $this->get_crm_table_name('notes');
  $sql= $wpdb->prepare('SELECT * FROM '.$table.' where id = %d limit 1',$id);
$results = $wpdb->get_row( $sql ,ARRAY_A );


return $results;
 }
    public  function delete_note($id){
  global $wpdb;
  $table_name = $this->get_crm_table_name('notes');
$sql=$wpdb->prepare("DELETE FROM $table_name WHERE id=%d",$id);
return  $wpdb->query($sql);
  
  }
  public function lead_actions($action,$leads){
        global $wpdb;
    $leads_table = $this->get_crm_table_name();
    $ids=implode(',',$leads);
    $key=key($action);
    $val=$action[$key];
  $sql="update {$leads_table} l set l.{$key}='{$val}'  where l.id in({$ids})"; //die();
  return $wpdb->query($sql);
}
public function get_lead_detail($lead_id){
          global $wpdb;
    $detail_table = $this->get_crm_table_name('detail');
   $sql=$wpdb->prepare("Select * from {$detail_table} where lead_id=%d",$lead_id);
  $detail_arr=$wpdb->get_results($sql,ARRAY_A);
 
  $detail=array();
if(is_array($detail_arr)){
  foreach($detail_arr as $v){
 $detail[$v['name']]=$v;     
  }  
}
return $detail;
}
public function search_lead_detail($value,$form_id,$entry_id=''){
          global $wpdb;
    $table = $this->get_crm_table_name();
    $detail_table = $this->get_crm_table_name('detail');
   $sql=$wpdb->prepare(" SELECT d.*,l.form_id FROM {$detail_table} d left join {$table} l on(d.lead_id=l.id) where l.form_id=%s and d.value like %s and l.type=0",array($form_id,'%'.$value.'%'));
   if(!empty($entry_id)) {$sql.=' and l.id!='.$entry_id;  }
  $detail_arr=$wpdb->get_row($sql,ARRAY_A);
return $detail_arr;
}
public function get_related_leads($vis_id,$id){
          global $wpdb;
    $table = $this->get_crm_table_name();
   $sql=$wpdb->prepare("Select * from {$table} where vis_id=%s and id!=%d order by id desc limit 20",array($vis_id,$id));
  return $wpdb->get_results($sql,ARRAY_A);
}
public function attach_lead_detail($lead){

    $detail=array();
    if(!empty($lead['id'])){
          global $wpdb;
    $detail_table = $this->get_crm_table_name('detail');
   $sql=$wpdb->prepare("Select * from {$detail_table} where lead_id=%d",$lead['id']);
  $detail_arr=$wpdb->get_results($sql,ARRAY_A);
if(is_array($detail_arr)){
  foreach($detail_arr as $v){
 $detail[$v['name']]=$v['value'];     
  }  
}
    }
 $lead['detail']=$detail;   
return $lead;
  }
    /**
  * delete leads
  * 
  * @param mixed $id
  */
public  function delete_leads($leads){
  global $wpdb;
    $ids=implode(',',$leads);
  $table_name = $this->get_crm_table_name();
  $wpdb->query("DELETE FROM $table_name WHERE id in($ids)");
  //
    $table_name = $this->get_crm_table_name('detail');
  $wpdb->query("DELETE FROM $table_name WHERE lead_id in($ids)");
  
      $table_name = $this->get_crm_table_name('notes');
  $wpdb->query("DELETE FROM $table_name WHERE lead_id in($ids)");
}
public function  get_vis_info_of_day($vis_id,$form_id,$type='0'){
        global $wpdb;
 $table=$this->get_crm_table_name();
 $time=date('Y-m-d H:i:s');
 $start=date('Y-m-d H:i:s',strtotime('-1 day'));
$sql=$wpdb->prepare("select id from $table where vis_id=%s and updated>%s and form_id=%s and type=%d order by id desc limit 1",$vis_id,$start,$form_id,$type);
return $wpdb->get_var($sql); 
}   
  /**
  * Get tables names
  * 
  * @param mixed $table
  */
  public  function get_crm_table_name($table=""){
  global $wpdb;
  if(!empty($table))
  return $wpdb->prefix .  vxcf_form::$id."_".$table;
  else
  return $wpdb->prefix . vxcf_form::$id;
  }

  /**
  * drop tables
  * 
  */
  public  function drop_tables(){
  global $wpdb;
  $wpdb->query("DROP TABLE IF EXISTS " . $this->get_crm_table_name());
  $wpdb->query("DROP TABLE IF EXISTS " . $this->get_crm_table_name('detail'));
  $wpdb->query("DROP TABLE IF EXISTS " . $this->get_crm_table_name('notes'));
  delete_option(vxcf_form::$type."_version");
  }
}
}
?>
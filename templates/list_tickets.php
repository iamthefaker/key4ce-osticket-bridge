<?php
/* Template Name: list_tickets.php */
?>
<style>
	.entry-content{max-width: 1100px !important;}
</style>
<script type="text/javascript">
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
  jQuery( document ).ready(function() {
  jQuery('#key4ce_ticket_list .noEdit').click(function(e){
   e.stopPropagation();
});
});
</script>
<?php if($keyost_usercloseticket==0) { ?>
<style>
#key4ce_ticket_menu1
{
	width: 8.5% !important;
}
#key4ce_ticket_list1
{
	width: 8.5% !important;
}
#key4ce_ticket_menu2
{
    width: 47% !important;
}
#key4ce_ticket_list2
{
    width: 47% !important;
}
#key4ce_ticket_menu4
{
	width:11% !important;
}
#key4ce_ticket_list4
{
	width:11% !important;
}
#key4ce_ticket_menu5
{
	width:11% !important;
}
#key4ce_ticket_list5
{
	width:11% !important;
}
#key4ce_ticket_menu6
{
	width:250px !important;
}
#key4ce_ticket_list6
{
	width:250px !important;
}
</style>
<?php } ?>
<?php
$time_format=key4ce_getKeyValue('time_format');
$date_format=key4ce_getKeyValue('date_format');
$datetime_format=key4ce_getKeyValue('datetime_format');
if(isset($_POST['close']))
{		
	$close_ticket_list=$_POST['tickets'];
	$i=0;
	foreach($close_ticket_list as $close_ticket)
	{				
		if($keyost_version==194 || $keyost_version==195 || $keyost_version==1951)
			$ost_wpdb->update($ticket_table, array('status_id'=>'3'), array('ticket_id'=>$close_ticket), array('%s'));
		else
			$ost_wpdb->update($ticket_table, array('status'=>'closed'), array('ticket_id'=>$close_ticket), array('%s'));
		$i++;
	}
	echo "<div style=' color: red;font-size: 15px;font-weight: bold;margin-top: 20px;text-align: center;'>"._e( "$i record(s) has been closed successfully", 'key4ce-osticket-bridge' )."</div>";
	echo "<script>window.location.href=location.href;</script>";	
}
if(@$list_opt) { 
?>
<form name="osticket" id="osticket" method="post" onSubmit="if(!confirm('<?php echo __("Are you sure you want to continue?", 'key4ce-osticket-bridge'); ?>')){return false;}">
<div class="">
<div id="key4ce_ticket_menu">
<?php if($keyost_usercloseticket==1) { ?>
<div id="key4ce_ticket_menu0"><input type="checkbox"  onchange="checkAll(this)" name="chk[]"></div>
<?php } ?>
<div id="key4ce_ticket_menu1"><?php echo __("Tickets #", 'key4ce-osticket-bridge'); ?></div>
<div id="key4ce_ticket_menu2"><?php echo __("Subject", 'key4ce-osticket-bridge'); ?></div>
<div id="key4ce_ticket_menu3"><?php echo __("Status", 'key4ce-osticket-bridge'); ?></div>
<div id="key4ce_ticket_menu5"><?php echo __("Date", 'key4ce-osticket-bridge'); ?></div>
</div>
<?php
	function ezDate($d) { 
        $ts = time() - strtotime(str_replace("-","/",$d)); 
        if($ts>31536000) $val = round($ts/31536000,0).' year'; 
        else if($ts>2419200) $val = round($ts/2419200,0).' month'; 
        else if($ts>604800) $val = round($ts/604800,0).' week'; 
        else if($ts>86400) $val = round($ts/86400,0).' day'; 
        else if($ts>3600) $val = round($ts/3600,0).' hour'; 
        else if($ts>60) $val = round($ts/60,0).' minute'; 
        else $val = $ts.' second'; 
        if($val>1) $val .= 's'; 
        return $val; 
	} 
	foreach($list_opt as $list) 
	{ 
	if($list->updated=="0000-00-00 00:00:00") 
	{ 
	$list_updated="-"; 
	} 
	else{ 
	$list_updated=ucwords(ezDate($list->updated)).' Ago'; 
	} 
	if(isset($_REQUEST['page_id']))
	{
		$ticket_view=get_permalink()."&service=view&ticket=".$list->number;
		$ticket_tr="&service=view&ticket=".$list->number;
	}
	else
	{
		$ticket_view=get_permalink()."?service=view&ticket=".$list->number;
		$ticket_tr="?service=view&ticket=".$list->number;
	}
  
	@$sub_str=Format::stripslashes($list->subject); 			
	echo "<div id='key4ce_ticket_list' onclick=\"location.href='$ticket_view';\">"; 
	if($keyost_usercloseticket==1)	
	echo "<div id='key4ce_ticket_list0' class=\"noEdit\"><input type='checkbox' name='tickets[]' value='".$list->ticket_id."'></div>";
	echo "<div id='key4ce_ticket_list1'><a href=$ticket_view>".$list->number."</a></div>"; 
	echo "<div id='key4ce_ticket_list2'>".key4ce_truncate($sub_str,60,'...')."</div><div id='key4ce_ticket_list3'>"; 
	if($list->status=='closed') { 
	echo '<font color=red>'.__("Closed", 'key4ce-osticket-bridge').'</font>'; 
	} 
	elseif ($list->status=='open' && $list->isanswered=='0') { 
	echo '<font color=green>'.__('Open', 'key4ce-osticket-bridge').'</font>'; 
    }
	elseif ($list->status=='open' && $list->isanswered=='1') { 
	echo '<font color=orange>'.__("Answered", 'key4ce-osticket-bridge').'</font>'; 
	} 
	echo "</div>"; 
    if ($list->updated=='0000-00-00 00:00:00') {
		$input_str  = "".$list->created.""; }
		else {
   	$input_str  = "".$list->updated.""; }
   	echo "<div id='key4ce_ticket_list5'>"; 		echo date($datetime_format, strtotime($input_str));
   	echo "</div>"; 
	//echo "<div style='clear: both; display: table-cell;'></div>";
	echo "</div>";
	} } 
	else 
	{ 
	echo '</div><div style="display: table; width: 100%;"><div align="center" id="no_tics" style="margin-top: 25px; text-align: center; font-size: 12pt; width: 100%; display:table-cell; float: left;"> <strong>';
  if(isset($_REQUEST['status']) && $status_opt=="closed") 
  echo "You don't have any closed Support Tickets.";
  else
  echo "You don't have any open or answered Support Tickets. Click the 'Create Ticket' button.";
  echo '</strong></div>';
	} 
?>
</div>

<?php 
if($keyost_usercloseticket==1) 
{
if(@$_REQUEST['status']!="closed" && count($list_opt)>0) 
{?>
<div style=" margin-left: 13px;margin-top: 15px;"><input type="submit" name="close" value="<?php echo __('Close','key4ce-osticket-bridge'); ?>>"></div>
<?php } }?>
</form>

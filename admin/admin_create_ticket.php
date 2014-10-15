<?php
/* Template Name: admin-create-ticket.php */
require_once( WP_PLUGIN_DIR . '/key4ce-osticket-bridge/admin/db-settings.php');
require_once( WP_PLUGIN_DIR . '/key4ce-osticket-bridge/includes/functions.php'); 
$dept_opt = $ost_wpdb->get_results("SELECT dept_name,dept_id FROM $dept_table where ispublic=1");
wp_enqueue_script('ost-bridge-validate',plugins_url('../js/validate.js', __FILE__));
$alowaray = explode(".",str_replace(' ', '',getKeyValue('allowed_filetypes')));
$strplc = str_replace(".", "",str_replace(' ', '',getKeyValue('allowed_filetypes')));
$allowedExts = explode(",", $strplc);
function add_quotes($str) {
    return sprintf("'%s'", $str);
}
$extimp = implode(',', array_map('add_quotes', $allowedExts));
$finalary = "'" . $extimp . "'";
?>
<?php 
$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => '',
	'meta_key'     => '',
	'meta_value'   => '',
	'meta_compare' => '',
	'meta_query'   => array(),
	'include'      => array(),
	'exclude'      => array(),
	'orderby'      => 'login',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
	'count_total'  => false,
	'fields'       => 'all',
	'who'          => ''
 );
//$getKeyvalue=$ost_wpdb->get_results("SELECT name,address FROM ".$keyost_prefix."user usr INNER JOIN " . $keyost_prefix . "user_email usremail ON usremail.id=usr.default_email_id",ARRAY_A);
global $wpdb;
$getKeyvalue=$wpdb->get_results("SELECT user_nicename,user_email FROM ".$wpdb->prefix."users",ARRAY_A);
$data=json_encode($getKeyvalue);
?>
<script language="javascript" src="<?php echo plugin_dir_url(__FILE__) . '../js/jquery.js'; ?>"></script>
<script language="javascript" src="<?php echo plugin_dir_url(__FILE__) . '../js/jquery.autocomplete.js'; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__) . '../css/jquery.autocomplete.css'; ?>" />
<script>
$(document).ready(function(){
var data = <?php echo $data; ?>;
$("#username").autocomplete(data, {
  formatItem: function(item) {
    //return item.name;
    return item.user_nicename;	
  }
}).result(function(event, item) {
  //document.getElementById('email').value=item.address;
	document.getElementById('email').value=item.user_email;
});
});
</script>
<script language="javascript" src="<?php echo plugin_dir_url(__FILE__) . '../js/jquery_1_7_2.js'; ?>"></script>
<script type="text/javascript">
var j=jQuery.noConflict();
    j(function() {
        var addDiv = j('#addinput');
        var i = j('#addinput p').size() + 1;
        var MaxFileInputs = <?php echo getKeyValue('max_staff_file_uploads'); ?>;
        j('#addNew').live('click', function() {
            if (i <= MaxFileInputs)
            {
                j('<p><span style="color:#000;">Attachment ' + i + ':</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="file" id="p_new_' + i + '" name="file[]" onchange="return checkFile(this);"/>&nbsp;&nbsp;&nbsp;<a href="#" id="remNew">Remove</a>&nbsp;&nbsp;&nbsp;<span style="color: red;font-size: 11px;">Max file upload size : <?php echo (getKeyValue('max_file_size') * .0009765625) * .0009765625; ?>MB</span></p>').appendTo(addDiv);
                i++;
            }
            else
            {
                alert("You have exceeds your file upload limit");
                return false;
            }
            return false;
        });

        j('#remNew').live('click', function() {
            if (i > 2) {
                j(this).parents('p').remove();
                i--;
            }
            return false;
        });
    });
</script>
<script type="text/javascript">
    function checkFile(fieldObj)
    {

        var FileName = fieldObj.value;
        var FileId = fieldObj.id;
        var FileExt = FileName.substr(FileName.lastIndexOf('.') + 1);
        var FileSize = fieldObj.files[0].size;
        var FileSizeMB = (FileSize / 10485760).toFixed(2);
        var FileExts = new Array(<?php echo $extimp; ?>);
        if ((FileSize > <?php echo getKeyValue('max_file_size'); ?>))
        {
            alert("Please make sure your file is less than <?php echo (getKeyValue('max_file_size') * .0009765625) * .0009765625; ?>MB.");
            document.getElementById(FileId).value = "";
            return false;
        }
        if (FileExts.indexOf(FileExt) < 0)
        {
            error = "Please make sure your file extension should be : \n";
            error += FileExts;
            alert(error);
            document.getElementById(FileId).value = "";
            return false;
        }
        return true;
    }
</script>
<style>
    #wp-message-wrap{border:2px solid #CCCCCC;border-radius: 5px;padding: 5px;width: 75%;}
    #message-html{height: 25px;}
    #message-tmce{height: 25px;}
</style>
<div id="thContainer">
    <div id="new_ticket">
        <div id="new_ticket_text1" style="  margin-bottom: 10px;margin-top: 15px;">Create A New Ticket</div>
        <div style="clear: both"></div>
        <div id="new_ticket_text2">Please fill in the form below to open a new ticket. All fields mark with [<font color=red>*</font>] <em>Are Required!</em></div>
        <div style="clear: both"></div>
        <form id="ticketForm" name="newticket" method="post" enctype="multipart/form-data" onsubmit="return validateFormNewTicket();">
            
			<input type="hidden" name="usid" value="<?php //echo $user_id;  ?>"/>
            <input type="hidden" name="ademail" value="<?php //echo $os_admin_email; ?>"/>
            <input type="hidden" name="stitle" value="<?php //echo $title_name; ?>"/>
            <input type="hidden" name="sdirna" value="<?php //echo $dirname; ?>"/>
            <input type="hidden" name="newtickettemp" value="<?php //echo $newticket; ?>"/>
            <div id="new_ticket_name">Username:</div>
            <div id="new_ticket_name_input">
			<input name="username" type="text" id="username" size="20"/>
			</div>
            <div style="clear: both"></div>
            <div id="new_ticket_email">Your Email:</div>
            <div id="new_ticket_email_input"><input class="ost" id="email" type="text" name="email"></div>
            <div style="clear: both"></div>
            <div id="new_ticket_subject">Subject:</div>
            <div id="new_ticket_subject_input"><input class="ost" id="subject" type="text" name="subject" size="35"/><font class="error">&nbsp;*</font></div>
            <div style="clear: both"></div>
            <div id="new_ticket_catagory">Catagories:</div>
            <div id="new_ticket_catagory_input">
                <select id="deptId" name="deptId">
                    <option value="" selected="selected"> Select a Category </option>
                    <?php
                    foreach ($dept_opt as $dept) {
                        echo '<option value="' . $dept->dept_id . '">' . $dept->dept_name . '</option>';
                    }
                    ?>
                </select><font class="error">&nbsp;*</font></div>
            <div style="clear: both"></div>
            <div id="new_ticket_priority">Priority:</div>
            <div id="new_ticket_priority_input"><select id="priority" name="priorityId">
                    <option value="" selected="selected"> Select a Priority </option>
                    <?php
                    foreach ($pri_opt as $priority) {
                        echo '<option value="' . $priority->priority_id . '">' . $priority->priority_desc . '</option>';
                    }
                    ?>
                </select><font class="error">&nbsp;*</font></div>
            <div style="clear: both"></div>
    
    <table class="welcome nobd" align="center" width="95%" cellpadding="3" cellspacing="3" border="0">
        <tr>
            <td class="nobd" align="center"><div align="center" style="padding-bottom: 5px;">To best assist you, please be specific and detailed in your message<font class="error">&nbsp;*</font></div></td>
        </tr>

        <tr>
            <td class="nobd" align="center">
        <center> <?php
            $content = '';
            $editor_id = 'message';
            $settings = array('media_buttons' => false);
            wp_editor($content, $editor_id, $settings);
            ?> </center>
        <div class="clear" style="padding: 5px;"></div></td>
        </tr>
    <?php 
if (getKeyValue('allow_attachments') == 1) {
	if(getPluginValue('Attachments on the filesystem')==1)
	{
        ?>
            <tr><td>
                    <div id="addinput">
                        <p>
                            <span style="color:#000;">Attachment 1:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="file" id="p_new" name="file[]" onchange="return checkFile(this);"/>&nbsp;&nbsp;&nbsp;<a href="#" id="addNew">Add</a>&nbsp;&nbsp;&nbsp;<span style="color: red;font-size: 11px;">Max file upload size : <?php echo (getKeyValue('max_file_size') * .0009765625) * .0009765625; ?>MB</span>
                        </p>
                    </div>
                </td></tr>
    <?php } else
	{
	?>
	 <tr><td>Attachments on the Filesystem plugin can be downloaded here: <a href="http://osticket.com/download/go?dl=plugin%2Fstorage-fs.phar" title="Attachement Filesystem Plugin" target="_blank">Attachement Filesystem Plugin</a></td></tr>
	<?php
	}
	}
	?>
        <tr>
            <td class="nobd" align="center">
                <p align="center" style="padding-top: 5px;"><input type="submit" name="create-admin-ticket" value="Create Ticket">
                    &nbsp;&nbsp;<input type="reset" value="Reset"></p>
            </td>
        </tr>
    </table></form>
    </div>
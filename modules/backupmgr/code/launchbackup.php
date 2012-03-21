<?php
include($_SERVER['DOCUMENT_ROOT'] . '/cnf/db.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/db/driver.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/debug/logger.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/runtime/dataobject.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/sys/versions.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/ctrl/options.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/ctrl/auth.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/ctrl/users.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/dryden/fs/director.class.php');
include($_SERVER['DOCUMENT_ROOT'] . '/inc/dbc.inc.php');
try {	
	$zdbh = new db_driver("mysql:host=localhost;dbname=" . $dbname . "", $user, $pass);
} catch (PDOException $e) {
	exit();
}
if (isset($_GET['id'])){
	$userid = $_GET['id'];
} else {
	$userid = NULL;
}
$currentuser = ctrl_users::GetUserDetail($userid);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>ZPanel &gt; Back-Ups</title>
<link href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/etc/styles/<?php echo $currentuser['usertheme']; ?>/css/<?php echo $currentuser['usercss']; ?>.css" rel="stylesheet" type="text/css">
<script src="../assets/ajaxsbmt.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<body style="background: #F3F3F3;">
<div style="margin-left:20px;margin-right:20px;">
<div class="zform_wrapper">
<h2>Backup your hosting account files</h2>
<p>Your data is ready to be backed up. This proccess can take a lot of time, depending on your directory size. When finished you will be prompted to download your archive.</p>
<p>Current public directory size: <b><?php echo fs_director::ShowHumanFileSize(dirSize(ctrl_options::GetOption('hosted_dir') . $currentuser['username'] . "/public_html")); ?></b></p>
<div id="BackupSubmit" style="height:100%;margin:auto;">
<form name="doBackup" action="response_normal.php" method="post" onsubmit="xmlhttpPost('dobackup.php?id=<?php echo $userid; ?>', 'doBackup', 'BackupResult', 'Compressing your data, please wait...<br><img src=\'../assets/bar.gif\'>'); return false;">
    <table class="zform">
        <tr valign="top">
        	<th nowrap="nowrap"><button class="fg-button ui-state-default ui-corner-all" id="SubmitBackup" type="submit" name="inBackUp" value="">Backup Now</button></th>
        	<td><input type="hidden" name="inDownLoad" id="inDownLoad" value="1" /></td>
	    </tr>
    </table>
</form>
</div>
<div id="BackupResult" style="display:block;height:100%;margin:auto;">
</div>

</div>
</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function() { 
		$("#BackupResult").hide();
			$("#SubmitBackup").click(function(){
			$("#BackupSubmit").hide();
			$("#BackupResult").show();
    	}); 
	})
</script>
<?php 
function dirSize($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        $size+=$file->getSize();
    }
    return $size;
} 
?>
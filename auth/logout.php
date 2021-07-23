<?php session_start();?>
<?php require_once('../inc/connection.php');?>
<?php	
	$_SESSION=array();
	if(isset($_COOKIE[session_name()]))
	{
		setcookie(session_name(),'',time()-86400,'/');
	}
	session_destroy();
	
	//echo("<script>location.href = '".ADMIN_URL."/index.php?msg=$msg';</script>");
    header('Location: ../index.php?logout=yes');
?>
<?php mysqli_close($connection);?>
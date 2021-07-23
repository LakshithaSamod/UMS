<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
 <?php require_once('../inc/func.php'); ?>
<?php
	
	//chk if a user login
	verify_user_isLogged($_SESSION['id']);
	
	if(isset($_GET['id']))
	{
		//getting the user info
		$id=mysqli_real_escape_string($connection,$_GET['id']);
		
		if ($id==$_SESSION['id']) 
		{
			//shd not delete current logged user
			header('Location: users.php?err=cannot_delete_current_user');
		}

		else
		{
			//delete the user
			$query="UPDATE user SET is_deleted=1 WHERE id={$id} LIMIT 1";

			$result=mysqli_query($connection,$query);

			if($result)
			{
				//user deleted
				header('Location: users.php?msg=user_deleted');
			}
			else
			{
				header('Location: users.php?err=delete_failed');
			}
		}
	}

	else
	{
		header('Location: users.php');

	}
?>
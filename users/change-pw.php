<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
 <?php require_once('../inc/func.php'); ?>
<?php
	
	//chk if a user login
	verify_user_isLogged($_SESSION['id']);
	
	$errors = array();
	$first_name = '';
	$last_name = '';
	$email = '';
	$password = '';
	$id='';

	if(isset($_GET['id']))
	{
		//getting the user info
		$id=mysqli_real_escape_string($connection,$_GET['id']);
		$query="SELECT * FROM user WHERE id ={$id} LIMIT 1";

		$result_set=mysqli_query($connection,$query);

		verify_query($result_set);

		//query success
		if(mysqli_num_rows($result_set)==1)
		{
				//user found
				$result=mysqli_fetch_assoc($result_set);
				$first_name = $result['first_name'];
				$last_name = $result['last_name'];
				$email = $result['email'];
		}

		else
		{
				//user not found
				header('Location: users.php?err=user_not_found');
		}
		

	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,  maximum-scale=1.0, minimum-scale=1.0">
	<title>Change Password</title>
	<link rel="stylesheet" type="text/css" href="../css/alert.css">
	<script src="../js/alert.js"></script>
	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- Custom Theme files -->
	<link href="../css/modify-user.css" rel="stylesheet" type="text/css" media="all" />
	<!-- //Custom Theme files -->
	<!-- web font -->
	<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
	<!-- //web font -->
	</head>
<body>
	
<?php require_once('../inc/header.php'); ?>
 
<div class="main-w3layouts wrapper">
		<h1>Change Password</h1>

		<?php
				if (!empty($errors)) 
				{
					display_errors($errors);
				}

		?>

		<div class="main-agileinfo">
			<div class="agileits-top">
		<form action="change-pw.php" class="userform" method="post">

			<input class="text email" type="text" placeholder="first name" name="first_name"  <?php echo 'value="' . $first_name . '"'; ?> disabled>
					<input class="text email" type="text" placeholder="last name" name="last_name" required=""  <?php echo 'value="' . $last_name . '"'; ?> disabled>
					<input class="text email" type="email" placeholder="Email" name="email" required=""  <?php echo 'value="' . $email . '"'; ?> disabled>
					<input class="text" type="password" name="password" placeholder="Password" required="" >
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<input type="submit" value="SAVE PASSWORD" name="submit">
				</form>
				<p>Want to go back? <a href="modify-user.php?id=<?php echo $id; ?> "> Back to edit user menu</a></p>
			</div>
		</div>
	
		<ul class="colorlib-bubbles">
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
	<!-- //main -->
</body>

</html>



<?php	
	$errors=array();

	if (isset($_POST['submit'])) {

		$id = $_POST['id'];		
		$password = $_POST['password'];

		// checking required fields
		$req_fields = array('id','password');

		check_req_fields($req_fields);

		// checking max length
		$max_len_fields = array('password' => 40);

		check_max_len($max_len_fields);

		
		if(empty($errors))
		{
			//no any errors... add a record
			$password=mysqli_real_escape_string($connection,$_POST['password']);
			$hashed_password=sha1($password);
			
		
			$query="UPDATE user SET password='{$hashed_password}' WHERE id='{$id}' LIMIT 1";

			$result=mysqli_query($connection,$query);

			if($result)
			{
				//query success .... redirecg to user page
				echo "<script>swal({
					title: 'Done!',
					text: 'Password changed successfully!',
					icon: 'success',
					buttons: {
						cancel: 'OK',
						catch: {
						  text: 'Back to users',
						  value: 'catch',
						},
						back: 'Back to edit user',  
					  },
					dangerMode: true,
				  })
				  .then((value) => {
					switch (value) {
				   
					  case 'catch': 
						window.location.replace('users.php?');
						break;

					  case 'back': 
							window.location.replace('modify-user.php?id=$id');	
				   
					  default:
					  window.location.replace('change-pw.php?id=$id');					  
					  break;
					}
				  });</script>";
			}
			else
			{
				echo "<script>swal('Oops... Something went wrong!', 'Database query failed', 'error')
				.then((value) => {
					window.location.replace('modify-user.php?id=$id');
				});;</script>";
			}

		}
		else{
			echo "<script>swal('Oops... ', '$errors[0]', 'warning');</script>";
		}
	}

?>

<?php mysqli_close($connection); ?>
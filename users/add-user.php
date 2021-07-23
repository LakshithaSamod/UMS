<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
<?php require_once('../inc/func.php'); ?>
<?php

//check if a user is logged
verify_user_isLogged($_SESSION['id']);
?>


<!DOCTYPE html>
<html>

<head>
	<title>Add New User</title>

	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,  maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	<link rel="stylesheet" type="text/css" href="../css/alert.css">
	<script src="../js/alert.js"></script>
</head>

<body>

	<?php require_once('../inc/header.php'); ?>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Add New User </h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="add-user.php" class="userform" method="post">

					<input class="text email" type="text" placeholder="First name" name="first_name" required="">
					<input class="text email" type="text" placeholder="Last name" name="last_name" required="">
					<input class="text email" type="email" placeholder="Email" name="email" required="">
					<input class="text" type="password" name="password" placeholder="Password" required="">

					<input type="submit" value="SAVE" name="submit">
				</form>
				<p>Want to go back? <a href="users.php"> Back to users!</a></p>
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
$errors = array();
//for giving initial values
$first_name = '';
$last_name = '';
$email = '';
$password = '';

if (isset($_POST['submit'])) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// checking required fields
	$req_fields = array('first_name', 'last_name', 'email', 'password');

	$errors = array_merge($errors, check_req_fields($req_fields));



	// checking max length
	$max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100, 'password' => 40);

	$errors = array_merge($errors, check_max_len($max_len_fields));

	// checking email address
	if (!is_email($_POST['email'])) {
		$errors[] = 'Email address is invalid.';
	}

	//chk email is already exists
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$query = "SELECT * FROM user WHERE email='{$email}' LIMIT 1";

	$result_set = mysqli_query($connection, $query);

	verify_query($result_set);

	// query succesfful

	if (mysqli_num_rows($result_set) == 1) {
		$errors[] = 'email address already exists.';
	}


	if (empty($errors)) {
		//no any errors... add a record
		$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
		$first_name = ucfirst($first_name);

		$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
		$last_name = ucfirst($last_name);

		$password = mysqli_real_escape_string($connection, $_POST['password']);
		$hashed_password = sha1($password);

		$query = "INSERT INTO user(first_name,last_name,email,password,is_deleted,is_active ) ";
		$query .= "VALUES('{$first_name}','{$last_name}','{$email}','{$hashed_password}',0,true)";

		$result = mysqli_query($connection, $query);

		if ($result) {				
			echo "<script>swal({
				title: 'Done!',
				text: 'New user created successfully!',
				icon: 'success',
				buttons: {
					cancel: 'Back to users',
					catch: {
					  text: 'OK',
					  value: 'catch',
					},
				  },
				dangerMode: true,
			  })
			  .then((value) => {
				switch (value) {
					   
				  case 'catch':					
					break;
			   
				  default:
				  window.location.replace('users.php?');
				}
			  });</script>";
		} else {
			echo "<script>swal('Oops... Something went wrong!', 'Database query failed', 'error')
			.then((value) => {
				window.location.replace('users.php?');
			});;</script>";
		}
	}
	else{
		$error = ucfirst(str_replace("_", " ", $errors[0]));
		echo "<script>swal('Oops.. There were error(s) on your add user form ', '$error', 'warning');</script>";
	}
}
mysqli_close($connection);
?>
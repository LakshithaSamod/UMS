<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
<?php require_once('../inc/func.php'); ?>
<?php

//check if a user is logged
verify_user_isLogged($_SESSION['id']);


$first_name = '';
$last_name = '';
$email = '';
$id = '';

if (isset($_GET['id'])) {

	//getting the user info
	$id = mysqli_real_escape_string($connection, $_GET['id']);
	$query = "SELECT * FROM user WHERE id ={$id} LIMIT 1";

	$result_set = mysqli_query($connection, $query);

	verify_query($result_set);

	//query success
	if (mysqli_num_rows($result_set) == 1) {
		//user found
		$result = mysqli_fetch_assoc($result_set);
		$first_name = $result['first_name'];
		$last_name = $result['last_name'];
		$email = $result['email'];
	} else {
		//user not found
		header('Location: users.php?err=user_not_found');
	}
}



// echo "<script>swal('hi', 'User edit successfully!', 'error');</script>";


?>

<!DOCTYPE html>



<html>

<head>
	<title>Edit User Form</title>

	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,  maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Edit User Form</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="modify-user.php" class="userform" method="post">
					
					<input class="text email" type="text" placeholder="first name" name="first_name"  <?php echo 'value="' . $first_name . '"'; ?>>
					<input class="text email" type="text" placeholder="last name" name="last_name" required=""  <?php echo 'value="' . $last_name . '"'; ?>>
					<input class="text email" type="email" placeholder="Email" name="email" required=""  <?php echo 'value="' . $email . '"'; ?>>
					<input class="text" type="password" name="password" placeholder="Password" disabled></br>
					<p><a href="change-pw.php?id=<?php echo $id; ?>"> Change Password </a></p>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
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

if (isset($_POST['submit'])) {

	$id = $_POST['id'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// checking required fields
	$req_fields = array('id', 'first_name', 'last_name', 'email');

	check_req_fields($req_fields);
	$errors = array_merge($errors, check_req_fields($req_fields));

	// checking max length
	$max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100);

	check_max_len($max_len_fields);
	$errors = array_merge($errors, check_max_len($max_len_fields));

	// checking email address
	if (!is_email($_POST['email'])) {
		$errors[] = 'Email address is invalid.';
	}

	//chk email add already exists
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$query = "SELECT * FROM user WHERE email='{$email}' AND id!='{$id}' LIMIT 1";

	$result_set = mysqli_query($connection, $query);

	verify_query($result_set);

	// query succesfful		
	if (mysqli_num_rows($result_set) == 1) {
		$errors[] = 'email address already exists.';
	}

	if (empty($errors)) {
		//no any errors... add a record
		$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
		$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
		$password = mysqli_real_escape_string($connection, $_POST['password']);
		$hashed_password=sha1($password);

		$query = "UPDATE user SET first_name='{$first_name}',last_name='{$last_name}',email='{$email}',password='{$hashed_password}' WHERE id='{$id}' LIMIT 1";

		$result = mysqli_query($connection, $query);

		if ($result) {
			//query success .... redirecg to user page
			echo "<script>swal({
				title: 'Done!',
				text: 'User edit successfully!',
				icon: 'success',
				buttons: {
					
					catch: {
					  text: 'Back to users', 
					  value: 'catch',
					},
					cancel: 'OK',					
				  },
				dangerMode: true,
				
			  })
			  .then((value) => {
				switch (value) {
			   
				  case 'catch': 
					window.location.replace('users.php?');
					break;				  
			   
				  default:
				  window.location.replace('modify-user.php?id=$id');					  
				  break;
				}
			  });</script>";
		} else {
			echo "<script>swal('Oops... Something went wrong!', 'Database query failed', 'error')
			.then((value) => {
				window.location.replace('users.php?');
			});;</script>";
		}
	}else {
		echo "<script>swal('Oops... ', '$errors[0]', 'warning');</script>";
	}

}


// echo "<script>swal.fire('The Internet?','That thing is still around?','question');</script>";

// $h = 'hi';
//  echo "<script>swal('Done!', 'New user created successfully!', 'success')
//  .then((value) => {
// 	window.location.replace('users.php?user=$h');
//  });;</script>";


//  echo "<script>swal({
// 	title: 'Are you sure?',
// 	text: 'Once deleted, you will not be able to recover this imaginary file!',
// 	icon: 'warning',
// 	buttons: {
// 		cancel: 'Run away!',
// 		catch: {
// 		  text: 'Throw Pokéball!',
// 		  value: 'catch',
// 		},
// 		defeat: true,
// 	  },
// 	dangerMode: true,
//   })
//   .then((value) => {
// 	switch (value) {
   
// 	  case 'defeat':
// 		swal('Pikachu fainted! You gained 500 XP!');
// 		break;
   
// 	  case 'catch':
// 		swal('Gotcha!', 'Pikachu was caught!', 'success');
// 		break;
   
// 	  default: 
// 	  window.location.replace('users.php?');
// 	}
//   });</script>";

			// echo "<script>swal('Done!', 'New user created successfully!', 'success');</script>";
			// echo "<script>swal('Done!', 'New user created successfully!', 'success')
			// .then((value) => {
			// swal(`The returned value is: ${value}`);
			// });;</script>";


// echo "<script>swal({
// 	title: 'Are you sure?',
// 	text: 'Once deleted, you will not be able to recover this imaginary file!',
// 	icon: 'warning',
// 	buttons: {
// 		cancel: 'Run away!',
// 		catch: {
// 		  text: 'Throw Pokéball!',
// 		  value: 'catch',
// 		},
// 	  },
// 	dangerMode: true,
//   })
//   .then((value) => {
// 	switch (value) {
		   
// 	  case 'catch':		
// 		break;
   
// 	  default:
// 	  window.location.replace('users.php?');
// 	}
//   });</script>";

mysqli_close($connection); ?>

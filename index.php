<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php require_once('inc/func.php'); ?>
<?php require_once('auth/registration.php'); ?>
<?php



//if already logged users cannot access this page
if (isset($_SESSION['id'])) {
	//echo("<script>location.href = '".ADMIN_URL."/index.php?msg=$msg';</script>");
	header('Location: users/users.php?msg=alreadyLogged');
}

// check for form login
if (isset($_POST['signinBtn'])) {

	$errors = array();

	// check if the username and password has been entered
	//if empty or enter a space

	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$password = mysqli_real_escape_string($connection, $_POST['password']);
	//echo $email;
	//echo $password;
	//die();

	$req_fields = array('email', 'password');

	$errors = array_merge($errors, check_req_fields($req_fields));


	// check if there are no any errors in the form
	if (empty($errors)) {
		// save username and password into variables
		$email 		= mysqli_real_escape_string($connection, $_POST['email']);
		$password 	= mysqli_real_escape_string($connection, $_POST['password']);
		$hashed_password = sha1($password);

		// prepare database query
		$query = "SELECT * FROM user 
						WHERE email = '{$email}' 
						AND password = '{$hashed_password}' 
						LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		verify_query($result_set); {
			// query succesfful

			if (mysqli_num_rows($result_set) == 1) {
				// valid user found
				$user = mysqli_fetch_assoc($result_set);
				$_SESSION['id'] = $user['id'];
				$_SESSION['first_name'] = $user['first_name'];

				//updating last login
				$query = "UPDATE user SET last_login=NOW()";
				$query .= "WHERE id={$_SESSION['id']} LIMIT 1";

				$result_set = mysqli_query($connection, $query);

				verify_query($result_set);

				// redirect to users.php
				header('Location: users/users.php');
			} else {
				// user name and password invalid
				$errors[] = 'Invalid Username / Password';
			}
		}
	}
}

//chk for sign up
if (isset($_POST['signupBtn'])) {

	$errors2 = array();

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// checking required fields
	$req_fields = array('first_name', 'last_name', 'email', 'password');

	$errors2 = array_merge($errors2, check_req_fields($req_fields));

	// checking max length
	$max_len_fields = array('first_name' => 50, 'last_name' => 100, 'email' => 100, 'password' => 40);

	$errors2 = array_merge($errors2, check_max_len($max_len_fields));

	// checking email address
	if (!is_email($_POST['email'])) {
		$errors2[] = 'Email address is invalid.';
	}

	

	//chk email add already exists
	$email = mysqli_real_escape_string($connection, $_POST['email']);
	$query = "SELECT * FROM user WHERE email='{$email}' LIMIT 1";

	$result_set = mysqli_query($connection, $query);

	verify_query($result_set);

	// query succesfful		
	if (mysqli_num_rows($result_set) == 1) {
		$errors2[] = 'email address already exists.';
	}

	if (empty($errors2)) {
		registerUser($first_name, $last_name, $email, $password);
	}
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Log In - User Management System</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,  maximum-scale=1.0, minimum-scale=1.0">
	<title>Log In - User Management System</title>
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="css/alert.css">
	<script src="js/alert.js"></script>
</head>

<body>

				<?php
				if (isset($errors) && !empty($errors)) {
					//echo '<p class="error">Invalid Username / Password</p>';
					// echo '<p class="error">' . $errors[0] . '</p>';
					$error = ucfirst(str_replace("_", " ", $errors[0]));
					echo "<script>swal('Oops... ', '$error', 'warning');</script>";
				}

				if (isset($errors2) && !empty($errors2)) {
					//echo '<p class="error">Invalid Username / Password</p>';
					
					$error = ucfirst(str_replace("_", " ", $errors2[0]));
					echo "<script>
					swal('Oops.. There were error(s) on your signup form', '$error', 'warning')
					.then((value) => {
						container.classList.add('right-panel-active');
					});;
					</script>";
				}

				if (isset($_GET['logout'])) {
					echo '<p class="info">You have successfully log out from the system.</p>';
				}

				if (isset($_GET['verify'])) {
					
					//check for the verification mail send or not
					if ($_GET['verify'] == 'Email sent'){
						echo "<script>swal('Verification link has been sent to your email', 'Please check your email!', 'info');</script>";
					}
					if ($_GET['verify'] == 'Email send failed'){
						echo "<script>swal('Oops.. mail could not be sent !', 'Please input a correct email or try again later', 'error');</script>";
					}
					
					//check for the verification link is correct
					if ($_GET['verify'] == 'Email verified successfully'){
						// echo '<p class="info">You have verified your email successfully. Now you can logging to the system.</p>';
						echo "<script>swal('You have verified your email successfully!', 'Now you can logging to the system', 'success');</script>";
					}
					if ($_GET['verify'] == 'Invalid verification code'){
						echo "<script>swal('Oops.. Invalid verification link!', 'Please check the email again', 'warning');</script>";
					}						
				}

				?>


				<div class="container" id="container">
					<div class="form-container sign-up-container">
					<form action="index.php" method="post">
							<h1>Create Account</h1>
							<div class="social-container">
								<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
								<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
								<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
							</div>
							<span>or use your email for registration</span>
							<input type="text" placeholder="First Name" name="first_name" />
							<input type="text" placeholder="Last Name" name="last_name"/>
							<input type="email" placeholder="Email" name="email"/>
							<input type="password" placeholder="Password" name="password"/>
							<button name="signupBtn">Sign Up</button>
						</form>
					</div>
					<div class="form-container sign-in-container">
					<form action="index.php" method="post">
							<h1>Sign in</h1>
							<div class="social-container">
								<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
								<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
								<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
							</div>
							<span>or use your account</span>
							<input type="email" placeholder="Email" name="email"/>
							<input type="password" placeholder="Password" name="password"/>
							<a href="#">Forgot your password?</a>
							<button name="signinBtn">Sign In</button>
						</form>
					</div>
					<div class="overlay-container">
						<div class="overlay">
							<div class="overlay-panel overlay-left">
								<h1>Welcome Back!</h1>
								<p>To keep connected with us please login with your personal info</p>
								<button class="ghost" id="signIn">Sign In</button>
							</div>
							<div class="overlay-panel overlay-right">
								<h1>Hello, Friend!</h1>
								<p>Enter your personal details and start journey with us</p>
								<button class="ghost" id="signUp">Sign Up</button>
							</div>
						</div>
					</div>
				</div>

	<script src="js/login.js"></script>
</body>

</html>
<?php mysqli_close($connection); ?>
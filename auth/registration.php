<html>

<head>
	<title>Edit User Form</title>

	<link rel="stylesheet" type="text/css" href="../css/alert.css">
	<script src="../js/alert.js"></script>
</head>

</html>



<?php

	echo "<script>swal('Done!', 'New user created successfully!', 'success');</script>";
	
	function registerUser($first_name, $last_name, $email, $password)
	{
		global $connection;

		$hashed_password=sha1($password);

		$verification_code = sha1($email . time());
		$verfication_URL  = 'http://localhost/ums/auth/verify.php?code=' . $verification_code;

		$query = "INSERT INTO user (first_name, last_name, email, password, verification_code, is_active)" ;
		$query .= "VALUES ('{$first_name}','{$last_name}' , '{$email}', '{$hashed_password}', '{$verification_code}', false)";

		$result = mysqli_query($connection, $query);

		// mail sending code
		$to	 		  = $email; // receiver
		$sender		  = 'lakisaman19@gmail.com '; // email address of the sender
		$mail_subject = 'Verify Email Address';
		$email_body   = '<p>Dear ' . $first_name . ' ' . $last_name . ',' . '</p>';
		$email_body  .= '<p>Thank you for signing up. There is one more step.
						Click below link to verify your email address in order to activate your account.</p>';
		$email_body  .= '<p>' . $verfication_URL . '</p>';
		$email_body  .= '<p>Thank You, <br>By LakiSam UMS</p>';

		$header       = "From: {$sender}\r\nContent-Type: text/html;";

		$send_mail_result = mail($to, $mail_subject, $email_body, $header);

		if ( $send_mail_result ) {
			// mail sent successfully
			// echo 'Please check your email.';
			header('Location: index.php?verify=Email sent');			
		} else {
			// mail could not be sent 
			header('Location: index.php?verify=Email send failed');
		}

	}
?>



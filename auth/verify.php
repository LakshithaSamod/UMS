<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
<?php require_once('../inc/func.php'); ?>

<?php 

	if (isset($_GET['code'])) {
		$verification_code = mysqli_real_escape_string($connection, $_GET['code']);

		$query = "SELECT * FROM user WHERE verification_code = '{$verification_code}'";
		
		//echo $query;
		$result = mysqli_query($connection, $query);

		if (mysqli_num_rows($result) == 1) {
			$query = "UPDATE user SET is_active = true, verification_code = NULL";
			$query .= " WHERE verification_code = '{$verification_code}' LIMIT 1"; 

			$result = mysqli_query($connection, $query);

			//verify_query($result);

			if (mysqli_affected_rows($connection) == 1) {
				header('Location: ../index.php?verify=Email verified successfully');
			} else {
				header('Location: ../index.php?verify=Invalid verification code');
			}
		}
		else {
			header('Location: ../index.php?verify=Invalid verification code');
		}

	}

 ?>

<?php mysqli_close($connection);?>
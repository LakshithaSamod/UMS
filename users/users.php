<?php session_start(); ?>
<?php require_once('../inc/connection.php'); ?>
<?php require_once('../inc/func.php'); ?>
<?php 

//check if a user is logged
verify_user_isLogged($_SESSION['id']);
$user_list = '';
$search = '';


//pagination
$query = " SELECT COUNT(id) AS total_rows FROM user WHERE is_deleted=0 AND is_active = true";
$result_set = $users = mysqli_query($connection, $query);
verify_query($result_set);
$result = mysqli_fetch_assoc($result_set);
$total_rows =  $result['total_rows'];

$rows_per_page = 5;

$no_of_pages = ceil($total_rows / $rows_per_page);

$page_no = 1;


if (isset($_GET['pno'])) {
	if ($_GET['pno'] < 1) {
		$page_no = 1;
	} else if ($_GET['pno'] > $no_of_pages) {
		$page_no = $no_of_pages;
	} else {
		$page_no = $_GET['pno'];
	}
} else {
	$page_no = 1;
}

$start = ($page_no - 1) * $rows_per_page;

//search

if (isset($_GET['search'])) {
	$search = mysqli_real_escape_string($connection, $_GET['search']);
	//getting the list of users by search
	//$query = " SELECT * FROM user WHERE (first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' or email LIKE '%{$search}%') AND is_deleted=0 ORDER BY first_name";
	$query = " SELECT * FROM user ";
	$query .= "WHERE (first_name LIKE '%{$search}%' OR ";
	$query .= "last_name LIKE '%{$search}%' OR ";
	$query .= "email LIKE '%{$search}%') AND ";
	$query .= "is_deleted=0 AND is_active = true ORDER BY first_name  ";
} else {
	//getting the list of users
	$query = " SELECT * FROM user WHERE is_deleted=0 AND is_active = true ORDER BY first_name ";
	$query .= "LIMIT {$start}, {$rows_per_page} ";
}

//for debugging
//echo $query;
//die();

$users = mysqli_query($connection, $query);

verify_query($users);

while ($user = mysqli_fetch_assoc($users)) {
	$user_list .= "<tr>";
	$user_list .= "<td>{$user['first_name']}</td>";
	$user_list .= "<td>{$user['last_name']}</td>";
	$user_list .= "<td>{$user['last_login']}</td>";
	$user_list .= "<td><a class=\"push_button blue\" href=\"modify-user.php?id={$user['id']}\">Edit</td>";
	$user_list .= "<td><a class=\"push_button red\" href=\"delete-user.php?id={$user['id']}\" onclick=\"return confirm('Are you sure?');\">Delete</td>";
	$user_list .= "</tr>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,  maximum-scale=1.0, minimum-scale=1.0">
	<title>Users</title>
	<link rel="stylesheet" type="text/css" href="../css/users.css">
	<link rel="stylesheet" href="../css/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../css/alert.css">
	<script src="../js/alert.js"></script>
</head>
<body class="user">


	<?php require_once('../inc/header.php'); ?>

	<main>
		<section>
			<!--for demo wrap-->
			<h1>Users
				<span> <a href="add-user.php"><button class="button"><span>Add New</span></button></a></span>

				<span class="refresh"> <a href="users.php"><button class="refeshBtn"> <span>Refresh</span></button></a></span>
			</h1>

			<!-- search -->
			<form action="users.php" method="get">
				<div class="search">
					<input type="text" class="searchTerm" name="search" placeholder="Type here to search by name or email address" value="<?php echo $search; ?>" autofocus required />
					<button type="submit" class="searchButton">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</form>

			<div class="tbl-header">
				<table cellpadding="0" cellspacing="0" border="0">
					<thead>
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Last Login</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
				</table>
			</div>
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<?php echo $user_list; ?>

					</tbody>
				</table>
			</div>

			<!-- -----------paginaton buttons----------- -->
			<div class="title">
				<ul class="pagination">
					<li class="icon">
						<a href="users.php?pno=1">
							<span class="fas fa-angle-left"></span><span class="fas fa-angle-left"></span>First
						</a>
					</li>
					<li class="icon">
						<a href="users.php?pno=<?php --$page_no;
												echo $page_no; ?>">
							<span class="fas fa-angle-left"></span> &nbsp Previous
						</a>
					</li>
					<li class="icon"><b>
							<a><span style="cursor:pointer"><?php echo " Page " . ++$page_no . " of " .  $no_of_pages . " "; ?></span></a>
						</b></li>

					<li class="icon">
						<a href="users.php?pno=<?php $page_no++;
												echo $page_no; ?>">Next &nbsp<span class="fas fa-angle-right"></span> </a>
					</li>
					<li class="icon">
						<a href="users.php?pno=<?php $page_no = $no_of_pages;
												echo $no_of_pages  ?>"> Last<span class="fas fa-angle-right"></span><span class="fas fa-angle-right"></span> </a>
					</li>
				</ul>
			</div>

		</section>
		<main>

			<!-- follow me template -->
			<div class="made-with-love">
				Contact us &nbsp
				<i>♥</i>&nbsp by &nbsp
				<a href="../contact/contact-us.php">email or sms</a>
			</div>
			<script src="../js/users.js"></script>
</body>

</html>



<?php

// if (isset($_GET['err'])) {
// 	if ($_GET['err'] == 'user_not_found') {
// 		echo "<script>swal('Oops...!', 'User edit failed!', 'info');</script>";
// 	} 
// }


// if (isset($_GET['pw_modified'])) {
// 	if ($_GET['pw_modified'] == 'true') {
// 		echo "<script>swal('Done!', 'User password edit successfully!', 'success');</script>";
// 	} 
// }
mysqli_close($connection);
?>
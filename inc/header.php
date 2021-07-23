<header>
	<div class="appname" align="center">User Mangement System</div>
	<div class="loggedin" align="right">Welcome <?php echo $_SESSION['first_name']; ?>!
		<a href="../auth/logout.php" class="anchor">&nbsp&nbsp Logout &nbsp<span class="fas fa-sign-out-alt fa-md"></span></a>
	</div>
</header>
<link rel="stylesheet" href="../css/css/all.min.css">
<style>
	header {
		background-image: url("https://www.toptal.com/designers/subtlepatterns/patterns/binding_dark.png");

	}

	.appname {
		color: #ffffff;
		font-family: 'Raleway', sans-serif;
		font-size: 35px;
		font-weight: 800;
		line-height: 20px;
		margin-top: -10px;
		text-align: center;
		text-transform: uppercase;
		padding-top: 25px;
	}

	a {
		text-decoration: none;
		color: #ffffff;
	}

.loggedin{
	color: white;
	margin: 0px 30px 0 0;
	padding: 6px;
}

</style>
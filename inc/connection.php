<?php
	$dbhost='localhost';
	$dbuser='root';
	$dbpass='';
	$dbname='userdb';
	$connection=mysqli_connect('localhost','root','','userdb');
	
	//check the connection
	if(mysqli_connect_errno())
	{
		die('DB connection failed '.mysqli_connect_error());
	}
	
    else
    {
		//echo "connection succesful";
	}
?>
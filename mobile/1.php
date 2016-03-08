<?php
	require("config.php"); 
	require("function.php");
		$mysql = new mysqli($mysql_server,$mysql_user,$mysql_password,$database);
	if(mysqli_connect_errno()){
			echo "Cannot connect to database.";
			exit;
		}
	/*CHARSET UTF-8*/
    $query=" SET NAMES 'utf8'; ";
    $result = $mysql->query($query);
$phone = $_GET['phone'];
echo "1000000000";
echo "<br/>";
echo check_shanghai_phone($phone);
echo "<br/>";
echo check_shanghai_phone("13127799430");

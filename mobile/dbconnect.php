<?php
/*Connect to MySQL Database*/
	//echo $mysql_server,$mysql_user,$mysql_password;
	$mysql = new mysqli($mysql_server,$mysql_user,$mysql_password,$database);
	if(mysqli_connect_errno()){
			echo "Cannot connect to database.";
			exit;
		}
	/*CHARSET UTF-8*/
    $query=" SET NAMES 'utf8'; ";
    $result = $mysql->query($query);
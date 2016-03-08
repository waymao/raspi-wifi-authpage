<?php

function check_phone($a){
	if(preg_match('^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$^' ,$a))
	{
    	return true;
	}else{
   		return false;
	}
}

function check_stnum($a){
	
 	if(preg_match('^(14|15|16|17|18)\d{3}$^' ,$a))
	{
    	return true;
	}else{
   		return false;
	}
}


function get_mac(){
	/*This function let you know the client's MAC Address*/
	$arp = "/usr/sbin/arp";
	$mac = shell_exec("$arp -a ".$_SERVER['REMOTE_ADDR']);
    preg_match('/..:..:..:..:..:../',$mac , $matches);
    @$mac = $matches[0];
    if (!isset($mac)) { 
		echo "无法获取您的Mac地址";
		exit;
	}else{
		return $mac;
	}
}


function mysqlcon(){
	/*This function is used to connect to the MySQL Database*/
	require("config.php"); //Get DB information (i.e. passwd, usr, address, db)
	$mysql = new mysqli($mysql_server,$mysql_user,$mysql_password,$database);//Connect to Database
	if(mysqli_connect_errno()){
			echo "无法连接到数据库";
			exit;
	}
	/*CHARSET UTF-8*/
    $query=" SET NAMES 'utf8'; ";
    $result = $mysql->query($query);
	return $mysql;

}

function access_log($mysql,$user_agent,$user_ip,$user_mac,$url_address){
	$query="SELECT `value` FROM `options` WHERE `name` = 'access-count'";
	$result = $mysql->query($query);
	$row = $result->fetch_assoc();
	$counter = $row['value'];
	$query="INSERT INTO `access_log` (`id`, `user_agent`, `ip`, `mac`, `user_id`,`redirect-url`) VALUES ('".$counter."', '".$user_agent."', '".$user_ip."', '".$user_mac."', '0','".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."');";
		$result = $mysql->query($query);
		$query="UPDATE `login_wm`.`options` SET `value` = $counter+1 WHERE `options`.`name` = 'access-count';";
		$result = $mysql->query($query);
		return true;
}


function INSERT_DATA($mysql,$name,$stnum,$phone,$mac,$ip){
	$query="SELECT SQL_CALC_FOUND_ROWS * FROM user_information;";
	$result = $mysql->query($query);
	$query="INSERT INTO `login_wm`.`user_information` (`id`, `name`, `st_id`, `phone_number`, `mac`,`ip`) VALUES (FOUND_ROWS(), '$name', '$stnum' , '$phone','$mac','$ip');";
	$result = $mysql->query($query);
	//echo $query;
	return $result;
}


<?php
	require("config.php"); 
	require("function.php");	
  header("location:http://192.168.106.1：8080/mobile/yz1.php?add="
    .urlencode($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']));
	access_log($mysql,$user_agent,$user_ip,$user_mac,$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'])	;
  exit;
?>
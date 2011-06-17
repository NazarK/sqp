<?php

$apptitle = "SQP Web Application";


error_reporting(E_ALL | E_STRICT);

//HOST SPECIFIC
define("mysql",0);
define("sqlite2",1);
define("USERS",0);
define("NICE_URLS",1); //apache mod_rewrite enabled



define("MYSQL_HOST","p41mysql145.secureserver.net");
define("MYSQL_USER","nkcompsqp");
define("MYSQL_PASS","SuperPass123");
define("MYSQL_DB","nkcompsqp");


define("SQLITE2_DB","sqlite2.db");
define("FOLDER_SEPARATOR","/");
define("HTTP_POST_DOUBLE_SLASH","1"); //used in HTTPPostValue, HTTPPostFix


$base_url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";
if(substr($base_url,strlen($base_url)-2,2)=='//') {
	$base_url = substr($base_url,0,strlen($base_url)-1);
}
//date_default_timezone_set("Europe/Amsterdam");

?>
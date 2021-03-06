<?php
/************************* MAIN ****************************/
session_start();

require_once "app.php";
require_once "bios.php"; //all functions
require_once "conf.php";


if(function_exists("data_model"))
  data_model();

db_connect();
error_reporting(E_ALL | E_STRICT);

if(!isset($_SESSION['lang'])) {
	$_SESSION['lang'] = 'eng';
}

global $submenu;
global $pageheader;
global $log;
global $content;
$query = form_post('q');
$submenu = "";
$log = "";
$content = "";
$pageheader = "";

//OTHER MODULES
$modules = array();
foreach(glob("uses/*.php") as $module) {
	require_once $module;
	$module_name = str_replace("uses/","",str_replace(".php","",$module));
	$modules[] = $module_name;
	$fname = $module_name."_connect";
	if(function_exists($fname)) {
		$fname();
	}
}

//LOGIN - different menus generation for different user's

$menu_logout = "";
$menu_user = "";

$menu_users = "";
if(user_authorized() && $_SESSION['userid']==1)
   $menu_users = ":: <a href=?q=users>Users</a>";

if(!isset($_GET['q']) || $_GET['q']=='') {
   if(function_exists("def_q")) {
      $_GET['q'] = def_q();
   } else {
      $_GET['q'] = 'home';
   }
}


//converts /about link to /p/34
menu_check_by_name($_GET['q']);
page_check_by_name($_GET['q']);

$parts = explode('/',$_GET['q']);


//CHECK FOR page_function
$function = "page";
$appropriate_function = "";
$appropriate_index = -1;
$temp_template = "";
$def_template = "";
foreach($parts as $i=>$part) {
    if($temp_template) { $temp_template .= "_";}
    $temp_template .= "$part";
    $function .= "_$part";
    if(function_exists($function)) {
        $appropriate_function = $function;
        $appropriate_index = $i;
	      $def_template = $temp_template;
    }
}



//CHECK FOR before_function
$function = "before";
$before_function = "";
foreach($parts as $i=>$part) {
    $function .= "_$part";
    if(function_exists($function)) {
        $before_function = $function;
    }
}

//// self_q
$self_q = "";
for($i=0;$i<=$appropriate_index;$i++) {
  if($self_q) $self_q .= "/";
  $self_q .= $parts[$i];
}

//ARGUMENTS
$args = array();
for($i=$appropriate_index;$i<count($parts)-$appropriate_index+1;$i++) {
   if(isset($parts[$i+1]))
   $args[] = $parts[$i+1];
}

//evaluate content
if($appropriate_function) {

    $i = $appropriate_index;
    switch(count($parts)-$appropriate_index-1) {
        case 0: $content = $appropriate_function(); break;
        case 1: $content = $appropriate_function($parts[$i+1]); break;
        case 2: $content = $appropriate_function($parts[$i+1],$parts[$i+2]); break;
        case 3: $content = $appropriate_function($parts[$i+1],$parts[$i+2],$parts[$i+3]); break;
        case 4: $content = $appropriate_function($parts[$i+1],$parts[$i+2],$parts[$i+3],$parts[$i+4]); break;
        case 5: $content = $appropriate_function($parts[$i+1],$parts[$i+2],$parts[$i+3],$parts[$i+4],$parts[$i+5]); break;
        case 6: $content = $appropriate_function($parts[$i+1],$parts[$i+2],$parts[$i+3],$parts[$i+4],$parts[$i+5],$parts[$i+6]); break;
        case 7: $content = $appropriate_function($parts[$i+1],$parts[$i+2],$parts[$i+3],$parts[$i+4],$parts[$i+5],$parts[$i+6],$parts[$i+7]); break;
        default: log_message("Too many parameters"); $content = $appropriate_function($parts[$i+1],$parts[$i+2],$parts[$i+3]); break;
    }

}

$content = script_uses_head().$content;
if(function_exists("before_content_post")) {
  before_content_post($content);
}

$pagename = $_GET['q'];
$pagename = str_replace(".","",$pagename);
if(!$content && !$appropriate_function) {
    $content = "<h1>ERROR:<br> Can't render '".$_GET['q']."'</h1>";
}

if(isset($GLOBALS['layout'])) {
	$layout = template($GLOBALS['layout']);
} elseif(!isset($template)) {
  $layout = template("main");
}

//used in admin
translate_parse($layout); // {~rus} {~eng}

echo $layout;
unset($_SESSION['flash']);

//don't show mysql not freed etc
ini_set("display_errors",0);
ini_set("mysql.trace_mode",0);

?>
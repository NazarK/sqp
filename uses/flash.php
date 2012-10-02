<?php

function flash($type,$message) {
  if($type=='error') {
    $GLOBALS['flash_errors'] = true;
  }
  
  if(isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
  } else {
	$flash = array();
  }
  if(!isset($flash[$type]))
    $flash[$type] = "";
  $flash[$type] .= $message."<br>";

  $_SESSION['flash'] = $flash;
}

function flash_errors() {
  return isset($GLOBALS['flash_errors']);
}

$template_call['flash_show'] = 1;
$template_call_admin['flash_show'] = 1;
function flash_show($delay=4) {
  $o = "";
  if(isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
	foreach($flash as $key=>$value) {
      $o .= "<div id=flash_{$key} class='flash $key'>$value</div>";
	}
  }
  $o .= "<style> .flash {border: 1px solid #aaa; background: #eee; padding: 10px 20px;} .notice,.message { background: #7FFF7F;} .error { background: #f44; color: #fff; } </style>";
  $delay = $delay*1000;
  $o .= "<script> $(function() { $('.flash').delay($delay).fadeOut(); }) </script>";
  unset($_SESSION['flash']);
  return $o;
}


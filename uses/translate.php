<?php
define("DEFAULT_LANG","orig");

if(!isset($lang_dir)) {
  $_GLOBALS['lang_dir'] = "";
}
if(form_post("lang")) {
	$lang_dir = form_post("lang")."/";
}

function set_lang($value) {
  global $apptitle;
  $_SESSION[$apptitle.'lang'] = $value;
}

function page_lang($value) {
	global $apptitle;
    $_SESSION[$apptitle.'lang'] = $value;
    if(isset($_SERVER['HTTP_REFERER'])) {
      Header("Location: ".$_SERVER['HTTP_REFERER']);
    } else die();    
}

function page_rus() {
  return page_lang("rus");
}

function page_eng() {
  return page_lang("eng");
}


function lang() {
  global $apptitle;
  if(!isset($_SESSION[$apptitle.'lang'])) return DEFAULT_LANG;
  return $_SESSION[$apptitle.'lang'];
}

function is_eng() {
  return lang()=='eng';
}

function is_rus() {
  return lang()=='rus';
}

$dictionary = array();
function trans($s) {
	if(lang()==DEFAULT_LANG) return $s;
	global $dictionary;
	if(count($dictionary)==0) {
		$lines = file("dictionary.txt");
		foreach($lines as $line) {
		  $parts = explode('=',$line);
		  if(isset($parts[1]))
		  $dictionary[$parts[0]] = trim($parts[1]);
		}
	}
	if(!isset($dictionary[$s])) return $s;
	return $dictionary[$s];

}

function _T($s) {
  return trans($s);
}


function __($key) {
	global $dictionary__;
	if(count($dictionary__)==0) {
		$lines = file("dictionary__.txt");
		foreach($lines as $line) {
		  $parts = explode('=',$line);
		  if(isset($parts[1]))
		  $dictionary__[$parts[0]] = trim($parts[1]);
		}
	}

	if(!isset($dictionary__[$key])) return $key;
	return fld_trans($dictionary__[$key]);
}

function dict($key) {
  return __($key);
}

function fld_trans($s,$to_lang="") {

  ini_set("pcre.backtrack_limit",2000000);
  
  global $lang_dir;
  $lang = $lang_dir;
  if($to_lang=="rus" || $to_lang=="ru") {
     $lang = "ru/";
  }
  if($to_lang=="eng" || $to_lang=="en") {
     $lang = "en/";
  }

  
  

  preg_match_all("|{([^}]*)}|",$s,$matches);
  if(isset($matches[1]))
  foreach($matches[1] as $value) {
	  $t = fld_trans($value,$to_lang);
	  $s = str_replace('{'.$value.'}',$t,$s);
  }

  if($lang=="" || $lang=="ru/") {
   preg_match_all("/ru:(.*?)(en:|kz:|$)/s",$s,$matches);
   if(!isset($matches[1][0])) {
	 preg_match_all("/^(.*?)(en:|kz:|$)/s",$s,$matches);
	 if(isset($matches[1][0])) {
	   return trim($matches[1][0]);
	 } else return $s;
   }
   else return trim($matches[1][0]);
  }

  if($lang=="en/") {
   preg_match_all("/en:(.*?)(ru:|kz:|$)/s",$s,$matches);
   if(!isset($matches[1][0])) return $s;
   else return trim($matches[1][0]);
  }

  if($lang=="kz/") {
   preg_match_all("/kz:(.*?)(en:|ru:|$)/s",$s,$matches);
   if(!isset($matches[1][0])) return $s;
   else return trim($matches[1][0]);
  }

  return $s;
}

function page_trans_test() {
  	$s = "en: english
	ru: russian $";
	$trans = fld_trans($s,"en");
	if($trans != 'english') echo "failed";
	else echo "passed";
	$s = file_get_contents("transtest.txt");
	echo preg_last_error();echo "<br>";
	echo strlen($s);
	$trans = fld_trans($s,"en");
	
	echo $trans;
	die("HERE");
}
function fld_trans_old($s) {
  $parts = explode("||",$s);
  if(count($parts)==1)  {
	  $parts = explode("english=",$s);
  }

  if(count($parts)==1)  {
	  $parts = explode("inenglish:",$s);
  }

  if(is_eng()) {
	if(isset($parts[1])) return $parts[1];
	else return $parts[0]; 
  } else {
	return $parts[0];
  }
}

function obj_trans($o) {
  foreach($o as $name=>$value) {
	  $o->$name = fld_trans($o->$name);
  }
  return $o;
}

function eng($s) {
  return fld_trans($s,"eng");
}

$files['dictionary__.txt']['directedit'] = true;
$template_call['__'] = 1;
$template_call['dict'] = 1;

?>
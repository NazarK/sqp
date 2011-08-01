<?php


function page_search_prepare() {
	requires_admin();
	echo "updating content_search value<Br>";
    $rr = db_fetch_objects(db_query("SELECT * FROM pages"));
	foreach($rr as $r) {
	  echo "page p/$r->id ".strlen($r->content)." bytes <br>";
      on_pages_content_update($r->id,$r->content);
	}
	die("DONE");
}

function on_pages_content_update($id,&$value) {
   $s = $value;
   $s = strip_for_search($s);
   $s = mb_strtolower($s,"UTF-8");
   db_query("UPDATE pages SET content_search='%s' WHERE id=%d",$s,$id);
}

function page_search_test() {
  echo "<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
  $_REQUEST['s']='rack';
  echo page_search();

  $_REQUEST['s']='gallery';
  echo page_search();

  $_REQUEST['s']='сервер';
  echo page_search();
  
  die();

}



function strip_for_search($s) {
   $s = strip_tags($s);
   $s = html_entity_decode($s,ENT_COMPAT,"UTF-8");
   $s = preg_replace("|{[^}]*}|","",$s);

   return $s;
}

function page_search($search="") {
  mb_internal_encoding("UTF-8");
  $s = $search;
  if(!$s)
    $s = form_post("s");
  $o = "";
if($s) {
  $rr = db_fetch_objects(db_query("SELECT * FROM pages WHERE content_search like '%%%s%%' LIMIT 10",$s));

  if(count($rr)==0) {
    $o .= "Под запрос <strong>$s</strong> не подходит ни одна страница.";
  } else
  
  foreach($rr as $r) {
	 $r->content = fld_trans(strip_for_search($r->content));
	 $r->content_search = fld_trans($r->content_search);
	 $p = 0;
	 if(mb_strpos($r->content_search,mb_strtolower($s))!==FALSE) {
		 $p = mb_strpos($r->content_search,mb_strtolower($s),0);
		 $r->content = mb_substr($r->content,0,$p)."<strong>".
			 mb_substr($r->content,$p,mb_strlen($s))."</strong>".mb_substr($r->content,$p+mb_strlen($s),mb_strlen($r->content));
	 }
	 $start = $p-200;
	 if($start<0) $start = 0;
	 $r->span = mb_substr($r->content,$start,400);
	 $r->url = translit(fld_trans($r->short,"ru"));
	 $r->short = fld_trans($r->short);
     $GLOBALS['r'] = $r;
     $o .= template("search");
  }
}
  $o .= "<div style='padding-top:20px'><a href=search/google&s=".urlencode($s).">Использовать google поиск по сайту</a></div>";
  return $o;

}


function page_search_google() {
  global $base_url;
  Header("Location: http://www.google.com/search?q=".form_post('s')."&sitesearch=".$base_url);
  die();
}

$template_call['search_param'] = true;
function search_param() {
  if(self_q()=="search") {
    return arg(0);
  }


}
?>
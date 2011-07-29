<?php


function page_pages_vars() {
  db_query("CREATE TABLE [pages] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[short] VARCHAR(80)  NULL,
[content] TEXT  NULL,
[content_search] TEXT NULL,
[fixed] BOOLEAN DEFAULT 'false' NULL,
[weight] INTEGER  NULL,
[category] INTEGER  NULL
)");
  die(" ");
}


function p_quickedit_html($id) {
	  if(admin()) return  "<a style='z-index:9000' target=_blank href=admin/edit/pages/content/$id><img style='z-index:9000' src=images/edit.png></a>";
	  return "";
}

function PageTitle($id) {
	return db_result(db_query("SELECT short FROM pages WHERE id=%d",$id));
}

function page_id_by_title_trans($title) {
    $pages = db_fetch_objects(db_query("SELECT id, short FROM pages"));
	foreach($pages as $p) {
      if(translit($p->short)==$title) {
		  return $p->id;
	  }
	}
}

function page_p($id,$edit=true) {
  if(is_numeric($id)) {
    $page = db_object_get("pages",$id);
  } else {

  }
  if($page) {
    $o = $page->content;
	if(function_exists("on_page_content"))
	   on_page_content($o);
  }
  else
    $o = "not defined";
  if($edit) {
    $o .= p_quickedit_html($id);
  }
  replace_my_tags($o); // {href {f {call
  if(form_post("die")) {
    replace_files($o); // {!something.js} {!something.css}
    replace_globals($o); // {!global} {!global}
    translate_parse($o); // {~rus} {~eng}
	die($o);
  }
  return $o;
}

$tables["pages"]["fields"][] = "short";
$tables["pages"]["liveedit"] = 1;
$tables["pages"]["weight"] = 1;

function page_admin_pages($act="",$id="") {
	requires_admin();
	use_template("admin");
	$o = "";
	if($act=="del") {
		$p = db_object_get("pages",$id);
		if($p->fixed=='Y') {
           $act = "-";
		   $o .= '<script>alert("Эту страницу нельзя удалить.")</script>';
		}
	}
	global $table_edit_props;
	$table_edit_props->col_title_show = false;
//	$table_edit_props->new_record_show = false;
//   $table_edit_props->del_record_show = false;
//    $table_edit_props->edit_record_show = false;
    global $base_url;
	$o .= table_edit("pages","admin/pages",$act,$id,"category","null","",
		"<a href=admin/edit/pages/content/[id]&back=admin/pages><img src=images/text_edit.png atl='Редактировать' title='Редактировать'></a> <a href={$base_url}p/[id]>{$base_url}p/[id]</a>");
	return $o;
}


function WebPageTitle() {
  if(self_q()=="p") {
	  $t = PageTitle(arg(0));
	  if($t)
	  return "$t - ";
	  else
	  return "";
  } else
  if(self_q()=='news') {
	  return "Новости - ";
  } else {
	  return "";
  }
}

function ContentTitle() {
  if(self_q()=="p") {
	  $t = PageTitle(arg(0));
	  return $t;
  } else 
  if(self_q()=="news") {
	  return "Новости";
  } else {
	  return "";
  }

}

function page_id_by_title($title) {
   return db_result(db_query("SELECT id FROM pages WHERE short='%s'",$title));
}

function translit($str) 
{
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"y","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
        " "=> "_", "/"=> "_"
    );
    return strtolower(strtr($str,$tr));
}

function page_admin_pages_new($title) {

}

$page_id = 0;
$link_to_page_id = 0;
function page_check_by_name(&$q) {
	global $page_id, $link_to_page_id;
	//support for page names
	$page_id = page_id_by_title_trans($q);
	$link_to_page_id = $page_id;
	if($page_id) { 
		$q = 'p/'.$page_id;
	}
}
?>
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
  if (admin ())
    return "<a style='z-index:9000' target=_blank href=admin/edit/pages/content/$id><img style='z-index:9000' src=images/bios/edit.png></a>";
  return "";
}

function PageTitle($id) {
  return fld_trans(db_result(db_query("SELECT short FROM pages WHERE id=%d", $id)));
}

function page_id_by_title_trans($title) {
  $pages = db_fetch_objects(db_query("SELECT id, short FROM pages"));
  foreach ($pages as $p) {
    if (translit(fld_trans($p->short, "rus")) == $title) {
      return $p->id;
    }
  }
}

function page_by_title($title,$edit=false) {
  $ret = db_fetch_object(db_query("SELECT * FROM pages WHERE short='%s' LIMIT 1",$title));
  if($edit && admin()) {
    $ret->content = p_quickedit_html($ret->id) . $ret->content;
  }

  return $ret->content;
}

function page_p($id, $edit=true) {
  if (is_numeric($id)) {
    $page = db_object_get("pages", $id);
  } else {

  }
  if ($page) {
    //file_put_contents("content.txt", $page->content);
    $o = fld_trans($page->content);


    if (function_exists("on_page_content"))
      on_page_content($o);
  }
  else
    $o = "not defined";
  if ($edit) {
    $o .= p_quickedit_html($id);
  }
  replace_my_tags($o); // {href {f {call
  if (form_post("die")) {
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

function page_admin_pages($act="", $id="") {
  requires_admin();
  use_layout("admin");
  $o = "";
  if ($act == "del") {
    $p = db_object_get("pages", $id);
    if ($p->fixed == 'Y') {
      $act = "-";
      $o .= '<script>alert("Эту страницу нельзя удалить.")</script>';
    }
  }
  global $table_edit_props;
  $table_edit_props->col_title_show = false;
//	$table_edit_props->new_record_show = false;
//   $table_edit_props->del_record_show = false;
//    $table_edit_props->edit_record_show = false;
  $table_edit_props->use_rename_icon_for_edit = true;
  global $base_url;
  $o .= table_edit("pages", "admin/pages", $act, $id, "category", "null", "weight", "admin_on_page");
  return $o;
}
function admin_on_page($id,$obj) {
  global $base_url;
  $nice_ref = translit($obj["pages.short"]);
  $ret = "<a href=admin/edit/pages/content/[id]&back=admin/pages><img src=images/admin/text_edit.png atl='Редактировать' title='Редактировать'></a> ссылка: <a href={$base_url}p/[id]>/p/[id]</a> или <a href=$base_url$nice_ref>/$nice_ref</a>";
  return $ret;
}

function WebPageTitle() {
  if (self_q() == "p") {
    $t = PageTitle(arg(0));
    if ($t)
      return "$t - ";
    else
      return "";
  } else
  if (self_q() == 'news') {
    return "Новости - ";
  } else {
    return "";
  }
}

$template_call['ContentTitle'] = true;

function ContentTitle() {
  if (self_q() == "p") {
    $t = PageTitle(arg(0));
    return $t;
  } else
  if (self_q() == "news") {
    return "Новости";
  } else {
    return "";
  }
}

function page_id_by_title($title, $lang="rus") {
  $pages = db_fetch_objects(db_query("SELECT id,short FROM pages"));
  foreach ($pages as $page) {
    if (fld_trans($page->short, $lang) == $title) {
      return $page->id;
    }
  }
  return 0;
}

function translit($str) {
  $tr = array(
      "А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
      "Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
      "Й" => "i", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
      "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
      "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
      "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "y", "Ь" => "",
      "Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
      "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
      "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l",
      "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
      "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
      "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
      "ы" => "y", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
      " " => "_", "/" => "_", "?" => "", "!" => "", "," => ""
  );
  return strtolower(strtr($str, $tr));
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
  if ($page_id) {
    $q = 'p/' . $page_id;
  }
}

$template_call['WebPageTitle'] = true;
$template_call['page_p'] = true;
?>
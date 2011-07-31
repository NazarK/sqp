<?php

/*
  Simple Query Parsing PHP CMS
  (c) 2010 nkcomp.com, Dexosoft, Nazar Kuliyev
*/

/*
  application part 
*/

$tables["top_list"]["fields"][] = "email";
$tables["top_list"]["fields"][] = "time";


$tables["users"]["fields"][] = "username";
$tables["users"]["fields"][] = "password";
$tables["users"]["fields"][] = "email";

function page_die() {
  die();
}

function page_pi() {
   phpinfo();
   die();
}

function page_db($tablename="") {
    page_header("database");
    if(mysql) {
        if($tablename) {
            flash("table $tablename structure");
            $res = db_query("DESC $tablename");  
            return htmlquery_code($res);
        } else {
            flash("connecting to database");
            $res = db_query("SHOW TABLES");
            return htmlquery_code($res,"<a href=?q=db/[Tables_in_".MYSQL_DB."]>details</a>");
        }
    }

    if(sqlite2) {
        return htmlquery_code(db_query("SELECT * FROM users"));
    }
}

function page_example($action="",$id="") {
  return table_edit("top_list","example",$action,$id);
}

function page_users($action="",$id="") {
  requires_authorization();
  return table_edit("users","users",$action,$id);
}

function def_q() {
  return "p/4";

}

function page_support() {
  return template();
}

function vert_menu() {
  if(in_admin()) return "{call vert_menu}";
  $page = db_object_get("pages",arg(0));

  $items = db_fetch_objects(db_query("SELECT * FROM menu"));
  $menu = -1;
  foreach($items as $item) {
    if($page->short == fld_trans($item->title,"rus")) {
       while($item->parent_id!=1) {
		   $item = db_object_get("menu",$item->parent_id);
	   }
       $menu = $item->id;
	   break;
	}
  }

  return template("content_vert_menu","menu",menu_with_links($menu));
}

//detect what menu root item is currently active

function menu_banner() {
  global $page_id;
  if($page_id) {
	$p = db_fetch_object(db_query("SELECT * FROM pages WHERE id=%d LIMIT 1",$page_id));
    $page = translit(fld_trans($p->short,"ru"));
  } else
    $page = self_q();

  $menu = menu_root_for_link($page);
  if($menu) {
	  $image = db_fetch_object(db_query("SELECT * FROM images WHERE title like '%%%s%%'",fld_trans($menu->title,"ru")));

	  if($image) {
		return "<img src={$image->link}>";
	  } 
  }
  return "";
}

function vert_menu_if_needed() {
  global $link_to_page_id;
  global $page_id;
  if($page_id) {
	$p = db_fetch_object(db_query("SELECT * FROM pages WHERE id=%d LIMIT 1",$page_id));
    $page = translit(fld_trans($p->short,"ru"));
  } else
    $page = self_q();

  $menu = menu_root_for_link($page);
  if($menu && $menu->id==2) return "";  
  if($link_to_page_id) return vert_menu();
}
?>
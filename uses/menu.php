<?php

function page_menu_vars() {
	
	db_query("
CREATE TABLE [menu] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[parent_id] INTEGER  NULL,
[title] VARCHAR(80)  NULL,
[fixed] BOOLEAN DEFAULT 'false' NULL,
[weight] INTEGER  NULL,
[link] VARCHAR(80)  NULL
)");


}


function menu_path($sub_id) {
	$o = "<a href=?q=admin/menu/edit>Меню</a>&nbsp;";
     $item = db_object_get("menu",$sub_id);
     $menupath = "<a href=?q=admin/menu/edit/$sub_id>$item->title</a>";
	 while($item->parent_id) {
	   $item = db_object_get("menu",$item->parent_id);
	   $menupath = "<a href=?q=admin/menu/edit/$item->id>$item->title</a>"." > $menupath";
	 }
	 $o .= "> $menupath<br>";
	 return $o;
}

function page_admin_menu_edit($parent_id="",$act="",$id="") {
	requires_admin();
	set_lang("other");
	use_template("admin");
    if(!$parent_id) $parent_id = 0;

	$o = "";
	if($act=="del") {
		$rec = db_object_get("menu",$id);
		if($rec->fixed=='Y') {
           $act = "-";
		   $o .= '<script>alert("Эту запись нельзя удалить.")</script>';
		}
	}

    global $tables;

    $tables['menu']['fields'][] = "title";
    $tables['menu']['fields'][] = "link";
	$tables['menu']['weight'] = true;


    if($parent_id) { 
	  $o .= menu_path($parent_id);
	}
    
    $o .= table_edit("menu","admin/menu/edit/$parent_id",$act,$id,"parent_id",$parent_id,"","on_menu"

	  );


	return $o;

}

function on_menu($id) {
  $count = db_result(db_query("SELECT count(1) FROM menu WHERE parent_id=%d",$id));
  $o = "<a href=admin/menu/edit/$id title='Подменю'><img src=images/menu.png></a>";
  if($count) $o .= "($count)";
  return $o;
}



function menu_items($parent_id) {
  $items = db_fetch_objects(db_query("SELECT * FROM menu WHERE parent_id=%d ORDER BY weight",$parent_id));
  return $items;
}

function menu_line($parent_id) {
  $items = menu_items($parent_id);
  $o = "";
  foreach($items as $item) {
	 if(!$item->link) $item->link = "m/$item->id";
     $o .= "<a href=$item->link>$item->title</a>";
  }
  return $o;
}

function page_m($id) {
   $m = db_object_get("menu",$id);

   if(!$m->link) {
     $o = "Меню - пустая ссылка";

	 if(!form_post('die')) return $o;
	 else { echo $o; die(); }
   }
   Header("Location: $m->link");
   die();
}

function menu_vertical($parent_id,$level=0) {
  $items = menu_items($parent_id);
  if($level=="") $level = 0;
  $o = "";
  foreach($items as $item) {
	  $href = $item->link;
	  if(!$href) {
		  $href = "m/$item->id";
	  }
      $o .= "<div class=menu_item level=$level parent_id=$parent_id item_id=$item->id><a href=$href>$item->title</a></div>";
	  $o .= menu_vertical($item->id,$level+1);
  }

  return $o;
}


function menu_page_link($parent_id,$index) {
  $items = menu_items($parent_id);
  $item = $items[$index];
  $link = $item->link;
  if(!$link) {
     $id = page_id_by_title($item->title);
	 if($id) {
	   $link = "p/$id";
	 }
  }
	 
  return "<a class=menuItem href=$link>$item->title</a>";
}

function page_menu_no_page($menu_id) {
  $menu = db_object_get("menu",$menu_id);
  return "Страница с названием '$menu->title' не найдена. Создайте страницу с названием '$menu->title'.";
}

function menu_with_links($parent_id) {
 $items = menu_items($parent_id);

 foreach($items as &$item) {
   if(!$item->link) {
     $page = page_id_by_title($item->title);
	 $item->altlink = "";
	 if($page) {
       $item->link = translit($item->title);
	   $item->altlink = 'p/'.$page;
	 } 
   }
 }

 $o = "";

 foreach($items as &$item) {
   if(!$item->link) {
     $item->link = "menu_no_page/".$item->id;    
     $item->altlink = "menu_no_page/".$item->id;    
   }
   $o .= "<div class=menuItemDiv><a class=menuItem href='{$item->link}'  althref='{$item->altlink}'>$item->title</a></div>";
 }

 return $o;
}

function menu_with_images_and_links($parent_id) {

 $items = menu_items($parent_id);

 foreach($items as &$item) {
   if(!$item->link) {
     $page = page_id_by_title($item->title);
	 if($page) {
       $item->link = "p/$page";
	 }
   }
   $item->img = db_result(db_query("SELECT link FROM images WHERE title='%s'",$item->title));
 }


 $o = "";

 foreach($items as &$item) {
   $img = "";
   if($item->img) $img = "<img src=$item->img>";
   $o .= "<div class=menuItemDiv><a class=menuItem href={$item->link}>$img</a></div>";
 }

 return $o;

}

?>
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
	 $item->title = fld_trans($item->title,"rus");
     $menupath = "<a href=?q=admin/menu/edit/$sub_id>$item->title</a>";
	 while($item->parent_id) {
	   $item = db_object_get("menu",$item->parent_id);
	   $item->title = fld_trans($item->title,"rus");
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

	if($act=="edit" && form_post("edit")) {
		$obj = db_object_get("menu",$id);
        $page_id = page_id_by_title(fld_trans($obj->title,"rus"));
		if($page_id) {
          db_query("UPDATE pages SET short='%s' WHERE id=%d",fld_trans(form_post("title"),"rus"),$page_id);
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

function page_admin_menu_page_attach($id) {
  $menu = db_object_get("menu",$id);
  $page_title = fld_trans($menu->title,"rus");
  db_query("INSERT INTO pages (short) VALUES ('%s')",$page_title);
  $page_id = db_last_id();
  redir("admin/edit/pages/content/$page_id&back=".form_post("back"));
}

function on_menu($id) {
  $obj = db_object_get("menu",$id);
  $page_id = page_id_by_title(fld_trans($obj->title,"rus"));
  $count = db_result(db_query("SELECT count(1) FROM menu WHERE parent_id=%d",$id));
  $o = "";
  $o .= "<a href=admin/menu/edit/$id title='Подменю'>--> </a>";//<img src=images/menu.png>
  if($count) $o .= "($count)";
  if($page_id)
    $o .= " <a href=admin/edit/pages/content/$page_id&back=admin/menu/edit/$obj->parent_id><img src='images/text_edit.png'></a>";
  else
	$o .= " <a href=admin/menu/page_attach/$id&back=admin/menu/edit/$obj->parent_id><img src='images/text_edit.png'></a>";
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
  $menu->title = fld_trans($menu->title,"rus");
  return "Страница с названием '$menu->title' не найдена. Создайте страницу с названием '$menu->title'.";
}

function menu_with_links($parent_id,$level=0) {
 $items = menu_items($parent_id);

 foreach($items as &$item) {
   if(!$item->link) {
     $page = page_id_by_title(fld_trans($item->title,"rus"));
	 $item->altlink = "";
	 if($page) {
       $item->link = translit(fld_trans($item->title,"rus"));
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
   $sub = menu_with_links($item->id,$level+1);
   global $lang_dir;
   $item->title = fld_trans($item->title);
   $lv = $level+1;
   $o .= "<div class='menuItemDiv level{$lv}'><div class=title><a class='menuItem caption' href='$lang_dir{$item->link}'  althref='{$item->altlink}'>$item->title</a></div><div class=subMenu>$sub</div></div>";
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

function site_menu_path($sub_id) {
     $item = db_object_get("menu",$sub_id);
	 $menupath = "";
	 while($item->parent_id) {
		 $page = page_id_by_title(fld_trans($item->title,"rus"));
		 $item->altlink = "";
		 if($page) {
		   $item->link = translit(fld_trans($item->title,"rus"));
		   $item->altlink = 'p/'.$page;
		 } 
		 $item->title = fld_trans($item->title);
		 $menupath = " / <a href=$item->link>$item->title</a>".$menupath;
	     $item = db_object_get("menu",$item->parent_id);
	 }
	 if($menupath) {
        $menupath = substr($menupath,2);
	 }
	 return $menupath;
}

function current_menu_path() {
  global $page_id;

  $page = db_object_get("pages",$page_id);
  $items = db_fetch_objects(db_query("SELECT * FROM menu"));
  $search = fld_trans($page->short,"rus");
  foreach($items as $item) {
    if(fld_trans($item->title,"rus")==$search) {
       return site_menu_path($item->id);
	}
  }
  return "";
}


function menu_root($item) {
  while(true) {
	if(!$item->parent_id) return $item;
	$parent = db_object_get("menu",$item->parent_id);  
	if(!$parent->parent_id) return $item;
	$item = $parent;
  }
}

function menu_root_for_link($link) {

  $menu_items = db_fetch_objects(db_query("SELECT * FROM menu"));

  foreach($menu_items as $item) {
     if($item->link == $link) {
		 return menu_root($item);
	 }

	 if(translit(fld_trans($item->title,"ru"))==$link) {
         return menu_root($item);
	 }
  }

  return 0;
}


$template_call['menu_with_links'] = true;
?>
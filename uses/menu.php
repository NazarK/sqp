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
  $item = db_object_get("menu", $sub_id);
  $item->title = fld_trans($item->title, "rus");
  $menupath = "<a href=?q=admin/menu/edit/$sub_id>$item->title</a>";
  while ($item->parent_id) {
    $item = db_object_get("menu", $item->parent_id);
    $item->title = fld_trans($item->title, "rus");
    $menupath = "<a href=?q=admin/menu/edit/$item->id>$item->title</a>" . " > $menupath";
  }
  $o .= "> $menupath<br>";
  return $o;
}

function page_admin_menu_edit($parent_id="", $act="", $id="") {
  requires_admin();
  set_lang("other");
  use_layout("admin");
  if (!$parent_id)
    $parent_id = 0;

  $o = "";
  if ($act == "del") {
    $rec = db_object_get("menu", $id);
    if ($rec->fixed == 'Y') {
      $act = "-";
      $o .= '<script>alert("Эту запись нельзя удалить.")</script>';
    }
  }


  global $tables;

  $tables['menu']['fields'][] = "title";
  $tables['menu']['fields'][] = "link";
  $tables['menu']['weight'] = true;


  if ($parent_id) {
    $o .= menu_path($parent_id);
  }

  global $table_edit_props;
  $table_edit_props->use_rename_icon_for_edit = true;

  $o .= table_edit("menu", "admin/menu/edit/$parent_id", $act, $id, "parent_id", $parent_id, "", "on_menu");
  $o .= "<style> input[type='submit'] { padding: 5px 10px; width: auto;}
	  input{ width:400px; }
	</style>";


  return $o;
}

function menu_page_title($id) {
  $res = "";
  while (true) {
    $menu = db_object_get("menu", $id);
    $add_title = fld_trans($menu->title, "rus");

    $id = $menu->parent_id;
    if (!$id)
      break;

    if ($res)
      $res = "{$add_title} | {$res}";
    else
      $res = $add_title;
  }
  return $res;
}

function page_admin_menu_page_attach($id) {
  $page_title = menu_page_title($id);
  db_query("INSERT INTO pages (short) VALUES ('%s')", $page_title);
  $page_id = db_last_id();
  db_query("UPDATE menu SET page_id=%d WHERE id=%d", $page_id, $id);
  redir("admin/edit/pages/content/$page_id&back=" . form_post("back"));
}

function on_menu($id) {
  $obj = db_object_get("menu", $id);
  if (!$obj->page_id) {
    $page_id = page_id_by_title(fld_trans($obj->title, "rus"));
    if ($page_id) {
      db_query("UPDATE menu SET page_id=%d WHERE id=%d", $page_id, $id);
      $obj->page_id = $page_id;
    }
  } else {
    if (!db_object_get("pages", $obj->page_id)) {
      $obj->page_id = 0;
      db_query("UPDATE menu SET page_id=0 WHERE id=%d", $id);
    }
  }

  $page_id = $obj->page_id;

  $count = db_result(db_query("SELECT count(1) FROM menu WHERE parent_id=%d", $id));
  $o = "";
  $o .= "<a href=admin/menu/edit/$id title='Подменю'>--> </a>"; //<img src=images/menu.png>
  if ($count)
    $o .= "($count)";
  if ($page_id)
    $o .= " <a href=admin/edit/pages/content/$page_id&back=admin/menu/edit/$obj->parent_id><img src='images/admin/text_edit.png'></a>";
  else
    $o .= " <a href=admin/menu/page_attach/$id&back=admin/menu/edit/$obj->parent_id><img src='images/admin/text_edit.png'></a>";
  return $o;
}

function menu_items($parent_id) {
  $items = db_fetch_objects(db_query("SELECT * FROM menu WHERE parent_id=%d ORDER BY weight", $parent_id));

  foreach($items as $item) {
    if(!$item->link) {
      $item->link = translit(fld_trans($item->title));
    }
  }
  return $items;
}

function menu_line($parent_id) {
  $items = menu_items($parent_id);
  $o = "";
  foreach ($items as $item) {
    if (!$item->link)
      $item->link = "m/$item->id";
    $o .= "<a href=$item->link>$item->title</a>";
  }
  return $o;
}

function page_m($id) {
  $m = db_object_get("menu", $id);

  if (!$m->link) {
    $o = "Меню - пустая ссылка";

    if (!form_post('die'))
      return $o;
    else {
      echo $o;
      die();
    }
  }
  Header("Location: $m->link");
  die();
}

function menu_vertical($parent_id, $level=0) {
  $items = menu_items($parent_id);
  if ($level == "")
    $level = 0;
  $o = "";
  foreach ($items as $item) {
    $href = $item->link;
    if (!$href) {
      $href = "m/$item->id";
    }
    $o .= "<div class=menu_item level=$level parent_id=$parent_id item_id=$item->id><a href=$href>$item->title</a></div>";
    $o .= menu_vertical($item->id, $level + 1);
  }

  return $o;
}

function menu_page_link($parent_id, $index) {
  $items = menu_items($parent_id);
  $item = $items[$index];
  $link = $item->link;
  if (!$link) {
    $id = page_id_by_title($item->title);
    if ($id) {
      $link = "p/$id";
    }
  }

  return "<a class=menuItem href=$link>$item->title</a>";
}

function page_menu_no_page($menu_id) {
  $menu = db_object_get("menu", $menu_id);
  $menu->title = fld_trans($menu->title, "rus");
  return "Страница с названием '$menu->title' не найдена. Создайте страницу с названием '$menu->title'.";
}

function menu_first_link($parent_id) {
  $items = menu_items($parent_id);

  foreach ($items as &$item) {
    if (!$item->link) {
      $item->link = translit(fld_trans($item->title, "rus"));
      $item->altlink = "";
      if ($item->page_id) {
        $item->altlink = 'p/' . $item->page_id;
      }
    }
    break;
  }

  return $items[0]->link;
}

function menu_url($id) {
  $res = "";
  while (true) {
    $menu = db_object_get("menu", $id);
    $add_url = translit(fld_trans($menu->title, "rus"));

    $id = $menu->parent_id;
    if (!$id)
      break;

    if ($res)
      $res = "{$add_url}/{$res}";
    else
      $res = $add_url;
  }
  return $res;
}

function menu_with_links($parent_id, $level=0) {
  $items = menu_items($parent_id);
  $menu_path = menu_url($parent_id);
  if ($menu_path)
    $menu_path = $menu_path . "/";

  foreach ($items as &$item) {
    if (!$item->link) {
      $item->link = $menu_path . translit(fld_trans($item->title, "rus"));
      
      $item->altlink = "";
      if ($item->page_id) {
        $item->altlink = '/p/' . $item->page_id;
      }
    }
  }

  $o = "";
  
  $site_folder = site_folder();
  foreach ($items as &$item) {
    if (!$item->link) {
      $item->link = "menu_no_page/" . $item->id;
      $item->altlink = "menu_no_page/" . $item->id;
    }
    $sub = menu_with_links($item->id, $level + 1);
    global $lang_dir;
    $active_class = "";

	if($item->title==$GLOBALS['menu__active_item__title_full']) {
		$active_class = "active";
	}

    $item->title = fld_trans($item->title);
    $lv = $level + 1;
    if ($sub) 
      $sub = "<div class=subMenu>$sub</div>";
    else
      $sub = "";


    #$o .= "<div class='menuItemDiv level{$lv}'><div class=title><a class='menuItem caption' href='$lang_dir{$item->link}'  althref='{$item->altlink}'>$item->title</a></div>$sub</div>";
    $class = array();
    if ($item==$items[count($items)-1]) {
      $class[] = "last";
    }

    if ($item==$items[0]) {
      $class[] = "first";
    }

    global $menu_id;

    if($item->id == $menu_id) {
      $class[] = "current-menu-item";
    }

    if(count($class))
      $class = " class='".implode(" ",$class)."' ";
    else
      $class = "";

    $o .= "<li$class><a class='$active_class' href='$site_folder$lang_dir{$item->link}'  data-althref='{$item->altlink}'>$item->title</a></li> $sub";
  }

  return $o;
}

function menu_with_images_and_links($parent_id) {

  $items = menu_items($parent_id);

  foreach ($items as &$item) {
    if (!$item->link) {
      $page = page_id_by_title($item->title);
      if ($page) {
        $item->link = "p/$page";
      }
    }
    $item->img = db_result(db_query("SELECT link FROM images WHERE title='%s'", $item->title));
  }


  $o = "";

  foreach ($items as &$item) {
    $img = "";
    if ($item->img)
      $img = "<img src=$item->img>";
    $o .= "<div class=menuItemDiv><a class=menuItem href={$item->link}>$img</a></div>";
  }

  return $o;
}

function site_menu_path($sub_id) {
  $item = db_object_get("menu", $sub_id);
  $menupath = "";
  while ($item->parent_id) {
    $page = page_id_by_title(fld_trans($item->title, "rus"));
    $item->altlink = "";
    if ($page) {
      $item->link = translit(fld_trans($item->title, "rus"));
      $item->altlink = 'p/' . $page;
    }
    $item->title = fld_trans($item->title);
    $menupath = " / <a href=$item->link>$item->title</a>" . $menupath;
    $item = db_object_get("menu", $item->parent_id);
  }
  if ($menupath) {
    $menupath = substr($menupath, 2);
  }
  return $menupath;
}

function current_menu_path() {
  global $page_id;

  $page = db_object_get("pages", $page_id);
  $items = db_fetch_objects(db_query("SELECT * FROM menu"));
  $search = fld_trans($page->short, "rus");
  foreach ($items as $item) {
    if (fld_trans($item->title, "rus") == $search) {
      return site_menu_path($item->id);
    }
  }
  return "";
}

function menu_root($item) {
  while (true) {
    if (!$item->parent_id)
      return $item;
    $parent = db_object_get("menu", $item->parent_id);
    if (!$parent->parent_id)
      return $item;
    $item = $parent;
  }
}

function menu_root_for_link($link) {

  $menu_items = db_fetch_objects(db_query("SELECT * FROM menu"));

  foreach ($menu_items as $item) {
    if ($item->link == $link) {
      return menu_root($item);
    }

    if (translit(fld_trans($item->title, "ru")) == $link) {
      return menu_root($item);
    }
  }

  return 0;
}

function menu_id_by_title_trans($title, $parent_id=-1) {
  if ($parent_id == -1)
    $menu = db_fetch_objects(db_query("SELECT id, title FROM menu"));
  else
    $menu = db_fetch_objects(db_query("SELECT id, title FROM menu WHERE parent_id=%d", $parent_id));
  foreach ($menu as $m) {
    if (translit(fld_trans($m->title)) == $title) {
	  $GLOBALS['menu__active_item__title_full'] = $m->title;
      return $m->id;
    }
  }
  return false;
}

function other_lang_url($lang) {
	if(!isset($GLOBALS['menu__active_item__title_full'])) return "";
	$menu_title = $GLOBALS['menu__active_item__title_full'];
	return translit(fld_trans($menu_title,$lang));
}

$template_call['menu_with_links'] = true;
$template_call['current_menu_path'] = true;

function menu_check_by_name(&$q) {
  global $page_id, $link_to_page_id;
  global $menu_id;
  $parts = explode("/", $q);

  $parent_menu = -1;
  $i = 0;
  while (true) {
    $menu_id = menu_id_by_title_trans($parts[$i], $parent_menu);
    $i++;
    if (!$menu_id)
      return;
    $obj = db_object_get("menu", $menu_id);
    $parent_menu = $menu_id;
    if ($i == count($parts)) {
      if ($obj->page_id) {
        $page_id = $obj->page_id;
        $link_to_page_id = $page_id;
        $q = 'p/' . $page_id;
        break;
      } else {
        return;
      }
    }
  }
}

?>
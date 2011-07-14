<?php
$tables['catalog']['liveedit'] = true;
function page_catalog_vars() {
  db_query("CREATE TABLE [catalog] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[title] VARCHAR(80)  NULL,
[specifications] TEXT  NULL,
[parent_id] INTEGER DEFAULT '0' NULL,
[specifications_search] TEXT NULL,
[fixed] BOOLEAN DEFAULT 'false' NULL,
[weight] INTEGER  NULL
)");
  db_query("
CREATE TABLE [catalog_images] (
[id] INTEGER  NOT NULL PRIMARY KEY,
[catalog_id] INTEGER  NULL,
[image_file] VARCHAR(40)  NULL,
[weight] INTEGER  NULL
)");

  die(" ");
}

function page_admin_catalog_edit($parent_id="",$act="",$id="") {
	requires_admin();
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

    $tables['catalog']['fields'][] = "title";
	$tables['catalog']['weight'] = true;


    $o .= catalog_admin_path($parent_id);

  if(form_file_uploaded("file")) {
	$fname = $_FILES["file"]['name'];
    $ext  = strtolower(fileext($fname));
	if(!($ext=="swf" || $ext=="jpg" || $ext=="gif" || $ext=="png" || $ext=="bmp" || $ext=="jpeg" || $ext=="pdf")) {
	  $o = "Данный тип файла не является картинкой";       
	  return $o;
	} else {
		@unlink("img/upload/catalog/".db_result(db_query("SELECT image_file FROM catalog WHERE id=%d",$id)));

		$fname = $id.".".fileext($fname);

		form_file_uploaded_move("file","img/upload/catalog/".$fname);
		db_query("UPDATE catalog SET image_file='$fname' WHERE id=%d",$id);
		//redir("admin/images");
	}
  }
	
    $o .= table_edit("catalog","admin/catalog/edit/$parent_id",$act,$id,"parent_id",$parent_id,"","on_catalog" );

   
	if($act=="edit" && catalog_item_level($id)==2) {
	  $o .= "<a class=admin_menu target=_blank href=catalog/view/$id>Просмотр на сайте</a>&nbsp;<br>";

	  $o .= "<a href=admin/edit/catalog/description/$id&back=admin/catalog/edit/$parent_id/edit/$id class=admin_menu>Описание</a>&nbsp;";

	  $o .= "<a href=admin/edit/catalog/overview/$id&back=admin/catalog/edit/$parent_id/edit/$id class=admin_menu>Overview</a>&nbsp;";

	  $o .= "<a href=admin/edit/catalog/specifications/$id&back=admin/catalog/edit/$parent_id/edit/$id class=admin_menu>Specifications</a>&nbsp;";

	  $o .= "<a href=admin/edit/catalog/download/$id&back=admin/catalog/edit/$parent_id/edit/$id class=admin_menu>Download</a>&nbsp;";

	  $o .= "<a href=admin/edit/catalog/support/$id&back=admin/catalog/edit/$parent_id/edit/$id class=admin_menu>Support</a>&nbsp;";

	  $o .= "<a href=admin/edit/catalog/related/$id&back=admin/catalog/edit/$parent_id/edit/$id class=admin_menu>Related Products</a>&nbsp;<br>";

	  $o .= "<a href=admin/catalog/images/$id class=admin_menu>Изображения</a><br>";
	  $rr = db_fetch_objects(db_query("SELECT * FROM catalog_images WHERE catalog_id=$id ORDER BY weight"));
	  foreach($rr as $r) {
         $o .= "<img width=200px src=img/upload/catalog/$r->image_file>";
	  }
	}

	if($act=="add" && catalog_item_level($parent_id)==1) {
      $o .= "Изображение можно будет загрузить после добавления товара.";
	}
    $o .= "<script>
$(function() {
  $('input[name=title]').css('width','400px');

});

	</script>";
	return $o;
}

function page_admin_catalog_images($catalog_id="",$act="",$id="") {
	  requires_admin();
	  use_template("admin");
	  global $tables;
  	  $tables['catalog_images']['weight'] = 1;
	  $o = "";
      if($act=="edit") {
	    $o .= "<a href=admin/catalog/images/$catalog_id><<Назад</a><br>";
	  } else {
	    $catalog = db_object_get("catalog",$catalog_id);
	    $o .= "<a href=admin/catalog/edit/{$catalog->parent_id}/edit/$catalog_id><<Назад</a><br>";
	  }

      if($act=="del") {
		$fname = db_object_get("catalog_images",$id)->image_file;
        @unlink("img/upload/catalog/$fname");
	  }

	  if($act=="edit") {
		  form_start("","post"," enctype='multipart/form-data' ");
		  form_file("Файл","file");
		  form_submit("Загрузить картинку","submit");
		  form_end();

		  $upload = form();
          $upload .= "<script>
             $(function() {
				 $('input[name=submit]').remove();
                 $('input[name=file]').change( function() {
					 $('form').submit();
	              });
	  });
		  </script>";
		  $o .= $upload;

		  if(form_file_uploaded("file")) {
			$fname = $_FILES["file"]['name'];
			$ext  = strtolower(fileext($fname));
			if(!($ext=="swf" || $ext=="jpg" || $ext=="gif" || $ext=="png" || $ext=="bmp" || $ext=="jpeg" || $ext=="pdf")) {
			  $o = "Данный тип файла не является картинкой";       
			  return $o;
			} else {
				@unlink("img/upload/catalog/".db_result(db_query("SELECT image_file FROM catalog_images WHERE id=%d",$id)));

				$fname = $id.".".fileext($fname);
				form_file_uploaded_move("file","img/upload/catalog/".$fname);
				db_query("UPDATE catalog_images SET image_file='$fname' WHERE id=%d",$id);
				redir("admin/catalog/images/$catalog_id");
			}
		  }
	  
	  
	  }

	  if($act=="add") {
		  $_REQUEST['add'] = true;
	  }
	  global $table_edit_props;

      $table_edit_props->add_redir = false;

      $o .= table_edit("catalog_images","admin/catalog/images/$catalog_id",$act,$id,"catalog_id",$catalog_id,"","on_catalog_image" );

	  if($act=="add") {
		$id = db_last_id();
        redir("admin/catalog/images/$catalog_id/edit/".$id);
		die();
	  }
	  return $o;
}

function on_catalog_image($id) {
  $obj = db_object_get("catalog_images",$id);
  return "<img height=100px src=img/upload/catalog/$obj->image_file>";
}

function catalog_item_level($id) {
  $level = 0;
  while(true) {
    $obj = db_object_get("catalog",$id);
	if(!$obj) break;
	if(!$obj->parent_id) break;
	$level++;
	$id = $obj->parent_id;
  }
  return $level;
}
function catalog_items_count($id) {
  return db_result(db_query("SELECT count(1) FROM catalog WHERE parent_id=%d",$id));
}

function on_catalog($id) {
  $o = "";
  $level = catalog_item_level($id);
  if($level==0) {
    $count = catalog_items_count($id);
    $o .= "<a href=admin/catalog/edit/$id>--></a>";
    if($count) $o .= "($count)";
  }

  if($level==1) {
    $count = catalog_items_count($id);
    $o .= "<a href=admin/catalog/edit/$id>--></a>";
    if($count) $o .= "($count)";
  }

/*  if($level==2)
    $o .= " <a href=admin/edit/catalog/specifications/$id><img src=images/text_edit.png></a>";*/
  return $o;
}

function catalog_admin_path($sub_id) {
	$o = "<a href=?q=admin/catalog/edit>Каталог</a>&nbsp;";
	if(!$sub_id) return $o;
     $item = db_object_get("catalog",$sub_id);
     $path = "<a href=?q=admin/catalog/edit/$sub_id>$item->title</a>";
	 while($item->parent_id) {
	   $item = db_object_get("catalog",$item->parent_id);
	   $path = "<a href=?q=admin/catalog/edit/$item->id>$item->title</a>"." > $path";
	 }
	 $o .= "> $path<br>";
	 return $o;
}

function catalog_path($id) {
	global $lang_dir;
	$o = "<a href={$lang_dir}catalog>Каталог</a>&nbsp;";
	$obj = db_object_get("catalog",$id);
	$parent = db_object_get("catalog",$obj->parent_id);
	$grand = db_object_get("catalog",$parent->parent_id);

	$o .= " / <a href={$lang_dir}catalog/".to_url($grand->title).">$grand->title</a>";
	$o .= " / <a href={$lang_dir}catalog/".to_url($grand->title)."/".to_url($parent->title).">$parent->title</a>";
	return $o;
}

function catalog_items($parent_id) {
  return db_fetch_objects(db_query("SELECT * FROM catalog WHERE parent_id=%d ORDER BY weight",$parent_id));
}

function catalog_images($id) {
  return db_fetch_objects(db_query("SELECT * FROM catalog_images WHERE catalog_id=%d ORDER BY weight",$id));
}
function page_catalog($folder="",$subfolder="") {
  $o = "";
  if($folder) {
    $items = catalog_items(0);
	foreach($items as $item) {
      if(to_url($item->title)==$folder) {
		   if(!$subfolder) {
			   $subfolders = catalog_items($item->id);
			   foreach($subfolders as $subfolder) {
			      $o .= "<div id=subfolder>";
				  $subfolder->url = to_url($subfolder->title);
                  $o .= "<div id=title><a href=catalog/$folder/$subfolder->url>$subfolder->title</a></div>";
                  $o .= "<div id=items>";
				  $subfolder_items = catalog_items($subfolder->id);
				  $count = 0;
                  foreach($subfolder_items as $item) {
					  if($count>3) break;
					  $img = catalog_images($item->id);
					  $img = $img[0]->image_file;
					  $o .= template("catalog_item","title",$item->title,"img",$img,"item_id",$item->id);
					  $count++;
				  }
    			  $o .= "</div>";
    			  $o .= "</div>";

			   }
		   } else {
			   $search = $subfolder;
			   $subfolders = catalog_items($item->id);
			   foreach($subfolders as $subfolder) {
				   if(to_url($subfolder->title)==$search) {
					  $o .= "<div id=subfolder>";
					  $o .= "<div id=title>$subfolder->title</div>";
                      $o .= "<div id=items>";
					  $subfolder_items = catalog_items($subfolder->id);
					  $count = 0;
					  foreach($subfolder_items as $item) {
						  if($count>3) break;
						  $img = catalog_images($item->id);
						  $img = $img[0]->image_file;
						  $o .= template("catalog_item","title",$item->title,"img",$img,"item_id",$item->id);
						  $count++;
					  }
					  $o .= "</div>";
					  $o .= "</div>";
				   }
			   }



		   }
	  }
	}
  }

  return template("catalog","items",$o);
}

function to_url($s) {
	$s = strtolower($s);
	$s = str_replace(" ","-",$s);
	return $s;
}
function catalog_menu() {
  $items = db_fetch_objects(db_query("SELECT id,title FROM catalog WHERE parent_id=0 ORDER BY weight"));
  $o = "";

  global $lang_dir;
  foreach($items as $item) {
	$url = to_url($item->title);
    $o .= "<div id=folder><div id=title><a href={$lang_dir}catalog/$url>$item->title</a></div>"; 

	$sub_items = db_fetch_objects(db_query("SELECT id,title FROM catalog WHERE parent_id=$item->id ORDER BY weight"));

	foreach($sub_items as $sub) {
	   $sub_url = to_url($sub->title);
       $o .= "<div id=subItem><a href={$lang_dir}catalog/$url/$sub_url>$sub->title</a></div>";
	}

	$o .= "</div>";

  }

  return $o;
}

function page_catalog_view($id) {
  $GLOBALS["item"] = obj_trans(db_object_get("catalog",$id));

  $GLOBALS["images"] = catalog_images($id);
  return template("catalog_view");
}


function catalog_menu_with_links($parent_id) {
 $items = catalog_items(0);

 global $lang_dir;
 foreach($items as &$item) {
   $item->link = "{$lang_dir}catalog/".to_url($item->title);
   $item->altlink = $item->link;
 }

 $o = "";

 foreach($items as &$item) {
   $o .= "<div class=menuItemDiv><a class=menuItem href='{$item->link}'  althref='{$item->altlink}'>$item->title</a></div>";
 }

 return $o;
}

function catalog_edit_html($id) {
  if(admin()) {
    $parent_id = db_object_get("catalog",$id)->parent_id;
    return "<a target=_blank href=admin/catalog/edit/$parent_id/edit/$id><img src=images/edit.png></a>";
  } else {
	return "";
  }

}


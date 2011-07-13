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


    if($parent_id) { 
	  $o .= catalog_path($parent_id);
	}
    
    $o .= table_edit("catalog","admin/catalog/edit/$parent_id",$act,$id,"parent_id",$parent_id,"","on_catalog" );


	return $o;
}

function on_catalog($id) {
  $o = "";
  $obj = db_object_get("catalog",$id);
  if(!$obj->parent_id) {
    $count = db_result(db_query("SELECT count(1) FROM catalog WHERE parent_id=%d",$id));
    $o .= "<a href=admin/catalog/edit/$id>продукты</a>";
    if($count) $o .= "($count)";
  }
  if($obj->parent_id)
  $o .= " <a href=admin/edit/catalog/specifications/$id><img src=images/text_edit.png></a>";
  return $o;
}

function catalog_path($sub_id) {
	$o = "<a href=?q=admin/catalog/edit>Каталогs</a>&nbsp;";
     $item = db_object_get("catalog",$sub_id);
     $path = "<a href=?q=admin/catalog/edit/$sub_id>$item->title</a>";
	 while($item->parent_id) {
	   $item = db_object_get("catalog",$item->parent_id);
	   $path = "<a href=?q=admin/catalog/edit/$item->id>$item->title</a>"." > $path";
	 }
	 $o .= "> $path<br>";
	 return $o;
}


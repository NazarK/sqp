<?php
$tables["images"]["fields"][] = "link";
$tables["images"]["fields"][] = "title";

function page_images_vars() {
	db_query("
CREATE TABLE [images] (
[id] INTEGER  PRIMARY KEY NOT NULL,
[link] VARCHAR(80)  NULL)");

}
function page_admin_images($act="",$id="") {
  requires_admin();
  use_template("admin");

  form_start("","post"," enctype='multipart/form-data' ");
  form_file("Файл","file");
  form_submit("Загрузить картинку","submit");
  form_end();

  $upload = form();

  if(form_file_uploaded("file")) {
	$fname = $_FILES["file"]['name'];
    $ext  = strtolower(fileext($fname));
	if(!($ext=="swf" || $ext=="jpg" || $ext=="gif" || $ext=="png" || $ext=="bmp" || $ext=="jpeg" || $ext=="pdf")) {
	  $o = "Данный тип файла не является картинкой";       
	  return $o;
	} else {
		if($act=="add") {
		  db_query("INSERT INTO images (link) VALUES ('')");
		  $id = db_last_id();
		} else {
		  @unlink(db_result(db_query("SELECT link FROM images WHERE id=%d",$id)));
		}

		$fname = $id.".".fileext($fname);

		form_file_uploaded_move("file","img/".$fname);
		db_query("UPDATE images SET link='img/$fname' WHERE id=%d",$id);
		redir("admin/images");
	}
  }

  if($act=="add") {
	$o = $upload;
	return $o;

  }

  if($act=="del") {
    $im = db_object_get("images",$id);
	unlink("$im->link");
  }

  $o = table_edit("images","admin/images",$act,$id,"","","","image_func");

  if($act=='edit') {
    $im = db_object_get("images",$id);
    $o .= "<img width=100px src=$im->link><br>$upload";
  }

  

  return $o;
}

function image_func($id) {
  $image = db_object_get("images",$id);
  return "<img height=40 src=$image->link>";
}

?>
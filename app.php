<?php

/*
  Simple Query Parsing PHP CMS
  (c) 2010 nkcomp.com, Dexosoft, Nazar Kuliyev
*/

/*
  application part
*/

function page_pi() {
   phpinfo();
   die();
}


function def_q() {
  return menu_first_link(1);
}


$template_call['menu_banner'] = true;
function menu_banner() {

   global $menu_id;
   if($menu_id) {
      $menu = db_object_get("menu",$menu_id);
      $image = image_by_name(mb_strtolower($menu->title,"UTF-8"));
      if($image) return "<img class=banner src=$image>";
   }

   return "";

}

?>
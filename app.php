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


?>
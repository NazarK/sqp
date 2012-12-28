<?php

require_once "app.php";
require_once "bios.php"; //all functions
require_once "conf.php";

error_reporting(E_ALL);

db_connect();

echo "query";
$res = db_query("SELECT * FROM menu WHERE parent_id=0");

echo "query done";
echo db_num_rows($res);

die("HERE");

error_reporting(E_ALL);

error_reporting(-1);

ini_set('error_reporting', E_ALL);

  echo("opening database");

  sqlite_open("./db/sqlite2.db");

  echo("database opened");



?>
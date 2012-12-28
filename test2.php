<?php

echo "pdo sqlite3";
$file_db = new PDO('sqlite:db/sqlite3.db');

echo "opened";


?>

<? echo "short tags"; ?>

<? echo "ssession start";

session_start();

echo "session started";

?>
<?php
if (!isset($dbname) || trim($dbname) == '') die("Database not specified");
$con = mysql_connect('localhost:/tmp/mysql.sock', 'root', '');
mysql_select_db($dbname, $con) or die(mysql_error());

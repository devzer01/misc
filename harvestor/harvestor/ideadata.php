<?php

$con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root');
mysql_select_db('ideastorm', $con);

$fp = fopen('idea.csv', 'w');

$sql = "SELECT * FROM idea";

$rs = mysql_query($sql, $con);

while ($r = mysql_fetch_assoc($rs)) {
	fputcsv($fp, $r);
}

fclose($fp);

<?php

$con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root');
mysql_select_db('ideastorm', $con);

$fp = fopen('idea_comment.csv', 'w');

$sql = "SELECT * FROM idea_comment";

$rs = mysql_query($sql, $con);
$header = 0;

while ($r = mysql_fetch_assoc($rs)) {
	if ($header == 0)  {
		fputcsv($fp, array_keys($r));
		$header = 1;
	}
	fputcsv($fp, $r);
}

fclose($fp);

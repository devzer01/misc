<?php

$con = mysql_connect('localhost:/var/run/mysqld/mysqld.sock', 'root');
mysql_select_db('ideastorm', $con);

$fp = fopen('idea_action_latest.csv', 'w');

$sql = "select idea_id, username, IF(vote_type=1, 'voteup', 'votedown') AS action "
     . "FROM idea_vote "
     . "UNION ALL "
     . "select idea_id, username, 'comment' AS action "
     . "FROM idea_comment "
     . "UNION ALL "
     . "SELECT idea_id, username, 'vote' AS action "
     . "FROM idea_user_vote ";


$rs = mysql_query($sql, $con) or die(mysql_error());
$header = 0;

while ($r = mysql_fetch_assoc($rs)) {
	if ($header == 0)  {
		fputcsv($fp, array_keys($r));
		$header = 1;
	}
	fputcsv($fp, $r);
}

fclose($fp);



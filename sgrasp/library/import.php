<?php
$con = mysql_connect('127.0.0.1', 'root', '') or die(mysql_error());
$db  = mysql_select_db('opstools');
$fp = fopen('data.csv.txt', 'r');
if (!$fp) {
    printf("File Not Open\n");
} else {
    printf("File Found\n");
}
$label = false;
$data  = false;
$eor   = false;
$row = array();
$type = NULL;
$str_desc = NULL;
$str_label = NULL;

while (($data = fgetcsv($fp)) !== FALSE) {
    
	if (preg_match("/^[A-Za-z]/", $data[0])) {
	    $str_label = $data[0];
	    $str_desc  = $data[1];
		//printf("Label %s, Desc %s\n", $data[0], $data[1]);
	} elseif (trim($data[0]) == '') {
	    printf("End of Data Record\n");
	    $sql = "INSERT INTO pjm_addon_datapoint (label, qtext, type, choices, status) "
	         . " VALUES ('" . mysql_real_escape_string($str_label) . "','" . mysql_real_escape_string($str_desc) . "','" . mysql_real_escape_string($type) . "','" . mysql_real_escape_string(serialize($row)) . "', 'A') ";
	    echo $sql;
	    mysql_query($sql, $con) or die(mysql_error());
	    printf("Record %s, Label %s, desc %s\n", $type, $str_label, $str_desc);
	    print_r($row);
	    $row = array();
	} else {
		//printf("%s\n", $data[2]);
	    //data point
	    if (preg_match("/DATETIME/", $data[2])) {
	        //date time
	        $type = 'datetime';
	    } elseif (preg_match("/Selected/", $data[3])) {
	    	list($dump, $id)   = split(":", $data[3]);
	        $desc = $data[5];
	        $row[] = array('choiceid' => $id, 'name' => $desc, 'custom' => $desc);
	    	$type = 'radio';
	        //printf("Label %s, Desc %s\n", $data[0], $data[1]);
	    } elseif (preg_match("/Checked/", $data[3])) {
	        $type = 'checkbox';
	        list($dump, $id)   = split(":", $data[3]);
	        $desc = $data[5];
	        $row[] = array('choiceid' => $id, 'name' => $desc, 'custom' => $desc);
	    }
	}
}
<?php

class schemaDB extends dbConnect {

function schemaDB() {
   $this->dbConnect();
}

/**
* GetTablesWithColumn()
*
* @param
* @todo NOT YET COMPLETED
* @return
* @since  - Fri Dec 08 10:56:12 PST 2006
*/
function GetTablesWithColumn($database, $field)
{
   $q = "SELECT table_name FROM info_schema.`columns` WHERE table_schema='$database' AND column_name='$field'";
   return $this->executeQuery($q);
}
}

?>

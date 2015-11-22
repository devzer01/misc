<?php

class archiveDB extends dbConnect {

	/**
	 * archiveDB()
	 *
	 * @param
	 * @return
	 * @access
	 * @since
	 */
	function archiveDB($options = array())
	{
		$this->dbConnect($options);
	}   

   /**
	* GetArchiveItemDefs()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - 2.0.1 - Mon Nov 13 12:44:52 PST 2006
	*/
	public function GetArchiveItemDefs($archive_item_def_type_id = 0)
	{
	   $q = "SELECT archive_item_def_id, archive_item_def_description, table_name, table_name_id_field_name, where_expression FROM archive_item_def WHERE status='A' AND archive_item_def_type_id='$archive_item_def_type_id'";
	   return $this->executeQuery($q);
	}
	
	/**
	* GetArchiveItemDefsByTable()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - 2.0.1 - Wed Nov 15 13:04:20 PST 2006
	*/
	public function GetArchiveItemDefsByTable($table_name, $archive_item_def_type_id=0)
	{
	   $q = "SELECT archive_item_def_id, archive_item_def_description, table_name, table_name_id_field_name, where_expression FROM archive_item_def WHERE status='A' AND table_name = '$table_name' AND archive_item_def_type_id='$archive_item_def_type_id'";
	   return $this->executeQuery($q);	   
	}
	
	/**
	* GetArchiveItemDef()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - Mon Nov 13 13:28:47 PST 2006
	*/
	function GetArchiveItemDef($archive_item_def_id)
	{
	   $q = "SELECT archive_item_def_id, archive_item_def_description, table_name, table_name_id_field_name, where_expression FROM archive_item_def WHERE status='A' AND archive_item_def_id='$archive_item_def_id'";
	   return mysql_fetch_assoc($this->executeQuery($q));
	}
	
	/**
	* CreateArchiveTable()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - 2.0.1 - Tue Nov 14 14:09:00 PST 2006
	*/
	public function CreateArchiveTable($table_name)
	{
	   $q = "CREATE TABLE IF NOT EXISTS ".MYSQL_DB."_archive.$table_name LIKE $table_name;";
	   $this->executeQuery($q);
	}
	
	/**
	* InsertArchiveEvent()
	*
	* @param
	* @todo NOT YET COMPLETED
	* @return
	* @since  - 2.0.1 - Fri Nov 17 14:38:22 PST 2006
	*/
	public function InsertArchiveEvent($archive_item_def_id, $query, $affected)
	{
	   $q = "INSERT INTO archive_event (`archive_item_def_id`, `remote_address`, `user_agent`, `query`, `affected`, `created_by`, `created_date`, `status`) ";
	   $q .= "VALUES ('$archive_item_def_id', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".mysql_real_escape_string($query)."', '$affected', '".$this->created_by."', NOW(), 'A')";
	   $this->executeQuery($q);
	   return $this->last_id;
	}

}

?>
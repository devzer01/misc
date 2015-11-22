<?php

class db_common extends db {
	
	/**
	* GetStudyProgrammingTypes()
	*
	* @param
	* @param 
	* @return
	* @since  - 10:46:26
	*/
	public function GetStudyProgrammingTypes()
	{
		$this->SetSelect();
		
		$this->select->from('study_programming_type', array('study_programming_type_id', 'study_programming_type_description'));
		
		$this->select->where('status = ?', 'A');
		
		return $this->db->fetchAssoc($this->select);
	}
	
	/**
	* GetCountryList()
	*
	* @param
	* @param 
	* @return
	* @since  - 16:04:26
	*/
	function GetCountryList()
	{
		$this->SetSelect();
		
		$this->select->from('country', array('country_code', 'country_description'));
		$this->select->order('sort_order');
		
		return $this->db->fetchAssoc($this->select);
	}
	
	/**
	* GetStudyDataSources()
	*
	* @param
	* @param 
	* @return
	* @since  - 14:51:09
	*/
	function GetStudyDataSources()
	{
		$this->SetSelect();
		
		$this->select->from('study_datasource', array('study_datasource_id', 'study_datasource_description'));
		
		$this->select->where('status = ?', 'A');
		
		return $this->db->fetchAssoc($this->select);
	}
	
}
?>
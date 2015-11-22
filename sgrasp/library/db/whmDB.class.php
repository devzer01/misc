<?php
/**
 * Whm Class
 * 
 * @copyright 2007 Global Market Insite Inc
 * @author sujith
 * @version 1.0
 * @package class 
 * @subpackage dbClass
 */  
class whmDB extends dbConnect
{ 
	
  /**
	* Constructor
	*
	*/ 
	function __construct() 
	{ 
		$config = Hb_Util_Config_SystemConfigReader::Read(); 
	   $options = array('host' => $config->whm->host, 'user' => $config->whm->username, 'pass' => $config->whm->password, 'db' => $config->whm->dbname);
				
	 	parent::__construct($options);
	}
	
	/**
	 * Get Panelists By Campaign Code
	 * 
	 *	@param String $code
	 */ 
	function GetPanelistsByCampaignCode($code) 
	{ 
		$query = "SELECT p.id, partner_id, code, description, household_id, first_name, last_name, email, country, gender, birthdate, language_id, c.country_code "
				.	"FROM partner_temp AS pt "
				.	"JOIN panelist as p on pt.id = p.partner_id "
				.	"JOIN country as c on p.country_id = c.country_id "
				.	"WHERE code in(" . $code . ")";
				
		return $this->executeQuery($query);
	}
	
	
	/**
	 * Get Number of Panalists on a specified month
	 * 
	 *	@param String $year
	 * @param String $month
	 */
	function GetNoOfPanalistsPerMonth($year_month) 
	{
	 	$query = 'SELECT COUNT(*) FROM panelist WHERE created_date LIKE "' . $year_month . '%"';
	 	return $this->executeQuery($query);
	}	
	
	/**
	 * Get Full Names of Tables Which Include the Given Phrase in the Name
	 *
	 * @param string $phrase	
	 * @return  
	 */
	function GetFullNamesOfTables($phrase)
	{
		$query = "SHOW TABLES FROM panel LIKE '%" . $phrase . "%'";
	 	$rs =  $this->executeQuery($query);
	 	
	 	$arr_tables = array();
	 	while ($row = $this->getDataArray($rs))
	 	{
	 		$index = 'Tables_in_panel (%' . $phrase . '%)' ;
	 		$arr_tables[] = $row[$index];

	 	}
	 	return $arr_tables ;
	}

	/**
	 * Checking whether table name exists 
	 *
	 * @param string $table_name
	 * @return  boolean
	 */
	function CheckTableExists($table_name) 
	{ 
		$query = "SELECT COUNT(*) as flag FROM information_schema.tables WHERE table_schema = 'panel' AND table_name = '" . $table_name . "'";
		$rec = $this->executeQuery($query); 
		$row = $this->FetchAssoc($rec);
		if ($row['flag'] > 0) {
			return true;
		}
		return false;
	}
	
	/**
	 * Summarize Monthly Activity of Panelist
	 *
	 * @param data type var name var description
	 * @return data_type desciption
	 */ 
	function GetPanelistsMonthlyActivitySummary($table_name, $panelist) 
	{ 
		if($this->CheckTableExists($table_name)) {
			$query = "SELECT SUM(invited) as invited, SUM(started) as started, SUM(screened) as screened, SUM(completed) as completed 
						FROM " . $table_name . " WHERE panelist_id in(" . $panelist . ")";
			
			$rec	= $this->executeQuery($query);
			return $this->FetchAssoc($rec);
		}
	}
	
	/**
	 * Get the Partner Ids By Campaign Codes
	 *
	 * @param array $campaign_codes
	 * @return result set Partner Ids
	 */ 
	function GetPartnerIdByCampaignCodes($campaign_codes)
	{
		$query = 'SELECT id FROM partner_temp WHERE code IN (';
		
		for ($i=0; $i<count($campaign_codes);$i++) {
			if ($i == count($campaign_codes)-1) {
				$campaign_str = $campaign_str . '"' . $campaign_codes[$i] . '"';
			} else {
				$campaign_str = $campaign_str . '"' . $campaign_codes[$i] . '",';
			}
		}
		
		$query = $query . $campaign_str . ')';
		
		return $this->executeQuery($query);		
	}
	
	/**
	 * Count Opt Ins
	 *
	 * @param string $str_panelists
	 * @param string $table
	 * @return string $opt_in_type
	 */ 
	function CountOptIns($str_panelists,$table,$opt_in_type)
	{
		
		if ( 'single' == $opt_in_type)
		{		
			$opt_in_field = "invited" ;
		}
		else
		{
			$opt_in_field = "completed" ;
		}
		
		$query = " SELECT SUM(" . $opt_in_field . ") FROM " . $table .
				   " WHERE panelist_id IN (" . $str_panelists . ")";
		$rs    = $this->executeQuery($query);
		$count = $this->GetField($rs, 0, 0) ;
		
		return $count ;	
	}	
	
	/**
	 * Get Gender Count By Panelist Id
	 *
	 * @param string $str_panelists
	 * @param int $gender
	 */ 
	function GetGenderCountByPanelistId($str_panelists, $gender)
	{
		$query = " SELECT COUNT(*) FROM panelist" .
				   " WHERE id IN (" . $str_panelists . ") AND gender = " . $gender;
				  
		$rs    = $this->executeQuery($query);
		$count = $this->GetField($rs, 0, 0) ;
		
		return $count; 
	}		
	
	/**
	 * Get Country Code By Country Id
	 *
	 * @param int $country_id
	 */ 
	function GetCountryCodeByCountryId($country_id)
	{
		$query = " SELECT country_code FROM country" .
				   " WHERE country_id = " . $country_id ;
				  
		$rs    = $this->executeQuery($query);
		$country_code = $this->GetField($rs, 0, 0) ;
		
		return $country_code; 
	}	
	
	/**
	 * Get Marital Status Count By Panelist Id
	 *
	 * @param string $str_panelists
	 * @param int $m_status
	 */ 
	function GetMaritalStatusCountByPanelistId($str_panelists, $m_status)
	{
		$query = " SELECT COUNT(*) FROM panelist" .
				   " WHERE id IN (" . $str_panelists . ") AND gender = " . $gender;
				  
		$rs    = $this->executeQuery($query);
		$count = $this->GetField($rs, 0, 0) ;
		
		return $count; 
	}	
	
	/**
	 * Get Answer Count By Panelist Id
	 *
	 * @param string $str_panelists
	 * @param string $table
	 * @param string $question
	 */ 
	function GetAnswerCountByQuestion($str_panelists, $table, $question)
	{
		$query = " SELECT $question, COUNT(panelist_id) as c FROM " . $table .
				   " WHERE panelist_id IN (" . $str_panelists . ") GROUP BY " . $question ;
				 
		$rs    = $this->executeQuery($query);
		
		return $rs; 
	}	
	
	/**
	 * Get Panelist Count By Date
	 *
	 * @param string $str_panelists
	 * @param string $date
	 */ 
	function GetPanelistCountByDate($str_panelists, $date, $status = "")
	{
		list($year, $month) = explode("-", $date);
		
		$whStatus = "";
		if($status != ""){
			 $whStatus = " AND status = '" . $status . "'";
		}
		$query = " SELECT COUNT(id) as c FROM panelist".
				   " WHERE id IN (" . $str_panelists . ") ". 
				   " AND MONTH(created_date) = '" . $month ."' AND YEAR(created_date) = '" . $year . "'" . $whStatus;
				
		$rs    = $this->executeQuery($query);
		$count = $this->GetField($rs, 0, 0) ;
		
		return $count; 
	}	
}
?>
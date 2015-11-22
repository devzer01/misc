<?php 
/**
 * Mapper class of ProposalSetting
 * 
 * @copyright 2007 Global Market Insite Inc
 * @author Sujith T
 * @version 1.0
 * @package Mapper 
 * @subpackage Proposal
 */  
class Hb_Mapper_Proposal_ProposalSetting extends Hb_Mapper_Mapper  
{  
  /**
   * Domain object class name
   *
   * @var string 
   */ 
   protected static $__domain = 'Hb_App_Proposal_ProposalSetting'; 

  /**
   * primary key of the table
   *
   * @var string 
   */
   protected $primary_key = 'proposal_setting_id';
   
  /**
   * table name
   *
   * @var string 
   */  
   protected $table = 'proposal_setting'; 
 
   /**
    * Find by id
    *
    * @param int id Account Merge Id
    * @return Hb_App_Proposal_ProposalSetting Returns domain object
    */ 
   protected function doFindById($id) 
   {
   	$select = $this->GetBaseSelect();	
	  	$select->where('ps.proposal_setting_name = ?', $id);
	  
	  return $this->getRow($select);
   }
   
   /**
    * Find by Proposal Setting name wrapper function
    *
    * @param string $name
    * @return retuslt object
    */
   protected function doFindBySettingName($name)
   {
   	$select = $this->GetBaseSelect();	
	  	$select->where('ps.proposal_setting_name = ?', $name);
	  
	   return $this->getRow($select);
   }
   
   /**
    * Find by Proposal Setting name
    *
    * @param string $name
    * @return Hb_App_Proposal_ProposalSetting
    */
   public function FindBySettingName($name)
   {
   	$result = $this->doFindBySettingName($name);
   	return $this->BuildObject($result);
   }
   
   /**
    * Prepare basic select statement
    * 
    * @return select returns select member of db connection object
    */ 
   protected function GetBaseSelect() 
   { 
      $db = Hb_Util_Db_Connection::GetInstance();
	  
	  	$select = $db->select();
	  	$select->from(array('ps' => $this->table), array($this->primary_key, 'proposal_setting_name', 'proposal_setting_description', 'proposal_setting_value'));
	  
	  	return $select;
   }
   
   /**
    * Builds the domain object from the provided row
    *
    * @param Object $row Table row
    * @return Hb_App_Proposal_ProposalSetting Returns domain object
    */ 
   function doBuildObject($row)
   { 
   	  return new Hb_App_Proposal_ProposalSetting($row->proposal_setting_id,
   	  		$row->proposal_setting_name,
   	  		$row->proposal_setting_value,
   	  		$row->proposal_setting_description
   	  		);
   }
   
   /**
    * Save
    *
    * @param $obj object Instance of the Hb_App_Proposal_ProposalSetting
    */ 
   function doSave(Hb_App_Object $obj)
   {
   	$data = array(
      	'proposal_setting_name'				=> $obj->GetSettingName(),
      	'proposal_setting_description'	=> $obj->GetSettingDescription(),
      	'proposal_setting_value'			=> $obj->GetSettingValue());
      	
  
      $db = Hb_Util_Db_Connection::GetInstance(); 
      
      if (is_null($obj->GetId())) {
			$db->insert($this->table, $data);
			$obj->SetSettingId($db->lastInsertId());
	  	} else {
			$db->update($this->table, $data, $this->primary_key . ' = ' . $obj->GetId());
	  	}
   }
}
?>
<?php
/**
 * @author nayana
 * @version 1.0
 * @updated 18-Jul-2007 7:19:50 PM
 * @todo Most of the proteted functionality here can be refactored into the abstract mapper layer.
 */
class Hb_Mapper_Account_Account extends Hb_Mapper_Mapper
{
	
	protected $primary_key = 'account_id';
	
	protected $table       = 'account';
	
	/**
 	 * Builds and returns Account domain object
	 *
	 * @param $row $object results row from the Zend_Db framework for account table
	 * @return Hb_App_Account_Account
	 */
	protected function doBuildObject($row)
	{
		return Hb_App_Account_Generator_Factory::GetInstance($row);
	}
	
	/**
	 * Builds and returns a Zend_Db Select object
	 * 
	 * @return Zend_Db_Select
	 */
	protected function GetBaseSelect()
	{
		$db = Hb_Util_Db_Connection::GetInstance();
		$select = $db->select();
		
		$select->from(array('a' => 'account'), array('account_id', 'account_name', 'country_code', 'account_status_id', 'status'));
		$select->joinLeft(array('at' => 'account_account_type'), 'at.account_id = a.account_id', array('account_type_id'));
		$select->joinLeft(array('ast' => 'account_account_sub_type'), 'ast.account_id = a.account_id', array('account_sub_type_id'));
		
		return $select;
	}
	
	/**
	 * Returns a tuple by given Account Id
	 *
	 * @return object
	 */
	protected function doFindById($id)
	{
		$select = $this->GetBaseSelect();		
		$select->where('a.account_id = ?', $id);
			
		return $this->getRow($select);
	}
	
	
	/**
	 * 
	 * @todo refactor further
	 */
	public function FindByName($account_name)
	{
		$result = $this->doFindByName($account_name);
		return $this->BuildCollection($result);
	}
	
	/**
	 * Returns a tuple by given Account Name
	 *
	 * @return object
	 */
	protected function doFindByName($account_name)
	{
		$select = $this->GetBaseSelect();
		$select->where('a.account_name LIKE ?', $account_name);
		return $this->getRows($select);
	}

	/**
	 * Saves a dirty domain object to the persistence layer
	 *
	 * @param $obj object Instance of the relevent domain object
	 */				
	protected function doSave(Hb_App_Object $obj)
	{

		$data = array(
			'account_name' => $obj->GetAccountName(),
			'country_code' => $obj->GetCountryCode(),
			'status'			=> $obj->GetStatus(),
			'account_status_id' => $obj->GetAccountStatus());

		$db = Hb_Util_Db_Connection::GetInstance();
					
		if (is_null($obj->GetId())) 
		{
			$db->insert('account', $data);
			$obj->SetAccountId($db->lastInsertId());
		}
		else 
		{
			$db->update('account', $data, 'account_id = ' . $obj->GetId());	
		}
	}
}
?>
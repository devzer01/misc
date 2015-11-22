<?php
/**
 * This class helps to doing database task of account hierarchy manager
 * @author Salika Wijewardana
 * @copyright Global Market Insite 2007
 * @package db_acm
 */
class db_acm_HierarchyManager extends db 
{	
			
   protected function __InsertAccountHierarchy($param )
   {
   	$table = 'account_hierarchy';
		$this->__Insert($table, $param);
   }
   
   protected function __InsertAccountHierarchyType($param)
   {
   	$table = 'account_hierarchy_type';
		$this->__Insert($table, $param);
   }
   
   protected function __UpdateAccountHierarchy($param = array(), $fields = array())
   {
 		$table = 'account_hierarchy';

 		if (isset($param['parent_account_id']))
 		{
			$where = $this->db->quoteInto('parent_account_id = ?', $param['parent_account_id'] );
 		}
 		
		if (isset($param['child_account_id']))
 		{
			$where = $this->db->quoteInto('child_account_id = ?', $param['child_account_id'] );
 		}
		
 		if (isset($param['status']))
 		{
 			$where = $this->db->quoteInto('status = ?',$param['status'] );
 		}
 		
		$this->__Update($table, $fields, $where);		
		
   }
   
   protected function __UpdateAccountHierarchyType($param = array(), $fields = array())
   {
 		$table = 'account_hierarchy_type';
		
 		if (isset($param['account_hierarchy_type_id']))
 		{
			$where = $this->db->quoteInto('account_hierarchy_type_id = ?', $param['account_hierarchy_type_id'] );
 		}
		
		if (isset($param['status']))
 		{
 			$where = $this->db->quoteInto('status = ?', $param['status'] );
 		}
		
		$this->__Update($table, $fields, $where);		
		
   }
   
   protected function __SelectAccountHierarchy($param = array(), $attr = array())
   {
   	$this->SetSelect();
						
		$this->select->from(array('ah' => 'account_hierarchy'), array('account_hierarchy_id' , 'child_account_id', 'parent_account_id', 'created_date'));
	 	$this->select->join(array('aht' => 'account_hierarchy_type'),'ah.account_hierarchy_type_id = aht.account_hierarchy_type_id', array('account_hierarchy_type_description',
	 	'account_hierarchy_type_id'));
	 	$this->select->join(array('ap' => 'account'), 'ap.account_id = ah.parent_account_id', array('parent_account_name' => 'account_name'));
	 	$this->select->join(array('ac' => 'account'), 'ac.account_id = ah.child_account_id',array('child_account_name' => 'account_name'));
	 	$this->select->join(array('u' => 'user'), 'ah.created_by = u.login', array('assigned_by' => 'last_name'));
   		$this->select->join(array('c' => 'country'), 'c.country_code = ac.country_code', array('country_description'));
	 	
   	if (isset($param['status']) && $param['status'] != '') 
   	{
			$this->select->where('ah.status = ?', $param['status']);
		}
		
		if (isset($param['parent_account_id'])) 
		{
			$this->select->where('ah.parent_account_id = ?', $param['parent_account_id']);
		}
		
		if (isset($param['child_account_id'])) 
		{
			$this->select->where('ah.child_account_id = ?', $param['child_account_id']);
		}		
		
		if (isset($attr['fetch_row']) && $attr['fetch_row'] == 1) 
		{
			return $this->db->fetchRow($this->select);	
		}
		
		return $this->db->fetchAssoc($this->select);
   }
   
   protected function __SelectAccountHierarcyType($param = array(), $attr = array())
   {
   	$this->SetSelect();
   	
   	$this->select->from('account_hierarchy_type');
   	
   	if (isset($param['status']) && $param['status'] != '') 
   	{
			$this->select->where('status = ?', $param['status']);
		}
		
		if (isset($attr['fetch_row']) && $attr['fetch_row'] == 1) 
		{
			return $this->db->fetchRow($this->select);	
		}
		
		return $this->db->fetchAssoc($this->select);
   }
   
      
   /**
    * Add data into account_hierarchy_type table
    *
    * @param int $account_hierarchy_type_id
    * @param int $parent_account_id
    * @param int $child_account_id
    * @return int    
    */
   function AddAccountHierarchy($account_hierarchy_type_id, $parent_account_id, $child_account_id)
	{
		$param['account_hierarchy_type_id']	= $account_hierarchy_type_id;
		$param['parent_account_id']			= $parent_account_id;	
		$param['child_account_id']				= $child_account_id;
		
		$this->__InsertAccountHierarchy($param);
		
		return $this->last_insert_id; 	
	}
   
   /**
    * Add data into account_hierarchy_type table
    *
    * @param varchar(50) $account_hierarchy_type_description
    * @return int
    */
   function AddAccountHierarchyType($account_hierarchy_type_description)
	{
		$param['account_hierarchy_type_description'] = $account_hierarchy_type_description;		
		
		$this->__InsertAccountHierarchyType($param);
		
		return $this->last_insert_id; 	
	}

	/**
	 * Modify data of account_hierarchy table
	 *
	 * @param int $account_hierarchy_id
	 * @param int $account_hierarchy_type_id
	 * @param int $parent_account_id
	 * @param int $child_account_id
	 */
   function UpdateAccountHierarchy($account_hierarchy_id, $account_hierarchy_type_id, $parent_account_id, $child_account_id)
   {
   	$param['account_hierarchy_id']			= $account_hierarchy_id;
   	
   	$fields['account_hierarchy_type_id']	= $account_hierarchy_type_id;
		$fields['parent_account_id']				= $parent_account_id;	
		$fields['child_account_id']				= $child_account_id;
		
		$this->__UpdateAccountHierarchy($param,$fields);
		return $this->affected_rows;
		
   }

   /**
    * Modify data of account_hierarchy_type table
    *
    * @param int $account_hierarchy_type_id
    * @param int $account_hierarchy_type_description
    */
   function UpdateAccountHierarchyType($account_hierarchy_type_id, $account_hierarchy_type_description)
   {
   	$param['account_hierarchy_type_id']           =	$account_hierarchy_type_id;
   	
   	$fields['account_hierarchy_type_description'] =	$account_hierarchy_type_description;
				
		$this->__UpdateAccountHierarchyType($param, $fields);
		return $this->affected_rows;
   }    
   
   /**
    * Delete data of account_hierarchy table acccording to account_hierarchy_id
    *
    * @param int $account_hierarchy_id
    */
   function DeleteAccountHierarchy($parent_account_id, $child_account_id)
   {
   	$param = array(
   		'parent_account_id' 	=> $parent_account_id,
   		'child_account_id'	=> $child_account_id
   	);
   	
   	$fields['status']					 = 'D';
				
		$this->__UpdateAccountHierarchy($param, $fields);
		return $this->affected_rows;
   }
   
   /**
    * Delete data of account_hierarchy table acccording to account_hierarchy_type_id
    *
    * @param int $account_hierarchy_type_id
    */
    function DeleteAccountHierarchyType($account_hierarchy_type_id)
   {
   	$param['account_hierarchy_type_id'] = $account_hierarchy_type_id;
   	
   	$fields['status']                   = 'D';
				
		$this->__UpdateAccountHierarchyType($param, $fields);
		return $this->affected_rows;
   }
   
   /**
    * Return childs details under parent account
    *
    * @param int $parent_account_id
    * @return array
    */
   function GetAccountChildren($parent_account_id)
   {
   	$param['parent_account_id'] =	$parent_account_id;
   	$param['status']				 =	'A';
   	
   	$attr['fetch_row']          = 0;
   	
   	return $this->__SelectAccountHierarchy($param, $attr);
   }
    
   /**
    * Get details of one child
    *
    * @param int $parent_account_id
    * @param int $child_account_id
    * @return array
    */
   function GetChild($parent_account_id, $child_account_id)
   {
   	$param['parent_account_id'] = $parent_account_id;
   	$param['child_account_id']  =	$child_account_id;
   	$param['status']            =	'A';
   	
   	$attr['fetch_row']          = 1;
   	
   	return $this->__SelectAccountHierarchy($param,$attr);   	
   }
      
   function GetAccountHierarchyTypes()
   {
   	$param['status']            =	'A';
   	$attr['fetch_row']          = 0;
   	
   	return $this->__SelectAccountHierarcyType($param, $attr);
   }
   
   function IsChildAccountSet($account_id) 
   {
   	$params['child_account_id']	= $account_id;
   	$params['status']					= 'A';
   	
   	$attr['fetch_row']				= 1;   	
   	
   	$child_account 			= $this->__SelectAccountHierarchy($params, $attr);
   	
   	return ($child_account ? true : false);
   	
   }   

}

?>
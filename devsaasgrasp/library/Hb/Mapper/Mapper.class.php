<?php
include_once 'Hb/Mapper/MapperHelper.class';
include_once 'Hb/Mapper/Exception/ObjectNotFoundException.class';
include_once 'Hb/App/Collection.class';
include_once 'Hb/App/Object.class';
include_once 'Hb/Util/Lang/Class.class';
include_once 'Hb/App/ObjectMap.class.php';
include_once 'Hb/Util/Db/Connection.class.php';
include_once 'Hb/Util/Log/Logger.class.php';

/**
 * 
 * Domain object mapper, that implements core mapper functionality
 * 
 * @author nayana
 * @version 1.1
 * @updated 18-Jul-2007 7:21:01 PM
 */
abstract class Hb_Mapper_Mapper
{
	/**
	 * 
	 * @var string member will be set to the domain object class by the helper, used in the caching mechanism
	 */
	protected $domain;
	
	/**
	 * 
	 * @var string must be set at the individual mapper level to the primary key of the table
	 */
	protected $primary_key;
	
	/**
	 * 
	 * @var string must be set at the individual mapper level to the primary table of the mapper
	 */
	protected $table;

	/**
	 * 
	 * Loaded by Object Helper 
	 * 
	 * @throws Exception
	 */
	public final function __construct($class)
	{
		$this->domain = $class;
		
		if (trim($this->primary_key) == '') {
			throw new Exception("Primary Key is not set for domain mapper " . $class);
		}
		
		if (trim($this->table) == '') {
			throw new Exception("Primary table not set for domain mapper " . $class);
		}
		
	}

	/**
	 * 
	 */
	public function __destruct()
	{
	}
	
	/**
	 * 
	 */
	protected function GetRowGateway()
	{
		try {
			$gateway = Hb_Mapper_MapperHelper::GetRowGatway($this->domain);
		} catch (Exception $e) {
			$gateway = Hb_Mapper_MapperHelper::GetRowGatewayByTable($this->table);
		}
		
		return $gateway;
	}

	/**
	 * Common Find method to retrive the Account Domain object given the id of the object
	 * 
	 * @param int $id object identification
	 * @return Hb_App_Object
	 */
	public function Find($id)
	{
		try {
			
			return $this->doFindInMap($id);
			
		} catch(Hb_Mapper_Exception_ObjectNotFoundException $e) {
			
			//should we check to see if the returning domain is a collection ?
			return $this->FindById($id);
			
		} catch (Exception $e) {
			$logger = Hb_Util_Log_Logger::GetInstance('mapper');
			$logger->LogException($e);		
		}
	}
	
	/**
	 * Internal method which looks up the object in the identity map
	 * 
	 * @param int $id object unique idenification
	 * @return Hb_App_Object
	 * @throws Hb_Mapper_Exception_ObjectNotFoundException
	 */
	protected function doFindInMap($id)
	{		
		if (Hb_App_ObjectMap::hasObject($this->domain, $id)) {
			return Hb_App_ObjectMap::getObject($this->domain, $id);	
		}
		
		throw new Hb_Mapper_Exception_ObjectNotFoundException();
	}
	
	/**
	 * 
	 * @return Hb_App_Account_Account
	 */
	protected function BuildObject($row)
	{
		$obj = $this->doBuildObject($row);
			
		Hb_App_ObjectMap::register($obj);
		
		return $obj; 
	}
	
	/**
	 * 
	 * @todo Move to Abstract mapper
	 */
	protected function getRow($select)
	{
		$db = Hb_Util_Db_Connection::GetInstance();
		return $db->fetchRow($select);
	}
	
	/**
	 * 
	 * @todo Move to Abstract mapper
	 */
	protected function getRows($select)
	{
		$db = Hb_Util_Db_Connection::GetInstance();
		return $db->fetchAll($select);
	}
	
	/**
	 * 
	 */
	protected function BuildCollection($rows, $collection_class = 'Hb_App_Collection')
	{
//		if (count($rows) == 0) {
//			throw new Exception();
//		}
		
		//FIXME we should validate if the class just passed in was a proper class or not
		$collection = new $collection_class;
		
		foreach ($rows as $row)
		{
			try {
				$collection->AddItem($this->doFindInMap($row->{$this->primary_key}));
			} catch (Hb_Mapper_Exception_ObjectNotFoundException $e) {
				$collection->AddItem($this->BuildObject($row));
			}
		}
		
		return $collection;
	}
	
	/**
	 * 
	 */
	public function Save(Hb_App_Object $obj)
	{
		$this->doSave($obj);
		
		$obj->markSaved();
		
		Hb_App_ObjectMap::register($obj);
	}
	
	/**
	 * 
	 * @param obj
	 */
	protected function Insert()
	{
		return $this->GetRowGateway()->createRow();
	}
	
	/**
	 * 
	 * @param obj
	 */
	protected function Update(Hb_App_Object $obj)
	{
		return $this->GetRowGateway()->fetchRow($this->primary_key . " = " . $obj->GetId());
	}
	
	protected function FindById($id)
	{
		$result = $this->doFindById($id);
		
		if (is_array($result) || Hb_Util_Lang_Class::isInstanceOf($this->domain, 'Hb_App_Collection')) {
			return $this->BuildCollection($result, $this->domain);
		}
		
		return $this->BuildObject($result);
	}
	
	abstract protected function doBuildObject($row);
	
	abstract protected function GetBaseSelect();
	
	abstract protected function doFindById($id);
	
	abstract protected function doSave(Hb_App_Object $obj);
	
	public function GetPrimaryKey()
	{
		return $this->primary_key;
	}
	
	public function GetTableName()
	{
		return $this->table;
	}
	
}
?>
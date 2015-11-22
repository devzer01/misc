<?php
require_once 'Zend/Cache.php';

class Hb_App_ObjectMap
{
	protected $objects = array();
	
	protected static $instance = null;
	
	protected $cache = null;
	
	/**
	 * Caching ability is added into the constructor
	 */
	private function __construct()
	{
		$cache_config = Hb_Util_Config_SystemConfigReader::Read('objects');
		
		if ($cache_config->cache->enabled == false) {
			return; 
		}
		
		$frontendOptions = array(
   			'lifetime' => $cache_config->cache->life, // cache lifetime of 2 hours
   			'automatic_serialization' => true
		);

		//TODO: this doesnt really belong here but this is a intrim fix
		switch ($cache_config->cache->type) {
			case 'File':
				$backendOptions = array(
    				'cache_dir' => $cache_config->cache->dir // Directory where to put the cache files
				);
			break;
			
			case 'Memcached':
				$backendOptions = array(
    				'servers' => array(array('host' => $cache_config->memcache->host, 'port' => $cache_config->memcache->port, 'persistent' => $cache_config->memcache->persistent)),
    				'compression' =>   $cache_config->memcache->compression
				);
			break;
		}

		//getting a Zend_Cache_Core object
		$this->cache = Zend_Cache::factory('Core', $cache_config->cache->type, $frontendOptions, $backendOptions);
	}
	
	/**
	 * Provide access to the singleton instance of ObjectMap
	 *
	 * @return Hb_App_ObjectMap
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Hb_App_ObjectMap();
		}
		
		return self::$instance;
	}
	
	/**
	 * Adding an Object into the cache
	 */
	protected function setCache($key, $obj)
	{
		if (!is_null($this->cache)) {
			$this->cache->save($obj, $key);
		}
	}
	
	/**
	 * Getting an Object From Cache
	 * 
	 * @param String $key lookup key for the object 
	 */
	protected function getCache($key)
	{
		if (is_null($this->cache)) {
			return false;
		}
		
		return $this->cache->load($key);	
	}
	
	/**
	 * 
	 * Register A object with the object map
	 * 
	 * @param Hb_App_Object $obj
	 */
	public static function register(Hb_App_Object $obj)
	{
		$key = self::getKey(get_class($obj), $obj->GetId());
		self::getInstance()->objects[$key] = $obj;
		self::getInstance()->setCache($key, $obj);
	}
	
	/**
	 * Returns an object from the object map 
	 */
	public static function getObject($class, $id)
	{
		$key = self::getKey($class, $id);
		return self::getInstance()->objects[$key];
	}
	
	/**
	 * Creates a unique MD5 identifier for the object lookup key
	 * 
	 * @param String $class name of the class
	 * @param Long $id Idenification of the object
	 */
	protected static function getKey($class, $id)
	{
		return md5($class . $id);	
	}
	
	/**
	 * Check to see if a object is present in the map
	 * 
	 * @param String $class Class Name
	 * @param Long $id idenification of the object instance
	 * @return boolean
	 */
	public static function hasObject($class, $id)
	{
		$key = self::getKey($class, $id);
		
		if ((isset(self::getInstance()->objects[$key]))) {
			return true;
		}
		
		$cache = self::getInstance()->getCache($key);
		
		if ($cache) {
			self::getInstance()->register($cache);
			return true;
		}
		
		return false;
		
	}
	
	
}
?>
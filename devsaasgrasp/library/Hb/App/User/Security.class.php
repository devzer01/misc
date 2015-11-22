<?php 
class Hb_App_User_Security 
{
	/**
	 * login Object
	 *
	 * @var Hb_App_User
	 */
	private $login;
	
	/**
	 * db Object
	 *
	 * @var db_security
	 */
	private $db ;
	
	/**
	* __construct()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function __construct($login)
	{
		$this->login = $login;
		$this->db 	 = new Hb_Db_Security();
	}

	/**
	 * Clears the flag that signals the Security Rights to be reloaded
	 */
   function ClearLoadRightsFlag()
   {
   	$this->db->UpdateUserAttr($this->login, "RELOAD_SECURITY", 0);
   }
   
   function CheckLoadRightsFlag()
   {
   	$user_attr = $this->db->GetUserAttr($this->login, "RELOAD_SECURITY");
   	return $user_attr[$this->login]["user_value"];
   }
	
	/**
	* LoadRights()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function LoadRights()
	{
		$inverted_ids 				= array();
		$security_type				= array();
		
		$exist_rights 				= $this->GetUserRights();		
		$individual_rights 			= $this->GetIndividualRights();
		$security_types 			= $this->GetSecurityType();
		
		//get all security types 	
		foreach ($security_types as $key => $value) {
			$security_type[] = $value['security_type'];
		}
		
		//get inverted id in the individial security array in to a new array	
		foreach ($individual_rights as $key => $value) {
			if(($value['invert'] == 1) && ($value['status'] = 'A')) {
				$inverted_ids[$value['security_id']] = $value['security_id'];			
			}
		}	

		//get security types in the existing session array
		foreach ($_SESSION as $key => $value) {			
			if(($value == 1) && (in_array($key, $security_type))) {
				if(!$this->isSecuritySet($key)) {					
					unset($_SESSION[$key]);
				}				
			}			
		}				
		
		//set exist security ids to a new array neglecting inverted security ids
		foreach ($exist_rights as $key1 => $value) {				
			if(!array_key_exists($value['security_id'], $inverted_ids)) {				
				$_SESSION[$value['security_type']] = true;							
 			}else {
 				unset($_SESSION[$value['security_type']] );
 			}
		}
      $this->ClearLoadRightsFlag();
	}
	
	/**
	* GetSecurityType()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Tue Jan 09 08:35:33 IST 2007
	*/
	function GetSecurityType()
	{
		return $this->db->SecurityTypes();
	}
	
	/**
	* isSecuritySet()
	*
	* @param -
	* @param - 	
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function isSecuritySet($security_type)
	{
		$in_group 		= false;
		$in_individual = false;
	
		//get the group level security
		$in_group  = $this->db->IsSecurityTypeInGroup($this->login, $security_type);		
		
		//get the individual level security
		$in_individual = $this->db->IsSecurityTypeInIndividual($this->login, $security_type, '0');
	 
		$in_invert_individual = $this->db->IsSecurityTypeInIndividual($this->login, $security_type, '1');
		
		if(!$in_group && !$in_individual) {
			return false; //not in grouplevel and not in individual level
		}
				
		if($in_group && $in_invert_individual['invert'] == 1) {			
			return  false; //in group level but has inverted individually
		}
		
		return true; //security is set for the security type
	}
	
	/**
	* SetSecurity()
	*
	* @param - 
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function SetSecurity($security_id, $invert, $status)
	{	
		if($status == 'set') {
			$this->db->SetIndividualSecurity($security_id, $invert, $this->login);
		}elseif ($status == 'unset') {
			$this->db->UnSetIndividualSecurity($security_id, $invert, $this->login);
		}	
	}
	
	/**
	* isAM()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function isAM()
	{
		if($this->isSecuritySet('IS_AM')) {
			return true;
		}
			return false;
	}
	
	/**
	* isAE()
	*
	* @param - 
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function isAE()
	{
		if($this->isSecuritySet('IS_AE')) {
			return true;
		}
			return false;
	}
	
	/**
	* isACCT()
	*
	* @param - 
	* @param -
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function isACCT()
	{	
		if($this->isSecuritySet('IS_ACCT')) {
			return  true;
		}
			return false;
	}
	
	/**
	* isADMIN()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function isADMIN()
	{ 
		if($this->isSecuritySet('CADMIN')) {
			return true;
		}
			return false;
	}
	
	/**
	* isTraining()
	*
	* @param - 
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function isTraining()
	{
		if($this->isSecuritySet('IS_TRAINING')) {
			return true;
		}
			return false;
	}	
	
	/**
	* GetUserRights()
	*
	* @param - 
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function GetUserRights()
	{
		return $this->db->GetSecurity($this->login);		
	}
	
	/**
	* GetUserDetails()
	*
	* @param - 
	* @param - 
	* @var db_security
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function GetUserDetails()
	{		
		return $this->db->UserDetail($this->login);
	}
	
	/**
	* SetGroup()
	*
	* @param - 
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function SetGroup()
	{
		//have to implement this function... 
	}	
	
	/**
	* userSecCheck()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 09:13:23 IST 2006
	*/
	function userSecCheck($security_type)
	{
		if($this->isSecuritySet($security_type)) {
			return true;
		}
			return false;
	}
	
	/**
	* GetIndividualRights()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 12:25:03 IST 2006
	*/
	function GetIndividualRights() 
	{		
		return $this->db->GetInididualSecurity($this->login);	
	}


	/**
	* GetDisableIndividualRights()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Mon Dec 18 15:19:55 IST 2006
	*/
//	function GetDisableIndividualRights()
//	{		
//		return $this->db->GetDisableIndividualSecurity($this->login);
//	}	
	
	/**
	* GetGroupRights()
	*
	* @param -
	* @param - 
	* @author - harsha
	* @since  - Wed Dec 13 12:25:20 IST 2006
	*/
	function GetGroupRights()
	{		
		return $this->db->GetGroupSecurity($this->login);			
	}	
}
?>
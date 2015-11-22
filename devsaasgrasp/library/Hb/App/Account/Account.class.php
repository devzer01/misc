<?php
include_once 'Hb/App/Account/AccountProduct.class';
include_once 'Hb/App/Account/AccountContact.class';
include_once 'Hb/App/Common/Country.class';
include_once 'Hb/App/ObjectHelper.class.php';
include_once 'Hb/App/Account/AccountAttributes.class';
include_once 'Hb/App/Object.class';
include_once 'Hb/App/Account/AccountProducts.class';
include_once 'Hb/App/Common/Contact.class';
include_once 'Hb/App/Common/User.class';
include_once 'Hb/App/Account/AccountContacts.class';
include_once 'Hb/App/Account/AccountUsers.class';
include_once 'Hb/App/Account/AccountUser.class';

/**
 * Domain Object for Account
 * 
 * @copyright 2007 Global Market Insite Inc
 * @author Nayana
 * @version 1.0
 * @package App
 * @subpackage Account
 *
 */

class Hb_App_Account_Account extends Hb_App_Object 
{
	protected  $account_id = 0;
	
	protected  $account_name = '';
	
	protected  $country_code = '';
	
	protected  $account_type_id = '';
	
	protected  $account_sub_type_id = '';
	
	/**
	 * Account Contact Collection
	 *
	 * @var Hb_App_Account_AccountContacts
	 * @access protected
	 */
	protected $__contacts = null;
	
	/**
	 * Account Comment Collection
	 *
	 * @var Hb_App_Account_AccountComments
	 * @access protected
	 */
	protected $__comments = null;
	
	/**
	 * Account User Collection
	 *
	 * @var Hb_App_Account_AccountUsers
	 * @access protected
	 */
	protected $__users    = null;
	
	/**
	 * Account Files collection
	 * 
	 * @var Hb_App_Account_AccountFiles
	 * @access protected
	 */
	protected $__files = null;
	
	/**
	 * Account Attribute Collection
	 *
	 * @var Hb_App_Account_AccountAttributes
	 * @access protected
	 */
	protected $__attributes = null;
	
	/**
	 * Geographic Information Object
	 *
	 * @var Hb_App_Common_Country
	 * @access protected
	 */
	protected $__country = null;
	
	/**
	 * Products Collection
	 *
	 * @var Hb_App_Account_AccountProducts
	 */
	protected $__products = null;
	
	/**
	 * Account Type Collection
	 *
	 * @var Hb_App_Account_AccountType
	 * @access protected
	 */
	protected $account_type = null;
	
	protected $status = null;
	
	/**
	 * Account Term
	 *
	 * @var Hb_App_Account_AccountTerm 
	 */
	protected $account_term = null;
	
	protected $account_status_id = null;
	
	/**
	 * Billing contacts
	 *
	 * @var Hb_App_Account_AccountCotnacts 
	 */
	protected $billing_contacts = null;
	
	/**
	 * Account Sub type object
	 * 
	 */
	protected $account_sub_type = null;
	
	/**
	 * Account Vendor object
	 * 
	 */
	protected $account_vendor = null;
		
	/**
	 * Create an Account Object
	 *
	 * @param int $id Account Number
	 * @param string $name Name of the account
	 * @param string $country ISO 3 code of the country account belongs to
	 * @param int $type list(1,2,3)
	 * @param int $sub_type list(1,2,3)
	 */
	public function __construct($id = null, $name, $country, $type, $sub_type, $account_status_id = null)
	{
		$this->account_id          = $id;
		$this->account_name        = $name;
		$this->country_code        = $country;
		$this->account_type_id     = $type;
		$this->account_sub_type_id = $sub_type;
		$this->account_status_id 	= $account_status_id;
		
		parent::__construct($id);
	}


	/**
	 * Set the Account Status for the  Account
	 *	
	 * @param int $account_status Account Status
	 */
	public function SetAccountStatus($status)
	{
		$this->account_status_id = $status;
		$this->MarkDirty();
	}
	
	/**
	 * Return the Account Status for the Account
	 *
	 * @return int Account Status
	 */
	public function GetAccountStatus()
	{
		return $this->account_status_id;
	}
	/**
	 * Set the Costomer for the Account
	 * 
	 * @param string $customer Customer
	 */
	public function SetCustomer()
	{
		$account_account_type = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountAccountType')->FindByAccountId($this->account_id);
		$account_account_type->setAccountTypeId(ACCOUNT_TYPE_CUSTOMER);
		$this->account_type_id = ACCOUNT_TYPE_CUSTOMER;
		$this->MarkDirty();		
	}
	
	/**
	 * Set the Account status for the  Account
	 * 
	 * @param string $status Account status
	 */
	public function SetStatus($status)
	{
		$this->status = $status;
		$this->MarkDirty();
	}
	
	/**
	* Return the Account Term for the Account
	*
	*/
	public function GetAccountTerm()
	{
		if(is_null($this->account_term)) {
			$this->account_term = new Hb_App_Account_AccountTerm();
			$this->account_term->SetAccount($this);
		}
		
		return $this->account_term;
	}
	
	/**
	 * Return the Account status for the Account
	 *
	 * @return string Account status
	 */
	public function GetStatus()
	{
		return $this->status;
	}
	
	/**
	 * Provides the Account ID 
	 *
	 * @return int
	 */
	public function GetAccountId()
	{
		return (int) $this->account_id;
	}
	
	/**
	 * Sets the account id, this method should ideally be called only by the mappers
	 *
	 * @param int $id
	 */
	public function SetAccountId($id)
	{
		$this->account_id = $id;
		$this->__id = $id;
		$this->MarkDirty();
	}
	
	/**
	 * Returns the contacts collection
	 *
	 * @return Hb_App_Account_AccountContacts
	 */
	public function GetContacts()
	{		
		if (is_null($this->__contacts)) {
			$this->__contacts = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountContacts')->Find($this->account_id);
		}
		
		return $this->__contacts;
	}

	/**
	 * Returns the Comments collection
	 *
	 * @return Hb_App_Account_AccountComments
	 */
	public function GetComments()
	{		
		if (is_null($this->__comments)) {
			$this->__comments = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountComments')->Find($this->account_id);
		}
		
		return $this->__comments;
	}
	
	public function GetPrimaryBillingContact()
	{
		if(is_null($this->__contacts)){
			$this->GetContacts();
		}
		
		if(count($this->__contacts) > 0 ) {
			foreach ($this->__contacts as $contact) {
				if($contact->GetContact()->GetTypeId() == CONTACT_TYPE_PRIMARY_BILLING) {
					return $contact;
				}
			}
		}
		
		return false;		
	}
	
	/**
	 *
	 * Returns a AccountContact object when refered with the contact id
	 * 
	 * @param int $contact_id
	 * @return Hb_App_Account_AccountContact
	 * 
	 * @param contact_id
	 */
	public function GetContact($contact_id)
	{
		if (is_null($this->__contacts)) {
			$this->__contacts = new Hb_App_Account_AccountContacts();
		}
		
		if (!$this->__contacts->HasItem($contact_id)) 
		{
			$contact = Hb_App_ObjectHelper::GetMapper(
				'Hb_App_Account_AccountContact')->Find($contact_id);
			
			$this->__contacts->AddItem($contact);
		}
				
		return $this->__contacts->GetItem($contact_id);
	}

	/**
	 * Returns the Product Collection object
	 * 
	 * @todo  change mapper to collection mapper
	 * @return Hb_App_Account_AccountProducts
	 */
	public function GetProducts()
	{
		if (is_null($this->__products)) {
			$this->__products = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountProducts')->Find($this->account_id);
		}
		
		return $this->__products;
	}
	
	/**
 	 * Returns a AccountProduct object when refered with the product id
	 *
	 * @param int $product_id
	 * @return Hb_App_Account_AccountProduct
	 */
	public function GetProduct($id)
	{ 
		if (is_null($this->__products)) 
		{
			$this->GetProducts();
		}
		
		try {	
			$account_product = Hb_App_ObjectHelper::GetMapper('Hb_App_Account_AccountProduct')->FindByProduct($this->account_id, $id);			
		}catch (Hb_App_Account_Exception_AccountProductNotFoundException  $e) {
			return false;
		}
					
		if (!$this->__products->HasItem($id)) 
		{ 								
			$this->__products->AddItem($account_product); 			
		}	
		
		return $this->__products->GetItem($id);
	}
	
	/**
	 * Returns the Collection of Users Associated with the Account
	 *
	 * @return Hb_App_Account_Users
	 * @todo change mapper to collection mapper
	 * @todo must revist the terminology users and design to use a domain name
	 */
	public function GetUsers()
	{		
		if (is_null($this->__users)) 
		{
			$this->__users = Hb_App_ObjectHelper::GetMapper(
				'Hb_App_Account_AccountUsers')->Find($this->account_id);
		}
		
		return $this->__users;
	}

	/**
	 * Return the collection of account files
	 * 
	 * @return Hb_App_Account_AccountFiles
	 */
	public function GetFiles() {
	   if (is_null($this->__files)) {
	      $this->__files = Hb_App_ObjectHelper::GetMapper("Hb_App_Account_AccountFiles")->Find($this->account_id);
	   }
	   
	   return $this->__files;
	}
	
	/**
	 * Returns the Attribute Collection object
	 * 
	 * @todo  change mapper to collection mapper
	 * @return Hb_App_Account_AccountAttributes
	 */
	public function GetAttributes()
	{
		if (is_null($this->__attributes))	
		{
			$this->__attributes = Hb_App_ObjectHelper::GetMapper(
				'Hb_App_Account_AccountAttributes')->Find($this->account_id);
		}	
		
		return $this->__attributes;
	}
	
	/**
	 * Returns an Attribute object when called with a attribute name
	 * 
	 * @param string $attr_name
	 * @return Hb_App_Account_AccountAttribute
	 * 
	 * @param attr_name
	 */
	public function GetAttribute($attr_name)
	{ 
		return $this->GetAttributes()->GetAttribute($attr_name);
	}

	/**
	 * Returns an boolean if the Attribute is set
	 * 
	 * @param string $attr_name
	 * @return boolean
	 * 	
	 */
	public function HasAttribute($attr_name)
	{
		try {			
		    $this->GetAttribute($attr_name);
		} catch (Hb_Data_ObjectNotInCollectionException $e){
			return false;
		}
		
		return true;
	}
	
	/**
	 * Set the Attribute value
	 *  
	 * @param $attr_name Attribute name
	 * @param $attr_value Attribute value
	 */
	public function SetAttribute($attr_name, $attr_value)
	{	
	    try {
            $this->GetAttribute($attr_name)->SetAttributeValue($attr_value);
	        
	    } catch (Hb_Data_ObjectNotInCollectionException $e){
	        $attr = new Hb_App_Account_AccountAttribute(null, $this->account_id, $attr_name, $attr_value);
	        $this->__attributes->AddAttribute($attr);
	    }
	}
	
	/**
	 * Return the region for the account
	 *
	 * @return int
	 */
	public function GetRegionId()
	{
		if (is_null($this->__country)) {
			$this->__country = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Country')->Find($this->country_code);
		}
		
		return $this->__country->GetRegion();
	}
	
	/**
	 * Return the Country object for the Account
	 *
	 * @return Hb_App_Common_Country Country object
	 */
	public function GetCountry()
	{
		if(is_null($this->__country)) {
			$this->__country = Hb_App_ObjectHelper::GetMapper('Hb_App_Common_Country')->Find($this->country_code);
		}
		
		return $this->__country;
	}

	/**
	 * Set the account name
	 * 
	 * @param $name account name
	 */
	public function SetAccountName($name)
	{
		$this->account_name = $name;
		$this->MarkDirty();
	}
	
	/**
	 * Get Account Name
	 *
	 * @return string
	 */
	public function GetAccountName()
	{
		return $this->account_name;
	}
	
	/**
	 * Set the country code
	 *
	 * @param string $code
	 */
	public function SetCountryCode($code)
	{
		$this->country_code = $code;
		$this->MarkDirty();
	}
	
	/**
	 * Returns the country code
	 *
	 * @return string
	 */
	public function GetCountryCode()
	{
		return $this->country_code;
	}
	
	/**
	 * Set Account Type
	 *
	 * @param int $type
	 */
	public function SetAccountType($type)
	{
		$this->account_type_id = $type;
		$this->MarkDirty();
	}
	
	/**
	 * Return Account Type
	 *
	 * @return int
	 */	
	public function GetAccountType()
	{
		return $this->account_type_id;
	}
	
	/**
	 * Set Account Type
	 *
	 * @param int $type
	 */
	public function SetAccountSubType($type)
	{
		$this->account_sub_type_id = $type;
		$this->MarkDirty();
	}
	
	/**
	 * Return Account Sub Type
	 *
	 * @return int
	 */	
	public function GetAccountSubType()
	{
		return $this->account_sub_type_id;
	}	
	
	/**
	 * Adds Account Contacts
	 *
	 * @param Hb_App_Common_Contact $contact
	 */
	public function AddContact(Hb_App_Common_Contact $contact)
	{
		if (is_null($this->__contacts)) {
			$this->__contacts = new Hb_App_Account_AccountContacts();
		}
		
		$ac = new Hb_App_Account_AccountContact(null, $this->account_id, $contact->GetId());
		$ac->SetAccount($this);
		$ac->SetContact($contact);
		
		$this->__contacts->AddItem($ac);
		
	}
	/**
	 * Returns the Account Type object
	 * 
	 * @todo   change mapper to collection mapper
	 * @return Hb_App_Account_AccountType
	 */
	public function GetAccountTypeObject()
	{
		if (is_null($this->account_type))
		{
			$this->account_type = Hb_App_ObjectHelper::GetMapper(
				'Hb_App_Account_AccountType')->Find($this->GetAccountType());
		}
		
		return $this->account_type;
	}
	
	/**
	 * Returns the Account Sub Type object
	 * 	
	 * @return Hb_App_Account_AccountSubType
	 */	
	
	public function GetAccountSubTypeObj()
	{
		if (is_null($this->account_sub_type))
		{
			$this->account_sub_type = Hb_App_ObjectHelper::GetMapper(
				'Hb_App_Account_AccountSubType')->Find($this->GetAccountSubType());
		}
		
		return $this->account_sub_type;
	}
	
}
?>

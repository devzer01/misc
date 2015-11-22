<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $user_id
 * @property string $login
 * @property string $user_type_id
 * @property string $first_name
 * @property string $last_name
 * @property string $department_id
 * @property integer $division_id
 * @property integer $function_id
 * @property integer $location_id
 * @property integer $vendor_id
 * @property string $last_login
 * @property string $email_address
 * @property string $sms_address
 * @property string $list_id
 * @property string $password
 * @property string $time_zone_id
 * @property string $created_by
 * @property string $created_date
 * @property string $modified_by
 * @property string $modified_date
 * @property string $status
 * @property string $country_code
 * @property string $contact_phone
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('division_id, function_id, location_id, vendor_id', 'numerical', 'integerOnly'=>true),
			array('login, user_type_id, department_id, time_zone_id, created_by, modified_by', 'length', 'max'=>10),
			array('first_name, last_name', 'length', 'max'=>30),
			array('email_address', 'length', 'max'=>100),
			array('sms_address', 'length', 'max'=>50),
			array('list_id', 'length', 'max'=>36),
			array('password', 'length', 'max'=>20),
			array('status', 'length', 'max'=>1),
			array('country_code', 'length', 'max'=>3),
			array('contact_phone', 'length', 'max'=>45),
			array('last_login, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, login, user_type_id, first_name, last_name, department_id, division_id, function_id, location_id, vendor_id, last_login, email_address, sms_address, list_id, password, time_zone_id, created_by, created_date, modified_by, modified_date, status, country_code, contact_phone', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'login' => 'Login',
			'user_type_id' => 'User Type',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'department_id' => 'Department',
			'division_id' => 'Division',
			'function_id' => 'Function',
			'location_id' => 'Location',
			'vendor_id' => 'Vendor',
			'last_login' => 'Last Login',
			'email_address' => 'Email Address',
			'sms_address' => 'Sms Address',
			'list_id' => 'List',
			'password' => 'Password',
			'time_zone_id' => 'Time Zone',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'modified_by' => 'Modified By',
			'modified_date' => 'Modified Date',
			'status' => 'Status',
			'country_code' => 'Country Code',
			'contact_phone' => 'Contact Phone',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('user_type_id',$this->user_type_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('department_id',$this->department_id,true);
		$criteria->compare('division_id',$this->division_id);
		$criteria->compare('function_id',$this->function_id);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('last_login',$this->last_login,true);
		$criteria->compare('email_address',$this->email_address,true);
		$criteria->compare('sms_address',$this->sms_address,true);
		$criteria->compare('list_id',$this->list_id,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('time_zone_id',$this->time_zone_id,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_by',$this->modified_by,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('contact_phone',$this->contact_phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
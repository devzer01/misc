<?php

/**
 * This is the model class for table "account".
 *
 * The followings are the available columns in table 'account':
 * @property string $account_id
 * @property string $account_name
 * @property string $country_code
 * @property string $created_by
 * @property string $created_date
 * @property string $modified_by
 * @property string $modified_date
 * @property string $status
 * @property integer $account_status_id
 * @property integer $account_type_id
 * @property string $total_revenue
 */
class Account extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Account the static model class
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
		return 'account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_name', 'required'),
			array('account_status_id, account_type_id', 'numerical', 'integerOnly'=>true),
			array('account_name', 'length', 'max'=>100),
			array('country_code', 'length', 'max'=>3),
			array('created_by, modified_by, total_revenue', 'length', 'max'=>10),
			array('status', 'length', 'max'=>1),
			array('created_date, modified_date', 'safe'),
			array('account_logo', 'file', 'types'=>'jpg,gif,png', 'maxSize'=>'204800', 'allowEmpty'=>true, 'maxFiles'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('account_id, account_name, country_code, created_by, created_date, modified_by, modified_date, status, account_status_id, account_type_id, total_revenue, account_logo', 'safe', 'on'=>'search'),
		);
	}
	
	public function file($attribute, $params)
	{
		print_r($_FILES);
		print_r($params);
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
			'account_id' => 'Account',
			'account_name' => 'Account Name',
			'country_code' => 'Country Code',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'modified_by' => 'Modified By',
			'modified_date' => 'Modified Date',
			'status' => 'Status',
			'account_status_id' => 'Account Status',
			'account_type_id' => 'Account Type',
			'total_revenue' => 'Total Revenue',
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

		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('account_name',$this->account_name,true);
		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_by',$this->modified_by,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('account_status_id',$this->account_status_id);
		$criteria->compare('account_type_id',$this->account_type_id);
		$criteria->compare('total_revenue',$this->total_revenue,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
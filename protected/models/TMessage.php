<?php

/**
 * This is the model class for table "t_message".
 *
 * The followings are the available columns in table 't_message':
 * @property integer $message_id
 * @property string $message_from
 * @property string $message_to
 * @property string $message_title
 * @property string $message_content
 * @property string $message_date
 * @property integer $message_status
 * @property string $message_action
 * @property integer $message_delete_send
 * @property integer $message_delete_receive
 */
class TMessage extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TMessage the static model class
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
		return 't_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_status, message_delete_send, message_delete_receive', 'numerical', 'integerOnly'=>true),
			array('message_from, message_to', 'length', 'max'=>20),
			array('message_title', 'length', 'max'=>45),
			array('message_action', 'length', 'max'=>200),
			array('message_content, message_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('message_id, message_from, message_to, message_title, message_content, message_date, message_status, message_action, message_delete_send, message_delete_receive', 'safe', 'on'=>'search'),
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
			'message_id' => 'Message',
			'message_from' => 'Message From',
			'message_to' => 'Message To',
			'message_title' => 'Message Title',
			'message_content' => 'Message Content',
			'message_date' => 'Message Date',
			'message_status' => 'Message Status',
			'message_action' => 'Message Action',
			'message_delete_send' => 'Message Delete Send',
			'message_delete_receive' => 'Message Delete Receive',
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

		$criteria->compare('message_id',$this->message_id);
		$criteria->compare('message_from',$this->message_from,true);
		$criteria->compare('message_to',$this->message_to,true);
		$criteria->compare('message_title',$this->message_title,true);
		$criteria->compare('message_content',$this->message_content,true);
		$criteria->compare('message_date',$this->message_date,true);
		$criteria->compare('message_status',$this->message_status);
		$criteria->compare('message_action',$this->message_action,true);
		$criteria->compare('message_delete_send',$this->message_delete_send);
		$criteria->compare('message_delete_receive',$this->message_delete_receive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * This is the model class for table "t_forum".
 *
 * The followings are the available columns in table 't_forum':
 * @property integer $forum_id
 * @property string $forum_from
 * @property string $forum_content
 * @property string $forum_date
 */
class TForum extends ActiveRecord
{
	public $forum_from_name;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TForum the static model class
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
		return 't_forum';
	}
	public function getColumnNames() {        
        return TForum::model()->getTableSchema()->getColumnNames();
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('forum_from', 'length', 'max'=>45),
			array('forum_content, forum_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('forum_id, forum_from, forum_content, forum_date', 'safe', 'on'=>'search'),
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
			'forum_id' => 'Forum',
			'forum_from' => 'Forum From',
			'forum_content' => 'Forum Content',
			'forum_date' => 'Forum Date',
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

		$criteria->compare('forum_id',$this->forum_id);
		$criteria->compare('forum_from',$this->forum_from,true);
		$criteria->compare('forum_content',$this->forum_content,true);
		$criteria->compare('forum_date',$this->forum_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			
		));
	}
	
}
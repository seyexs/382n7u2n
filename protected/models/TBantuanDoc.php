<?php

/**
 * This is the model class for table "t_bantuan_doc".
 *
 * The followings are the available columns in table 't_bantuan_doc':
 * @property integer $id
 * @property integer $t_bantuan_program_id
 * @property integer $parent_id
 * @property string $filename
 * @property string $file_type
 * @property string $path_file
 * @property integer $is_dir
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TBantuanDoc extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TBantuanDoc the static model class
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
		return 't_bantuan_doc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_bantuan_program_id, parent_id, is_dir, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>100),
			array('file_type', 'length', 'max'=>6),
			array('path_file', 'length', 'max'=>200),
			array('created_date, modified_date,share_file', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, t_bantuan_program_id, parent_id, filename, file_type, path_file, is_dir, created_date, created_by, modified_date, modified_by, deleted,share_file', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			't_bantuan_program_id' => 'T Bantuan Program',
			'parent_id' => 'Parent',
			'filename' => 'Filename',
			'file_type' => 'File Type',
			'path_file' => 'Path File',
			'is_dir' => 'Is Dir',
			'created_date' => 'Created Date',
			'created_by' => 'Created By',
			'modified_date' => 'Modified Date',
			'modified_by' => 'Modified By',
			'deleted' => 'Deleted',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('t_bantuan_program_id',$this->t_bantuan_program_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('file_type',$this->file_type,true);
		$criteria->compare('path_file',$this->path_file,true);
		$criteria->compare('is_dir',$this->is_dir);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
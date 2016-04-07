<?php

/**
 * This is the model class for table "t_bantuan_persyaratan_penerima_inquery".
 *
 * The followings are the available columns in table 't_bantuan_persyaratan_penerima_inquery':
 * @property integer $id
 * @property integer $t_bantuan_program_id
 * @property string $query
 * @property string $keterangan
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property integer $deleted
 */
class TBantuanPersyaratanPenerimaInquery extends ActiveRecord
{
	public $data = array();
	public $statusExecute=1;
	public $msg;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TBantuanPersyaratanPenerimaInquery the static model class
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
		return 't_bantuan_persyaratan_penerima_inquery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('t_bantuan_program_id', 'required'),
			array('t_bantuan_program_id, created_by, modified_by, deleted', 'numerical', 'integerOnly'=>true),
			array('query, keterangan, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, t_bantuan_program_id, query, keterangan, created_date, created_by, modified_date, modified_by, deleted', 'safe', 'on'=>'search'),
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
			'tBantuanProgram'=>array(self::BELONGS_TO,'TBantuanProgram','t_bantuan_program_id')
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
			'query' => 'Query',
			'keterangan' => 'Keterangan',
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
		$criteria->compare('query',$this->query,true);
		$criteria->compare('keterangan',$this->keterangan,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function executeQuery() {
        $data = array();
        $status = 'success';
        $query = '';
        try {
            $cmd = Yii::app()->db->createCommand($this->query);
            $this->data = $cmd->queryAll();
        } catch (Exception $e) {
			$this->statusExecute=0;
			$this->msg=$e->getMessage();
        }
        if (!$this->hasErrors()) {
            
            return $this;
        }
    }
}
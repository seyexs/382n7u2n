<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveRecord
 *
 * @author robi
 * @created on Nov 6, 2012
 */
class ActiveRecord extends CActiveRecord {

    public $description;
	private static $dbdapodik = null;
 
    protected static function getDapodikDbConnection()
    {
        if (self::$dbdapodik !== null)
            return self::$dbdapodik;
        else
        {
            self::$dbdapodik = Yii::app()->dbdapodik;
            if (self::$dbdapodik instanceof CDbConnection)
            {
                self::$dbdapodik->setActive(true);
                return self::$dbdapodik;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
        }
    }
    protected function beforeValidate() {
		
			
		if($this->hasAttribute('created_date') && $this->hasAttribute('created_by') && $this->hasAttribute('modified_date') && $this->hasAttribute('modified_by')){
			if ($this->isNewRecord) {
					if($this->hasAttribute('id'))
						unset($this->id);
					
					$this->created_date = $this->modified_date = new CDbExpression('GETDATE()');
					$this->created_by = $this->modified_by = Yii::app()->user->id;
					try{
						$this->deleted=0;
					}  catch (Exception $e){
							
					}
				
				
			} else {
				try{
					$this->modified_date = new CDbExpression('GETDATE()');
					$this->modified_by = Yii::app()->user->id;
				}catch (Exception $e){
					echo $e;
				}
				
			}
		}
        return parent::beforeValidate();
    }
	/*protected function beforeSave(){
		$sqlon='set IDENTITY_INSERT [dbo].['.$this->getOriginalTableName().'] ON;';
		Yii::app()->db->createCommand($sqlon)->execute();
		return parent::beforeSave();
	}
	protected function afterSave(){
		$sqloff='set IDENTITY_INSERT [dbo].['.$this->getOriginalTableName().'] OFF;';
		Yii::app()->db->createCommand($sqloff)->execute();
		return parent::afterSave();
	}*/
    public function beforeFind() {
        //$this->getDbCriteria()->addCondition('INOBSOLETE=0');
        parent::beforeFind();
    }

    public function beforeDelete() {
        //$dt = new CDbExpression('NOW()');
        //$this->saveAttributes(array('INOBSOLETE' => 1, 'DTMODIFY' => $dt, 'IDMODIFIER' => Yii::app()->user->id));
        
        return parent::beforeDelete();
        //return false;
    }

    public function afterFind() {
        /* if (isset($this->DTMODIFY))
          $this->DTMODIFY = Yii::app()->controller->strToTime($this->DTMODIFY); */
        parent::afterFind();
    }

    public function getOriginalTableName() {
        return str_replace(array('{{', '}}'), '', $this->tableName());
    }

    public function getBooleanValue($fieldVal) {
        return ($fieldVal == '1') ? 'Yes' : 'No';
    }

    public function extractTime($tmstmp) {
        $t = preg_split('/ /', $tmstmp);
        $tm = preg_split('/[.]/', $t[1]);
        if ($t[2] == 'PM')
            $tm[0] = intval($tm[0]) + 12;
        return $tm[0] . ':' . $tm[1] . ':' . $tm[2] . ' ' . $t[2];
    }

    public static function getModelListOptions($conditions='', $value='', $label='') {
        $list = self::model()->findAll(array(
            'select' => $value . ',' . $label,
            'order' => 'lower(' . $label . ')',
            'condition' => $conditions
                ));
        return CHtml::listData($list, $value, $label);
    }

    public function truncate() {
        Yii::app()->db->createCommand()->truncateTable($this->tableName());
    }
    public function unsetFKChecks(){
        Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
    }
    public function setFKChecks(){
        Yii::app()->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

}

?>

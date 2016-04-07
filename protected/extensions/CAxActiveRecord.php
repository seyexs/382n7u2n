<?php

class CAxActiveRecord extends CActiveRecord {

    protected function beforeValidate() {
        if ($this->isNewRecord) {
            $this->INOBSOLETE = 0;
            $this->DTCREATE = $this->DTMODIFY = new CDbExpression('NOW()');	
            $this->IDCREATOR = $this->IDMODIFIER = Yii::app()->user->id;
        } else {
            $this->DTMODIFY = new CDbExpression('NOW()');
            $this->IDMODIFIER = Yii::app()->user->id;
        }
        return parent::beforeValidate();
    }

    public function beforeFind() {
        $this->getDbCriteria()->addCondition('INOBSOLETE=0');
        parent::beforeFind();
    }

    public function beforeDelete() {
        $dt = new CDbExpression('NOW()');
        $this->saveAttributes(array('INOBSOLETE' => 1, 'DTMODIFY' => $dt, 'IDMODIFIER' => Yii::app()->user->id));
        parent::beforeDelete();
        return false;
    }

    public function afterFind() {
        /*if (isset($this->DTMODIFY))
            $this->DTMODIFY = Yii::app()->controller->strToTime($this->DTMODIFY);*/
        parent::afterFind();
    }

    public function getOriginalTableName() {
        return Yii::app()->db->tablePrefix . str_replace(array('{{', '}}'), '', $this->tableName());
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

}
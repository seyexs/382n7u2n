<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $id
 * @property integer $sort
 * @property integer $parent
 * @property string $title
 * @property string $url
 * @property string $bizrule
 * @property string $cssclass
 */
class Menu extends CActiveRecord {
	public $menuArray;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Menu the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menu';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sort,parent_id,title, url', 'required'),
            array('sort, parent_id', 'numerical', 'integerOnly' => true),
            array('title, url, bizrule', 'length', 'max' => 255),
			array('cssclass','length','max'=>64),
			array('cssclass,petunjuk_penggunaan','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
//            array('id, sort, parent_id, title, url, bizrule, cssclass', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'getparent' => array(self::BELONGS_TO, 'Menu', 'parent_id'),
            'childs' => array(self::HAS_MANY, 'Menu', 'parent_id', 'order' => 'sort ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'sort' => 'Sort',
            'parent_id' => 'Parent',
            'title' => 'Title',
            'url' => 'Url',
            'bizrule' => 'Bizrule',
            'cssclass' => 'CSS Class'
        );
    }
	
	private function cekDaftarMenu($id){
		$dbname=Yii::app()->params['dbname'];
		$uid=Yii::app()->user->id;
		$strQry='
			select count(*) as jml from (
			select * from (
			SELECT menu.*
						  FROM '.$dbname.'.[authitemchild] m 
						  inner join '.$dbname.'.[authassignment] g on g.itemname=m.parent
						  inner join '.$dbname.'.[menu] menu on m.child=menu.bizrule
						  where g.userid='.$uid.'
			UNION
			select mn.* from '.$dbname.'.[menu] mn
			inner join (
			SELECT menu.*
						  FROM '.$dbname.'.[authitemchild] m 
						  inner join '.$dbname.'.[authassignment] g on g.itemname=m.parent
						  inner join '.$dbname.'.[menu] menu on m.child=menu.bizrule
						  where g.userid='.$uid.'
			) as tbl on mn.path=left(tbl.path,len(mn.path)) where mn.url=\'#\'
			) as tbl_menu where tbl_menu.id='.$id.'
			) as tbl
			';
		$model=Yii::app()->db->createCommand($strQry)->queryAll();
		return $model[0]['jml'];
	}
	
    public function getListed() {
        $subitems = array();
		$c=false;
        if ($this->childs)
            foreach ($this->childs as $child) {
				//$c=!empty($child->bizrule) ? Yii::app()->user->checkAccess($child->bizrule):true;
				$c=$this->cekDaftarMenu($child->id);
				//echo $child->id."\n";
				//echo $c;exit;
				if($c || $child->parent_id==1){
					$subitems[] = $child->getListed();
				}
            }
		
        $returnarray = array(
            'id'=>$this->id,
			'text' => ($this->childs)?'<b>'.$this->title.'</b>':$this->title, 
			'parentid'=>$this->parent_id,
            'hrefTarget' => $this->url, 
            //'hidden' => Yii::app()->user->checkAccess($this->url),//(!empty($this->url) && $this->url!='#') ?Yii::app()->user->checkAccess($this->url):false, 
            'visible' => !empty($this->bizrule) ? Yii::app()->user->checkAccess($this->bizrule):true,
            'leaf'=>($this->childs)?false:true,
            'iconCls' => !empty($this->cssclass)?$this->cssclass:'',
        );
		
        if ($subitems != array())
            $returnarray = array_merge($returnarray, array('children' => $subitems));
		return $returnarray;
        
		
    }
	public function getMenuItems2(){
		$dbname=Yii::app()->params['dbname'];
		$data=Menu::model()->findAll(array(
			'condition'=>'path in (SELECT path
			  FROM '.$dbname.'.[authitemchild] m 
			  inner join '.$dbname.'.[authassignment] g on g.itemname=m.parent
			  inner join '.$dbname.'.[menu] menu on m.child=menu.bizrule
			  where g.userid=:id)',
			'params'=>array(':id'=>Yii::app()->user->id)  
		));
		return json_encode($data);
	}
    public function getMenuItems(){
        $items[] = $this->getListed();
        $subitems = array();
        if(!empty ($items)){
            foreach($items as $item)
                $subitems = $item['children'];
        }
		//benerin array nya soalnya ada index array yang null 
		foreach($subitems[0]['children'] as $idx=>$i){
			if(!($i['leaf']) && !isset($i['children'])){
				unset($subitems[0]['children'][$idx]);
				
			}
		}
		$hasilRepair=array();
		foreach($subitems[0]['children'] as $idx=>$d){
			if($subitems[0]['children'][$idx])
				$hasilRepair[]=$d;
		}
		//print_r($hasilRepair);exit;
		//$hasilRepair=$this->cekChildren($hasilRepair);
		$subitems[0]['children']=$hasilRepair;
		//print_r($subitems);exit;
        return json_encode($subitems);
    }
    private function cekChildren($data){
		foreach($data as $d){
			if(!isset($d['children']) && !($d['leaf'])){
				echo "hapus ".$d['text']."\n";
				return $d;
			}else if(!$d['leaf'] && isset($d['children'])){
				//echo "kirim ->".$d['text']."\n";
				//print_r($d);
				//echo "===============================================================================\n";
				$hapus=$this->cekChildren($d['children']);
				unset($hapus);
			}
		}
		//return $data;
	}
	
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('sort', $this->sort);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('bizrule', $this->bizrule, true);
		$criteria->compare('cssclass', $this->cssclass);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}

<?php
class TBantuanPersyaratanPenerimaInqueryController extends Controller
{
	public $jmlRowData=0;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using one-column layout. See 'protected/views/layouts/column1.php'.
	 */
	
        
        public function init() {
            parent::init();
        }
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
                    'rights',
		);
	}

	public function allowedActions() 
        { 
            return ''; 
        }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TBantuanPersyaratanPenerimaInquery;

		$data = json_decode(stripslashes($_POST['data']));

        foreach ($data as $key => $val) {
			if($model->hasAttribute($key))
				$model->$key = $val;
        }
				
        if ($model->save()) {
            echo json_encode(array(
                "success" => true,
                "data" => array(
                "id" => $model->id,
                )
            ));
        }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	
    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id the ID of the model to be updated
    */
    public function actionUpdate($id) {

        $put_var = array();
        parse_str(file_get_contents('php://input'), $put_var);

        foreach ($put_var as $data) {
            $json = CJSON::decode($data, true);
        }

        $model = $this->loadModel($id);

        foreach ($json as $var => $value) {
            // Does model have this attribute? If not, raise an error
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            }
        }

        if ($model->save()){
            echo json_encode(array(
                "success" => true,
                "data" => array(
                    "id" => $model->id,
                )
            ));
        }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

                $model=$this->loadModel($id);
				$model->deleted=1;
				
                echo json_encode(array(
                    "success" => ($model->save())
                ));
        }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$this->render('index');
	}
	public function actionIndexBos()
	{
		$bantuanOperasionalid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
		$this->render('indexBos',array(
			'r_bantuan_id'=>$bantuanOperasionalid
		));
	}
	public function actionDisplayResult(){
		$bantuanOperasionalid='90A69530-96AB-40E1-A315-EF7B0A9DF67A';
		$bantuan=TBantuanProgram::model()->findAll(array(
			'condition'=>'deleted=0 and r_bantuan_id=:id',
			'params'=>array(':id'=>$bantuanOperasionalid)
		));
		$items="";
		foreach($bantuan as $b){
			$query=TBantuanPersyaratanPenerimaInquery::model()->findAll(array(
				'condition'=>'deleted=0 and t_bantuan_program_id=:id',
				'params'=>array(':id'=>$b->id)
			));
			if(isset($query[0]->id)){
				if($items==""){
				$items="{
					title:'".$b->nama."',
					iconCls: 'icon-new-data',
					autoScroll:true,
					layout:'fit',
						items:[Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._query_result_process',{
							id:'tabitem".$query[0]->id."',
							frame:false,
							border:0,
							queryId:".$query[0]->id.",
						})]
					}";
				}else{
					$items.=",{
					title:'".$b->nama."',
					iconCls: 'icon-new-data',
					autoScroll:true,
					layout:'fit',
						items:[Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._query_result_process',{
							id:'tabitem".$query[0]->id."',
							frame:false,
							border:0,
							dataQueryId:".$query[0]->id.",
						})]
					}";
				}
			}
		}
		$this->render('_query_result_process',array(
			'items'=>$items
		));
	}
	public function actionGetBantuan(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$appDb=Yii::app()->params['appDb'];
            $rbid=(isset($_GET['rbid']) && $_GET['rbid']<>'')?' and r_bantuan_id=\''.$_GET['rbid'].'\' ':' ';
            $total = TBantuanProgram::model()->count(array(
				'condition'=>'deleted=0'.$rbid.'and id not in(select t_bantuan_program_id from '.$appDb.'.dbo.[t_bantuan_persyaratan_penerima_inquery] where deleted=0)',
			));
			
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanProgram::model()->findAll(array(
				'condition'=>'(kode_wilayah=\'\' or kode_wilayah is null) and deleted=0'.$rbid.'and id not in(select t_bantuan_program_id from '.$appDb.'.dbo.[t_bantuan_persyaratan_penerima_inquery] where deleted=0)',
				'limit'=>$limit, 
				'offset'=>$start
			));
			
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $model
            ));

            Yii::app()->end();
    }
	/**
        * Read all table content
    */
        
    public function actionRead(){
            $this->layout = false;

            $start = (int) $_GET['start'];
            $limit = (int) $_GET['limit'];
			$q=(isset($_GET['q']))?$_GET['q']:'';
			$appDb=Yii::app()->params['appDb'];
            $rbid=(isset($_GET['rbid']) && $_GET['rbid']<>'')?' and p.r_bantuan_id=\''.$_GET['rbid'].'\'':'';
            $total = TBantuanPersyaratanPenerimaInquery::model()->count(array(
				'join'=>'inner join '.$appDb.'.dbo.t_bantuan_program p on t.t_bantuan_program_id=p.id',
				'condition'=>'t.deleted=0 and (t.query like :q1 or t.keterangan like :q2)'.$rbid,
				'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
			));
			$limit=($limit+$start>$total)?($total-$start):$limit;
            $model = TBantuanPersyaratanPenerimaInquery::model()->findAll(array(
				'join'=>'inner join '.$appDb.'.dbo.t_bantuan_program p on t.t_bantuan_program_id=p.id',
				'condition'=>'t.deleted=0 and (t.query like :q1 or t.keterangan like :q2)'.$rbid,
				'params'=>array(':q1'=>'%'.$q.'%',':q2'=>'%'.$q.'%'),
				'limit'=>$limit, 
				'offset'=>$start
			));
			$data=array();
			foreach($model as $d){
				$data[]=array(
					'id'=>$d->id,
					't_bantuan_program_id'=>$d->t_bantuan_program_id,
					't_bantuan_program_nama'=>$d->tBantuanProgram->nama,
					'query'=>$d->query,
					'keterangan'=>$d->keterangan,
					'deleted'=>$d->deleted
				);
			}
            
            echo CJSON::encode(array(
                "success" => true,
                "total" => $total,
                "data" => $data
            ));

            Yii::app()->end();
    }
	public function actionExecute(){
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$id=$_POST['id'];
		$appDb=Yii::app()->params['appDb'];
		$dapodikmenDb=Yii::app()->params['dapodikmenDb'];
        $model = $this->loadModel($id);
        if (isset($id)) {
			//print_r(Yii::app()->db->createCommand($model->query)->queryAll());exit;
			//$model->query="select 123 as nilai";
			if($model->tBantuanProgram->rBantuanPenerima->kode=="SK"){
				$strModifQuery="select tbl.*,case when sekolah_id in(select sekolah_id from ".$appDb.".dbo.t_bantuan_penerima where t_bantuan_program_id=".$model->t_bantuan_program_id.") then 'SP|Ok' else 'SP' end as status_data from (".$model->query.") as tbl";
			}else{
				$strModifQuery="select tbl.*,case when peserta_didik_id in(select a.peserta_didik_id from ".$appDb.".dbo.t_bantuan_penerima_siswa a inner join ".$appDb.".dbo.t_bantuan_penerima b on a.t_bantuan_penerima_id=b.id where b.t_bantuan_program_id=".$model->t_bantuan_program_id.") then 'PD|Ok' else 'PD' end as status_data from (".$model->query.") as tbl";
			}
			$model->query=$strModifQuery;
            $model->executeQuery();
			if($model->statusExecute==0){
				echo CJSON::encode(array(
					"success" => true,
					"total" => 0,
					"result" => '',
					'message'=>$model->msg,//'Query data gagal!'
				));

				Yii::app()->end();
			}
			
			if (!empty($model->data)) {
				$hData = $model->data[0];
				$numcolumn = count($hData);
				$gridColumn=array();
				$modelColumn=array();
				$data=array();
				//$gridColumn[]=array('xtype'=>'rownumberer','text'=>'No.','sortable'=>false,'flex'=>false,'width'=>40);
				
				foreach ($hData as $h => $v) {
					$label=ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $h)))));
					$label=preg_replace('/\s+/',' ',$label);
					if(strcasecmp(substr($label,-3),' id')===0)
						$label=substr($label,0,-3);
					if($label==='Id')
						$label='ID';
					
					$sType="function(records,v){Ext.getCmp('bantuan123123').actionSum(records,v,this.dataIndex);}";
					$gridColumn[]=array(
						'dataIndex'=>$h,
						'text'=>$label,
						'flex'=>true,
						'renderer'=>'=r=',
						'summaryType'=>'=stype=',
						'summaryRenderer'=>'=srender=',
					);
					$modelColumn[]=array('name'=>$h);
				}
				
				$json=CJSON::encode(array(
					"success" => true,
					"total" => count($model->data),
					"gridColumn" => $gridColumn,
					'modelColumn'=>$modelColumn,
					'data'=>$model->data,//array('total'=>count($model->data),'data'=>$model->data),
					'message'=>''
				));
				$json=str_replace('"=stype="',"function(records,dataIndex,v){var container=(Ext.getCmp('calonpenerimabantuantabitemcontentid'))?Ext.getCmp('calonpenerimabantuantabitemcontentid'):Ext.getCmp('persyaratanpenerimainquerydisplaycontainer');return container.actionSum(records,dataIndex,v);}",$json);
				$json=str_replace('"=srender="',"function(value,summaryData,dataIndex){var container=(Ext.getCmp('calonpenerimabantuantabitemcontentid'))?Ext.getCmp('calonpenerimabantuantabitemcontentid'):Ext.getCmp('persyaratanpenerimainquerydisplaycontainer');return container.actionGroupSum(value,summaryData,dataIndex);}",$json);
				$json=str_replace('"=r="',"function(value){return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;}",$json);
				
				echo $json;
				unset($model);
				Yii::app()->end();
			}else{
				echo CJSON::encode(array(
					"success" => true,
					"total" => 0,
					"result" => '',
					'message'=>'Hasil Query Data Kosong!!'
				));

				Yii::app()->end();
			}
        }else{
			echo 'id kosong';
		}
        
    }
	public function actionExportExcel(){
		$id=$_GET['id'];
		$this->downloadExport($id);
	}
	private function downloadExport($id){
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$dir='./media/tmp/u-'.Yii::app()->user->id.'/';
		$kolom=array();
		$kolom2=array();
		/*sedia 701 kolom :D a-zz */
		for ($col = ord('a'); $col <= ord('z'); $col++){
			$kolom[]=chr($col);
			for ($col2 = ord('a'); $col2 <= ord('z'); $col2++){
				$kolom2[]=chr($col).chr($col2);
			}
		}
		$kolom=array_merge($kolom,$kolom2);
		$start_row=$var_start_row=3;
		$style_row=11;
		$max_column="AN";
		$model=$this->loadModel($id);
		$model->data=Yii::app()->db->createCommand($model->query)->queryAll();
		//$dt=Yii::app()->db->createCommand($model->query)->queryAll();
		//exit;
		$excel=$this->buildArrayExcel($kolom,$model,$start_row,'-','-');
		//print_r($excel);exit;
		//$excel['G5']=date("Y-m-d H:i:s");
		$inputFileName = Yii::getPathOfAlias('application.views').DIRECTORY_SEPARATOR.'tBantuanPersyaratanPenerimaInquery/tmp/export.xls';
		echo $this->performDownload('Data Export.xls',$this->createExcel('save',$excel,$inputFileName,'Data Export',$var_start_row,$this->jmlRowData,$max_column,$dir));
		unset($model);
	}
	private function buildArrayExcel($kolom,$model,$start_row,$nama_prop,$nama_kab){
		$no=1;
		$hData = $model->data[0];
		$numcolumn = count($hData);
		$number=1;
		$excel=array();
		$index=1;
		$excel['A2']='No.';
		foreach ($hData as $h => $v) { 
            $label=ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $h)))));
			$label=preg_replace('/\s+/',' ',$label);
			if(strcasecmp(substr($label,-3),' id')===0)
				$label=substr($label,0,-3);
			if($label==='Id')
				$label='ID';
			
			
			$excel[$kolom[$index].'2']=$label;
			$index++;
		}
		//print_r($excel);exit;
		
		foreach ($model->data as $idx=>$data){
			$index=1;
			/*no*/
			$excel['A'.$start_row]=$no;
			//print_r($data);exit;
			foreach ($data as $h => $v) {
				$nextKolom=($idx+$index);
				$excel[$kolom[$index].$start_row]=isset($data[$h]) ? $data[$h] : '';
				//echo 'excel['.$kolom[$nextKolom].$start_row.']='.$data[$h].'</br>';
				$index++;
				//print_r($excel);exit;
			}
			//print_r($excel);exit;
			$start_row+=1;
			$no+=1;
			
		}
		//print_r($excel);exit;	
		$this->jmlRowData=$no;
		return $excel;
	}
	private function createExcel($mode,$excel,$inputFileName,$nama_file,$start_row,$jml_data,$max_column,$dir){
		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
        require_once($phpExcelPath. DIRECTORY_SEPARATOR."PHPExcel/IOFactory.php");
        $inputFileType = 'Excel5';
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		
		$objPHPExcel = $objReader->load($inputFileName);
		//echo 'copying style '.'A'.$style_row;
		//$style=$objPHPExcel->getActiveSheet()->g‌etStyle('A'.$style_row);
		//echo ' success!';
		$sheet=$objPHPExcel->getActiveSheet();
		$sheet->insertNewRowBefore($start_row + 1, $jml_data)->getDefaultStyle()->getFont()->setName('Calibri')->setSize(14);
		foreach($excel as $kolom=>$v){
				$objPHPExcel->getActiveSheet()->getCell($kolom)->setValueExplicit($v, PHPExcel_Cell_DataType::TYPE_STRING);
				//$objPHPExcel->getActiveSheet()->setCellValue($kolom,$v);
				//$objPHPExcel->getActiveSheet()->getStyle($kolom)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				//$objPHPExcel->getActiveSheet()->getColumnDimension($kolom)->setAutoSize(true);
		}
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.':D'.($start_row+($jml_data-2)))->getProtection()->setLocked( PHPExcel_Style_Protection::PROTECTION_PROTECTED );
		//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
		//$objPHPExcel->getActiveSheet()->getProtection()->setPassword('verwilditpsmk2014');
		
		 
		// Write out as the new file
		$outputFileType = 'Excel5';
		//$dir='./media/mydocuments/uploads/public/data-verwil-format-1/';
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}
		$outputFileName = $dir.$nama_file.'.xls';
		//echo 'saving '.$nama_file.'.xls ';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outputFileType);
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save($outputFileName);
		return $outputFileName;
	}
	private function performDownload($filename='Data Verifikasi Wilayah.xls',$path){
		$filename=str_replace(' ','.',$filename);
        $filecontent = file_get_contents($path);
        header("Content-Type: application/ms-excel");
        header("Content-disposition: attachment; filename=".$filename);
        header("Pragma: no-cache");
        echo $filecontent;
        exit;
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=TBantuanPersyaratanPenerimaInquery::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbantuan-persyaratan-penerima-inquery-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

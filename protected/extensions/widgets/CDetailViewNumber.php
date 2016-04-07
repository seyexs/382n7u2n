<?php

Yii::import('zii.widgets.CDetailView');


class CDetailViewNumber extends CDetailView{

	public $setRowNumber = false;
	public $nullDisplay = '';
	/**
	 * Renders the detail view.
	 * This is the main entry of the whole detail view rendering.
	 */
	public function run()
	{
		$formatter=$this->getFormatter();
		if ($this->tagName!==null)
			echo CHtml::openTag($this->tagName,$this->htmlOptions);

		$i=0;
		$n=is_array($this->itemCssClass) ? count($this->itemCssClass) : 0;
						
		foreach($this->attributes as $attribute)
		{
			if(is_string($attribute))
			{
				if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/',$attribute,$matches))
					throw new CException(Yii::t('zii','The attribute must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
				$attribute=array(
					'name'=>$matches[1],
					'type'=>isset($matches[3]) ? $matches[3] : 'text',
				);
				if(isset($matches[5]))
					$attribute['label']=$matches[5];
			}
			
			if(isset($attribute['visible']) && !$attribute['visible'])
				continue;
			
			$tr=array('{row_no}'=>'', '{label}'=>'', '{class}'=>$n ? $this->itemCssClass[$i%$n] : '');
			
			if(isset($attribute['cssClass']))
				$tr['{class}']=$attribute['cssClass'].' '.($n ? $tr['{class}'] : '');
			
			if ($this->setRowNumber == true)
				$tr['{row_no}']=($i+1);

			if(isset($attribute['label']))
				$tr['{label}']=$attribute['label'];
			else if(isset($attribute['name']))
			{
				if($this->data instanceof CModel)
					$tr['{label}']=$this->data->getAttributeLabel($attribute['name']);
				else
					$tr['{label}']=ucwords(trim(strtolower(str_replace(array('-','_','.'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $attribute['name'])))));
			}

			if(!isset($attribute['type']))
				$attribute['type']='text';
			if(isset($attribute['value']))
				$value=$attribute['value'];
			else if(isset($attribute['name']))
				$value=CHtml::value($this->data,$attribute['name']);
			else
				$value=null;

			$tr['{value}']=$value===null ? $this->nullDisplay : $formatter->format($value,$attribute['type']);

			$this->renderItem($attribute, $tr);

			$i++;
		}

		if ($this->tagName!==null)
			echo CHtml::closeTag($this->tagName);
	}

}

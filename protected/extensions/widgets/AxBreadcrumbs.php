<?php
Yii::import('zii.widgets.CBreadcrumbs');
class AxBreadcrumbs extends CBreadcrumbs
{
	public $separator='&gt;';

	/**
	 * Renders the content of the portlet.
	 */
	public function run()
	{
		if(empty($this->links))
			return;

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
                echo CHtml::openTag('ul');
		$links=array();
		if($this->homeLink===null){
                    $links[]='<li>'.CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl).'</li>';
                }
		else if($this->homeLink!==false){
                    $links[]='<li>'.$this->homeLink.'</li>';
                }
		foreach($this->links as $label=>$url)
		{
			if(is_string($label) || is_array($url))
				$links[]='<li>'.CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url).'</li>';
			else
				$links[]='<li>'.'<strong>'.($this->encodeLabel ? CHtml::encode($url) : $url).'</strong>'.'</li>';
		}
		//print_r($links);
                //echo "<br>";
                echo implode('<li class="bc-spacer">'.$this->separator.'</li>',$links);
                echo CHtml::closeTag('ul');
		echo CHtml::closeTag($this->tagName);
                echo '<div class="clearboth"></div>';
	}
}
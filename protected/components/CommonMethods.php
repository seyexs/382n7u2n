<?php

/*
 * /protected/components/CommonMethods.php
 * 
 */


class CommonMethods {
    
    private $data = array();
    
    public function makeDropDown($parents)
    {
        global $data;
        $data = array();
        //$data['0'] = '-- ROOT --';
        foreach($parents as $parent)
        {
			if ( $parent->id >= 1 ){
				//$data[$parent->id] = $parent->title;
				$data[]=array('id'=>$parent->id,'title'=>$parent->title);
				$this->subDropDown($parent->childs);
			}
        }
       return $data;
    }
    
    
    public function subDropDown($children,$space = '---')
    {
        global $data;
        foreach($children as $child)
		{
			//$data[$child->id] = $space.$child->title.' ('.$child->sort.')';
			$data[]=array('id'=>$child->id,'title'=>$space.$child->title.' ('.$child->sort.')');
			$this->subDropDown($child->childs,$space.'---');
		}
    }
}

?>

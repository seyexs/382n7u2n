<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebUser
 *
 * @author ardha
 * @created on Nov 5, 2012
 */
class WebUser extends RWebUser {
    //put your code here
    
    public $logoutUrl = array('/site/logout');
    public $signupUrl = array('/site/signup');
    //private $authItems = array();
    
    public function setUserAuthItems(){
        if(!Yii::app()->user->isGuest){
            $oAuthItems = Yii::app()->getAuthManager()->getAuthItems(NULL, $this->Id);
            $authItems = array();
            foreach($oAuthItems as $item => $auth)
                $authItems[] = $item;
            $this->setState('authItems', $authItems);
        }
        else
            $this->setState('authItems', array());
    }
    public function checkAccessUrl($url){
        $authItems = $this->getState('authItems');
        if(!empty($authItems)){
            $v = explode('/', $url);
            if(count($v) > 2)
                $cid = $v[1];
            else
                $cid = $v[0];
            
            
        }
        return false;
    }
    
}

?>
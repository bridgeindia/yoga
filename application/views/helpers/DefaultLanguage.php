<?php

require_once 'Zend/Session.php';
class Zend_View_Helper_DefaultLanguage extends Zend_View_Helper_Abstract
{
    function defaultLanguage() {
	
	 $sess = new Zend_Session_Namespace('UserLanguage');
		$lang_browser = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		
		if($lang_browser !="")
	        {
	        	if($lang_browser == "en")
	        	{
	        		$browserLanguage = 1;
	        	}
	        	elseif($lang_browser == "de") 
	        	{
	        		$browserLanguage = 2;
	        	}
	        	else {
	        		$browserLanguage = 1;
	        	}
	        }
		
		
        if($sess->lang !="")
        {
        	if($sess->lang == "en")
        	{
        		$defaultLanguage = 1;
        	}
        	else 
        	{
        		$defaultLanguage = 2;
        	}
        }
        else 
        {
        	$defaultLanguage = $browserLanguage;
        }
        return $defaultLanguage;
	
	}
}
?>
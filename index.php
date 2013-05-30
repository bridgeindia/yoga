<?php 
/* Created By: Lekha
*  On :6th Jan 2012  
*/

ini_set('display_errors',1);
date_default_timezone_set('Europe/London');

//directory setup and class loading
set_include_path('.'. PATH_SEPARATOR .'library/'
                . PATH_SEPARATOR . 'application/models'
                . PATH_SEPARATOR . get_include_path());

//include "../library/Zend/Loader.php";
//Zend_Loader::registerAutoload();

require_once 'library/Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);

//load configuration
$config = new Zend_Config_Ini('application/config.ini','general');
$registry = Zend_Registry::getInstance();
$registry->set('config',$config);
$baseUrl = $config->baseHttp;
define('BASE_URL', $baseUrl);

$lang_browser = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

ini_set('session.save_path', dirname(__FILE__).'/tmp/sessions');

$sess = new Zend_Session_Namespace('UserLanguage');

if($sess->lang != "") 
{
	$lang = $sess->lang ;
}
else 
{
   
	if(preg_match("/(bg|cs|da|el|es|da|de|et|fi|fr|ga|hu|it|lt|lv|mt|nl|pl|pt|ro|sk|sv)([,;].*)?$/i",$lang_browser))
	 { 
	    if(($lang_browser=='de'))
			{
				$lang = $lang_browser;
			}
			else
			{
				$lang   = "en";
			}
		
	}
	else
	{
		$lang   = "en";
	}
}


    
Zend_Loader::loadClass('Zend_Translate');
Zend_Loader::loadClass('Zend_Registry');
$translationFilePath = 'languages/' . $lang . '/default.mo';
$translate = new Zend_Translate('gettext', $translationFilePath);
$registry = Zend_Registry::getInstance();
$registry->set('Zend_Translate', $translate);

//database setup
$db = Zend_Db::factory($config->db);
Zend_Db_Table::setDefaultAdapter($db);

//setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(false);
$frontController->setControllerDirectory('application/controllers');
Zend_Layout::startMvc(array('layoutPath'=>'application/layouts'));
//array('layoutPath'=>'application/layouts')

//session
Zend_Session::start();
$objsession = new Zend_Session_Namespace('Default'); 

//run
$frontController->dispatch();
                
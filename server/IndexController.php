<?php ini_set('display_errors', 1);
/* Created By : Lekha
 * On : 5th March 2012
 * Index Controller Class
*/



require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Session.php';
require_once 'Zend/Mail.php';
require_once 'class.phpmailer.php';


Zend_Layout::startMvc(array('layoutPath'=>'application/layouts/index'));

class IndexController extends Zend_Controller_Action
{
	
	//private $toemail = "lekha@bridge-india.in";
	
	 /*
	  * created by Lekha
	  * date : 5/3/2012
	  * index action
	  * 
	  */
    function indexAction()
    {
    	
    	
    	
    	$this->view->pageTitle = "Welcome to Fitness4me ";
        $this->view->bodyCopy = "<p > Login.</p>";
       
          $this->_redirect('/index/home');
                 
    }
    
    
     /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the home page
	  * 
	  */
    function homeAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
	    	
	    	$this->_helper->layout()->setLayout('layout_home');
	    	
	    	$fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
			
     	    $pageId           = $fitnessPages->getPageByName('Homepage');
     	    $aboutId          = $fitnessPages->getPageByName('workhow');
			$faqId            = $fitnessPages->getPageByName('guarantee');
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
			
     	    $aboutContent     = $fitnessPagesMulti->getPage($aboutId['page_id'],$defaultLang);
			$faqContent       = $fitnessPagesMulti->getPage($faqId['page_id'],$defaultLang);
     	   
     	    $this->view->pageContent      = substr(strip_tags($pageContent['page_content']),0,195);
			$this->view->pageTitle      = $pageContent['page_title'];
     	    $this->view->aboutContent     = substr(strip_tags($aboutContent['page_section1']),0,245);
			$this->view->aboutTitle      = $aboutContent['page_title'];
			$this->view->faqContent       = substr(strip_tags($faqContent['page_content_sub']),0,218);
			$this->view->faqTitle      = $faqContent['page_title'];
			
			$this->view->defaultLang    =  $defaultLang;
			
	    	
	    	//if($this->view->loginStatus == 1)
	    	//$this->view->memberStatus = $this->checkUserMembership();
	    	
	    	
	    }
		
		public function supportAction()
		{
			$this->_helper->layout()->disableLayout();
		}
	    
	    
	      /*
		  * created by Lekha
		  * date : 25/4/2012
		  * action for the contact page
		  * 
		  */
	    function contactpageAction()
	    {
	    	
	    	$this->view->loginStatus  = $this->isLoggedIn();
	    	
	    	$translate = Zend_Registry::get('Zend_Translate');
	    	
	    	if ($this->_request->isPost())    
				{
				  if($_SESSION['cap_code']==$_POST['captcha'])
				  {
				  	
				  
			    	$name = $_POST['name'];
					$email = $_POST['email'];
					$phone = $_POST['phone'];
					$call = $_POST['call'];
					$website = $_POST['website'];
					$priority = $_POST['priority'];
					$type = $_POST['type'];
					$message = $_POST['message'];
					$formcontent="Hello,<br/><br/>You have a message from $name .<br/>";
					$formcontent .="Email : $email<br/>";
					$formcontent .="Message : $message<br/>";
					$recipient = "lekbin@gmail.com";
					$subject = $translate->translate("Contact Form - Fitness4me - priority : $priority");
					
					 //$ccaddress = "lekbin@gmail.com";
					$address = "support@fitness4.me";
					//$address = "lekbin@gmail.com";

						$body="Hello,<br/><br/>You have a message from $name .<br/>";
						$body .="Email : $email<br/>";
						$body .="Message : $message<br/>";
						
						 // To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= 'From: Fitness4me<'.$email.'>' . "\r\n";
							$headers .= 'Cc: Fitness4me<'.$ccaddress.'>' . "\r\n";
							
							// Additional headers
							$headers .= 'From: Fitness4me' . "\r\n";
							
							mail($address,$subject,$body,$headers);
						
						
								
								$this->view->message  = $translate->translate("Thank you for contacting us. We will get back to you soon.");
								}
								else
								{
									$this->view->captchaerror  = $translate->translate("The captcha value you entered is not correct.Please try again.");
								}
								
				}
	    }
   
      /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the home page detail
	  * 
	  */
     function homepageAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
	    	
	    	$this->_helper->layout()->setLayout('layout_homepage');
	    	
	    	$fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('Homepage');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	    $pageContent1      = str_replace("<strong>","<b>",$pageContent['page_content']);
            $this->view->pageContent      = str_replace("</strong>","</b>",$pageContent1);
			$pageContent2      = str_replace("<strong>","<b>",$pageContent['page_content_sub']);
            $this->view->pageContent2      = str_replace("</strong>","</b>",$pageContent2);
     	    $this->view->defaultLang    =  $defaultLang;
	    }
    
	
	
	function headerAction()
	{
		$defaultLang      = $this->getDefaultLanguage();
		$this->view->defaultLang    =  $defaultLang;
	}
    /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the workhow page
	  * 
	  */
     function workhowAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			$this->_helper->layout()->setLayout('layout_tour');
	    	
	        $fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	   /* $pageId           = $fitnessPages->getPageByName('workhow');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	    $this->view->pageContent      = str_replace("\n","<br/>",$pageContent['page_content']);*/
			
			$pageIdMain           = $fitnessPages->getPageByName('workhow');
     	   
     	   
     	    
     	    $pageContentMain      = $fitnessPagesMulti->getPage($pageIdMain['page_id'],$defaultLang);
     	   
     	    
			
			$pageId           = $fitnessPages->getPageByName('tourflexibility');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
			
			
			
			$pageIdExercise           = $fitnessPages->getPageByName('tourexercise');
     	   
     	   
     	    
     	    $pageContentExerc      = $fitnessPagesMulti->getPage($pageIdExercise['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitleExer        = mb_convert_encoding($pageContentMain['page_section2_title'],"UTF-8");
     	   // $this->view->pageContentExer      = str_replace("\n","<br/>",$pageContentExerc['page_content']);
		   $this->view->pageContentExer      = mb_convert_encoding($pageContentMain['page_section2'],"UTF-8");
     	   
     	    $this->view->pageTitle        = mb_convert_encoding($pageContentMain['page_section1_title'],"UTF-8");
     	   // $this->view->pageContent      = str_replace("\n","<br/>",$pageContent['page_content']);
		   $this->view->pageContent      = mb_convert_encoding($pageContentMain['page_section1'],"UTF-8");
		   
		   $this->view->pageTitleMain        = mb_convert_encoding($pageContentMain['page_title'],"UTF-8");
		   $this->view->pageSubMain          = mb_convert_encoding($pageContentMain['page_content_sub'],"UTF-8");
	    }
    
	/*
	  * created by Lekha
	  * date : 28/5/2012
	  * action for the flexibility page
	  * 
	  */
     function tourflexibilityAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			$this->_helper->layout()->setLayout('layout_tour');
	    	
	        $fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('tourflexibility');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	    $this->view->pageContent      = str_replace("\n","<br/>",$pageContent['page_content']);
	    }
		
		
      /*
	  * created by Lekha
	  * date : 28/5/2012
	  * action for the flexibility page
	  * 
	  */
     function tourexerciseAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			$this->_helper->layout()->setLayout('layout_tour');
	    	
	        $fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('tourexercise');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	    $this->view->pageContent      = str_replace("\n","<br/>",$pageContent['page_content']);
	    }
    
	
	/*
	  * created by Lekha
	  * date : 28/5/2012
	  * action for the flexibility page
	  * 
	  */
     function tourfreedomAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			$this->_helper->layout()->setLayout('layout_tour');
	    	
	        $fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('tourfreedom');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	    $this->view->pageContent      = str_replace("\n","<br/>",$pageContent['page_content']);
	    }
    
    /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the faq page
	  * 
	  */
     function faqAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			
			$this->_helper->layout()->setLayout('layout_faq');
	    	
			$defaultLang      = $this->getDefaultLanguage();
			
	    	$fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('FAQ');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	   // $this->view->pageContent      = stripslashes(str_replace("\n","<br/>",$pageContent['page_content']));
		   $pageContent1      = str_replace("<strong>","<b>",$pageContent['page_content']);
            $this->view->pageContent      = str_replace("</strong>","</b>",$pageContent1);
		   
		   $this->view->defaultLang  = $defaultLang;
	    }
    
	
	  /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the faq page
	  * 
	  */
     function guaranteeAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			
			$this->_helper->layout()->setLayout('layout_guarantee');
			$defaultLang      = $this->getDefaultLanguage();
	    	
	    	$fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('guarantee');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	   // $this->view->pageContent      = stripslashes(str_replace("\n","<br/>",$pageContent['page_content']));
		   $pageContent1      = str_replace("<strong>","<b>",$pageContent['page_content']);
            $this->view->pageContent      = str_replace("</strong>","</b>",$pageContent1);
			
			$this->view->defaultLang  = $defaultLang;
		   
	    }
	
	/*
	  * created by Lekha
	  * date : 26/6/2012
	  * action for the truth about page
	  * 
	  */
     function truthAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
			
			$this->_helper->layout()->setLayout('layout_truth');
	    	
	    	$fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('truth');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = $pageContent['page_title'];
     	  //$this->view->pageContent      = stripslashes(str_replace("\n","<br/>",$pageContent['page_content']));
		 $this->view->pageContent      = $pageContent['page_content'];
		 $this->view->pageSub         = $pageContent['page_content_sub'];
	    }
    
	    
     /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the login page
	  * 
	  */
     function loginAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
	    	
	    	if($this->view->loginStatus == 1)
	    	//$this->view->memberStatus = $this->checkUserMembership();
	    	
	    	$this->view->pageTitle = "";
	    }
    
     /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action for the thank you page after registering
	  * 
	  */
     function thankyouAction()
	    {
	    	$this->view->loginStatus  = $this->isLoggedIn();
	    	if($this->view->loginStatus == 1)
	    	//$this->view->memberStatus = $this->checkUserMembership();
	    	
	    	$this->view->pageTitle = "";
	    }

	    
	    
	    public function logoutAction()
		{
			 Zend_Session::destroy();
			 $this->_redirect('/index/index');
		}

  
    /*
	  * created by Lekha
	  * date : 5/3/2012
	  * method to set the language session on selection
	  * 
	  */
     function setlanguageAction()
     {
     	$this->view->loginStatus  = $this->isLoggedIn();
     	
     	$langcode     = $this->_request->getParam('lang');
		$params       = str_replace(",","/",$this->_request->getParam('params'));
		
		if($this->_request->getParam('page') == 'playvideo')
		{
			$redirectPage = '/'.$this->_request->getParam('contr').'/'.$this->_request->getParam('page')."/".$params;
		} 
     	else
		{
			$redirectPage = '/'.$this->_request->getParam('contr').'/'.$this->_request->getParam('page');
		}
     	
     	 $sess = new Zend_Session_Namespace('UserLanguage');
		  if($sess->isLocked())
		  $sess->unlock();
		  $sess->lang = $langcode;
		  $this->_redirect($redirectPage);
     }
     
     
     
     /**
	 * function that returns the default language
	 * @author lekha
	 * @date 3/22/2012
	 * 
	 */ 
	public function getDefaultLanguage()
	{
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
     
     
      /*
	  * created by Lekha
	  * date : 14/3/2012
	  * action that handles the registration process.user session set here
	  * 
	  */
     public function registeruserAction()
     {
     	$siteurl   = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl();
     	
     	
     	$this->view->loginStatus  = $this->isLoggedIn();
     	$translate = Zend_Registry::get('Zend_Translate');
        
     	//if($this->view->loginStatus == 1)
     	//$this->view->memberStatus = $this->checkUserMembership();
     	
     	$fitnessGeneral     = new FitnessUserGeneral();
     	$fitnessMembership  = new FitnessUserMembership();
     	
     	$userArray       =  array();
     	$usermemberArray = array();
     	
     	
     	if ($this->_request->isPost())    
		{
			$userArray['user_first_name'] = $this->_request->getPost('user_first_name');
			$userArray['user_surname']    = "";
			
			$userArray['user_email']      = $this->_request->getPost('user_email');
			$userArray['user_username']   = $this->_request->getPost('user_username');
			$userArray['user_password']   = md5($this->_request->getPost('user_password'));
			$userArray['user_level']      = $this->_request->getPost('user_level');
			$userArray['user_type']       = 1;
			$userArray['user_status']     = 0;
			
			//check if username exists
			//$checkRecords      =   $fitnessGeneral->checkRecordExists($this->_request->getPost('user_username'));
			$checkRecords           = $fitnessGeneral->checkRecordExists($this->_request->getPost('user_username'));
			$checkRecordsEmail      = $fitnessGeneral->checkEmailExists($this->_request->getPost('user_email'));
			
		/*if(($checkRecords['count'] > 0) || ($checkRecordsEmail['count'] > 0))
			{
				if(($checkRecords['count'] > 0) && ($checkRecordsEmail['count'] == 0))
					{
					  $this->view->errormsg =  $translate->translate("Username exists. Please enter another one.");
					}
					
					if(($checkRecords['count'] == 0) && ($checkRecordsEmail['count'] > 0))
					{
					  $this->view->uname   = $this->_request->getPost('user_username');
					  $this->view->errormsg =  $translate->translate("An account for this email already exists.");
					}
					
					if(($checkRecords['count'] > 0) && ($checkRecordsEmail['count'] > 0))
					{
					  
					  $this->view->errormsg =  $translate->translate("Email/Username exists. Please enter another one.");
					}
					
				$this->view->fname   = $this->_request->getPost('user_first_name');
				$this->view->surname = $this->_request->getPost('user_surname');
				$this->view->dob   = $this->_request->getPost('user_dob');
				$this->view->gender   = $this->_request->getPost('user_gender');
				$this->view->email   = $this->_request->getPost('user_email');
				$this->view->level   = $this->_request->getPost('user_level');
			}*/	
			
		if(($checkRecords['count'] > 0))
			{
				
					
										
				
					  
					  $this->view->errormsg =  $translate->translate("Username exists. Please enter another one.");
					
					
				$this->view->fname   = $this->_request->getPost('user_first_name');
				$this->view->surname = "";
				$this->view->dob   = $this->_request->getPost('user_dob');
				$this->view->gender   = $this->_request->getPost('user_gender');
				$this->view->email   = $this->_request->getPost('user_email');
				$this->view->level   = $this->_request->getPost('user_level');
			}
			
			else 
			{
				$fitnessGeneral->addData($userArray);
				$getLastUserId                = $fitnessGeneral->getLastUserId();
				
				
				
				//record for membership table
				$usermemberArray['user_id'] = $getLastUserId['user_id'];
				$usermemberArray['user_status']    = 1;
				$usermemberArray['trial']          = 0;
				$usermemberArray['trial_period']   = 0;
				$usermemberArray['membership_plan']= '';
				$usermemberArray['membership_validity_date']   = '';
				$usermemberArray['registration_date']   = date('Y-m-d');
				$usermemberArray['upgrade_date']        = '';
				
				$fitnessMembership->addData($usermemberArray);
				
				
				
				if($getLastUserId['user_id'] !="")
				{
					
					 //send confirmation mail
					    $siteUrl            = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl()."/index/confirm/rec/".$this->_request->getPost('user_username')."/conf/".base64_encode($this->_request->getPost('user_password'));
						$logoUrl            = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl()."/public/new/images/logo.jpg";
					 
						 // To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

// Additional headers
$headers .= 'From: Fitness4me<info@fitness4.me>' . "\r\n";
$headers .= 'Reply-To:Fitness4me<info@fitness4.me>'. "\r\n";




						
						$to       = $this->_request->getPost('user_email');
						$from     = "info@fitness4.me";
						
						$subject  = "Welcome to fitness4.me";
						
						$defaultLang     = $this->getDefaultLanguage();	
						
						if($defaultLang == 1)
						{
							$body  = "Dear ".$this->_request->getPost('user_first_name')."" . "<br/><br/>";
							$body .= "Welcome to fitness4.me.<br/>";
							$body .= "Our experts have developed exciting and efficient workouts, with the sole purpose of helping YOU achieve your goals.<br/>";
							$body .= "And in a few minutes you will have access to them on your smartphone, tablet or PC.<br/>
	                         EXERCISE ANYWHERE ANYTIME!<br/><br/>";
	                        $body .= "You are just one step away from getting started.<br/>";
							$body .= "Please click this link and you are ready to go:<br/><br/>";
	                        $body .= "<a href='$siteUrl'>Click here</a><br/>"; 
							$body .= "See you on the web!<br/><br/>";
							$body .= "<img src='$logoUrl' /><br/><br/>";
						}
						else
						{
							$body  = "Hallo ".$this->_request->getPost('user_first_name')."" . "<br/><br/>";
							$body .= "Willkommen bei fitness4.me.<br/>";
							$body .= "Unsere Experten haben aufregende und effektive Workouts entwickelt – mit dem alleinigen Zweck, dich beim Erreichen deiner Ziele zu unterstützen.<br/>";
							$body .= "In wenigen Minuten wirst du darauf zugreifen können - über dein Smartphone, deinen Tablet oder PC.<br/>
	                         DEIN TRAINING – WANN DU WILLST, WO DU WILLST!<br/><br/>";
	                        $body .= "Du bist nur einen Schritt davon entfernt, endlich loszustarten.<br/>";
							$body .= "Bitte klicke auf diesen Link und schon kannst du beginnen:<br/><br/>";
	                        $body .= "<a href='$siteUrl'>Klicke hier</a><br/>"; 
							$body .= "Wir sehen uns im Web!<br/><br/>";
							$body .= "<img src='$logoUrl' /><br/><br/>";
						}
							//$message = mb_convert_encoding($body,"UTF-8");				
						
						mail($to,$subject,$body,$headers);
						
						
						

						
					 
				}
				$this->_redirect('/index/thankyou');
			}
			
		}
     }
     
     
     /*
	  * created by Lekha
	  * date : 11/4/2012
	  * action that handles the confirmation page after user confirms link.
	  * 
	  */
     public function confirmAction()
     {
     	
     	$fitnessGeneral     = new FitnessUserGeneral();
     	$fitnessMembership  = new FitnessUserMembership();
     	
     	
     	$userArray          = array();
     	
     	$username           =  $this->_request->getParam('rec');
     	$password           =  base64_decode($this->_request->getParam('conf'));
     	
     	$userDetails      =  $fitnessGeneral->getUserbyUsername($username);
     	$userMembership   = $fitnessMembership->getUserMembership($userDetails['user_id']);
     	
     	$registrationDate = $userMembership['registration_date'];
     	$diff             = $this->_date_diff(strtotime($registrationDate), time());
     	
     	
     	//check the days since registration , if more  than a month disable the confirm mail with login details
     	if(($diff['days'] < 30) && ($userDetails['user_status'] == 0))
     	{
     	
     		
	     	$userArray['user_status']  = 1;
	     	
	     	
	     	
	     	
	     	$where ="user_username='".$username."'";
		  	       $fitnessGeneral->update($userArray,$where);
		  	       
		  	
		  	
		  	 //send mail with login details
	
		  	 
		  	              
							$logoUrl            = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl()."/public/new/images/logo.jpg";
							$to       = $userDetails['user_email'];
							$from     = "info@fitness4.me";
							$subject  = "Fitness4me Login details";
							
							
			$defaultLang     = $this->getDefaultLanguage();		
			if($defaultLang == 1)
			{
				            $body  = "Dear ".$userDetails['user_first_name']."<br/><br/>";
							$body .= "Thank You for joining Fitness4me.<br/>Please find your login details below:<br><br/>";
							$body .= "Username - ".$userDetails['user_username']."<br/>";
							$body .= "<img src='$logoUrl' />";
			}		   
             else
			 {
			 	            $body  = "Hallo  ".$userDetails['user_first_name']."<br/><br/>";
							$body .= "Vielen Dank, dass du dich für Fitness4.me entschieden hast.<br>";
							$body .= "Nachfolgend kannst du deine Login-Daten einsehen:<br><br/>";
							$body .= "Benutzername - ".$userDetails['user_username']."<br/>";
							$body .= "<img src='$logoUrl' />";
			 }
						    
						
						
						 // To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
							
							// Additional headers
							$headers .= 'From: Fitness4me<info@fitness4.me>' . "\r\n";
							
							mail($to,$subject,$body,$headers);
													
						

						
						
     	}  
     	else 
     	{
     		$this->_redirect('/index/home');
     	}
	  	       
	  	       
     }
     
     
	 
	 
	  /**
	 * function that displays the terms and conditions for the app users
	
	 * @author lekha
	 * @date 9/4/2012
	 * 
	 */
     function termsappAction()
     {
	 
	        $this->_helper->layout()->disableLayout();
     	    $fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('terms-app');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = mb_convert_encoding($pageContent['page_title'],'UTF-8');
     	    $this->view->pageContent      = mb_convert_encoding($pageContent['page_content'],'UTF-8');
     }
	 
     
     
     /**
	 * function that displays the terms and conditions
	
	 * @author lekha
	 * @date 4/24/2012
	 * 
	 */
     function termsAction()
     {
	 
	        $this->_helper->layout()->disableLayout();
     	    $fitnessPages     = new FitnessWebsitePages();
     	    $fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('Terms');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = mb_convert_encoding($pageContent['page_title'],'UTF-8');
     	    $this->view->pageContent      = mb_convert_encoding($pageContent['page_content'],'UTF-8');
     }
    
    /**
	 * function that handles the forgot username/password 
	
	 * @author lekha
	 * @date 4/24/2012
	 * 
	 */
     function forgotloginAction()
     {
     	$fitnessUser        =  new FitnessUserGeneral();
     	$userArray          = array();
     	$translate = Zend_Registry::get('Zend_Translate');
		
		
     	if ($this->_request->isPost())    
		{
			$email       =   $this->_request->getPost('forgot_email');
			
			$checkRecordsEmail      = $fitnessUser->checkEmailExists($email);
			
			if(($checkRecordsEmail['count'] < 1))
			{
				
				$this->view->message =  $translate->translate("Email does not exist in our database.Please use your registered email");
			}
			else
			{
				
			
			$userDetails =   $fitnessUser->getUserbyEmail($email);
				
        				
			$password    =   $this->generatePassword();
			
			$siteUrl     = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl();
			
			$userArray['user_password']  = md5($password);
			
			$where    = " user_email='".$email."'";
			
			$fitnessUser->update($userArray,$where);
			
			$logoUrl            = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl()."/public/new/images/logo.jpg";
			
			$to       = $email;
		    $from     = "fitness@fitness4me.com";
			$subject  = "Fitness4.me Password";
			
			//send a mail with the user password
			//php mailer
			$defaultLang     = $this->getDefaultLanguage();	
			if($defaultLang == 1)		
                 {
				 	    $message  = "Dear ".$userDetails['user_first_name']."<br/><br/>";
						$message .= "We have generated a new password for you<br/>";
						$message .= "Please login using your username and the following password:<br/>";
						
						$message .= "Password - ".$password."<br/><br/>";
						$message .= "You can change it to another one of your choice under settings.<br/>";
						$message .= "Best regards.<br/>";
						$message .= "<img src='$logoUrl' />";
				 }
				 else
				 {
				 	    $message  = "Hallo ".$userDetails['user_first_name']."<br/><br/>";
						$message .= "Wir haben ein neues Passwort für dich generiert .<br/>";
						$message .= "Bitte melde dich an, indem du deinen Benutzernamen und das folgende Passwort angibst:<br/>";
						
						$message .= "Passwort  - ".$password."<br/><br/>";
						$message .= "Du kannst das Passwort in den Einstellungen nach deinen Wünschen verändern.<br/>";
						$message .= "Viele Grüße.<br/>";
						$message .= "<img src='$logoUrl' />";
				 }
						//$body = mb_convert_encoding($message,"UTF-8");
						
						 // To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
							
							// Additional headers
							$headers .= 'From: Fitness4me<info@fitness4.me>' . "\r\n";
							
							mail($to,$subject,$message,$headers);
						
						
							
		$this->view->message    = $translate->translate("Your password has been mailed to the email provided.");	
		}				
		}
     }
     
	 
	/**
	 * function that displays the sitemap of the website
	 * @author lekha
	 * @date 8/16/2012
	 * 
	 */
	 public function sitemapAction()
	 {
	 	$this->_helper->layout()->setLayout('layout_sitemap');
		$this->view->loginStatus  = $this->isLoggedIn(); 
	 }
	 
	 /**
	 * function that displays the sitemap of the website
	 * @author lekha
	 * @date 8/16/2012
	 * 
	 */
	 public function privacyAction()
	 {
	 	$this->_helper->layout()->setLayout('layout_sitemap');
		$this->view->loginStatus  = $this->isLoggedIn();
		
		$fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('privacy');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = mb_convert_encoding($pageContent['page_title'],'UTF-8');
     	    $this->view->pageContent      = mb_convert_encoding($pageContent['page_content'],'UTF-8');
	 }
	 
	 
	  /**
	 * function that displays the terms and conditions
	 * @author lekha
	 * @date 9/5/2012
	 * 
	 */
	 public function termsfullAction()
	 {
	 	$this->_helper->layout()->setLayout('layout_sitemap');
		$this->view->loginStatus  = $this->isLoggedIn();
		
		$fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('Terms');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = mb_convert_encoding($pageContent['page_title'],'UTF-8');
     	    $this->view->pageContent      = mb_convert_encoding($pageContent['page_content'],'UTF-8');
	 }
	 
	 
     /**
	 * function that displays the credits page
	 * @author lekha
	 * @date 9/5/2012
	 * 
	 */
	 public function creditsAction()
	 {
	 	$this->_helper->layout()->setLayout('layout_sitemap');
		$this->view->loginStatus  = $this->isLoggedIn();
		
		$fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	    
     	    
     	    $defaultLang      = $this->getDefaultLanguage();
     	    $pageId           = $fitnessPages->getPageByName('credits');
     	   
     	   
     	    
     	    $pageContent      = $fitnessPagesMulti->getPage($pageId['page_id'],$defaultLang);
     	   
     	    $this->view->pageTitle        = mb_convert_encoding($pageContent['page_title'],'UTF-8');
     	    $this->view->pageContent      = mb_convert_encoding($pageContent['page_content'],'UTF-8');
	 }
	 
	/**
	 * function that displays the news template
	 * @author lekha
	 * @date 8/21/2012
	 * 
	 */
	 public function newsAction()
	 {
	 	$this->_helper->layout()->setLayout('layout_news');
		$this->view->loginStatus  = $this->isLoggedIn(); 
     	    
	    $fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();		
		$fitnessNews            = new FitnessNews();
		$fitnessNewsMulti       =  new FitnessNewsMultilang();
		
		$lang                   =  $this->getDefaultLanguage();
		if($this->_request->getParam('category') != "")
		{
			$category           = $this->_request->getParam('category');
			
			$getNews            = $fitnessNews->getNewsByCategory($category);
			
			foreach($getNews  as $catN)
			{
				$newsCatList[]  =  $fitnessNewsMulti->getNews($catN['id'],$lang);
				
			}
			$this->view->newsList   = $newsCatList;
		}
		else if($this->_request->getParam('date') != "")
		{
			$getNews            = $fitnessNews->getNewsByDate($this->_request->getParam('date'));
			
			foreach($getNews  as $catN)
			{
				$newsCatList[]  =  $fitnessNewsMulti->getNews($catN['id'],$lang);
				
			}
			$this->view->newsList   = $newsCatList;
		}
		else
		{
			$this->view->newsList   =  $fitnessNewsMulti->getAllInterests($lang);
		}
		
		//get the date range()
		
		
		$dateFirst                = date('Y-m-d');
		$dateSecond               = date('Y-m-d',strtotime('-2 month'));
		
		$this->view->dateRange    = $this->get_months($dateSecond,$dateFirst);
		
		//get the news subtext
		$pageId              = $fitnessPages->getPageByName('News');
     	 
     	$pageContent         = $fitnessPagesMulti->getPage($pageId['page_id'],$lang);
		
		$this->view->newsSub = $pageContent['page_content_sub'];
		$this->view->newsSub = $pageContent['page_content_sub'];
		$this->view->defaultLang  = $lang;
		
	 }
   
     /**
	 * function that tracks the user free /trial period
	 * @params user id
	 * @author lekha
	 * @date 3/22/2012
	 * 
	 */
     public function checkUserMembership()
     {
     	
     	$fitnessUser        =  new FitnessUserGeneral();
     	$fitnessMembership  = new FitnessUserMembership();
     	$fitnessUnlocked    = new FitnessUserWorkoutsUnlocked();
     	$fitnessDone        = new FitnessUserWorkoutsDone();
     	
     	
     	
     	$sess             =  new Zend_Session_Namespace('UserSession');
     	$userDetails      = $fitnessUser->getUserbyUsername($sess->username);	
     	$userMembership   = $fitnessMembership->getUserMembership($userDetails['user_id']);
     	
     	//get user registration date from database only if user in trial period
     	
     	if($userMembership['trial'] == 1)
     	{
	     	$registrationDate = $userMembership['registration_date'];
	     	//get the number of days in the current month
	     	if($userMembership['trial_period'] == 1)
	     	{
	     	$monthDays        = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
	     	}
	     	else 
	     	{
	     		$monthdaysFirst  = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
	     		$monthdaysSecond = cal_days_in_month(CAL_GREGORIAN, date('m')+1, date('Y'));
	     		$monthdaysThird  = cal_days_in_month(CAL_GREGORIAN, date('m')+2, date('Y'));
	     		$monthDays       = $monthdaysFirst + $monthdaysSecond + $monthdaysThird;
	     		
	     	}
	     	
	     	
	     	$diff             = $this->_date_diff(strtotime($registrationDate), time());
	     	$purchasedWorkouts = $fitnessUnlocked->getPurchasedWorkouts($userDetails['user_id']);
	     	foreach($purchasedWorkouts as $purchased)
	     	{
	     		$pWorkouts[]   = $purchased['workout_id'];
	     	}
	     	$implodedWorkouts  = implode(",",$pWorkouts);
	     	
	     	if($diff['days'] > $monthDays)
	     	{
	     		//$fitnessUnlocked->setLockStatus($userDetails['user_id'],0,$implodedWorkouts);
	     		//$fitnessDone->setLockStatus($userDetails['user_id'],0);
	     		return 0;
	     	}
	     	else 
	     	{
	     		
	     		return 1;
	     	}
     	}
     	else 
     	{
     		return 1;
     	}
     	
     }
     
	 
	 /**
	 * function that displays the bmi calculator
	
	 * @author lekha
	 * @date 7/4/2012
	 * 
	 */
	 public function bmiAction()
	 {
	 	
	 }
     
	 
	 
	 
	
	   /**
		 * function that gets the date range
		
		 * @author lekha
		 * @date 8/21/2012
		 * 
		 */
		public function get_months($startstring, $endstring)
		{
		$time1  = strtotime($startstring);//absolute date comparison needs to be done here, because PHP doesn't do date comparisons
		$time2  = strtotime($endstring);
		$my1     = date('mY', $time1); //need these to compare dates at 'month' granularity
		$my2    = date('mY', $time2);
		$year1 = date('Y', $time1);
		$year2 = date('Y', $time2);
		$years = range($year1, $year2);
		 
		foreach($years as $year)
		{
		$months[$year] = array();
		while($time1 < $time2)
		{
		if(date('Y',$time1) == $year)
		{
		$months[$year][] = date('M', $time1);
		$time1 = strtotime(date('M Y', $time1).' +1 month');
		}
		else
		{
		break;
		}
		}
		continue;
		}
		 
		return $months;
		}
 

     public function isLoggedIn()
	{
		If (Zend_Session::namespaceIsset('UserSession')) 
		{

          return 1;

        }
        else 
        {
        	return 0;
        }
	}
	
	
	/**
 * function that finds the difference betwen 2 timestamps
 * @params :Accepts two unix timestamps.
 * return the difference in days
 * date 3/27/2012
 * author Lekha
 */
function _date_diff($one, $two)
{
    $invert = false;
    if ($one > $two) {
        list($one, $two) = array($two, $one);
        $invert = true;
    }

    $key = array("y", "m", "d", "h", "i", "s");
    $a = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $one))));
    $b = array_combine($key, array_map("intval", explode(" ", date("Y m d H i s", $two))));

    $result = array();
   
    $result["days"] = intval(abs(($one - $two)/86400));

    if ($invert) {
       $this->_date_normalize($a, $result);
    } else {
        $this->_date_normalize($b, $result);
    }

    return $result;
}



/**
 * function to normalize the result date
 * @params :Accepts the format and result date
 * return the normalized date
 * date 3/27/2012
 * author Lekha
 */
function _date_normalize($base, $result)
{
    $result = $this->_date_range_limit(0, 60, 60, "s", "i", $result);
    $result = $this->_date_range_limit(0, 60, 60, "i", "h", $result);
    $result = $this->_date_range_limit(0, 24, 24, "h", "d", $result);
    $result = $this->_date_range_limit(0, 12, 12, "m", "y", $result);

    $result = $this->_date_range_limit_days($base, $result);

    $result = $this->_date_range_limit(0, 12, 12, "m", "y", $result);

    return $result;
}



function _date_range_limit($start, $end, $adj, $a, $b, $result)
{
    if ($result[$a] < $start) {
        $result[$b] -= intval(($start - $result[$a] - 1) / $adj) + 1;
        $result[$a] += $adj * intval(($start - $result[$a] - 1) / $adj + 1);
    }

    if ($result[$a] >= $end) {
        $result[$b] += intval($result[$a] / $adj);
        $result[$a] -= $adj * intval($result[$a] / $adj);
    }

    return $result;
}



function _date_range_limit_days($base, $result)
{
    $days_in_month_leap = array(31, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $days_in_month = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $this->_date_range_limit(1, 13, 12, "m", "y", $base);

    $year = $base["y"];
    $month = $base["m"];

    if (!$result["invert"]) {
        while ($result["d"] < 0) {
            $month--;
            if ($month < 1) {
                $month += 12;
                $year--;
            }

            $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
            $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

            $result["d"] += $days;
            $result["m"]--;
        }
    } else {
        while ($result["d"] < 0) {
            $leapyear = $year % 400 == 0 || ($year % 100 != 0 && $year % 4 == 0);
            $days = $leapyear ? $days_in_month_leap[$month] : $days_in_month[$month];

            $result["d"] += $days;
            $result["m"]--;

            $month++;
            if ($month > 12) {
                $month -= 12;
                $year++;
            }
        }
    }

    return $result;
}  


/**
 * function to generate a password for user
 
 * return the password generated
 * date 4/24/2012
 * author Lekha
 */
function generatePassword($length=6, $strength=4) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}
}
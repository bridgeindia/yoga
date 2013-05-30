<?php

require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Session.php';
//Zend_Layout::startMvc(array('layoutPath'=>'application/layouts/'));
Zend_Layout::startMvc(array('layoutPath'=>'application/layouts/index'));
class UserController extends Zend_Controller_Action
{

    private $authcode = 'lekha';
	public function indexAction()
	{
		//$this->checkUserMembership();
		$this->view->pageTitle = "Welcome ";
		
		
        $this->view->loginStatus  = $this->isLoggedIn();
        //$this->view->memberStatus = $this->checkUserMembership();
        
        $this->view->bodyCopy = "<p></p>";
	}
 
  
  public function authAction()
  {
    $request 	= $this->getRequest();
    $registry 	= Zend_Registry::getInstance();
	$auth		= Zend_Auth::getInstance(); 
	
	$DB = $registry['DB'];
		
	$authAdapter = new Zend_Auth_Adapter_DbTable($DB);
    $authAdapter->setTableName('fitness_user_general')
                ->setIdentityColumn('user_username')
                ->setCredentialColumn('user_password');    
	
	if(($request->getParam('user_username') !="") && ($request->getParam('user_password') !=""))
	{
		
	
	// Set the input credential values
	$uname = $request->getParam('user_username');
	$paswd = md5($request->getParam('user_password'));
	
    $authAdapter->setIdentity($uname);
    $authAdapter->setCredential($paswd);
    
    $select = $authAdapter->getDbSelect();
    $select->where('user_status = 1');

    // Perform the authentication query, saving the result
    $result = $auth->authenticate($authAdapter);

    if($result->isValid()){
      //print_r($result);	
	  $data = $authAdapter->getResultRowObject(null,'password');
	  
	  $auth->getStorage()->write($data);
	  $sess = new Zend_Session_Namespace('UserSession');
	  if($sess->isLocked())
	  $sess->unlock();
	  $sess->username = $uname;
	  
	  //record login status
	  $fitnessUser     =  new FitnessUserGeneral();
	  $fitnessUser->userLogin($uname);
	  $loginDetails    = $fitnessUser->getLastLogin($uname);
	  $fitnessUser->userLogin($uname);
	   
	  if($loginDetails['user_login'] == 1)
	     $this->_redirect('/user/settings');
	  else 
	     $this->_redirect('/user/listworkouts');
	 
	}else{
	  $this->_redirect('/user/loginform');
	}
	
	}
	else
	{
		$this->_redirect('/user/loginform');
	}
    	
  }
  
  public function loginformAction()
	{
		$this->view->pageTitle = "Please login. ";
        $this->view->bodyCopy = "<p ></p>";
	}
	
	public function logoutAction()
	{
		Zend_Session::destroy();
		 $this->_redirect('/index/index');
	}
	
	public function administrationAction()
	{
		$this->view->loginStatus  = $this->isLoggedIn();
		//$this->view->memberStatus = $this->checkUserMembership();
		
	}
	
	
	/*
	  * created by Lekha
	  * date : 22/8/2012
	  * action to sort a associative array according to a key
	  * 
	  */
	function array_sort_by_column($arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
	$sort_col_id = array();
	$i=0;
    foreach ($arr as $key=> $row) {
        $sort_col[$i]['favs'] = $row[$col];
		
    $i=$i+1;}
    array_multisort($sort_col, $dir, $arr); 

    return $arr;
}
	
	
	/*
	  * created by Lekha
	  * date : 26/4/2012
	  * action that handles the workout list
	  * 
	  */
	public function listworkoutsAction()
	{
		$this->view->loginStatus  = $this->isLoggedIn();
		$this->_helper->layout()->setLayout('layout_workouts');
		
		
		//if($this->view->loginStatus == 1)
		//$this->view->memberStatus = $this->checkUserMembership();
		
		$this->view->pageTitle = "Workouts";
        
        
		if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/index/login');
        }
        
        $defaultLanguage              =  $this->getDefaultLanguage();
        
        $fitnessExercise              =  new FitnessExerciseGeneral();
        $fitnessWorkouts              =  new FitnessWorkouts();
        $fitnessuserunlocked          =  new FitnessUserWorkoutsUnlocked();
        $fitnessUser                  =  new FitnessUserGeneral();
        $fitnessDocuments             =  new FitnessExerciseDocumentsMultilang();
  	    $fitnessRepetition            =  new FitnessExerciseRepetition();
  	    $fitnessMuscles               =  new FitnessBodyAreasMultilang();
  	    $fitnessworkoutRate           =  new FitnessWorkoutRates();
		$fitnessDevices               =  new AppleDevices();
		$fitnessKeys                  =  new FitnessAndroidKey();
		$fitnessFavWorkouts           =  new FitnessUserFavWorkouts();
  	    
  	    
  	    $this->view->listMuscles       = $fitnessMuscles->listMuscles($defaultLanguage);
  	    
  	    $sess           =  new Zend_Session_Namespace('UserSession');
        $userDetails    = $fitnessUser->getUserbyUsername($sess->username);	
		
		$this->view->loginCount   = $userDetails['user_login'];
        
		if($this->_request->getParam('search') != "")
		{
			$this->view->searchKeyword    = $this->_request->getParam('search');
		}
		
		if($this->_request->getParam('page') != "")
		{
			$this->view->page    = $this->_request->getParam('page');
		}
        
		//unlocking free workouts
        if($this->_request->getParam('workoutUnlock') != "")
        {
        	$unlockedArray       =  array();
        	$unlockedArray['user_id']                  = $userDetails['user_id'];
			$unlockedArray['workout_id']               = $this->_request->getParam('workoutUnlock');
			$unlockedArray['workout_purchase_status']  = 'false';
			$unlockedArray['unlocked_date']            = date('Y-m-d');
		
			$unlockedArray['unlocked_status']          = 1;
			$unlockedArray['unlock_location']        = 1;
			
			
            $fitnessuserunlocked->addData($unlockedArray);
			
			//send notification to android and iphones
			$devicetokenDetail   = $fitnessDevices->getDeviceByUser($userDetails['user_id']);
		  
		  $androidKey            =  $fitnessKeys->getKeyByUser($userDetails['user_id']);
		  
		  
		  if($devicetokenDetail['devicetoken'] != "")
		  $this->sendNotification('lekha',"A free workout has been unlocked in fitness4.me",$devicetokenDetail['devicetoken'],'',4);
		  
		  if($androidKey['android_key'] != "")
		  $this->sentandroid($androidKey['android_key'],"A free workout has been unlocked in fitness4.me",4);
			
          $this->_redirect('/user/listworkouts');
        }
  	    
  	    //get the filter post data and relist the workout
  	    
  	    if ($this->_request->isPost())    
		{
			$workoutDataNew   = array();
			$workoutNew       = array();
			
			
			//process the workout list according to the filter selected
			if($this->_request->getPost('user_muscles') !="")
			{
				
			
			$filteramuscles   = rtrim($this->_request->getPost('user_muscles'),",");
			
			$filterArray      = explode(",",$filteramuscles);
			
			$workoutArray     = $fitnessWorkouts->getWorkoutsByMuscles($userDetails['user_workout_level']);
			
			foreach($workoutArray as $workout)
			{
				$filterworkArray = explode(",",$workout['work_filter']);
				
			    $result = array_intersect($filterArray,$filterworkArray);
			   
				//if(!(empty($result)) )
				if(count($result) == count($filterArray))
				{ 
					$workoutIdNew[] = $workout['id'];
				}
			}
			
			foreach($workoutIdNew as $workout)
			{
				$workoutNew[]      = $fitnessWorkouts->getWorkout($workout);
				
			}
			
			if(!(empty($workoutNew)))
			{
			$this->view->workoutData      = $workoutNew;
			}
			else
			{
				$this->view->filtermessage  = "No results found.";
			}
			
			}
			else 
			{
				$this->view->workoutData      = $fitnessWorkouts->listWorkouts($defaultLanguage,$userDetails['user_workout_level']);
			}
			
			//pass the filter data to the view
			$this->view->filteramuscles   = rtrim($this->_request->getPost('user_muscles'),",");
			
		}
		else 
		{
			$this->view->workoutData      = $fitnessWorkouts->listWorkouts($defaultLanguage,$userDetails['user_workout_level']);
		}
  	   
  	    
		      
        
        
       //get the unlocked workouts of the current user
        if($this->isLoggedIn() == 1)
        {
          $sess           =  new Zend_Session_Namespace('UserSession');
          $userDetails    = $fitnessUser->getUserbyUsername($sess->username);	
         
          $unlocked       = $fitnessuserunlocked->getUserWorkoutsUnlocked($userDetails['user_id']);
          
          foreach($unlocked as $workout)
          {
          	$unlockedIds[]  = $workout['workout_id'];
          }
		  
		  //add the free workouts to the unlocked array
		  
		if(count($unlockedIds) > 0) 
		{
			foreach($this->view->workoutData as $unlocked)
		{//print_r($unlocked);
			if($unlocked['status']==1) 
			{
				array_push($unlockedIds,$unlocked['id']);
			}
		
		
		}
		}else
		{
			foreach($this->view->workoutData as $unlocked)
		{//print_r($unlocked);
			if($unlocked['status']==1) 
			{
				$unlockedIds[]  = $unlocked['id'];
			}
		
		
		}
		}  
		
          $this->view->userunlocked = $unlockedIds;
         
        }
        else 
        {
        	$this->view->userunlocked  =  array();
        }
		
				
		
       		
		//get the workouts marked as favourite by the user
		$favWorkouts       = $fitnessFavWorkouts->getFavWorkouts($userDetails['user_id']);
		foreach($favWorkouts as $fav)
          {
          	$favIds[]  = $fav['workout_id'];
          }
		  $this->view->favWorkouts  = $favIds;
		  
		  
		  
		
		  
		  //sort the workouts according to unlocked status
		  
		 if(count($unlockedIds) > 0) 
		 {
		 	 $f=0;
		foreach($this->view->workoutData as $unlocked)
		{//print_r($unlocked);
			if(in_array($unlocked['id'],$unlockedIds)) 
			{
				$this->view->workoutData[$f]['unlocked']  = 1;
			}
			else
			{
				$this->view->workoutData[$f]['unlocked']  = 0;
			}
			
		$f=$f+1;
		}
		 }
		  
		  
		//sort the workout list according to the favourite workouts
		  
		  $c=0;
		foreach($this->view->workoutData as $sorted)
		{
			if(in_array($sorted['id'],$this->view->favWorkouts))
			{
				$this->view->workoutData[$c]['favs']  = 1;
			}
			else
			{
				$this->view->workoutData[$c]['favs']  = 0;
			}
			
		$c=$c+1;
		}
		//call the function to sort associative arrays by a key
        
		if($userDetails['workout_purchase'] != 1)
		$this->view->workoutData =  $this->array_sort_by_column($this->view->workoutData, 'unlocked');
		else
		$this->view->workoutData =  $this->array_sort_by_column($this->view->workoutData, 'favs');
		
        
        $workoutrate                   = $fitnessworkoutRate->getRateByVersion(1);
         
        $this->view->userid            =  $userDetails['user_id'];
        $this->view->userlevel         =  $userDetails['user_workout_level'];
        $this->view->workoutrate       =  $workoutrate['rate_single_workout'];
        $this->view->defaultLang       =  $this->getDefaultLanguage();
		$this->view->fullpurchase       =  $userDetails['workout_purchase'];
		
       
        
        
		
	}
	
	
	 /*
	  * created by Lekha
	  * date : 19/4/2012
	  * action for the payment options page
	  * 
	  */
	public function paymentoptionsAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->view->loginStatus  = $this->isLoggedIn();
		if($this->isLoggedIn() == 0)
     	{
     		$this->_redirect('/index/login');
     	}
		$fitnessworkoutRate           =  new FitnessWorkoutRates();
		$fitnessUserSettings =  new FitnessUserSettings();
		$fitnessUserGeneral         =  new FitnessUserGeneral();
		$sess           =  new Zend_Session_Namespace('UserSession');
		$userDetails    = $fitnessUserGeneral->getUserbyUsername($sess->username);
		$userSettings   = $fitnessUserSettings->getUserSettings($userDetails['user_id']);
		
		if($userSettings['address_check']!=1)
			{
				$this->_redirect('user/checksettings');
			}
        $workoutrate     = $fitnessworkoutRate->getRateByVersion(1);
		$this->view->single  = $workoutrate['rate_single_workout'];
		$this->view->allRate = $workoutrate['rate_total_workout'];
		
		$this->view->workoutID   = $this->_request->getParam('workout_id');
		
	}
	
	/*
	  * created by Lekha
	  * date : 26/4/2012
	  * action terminate a user account
	  * 
	  */
	function terminateaccountAction()
	{
		$this->view->loginStatus  = $this->isLoggedIn();
		$translate = Zend_Registry::get('Zend_Translate');
		
		
		$fitnessUserGeneral       =  new FitnessUserGeneral();
		$this->view->username     = base64_decode($this->_request->getParam('Dbqs'));
		
		$sess           =  new Zend_Session_Namespace('UserSession');
		$userDetails    = $fitnessUserGeneral->getUserbyUsername($sess->username);
		
		
		if ($this->_request->isPost())    
		{
			
		  
		  $terminateReason        = $this->_request->getPost('terminate_reason');
		  $username               = $this->_request->getPost('username');
		  
		  
		  $fitnessUserGeneral->terminateAccount($username);	
		  
		  $message                = "Hello,<br/><br/> The user ".$userDetails['user_first_name']." has terminated his/her account with fitness4.me. Below is the reason as given by the user:";	
		  $message               .= $terminateReason;
		  
		  $from                   = $userDetails['user_email'];
		  $to                     = "lekbin@hotmail.com";
		  $subject                = "fitness4.me - Account Termination";
		  
		  //mail($to,$subject,$message,$headers);
						$mail = new Zend_Mail();

						$mail->setBodyHtml($message);
						$mail->setFrom($from, $userDetails['user_first_name']);
						$mail->addTo($to, "fitness4.me");
						$mail->setSubject($subject);
						$mail->send();
						
						
		//kill session and redirect user
		
		 Zend_Session::destroy();
	     $this->_redirect('/index/index');
		}
		
	}
	
	
	  /*
	  * created by Lekha
	  * date : 15/3/2012
	  * action for the user settings page
	  * 
	  */
     function settingsAction()
     {
     	$this->view->loginStatus  = $this->isLoggedIn();
     	$this->_helper->layout()->setLayout('layout_workouts');
     	//if($this->view->loginStatus == 1)
     	//$this->view->memberStatus = $this->checkUserMembership();
     	
     	
     	$userArray                = array();
     	$usersettingsArray        = array();
     	
     	if($this->isLoggedIn() == 0)
     	{
     		$this->_redirect('/index/login');
     	}
     	
     	$fitnessUser         =  new FitnessUserGeneral();
     	$fitnessTargets      =  new FitnessTargetsMultilang();
     	$fitnessInterests    =  new FitnessInterestsMultilang();
     	$fitnessUserSettings =  new FitnessUserSettings();
		$fitnessCountries    =  new Countries();
		$fitnessDevices     =   new AppleDevices();
		$fitnessKeys        =   new FitnessAndroidKey();
		$fitnessFeatured    =   new FitnessFeaturedWorkout();
     	
     	$defaultLanguage=  $this->getDefaultLanguage();
     	 
     	$sess           =  new Zend_Session_Namespace('UserSession');
     	
     	$this->view->countries      = $fitnessCountries->getAllCountries();
     	$this->view->listTargets    = $fitnessTargets->getAllTargets($defaultLanguage);
     	$this->view->listInterests  = $fitnessInterests->getAllInterests($defaultLanguage);
     	$this->view->userDetails    = $fitnessUser->getUserbyUsername($sess->username);
     	
     	if ($this->_request->isPost())    
		{
		  $userId                       = $this->_request->getPost('user_id');	
		  
		  $userArray['user_first_name'] = $this->_request->getPost('user_fname');
		  $userArray['user_surname']    = $this->_request->getPost('user_surname');
		  $userArray['user_email']      = $this->_request->getPost('user_email');
		  if($this->_request->getPost('user_password') != "")
		  {
		  	$userArray['user_password']   = md5($this->_request->getPost('user_password'));
		  }
		  
		  $levelChnage                  = 1;
		  if($this->view->userDetails['user_workout_level'] != $this->_request->getPost('user_level'))
		  {
		  $devicetokenDetail   = $fitnessDevices->getDeviceByUser($userId);
		  
		  $androidKey          =  $fitnessKeys->getKeyByUser($userId);
		  
		  if($devicetokenDetail['devicetoken'] !="")
		  $this->sendNotification('lekha',"User workout level has been changed in fitness4.me",$devicetokenDetail['devicetoken'],'',$this->_request->getPost('user_level'));
		  
		  if($androidKey['android_key'] !="")
		  $this->sentandroid($androidKey['android_key'],"User workout level has been changed in fitness4.me",$this->_request->getPost('user_level'));
		  
		  $fitnessFeatured->deleteData();
		  }
		  if($levelChnage == 1)
		  {
		  $userArray['user_workout_level']      = $this->_request->getPost('user_level');
		  }
		  $userArray['user_gender']      = $this->_request->getPost('user_gender');
		  $userArray['user_dob']         = $this->_request->getPost('user_dob');
		  $userArray['terms_conditions']         = $this->_request->getPost('terms');
		  
		  $where = " user_id='".$userId."'";
		  $fitnessUser->update($userArray,$where);
		  
		  
		  if($this->_request->getPost('user_targets') != "")
		  {
		  	$usersettingsArray['workout_targets'] = rtrim($this->_request->getPost('user_targets'),",");
		  }
		  if($this->_request->getPost('user_interests') != "")
		  {
		  	$usersettingsArray['workout_interests']= rtrim($this->_request->getPost('user_interests'),",");
		  }
		  $usersettingsArray['member_fitnessclub'] = $this->_request->getPost('club_member');
		  $usersettingsArray['offers']             = $this->_request->getPost('offers');
		  $usersettingsArray['updates']            = $this->_request->getPost('updates');
		  $usersettingsArray['dnb']                = $this->_request->getPost('dnb');
		  $usersettingsArray['address']            = $this->_request->getPost('address');
		  $usersettingsArray['country']            = $this->_request->getPost('country');
		  $usersettingsArray['zipcode']            = $this->_request->getPost('zipcode');
		  $usersettingsArray['telephone']          = $this->_request->getPost('telephone');
		  
		  if(($usersettingsArray['address'] !="") && ($usersettingsArray['country']!="") && ($usersettingsArray['zipcode']!="") && ($userArray['user_first_name']!="")&& ($userArray['user_gender']!="")&& ($userArray['terms_conditions']!=""))
		  {
		  	$usersettingsArray['address_check']  = 1;
		  }
		  
		  
		  //check if record exists
		 $checkRecords  =  $fitnessUserSettings->getCount($userId);
		 
		 if($checkRecords['count'] < 1 )
		 {
		 	$usersettingsArray['user_id']          = $userId;
		 	$fitnessUserSettings->addData($usersettingsArray);
		 }
		 else 
		 {
		 	$where = " user_id='".$userId."'";
		 	$fitnessUserSettings->update($usersettingsArray,$where);
		 }
		 
		  $this->_redirect('/user/listworkouts');
		}
		
		$this->view->userDetails    = $fitnessUser->getUserbyUsername($sess->username);
		$this->view->userSettings   = $fitnessUserSettings->getUserSettings($this->view->userDetails['user_id']);
		$this->view->defaultLang    = $defaultLanguage;
     	
     }
     
    public function checksettingsAction()
	{
		$this->view->loginStatus  = $this->isLoggedIn();
		$this->_helper->layout()->disableLayout();
		if($this->isLoggedIn() == 0)
     	{
     		$this->_redirect('/index/login');
     	}
	}
     
     
     /**
	 * function that displays the workout information before the video
	 * @params workout id
	 * @author lekha
	 * @date 4/25/2012
	 
	 */
  public function playvideoAction()
  {
  	$this->view->loginStatus  = $this->isLoggedIn();
  	//$this->_helper->layout()->setLayout('layout_workouts');
	$this->_helper->layout()->disableLayout();
  	
  	if($this->isLoggedIn() == 0)
     	{
     		$this->_redirect('/index/login');
     	}
  	
  	$this->view->workoutid        = $this->_request->getParam('workout_id');	
  	
  	
  	$fitnessWorkouts              =  new FitnessWorkouts();
  	$fitnessWorkoutsMulti         =  new FitnessWorkoutsMultilang();
  	$fitnessWorkoutsUnlocked      =  new FitnessUserWorkoutsUnlocked();
	$fitnessDocuments             =  new FitnessExerciseDocuments();
	$fitnessRepetition            =  new FitnessExerciseRepetition();
	$fitnessExercises             =  new FitnessExerciseGeneral();
	$fitnessExerciseEquipments    = new FitnessExerciseEquipments();
	$fitnessEquipmentsMulti       = new FitnessEquipmentsMultilang();
	$fitnessDevices               =  new AppleDevices();
    $fitnessKeys                  =  new FitnessAndroidKey();
	$fitnessFavWorkouts           =  new FitnessUserFavWorkouts();
	$fitnessUserSettings          =  new FitnessUserSettings();
	$fitnessUser                  =  new FitnessUserGeneral();
   	
	// get workout info

	$workoutInfo               =   $fitnessWorkouts->getWorkout($this->_request->getParam('workout_id'));
	$workoutMulti              =   $fitnessWorkoutsMulti->getWorks($this->_request->getParam('workout_id'),$this->getDefaultLanguage());
	$user_level                =   $this->_request->getParam('userlevel');
	$userid                    =   $this->_request->getParam('userid');
	
	$userDetails    = $fitnessUser->getUser($userid);	
	
	$userSettings   = $fitnessUserSettings->getUserSettings($userid);
	print_r($userDetails);exit;
	if($userSettings['address_check']!=1)
	{
		$this->_redirect('user/checksettings');
	}
	
	$defaultLang     = $this->getDefaultLanguage();
	$workoutData      = $fitnessWorkouts->listWorkouts($defaultLang,$user_level);
	
	$favWorkouts       = $fitnessFavWorkouts->getFavWorkouts($userid);
		foreach($favWorkouts as $fav)
          {
          	$favIds[]  = $fav['workout_id'];
          }
		 
	
	
	$workout_id         =  $workoutInfo['id'];
	if(in_array($workout_id,$favIds))
	{
		
		$this->view->favstatus   = 1;
	}
	else
	{
		
		$this->view->favstatus   = 0;
	}
	
	//umlock free workouts
	
	if($this->_request->getParam('workoutUnlock') != "")
        {
        	$unlockedArray       =  array();
        	$unlockedArray['user_id']                  = $userid;
			$unlockedArray['workout_id']               = $this->_request->getParam('workoutUnlock');
			$unlockedArray['workout_purchase_status']  = 'false';
			$unlockedArray['unlocked_date']            = date('Y-m-d');
		
			$unlockedArray['unlocked_status']          = 1;
			$unlockedArray['unlock_location']        = 1;
			
			
            $fitnessWorkoutsUnlocked->addData($unlockedArray);
			
			//send notification
			$devicetokenDetail   = $fitnessDevices->getDeviceByUser($userid);
		  
		  $androidKey            =  $fitnessKeys->getKeyByUser($userid);
		  
		  if($devicetokenDetail['devicetoken'] != "")
		  $this->sendNotification('lekha',"A free workout has been unlocked in fitness4.me",$devicetokenDetail['devicetoken'],'',4);
		  
		  if($androidKey['android_key'] != "")
		  $this->sentandroid($androidKey['android_key'],"A free workout has been unlocked in fitness4.me",4);
			
          $this->_redirect('/user/playvideo/workout_id/'.$this->_request->getParam('workoutUnlock').'/userlevel/'.$this->_request->getParam('userlevel').'/userid/'.$userid);
        }
	
	$unlocked       = $fitnessWorkoutsUnlocked->getUserWorkoutsUnlocked($userid);
          
          foreach($unlocked as $workout)
          {
          	$unlockedIds[]  = $workout['workout_id'];
          }
		  
		 if(count($unlockedIds) > 0) 
		{
				foreach($workoutData as $work)
			    {
					if($work['status']==1) 
					{
						array_push($unlockedIds,$work['id']);
					}
			
			
			    }
			}
			else
			{
				foreach($workoutData as $work)
			    {
					if($work['status']==1) 
					{
						$unlockedIds[]  = $work['id'];
					}
			
			
			   }
		    }  
          $this->view->userunlocked = $unlockedIds;
	
	//get props used if any
	$exerciseList      =   $fitnessWorkouts->getExercises($workout_id);
	
	
		$exerArray         =   explode(",",$exerciseList['work_exercises']);
	
	
	
	
	foreach($exerArray as $exer)
	{
		$equipmentsList = $fitnessExerciseEquipments->getEquipment($exer);
		
		$equipmentArray[] = explode(",",$equipmentsList['equipments_home']);
		
		
		
		
	}
	
	 $result = array(); 
     foreach ($equipmentArray as $key => $value) 
	 { 
      if(is_array($value)) { 
      $result = array_merge($result,$value); 
    } 
    else { 
      $result[$key] = $value; 
    } 
	}
	
	$equipmentArray = array_unique($result);
	
	$b=0;
	foreach($equipmentArray as $equip)
		{if($equip!=""){
			
		
			$equipName  =  $fitnessEquipmentsMulti->getEquipmentById($equip,$this->getDefaultLanguage());
			if($equipName['equipment_name'] == "chair")
			{
				$equipName['equipment_name'] = "chair/s maximum no 2";
			}
			$equipments .=   $equipName['equipment_name'].",";
		$b=$b+1;}}
	
	$equipmentsStr      =  rtrim($equipments,",");
	
	$workoutArray       =  $fitnessWorkouts->getWorkout($workout_id);
	
	if($workoutArray['work_exercises_order'] == "")
	{
		$exerciseArray      =  explode(",",rtrim($workoutArray['work_exercises'],','));
	}
	else
	{
		$exerciseArray      =  explode(",",rtrim($workoutArray['work_exercises_order'],','));
	}
	
	
	$totalExerciseCount   = count($exerciseArray);
	
	
	  	
	  	
	  	//build the repetition array for the videos
	  	$m=1;
	  	foreach($exerciseArray as $exercise)
	  	{
	  		$repetition        =  $fitnessRepetition->getRecord($exercise,$user_level);
	  		$video           =   $fitnessDocuments->getRecord($exercise);
	  		if($repetition['repetitions'] > 0)
	  		{
	  			
	  		$repetitionArray[] =  1;
	  		$repetitionArray[] =  1;
	  		$repetitionArray[] =  1;
	  		
			if($this->getDefaultLanguage() == 2)
			{
				$intro_array = explode('.mp4',$video['poster_video']);
				$intro_video = "intro/de/".$intro_array[0]."_de.mp4";
				$stopvideo   = "test/de/stop_exercise_de.mp4";
				$otherSide   = "test/de/otherside_exercise_de.mp4";
				$recovery    = "recovery/de/recovery_".$workoutInfo['work_recovery_time']."_de.mp4";
				$next        = "test/de/next_exercise_de.mp4";
				$completed   = "test/de/completed_exercise_de.mp4";
				
			}
			else
			{
				$intro_video = "intro/".$video['poster_video'];
				$stopvideo   = "test/stop_exercise.mp4";
				$otherSide   = "test/otherside_exercise.mp4";
				$recovery    = "recovery/recovery_".$workoutInfo['work_recovery_time'].".mp4";
				$next        = "test/next_exercise.mp4";
				$completed   = "test/completed_exercise.mp4";
			}
	  		
			$videoArray[]    =   $intro_video;
	  		$videoArray[]    =   $video['workout_video_file']."-".$repetition['repetitions'].".mp4";
	  		
			
	  		$videoArray[]    =   $stopvideo;
	  		
			//insert the recovery here
			
			
			
			
	  				
					$videoexplode = $video['workout_video_file']."-".$repetition['repetitions'];
					
					if(file_exists('./public/videos/'.$videoexplode.'-2.mp4'))
					{
					    $repetitionArray[] =  1;
						$repetitionArray[] =  1;
						$repetitionArray[] =  1;
						//$repetitionArray[] =  1;
						$videoArray[]    =   $otherSide;
						
						$videoArray[]    =   $videoexplode.'-2.mp4';
						$videoArray[]    =   $stopvideo;
						//$videoArray[]    =   "test/next_exercise.mp4";
					}
					//else
					//{   
					    if(($workoutInfo['work_recovery_interval'] == 1) && ($m!=$totalExerciseCount))
							{
							  $repetitionArray[] =  1;
							  $videoArray[]    =   $recovery;
							}
							
							if($workoutInfo['work_recovery_interval'] == 2)
							{
								if(($m%2) == 0)
								{
									$repetitionArray[] =  1;
							        $videoArray[]    =   $recovery;
								}
							}
							
							if($workoutInfo['work_recovery_interval'] == 3)
							{
								if(($m%3) == 0)
								{
									$repetitionArray[] =  1;
							        $videoArray[]    =   $recovery;
								}
							}
					
					    
					//}
					if($m != $totalExerciseCount)
					{
						$repetitionArray[] =  1;
						$videoArray[]    =   $next;
					}
	  				
	  			
	  		}
	  	$m=$m+1;
	  	
		}
		array_push($repetitionArray,1);
		array_push($videoArray,$completed);
		
		
	  	$videoStr  = implode(",",$videoArray);
	  	$repsStr   = implode(",",$repetitionArray);

	
	
  	    
	  		
  	   
	  
	
	
  	$this->view->description  =   $workoutMulti['description_big']; 
	$this->view->name         =   $workoutMulti['work_name'];
  	$this->view->benefits     =   $workoutInfo['work_filter']; 
  	$this->view->image        =   $workoutInfo['work_image_list']; 
  	$this->view->videoStr     =   $videoStr; 
  	$this->view->repStr       =   $repsStr; 
  	$this->view->siteurl      = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl();
  	$this->view->userid       =   $userid;
	$this->view->userlevel       =   $user_level;
  	$this->view->workoutid    =   $workout_id;
	$this->view->props        =   $equipmentsStr;
  	$this->view->defaultLang       = $defaultLang;
	$this->view->fullpurchase       =  $userDetails['workout_purchase'];
	
  }
  
  
  
    /**
	 * function that displays the workout statistics of a user
     * @author lekha
	 * @date 4/9/2012
	 * 
	 */
    public function viewstatsAction()
    {
    	$this->view->loginStatus  = $this->isLoggedIn();
		
		$fitnessWorkouts          =  new FitnessWorkouts();
		$fitnessWorkoutsMulti     =  new FitnessWorkoutsMultilang();
    	$fitnessFeatured          =  new FitnessFeaturedWorkout();
		$fitnessUser              =  new FitnessUserGeneral();
		$fitnessUserSettings =  new FitnessUserSettings();
		$featuredArray            =  array();
    	
    	//if($this->view->loginStatus == 1)
  	    //$this->view->memberStatus = $this->checkUserMembership();
  	  
  	    
  	    $sess           =  new Zend_Session_Namespace('UserSession');
  	    
  	    $userDetails    = $fitnessUser->getUserbyUsername($sess->username);
  	    $user_id        = $userDetails['user_id'];
		
		
        $userSettings   = $fitnessUserSettings->getUserSettings($user_id);
		if($userSettings['address_check']!=1)
			{
				$this->_redirect('user/checksettings');
			}
  	    
  	   $defaultLang     = $this->getDefaultLanguage();
  	    
	  		
  	   $this->view->userid       = $user_id;
	   $this->view->defaultLang       = $defaultLang;
  	   
  	    
  	   
  	    
    }
    
    
    
  
  
  
   /**
	 * function that displays the page with membership plans
     * @author lekha
	 * @date 3/27/2012
	 * 
	 */
	public function membershipAction()
	{
		
		$this->view->loginStatus  = $this->isLoggedIn();
		
		//if($this->view->loginStatus == 1)
		//$this->view->memberStatus = $this->checkUserMembership();
		
		
			if($this->isLoggedIn() == 0)
		     	{
		     		$this->_redirect('/index/login');
		     	}
		
		$fitnessMembership        =  new FitnessMembershipPlans();
		$fitnessMembershipMulti   =  new FitnessMembershipPlansMultilang();
		
		$membershipPlans          = array();
		
		$defaultLanguage          = $this->getDefaultLanguage();
		
		
		$plans                    =  $fitnessMembership->listPlans();
		
		$i = 0;
		foreach($plans as $plan)
		{
			$membershipPlans[$i]['membership_id'] = $plan['membership_id'];
			$membershipPlans[$i]['name'] = $plan['membership_plan'];
			$membershipPlans[$i]['rate'] = $plan['membership_rate'];
			$membershipPlans[$i]['free'] = $plan['membership_offer_period'];
			$membershipPlans[$i]['advance_months'] = $plan['advance_months'];
			
			$description             =  $fitnessMembershipMulti->getPlans($plan['membership_id'],$defaultLanguage);
			
			$membershipPlans[$i]['desc'] = $description['membership_description'];
			
		$i = $i + 1;}
		
		$this->view->plans          =  $membershipPlans;
		
		
	}
  
  
   
     /**
	 * function that handles the paypal payment method
	 * @params amount, userid,workout id
	 * @author lekha
	 * @date 3/22/2012
	 * 
	 */
     public function paymentpaypalAction()
     {
     	$this->view->loginStatus  = $this->isLoggedIn();
     	
     	//if($this->view->loginStatus == 1)
     	//$this->view->memberStatus = $this->checkUserMembership();
     	
     	
     		if($this->isLoggedIn() == 0)
		     	{
		     		$this->_redirect('/index/login');
		     	}
     	
     	$fitnessUser        =  new FitnessUserGeneral();
     	$fitnessWorkout     =  new FitnessWorkouts();
     	$fitnessworkoutRate           =  new FitnessWorkoutRates();
		$fitnessPromotion     = new FitnessPromotionCodes();
		$promotionUsers       = new FitnessPromotionUsers();
     	
     	$sess           =  new Zend_Session_Namespace('UserSession');
     	$userDetails    = $fitnessUser->getUserbyUsername($sess->username);	
     	$workout_id     = $this->_request->getParam('workout_id');
     	$purchaseType   = $this->_request->getParam('type');
     	
     	//get workout rate
     	$workoutDetails = $fitnessWorkout->getWorkout($workout_id);
     	$workoutrate     = $fitnessworkoutRate->getRateByVersion(1);
     	
     	$this->view->loginStatus  = $this->isLoggedIn();
     	$this->view->siteurl      = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl();
     	$this->view->user_id      = $userDetails['user_id'];
     	$this->view->workout_id   = $workout_id;
     	$this->view->purchaseType = $purchaseType;
     	
		
		
		if($this->_request->getPost('promotion_code') !="")
		{
			//check if user has used this promotion code
			
			//check if promotion code is active
			
			$checkStatus  =  $fitnessPromotion->checkStatus($this->_request->getPost('promotion_code'));
			
			if($checkStatus['status'] == 1)
			{
				
			
			
			$checkUser    =  $promotionUsers->checkUser($userDetails['user_id'],$this->_request->getPost('promotion_code'));
			if($checkUser['count'] < 1)
			{
				//get the reduction in price for this promotion
				
				$promoDetails    = $fitnessPromotion->getpromoByCode($this->_request->getPost('promotion_code'));
				
				//insert user in promo table
				$promouserArray     = array();
				
				$promouserArray['promotion_id']   = $promoDetails['id'];
				$promouserArray['promotion_code']   = $promoDetails['promotion_code'];
				$promouserArray['user_id']   = $userDetails['user_id'];
				$promotionUsers->addData($promouserArray);
				
				$this->view->reduction   =  $promoDetails['price_reduction'];
				$this->view->message   =  "Your discount has been applied.";
			}
			else
			{
				$this->view->message   =  "You have used a promotion code previously.";
			}
			
			}
			else
			{
				$this->view->message   =  "This promotion code is not active anymore.";
			}
		}
     	if($purchaseType == 1)
     	{
     	$this->view->workout_rate = $workoutrate['rate_single_workout'];
     	}else 
     	{
     		$this->view->workout_rate = $workoutrate['rate_total_workout'];
     	}
     	
     	$this->view->lang             = $this->getDefaultLanguage();
		
     }
     
     
     
      /**
	 * function that handles the recurring paypal payment method
	 * @params amount,advance period,free months,plan id
	 * @author lekha
	 * @date 3/27/2012
	 * 
	 */
      public function recurringpaymentAction()
      {
      	$this->view->loginStatus  = $this->isLoggedIn();
      	
      	//if($this->view->loginStatus == 1)
     	//$this->view->memberStatus = $this->checkUserMembership();
     	
     		if($this->isLoggedIn() == 0)
	     	{
	     		$this->_redirect('/index/login');
	     	}
     	
     	$fitnessUser        =  new FitnessUserGeneral();
     	$fitnessMembership  =  new FitnessMembershipPlans();
     	
     	$sess           =  new Zend_Session_Namespace('UserSession');
     	$userDetails    = $fitnessUser->getUserbyUsername($sess->username);	
     	
     	$this->view->user_id     = $userDetails['user_id'];
     	$this->view->plan        = $this->_request->getParam('plan');
     	
     	$amount                  = $fitnessMembership->getPlans($this->view->plan);
     	$this->view->siteurl      = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl();
     	$this->view->recurring   = $amount['membership_rate'];
     	$this->view->freemonths  = $this->_request->getParam('free');
     	$this->view->advperiod   = $this->_request->getParam('period');
     	
     	
      }
     
     
     /**
	 * function that displays the page after payment
     * @author lekha
	 * @date 3/26/2012
	 * 
	 */
     public function confirmpaymentAction()
     {
     	$this->view->loginStatus  = $this->isLoggedIn();
     	
     	//if($this->view->loginStatus == 1)
     	//$this->view->memberStatus = $this->checkUserMembership();
     	
     	$fitnessUser        =  new FitnessUserGeneral();
     	$fitnessPayment     =  new FitnessWebsitePaymentDetails();
     	
     	$sess           =  new Zend_Session_Namespace('UserSession');
     	$userDetails    =  $fitnessUser->getUserbyUsername($sess->username);	
     
     	//check payment status of user
     	
     	$checkRecord    =  $fitnessPayment->checkPaymentStatus($userDetails['user_id'],date('Y-m-d'));
     		
     	
     	
     	$this->view->paymentStatus = $checkRecord['payment_status'];
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
	     	if($diff['days'] > $monthDays)
	     	{
	     		//$fitnessUnlocked->setLockStatus($userDetails['user_id'],0);
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
	 * function that checks if a user session is set
	 * @author lekha
	 * @date 3/22/2012
	 * 
	 */ 
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


function sendNotification($auth,$message,$devicetoken,$sender="",$level)
        {
        	

        	$fitnessDevices        =   new AppleDevices();
        	$fitnessMessages       =   new DeviceMessages();
        	$siteUrl     = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] .$this->view->baseUrl();
        	if($this->checkAuth($auth))
        	{
				        	
				         
				            
				            // Put your private key's passphrase here:
				            $passphrase = 'Fitness4Me';//'pushchat';
				        
				            $ctx = stream_context_create();
							stream_context_set_option($ctx, 'ssl', 'local_cert','./ck.pem');
							stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
				
							// Open a connection to the APNS server
							$fp = stream_socket_client(
								'ssl://gateway.sandbox.push.apple.com:2195', $err,
								$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				
							if (!$fp)
								exit("Failed to connect: $err $errstr" . PHP_EOL);
							
							//echo 'Connected to APNS' . PHP_EOL;
							
							
							
							// Create the payload body
							$body['aps'] = array(
								'alert' => $message,
								'sound' => 'default.aiff',
								'badge' => $level
								);
							
							// Encode the payload as JSON
							$payload = json_encode($body);
							
							// Build the binary notification
							$msg = chr(0) . pack('n', 32) . pack('H*', $devicetoken) . pack('n', strlen($payload)) . $payload;
				
						// Send it to the server
						$result = fwrite($fp, $msg, strlen($msg));
						
						
						$messageArray    = array();
						
							if (!$result)
							{
								$messageStatus =  array('response'=>'Error in processing');
								
								
								
								
							}
							else
							{
								$messageStatus = array('response'=>'Successfully processed');
								
								
								
								
								
						
						
							}
				
						// Close the connection to the server
						fclose($fp);
						echo json_encode($messageStatus);
						$message = array();
        	}
        }
		
		
		function checkAuth($auth)
		    {
		    	if($auth!=$this->authcode)
		    	{
		    		$error = array('error'=>'User not authenticated to use this service');
		    		
		    		
		    		return false;
		    	}
		    	else 
		    	{
		    		return true;
		    	}
		    }
			
			public function sentandroid($key,$messageText,$level)
	{
		
		
		$url = "https://www.google.com/accounts/ClientLogin";
$accountType = 'GOOGLE'; //Doesn't change for this
$email = 'lekbin@gmail.com'; //Enter your Google Account email
$password = 'hateoranges';  //Enter your Google Account password
$registrationId = $key;
$source = 'companyName-ApplicationName-VersionCode'; //Enter a name for this source of the login
$service = 'ac2dm'; //Select which service you want to log into

//Once that is all done it’s time to use some cURL to send our request and retrieve the auth token:
$ch = curl_init();
$URL = $url."?accountType=".$accountType."&Email=".$email."&Passwd=".$password."&source=".$source."&service=".$service;


// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

//Divide the response into an array as to find the auth token
$line = explode("\n", $response);


// close cURL resource, and free up system resources
curl_close($ch);
unset($ch);
unset($response);

$auth_token = str_replace("Auth=", "", $line[2]); //auth token from Google Account Sign In

$messageUrl = "https://android.apis.google.com/c2dm/send";
$collapseKey = "storedmessages";
$data = array('data.message'=>$messageText,'data.badge'=>$level); //The content of the message

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $messageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$header = array("Authorization: GoogleLogin auth=".$auth_token); //Set the header with the Google Auth Token
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

$postFields = array("registration_id" => $registrationId, "collapse_key" => $collapseKey);
$postData = array_merge($postFields, $data);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$response = curl_exec($ch);
print_r($response);
//Print response from C2DM service//


// close cURL resource, and free up system resources
curl_close($ch);


	}
	
  
}
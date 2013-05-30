<?php
/* Created By : Lekha
 * On : 24th Feb 2012
 * Controller Class for admin
*/

require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Session.php';
Zend_Layout::startMvc(array('layoutPath'=>'application/layouts/admin/'));

class AdminController extends Zend_Controller_Action
{

     private $authcode = 'lekha';
	
	
	/*
	* created by Lekha
    * date : 23/2/2012
	* index method. Loads the login page for admin users
	*/
   function indexAction()
    {  
    	$this->view->pageTitle = "Administrator Sign In ";
        $this->view->bodyCopy = "<p >Please Login.</p>";

        //check if logged in and redirect to homepage
        if($this->isLoggedIn() ==1) 
        {
        	$this->_redirect('/admin/homeuser');
        }
               
    }
    
    /*
    * created by Lekha
    * date : 23/2/2012
	* auth method. 
	* checks the user authentication and redirects accordingly
	*/
      public function authAction()
       {
		    $request 	= $this->getRequest();
		    $registry 	= Zend_Registry::getInstance();
			$auth		= Zend_Auth::getInstance(); 
			
			$DB = $registry['DB'];
				
			$authAdapter = new Zend_Auth_Adapter_DbTable($DB);
		    $authAdapter->setTableName('fitness_admin_accounts')
		                ->setIdentityColumn('admin_username')
		                ->setCredentialColumn('admin_password');    
			
			// Set the input credential values
			$uname = $request->getParam('user_username');
			$paswd = $request->getParam('user_password');
			
		    $authAdapter->setIdentity($uname);
		    $authAdapter->setCredential(md5($paswd));
		
		    // Perform the authentication query, saving the result
		    $result = $auth->authenticate($authAdapter);
		   
		    if($result->isValid()){
		     
			  $data = $authAdapter->getResultRowObject(null,'password');
			 
			  $auth->getStorage()->write($data);
			  $sess = new Zend_Session_Namespace('AdminSession');
			  if($sess->isLocked())
			  $sess->unlock();
			  $sess->username = $uname;
			 
			  	$this->_redirect('/admin/homeuser');
			 
			}else{
			  $this->_redirect('/admin/index');
			}
    	
  }
  
  
    /*
    * created by Lekha
    * date : 23/2/2012
	* home page method. 
	* method for the user homepage
	*/
  function homeuserAction()
  {
  	 if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  	$this->view->pageTitle = "Welcome to the fitness  dashboard";
  	$this->view->loginStatus  = $this->isLoggedIn();
    
  }
  
  
   /*
    * created by Lekha
    * date : 23/4/2012
	* method to view the workouts an exercise has been added to 
	* 
	*/
   function viewworkoutsAction()
   {
   	  if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        
         $exerciseId            = $this->_request->getParam("exercise");
        
	     $fitnessWorkouts   =  new FitnessWorkouts();
	     
	     $this->view->workouts  = $fitnessWorkouts->getWorkoutsByExercise($exerciseId);
       
     
        
   }
  
  
  
  
  
   /*
    * created by Lekha
    * date : 23/2/2012
	* method to add workouts. 
	* 
	*/
  function addworkoutAction()
  {
  	
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
     
  	$this->view->pageTitle    = "Add workouts";
  	
  	$this->view->loginStatus  = $this->isLoggedIn();
  	
  	
  	
    $musclesData    =  new FitnessBodyAreas();
    $equipmentData  =  new FitnessEquipmentsMultilang();
    $timeframesData =  new FitnessWorkoutTimeframes();
    $exercisesData  =  new FitnessWorkouts();
    $adminData      =  new FitnessAdminAccounts();
    
    $sess           =  new Zend_Session_Namespace('AdminSession');
    $adminType      = $adminData->getTypeByUsername($sess->username);	
   
    
    
    $this->view->musclesList    =  $musclesData->listMuscles();
    $this->view->equipmentList  =  $equipmentData->listEquipments();
    $this->view->timeframesList =  $timeframesData->listTimeframes();
    $this->view->exerciseList   =  $exercisesData->listWorkouts();
    $this->view->adminType                         =  $adminType['admin_user_type'];
   
    
    $equipmentCount             =   count($equipmentData->listEquipments());
    //get the post data
    
    if ($this->_request->isPost())    
		{
			$fitnessGeneralData  = array();
			
			//get all the post data for the table fitness exercise general
			//$fitnessGeneralData['workout_exercises']  = rtrim($this->_request->getPost("exerciseslist",","));
			$fitnessGeneralData['timeframe']          = $this->_request->getPost("timeframes");
			$fitnessGeneralData['otherside']          = $this->_request->getPost("otherside");
			$fitnessGeneralData['primary_muscle']     = $this->_request->getPost("primary_muscle");
			$fitnessGeneralData['secondary_muscle']   = rtrim($this->_request->getPost("sec_muscles"),",");
			$fitnessGeneralData['paystatus']          = $this->_request->getPost("workout_pay_status");
			$fitnessGeneralData['workoutRate']        = $this->_request->getPost("workout_rate");
			$fitnessGeneralData['workoutLevel']       = implode(",",$this->_request->getPost("workout_level"));
			$fitnessGeneralData['translator_check']   = $this->_request->getPost("trans_check");
			$fitnessGeneralData['master_check']       = $this->_request->getPost("masterStatus");
			
			//insert data into the  fitness exercise general table
			$fitnessGeneral =  new FitnessExerciseGeneral();
			$workoutId      =  $fitnessGeneral->addData($fitnessGeneralData);
			$workoutId      =  $fitnessGeneral->getLastWorkoutId();
			$lastWorkoutID  =  $workoutId['workout_id'];
			
			//get all post data for the table fitness_exercise_general_multilang
			
			$fitnessGeneralMultilangData  = array();
			$fitnessGeneralMultilangData['workout_id']            = $lastWorkoutID;
			$fitnessGeneralMultilangData['otherside']             = $this->_request->getPost("otherside");
			$fitnessGeneralMultilangData['lang_id']               = $this->getDefaultLanguage();;
			$fitnessGeneralMultilangData['workout_name']          = $this->_request->getPost("workout_name");
			$fitnessGeneralMultilangData['workout_preparation']   = $this->_request->getPost("workout_preparation");
			$fitnessGeneralMultilangData['workout_execution']     = $this->_request->getPost("workout_execution");
			$fitnessGeneralMultilangData['workout_advice']        = $this->_request->getPost("workout_advice");
			
			//insert data into the  fitness_exercise_general_multilang table
			$fitnessGeneralMultiLang =  new FitnessExerciseGeneralMultilang();
			
			
			$fitnessGeneralMultiLang->addData($fitnessGeneralMultilangData);
			
			
			
			//get all the post data for the table fitness_exercise_equipments
			$fitnessEquipmentsData  = array();
			$fitnessEquipmentsData['workout_id']            = $lastWorkoutID;
			
			$fitnessEquipmentsData['equipment_required']    = $this->_request->getPost("equipment_required");
			$fitnessEquipmentsData['equipments_home']       = rtrim($this->_request->getPost("home_equipments"),",");
			$fitnessEquipmentsData['equipments_office']     = rtrim($this->_request->getPost("office_equipments"),",");
			$fitnessEquipmentsData['equipments_nature']     = rtrim($this->_request->getPost("nature_equipments"),",");
			$fitnessEquipmentsData['equipments_hotel']      = rtrim($this->_request->getPost("hotel_equipments"),",");
			
			$fitnessEquipments =  new FitnessExerciseEquipments();
			$fitnessEquipments->addData($fitnessEquipmentsData);
			
			//get all the post data for the table fitness_exercise_documents
			$fitnessDocumentsData  = array();
			$fitnessDocumentsData['workout_id']            = $lastWorkoutID;
			$fitnessDocumentsData['photo_start']    = "";
			$fitnessDocumentsData['photo_end']    = "";
			$fitnessDocumentsData['video_file']    = $this->_request->getPost("video_file");
			$fitnessDocumentsData['poster_video']  = $this->_request->getPost("poster_video");
			
			
			$fitnessDocuments =  new FitnessExerciseDocuments();
			$documentId = $fitnessDocuments->addData($fitnessDocumentsData);
			
			//get all the post data for the table FitnessExerciseDocumentsMultilang
			
			$fitnessDocumentsMultilangData  = array();
			$fitnessDocumentsMultilangData['workout_id']    = $lastWorkoutID;
			$fitnessDocumentsMultilangData['lang_id']       = $this->getDefaultLanguage();
			$fitnessDocumentsMultilangData['document_id']   = $documentId;
			$fitnessDocumentsMultilangData['youtube_link']  = $this->_request->getPost("youtube_link");
			$fitnessDocumentsMultilangData['sound_file']    = $this->_request->getPost("sound_file");
			//$fitnessDocumentsMultilangData['video_file']    = $this->_request->getPost("video_file");
			//$fitnessDocumentsMultilangData['poster_video']  = $this->_request->getPost("poster_video");
			
			
			$fitnessDocumentsMultilang =  new FitnessExerciseDocumentsMultilang();
			$fitnessDocumentsMultilang->addData($fitnessDocumentsMultilangData);
			
			
			//get all the post data for the table fitness_exercise_ranking
			
			
			$fitnessRankingData  = array();
			$fitnessRanking =  new FitnessExerciseRanking();
			$fitnessRankingData['workout_id']    = $lastWorkoutID;
			foreach($this->view->musclesList as $muscles)
			{
				$fitnessRankingData['body_area_id']  = $muscles['area_id'];
				$fitnessRankingData['ranking']       = $this->_request->getPost("ranking_".$muscles['area_id']);
				$fitnessRanking->addData($fitnessRankingData);
			}

			
			
			//get all the post data for the table fitness_exercise_repetition
			
			
			$fitnessRepetitionData  = array();
			$fitnessRepetition =  new FitnessExerciseRepetition();
			$fitnessRepetitionData['workout_id']    = $lastWorkoutID;
			
			if($this->_request->getPost("repetition_beginners") !="")
			{
			$fitnessRepetitionData['exercise_level_id']    = 1;
			$fitnessRepetitionData['repetitions']          = $this->_request->getPost("repetition_beginners");
			
			$fitnessRepetition->addData($fitnessRepetitionData);
			}
			if($this->_request->getPost("repetition_advanced") !="")
			{
			$fitnessRepetitionData['exercise_level_id']    = 2;
			$fitnessRepetitionData['repetitions']          = $this->_request->getPost("repetition_advanced");
			$fitnessRepetition->addData($fitnessRepetitionData);
			}
			if($this->_request->getPost("repetition_professional") !="")
			{
			$fitnessRepetitionData['exercise_level_id']    = 3;
			$fitnessRepetitionData['repetitions']          = $this->_request->getPost("repetition_professional");
			$fitnessRepetition->addData($fitnessRepetitionData);
			}
			
			
			
			
		    $this->_redirect('/admin/listworkout');
			
		}
  }
  
  
   /*
    * created by Lekha
    * date : 27/2/2012
	* method for deleting records 
	* @param workout id
	*/
  function workouteditAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  	
  	if($delete_id != "")
  	{
  		$fitnessGeneral  =  new FitnessExerciseGeneral();
  		
  		
        $fitnessGeneral->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listworkout');
        
        
  	}
  }
  
  
  
  
  
      
 /*
  * created by Lekha
  * date : 13/3/2012
  * method to set the locked status of a workout
  * @param : workout id,lock status
  */
     public function setLockStatusAction()
     {
     	
     	$fitnessworkout   =  new FitnessWorkouts();
     	$status                      =   $this->_request->getParam('status');
     	$workId                      =   $this->_request->getParam('workid');
     	
     	$fitnessworkout->setLockStatus($status,$workId);
     	$this->_redirect('/admin/listworks'); 
     	 
     	 
     }
  
  
     /*
    * created by Lekha
    * date : 23/4/2012
	* method for deleting a workout rate
	* @param rate id
	*/
  function removerateAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		$fitnessworkoutRate  =  new FitnessWorkoutRates();
  		
  		
        $fitnessworkoutRate->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listworkoutrates');
        
        
  	}
  }
     
     
     
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for deleting membership plans 
	* @param plan id
	*/
  function removeplanAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		$fitnessPlan  =  new FitnessMembershipPlans();
  		
  		
        $fitnessPlan->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listplans');
        
        
  	}
  }
  
  
  public function exerciseimageAction()
  {
  	/* read the source image */
	
	  //main image upload
	  $this->view->exerciseID   = $this->_request->getParam('exercise');
	  
	  if ($this->_request->isPost()) 
	{
	 $this->view->exerciseID = $this->_request->getParam('exercise');
	 
		if ($_FILES['image']['name']!="") 
		{ 
		
		   $this->smart_resize_image($_FILES['image']['tmp_name'],
                              $width              = 100, 
                              $height             = 100, 
                              $proportional       = false, 
                              $output             = 'return', 
                              $delete_original    = true, 
                              $use_linux_commands = false );
	        /*$thumbnail_width	= 100;
			$thumbnail_height	= 100;
			$filename = basename($_FILES['image']['name']);
	        $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
			$thumb_preword		= "resize_".$this->_request->getPost('exercise');
			$upload_dir = "./public/images/exercises/single/".$thumb_preword.".".$file_ext;
		
			$arr_image_details	= getImageSize($_FILES['image']['tmp_name']);
			$original_width		= $arr_image_details[0];
			$original_height	= $arr_image_details[1];
		
			
			$scaling_factor = ($original_width / $thumbnail_width);
			$new_width = $thumbnail_width;
			$new_height = ($original_height / $scaling_factor);
					
			$dest_x = intval(($thumbnail_width - $new_width) / 2);
			$dest_y = intval(($thumbnail_height - $new_height) / 2);
		
		
		
			if($arr_image_details[2]==1) { $imgt = "ImageGIF"; $imgcreatefrom = "ImageCreateFromGIF";  }
			if($arr_image_details[2]==2) { $imgt = "ImageJPEG"; $imgcreatefrom = "ImageCreateFromJPEG";  }
			if($arr_image_details[2]==3) { $imgt = "ImagePNG"; $imgcreatefrom = "imagecreatefrompng";  }
		
		
			if( $imgt ) { 
				$old_image	= $imgcreatefrom($_FILES['image']['tmp_name']);
				$new_image	= imagecreatetruecolor($new_width, $new_height);
				$white = imagecolorallocate($new_image, 255, 255, 255);
                imagefill($new_image, 0, 0, $white);
				
				imageCopyResized($new_image,$old_image,$dest_x, 		
				$dest_y,0,0,$new_width,$new_height,$original_width,$original_height);
				$imgt($new_image,$upload_dir);
			}*/

		
	  }
	  }
  }
  
  
  /**
  * created by Lekha
  * date : 16/10/2012
  *  method for uploading images
  * 
  **/
  
  function cropimageAction()
  {
  	$this->_helper->layout()->disableLayout();
	$exerciseGeneral   = new FitnessExerciseGeneral();
	
	$this->view->exerciseId   = $this->_request->getParam('exercise');
	
	$upload_dir = "./public/images/exercises/single"; 				// The directory for the images to be saved in
	$upload_path = $upload_dir."/";				// The path to where the image will be saved
	$large_image_prefix = "resize_".$this->_request->getParam('exercise'); 			// The prefix name to large image
	$thumb_image_prefix = "thumbnail_".$this->_request->getParam('exercise');			// The prefix name to the thumb image
	$large_image_name = $large_image_prefix;     // New name of the large image (append the timestamp to the filename)
	$thumb_image_name = $thumb_image_prefix;     // New name of the thumbnail image (append the timestamp to the filename)
	$max_file = "3"; 							// Maximum file size in MB
	$max_width = "150";	
	$max_width2 = "90";						// Max width allowed for the large image
	$thumb_width = "90";						// Width of thumbnail image
	$thumb_height = "100";	
	
	//Image Locations
	$large_image_location = $upload_path.$large_image_name;
	$thumb_image_location = $upload_path.$thumb_image_name;	
	
	// Only one of these image types should be allowed for upload
	$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
	$allowed_image_ext = array_unique($allowed_image_types); // do not change this
	$image_ext = "";	// initialise variable, do not change this.
	foreach ($allowed_image_ext as $mime_type => $ext) {
	    $image_ext.= strtoupper($ext)." ";
	}



				// Height of thumbnail image
	
	if ($this->_request->isPost()) 
	{
	    //main image upload
		if ($_FILES['image']['name']!="") 
		{ 
		   
	       //Get the file information
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	$this->view->exerciseId   = $this->_request->getPost('exerciseId');
	
	//update the filesnames with the exercise Id
	
	$large_image_prefix = "resize_".$this->_request->getPost('exerciseId'); 			// The prefix name to large image
	$thumb_image_prefix = "thumbnail_".$this->_request->getPost('exerciseId');			// The prefix name to the thumb image
	$large_image_name = $large_image_prefix;     // New name of the large image (append the timestamp to the filename)
	$thumb_image_name = $thumb_image_prefix;
	//Image Locations
	$large_image_location = $upload_path.$large_image_name;
	$thumb_image_location = $upload_path.$thumb_image_name;	
	
			//Only process if the file is a JPG, PNG or GIF and below the allowed limit
			if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
				
				foreach ($allowed_image_types as $mime_type => $ext) {
					//loop through the specified image types and if they match the extension then break out
					//everything is ok so go and check file size
					if($file_ext==$ext && $userfile_type==$mime_type){
						$error = "";
						break;
					}else{
						$error = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
					}
				}
				//check if the file size is above the allowed limit
				if ($userfile_size > ($max_file*1048576)) {
					$error.= "Images must be under ".$max_file."MB in size";
				}
				
			}else{
				$error= "Select an image for upload";
			}
			
	//Everything is ok, so we can upload the image.
	if (strlen($error)==0){
		
		if (($_FILES['image']['name']!="")){
						//this file could now has an unknown file extension (we hope it's one of the ones set above!)
						$large_image_location = $large_image_location.".".$file_ext;
						$thumb_image_location = $thumb_image_location.".".$file_ext;
						
						//put the file ext in the session so we know what file to look for once its uploaded
						
						
						move_uploaded_file($userfile_tmp, $large_image_location);
						chmod($large_image_location, 0777);
						
						copy($large_image_location, $thumb_image_location);
						chmod($thumb_image_location, 0777);
						
						$width = $this->getWidth($large_image_location);
						$height = $this->getHeight($large_image_location);
						
						//Scale the image if it is greater than the width set above
						if ($width > $max_width2){
							$scale = $max_width2/$width;
							$uploaded = $this->resizeImage($thumb_image_location,$width,$height,$scale);
						}else{
							$scale = 1;
							$uploaded = $this->resizeImage($thumb_image_location,$width,$height,$scale);
						}
						//Scale the image if it is greater than the width set above
						if ($width > $max_width){
							$scale = $max_width/$width;
							$uploaded = $this->resizeImage($large_image_location,$width,$height,$scale);
						}else{
							$scale = 1;
							$uploaded = $this->resizeImage($large_image_location,$width,$height,$scale);
						}
						
						
						
						
						
						
		}
		if (file_exists($large_image_location))
			 {
			 	$this->view->largeImage   = "yes";
				$this->view->largeFile          = $large_image_name.".".$file_ext;
				$this->view->largewidth = $this->getWidth($large_image_location);
				$this->view->largeheight = $this->getHeight($large_image_location);
				$this->view->ext         = $file_ext;
				
			 }
			 if (file_exists($thumb_image_location))
			 {
			 	$this->view->thumbImage   = "yes";
				$this->view->thumbFile          = $thumb_image_name.".".$file_ext;
			 }
	  }
	}
	
		
	
	//add the image to exercise database
	
	                    //add the image to exercise database
						$imageArray = array();
						
						$imageArray['workout_image']  = $large_image_name.".".$file_ext;
						$where ="workout_id='".$this->_request->getPost('exerciseId')."'";
						$exerciseGeneral->update($imageArray,$where);
						
						$imagethumbArray = array();
						
						$imagethumbArray['workout_imagethumb']  = $thumb_image_name.".".$file_ext;
						$where ="workout_id='".$this->_request->getPost('exerciseId')."'";
						$exerciseGeneral->update($imagethumbArray,$where);
	 }
	 
	 
	 
			 
	 
	}
	
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for deleting interests 
	* @param interest id
	*/
  function removetargetAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		$fitnessTarget  =  new FitnessTargets();
  		
  		
        $fitnessTarget->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listtargets');
        
        
  	}
  }
  
  
  
   /*
    * created by Lekha
    * date : 25/4/2012
	* method for deleting website pages 
	* @param page id
	*/
  function removepageAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		$fitnessWebsitePages  =  new FitnessWebsitePages();
  		
  		
        $fitnessWebsitePages->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listpages');
        
        
  	}
  }
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for deleting interests 
	* @param interest id
	*/
  function removeinterestAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		$fitnessInterest  =  new FitnessInterests();
  		
  		
        $fitnessInterest->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listinterests');
        
        
  	}
  }
  
  
  /*
    * created by Lekha
    * date : 29/2/2012
	* method for deleting equipments 
	* @param equipment id
	*/
  function removeequipmentAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		$fitnessEquipments  =  new FitnessEquipments();
  		
  		
        $fitnessEquipments->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listequipments');
        
        
  	}
  }
  
  
  /*
    * created by Lekha
    * date : 27/2/2012
	* method for deleting muscles 
	* @param muscle id
	*/
  function removemuscleAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  	
  	if($delete_id != "")
  	{
  		$fitnessBodyAreas  =  new FitnessBodyAreas();
  		
  		
        $fitnessBodyAreas->find($delete_id)->current()->delete();
		
        $this->_redirect('/admin/listmuscles');
        
        
  	}
  }
  
  
   /*
    * created by Lekha
    * date : 6/8/2012
	* method for deleting category 
	* @param category id
	*/
  function removecategoryAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  	
  	if($delete_id != "")
  	{
  		$fitnessNewsCategory  =  new FitnessNewsCategory();
  		$fitnessNewsCategory->find($delete_id)->current()->delete();
  		
        
        $this->_redirect('/admin/listcategory');
        
        
  	}
  }
  
  
   /*
    * created by Lekha
    * date : 5/32/2012
	* method for deleting workouts 
	* @param workout  id
	*/
  function removeworkAction()
  {
  	$delete_id = $this->_request->getParam('delete');
  
  	
  	if($delete_id != "")
  	{
  		 $fitnessWorkouts              =  new FitnessWorkouts();
  		
  		
        $fitnessWorkouts->find($delete_id)->current()->delete();
        $this->_redirect('/admin/listworks');
        
        
  	}
  }
  
  
  
   /*
    * created by Lekha
    * date : 23/4/2012
	* method for listing workout rates
	* 
	*/
  public function listworkoutratesAction()
  {
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  		$fitnessRates           =  new FitnessWorkoutRates();
  		
  		$this->view->rateList               = $fitnessRates->listRates();
  }
  
  
  
   /*
    * created by Lekha
    * date : 23/4/2012
	* method for adding workout rates
	* 
	*/
  public function addratesAction()
  {
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $workrateData           =  array();
  		$fitnessRates           =  new FitnessWorkoutRates();
  		
  		if ($this->_request->isPost())    
		{
		
  	       $workrateData['app_version']                =   $this->_request->getPost('app_version');
  	       $workrateData['rate_single_workout']        =   $this->_request->getPost('workout_single_rate');
  	       $workrateData['rate_total_workout']         =   $this->_request->getPost('workout_total_rate');
  	       
  	       $fitnessRates->addData($workrateData);
  	       $this->_redirect('/admin/listworkoutrates');
		}
  		
  }
  
  
   /*
    * created by Lekha
    * date : 23/4/2012
	* method for editing workout rates
	* 
	*/
  public function editratesAction()
  {
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $workrateData           =  array();
  		$fitnessRates           =  new FitnessWorkoutRates();
  		
  		$this->view->workoutrateId          =   $this->_request->getParam('rateId');
  		$this->view->rateDetails            =   $fitnessRates->getRateDetails($this->_request->getParam('rateId'));
  		
  		if ($this->_request->isPost())    
		{
		
		   $rateId                                     =   $this->_request->getPost('rateId');	
  	       $workrateData['app_version']                =   $this->_request->getPost('app_version');
  	       $workrateData['rate_single_workout']        =   $this->_request->getPost('workout_single_rate');
  	       $workrateData['rate_total_workout']         =   $this->_request->getPost('workout_total_rate');
  	       
  	       $where = "id='".$rateId."'"; 
	        $fitnessRates->update($workrateData,$where);
  	       $this->_redirect('/admin/listworkoutrates');
		}
  		
  }
  
   /*
    * created by Lekha
    * date : 28/2/2012
	* method for adding equipments
	* 
	*/
  function addequipmentAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  		$fitnessEquip           =  new FitnessEquipments();
  		$fitnessEquipMulti      =  new FitnessEquipmentsMultilang();
  		$equipData              =  array();
  		$equipMultilangData     =  array();
  		
  		if ($this->_request->isPost())    
		{
		
  	       $equipData['eqp_name']                =   $this->_request->getPost('equip_name');
  	       $fitnessEquip->addData($equipData);
  	       $equipId                              =   $fitnessEquip->getLastEquipId();
  	       
  	       $equipMultilangData['equipment_id']   =   $equipId['eqp_home_id'];
  	       $equipMultilangData['lang_id']        =   $this->getDefaultLanguage();
  	       $equipMultilangData['equipment_name'] =   $this->_request->getPost('equip_name');
  	       
  	       $fitnessEquipMulti->addData($equipMultilangData);
  	       
  	       $this->_redirect('/admin/listequipments');
  	       
		}
    
  }
  
  
   /*
    * created by Lekha
    * date : 28/2/2012
	* method for adding muscles
	* 
	*/
  function addmusclesAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  		$fitnessBodyAreas       =  new FitnessBodyAreas();
  		$fitnessBodyAreasMulti  =  new FitnessBodyAreasMultilang();
  		$muscleData             =  array();
  		$muscleMultilangData    =  array();
  		
  		if ($this->_request->isPost())    
		{
		
  	       $muscleData['area_name']             =   $this->_request->getPost('muscle_name');
  	       $fitnessBodyAreas->addData($muscleData);
  	       $muscleId                            =   $fitnessBodyAreas->getLastMuscleId();
  	       
  	       $muscleMultilangData['bodyarea_id']  =   $muscleId['area_id'];
  	       $muscleMultilangData['lang_id']      =   $this->getDefaultLanguage();
  	       $muscleMultilangData['area_name']    =   $this->_request->getPost('muscle_name');
  	       
  	       $fitnessBodyAreasMulti->addData($muscleMultilangData);
  	       
  	       $this->_redirect('/admin/listmuscles');
  	       
		}
    
  }
  
  
  /*
    * created by Lekha
    * date : 29/2/2012
	* method for adding interests
	* 
	*/
  function addinterestAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  		$fitnessInterest        =  new FitnessInterests();
  		$fitnessInterestMulti   =  new FitnessInterestsMultilang();
  		$interestData           =  array();
  		$intereseMultilangData  =  array();
  		
  		if ($this->_request->isPost())    
		{
		
  	       $interestData['interest_name']            =   $this->_request->getPost('interest_name');
  	       $fitnessInterest->addData($interestData);
  	       $interestId                               =   $fitnessInterest->getLastInterestsId();
  	       
  	       $intereseMultilangData['interest_id']     =   $interestId['interest_id'];
  	       $intereseMultilangData['lang_id']         =   $this->getDefaultLanguage();
  	       $intereseMultilangData['interest_name']   =   $this->_request->getPost('interest_name');
  	       
  	       $fitnessInterestMulti->addData($intereseMultilangData);
  	       
  	       $this->_redirect('/admin/listinterests');
  	       
		}
    
  }
  
  
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for adding targets
	* 
	*/
  function addplanAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  		$fitnessPlan          =  new FitnessMembershipPlans();
  		$fitnessPlanMulti     =  new FitnessMembershipPlansMultilang();
  		$planData             =  array();
  		$planMultilangData    =  array();
  		
  		if ($this->_request->isPost())    
		{
		
  	       $planData['membership_plan']                     =   $this->_request->getPost('membership_plan');
  	       $planData['membership_offer_period']             =   $this->_request->getPost('membership_offer_period');
  	       $planData['membership_rate']                     =   $this->_request->getPost('membership_rate');
  	       $planData['membership_status']                   =   1;
  	       $fitnessPlan->addData($planData);
  	       $planId                                          =   $fitnessPlan->getLastMembershipId();
  	       
  	       $planMultilangData['membership_id']              =   $planId['membership_id'];
  	       $planMultilangData['lang_id']                    =   $this->getDefaultLanguage();
  	       $planMultilangData['membership_plan']            =   $this->_request->getPost('membership_plan');
  	       $planMultilangData['membership_description']     =   $this->_request->getPost('membership_description');
  	       
  	       $fitnessPlanMulti->addData($planMultilangData);
  	       
  	       $this->_redirect('/admin/listplans');
  	       
		}
    
  }
  
  
  
  
   
  /*
    * created by Lekha
    * date : 29/2/2012
	* method for adding targets
	* 
	*/
  function addtargetAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  		$fitnessTarget        =  new FitnessTargets();
  		$fitnessTargetMulti   =  new FitnessTargetsMultilang();
  		$targetData           =  array();
  		$targetMultilangData  =  array();
  		
  		if ($this->_request->isPost())    
		{
		
  	       $targetData['target_name']              =   $this->_request->getPost('target_name');
  	       $fitnessTarget->addData($targetData);
  	       $targetId                               =   $fitnessTarget->getLastTargetId();
  	       
  	       $targetMultilangData['target_id']       =   $targetId['target_id'];
  	       $targetMultilangData['lang_id']         =   $this->getDefaultLanguage();
  	       $targetMultilangData['target_name']     =   $this->_request->getPost('target_name');
  	       
  	       $fitnessTargetMulti->addData($targetMultilangData);
  	       
  	       $this->_redirect('/admin/listtargets');
  	       
		}
    
  }
  
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for editing membership plans
	* 
	*/
  function editplanAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->planId     =   $this->_request->getParam('planId');
        
        
        
        $fitnessPlans        =  new FitnessMembershipPlans();
  		$fitnessPlansMulti   =  new FitnessMembershipPlansMultilang();
  		$planData            =  array();
  		$plansMultilangData  =  array();
  		
  		
  		   $this->view->fitnessPlan     = $fitnessPlans->getPlans($this->_request->getParam('planId'),$this->_request->getParam('langId'));
  		
  		   $this->view->fitnessMultiPlan = $fitnessPlansMulti->getPlans($this->_request->getParam('planId'),$this->_request->getParam('langId'));
  		   
  		   if($this->_request->getParam('langId') == "")
  		   {
  		   	 $this->view->membershipPlan    = $this->view->fitnessPlan['membership_plan'];
  		   }
  		   else 
  		   {
  		   	 $this->view->membershipPlan    = $this->view->fitnessMultiPlan['membership_plan'];
  		   }
  		
  		
  		if ($this->_request->isPost())    
		{
		
  	       if($this->_request->getPost('langId') == "")
  	       {
	  	       $planData['membership_plan']           =   $this->_request->getPost('membership_plan');
	  	       $planData['membership_offer_period']   =   $this->_request->getPost('membership_offer_period');
	  	       $planData['membership_rate']           =   $this->_request->getPost('membership_rate');
	  	      
	  	       
	  	       
	  	       $where ="membership_id='".$this->_request->getPost('planId')."'";
	  	       $fitnessPlans->update($planData,$where);
		  }
  	       
  	       
  	       if($this->_request->getPost('langId') != "")
  	       {
	  	       $plansMultilangData['membership_id']               =   $this->_request->getPost('planId');
	  	       $plansMultilangData['lang_id']                     =   $this->_request->getPost('langId');
	  	       $plansMultilangData['membership_description']      =   $this->_request->getPost('membership_description');
	  	       
	  	       $plansMultilangData['membership_plan']             =   $this->_request->getPost('membership_plan');
	  	       
	  	       $checkRecords                                      =   $fitnessPlansMulti->getLangRecord($this->_request->getPost('planId'),$this->_request->getPost('langId'));
	  	      
	  	       if($checkRecords['count'] > 0)
	  	       {
	  	       	  $fitnessPlansMulti->update($plansMultilangData, array(
					'membership_id = ?' => $this->_request->getPost('planId'),
					'lang_id = ?' => $this->_request->getPost('langId')
					));
	  	       }
	  	       else 
	  	       {
	  	       	$fitnessPlansMulti->addData($plansMultilangData);
	  	       }
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listplans');
  	       
		}
    
  }
  
  
  
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for editing target
	* 
	*/
  function edittargetAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->targetId   =   $this->_request->getParam('targetId');
        
        
        
        $fitnessTarget        =  new FitnessTargets();
  		$fitnessTargetMulti   =  new FitnessTargetsMultilang();
  		$targetData           =  array();
  		$targetMultilangData  =  array();
  		
  		
  		$this->view->fitnessMultiTargetData = $fitnessTargetMulti->getTargets($this->_request->getParam('targetId'),$this->_request->getParam('langId'));
  		
  		if ($this->_request->isPost())    
		{
		
  	       
  	       $targetMultilangData['target_id']      =   $this->_request->getPost('targetId');
  	       $targetMultilangData['lang_id']        =   $this->_request->getPost('langId');
  	       $targetMultilangData['target_name']    =   $this->_request->getPost('target_name');
  	       
  	       
  	       
  	       
  	       $checkRecords                        =   $fitnessTargetMulti->getLangRecord($this->_request->getPost('targetId'),$this->_request->getPost('langId'));
  	      
  	       if($checkRecords['count'] > 0)
  	       {
  	       	  $fitnessTargetMulti->update($targetMultilangData, array(
				'target_id = ?' => $this->_request->getPost('targetId'),
				'lang_id = ?' => $this->_request->getPost('langId')
				));
  	       }
  	       else 
  	       {
  	       	$fitnessTargetMulti->addData($targetMultilangData);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listtargets');
  	       
		}
    
  }
  
  
  
  
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for editing interests
	* 
	*/
  function editinterestAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->intId      =   $this->_request->getParam('intId');
        
        
        
        
  		$fitnessInterest        =  new FitnessInterests();
  		$fitnessInterestMulti   =  new FitnessInterestsMultilang();
  		$interestData           =  array();
  		$intereseMultilangData  =  array();
  		
  		$this->view->fitnessMultiIntData = $fitnessInterestMulti->getInterests($this->_request->getParam('intId'),$this->_request->getParam('langId'));
  		
  		if ($this->_request->isPost())    
		{
		
  	       
  	       $intereseMultilangData['interest_id']      =   $this->_request->getPost('intId');
  	       $intereseMultilangData['lang_id']          =   $this->_request->getPost('langId');
  	       $intereseMultilangData['interest_name']    =   $this->_request->getPost('interest_name');
  	       
  	       
  	       
  	       
  	       $checkRecords                        =   $fitnessInterestMulti->getLangRecord($this->_request->getPost('intId'),$this->_request->getPost('langId'));
  	      
  	       if($checkRecords['count'] > 0)
  	       {
  	       	  $fitnessInterestMulti->update($intereseMultilangData, array(
				'interest_id = ?' => $this->_request->getPost('intId'),
				'lang_id = ?' => $this->_request->getPost('langId')
				));
  	       }
  	       else 
  	       {
  	       	$fitnessInterestMulti->addData($intereseMultilangData);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listinterests');
  	       
		}
    
  }
  
  
  
  
  
   /*
    * created by Lekha
    * date : 29/2/2012
	* method for editing equipments
	* 
	*/
  function editequipmentAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->equipId     =   $this->_request->getParam('equipId');
        
        
        
        
  		$fitnessEquip           =  new FitnessEquipments();
  		$fitnessEquipMulti      =  new FitnessEquipmentsMultilang();
  		$equipData              =  array();
  		$equipMultilangData     =  array();
  		
  		$this->view->fitnessMultiEquipData = $fitnessEquipMulti->getEquipments($this->_request->getParam('equipId'),$this->_request->getParam('langId'));
  		
  		if ($this->_request->isPost())    
		{
		
  	       $equipData['eqp_name']  	                =   $this->_request->getPost('equip_name');    
  	       $equipMultilangData['equipment_id']      =   $this->_request->getPost('equipId');
  	       $equipMultilangData['lang_id']           =   $this->_request->getPost('langId');
  	       $equipMultilangData['equipment_name']    =   $this->_request->getPost('equip_name');
  	       
  	       
  	       
  	       
  	       $checkRecords                        =   $fitnessEquipMulti->getLangRecord($this->_request->getPost('equipId'),$this->_request->getPost('langId'));
  	      
  	       if($checkRecords['count'] > 0)
  	       {
  	       	  $fitnessEquipMulti->update($equipMultilangData, array(
				'equipment_id = ?' => $this->_request->getPost('equipId'),
				'lang_id = ?' => $this->_request->getPost('langId')
				));
  	       }
  	       else 
  	       {
  	       	$fitnessEquipMulti->addData($equipMultilangData);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listequipments');
  	       
		}
    
  }
  
  
  
  
   /*
    * created by Lekha
    * date : 28/2/2012
	* method for editing muscles
	* 
	*/
  function editmuscleAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->areaId     =   $this->_request->getParam('areaId');
        
        
        
        
  		$fitnessBodyAreas       =  new FitnessBodyAreas();
  		$fitnessBodyAreasMulti  =  new FitnessBodyAreasMultilang();
  		$muscleData             =  array();
  		$muscleMultilangData    =  array();
  		
  		$this->view->fitnessMultiMuscleData = $fitnessBodyAreasMulti->getMuscles($this->_request->getParam('areaId'),$this->_request->getParam('langId'));
  		
  		if ($this->_request->isPost())    
		{
		
  	         	       
  	       $muscleMultilangData['bodyarea_id']  =   $this->_request->getPost('areaId');
  	       $muscleMultilangData['lang_id']      =   $this->_request->getPost('langId');
  	       $muscleMultilangData['area_name']    =   $this->_request->getPost('muscle_name');
  	       
  	       $checkRecords                        =   $fitnessBodyAreasMulti->getLangRecord($this->_request->getPost('areaId'),$this->_request->getPost('langId'));
  	      
  	       if($checkRecords['count'] > 0)
  	       {
  	       	  $fitnessBodyAreasMulti->update($muscleMultilangData, array(
				'bodyarea_id = ?' => $this->_request->getPost('areaId'),
				'lang_id = ?' => $this->_request->getPost('langId')
				));
  	       }
  	       else 
  	       {
  	       	$fitnessBodyAreasMulti->addData($muscleMultilangData);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listmuscles');
  	       
		}
    
  }
  
  
  /*
    * created by Lekha
    * date : 28/2/2012
	* method for editing muscles
	* 
	*/
  function editcategoryAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->catId     =   $this->_request->getParam('catId');
        
       
        
        
  		$fitnessCategory      =  new FitnessNewsCategory();
  		$fitnessCatMulti      =  new FitnessNewsCategoryMultilang();
  		$catData             =  array();
  		$newsMultilangData    =  array();
		$newsmultiArray       =  array();
  		
  		$this->view->fitnessMultiCatData = $fitnessCatMulti->getNewsCategory($this->_request->getParam('catId'),$this->_request->getParam('langId'));
		
  		
  		if ($this->_request->isPost())    
		{
		  
  	         	       
  	       
		   $newsMultilangData['category_name']    =   $this->_request->getPost('category');
  	      
  	        	       
  	       $checkRecords                        =   $fitnessCatMulti->getLangRecord($this->_request->getPost('catId'),$this->_request->getPost('langId'));
  	     
  	       if($checkRecords['count'] > 0)
  	       {
		     
  	       	  $fitnessCatMulti->update($newsMultilangData, array(
				'category_id = ?' => $this->_request->getPost('catId'),
				'lang_id = ?' => $this->_request->getPost('langId')
				));
			
  	       }
  	       else 
  	       { 
		   
		   $newsmultiArray['category_id']  =   $this->_request->getPost('catId');
  	       $newsmultiArray['name']    =   $this->_request->getPost('category');
		    $newsmultiArray['lang_id']      =   $this->_request->getPost('langId');
  	       	$fitnessCatMulti->addData($newsmultiArray);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listcategory');
  	       
		}
    
  }
  
  
  
    /*
    * created by Lekha
    * date : 28/2/2012
	* method for editing muscles
	* 
	*/
  function editnewsAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->newsId     =   $this->_request->getParam('newsId');
        
       
        
        
  		$fitnessNews       =  new FitnessNews();
  		$fitnessNewsMulti  =  new FitnessNewsMultilang();
		$fitnessCategoryMulti  = new FitnessNewsCategoryMultilang();
		$defaultLang           = $this->getDefaultLanguage();
     	
     	
		
		$this->view->newcategoryList   = $fitnessCategoryMulti->getAllcategory($defaultLang);
  		$newsData             =  array();
  		$newsMultilangData    =  array();
		$newsmultiArray       =  array();
  		
  		$this->view->fitnessMultiNewsData = $fitnessNewsMulti->getNews($this->_request->getParam('newsId'),$this->_request->getParam('langId'));
		$this->view->fitnessNewsData      = $fitnessNews->getNews($this->_request->getParam('newsId'));
  		
  		if ($this->_request->isPost())    
		{
		  
  	         	       
  	       $newsMultilangData['news_id']  =   $this->_request->getPost('newsId');
		   $newsMultilangData['news_title']       =   $this->_request->getPost('news_title');
  	      
  	       $newsMultilangData['news_content']     =   $this->_request->getPost('news');
		   $newsData['news_category']             =   $this->_request->getPost('category');
		   $newsData['news_date']                 =   $this->_request->getPost('news_date');
  	       
  	       $checkRecords                        =   $fitnessNewsMulti->getLangRecord($this->_request->getPost('newsId'),$this->_request->getPost('langId'));
  	     
		 
		    $fitnessNews->update($newsData, array(
				'id = ?' => $this->_request->getPost('newsId')
				));
		   
  	       if($checkRecords['count'] > 0)
  	       {
		     
  	       	  $fitnessNewsMulti->update($newsMultilangData, array(
				'news_id = ?' => $this->_request->getPost('newsId'),
				'lang = ?' => $this->_request->getPost('langId')
				));
			
  	       }
  	       else 
  	       { 
		   
		   $newsmultiArray['news_id']  =   $this->_request->getPost('newsId');
  	       $newsMultilangData['news_title']    =   $this->_request->getPost('news_title');
  	       $newsmultiArray['news_content']    =   $this->_request->getPost('news');
		    $newsmultiArray['lang_id']      =   $this->_request->getPost('langId');
  	       	$fitnessNewsMulti->addData($newsmultiArray);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listnews');
  	       
		}
    
  }
  
  
    /*
    * created by Lekha
    * date : 26/7/2012
	* method for listing Users depending on the options they opted
	* 
	*/
   public function listusersofferAction()
   {
   	  
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus  = $this->isLoggedIn();
        $fitnessUserGeneral       =  new FitnessUserGeneral();
		$fitnessUserSettings      =  new FitnessUserSettings();
		$usersOffer               = $fitnessUserSettings->getUsersByField('offers');
       foreach($usersOffer as $users)
	   {
	   	 $usersInfo[]       =  $fitnessUserGeneral->getUser($users['user_id']);
	   }
        $this->view->listUsers    =  $usersInfo;
   }
  
  
   /*
    * created by Lekha
    * date : 26/7/2012
	* method for listing Users depending on the options they opted
	* 
	*/
   public function listusersupdateAction()
   {
   	  
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus  = $this->isLoggedIn();
        $fitnessUserGeneral       =  new FitnessUserGeneral();
		$fitnessUserSettings      =  new FitnessUserSettings();
		$usersOffer               = $fitnessUserSettings->getUsersByField('updates');
       foreach($usersOffer as $users)
	   {
	   	 $usersInfo[]       =  $fitnessUserGeneral->getUser($users['user_id']);
	   }
        $this->view->listUsers    =  $usersInfo;
   }
   
   
   /*
    * created by Lekha
    * date : 26/7/2012
	* method for listing Users depending on the options they opted
	* 
	*/
   public function listusersdnbAction()
   {
   	  
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus  = $this->isLoggedIn();
        $fitnessUserGeneral       =  new FitnessUserGeneral();
		$fitnessUserSettings      =  new FitnessUserSettings();
		$usersOffer               = $fitnessUserSettings->getUsersByField('dnb');
       foreach($usersOffer as $users)
	   {
	   	 $usersInfo[]       =  $fitnessUserGeneral->getUser($users['user_id']);
	   }
        $this->view->listUsers    =  $usersInfo;
   }
  
    /*
    * created by Lekha
    * date : 29/2/2012
	* method for listing Users 
	* 
	*/
   public function listusersAction()
   {
   	  
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus  = $this->isLoggedIn();
        $fitnessUserGeneral       =  new FitnessUserGeneral();
        
        $this->view->listUsers    =  $fitnessUserGeneral->listUsers();
   }
   
   
   /*
    * created by Lekha
    * date : 1/1/2012
	* method for viewing details of a single user
	* 
	*/
   public function viewuserAction()
   {
   	  
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus  = $this->isLoggedIn();
        $fitnessUserGeneral             =  new FitnessUserGeneral();
        $fitnessUserSettings            =  new FitnessUserSettings();
        $fitnessTargets                 =  new FitnessTargets();
        $fitnessInterest                =  new FitnessInterests();
        $fitnessworkoutsDone            =  new FitnessUserWorkoutsDone();
        $fitnessworkoutGeneralMulti     =  new FitnessExerciseGeneralMultilang();
        $fitnessworkoutsUnlocked        =  new FitnessUserWorkoutsUnlocked();
        $fitnessworkoutsCustom          =  new FitnessUserCustomWorkouts();
        $fitnessworkoutsMembership      =  new FitnessUserMembership();
        $fitnessMembershipPlans         =  new FitnessMembershipPlans();
        
        
        $userId                         =   $this->_request->getParam('userId');
        
        
        
        $userSettingsData               =  $fitnessUserSettings->getUserSettings($userId);
        $usertargets                    =  explode(",",$userSettingsData["workout_targets"]);
        $userinterests                  =  explode(",",$userSettingsData["workout_interests"]);
        $userworkouts                   =  $fitnessworkoutsDone->getUserWorkoutsDone($userId);
        $userworkoutsUnlocked           =  $fitnessworkoutsUnlocked->getUserWorkoutsUnlocked($userId);
        $userworkoutsCustom             =  $fitnessworkoutsCustom->getUserWorkoutCustom($userId);
        $userworkoutsMembership         =  $fitnessworkoutsMembership->getUserMembership($userId);
        $membershipPlan                 =  $fitnessMembershipPlans->getPlans($userworkoutsMembership['membership_plan']);
       
        
        $userworkoutArray               =  array();
        $userworkoutUnlockedArray       =  array();
        $userworkoutCustomArray         =  array();
       
        
        foreach($usertargets as $targetId)
        {
        	$targets                    =  $fitnessTargets->getTarget($targetId);
        	$targetName[]               =  $targets['target_name'];
        }
        foreach($userinterests as $interestId)
        {
        	$interest                   =  $fitnessInterest->getInterest($interestId);
        	$interestName[]             =  $interest['interest_name'];
        }
        $i=0;
        foreach($userworkouts as $workout)
        {
        	$workoutData                              =  $fitnessworkoutGeneralMulti->getWorkoutName($workout['workout_id']);
        	$userworkoutArray[$i]['workout_name']         =  $workoutData['workout_name'];
        	$userworkoutArray[$i]['workout_time']         =  $workout['workout_time'];
        	$userworkoutArray[$i]['workout_date']         =  $workout['workout_done_date'];
        $i=$i+1;}
        
        $k=0;
        foreach($userworkoutsUnlocked as $workout)
        {
        	$workoutData                                      =  $fitnessworkoutGeneralMulti->getWorkoutName($workout['workout_id']);
        	$userworkoutUnlockedArray[$k]['workout_name']         =  $workoutData['workout_name'];
        	$userworkoutUnlockedArray[$k]['workout_status']       =  $workout['workout_purchase_status'];
        	$userworkoutUnlockedArray[$k]['workout_date']        =  $workout['unlocked_date'];
        $k=$k+1;}
        
        $j=0;
        foreach($userworkoutsCustom as $custom)
        {
        	
        	$userworkoutCustomArray[$j]['workout_name']        =  $custom['custom_workout_name'];
        	$userworkoutCustomArray[$j]['workout_time']        =  $workout['total_workout_time'];
        	$userworkoutCustomArray[$j]['workout_date']        =  $workout['date_created'];
        $j=$j+1;}
       
        $this->view->userGeneralData                  =  $fitnessUserGeneral->getUser($userId);
        $this->view->userTargetData                   =  implode(",",$targetName);
        $this->view->userInterestData                 =  implode(",",$interestName);
        $this->view->userSettingsData                 =  $userSettingsData;
        $this->view->userWorkoutData                  =  $userworkoutArray;
        $this->view->userWorkoutUnlockedData          =  $userworkoutUnlockedArray;
        $this->view->userWorkoutCustomData            =  $userworkoutCustomArray;
        $this->view->userWorkoutMembershipData        =  $userworkoutsMembership;
        $this->view->userMembershipName               =  $membershipPlan['membership_plan'];
        
        
        //get targets from the target model
        
        
   }
   
   
   public function userstatisticsAction()
   {
   	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        
  	$this->view->pageTitle = "User Statistics";
  	$this->view->loginStatus  = $this->isLoggedIn();
  	
  	$userId                         =   $this->_request->getParam('userId');
  	
  	
  	$fitnessworkoutGeneralMulti     =  new FitnessExerciseGeneralMultilang();
  	$fitnessworkoutsDone            =  new FitnessUserWorkoutsDone();
  	
  	
  	$userworkouts                   =  $fitnessworkoutsDone->getUserWorkoutsDone($userId);
  	
  	$totalTime                      =  $fitnessworkoutsDone->getTotalWorkoutTime($userId);
  	$userworkoutArray               =  array();
  	
  	//get the last 3 workouts of the user
  	
  	$i=0;
        foreach($userworkouts as $workout)
        {
        	if($i<=3)
        
		        {
		        	$workoutData                                  =  $fitnessworkoutGeneralMulti->getWorkoutName($workout['workout_id']);
		        	$userworkoutArray[$i]['workout_name']         =  $workoutData['workout_name'];
		        	$userworkoutArray[$i]['workout_time']         =  $workout['workout_time'];
		        	$userworkoutArray[$i]['workout_date']         =  $workout['workout_done_date'];
		       }
         $i=$i+1;
        }
        
        
        $this->view->userWorkoutData                  =  $userworkoutArray;
        $this->view->userWorkoutTime                  =  $totalTime['totaltime'];
   }
  
  
   /*
    * created by Lekha
    * date : 24/2/2012
	* method for listing workouts 
	* 
	*/
  function listworkoutAction()
  {
  	
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        
  	$this->view->pageTitle = "List workouts";
  	$this->view->loginStatus  = $this->isLoggedIn();
  	//fetch data from  fitness exercise general table
			$fitnessGeneral  =  new FitnessExerciseGeneral();
			$fitnessGeneralMultiLang =  new FitnessExerciseGeneralMultilang();
			$musclesData    =  new FitnessBodyAreasMultilang();
			
			$workoutsArray   =  array();
			
			if($this->_request->getParam('active') !="")
			{
				$workoutsData    =  $fitnessGeneral->selectActiveRecords();
			}
			else
			{
				$workoutsData    =  $fitnessGeneral->selectInActiveRecords();
			}
			
			$workoutArray    = array();
			
			$fitnessLanguage           =  new FitnessLanguages();
			$this->view->languageList  = $fitnessLanguage->getLanguages();
			
			$defaultLanguage           = $this->getDefaultLanguage();
			$i=0;
			
			
			foreach($workoutsData as $workouts)
			{
				$workoutArray["workout_id"] = $workouts['workout_id'];
				$workoutNameArray           = $fitnessGeneralMultiLang->getWorkoutName($workouts['workout_id'],$defaultLanguage);
				
				$workout_name               = $workoutNameArray['workout_name'];
				
				$primary_muscleArray        = $musclesData->getMuscles($workouts['workout_primary_muscles']);
				$workout_primary_muscle     =  $primary_muscleArray['area_name'];
				$secondary_muscleArray      =  explode(",",$workouts['workout_secondary_muscles']);
				
				foreach($secondary_muscleArray as $secMuscle)
				{
					$secMuscleArray         =  $musclesData->getMuscles($secMuscle);
					
					$workout_sec_muscles    =  $workout_sec_muscles . $secMuscleArray['area_name'] .",";
				}
				$workout_sec_muscles        =  rtrim($workout_sec_muscles,",");
				
				$workoutLevelArray          =  explode(",",$workouts['workout_level']);
				
				foreach($workoutLevelArray as $level)
				{
					if($level == 1)
					{
						$workout_level_beginner  = 1;
					}
					else 
					{
						$workout_level_beginner  = 0;
					}
					if($level == 2)
					{
						$workout_level_advanced  = 1;
					}
					else 
					{
						$workout_level_advanced  = 0;
					}
					if($level == 3)
					{
						$workout_level_proffessional  = 1;
					}
					else 
					{
						$workout_level_proffessional  = 0;
					}
				}
				
				$workoutsArray[$i]['workout_id']      = $workouts['workout_id'];
				$workoutsArray[$i]['workout_name']    = $workout_name;
				$workoutsArray[$i]['primaryMuscle']   = $workout_primary_muscle;
				
				$workoutsArray[$i]['secondaryMuscle'] = $workout_sec_muscles;
				
				$workoutsArray[$i]['beginners']       = $workout_level_beginner;
				$workoutsArray[$i]['advanced']        = $workout_level_advanced;
				$workoutsArray[$i]['professional']    = $workout_level_proffessional;
				
				$workoutsArray[$i]['level']    = $workoutLevelArray;
			
				$workout_sec_muscles = "";
			$i=$i+1;
			}
  
			
			
			
			
			$this->view->workoutArray =$workoutsArray;
  	
  }
  
  
  /*
  * created by Lekha
  * date : 27/2/2012
  * method to update workout
  * 
  */
  
  public function updateworkoutAction()
  {
  	
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
  	
    $musclesDataList=  new FitnessBodyAreas();    
  	$musclesData    =  new FitnessBodyAreasMultilang();
    $equipmentData  =  new FitnessEquipmentsMultilang();
    $timeframesData =  new FitnessWorkoutTimeframes();
    $workoutexercises        =  new FitnessWorkouts();
    
    
  	$this->view->musclesList    =  $musclesDataList->listMuscles();
    $this->view->equipmentList  =  $equipmentData->listEquipments();
    $this->view->timeframesList =  $timeframesData->listTimeframes();
    
    
  	if ($this->_request->isPost())    
		{
  	       $workoutId                          =   $this->_request->getPost('workoutID');
  	       $langID                             =   $this->_request->getPost('langID');
  	       
  	       $fitnessGeneralData = array();
  	       //get all the post data for the table fitness exercise general
  	       
  	       // $fitnessGeneralData['workout_exercises']           = rtrim($this->_request->getPost("exerciseslist"),",");
			$fitnessGeneralData['workout_timeframe']           = $this->_request->getPost("timeframes");
			$fitnessGeneralData['otherside']                   = $this->_request->getPost("otherside");
			$fitnessGeneralData['workout_primary_muscles']     = $this->_request->getPost("primary_muscle");
			$fitnessGeneralData['workout_secondary_muscles']   = rtrim($this->_request->getPost("sec_muscles"),",");
			$fitnessGeneralData['workout_pay_status']          = $this->_request->getPost("workout_pay_status");
			$fitnessGeneralData['workout_rate']        = $this->_request->getPost("workout_rate");
			$fitnessGeneralData['workout_level']       = implode(",",$this->_request->getPost("workout_level"));
			$fitnessGeneralData['translator_check']    = $this->_request->getPost("trans_check");
			if($this->_request->getPost("masterStatus") !="")
			{
				$fitnessGeneralData['master_check']        = $this->_request->getPost("masterStatus");
			}
			
			
			
			//insert data into the  fitness exercise general table
			$fitnessGeneral =  new FitnessExerciseGeneral();
			$where = "workout_id='".$workoutId."'"; 
	        $fitnessGeneral->update($fitnessGeneralData,$where);
	        
	        
	        //get all post data for the table fitness_exercise_general_multilang
			
			$fitnessGeneralMultilangData  = array();
			
			
			$fitnessGeneralMultilangData['workout_name']          = $this->_request->getPost("workout_name");
			$fitnessGeneralMultilangData['otherside']             = $this->_request->getPost("otherside");
			
			$fitnessGeneralMultilangData['workout_preparation']   = $this->_request->getPost("workout_preparation");
			$fitnessGeneralMultilangData['workout_execution']     = $this->_request->getPost("workout_execution");
			$fitnessGeneralMultilangData['workout_advice']        = $this->_request->getPost("workout_advice");
			
			//insert data into the  fitness_exercise_general_multilang table
			$fitnessGeneralMultiLang                 =  new FitnessExerciseGeneralMultilang();
			
			$checkGeneralMultiLangRecord             =  $fitnessGeneralMultiLang->getLangRecord($workoutId,$langID);
			
			if($checkGeneralMultiLangRecord['count'] > 0)
			{
				       $fitnessGeneralMultiLang->update($fitnessGeneralMultilangData, array(
				'workout_id = ?' => $workoutId,
				'lang_id = ?' => $langID
				));
			}
			else 
			{
				$fitnessGeneralMultilangData['workout_id']          = $this->_request->getPost("workoutID");
				$fitnessGeneralMultilangData['lang_id']          = $this->_request->getPost("langID");
				$fitnessGeneralMultiLang->addData($fitnessGeneralMultilangData);
			}
	
	//get all the post data for the table fitness_exercise_equipments
			$fitnessEquipmentsData  = array();
			
			$fitnessEquipmentsData['equipment_required']    = $this->_request->getPost("equipment_required");
			$fitnessEquipmentsData['equipments_home']       = rtrim($this->_request->getPost("home_equipments"),",");
			$fitnessEquipmentsData['equipments_office']     = rtrim($this->_request->getPost("office_equipments"),",");
			$fitnessEquipmentsData['equipments_nature']     = rtrim($this->_request->getPost("nature_equipments"),",");
			$fitnessEquipmentsData['equipments_hotel']      = rtrim($this->_request->getPost("hotel_equipments"),",");
			
			$fitnessEquipments =  new FitnessExerciseEquipments();
			$where = "workout_id='".$workoutId."'"; 
	        $fitnessEquipments->update($fitnessEquipmentsData,$where);
	        
	        
	        
	        //get all the post data for the table fitness_exercise_documents
			$fitnessDocumentsData  = array();
			
			$fitnessDocumentsData['workout_photo_start']    = $this->_request->getPost("photo_start");
			$fitnessDocumentsData['workout_photo_end']    = $this->_request->getPost("photo_end");
			$fitnessDocumentsData['workout_video_file']    = $this->_request->getPost("video_file");
			$fitnessDocumentsData['poster_video']          = $this->_request->getPost("poster_video");
			
			
			$fitnessDocuments =  new FitnessExerciseDocuments();
			
			$where = "workout_id='".$workoutId."'"; 
	        $fitnessDocuments->update($fitnessDocumentsData,$where);
			
			
			//get all the post data for the table FitnessExerciseDocumentsMultilang
			
			$fitnessDocumentsMultilangData  = array();
			
			
			$fitnessDocumentsMultilangData['workout_youtube_link']  = $this->_request->getPost("youtube_link");
			$fitnessDocumentsMultilangData['workout_sound_file']    = $this->_request->getPost("sound_file");
			
			
			
			$fitnessDocumentsMultilang     =  new FitnessExerciseDocumentsMultilang();
			
			$checkDocumentsMultilangRecord = $fitnessDocumentsMultilang->getLangRecord($workoutId,$langID);
			
			if($checkDocumentsMultilangRecord['count'] > 0)
			{
				        $fitnessDocumentsMultilang->update($fitnessDocumentsMultilangData, array(
				'workout_id = ?' => $workoutId,
				'lang_id = ?' => $langID
				));
			}
			else 
			{
				$documentID                                             = $fitnessDocuments->getDocumentId($workoutId);
				
				$fitnessDocumentsMultilangData['document_id']           = $documentID['document_id'];
				$fitnessDocumentsMultilangData['workout_id']            = $workoutId;
			    $fitnessDocumentsMultilangData['lang_id']               = $langID;
			    $fitnessDocumentsMultilangData['youtube_link']          = $this->_request->getPost("youtube_link");
			    $fitnessDocumentsMultilangData['sound_file']            = $this->_request->getPost("sound_file");
			    $fitnessDocumentsMultilangData['video_file']            = $this->_request->getPost("video_file");
			    $fitnessDocumentsMultilangData['poster_video']          = $this->_request->getPost("poster_video");
			
			    
				$fitnessDocumentsMultilang->addData($fitnessDocumentsMultilangData);
			}
	
	
	//get all the post data for the table fitness_exercise_ranking
			
			
			$fitnessRankingData  = array();
			$fitnessRanking =  new FitnessExerciseRanking();
			
			foreach($this->view->musclesList as $muscles)
			{
				$fitnessRankingData['body_area_id']  = $muscles['area_id'];
				$fitnessRankingData['ranking']       = $this->_request->getPost("ranking_".$muscles['area_id']);
				
				$checkRankingCount                   = $fitnessRanking->getCountRecords($workoutId,$muscles['area_id']);
				
				if($checkRankingCount['count'] > 0 )
				{
								$fitnessRanking->update($fitnessRankingData, array(
					'workout_id = ?' => $workoutId,
					'ranking_id = ?' => $this->_request->getPost("rankID_".$muscles['area_id'])
					));
				}
				else 
				{
					$fitnessRankingData['workout_id']  = $workoutId;
					$fitnessRanking->addData($fitnessRankingData);
				}
			}

	
			//get all the post data for the table fitness_exercise_repetition
			
			
			$fitnessRepetitionData  = array();
			$fitnessRepetition =  new FitnessExerciseRepetition();
			
			
			if($this->_request->getPost("repetition_beginners") !="")
			{
			$fitnessRepetitionData['exercise_level_id']    = 1;
			$fitnessRepetitionData['repetitions']          = $this->_request->getPost("repetition_beginners");
			
			$checkRows   =  $fitnessRepetition->checkRecord($workoutId,1);
				if(count($checkRows) > 0)
				{
				$fitnessRepetition->update($fitnessRepetitionData, array(
					'workout_id = ?' => $workoutId,
					'exercise_level_id = ?' => 1
					));
				}
				else 
				{
					$fitnessRepetitionData['workout_id']    = $workoutId;
					$fitnessRepetition->addData($fitnessRepetitionData);
				}
			}
			if($this->_request->getPost("repetition_advanced") !="")
			{
			$fitnessRepetitionData['exercise_level_id']    = 2;
			$fitnessRepetitionData['repetitions']          = $this->_request->getPost("repetition_advanced");
			
			$checkRows   =  $fitnessRepetition->checkRecord($workoutId,2);
			if(count($checkRows) > 0)
				{
					$fitnessRepetition->update($fitnessRepetitionData, array(
						'workout_id = ?' => $workoutId,
						'exercise_level_id = ?' => 2
						));
				}
				else 
				{
					$fitnessRepetitionData['workout_id']    = $workoutId;
					$fitnessRepetition->addData($fitnessRepetitionData);
				}
			}
			if($this->_request->getPost("repetition_professional") !="")
			{
			$fitnessRepetitionData['exercise_level_id']    = 3;
			$fitnessRepetitionData['repetitions']          = $this->_request->getPost("repetition_professional");
			
			$checkRows   =  $fitnessRepetition->checkRecord($workoutId,3);
			
			if(count($checkRows) > 0)
				{
					$fitnessRepetition->update($fitnessRepetitionData, array(
						'workout_id = ?' => $workoutId,
						'exercise_level_id = ?' => 3
						));
				}
				else 
				{
					$fitnessRepetitionData['workout_id']    = $workoutId;
					$fitnessRepetition->addData($fitnessRepetitionData);
				}
			}
			
			
			
			//get post data for fitness_exercise_diff_degree
			
			
			/*$fitnessDiffData  = array();
			$fitnessDiff =  new FitnessExerciseDiffDegree();
			
			if($this->_request->getPost("degree_beginner") =="on")
			$fitnessDiffData['degree_beginner']    = 1;
			else 
			$fitnessDiffData['degree_beginner']    = 0;
			if($this->_request->getPost("beginner_first") =="on")
			$fitnessDiffData['beginner_first']    = 1;
			else 
			$fitnessDiffData['beginner_first']    = 0;
			if($this->_request->getPost("beginner_second") =="on")
			$fitnessDiffData['beginner_second']    = 1;
			else 
			$fitnessDiffData['beginner_second']    = 0;
			if($this->_request->getPost("beginner_third") =="on")
			$fitnessDiffData['beginner_third']    = 1;
			else 
			$fitnessDiffData['beginner_third']    = 0;
			
			if($this->_request->getPost("degree_advanced") =="on")
			$fitnessDiffData['degree_advanced']    = 1;
			else 
			$fitnessDiffData['degree_advanced']    = 0;
			if($this->_request->getPost("advanced_first") =="on")
			$fitnessDiffData['advanced_first']    = 1;
			else 
			$fitnessDiffData['advanced_first']    = 0;
			if($this->_request->getPost("advanced_second") =="on")
			$fitnessDiffData['advanced_second']    = 1;
			else 
			$fitnessDiffData['advanced_second']    = 0;
			if($this->_request->getPost("advanced_third") =="on")
			$fitnessDiffData['advanced_third']    = 1;
			else 
			$fitnessDiffData['advanced_third']    = 1;
			
			if($this->_request->getPost("degree_professional") =="on")
			$fitnessDiffData['degree_professional']    = 1;
			else 
			$fitnessDiffData['degree_professional']    = 0;
			if($this->_request->getPost("professional_first") =="on")
			$fitnessDiffData['professional_first']    = 1;
			else 
			$fitnessDiffData['professional_first']    = 0;
			if($this->_request->getPost("professional_second") =="on")
			$fitnessDiffData['professional_second']    = 1;
			else 
			$fitnessDiffData['professional_second']    = 0;
			if($this->_request->getPost("professional_third") =="on")
			$fitnessDiffData['professional_third']    = 1;
			else 
			$fitnessDiffData['professional_third']    = 0;
			
			
			$where = "workout_id='".$workoutId."'"; 
	        $fitnessDiff->update($fitnessDiffData,$where);*/
			
			
			 $this->_redirect('/admin/listworkout');
		}
  	 
  }
  
  /*
  * created by Lekha
  * date : 27/2/2012
  * method to display the edit page of the workout
  * @param : workout id
  */
  public function editworkoutAction()
  {
  	
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        
  	$this->view->loginStatus  = $this->isLoggedIn();
  	
  	
  	$musclesDataList         =  new FitnessBodyAreas();
  	$musclesData             =  new FitnessBodyAreasMultilang();
    $equipmentData           =  new FitnessEquipmentsMultilang();
    $timeframesData          =  new FitnessWorkoutTimeframes();
    $fitnessGeneral          =  new FitnessExerciseGeneral();
	$fitnessGeneralMultiLang =  new FitnessExerciseGeneralMultilang();
	$musclesData             =  new FitnessBodyAreasMultilang();
	$workoutEquipments       =  new FitnessExerciseEquipments();
	$workoutRanking          =  new FitnessExerciseRanking();
	$workoutRepetition       =  new FitnessExerciseRepetition();
	$workoutDegreeDiff       =  new FitnessExerciseDiffDegree();
	$workoutDocuments        =  new FitnessExerciseDocuments();
	$workoutDocumentsMulti   =  new FitnessExerciseDocumentsMultilang();
	$workoutexercises        =  new FitnessWorkouts();
    
	$adminData      =  new FitnessAdminAccounts();
    
    $sess           =  new Zend_Session_Namespace('AdminSession');
    $adminType      = $adminData->getTypeByUsername($sess->username);	
    
    $this->view->musclesList    =  $musclesDataList->listMuscles();
    $this->view->equipmentList  =  $equipmentData->listEquipments();
    $this->view->timeframesList =  $timeframesData->listTimeframes();
  	$this->view->exerciseList   =  $workoutexercises->listWorkouts() ;    
  	$this->view->adminType      =  $adminType['admin_user_type'];
			
			
  	    $workoutId                          =   $this->_request->getParam('workoutId');
  	    $langId                             =   $this->_request->getParam('langId');
  	    
  	    
  	    
  	    $getWorkoutGeneralData              =  $fitnessGeneral->getRecord($workoutId);
		
		if($langId == 1)
		{
			$this->view->getGermanDescription           = $fitnessGeneralMultiLang->getRecord($workoutId,2);
		}
  	    $getWorkoutGeneralMultilangData     =  $fitnessGeneralMultiLang->getRecord($workoutId,$langId);
  	    $workoutEquipmentsData              =  $workoutEquipments->getRecord($workoutId);
  	    $workoutRankingData                 =  $workoutRanking->getRecord($workoutId);
  	    $workoutDegreeData                  =  $workoutDegreeDiff->getRecord($workoutId);
  	    $workoutDocumentsData               =  $workoutDocuments->getRecord($workoutId);
  	    $workoutDocumentsMultiData         =  $workoutDocumentsMulti->getRecord($workoutId,$langId);
  	    
  	    $workoutRepetitionBeginner          =  $workoutRepetition->getRecord($workoutId,1);
  	    $workoutRepetitionAdvanced          =  $workoutRepetition->getRecord($workoutId,2);
  	    $workoutRepetitionProfessn          =  $workoutRepetition->getRecord($workoutId,3);
  	    
  	    
  	    $exerciseArray                      =  explode(",",$getWorkoutGeneralData['workout_exercises']);
  	    $getPrimaryMuscleArray              =  $musclesData->getMuscles($getWorkoutGeneralData['workout_primary_muscles']);
  	    
  	    $secondary_muscleArray              =  explode(",",$getWorkoutGeneralData['workout_secondary_muscles']);
  	   
  	    
  	    
  	    $this->view->primary_muscle         =  $getPrimaryMuscleArray['bodyarea_id'];
  	    $this->view->sec_muscle             =  $secondary_muscleArray;
  	    $this->view->exercises              =  $exerciseArray;
  	    
  	    $this->view->workoutID              =  $workoutId;
  	    $this->view->langID                 =  $langId;
  	    $this->view->workoutName            =  $fitnessGeneralMultiLang->getWorkoutName($workoutId,$langId);
  	    $this->view->workoutGeneralMultilang= $getWorkoutGeneralMultilangData;
  	    $this->view->workoutEquipments      = $workoutEquipmentsData;
  	    $this->view->workoutRanking         = $workoutRankingData;
  	    $this->view->repetitionBeginner     = $workoutRepetitionBeginner['repetitions'];
  	    $this->view->repetitionAdvanced     = $workoutRepetitionAdvanced['repetitions'];
  	    $this->view->repetitionProfessn     = $workoutRepetitionProfessn['repetitions'];
  	    $this->view->workoutDegree          = $workoutDegreeData;
  	    $this->view->workoutDocuments       = $workoutDocumentsData;
  	    $this->view->workoutDocumentsMulti  = $workoutDocumentsMultiData;
  	    $this->view->workoutGeneralData     = $getWorkoutGeneralData;
  	    
  	   
  	
  }
  
  
  /*
  * created by Lekha
  * date : 23/2/2012
  * method to set a language selected for the backend
  * @param : language id
  */
   function setlanguageAction()
     {
     	$langcode = $this->_request->getParam('lang');
     	 $sess = new Zend_Session_Namespace('UserLanguage');
		  if($sess->isLocked())
		  $sess->unlock();
		  $sess->lang = $langcode;
		  $this->_redirect('/admin/homeuser');
     }
     
     
     
 /*
  * created by Lekha
  * date : 27/2/2012
  * method to add languages to the project
  * 
  */
     public function addlanguagesAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
     	$this->view->loginStatus  = $this->isLoggedIn();
     	$fitnessLanguage   =  new FitnessLanguages();
     	$langauageData  = array();	
     	
     	
     	if ($this->_request->isPost())    
		{
		
  	       $langauageData['langName']                          =   $this->_request->getPost('language_name');
  	       $langauageData['langCode']                          =   $this->_request->getPost('language_code');
  	       $langauageData['iconName']                          =   $langauageData['langCode'].".png"; 
  	       
  	       $langIcon                                           =   $_FILES["language_flag"]["name"];
  	       
  	       
  	       
  	         if($langIcon != "")
			    {
			       move_uploaded_file($_FILES['language_flag']['tmp_name'],'./public/images/languages/'. $langauageData['iconName']);
			    }
		   $fitnessLanguage->addData($langauageData);	    
			    
		}
     }
     
     
     
 /*
  * created by Lekha
  * date : 27/2/2012
  * method to list out all the langugaes 
  * 
  */
     public function listlanguagesAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        $fitnessLanguage   =  new FitnessLanguages();
        $langauageData  = array();	
        
        $this->view->loginStatus  = $this->isLoggedIn();
        
        //call methid to list all languages
        
        $this->view->fitnessLanguages  =  $fitnessLanguage->getLanguages();
        
        
     }
     
     
 /*
  * created by Lekha
  * date : 27/2/2012
  * method to edit a language
  * @param : language id
  */
     public function editlanguageAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        $fitnessLanguage   =  new FitnessLanguages();
        
        $this->view->loginStatus          = $this->isLoggedIn();
        
        $this->view->langauageId                      =   $this->_request->getParam('languageId');
        //get language details
        
        $this->view->fitnessLanguageData  = $fitnessLanguage->getLanguage($this->_request->getParam('languageId'));
        
        //get post data
        
        if ($this->_request->isPost())    
		{
		
		   $langauageId                                        =   $this->_request->getPost('language_id');	
  	       $langauageData['language_name']                          =   $this->_request->getPost('language_name');
  	       $langauageData['language_code']                          =   $this->_request->getPost('language_code');
  	       $langauageData['language_flag']                          =   $langauageData['language_code'].".png"; 
  	       
  	        $langIcon                                               =   $_FILES["language_flag"]["name"];
  	       
  	       
  	       
  	         if($langIcon != "")
			    {
			       move_uploaded_file($_FILES['language_flag']['tmp_name'],'./public/images/languages/'. $langauageData['language_flag']);
			    }
			    
			    
			$where = "language_id='".$langauageId."'"; 
	        $fitnessLanguage->update($langauageData,$where);
		}
        
        
     }
     
     
	 /*
	  * created by Lekha
	  * date : 25/4/2012
	  * method to app website pages
	  *
	  */
     function addpageAction()
     {
     	$fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	
     	$pageArray        =  array();
     	$pageMultiArray   =  array();
     	
     	if ($this->_request->isPost())    
		{
			$pageArray['page_name']   =   $this->_request->getPost('page_name');	
			
			$fitnessPages->addData($pageArray);
			
			$lastPageId               =   $fitnessPages->getLastPageId();
			
			$pageMultiArray['page_id']     =   $lastPageId['page_id'];
			$pageMultiArray['page_title']  =   $this->_request->getPost('page_name');
			$pageMultiArray['lang_id']     =   $this->getDefaultLanguage();
			
			$fitnessPagesMulti->addData($pageMultiArray);
			
			
			$this->_redirect('/admin/listpages'); 
		}
     	
     }
     
	 
	 /*
	  * created by Lekha
	  * date : 23/7/2012
	  * method to add news
	  *
	  */
     function addnewsAction()
     {
     	$fitnessNews     = new FitnessNews();
     	$fitnessNewsMulti= new FitnessNewsMultilang();
		$fitnessCategoryMulti  = new FitnessNewsCategoryMultilang();
		$defaultLang           = $this->getDefaultLanguage();
     	
     	$newsArray        =  array();
     	$newsMultiArray   =  array();
		
		$this->view->newcategoryList   = $fitnessCategoryMulti->getAllcategory($defaultLang);
     	
     	if ($this->_request->isPost())    
		{
			$newsArray['news']               =   $this->_request->getPost('news');
			$newsArray['news_category']      =   $this->_request->getPost('category');
			$newsArray['news_date']          =   $this->_request->getPost('news_date');		
			
			$fitnessNews->addData($newsArray);
			
			$lastnewsId                      =   $fitnessNews->getLastNewsId();
			
			$newsMultiArray['news_id']       =   $lastnewsId['id'];
			$newsMultiArray['news_title']    =   $this->_request->getPost('news_title');
			$newsMultiArray['news_content']  =   $this->_request->getPost('news');
			$newsMultiArray['lang_id']       =   $this->getDefaultLanguage();
			
			$fitnessNewsMulti->addData($newsMultiArray);
			
			
			$this->_redirect('/admin/listnews'); 
		}
     	
     }
	 
	 
	  /*
	  * created by Lekha
	  * date : 6/8/2012
	  * method to add category
	  *
	  */
     function addcategoryAction()
     {
     	$fitnessCategory     = new FitnessNewsCategory();
     	$fitnessCatMulti     = new FitnessNewsCategoryMultilang();
     	
     	$newsArray        =  array();
     	$newsMultiArray   =  array();
     	
     	if ($this->_request->isPost())    
		{
			$newsArray['name']               =   $this->_request->getPost('category');	
			
			$fitnessCategory->addData($newsArray);
			
			$lastnewsId                      =   $fitnessCategory->getLastCategoryId();
			
			$newsMultiArray['category_id']       =   $lastnewsId['id'];
			$newsMultiArray['name']  =   $this->_request->getPost('category');
			$newsMultiArray['lang_id']       =   $this->getDefaultLanguage();
			
			
			
			$fitnessCatMulti->addData($newsMultiArray);
			
			
			$this->_redirect('/admin/listcategory'); 
		}
     	
     }
     
     /*
	  * created by Lekha
	  * date : 25/4/2012
	  * method to list website pages
	  *
	  */
     function listpagesAction()
     {
     	$fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();
     	
     	$fitnessLanguage           =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
     	
     	$this->view->listpages  = $fitnessPagesMulti->listPages($defaultLanguage);
     	
     }
     
     
     
     
     function editpageAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->pageId   =   $this->_request->getParam('pageId');
        
        
        
        $fitnessPages     = new FitnessWebsitePages();
     	$fitnessPagesMulti= new FitnessWebsitePagesMultilang();
  		$pageArray           =  array();
  		$pageMultilangData   =  array();
  		
  		
  		$this->view->fitnessMultiPageData = $fitnessPagesMulti->getPage($this->_request->getParam('pageId'),$this->_request->getParam('langId'));
		if($this->_request->getParam('langId') != '1')
		{
			$this->view->fitnessTranslation = $fitnessPagesMulti->getPage($this->_request->getParam('pageId'),1);
		}
  		
  		if ($this->_request->isPost())    
		{
		
  	       
  	       $pageMultilangData['page_id']       =   $this->_request->getPost('pageId');
  	       $pageMultilangData['lang_id']       =   $this->_request->getPost('langId');
  	       $pageMultilangData['page_title']    =   $this->_request->getPost('page_title');
  	       $pageMultilangData['page_content']  =   $this->_request->getPost('page_content');
		   $pageMultilangData['page_content_sub']  =   $this->_request->getPost('page_content_sub');
		    $pageMultilangData['page_section1']  =   $this->_request->getPost('page_section1');
			$pageMultilangData['page_section2']  =   $this->_request->getPost('page_section2');
			$pageMultilangData['page_section1_title']  =   $this->_request->getPost('page_section1_title');
			$pageMultilangData['page_section2_title']  =   $this->_request->getPost('page_section2_title');
  	       
  	       
  	       
  	       
  	       $checkRecords                        =   $fitnessPagesMulti->getLangRecord($this->_request->getParam('pageId'),$this->_request->getParam('langId'));
  	      
  	       if($checkRecords['count'] > 0)
  	       {
  	       	  $fitnessPagesMulti->update($pageMultilangData, array(
				'page_id = ?' => $this->_request->getPost('pageId'),
				'lang_id = ?' => $this->_request->getPost('langId')
				));
  	       }
  	       else 
  	       {
  	       	$fitnessPagesMulti->addData($pageMultilangData);
  	       }
  	       
  	       
  	       $this->_redirect('/admin/listpages');
  	       
		}
     }
     
     
 /*
  * created by Lekha
  * date : 27/2/2012
  * method to set a language as default
  * @param : language id
  */
     public function setdefaultlanguageAction()
     {
     	$fitnessLanguage   =  new FitnessLanguages();
     	$langauageId                      =   $this->_request->getParam('languageId');
     	
     	$fitnessLanguage->setDefaultLanguage($langauageId);
     	$this->_redirect('/admin/listlanguages'); 
     	 
     	 
     }
     
     
     
     
 /*
  * created by Lekha
  * date : 29/2/2012
  * method to set a membership plan as active or inactive
  * @param : language id
  */
     public function setstatusplanAction()
     {
     	$fitnessPlan                 =  new FitnessMembershipPlans();
     	$planId                      =   $this->_request->getParam('planId');
     	$planStatus                  =   $this->_request->getParam('planStatus');
     	
     	$fitnessPlan->setStatusPlan($planId,$planStatus);
     	$this->_redirect('/admin/listplans'); 
     }
     
     
     
 /*
  * created by Lekha
  * date : 27/2/2012
  * method to set a language as active or inactive
  * @param : language id
  */
     public function setstatuslanguageAction()
     {
     	$fitnessLanguage                  =  new FitnessLanguages();
     	$langauageId                      =   $this->_request->getParam('languageId');
     	$langauageStatus                  =   $this->_request->getParam('langStatus');
     	
     	$fitnessLanguage->setStatusLanguage($langauageId,$langauageStatus);
     	$this->_redirect('/admin/listlanguages'); 
     }
     
     
     
      /*
	  * created by Lekha
	  * date : 27/2/2012
	  * method to list out all the equipments
	  * 
	  */
     public function listequipmentsAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus   = $this->isLoggedIn();
        $fitnessEquipments         =  new FitnessEquipments();
        $fitnessLanguage           =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->equipData     = $fitnessEquipments->listEquipments();
     }
     
     
     
	 /*
	  * created by Lekha
	  * date : 27/2/2012
	  * method to list out all the muscles
	  * 
	  */
     public function listmusclesAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus = $this->isLoggedIn();
        $fitnessMuscles          =  new FitnessBodyAreas();
        $fitnessLanguage         =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->muscleData    = $fitnessMuscles->listMuscles();
     }
     
     /*
	  * created by Lekha
	  * date : 23/7/2012
	  * method to list out all the news
	  * 
	  */
     public function listnewsAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus   = $this->isLoggedIn();
        $fitnessNews               =  new FitnessNews();
        $fitnessLanguage           =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->newsData      = $fitnessNews->listNews();
     }
	 
	 
	 
	 /*
	  * created by Lekha
	  * date : 6/8/2012
	  * method to list out all the news
	  * 
	  */
     public function listcategoryAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus   = $this->isLoggedIn();
        $fitnessCategory           =  new FitnessNewsCategory();
        $fitnessLanguage           =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->catData      = $fitnessCategory->listNewsCategory();
     }
     
      /*
	  * created by Lekha
	  * date : 29/2/2012
	  * method to list out all the interests
	  * 
	  */
     public function listinterestsAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus   = $this->isLoggedIn();
        $fitnessInterests          =  new FitnessInterests();
        $fitnessLanguage           =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->interestsData = $fitnessInterests->listInterests();
     }
     
     
     
      /*
	  * created by Lekha
	  * date : 29/2/2012
	  * method to list out all the targets
	  * 
	  */
     public function listtargetsAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus   = $this->isLoggedIn();
        $fitnessTargets            =  new FitnessTargets();
        $fitnessLanguage           =  new FitnessLanguages();
        
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->targetData    = $fitnessTargets->listTargets();
     }
     
     
     
      /*
	  * created by Lekha
	  * date : 29/2/2012
	  * method to list out all the membership plans
	  * 
	  */
     public function listplansAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus   = $this->isLoggedIn();
        $fitnessPlans              =  new FitnessMembershipPlans();
        $fitnessLanguage           =  new FitnessLanguages();
        
        
                
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
        $this->view->planData      = $fitnessPlans->listPlans($defaultLanguage);
     }
     
     
     
     /*
	  * created by Lekha
	  * date : 5/3/2012
	  * method to list out all the workouts based on the exercises
	  * 
	  */
     public function listworksAction()
     {
     	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus      = $this->isLoggedIn();
        $fitnessWorkouts              =  new FitnessWorkouts();
        
        $fitnessLanguage           =  new FitnessLanguages();
        
        
                
        $defaultLanguage           = $this->getDefaultLanguage();
        $this->view->languageList  = $fitnessLanguage->getLanguages();
       
                
       
        $this->view->workoutData      = $fitnessWorkouts->listWorkoutsBackend($defaultLanguage);
     }
     
	 
	 
	  /**
  * created by Lekha
  * date : 16/10/2012
  *  method for uploading images
  * 
  **/
  
  function cropimageworkoutAction()
  {
  	$this->_helper->layout()->disableLayout();
	$fitnessWorkouts   = new FitnessWorkouts();
	
	$this->view->exerciseId   = $this->_request->getParam('exercise');
	
	$upload_dir = "./public/images/exercises"; 				// The directory for the images to be saved in
	$upload_path = $upload_dir."/";				// The path to where the image will be saved
	$upload_dir_thumb = "./public/images/exercises/thumbs"; 				// The directory for the images to be saved in
	$upload_path_thumb = $upload_dir_thumb."/";				// The path to where the image will be saved
	
	$upload_dir_mobile = "./public/images/exercises/thumbs/mobile"; 				// The directory for the images to be saved in
	$upload_path_mobile = $upload_dir_mobile."/";				// The path to where the image will be saved
	
	
	$max_file = "3"; 							// Maximum file size in MB
	$max_width = "150";	
	$max_width2 = "90";	
	
	
	
	// Only one of these image types should be allowed for upload
	$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
	$allowed_image_ext = array_unique($allowed_image_types); // do not change this
	$image_ext = "";	// initialise variable, do not change this.
	foreach ($allowed_image_ext as $mime_type => $ext) {
	    $image_ext.= strtoupper($ext)." ";
	}



				// Height of thumbnail image
	
	if ($this->_request->isPost()) 
	{
	    //main image upload
		if ($_FILES['image']['name']!="") 
		{ 
		   
	       //Get the file information
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	$this->view->exerciseId   = $this->_request->getPost('exerciseId');
	
	//update the filesnames with the exercise Id
	
	
	//Image Locations
	$large_image_location = $upload_path.$userfile_name;
	$thumb_image_location = $upload_path_thumb.$userfile_name;	
	
	
			//Only process if the file is a JPG, PNG or GIF and below the allowed limit
			if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
				
				foreach ($allowed_image_types as $mime_type => $ext) {
					//loop through the specified image types and if they match the extension then break out
					//everything is ok so go and check file size
					if($file_ext==$ext && $userfile_type==$mime_type){
						$error = "";
						break;
					}else{
						$error = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
					}
				}
				//check if the file size is above the allowed limit
				if ($userfile_size > ($max_file*1048576)) {
					$error.= "Images must be under ".$max_file."MB in size";
				}
				
			}else{
				$error= "Select an image for upload";
			}
			
	//Everything is ok, so we can upload the image.
	if (strlen($error)==0){
		
		if (($_FILES['image']['name']!="")){
												
						
						
						
						move_uploaded_file($userfile_tmp, $large_image_location);
						chmod($large_image_location, 0777);
						copy($large_image_location, $thumb_image_location);
						chmod($thumb_image_location, 0777);
						
						$width = $this->getWidth($large_image_location);
						$height = $this->getHeight($large_image_location);
						
						
						//Scale the image if it is greater than the width set above
						if ($width > $max_width2){
							$scale = $max_width2/$width;
							$uploaded = $this->resizeImage($thumb_image_location,$width,$height,$scale);
						}else{
							$scale = 1;
							$uploaded = $this->resizeImage($thumb_image_location,$width,$height,$scale);
						}
						//Scale the image if it is greater than the width set above
						if ($width > $max_width){
							$scale = $max_width/$width;
							$uploaded = $this->resizeImage($large_image_location,$width,$height,$scale);
						}else{
							$scale = 1;
							$uploaded = $this->resizeImage($large_image_location,$width,$height,$scale);
						}
						
						
						
						
		}
		if (file_exists($large_image_location))
			 {
			 	$this->view->largeImage   = "yes";
				$this->view->largeFile          = $userfile_name;
				$this->view->largewidth = $this->getWidth($large_image_location);
				$this->view->largeheight = $this->getHeight($large_image_location);
				$this->view->ext         = $file_ext;
				
			 }
			 if (file_exists($thumb_image_location))
			 {
			 	$this->view->thumbImage   = "yes";
				$this->view->thumbFile          = $thumb_image_name.".".$file_ext;
			 }
	  }
	}
	
	
	$workoutArray  = array();
	$workoutArray['work_image_list']  = $userfile_name;
	$where  = " id='".$this->_request->getPost('exerciseId')."'";
	$fitnessWorkouts->update($workoutArray,$where);
	
	
	 }
	 
  }
     
     
     /*
	  * created by Lekha
	  * date : 5/3/2012
	  * method to add workouts based on the exercises
	  * 
	  */
     public function addworkAction()
     {
	     	if($this->isLoggedIn() == 0) 
	        {
	        	$this->_redirect('/admin/index');
	        }
	        
	        $this->view->loginStatus      = $this->isLoggedIn();
	        $fitnessWorkouts              =  new FitnessWorkouts();
	        $fitnessWorkoutsMulti         =  new FitnessWorkoutsMultilang();
	        $fitnessExercises             =  new FitnessExerciseGeneral();
	        $fitnessExercisesMulti        =  new FitnessExerciseGeneralMultilang();
	        $fitnessTimeframes            =  new FitnessWorkoutTimeframes();
	        $fitnessMembers               =  new FitnessUserMembership();
            $fitnessUnlockedWorkouts      = new FitnessUserWorkoutsUnlocked();
			$fitnessUser                  =  new FitnessUserGeneral();
	        
	        $listExercises                = $fitnessExercises->selectRecordsWorkout();
	        
	       
	        
	        $exerciseArray                = array();
	        
	        
	        $i=0;
	        foreach($listExercises as $exercise)
	        {
	        	
	        	$exerciseArray[$i]["exercise_id"]   = $exercise['workout_id'];
	            $exerciseNameArray                  = $fitnessExercisesMulti->getWorkoutName($exercise['workout_id'],$this->getDefaultLanguage());
	            $exerciseArray[$i]["exercise_name"] = $exerciseNameArray['workout_name'];
	           
	        	
	        	
	        $i=$i+1;
	        }
	        $exerciseArray =  $this->array_sort_by_column($exerciseArray, 'exercise_name');
	        $this->view->listExercises    =  $exerciseArray;
	        $this->view->listtimeframes   =  $fitnessTimeframes->listTimeframes();
	        
	        $workArray                    = array();
	        $workMultiArray               = array();
	       
	        if ($this->_request->isPost())    
			{
			   //$workImage                      = $_FILES["work_image"]["name"];
		    
			   $workArray['work_name']         =   $this->_request->getPost('work_name');
			   $workArray['work_image_list']   =   "";
			   $workArray['work_exercises']    =   rtrim($this->_request->getPost("exerciseslist"),",");
			  
			   $workArray['work_duration']     =   "";
			   $workArray['work_recovery_time']        =   "";	
			   $workArray['work_recovery_interval']    =   "";			  
			   
			   $workArray['work_filter']        =   rtrim($this->_request->getPost("filterlist"),",");
			   $workArray['description_small']        =   $this->_request->getPost("description_small");
			   $workArray['description_big']        =   $this->_request->getPost("description_big");
			   $workArray['work_level']        =   $this->_request->getPost('work_level');
			   
			  
		  	   			  // print_r($workArray);exit;
			   $fitnessWorkouts->addData($workArray);
			   
			   
			   $workID                         = $fitnessWorkouts->getLastWorkId();
			  
			   
			   
			   
			   $workMultiArray['work_name']    =   $this->_request->getPost('work_name');
			   $workMultiArray['work_id']      =   $workID['id'];
			   $workMultiArray['lang_id']      =   $this->getDefaultLanguage();
			   $workMultiArray['description_small']        =   $this->_request->getPost("description_small");
			   $workMultiArray['description_big']        =   $this->_request->getPost("description_big");
			  
			   $fitnessWorkoutsMulti->addData($workMultiArray); 
			
			
			//check if members exists and add the workout to their unlocked list
			
			$memberData   =  $fitnessMembers->getAllMemberIds();
			$unlockedArray= array();
			
			foreach($memberData as $userId)
			{ 
				$unlockedArray['user_id'] = $userId['user_id'];
				$unlockedArray['workout_id'] = $workID['id'];
				$unlockedArray['workout_purchase_status'] = "true";
				$unlockedArray['unlocked_date'] = date('Y-m-d');
				$unlockedArray['unlocked_status'] = 1;
				
				$fitnessUnlockedWorkouts->addData($unlockedArray);
				
			}
			
			   
		    $this->_redirect('/admin/listworks');
			}
                
       
        
     }
     
     function array_sort_by_column($arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
	$sort_col_id = array();
	$i=0;
    foreach ($arr as $key=> $row) {
        $sort_col[$i]['exercise_name'] = $row[$col];
		$sort_col[$i]['exercise_id'] = $row['exercise_id'];
    $i=$i+1;}
array_multisort($sort_col, $dir, $arr); 

    return $sort_col;
}
     
   /*
    * created by Lekha
    * date : 5/3/2012
	* method for editing workouts
	* 
	*/
  function editworkAction()
  {
  	
  	 		
  	if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->langId     =   $this->_request->getParam('langId');
        $this->view->workId     =   $this->_request->getParam('workId');
        
        
        
            $fitnessWorkouts              =  new FitnessWorkouts();
	        $fitnessWorkoutsMulti         =  new FitnessWorkoutsMultilang();
	        $fitnessExercises             =  new FitnessExerciseGeneral();
	        $fitnessExercisesMulti        =  new FitnessExerciseGeneralMultilang();
	        $fitnessTimeframes            =  new FitnessWorkoutTimeframes();
	        $workoutDocuments             =  new FitnessExerciseDocuments();
	        $workoutDocumentsMulti        =  new FitnessExerciseDocumentsMultilang();
	        $fitnessDevices     =   new AppleDevices();
		    $fitnessKeys        =   new FitnessAndroidKey();
	        
	        $adminData      =  new FitnessAdminAccounts();
    
		    $sess           =  new Zend_Session_Namespace('AdminSession');
		    $adminType      = $adminData->getTypeByUsername($sess->username);	
	        $this->view->adminType    = $adminType['admin_user_type'];
			
	        $listExercises                = $fitnessExercises->selectRecordsWorkout();
	        
	       
	        
	        $exerciseArray                = array();
	        
	        $i=0;
	        foreach($listExercises as $exercise)
	        {
	        	
	        	$exerciseArray[$i]["exercise_id"]   = $exercise['workout_id'];
	            $exerciseNameArray                  = $fitnessExercisesMulti->getWorkoutName($exercise['workout_id'],$this->getDefaultLanguage());
	            $exerciseArray[$i]["exercise_name"] = $exerciseNameArray['workout_name'];
	           
	        	
	        	
	        $i=$i+1;
	        }
	       $exerciseArray =  $this->array_sort_by_column($exerciseArray, 'exercise_name');
		  
	        $this->view->listExercises    =  $exerciseArray;
	        $this->view->listtimeframes   =  $fitnessTimeframes->listTimeframes();
	        
	        
	        $this->view->workData               =  $fitnessWorkouts->getWorkout($this->_request->getParam('workId'));
	        
	        
	        
	         $exerciseArray                      =  explode(",",$this->view->workData['work_exercises']);
	         
	         $this->view->exercises              =  $exerciseArray;
	        //$this->view->workoutDocuments       = $workoutDocumentsData;
  	        //$this->view->workoutDocumentsMulti  = $workoutDocumentsMultiData;
	        
	        
	         if($this->_request->getParam('langId') != "")
	         {
	         	$workmultiData            =  $fitnessWorkoutsMulti->getWorks($this->_request->getParam('workId'),$this->_request->getParam('langId'));
				if($this->_request->getParam('langId') == 1)
				{
					$this->view->workmultiDataTranslate            =  $fitnessWorkoutsMulti->getWorks($this->_request->getParam('workId'),2);
					
				}
				
				$this->view->workmulti    = $workmultiData;
	         	$this->view->workname     =  $workmultiData['work_name'];
	         	
	         }
	         else 
	         {
			 $workmultiData            =  $fitnessWorkoutsMulti->getWorks($this->_request->getParam('workId'),$this->getDefaultLanguage());
				
				$this->view->workmulti    = $workmultiData;
	         	$this->view->workname     = $this->view->workData['work_name'];
	         }
	        
	        
	        $this->view->exercises        =   explode(",",$this->view->workData['work_exercises']);
			$d=0;
			foreach($this->view->exercises as $exerciseID)
			{
			    $exerciseDetails                      = $fitnessExercisesMulti->getWorkoutName($exerciseID,$this->getDefaultLanguage());
				$sortExerciseArray[$d]['name']        = $exerciseDetails['workout_name'];
				$sortExerciseArray[$d]['id']          = $exerciseID;
				
			$d=$d+1;}
			
			if($this->view->workData['work_exercises_order'] !="")
			{
				$this->view->sortcomplete       =   explode(",",$this->view->workData['work_exercises_order']);
				$sortDiff                       =    array_diff($this->view->exercises,$this->view->sortcomplete);
				$reversesortDiff                =    array_diff($this->view->sortcomplete,$this->view->exercises);
				//print_r($this->view->exercises);echo "<br/>";
				//print_r($this->view->sortcomplete);echo "<br/>";
				
				//remove deleted exercises
				foreach($this->view->sortcomplete as $key=>$exerciseID)
				{
				      if(in_array($exerciseID,$reversesortDiff))
					  {
					  	unset($this->view->sortcomplete[$key]);
					  }
				}
				
				$j=0;
				foreach($this->view->sortcomplete as $exerciseID)
				{
				    $exerciseDetails                      = $fitnessExercisesMulti->getWorkoutName($exerciseID,$this->getDefaultLanguage());
					$sortCompleteExerciseArray[$j]['name']        = $exerciseDetails['workout_name'];
					$sortCompleteExerciseArray[$j]['id']          = $exerciseID;
					
				$j=$j+1;}
			}
			
			$r=0;
			foreach($sortDiff as $diff)
			{
				    $exerciseDiffDetails                          = $fitnessExercisesMulti->getWorkoutName($diff,$this->getDefaultLanguage());
					$sortDiffExerciseArray[$r]['name']        = $exerciseDiffDetails['workout_name'];
					$sortDiffExerciseArray[$r]['id']          = $diff;
			$r=$r+1;}
			
			$this->view->sortExercises    = $sortExerciseArray;
			$this->view->sortCompleteExercises    = $sortCompleteExerciseArray;
			$this->view->sortDiffExercises        = $sortDiffExerciseArray;
	        
	        $workArray                    = array();
	        $workMultiArray               = array();    
	        
	        
	        
	        
  		if ($this->_request->isPost())    
		{
		
  	           $workId                         =   $this->_request->getPost('work_id');	       
  	           if($this->_request->getPost('langId') == "")
		  	       {
		  	       	   //$workImage                      = $_FILES["work_image"]["name"];
		  	       	   
		  	           $workArray['work_name']         =   $this->_request->getPost('work_name');
		  	           
		  	           $workArray['work_image_list']   =   $this->_request->getPost('work_image');
		  	           
		  	           
		  	           $workArray['work_exercises']    =   rtrim($this->_request->getPost("exerciseslist",","));
		  	           $workArray['work_duration']     =   $this->_request->getPost('work_duration');
		  	           $workArray['work_recovery_time']        =   $this->_request->getPost('work_recovery_time');	
					   $workArray['work_recovery_interval']    =   $this->_request->getPost('work_recovery_interval');			  
					   
					   $workArray['work_filter']        =   rtrim($this->_request->getPost("filterlist"),",");
					   $workArray['description_small']     =   $this->_request->getPost("description_small");
					   $workArray['description_big']        =   $this->_request->getPost("description_big");
		  	           
		  	           $workArray['work_level']        =   $this->_request->getPost('work_level');
					  
					   if($this->_request->getPost('activestatus') !="")
					   {
					   	 $workArray['isactive']          =   $this->_request->getPost('activestatus');
					   }
		  	          
					  if($this->_request->getPost('isfree') !="")
					   {
					   	 $workArray['status']          =   $this->_request->getPost('isfree');
					   }
		  	           
		  	           
		  	        				   
					   
					   $where                          =  "id='".$workId."'";
					   $fitnessWorkouts->update($workArray,$where);
					   
					    /*if($workImage!="")
						    {
						    move_uploaded_file($_FILES['work_image']['tmp_name'],'./public/images/exercises/'.$workImage);
						    $this->createThumb('./public/images/exercises/'.$workImage,'./public/images/exercises/thumbs/'.$workImage,100);
							$this->createThumbMobile('./public/images/exercises/'.$workImage,'./public/images/exercises/thumbs/mobile/'.$workImage,100);
						    
						    }*/
							
							if($this->_request->getPost('isactive') == 1)
							{
								//update the workout change table
								$fitnessUpdateChange    = new FitnessWorkoutChanges();
								$changeArray            = array();
								
								//check if record exists for today's date
								
								$currentDate    = date('Y-m-d');
								
								$checkRecord    =  $fitnessUpdateChange->getRecordByDate($currentDate);
								
								if($checkRecord['count'] < 1)
								{
									
									$changeArray['status']   = 1;
									$changeArray['change_date']   = $currentDate;
									
									$fitnessUpdateChange->addData($changeArray);
								}
								
								
								/*$devices      =  $fitnessDevices->getAllDevices();
			
								$keys         =  $fitnessKeys->getAllKeys();
								
								$message      =  "The workout ".$this->_request->getPost('work_name')." has been updated in Fitness4.me";
								foreach($devices as $device)
								{
								    if($device['devicetoken'] !="")
									{
										//$this->sendNotification('lekha',$message,$device['devicetoken'],4);
									}
									
								}
								
								foreach($keys as $key)
								{
								    if($key['android_key'] !="")
									{
										//$this->sentandroid($key['android_key'],$message,4);
										
									}
									
									
								}*/
							}
						    
		  	       }

		  	       if($this->_request->getPost('langId') != "")
		  	       {    
				  	       $checkRecords                       =   $fitnessWorkoutsMulti->getLangRecord($this->_request->getPost('work_id'),$this->_request->getPost('langId'));
				  	          
				  	       $workMultiArray['work_id']          =   $workId;
				  	       $workMultiArray['lang_id']          =   $this->_request->getPost('langId');
				  	       $workMultiArray['work_name']        =   $this->_request->getPost('work_name');
				  	       $workMultiArray['description_small']        =   $this->_request->getPost("description_small");
					       $workMultiArray['description_big']        =   $this->_request->getPost("description_big");
				  	       
				  	       if($checkRecords['count'] > 0)
				  	       {
				  	       	  $fitnessWorkoutsMulti->update($workMultiArray, array(
								'work_id = ?' => $this->_request->getPost('work_id'),
								'lang_id = ?' => $this->_request->getPost('langId')
								));
				  	       }
				  	       else 
				  	       {
				  	       	$fitnessWorkoutsMulti->addData($workMultiArray);
				  	       }
		  	       }
		  	       
		  	       
		  	   
  	       
  	       
  	       $this->_redirect('/admin/listworks');
  	       
		}
    
  }
  
  
  
     /*
	  * created by Lekha
	  * date : 6/19/2012
	  * Amazon payment method
	  * 
	  */
	   public function listpaymentsAction()
	  {
	  
	    if($this->isLoggedIn() == 0) 
        {
        	$this->_redirect('/admin/index');
        }
        
        $this->view->loginStatus      = $this->isLoggedIn();
		
		
	  	$fitnessPyaments   =  new FitnessWebsitePaymentDetails();
		$fitnessAppPayments=  new FitnessAppPaymentDetails();
		
		
		$fitnessWorkouts   =  new FitnessWorkouts();
		$fitnessUser       =  new FitnessUserGeneral();
		$paymentRecord     =  array();
		$apppaymentRecord     =  array();
		
		$payments          =  $fitnessPyaments->getAllPayments();
		$apppayments       =  $fitnessAppPayments->getAllPayments();
		
		$i=0;
		foreach($payments as $payment)
		{
			$userDetails                = $fitnessUser->getUser($payment['user_id']);
			$paymentRecord[$i]['user']  = $userDetails['user_first_name']. " " . $userDetails['user_surname'];
			if($payment['payment_method'] == 1)
			$paymentRecord[$i]['payment_method']  = "Paypal";
			else
			{
				$paymentRecord[$i]['payment_method']  = "Google";
			}
			
			$paymentRecord[$i]['status']  = $payment['payment_status'];
			$paymentRecord[$i]['type']    = $payment['payment_type_workouts'];
			$paymentRecord[$i]['amount']  = $payment['payment_amount'];
			$paymentRecord[$i]['trxn']    = $payment['payment_transaction_id'];
			$paymentRecord[$i]['date']    = $payment['payment_date'];
			
			
		$i=$i+1;
		}
		
		
		$i=0;
		foreach($apppayments as $apppayment)
		{
			$userDetails                = $fitnessUser->getUser($apppayment['user_id']);
			$apppaymentRecord[$i]['user']  = $userDetails['user_first_name']. " " . $userDetails['user_surname'];
			if($apppayment['payment_method'] == 1)
			$apppaymentRecord[$i]['payment_method']  = "Paypal";
			
			$apppaymentRecord[$i]['status']  = $apppayment['payment_status'];
			
			$apppaymentRecord[$i]['amount']  = $apppayment['amount'];
			$apppaymentRecord[$i]['trxn']    = $apppayment['transaction_id'];
			$apppaymentRecord[$i]['date']    = $apppayment['payment_date'];
			
			
		$i=$i+1;
		}
		
		//get iphone purchases data
		
		$iphonePurchase   =  $fitnessUser->getIphonePremiumUsers();
		$androidPurchase   =  $fitnessUser->getAndroidPremiumUsers();
		
		$iphoneCount      =  count($iphonePurchase);
		
		$this->view->payments  = $paymentRecord;
		$this->view->apppayments  = $apppaymentRecord;
		$this->view->iphonePurchase = $iphonePurchase;
		$this->view->androidPurchase = $androidPurchase;
		
	  }
  
     
	 
	  /*
	  * created by Lekha
	  * date : 26/6/2012
	  *method to store promotion codes
	  * 
	  */
     public function addcodesAction()
     {
     	$fitnessPromotion     = new FitnessPromotionCodes();
		$promotionArray       =   array();
		
		if($this->_request->getPost('code_name') != "")
		{
			$promotionArray['promotion_code']  = $this->_request->getPost('code_name');
			$promotionArray['price_reduction']  = $this->_request->getPost('price_reduction');
			if($this->_request->getPost('code_status')  !="")
			{
				$promotionArray['status']  = $this->_request->getPost('code_status');
			}
			
			$fitnessPromotion->addData($promotionArray);
			$this->_redirect('/admin/listcodes');
		}
		
     }
     
	 
	  /*
	  * created by Lekha
	  * date : 26/6/2012
	  *method to list promotion codes
	  * 
	  */
     public function listcodesAction()
     {
     	$fitnessPromotion     = new FitnessPromotionCodes();
		$promotionArray       =   array();
		
		$this->view->promotioncodes       =  $fitnessPromotion->getCodes();
		
     }
	 
	 
	  /*
	  * created by Lekha
	  * date : 26/6/2012
	  *method to list promotion codes
	  * 
	  */
     public function userstatsAction()
     {
     	$fitnessPromotion     = new FitnessPromotionCodes();
		$fitnessUser          = new FitnessUserGeneral();
		$promotionUsers       = new FitnessPromotionUsers();
		
		
		//get the number of users registered through iphone and android
		
		$usersIphone          =  $fitnessUser->getIphoneUserCount();
		$usersAndroid         =  $fitnessUser->getAndroidUserCount();
		
		//get the users from website
		$usersWeb             =  $fitnessUser->getUserCount();
		
		foreach($usersWeb as $web)
		{
			$usersweb[]   = $web['user_id'];
		}
		
		//get users who have used promotion codes
		$userspromo = array();
		$usersPromotion       =  $promotionUsers->getPromotionUsers();
		foreach($usersPromotion as $promo)
		{
			$userspromo[]   = $promo['user_id'];
		}
		
		$usersWebFinal      =  array_diff($usersweb,$userspromo);
		
		//get all promotion ids
		$promotionsIds      =  $fitnessPromotion->getCodes();
		
		$i=0;
		foreach($promotionsIds as $ids)
		{
			$promotionsUsers[$i]['promotion_name']  = $ids['promotion_code'];
			$promoUsers                             = $promotionUsers->getUsersByCode($ids['id']);
			
			$promotionsUsers[$i]['promotion_users']  = $promoUsers[0]['count'];
			
		$i=$i+1;}
		
		
		$this->view->usersIphone       =  $usersIphone['count'];
		$this->view->usersAndroid      =  $usersAndroid['count'];
		$this->view->usersWeb          =  count($usersWebFinal);
		$this->view->promotionData     =  $promotionsUsers;
     }
	 
	 
	  /*
	  * created by Lekha
	  * date : 26/6/2012
	  *method to edit a promotion code
	  * @param   promotion code id
	  */
     public function editcodeAction()
     {
     	$fitnessPromotion     = new FitnessPromotionCodes();
		$promotionArray       =   array();
		
		
		$this->view->codeId   =   $this->_request->getParam('codeId');
		
		
		$this->view->promotioncode       =  $fitnessPromotion->getCodeById($this->_request->getParam('codeId'));
		
		if($this->_request->getPost('codeId') != "")
		{
		     $promotionArray['promotion_code']  = $this->_request->getPost('code_name');
				$promotionArray['price_reduction']  = $this->_request->getPost('price_reduction');
				
				
					$promotionArray['status']  = $this->_request->getPost('code_status');
				
				
				 $where  =  "id='".$this->_request->getPost('codeId')."'";
			     $fitnessPromotion->update($promotionArray,$where);
				 
				 $this->_redirect('/admin/listcodes');
		}
		
     }
     
	  function removenewsAction()
		  {
		  	$delete_id = $this->_request->getParam('delete');
		  
		  	
		  	if($delete_id != "")
		  	{
		  		$fitnessNews     = new FitnessNews();
		  		
		  		
		        $fitnessNews->find($delete_id)->current()->delete();
		        $this->_redirect('/admin/listnews');
		        
		        
		  	}
		  }
	 
	  function removecodeAction()
		  {
		  	$delete_id = $this->_request->getParam('delete');
		  
		  	
		  	if($delete_id != "")
		  	{
		  		$fitnessPromotion     = new FitnessPromotionCodes();
		  		
		  		
		        $fitnessPromotion->find($delete_id)->current()->delete();
		        $this->_redirect('/admin/listcodes');
		        
		        
		  	}
		  }
     
     /*
	  * created by Lekha
	  * date : 2/1/2012
	  * Amazon payment method
	  * 
	  */
     public function paymentAction()
     {
     	
     }
     
     
     
     /*
	  * created by Lekha
	  * date : 2/1/2012
	  * Paypal payment method
	  * 
	  */
     public function paymentpaypalAction()
     {


      }
     
      
       /*
	  * created by Lekha
	  * date : 2/1/2012
	  * Google payment method
	  * 
	  */
     public function paymentgoogleAction()
     {


      }
     
     
     /*
	  * created by Lekha
	  * date : 27/2/2012
	  * method to get the default language selected for backend
	  * 
	  */
     public function getDefaultLanguage()
     {
     	$fitnessLanguage   = new FitnessLanguages();
     	
        $defaultLang       = $fitnessLanguage->getDefaultLanguage();
        return $defaultLang['language_id'];
     }
     
     
     
 /*
  * created by Lekha
  * date : 23/2/2012
  * method to check the login session of user
  * 
  */
     public function isLoggedIn()
	 {
		If (Zend_Session::namespaceIsset('AdminSession')) 
		{

          return 1;

        }
        else 
        {
        	return 0;
        }
	}
	
	
	
	
 /*
  * created by Lekha
  * date : 23/2/2012
  * method to logout and clear the user session
  * 
  */
	public function logoutAction()
	{
		Zend_Session::destroy();
		 $this->_redirect('/admin/index');
	}
	
	
  /*
  * created by Lekha
  * date : 24/4/2012
  * method to create thumbnails for uploaded images
  * 
  */
	function createThumb($src,$dest,$desired_width)
		{
		
		
		$max_h = 150;
        $max_w = 100;
		
		if ($max_w > $max_h)
    $max_h = $max_w;
else if ($max_h > $max_w)
    $max_w = $max_h;
	
	
	 $size = getImageSize($src);
  $old_w = $size[0];
  $old_h = $size[1];
	
	if ($old_w > $old_h) {
    $nw=$max_w;
    $nh=$old_h*($max_w/$old_w);
}
if ($old_w < $old_h) {
    $nw=$old_w*($max_h/$old_h);
    $nh=$max_h;
}
if ($old_w == $old_h) {
    $nw=$max_w;
    $nh=$max_h;
}
		 
  
  // Building the intermediate resized thumbnail

  $resimage = imagecreatefromjpeg($src); 
  $newimage = imagecreatetruecolor($nw, $nh);  // use alternate function if not installed
  
 
  imageCopyResampled($newimage, $resimage,0,0,0,0,$nw, $nh, $old_w, $old_h);
  
  // Making the final cropped thumbnail
  
  $viewimage = imagecreatetruecolor($max_w, $max_h);
   $bg = imagecolorallocate ( $viewimage, 255, 255, 255 );
  imagefill ( $viewimage, 0, 0, $bg );
  
  imagecopy($viewimage, $newimage, 0, 0, 0, 0, $nw, $nh);
  
  // saving
  imageJpeg($viewimage, $dest, 85);
		  
		}



  /*
  * created by Lekha
  * date : 24/4/2012
  * method to create thumbnails for uploaded images
  * 
  */
	function createThumbMobile($src,$dest,$desired_width)
		{
		
		
		$max_h = 68;
        $max_w = 80;
		
		if ($max_w > $max_h)
    $max_h = $max_w;
else if ($max_h > $max_w)
    $max_w = $max_h;
	
	
	 $size = getImageSize($src);
  $old_w = $size[0];
  $old_h = $size[1];
	
	if ($old_w > $old_h) {
    $nw=$max_w;
    $nh=$old_h*($max_w/$old_w);
}
if ($old_w < $old_h) {
    $nw=$old_w*($max_h/$old_h);
    $nh=$max_h;
}
if ($old_w == $old_h) {
    $nw=$max_w;
    $nh=$max_h;
}
		 
  
  // Building the intermediate resized thumbnail

  $resimage = imagecreatefromjpeg($src); 
  $newimage = imagecreatetruecolor($nw, $nh);  // use alternate function if not installed
  
 
  imageCopyResampled($newimage, $resimage,0,0,0,0,$nw, $nh, $old_w, $old_h);
  
  // Making the final cropped thumbnail
  
  $viewimage = imagecreatetruecolor($max_w, $max_h);
   $bg = imagecolorallocate ( $viewimage, 255, 255, 255 );
  imagefill ( $viewimage, 0, 0, $bg );
  
  imagecopy($viewimage, $newimage, 0, 0, 0, 0, $nw, $nh);
  
  // saving
  imageJpeg($viewimage, $dest, 85);
		  
		}
		
		
 /*
	* created by Lekha
    * date : 22/6/2012
	* method to send notification to iphone users
	*/		
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
						//echo json_encode($messageStatus);
						$message = array();
        	}
        }
		
		
		 /*
	* created by Lekha
    * date : 22/6/2012
	* method to check authentication
	*/
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


    /*
	* created by Lekha
    * date : 22/6/2012
	* method to send notification to android users
	*/			
  function sentandroid($key,$messageText,$level)
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
		
		//Print response from C2DM service//
		
		
		// close cURL resource, and free up system resources
		curl_close($ch);


	}
	
	
	##########################################################################################################
# IMAGE FUNCTIONS																						 #
# You do not need to alter these functions																 #
##########################################################################################################
function resizeImage($image,$width,$height,$scale) {
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	$white = imagecolorallocate($newImage, 255, 255, 255);
    imagefill($newImage, 0, 0, $white);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$image); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$image,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$image);  
			break;
    }
	
	chmod($image, 0777);
	return $image;
}
//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	//$newImageWidth = ceil($width * $scale);
	//$newImageHeight = ceil($height * $scale);
	
	$max_w = 90;
	$max_h = 100;
	
	
		
		/*if ($max_w > $max_h)
    $max_h = $max_w;
else if ($max_h > $max_w)
    $max_w = $max_h;*/
	
	
	
  $old_w = $imagewidth;
  $old_h =$imageheight;
	
	if ($old_w > $old_h) {
    $nw=$max_w;
    $nh=$old_h*($max_w/$old_w);
}
if ($old_w < $old_h) {
    $nw=$old_w*($max_h/$old_h);
    $nh=$max_h;
}
if ($old_w == $old_h) {
    $nw=$max_w;
    $nh=$max_h;
}
	
	$newImage = imagecreatetruecolor($nw,$nh);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$nw,$nh,$width,$height);
	
  
  // saving
 
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,100); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}

function smart_resize_image($file,
                              $width              = 0, 
                              $height             = 0, 
                              $proportional       = false, 
                              $output             = 'file', 
                              $delete_original    = true, 
                              $use_linux_commands = false ) {
							  
							  
      
    if ( $height <= 0 && $width <= 0 ) return false;

    # Setting defaults and meta
    $info                         = getimagesize($file);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    list($width_old, $height_old) = $info;

    # Calculating proportionality
    if ($proportional) {
      if      ($width  == 0)  $factor = $height/$height_old;
      elseif  ($height == 0)  $factor = $width/$width_old;
      else                    $factor = min( $width / $width_old, $height / $height_old );

      $final_width  = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }

    # Loading image to memory according to type
    switch ( $info[2] ) {
      case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
      case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
      case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
      default: return false;
    }
    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);

      if ($transparency >= 0) {
        $transparent_color  = imagecolorsforindex($image, $trnprt_indx);
        $transparency       = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
    # Taking care of original, if needed
    if ( $delete_original ) {
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }

    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination
    switch ( $info[2] ) {
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output);   break;
      case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
      case IMAGETYPE_PNG:   imagepng($image_resized, $output);    break;
      default: return false;
    }

    return true;
  }
		
}
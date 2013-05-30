<?php
/**
* Created By   : Lekha
* On           : 4/24/2013
* Handles the json methods for iphone and android
**/

class yoga
{

  //db connection object
  public function __construct() {
  
		  $host   ='mysql1000.mochahost.com';
		  $dbname = 'bridge_yoga';
		  $user   = 'bridge_yoga2';
		  $pass   = 'bridgeyoga2';
		  
		
		try {
		 
					  $dBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
					  
		  }        
		    catch(PDOException $e) { 
			    echo "database connection error";
			    echo $e->getMessage();
		}
		$this->db = $dBH;
    }
	
	
	
	   /* created by : lekha
	* On         : 3/4/2013
	* function that registers a speed trap user
	* parameters : first name,lastname,email,password
	*/
 public function registerUser($fname,$lname,$email,$password,$fb)
{
    $jsonArray   = array();
	$userArray   =  array();
	//register user
	$password    = md5($password);
	$currentDate = date('Y-m-d');
	
	$siteUrl   = 'http://'.$_SERVER['SERVER_NAME'];
	$logoUrl   = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] ."/public/new/images/logo.jpg";
	
			//send mail with username and password for website
			
			  // To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
							
							// Additional headers
							$headers .= 'From: yoga4.me<support@yoga.entwickler-php.com>' . "\r\n";
							
							$to       = $email;
							$subject  = "Welcome to Yoga4.mr";
							
								$message  = "Dear ".$fname."<br/><br/>";
								$message .= "Thank you for downloading our yoga4.me app.<br/>";
								$message .= "We hope you will enjoy our yoga poses and that they will help you reach your goals.<br/><br/>";
								//$message .= "On our homepage you will find statistics, more information on exercises and workouts and an abundance of other information.<br/>";
								
								
								$message .= "<img src='$logoUrl' /><br/>";
								$message .= "PS<br/>";
								$message .= "If you are not satisfied, please tell us<br/>";
								$message .= "If you are satisfied tell your friends!";
							
							
							
							
							
							
	$usercount   = 0;
	if($fb==1)
	{
		$password ="";
		$fbuser       =1;
		
		$fbdata               = array($email);
     	$checkUser          = $this->db->prepare("select * from fitness_user_general where user_email=? and fbuser=1");
		$checkUser->execute($fbdata);
		$usercount          =  $checkUser->rowCount();
		
		$checkUser->setFetchMode(PDO::FETCH_ASSOC);
		$getDetails            = $checkUser->fetch();
	}
	else
	{
		$fbuser       =0;
	}
	
	if($usercount < 1)
	{
		$data = array($fname,$lname,$email,$password,$currentDate,$fbuser);
	
		$query = $this->db->prepare("insert into fitness_user_general(`user_first_name`,`user_surname`,`user_email`,`user_password`,`registrationDate`,`fbuser`) values(?,?,?,?,?,?)");
		
		$query->execute($data);
		
		$recentUser  = $this->db->lastInsertId('user_id');
		mail($to,$subject,$message,$headers); 
	}
	else
	{
		$recentUser = $getDetails["user_id"];
		$fname      = $getDetails["user_first_name"];
		$lname      = $getDetails["user_surname"]; 
		$email   = $getDetails["user_email"]; 
	}
	
	
	
	if(($recentUser!="")&&($recentUser!=0))
	{
	    
		    $userArray['items'][0]['id']       = $recentUser;
     		$userArray['items'][0]['fname']    = $fname;
			$userArray['items'][0]['lname']    = $lname;
     	    $userArray['items'][0]['email']    = $email;
	}
	else
	{
		$userArray['items'][0]['id']       = "0";
        $userArray['items'][0]['message']  = "User not registered";
	}
	    ob_start();
		echo  json_encode($userArray);
}



/* created by : lekha
	* On         : 3/4/2013
	* function that handles the login action of a user
	* parameters : email,password
	*/
public function userlogin($email,$password)
     {
     	
     	$userArray          =  array();
     	
     	$password           = md5($password);
     	
		//check user
		
		$data               = array($email,$password);
     	$checkUser          = $this->db->prepare("select * from fitness_user_general where user_email=? and user_password=? and fbuser=0");
		$checkUser->execute($data);
		$usercount          =  $checkUser->rowCount();
		
		$checkUser->setFetchMode(PDO::FETCH_ASSOC);
						
		
     	$i=0;
     	
     	if($usercount > 0)
     	{
		    $getDetails            = $checkUser->fetch();
			
     		
						
     		$userArray['items'][$i]['id']       = $getDetails['user_id'];
     		$userArray['items'][$i]['fname']    = $getDetails['user_first_name'];
			$userArray['items'][$i]['lname']    = $getDetails['user_surname'];
     	    $userArray['items'][$i]['email']    = $getDetails['user_email'];
			
			
     	}
     	else 
     	{
     		$userArray['items'][$i]['id']       = "0";
     		$userArray['items'][$i]['message']  = "Please check your username or password.";
     	}
     	
     	
     		        ob_start();
		        	echo json_encode($userArray);
		        
	             
		
		    
     }
	 
	 
	  /* created by : lekha
	* On         : 4/24/2013
	* function that checks username availability
	* parameters : username(email)
	*/
	public  function checkusername($email)
	{
		
		
		$sql = 'SELECT count(*) as count FROM fitness_user_general where user_email="'.$email.'"  order by user_id DESC limit 0,1';
		$query_execute  = $this->db->query($sql);
		$query_execute->setFetchMode(PDO::FETCH_ASSOC);
						
		$getData = $query_execute->fetch();
									
							        
		
		
		$usernameAvailable = array();
		
		if($getData['count'] > 0)
		{
			$usernameAvailable['items'][0]['status']  = "true";
		}
		else 
		{
			$usernameAvailable['items'][0]['status']  = "false";
		}
		
		
		                
		             
		        ob_start();
		        echo  json_encode($usernameAvailable);
			
	}
	
	
	/**
	 * Register Apple device
	 *
	 * Using your Delegate file to auto register the device on application launch.  This will happen automatically from the Delegate.m file in your iPhone Application using our code.
	 *
	 * @param sting $appname Application Name
	 * @param sting $appversion Application Version
	 * @param sting $deviceuid 40 charater unique user id of Apple device
	 * @param sting $devicetoken 64 character unique device token tied to device id
	 * @param sting $devicename User selected device name
	 * @param sting $devicemodel Modle of device 'iPhone' or 'iPod'
	 * @param sting $deviceversion Current version of device
	 * @param sting $pushbadge Whether Badge Pushing is Enabled or Disabled
 	 * @param sting $pushalert Whether Alert Pushing is Enabled or Disabled
 	 * @param sting $pushsound Whether Sound Pushing is Enabled or Disabled
     * @access private
     */
	 public function registerdevice($userid,$deviceuid,$devicetoken)
	{
		
		        $jsonArray           =  array();
		
		        $data               = array($userid,$deviceuid,$devicetoken);
     	
		
				$check_record  = $this->db->prepare("select * from apple_devices where user_id=? and deviceuid=? and devicetoken=?");
				$check_record->execute($data);
				$count         = $check_record->rowCount();
			
			    if($count < 1)	
				{
				    
					$userdata = array($userid,$deviceuid,$devicetoken);
	
	
				    $sql = $this->db->prepare("INSERT INTO `apple_devices`(`user_id`,`deviceuid`,`devicetoken`) values(?,?,?)");
					$sql->execute($userdata);
					
					$jsonArray['items'][0]['status']                =  "success";
				}
				else
				{
				    
				    $update   =  "update apple_devices set deviceuid='".$deviceuid."',devicetoken='".$devicetoken."' where user_id='".$userid."'";
					$query_execute  = $this->db->query($update);
					$jsonArray['items'][0]['status']                =  "record exists";
				}
		

                echo  json_encode($jsonArray);

		

		
		
		
	}
	
	
	 function sendFeedbackMail($userid,$device,$name,$email,$mailbody)
	{
	    $mailbody  =  htmlentities(strip_tags($mailbody));
		$siteUrl   = 'http://'.$_SERVER['SERVER_NAME'];
	    $logoUrl            = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] ."/public/new/images/logo.jpg";
		$jsonArray  = array();	
		
		$dataArray      = array($userid,$device,$mailbody);
		$insert     =  $this->db->prepare("insert into fitness_feedback(`userid`,`devicename`,`message`) values(?,?,?)");
		$insert->execute($dataArray);
		
		$tipappEmail    ="lekha@bridge-india.in";
		$ccemail        ="ciby.k@bridge-india.in";
						
			  // To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
							
							// Additional headers
							$headers .= 'From: yoga4me<support@yoga4me.com>' . "\r\n";
							
							$to       = $tipappEmail;
							
							$subject  = "Yoga App Feedback";
							$message  = "Yoga4me<br/><br/>";
							$message .= "You have received a feedback from $name.<br/>";
							$message .= "Sender Email :  $email.<br/>";
							$message .= "Feedback :<br/>";
							$message .= $mailbody."<br/><br/>";
							$message .= "<img src='$logoUrl' /><br/>";
							mail($to,$subject,$message,$headers);
							
							
							$jsonArray['items'][0]['status']        ="sent";
							echo json_encode($jsonArray);
	}
	
	
	
	/**
	 * function that gives the yoga video of a yoga pose
	 * @params duration
	 * @author lekha
	 * @date 5/7/2013
	 * return json response with user id
	 */
	 function listvideo($userid,$workoutid,$lang)
	{
	   $jsonArray = array();
	   
	   
	   $getYogaPoses = $this->db->prepare("SELECT work_exercises FROM fitness_workouts where id=$workoutid");
	   $getYogaPoses->execute();
	   $getYogaPoses->setFetchMode(PDO::FETCH_ASSOC);
	   $getPoses          = $getYogaPoses->fetch();
	   
	   
	   $siteUrl   = 'http://'.$_SERVER['SERVER_NAME']."/";
	   $videoID           = $getPoses["work_exercises"];
	   
	   //get the video name from the documents table
	   
	   $getVideo          = $this->db->prepare("select workout_video_file from fitness_exercise_documents where workout_id=$videoID");
	   $getVideo->execute();
	   $getVideo->setFetchMode(PDO::FETCH_ASSOC);
	   $videoDetail   = $getVideo->fetch();
	   $video         = $siteUrl."public/js_old/temp/".$videoDetail["workout_video_file"];
	   
	   $jsonArray['items'][0]['workoutID']      = $workoutid;
	   $jsonArray['items'][0]['workout_video']  = $video;
	   $jsonArray['items'][0]['workout_video_name']  = $videoDetail["workout_video_file"];
	   
	   echo  str_replace('\/', '/', json_encode($jsonArray));
	   }
	
	
	
	/**
	 * function that gives the yoga video of a yoga pose
	 * @params duration
	 * @author lekha
	 * @date 5/7/2013
	 * return json response with user id
	 */
	 function listallvideos($lang)
	{
	   $jsonArray = array();
	    $siteUrl   = 'http://'.$_SERVER['SERVER_NAME']."/";
	   
	   $getYogaPoses = $this->db->prepare("SELECT id,work_exercises FROM fitness_workouts");
	   $getYogaPoses->execute();
	   $getYogaPoses->setFetchMode(PDO::FETCH_ASSOC);
	   $i=0;
	   while($getPoses          = $getYogaPoses->fetch())
	   {
	   	 $videoID           = $getPoses["work_exercises"];
	   
		   //get the video name from the documents table
		   
		   $getVideo          = $this->db->prepare("select workout_video_file from fitness_exercise_documents where workout_id=$videoID");
		   $getVideo->execute();
		   $getVideo->setFetchMode(PDO::FETCH_ASSOC);
		   $videoDetail   = $getVideo->fetch();
		   $video         = $siteUrl."public/js_old/temp/".$videoDetail["workout_video_file"];
		   
		   $jsonArray['items'][$i]['workoutID']      = $getPoses["id"];
		   $jsonArray['items'][$i]['workout_video']  = $video;
		   $jsonArray['items'][$i]['workout_video_name']  = $videoDetail["workout_video_file"];
	  $i=$i+1;
	   }
	   
	  
	   
	   
	   echo  str_replace('\/', '/', json_encode($jsonArray));
	   }
	
	
	/**
	 * function that lists out the yoga poses
	 * @params duration
	 * @author lekha
	 * @date 5/7/2013
	 * return json response with user id
	 */
	 function listapps($userid,$lang)
	{
	   $getYogaPoses = $this->db->prepare("SELECT * FROM fitness_workouts where isactive=1");
	   $getYogaPoses->execute();
	   
	   $getYogaPoses->setFetchMode(PDO::FETCH_ASSOC);
	   while($getPoses          = $getYogaPoses->fetch())
	   {
	   	  $poses[]              = $getPoses;
	   }
	   $appsList = $poses;
	  $i=0;
		foreach($appsList as $app)
		{
			$siteUrl   = 'http://'.$_SERVER['SERVER_NAME']."/";
			$imageUrl    = $siteUrl."/public/images/exercises/thumbs/".$app['work_image_list'];
			$imageAndroidUrl    = $siteUrl."/public/images/exercises/".$app['work_image_list'];
			$imageThumb  = $siteUrl."/public/images/exercises/thumbs/".$app['work_image_list'];
			
			//get props used if any
			 $exercise           = $app['work_exercises'];
			//get the caution and tips
			 $getDescription = $this->db->prepare("SELECT workout_execution,workout_advice FROM fitness_exercise_general_multilang where workout_id=$exercise and lang_id=$lang");
	         $getDescription->execute();
			 $desc          = $getDescription->fetch();
			 
			//$exerArray         =   explode(",",$app['work_exercises']);
			
			
             $getworkoutDesc = $this->db->prepare('SELECT work_name,description_small,description_big FROM fitness_workouts_multilang where work_id="'.$app['id'].'" and  lang_id="'.$lang.'"');
	         $getworkoutDesc->execute();
			 $descriptions          = $getworkoutDesc->fetch();
			 
			
			
			 $getFilters = $this->db->prepare("SELECT workout_secondary_muscles FROM fitness_exercise_general where workout_id=$exercise");
	         $getFilters->execute();
			 $filters          = $getFilters->fetch();
			
			
			
			$workoutArray['items'][$i]["id"]    =  $app['id'];
			$workoutArray['items'][$i]["name"]  =  mb_convert_encoding($descriptions['work_name'],'UTF-8');
						
			$workoutArray['items'][$i]["description"]  =  mb_convert_encoding($descriptions['description_small'],'UTF-8');
			
			if($workoutArray['items'][$i]["description"]=="")
			{
				$workoutArray['items'][$i]["description"] = "null";
			}
			
			$workoutArray['items'][$i]["description_big"]  =   mb_convert_encoding($descriptions['description_big'],'UTF-8');
			$workoutArray['items'][$i]["workout_caution"]  =   mb_convert_encoding($desc['workout_execution'],'UTF-8');
			$workoutArray['items'][$i]["workout_tip"]      =   mb_convert_encoding($desc['workout_advice'],'UTF-8');
			$workoutArray['items'][$i]["workout_filters"]      =   $filters['workout_secondary_muscles'];
			$workoutArray['items'][$i]["image"]  =  $imageThumb;
			$workoutArray['items'][$i]["image_name"]  =  $app['work_image_list'];
			$workoutArray['items'][$i]["image_thumb"]  =   $imageUrl;
			$workoutArray['items'][$i]["image_android"]  =   $imageAndroidUrl;
			$workoutArray['items'][$i]["islocked"] =  'false';
			
			
				
				
			
		
			
		$i=$i+1;
		
		$result = array();
$equipments="";
$equipmentArray = array();

}
		
		
		
		                
		       
		        ob_start();
		        echo  str_replace('\/', '/', json_encode($workoutArray));
		        
		           
	
	
	}
	
	function listFilters($lang)
	{
	   $filters  = array();
	   $getYogaFilters = $this->db->prepare("SELECT bodyarea_id,area_name FROM fitness_body_areas_multilang where lang_id='".$lang."' order by id ASC");
	   $getYogaFilters->execute();
	   
	   $getYogaFilters->setFetchMode(PDO::FETCH_ASSOC);
	   $i=0;
	    while($getFilters          = $getYogaFilters->fetch())
	   {
	   	  $filters["items"][$i]["filter_id"]      = $getFilters["bodyarea_id"];
		  $filters["items"][$i]["filter_name"]    = $getFilters["area_name"];
		  
		  
	   $i=$i+1;
	   }
	   
	   ob_start();
		        echo  str_replace('\/', '/', json_encode($filters));
	   
	}
	
	
}
?>
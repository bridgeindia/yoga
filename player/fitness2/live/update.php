<?php session_start();

include("conn.php");
function sendNotification($message,$devicetoken,$sender="",$level="",$plan="")
        {
        	
            $sandbox               =  1;
        	
        	
			if($level=="")
			{
				$level = 6;
			}
        	
				        	
				         
				            
				            // Put your private key's passphrase here:
				            $passphrase = 'fitness4me';//'pushchat';
				        
						   if($plan==0)
						   {
						   
								$ctx = stream_context_create();
							    stream_context_set_option($ctx, 'ssl', 'local_cert','../ck.pem');
							    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
													   	
						   }
						   else if($plan==1)
						   {
						   	$ctx = stream_context_create();
							stream_context_set_option($ctx, 'ssl', 'local_cert','../ckpre.pem');
							stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
						   }
						   else if($plan==2)
						   {
						    if($sandbox==1)
							{
							        
									$ctx = stream_context_create();
							        stream_context_set_option($ctx, 'ssl', 'local_cert','../mobile/version3/apk_sandbox/ck.pem');
							        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
							}
							else
							{
								$ctx = stream_context_create();
							    stream_context_set_option($ctx, 'ssl', 'local_cert','../mobile/version3/apk_live/ck.pem');
							    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
							}
						   	
						   }
				            
				
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
						
						
				
						// Close the connection to the server
						fclose($fp);
						//echo json_encode($messageStatus);
						$message = array();
        	
        }
function sentandroid($key,$messageText,$level="")
	{
		
		if($level=="")
		{
			$level = 6;
		}
		
		 // Replace with real server API key from Google APIs  
                $apiKey = "AIzaSyDyM_X4TGozo3Ws_TKaWmNlO5IKvW-bHr8";    

                  // Replace with real client registration IDs
               $registrationIDs = array($key);

              // Message to be sent
             $message = $messageText;

             // Set POST variables
            $url = 'https://android.googleapis.com/gcm/send';

           $fields = array(
           'registration_ids' => $registrationIDs,
             'data' => array( "message" => $message,"badge" => $level ),
            );
         $headers = array(
          'Authorization: key=' . $apiKey,
         'Content-Type: application/json'
          );

         // Open connection
              $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         //     curl_setopt($ch, CURLOPT_POST, true);
           //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));

                // Execute post
             $result = curl_exec($ch);

            // Close connection
               curl_close($ch);
             
              //print_r($result);
               //var_dump($result);
	}


if($_POST['id']!="")
{

	$productId  = $_POST["id"];
    
}


$date       =date('Y-m-d');
if($_POST["act"] == "insert")
{
  $newlist   = rtrim($_POST["list"],",");
  $level     = $_POST["level"];
	 if(isset($_SESSION['selfworkout']))
	 {
		 	if($_SESSION['selfworkout']!="")
				{
				    $session_array           = explode(",",$newlist);
					if(!(in_array($productId,$session_array)) || ($productId=='rec15')|| ($productId=='rec30'))
					$_SESSION['selfworkout'] = $newlist.",".$productId;
				}
				  else
				  {
				  	$_SESSION['selfworkout'] = $productId;
				  }
				  
				 
		}
		else
		{
			$_SESSION['selfworkout'] = $productId;
			
			
		}


}


if($_POST["act"] == "delete")
{
    unset($_SESSION['selfworkout']);
    $product_id =  $_POST["id"];
	$list       =  rtrim($_POST["list"],",");
	$level      = $_POST["level"];
	$workoutArray  = explode(",",$list);
	$offset = array_search($product_id,$workoutArray);
	
	array_splice($workoutArray,$offset,1);
	
	$_SESSION['selfworkout'] = implode(",",$workoutArray);
	
}
if($_POST["act"] == "save")
{
   if(isset($_SESSION['selfworkout']))
 {  
   
	
	$userlevel = $_POST["userlevel"];
	$userid    = $_POST["userid"];
	$time      = ($_POST["time"]*60);
	$workoutid = $_POST["workoutid"];
	$userstatus = $_POST["userstatus"];
	$workoutName = $_POST["workoutname"];
	$products   = rtrim($_POST["id"],",");
	
	
    $exerciseArray  = explode(",",$products);
	foreach($exerciseArray as $exercise)
	{
		
		
		//get equipments
		
		$select_equipments  = mysql_query("select equipments_home,equipments_office,equipments_nature,equipments_hotel from fitness_exercise_equipments where workout_id='".$exercise."'");
		
		$get_equipments     = mysql_fetch_row($select_equipments);
		if(count($get_equipments) > 0)
		{
			//$equipmentsStr      = $get_equipments[0].",".$get_equipments[1].",".$get_equipments[2].",".$get_equipments[3].",";
			if($get_equipments[0]!="")
			$equipStr  .= $get_equipments[0].",";
			if($get_equipments[1]!="")
			$equipStr  .= $get_equipments[1].",";
			if($get_equipments[2]!="")
			$equipStr  .= $get_equipments[2].",";
			if($get_equipments[3]!="")
			$equipStr  .= $get_equipments[3].",";
		}
		
		
		
		
		//get the muscles
		$select_muscles     = mysql_query("select body_area_id from fitness_exercise_ranking where workout_id='".$exercise."' and ranking in (6)");
		
		while($get_muscles  = mysql_fetch_array($select_muscles))
		{
			$musclesArray[]  = $get_muscles['body_area_id'];
		}
		$musclesArray        = array_unique($musclesArray);
		
		
	
	}
	$equipStr  = rtrim($equipStr,",");
	$equipmentsArray   = explode(",",$equipStr);
	
	$equipmentsArray   = array_unique($equipmentsArray);
	
	
	$equipments        = implode(",",$equipmentsArray);
	$muscles           = implode(",",$musclesArray);
	
  	$date   = date('Y-m-d');
	if($userstatus==1)
	{
	   //get the android key for user
	$selectKey  = mysql_query("select android_key from fitness_android_key where user_id='".$userid."'");
	$getKey     = mysql_fetch_array($selectKey);
	$key        = $getKey["android_key"];
	$messageText = "A self-made workout has been added/edited in fitness4.me";
	if($key!="")
	sentandroid($key,$messageText,$level="");
	
	//get iphone device key
	$selectIphoneKey  = mysql_query("select devicetoken from apple_devices where user_id='".$userid."'");
	$getIphoneKey     = mysql_fetch_array($selectIphoneKey);
	$iphonekey        = $getIphoneKey["devicetoken"];
	
	//get user plan
	$selectPlan        =  mysql_query("select plan from fitness_user_general where user_id='".$userid."'");
	$getPlan           = mysql_fetch_array($selectPlan);
	$plan              = $getPlan["plan"];
	if($iphonekey!="")
	sendNotification($messageText,$iphonekey,$sender="",$level="",$plan);
	
	
		if($workoutid=="null")
	{
		$query  = "insert into fitness_selfmade_workouts(`userid`,`workout_name`,`workout_equipment`,`workout_focus`,`collection`,`duration`,`date_created`) values('".$userid."','".$workoutName."','".$equipments."','".$muscles."','".$products."','".$time."','".$date."')";
		 mysql_query($query);
		 $sql = 'SELECT id FROM fitness_selfmade_workouts order by id DESC limit 0,1';
			$query_execute  = mysql_query($sql);
			$result  = mysql_fetch_array($query_execute);
			$lastID  =	$result['id'];		
	}
    else
	{
		$query = "update fitness_selfmade_workouts set workout_name='".$workoutName."',workout_equipment='".$equipments."',workout_focus='".$muscles."',collection='".$products."',duration='".$time."' where id='".$workoutid."' and userid='".$userid."'";
		 mysql_query($query);
		 $lastID  =	$workoutid;	
	}
									
	echo $lastID;	
	}
	else
	{
	 $str   = $products. "%%".$equipments."%%".$muscles;
	 echo $str;
	}
				    
	unset($_SESSION['selfworkout']);
	
	}
}


 if(isset($_SESSION['selfworkout']))
 {
		if($_SESSION['selfworkout']!="")
		{
		    $_SESSION['selfworkout'] = rtrim($_SESSION['selfworkout'],",");
			$exerciseArray  = explode(",",$_SESSION['selfworkout']);
			
		$i=0;
		$count = 0;
		$html="";
		$muscles = "";
		$time=0;
		foreach($exerciseArray as $exercise)
		{
		  if($exercise!="")
		  {
		  	
		 
		    if($exercise=='rec15')
			{
			    $url_rec    = "../../../../public/css/images/workouts/selfmade/prod".$exercise.".png";
				$html .="<div  id='".$exercise."' style='background:url(".$url_rec.") no-repeat;' />";
				$rectime +=15;
			}
			else if($exercise=='rec30')
			{
				$url_rec    = "../../../../public/css/images/workouts/selfmade/prod".$exercise.".png";
				$html .="<div  id='".$exercise."' style='background:url(".$url_rec.") no-repeat;' />";
				$rectime +=30;
			}
			else
			{
				$prodID = $exercise;
			$selectImage  = mysql_query("select workout_imagethumb,workout_timeframe,otherside,workout_primary_muscles,workout_secondary_muscles from fitness_exercise_general where workout_id='".$exercise."'");
			$getImage     = mysql_fetch_row($selectImage);
			$selectName  = mysql_query("select workout_name from fitness_exercise_general_multilang where workout_id='".$exercise."' and lang_id=1");
			$getName     = mysql_fetch_row($selectName);
			$select_repetition = mysql_query("select repetitions from fitness_exercise_repetition where workout_id='".$exercise."' and exercise_level_id='".$level."'");
			$getRep            = mysql_fetch_row($select_repetition);
			
			$url    = "../../../../public/images/exercises/single/".$getImage[0];
			$html .= "<div  id='".$prodID."' style='background:url(".$url.") no-repeat;' title='".$getName[0]."'/>";
			
			if($getImage[2]==1)
			{
				$exertime  = ($getImage[1] * $getRep[0])*2;
				$time +=$exertime;
			}
			else
			{
				$exertime2  = ($getImage[1] * $getRep[0]);
				$time +=$exertime2;
			}
			
			$muscles .=$muscles.$getImage[3].",".$getImage[4].",";
			
			$count ++;
			}
			
			
			
		 $i=$i+1;
		   }
		 }
		 $muscles = rtrim($muscles,",");
		 
		 $muscleArray =  explode(",",$muscles);
		 $muscleArray = array_unique($muscleArray);
		  $time   = round((($time+$rectime)/60),2);
		 $time   = $time;
		 $focuscount = count($muscleArray);
		 $params = $html."**".$time."**".$count."**".$focuscount;
		echo $params;
		}
		
}

?>
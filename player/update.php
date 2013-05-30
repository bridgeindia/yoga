<?php session_start();
include("conn.php");

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
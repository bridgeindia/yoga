<?php session_start();

include("conn.php");

if($_POST["user_id"]!="")
{
	$userid  = $_POST["user_id"];
	$workoutID = $_POST["workoutid"];
	$level     = $_POST["level"];
}

/*$html="";
$select_exewrcise   = mysql_query("select * from workouts");
$i=0;
while($get_exercise       = mysql_fetch_array($select_exewrcise))
{
    $prodID = $get_exercise['id']."-".$get_exercise['exercise'];
	$html .= "<img src='images/prod".$get_exercise['exercise'].".jpg' id='".$prodID."'/>";

 $i=$i+1;
 }*/
 
 
 //check if selfmade id is passed and if so get the collection of exercises
  if($_SESSION['selfworkout']=="")
  {
  	if(($workoutID!="") && ($userid!=""))
 {
 	$selectDetails  =  mysql_query("select * from fitness_selfmade_workouts where userid='".$userid."' and id='".$workoutID."'");
	$getDetails     =  mysql_fetch_row($selectDetails);
	if($getDetails[5] !="")
	{
		$_SESSION['selfworkout'] = rtrim($getDetails[5],",");
	}
 }
  }
 
 
 
 if(isset($_SESSION['selfworkout']))
 {
 	if($_SESSION['selfworkout']!="")
		 {
		 	$exerciseArray  = explode(",",$_SESSION['selfworkout']);
		$i=0;
		$html="";
		$count = 0;
		$muscles = "";
		$rectime=0;
		$time=0;
		foreach($exerciseArray as $exercise)
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
		 
		 $muscles = rtrim($muscles,",");
		 $muscleArray =  explode(",",$muscles);
		 $muscleArray = array_unique($muscleArray);
		 $time   = round((($time+$rectime)/60),2);
		 $time   = $time;
		 $focuscount = count($muscleArray);
		  
		 $params = $html."**".$time."**".$count."**".$focuscount;
		echo $params;
		
        }
		else
		{
			echo "";
		}
 }
 else
 {
 	echo "";
 }
 
 
?>
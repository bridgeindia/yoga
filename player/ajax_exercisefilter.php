<?php
include("../paypal_ipn/conn.php");

function array_sort_by_column($arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
	$sort_col_id = array();
	$i=0;
    foreach ($arr as $key=> $row) {
        $sort_col[$i]['workout_name'] = $row[$col];
		$sort_col[$i]['workout_id'] = $row['workout_id'];
    $i=$i+1;}
array_multisort($sort_col, $dir, $arr); 

    return $sort_col;
}
     
	 
	 
if(($_GET["exercises"]!="")&&($_GET["level"]!=""))
{
    $level  = $_GET["level"];
	$exerciseArray = explode(",",$_GET["exercises"]);
	$select_records  = mysql_query("select workout_id from fitness_exercise_repetition where exercise_level_id='".$level."' and repetitions!=''");
	
	$exercisesArray  = array();
	while($getRecords = mysql_fetch_array($select_records))
	{
		$workoutID     = $getRecords["workout_id"];
		$checkRecord   = mysql_query("select master_check from fitness_exercise_general where workout_id='".$workoutID."'");
		$getChecked    = mysql_fetch_row($checkRecord);
		if($getChecked[0]==1)
		{
			$selectWorkouts   = mysql_query("select workout_id,workout_name from fitness_exercise_general_multilang where workout_id='".$workoutID."' and lang_id=1");
			$getWorkout[]       = mysql_fetch_array($selectWorkouts);
			
		}
	}
	foreach($getWorkout as $workouts)
	{   
		$exercisesArray[]  = $workouts;
	}
	
	$exercisesArray  = array_sort_by_column($exercisesArray,"workout_name");
	foreach($exercisesArray as $exerciseRow)
	{
		if(in_array($exerciseRow["workout_id"],$exerciseArray))
			{
				$checked  = "selected";
			}
			else
			{
				$checked  = "";
			}
			$options          .="<option value='".$exerciseRow["workout_id"]."' $checked>".$exerciseRow["workout_name"]."</option>";
	}
	/*if(in_array($workoutID,$exerciseArray))
			{
				$checked  = "selected";
			}
			else
			{
				$checked  = "";
			}
			$options          .="<option value='".$getWorkout[0]."' $checked>".$getWorkout[1]."</option>";*/
	echo $options;
}

?>
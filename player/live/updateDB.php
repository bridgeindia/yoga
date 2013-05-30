<?php
require_once("conn.php");

$action 				= mysql_real_escape_string($_POST['action']);
$updateRecordsArray 	= $_POST['recordsArray'];

if ($action == "updateRecordsListings"){

	$listingCounter = 1;
	$workout_ID     = $_POST['workout'][0];
	$workouts="";
	foreach ($updateRecordsArray as $recordIDValue) {
        $workouts   = $workouts . $recordIDValue.",";
		
	}
    $workoutString   = rtrim($workouts,",");
	
	if($workout_ID !="")
	{
		$query           = mysql_query("update fitness_workouts set work_exercises_order='".$workoutString."' where id='".$workout_ID."'");
		
	}
	
	
	//print_r($workouts);
	echo "Your workout order has been saved";
	
}
?>
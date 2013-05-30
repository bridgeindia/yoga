<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_selfmade_workouts_done
 */
class FitnessSelfmadeWorkoutsDone extends Zend_Db_Table
{
	
    protected $_name = 'fitness_selfmade_workouts_done';
    
    
	
	
	 public function getSelfWorkoutsDone($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_selfmade_workouts_done where user_id='".$userId."'  and workout_done_status =1 order by workout_done_date DESC limit 0,3";
	    	$result = $db->fetchAll($sql);
	    	
	    	return $result;
	    }
	    
	    public function getSelfTotalWorkoutTime($userId,$interval=0)
	    {
	    	global $db;
	    	
	    	if($interval !=0)
	    	{
	    		$where  = " and workout_done_date >= date(now()-interval $interval day)";
	    	}
	    	else 
	    	{
	    		$where = "";
	    	}
	    	
	    	$sql = "select SUM(workout_time) as totaltime from fitness_selfmade_workouts_done where user_id='".$userId."' and workout_done_status =1 $where";
	        
	    	$result = $db->fetchRow($sql,2);
	    	
	    	return $result;
	    }
	    
	     public function setLockStatus($userId,$lock)
			    {
			    	global $db;
			    	
			    	
			    	$sql = "update fitness_selfmade_workouts_done set workout_done_status='".$lock."' where user_id='".$userId."'";
			    	
			    	$db->query($sql);
			    	
			    }
			    
			    
			    public function checkRecordExists($userId,$workout_id)
				   {
				      	global $db;
					    	
					    	$sql = 'SELECT count(*) as count FROM fitness_selfmade_workouts_done where user_id="'.$userId.'" and workout_id="'.$workout_id.'" order by user_id DESC limit 0,1';
					
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
			    
			    
			     public function updateWorkoutTime($userId,$workout_id,$workoutTime)
				    {
				    	global $db;
				    	$currentDate  = date('Y-m-d h:m:s');
				    	
				    	$sql = "update fitness_selfmade_workouts_done set `workout_time`=`workout_time` + '".$workoutTime."',`workout_done_date`='".$currentDate."' where user_id='".$userId."' and workout_id='".$workout_id."'";
				    	
				    	$db->query($sql);
				    	
				    }
				    
				    public function insertWorkoutTime($userId,$workout_id,$workoutTime)
				    {
				    	global $db;
				    	$currentDate  = date('Y-m-d h:m:s');
				    	
				    	$sql = "insert into fitness_selfmade_workouts_done(`user_id`,`workout_id`,`workout_time`,`workout_done_date`,`workout_done_status`) values('".$userId."','".$workout_id."','".$workoutTime."','".$currentDate."',1)";
				    	
				    	$db->query($sql);
				    	
				    }
					
					public function getWorkoutsCount()
				   {
				      	global $db;
					    	
					    	$sql = 'SELECT DISTINCT(workout_id) as workoutId, count(workout_id) AS count FROM fitness_selfmade_workouts_done  GROUP BY workout_id  order by count DESC limit 0,3';
					
							$result = $db->fetchAll($sql, 2);
							
							return $result;
				   }
	    
}
?>
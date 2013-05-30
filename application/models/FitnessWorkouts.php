<?php
/* Created by : Lekha
 *  on : 29rd Feb 2012
 *  Class that interacts with the  table fitness_workouts
 */
class FitnessWorkouts extends Zend_Db_Table
{
	
    protected $_name = 'fitness_workouts';
    
    protected $_dependentTables = array(
    'FitnessWorkoutsMultilang',
    'FitnessUserWorkoutsUnlocked',
    'FitnessUserWorkoutsDone'
    );
    
    
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'work_name'          => $data['work_name'],
	    'work_image_list'    => $data['work_image_list'],
	    'work_exercises'     => $data['work_exercises'],
		
	    'work_level'         => $data['work_level'],
	    'work_recovery_time' => $data['work_recovery_time'],
	    'work_recovery_interval' => $data['work_recovery_interval'],
	    'work_filter'        => $data['work_filter'],
	    'work_duration'      => $data['work_duration'],
	    'description_small'  => $data['description_small'],
	   	'description_big'    => $data['description_big'],
	   	'workout_date'       => date('Y-m-d')   	    
	    );

    $db->insert('fitness_workouts', $data);
    	
    }
    
    
     
    public function listWorkoutsBackend($lang=1)
	    {
	    	global $db;
	    	
	    	$sql = "SELECT * FROM fitness_workouts  order by id ASC";
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
		public function listAllWorkouts()
	    {
	    	global $db;
	    	
	    	$sql = "SELECT * FROM fitness_workouts where isactive=1 order by id ASC";
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	   public function listWorkouts($lang=1,$level,$duration)
	    {
	    	global $db;
	    	
	    	$sql = "SELECT * FROM fitness_workouts where work_level='".$level."' and work_duration='".$duration."' and isactive=1 order by id ASC";
	       
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	  
		   
		   public function getWorkout($workouttId)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_workouts where id="'.$workouttId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		   
		    public function getWorkoutwithDuration($duration,$level=1)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_workouts where work_duration="'.$duration.'" and  work_level="'.$level.'" and isactive=1';
			
					$result = $db->fetchAll($sql, 2);
					
					return $result;
		   }
		   
		    public function getLastWorkId()
			   {
			      	global $db;
				    	
				    	$sql = 'SELECT id FROM fitness_workouts order by id DESC limit 0,1';
				
						$result = $db->fetchRow($sql, 2);
						
						return $result;
			   }
			   
			   
			   public function setLockStatus($locked,$workid)
			   {
			      	   global $db;
				    	
				    	$sql = 'UPDATE fitness_workouts set islocked="'.$locked.'"  where id="'.$workid.'"';
				
						$db->query($sql);
			   }
			   
			   
			   public function getWorkoutsByMuscles($level=1,$duration=10)
				   {
				   	 global $db;
				   	 $sql = 'SELECT id,work_filter FROM fitness_workouts where work_level="'.$level.'" and work_duration="'.$duration.'" and isactive=1';
				   	 $result = $db->fetchAll($sql, 2);
							
					 return $result;
				   	 
				   }
				   
				   
				   public function getWorkoutsByExercise($exercise)
				   {
				   	  global $db;
				   	  
				   	  $sql = "SELECT id,work_name FROM fitness_workouts where work_exercises REGEXP '^{$exercise}[,]|[,]{$exercise}[,]|[,]{$exercise}$|^{$exercise}$' ";
				   	 
				   	 $result = $db->fetchAll($sql, 2);
							
					 return $result;
				   	  
				   }
				   
				    public function getWorkoutsByDate($date)
					   {
					   	 global $db;
					   	 
					   	 $sql = "SELECT * FROM fitness_workouts where workout_date='".$date."' and isactive=1";
					   	
					   	 $result = $db->fetchAll($sql, 2);
								
						 return $result;
					   	 
					   }
					   
					    public function getExercises($workoutId)
					   {
					   	 global $db;
					   	 
					   	 $sql = "SELECT work_exercises,work_exercises_order FROM fitness_workouts where id='".$workoutId."'";
					   	
					   	 $result = $db->fetchRow($sql, 2);
								
						 return $result;
					   	 
					   }
					   
					   public function checkstatus($workoutId)
					   {
					   	 global $db;
					   	 
					   	 $sql = "SELECT count(*) as count FROM fitness_workouts where id='".$workoutId."' and isactive=1";
					   	
					   	 $result = $db->fetchRow($sql, 2);
								
						 return $result;
					   	 
					   }
					   
					    public function getFirstWorkId($level=1)
						   {
						      	global $db;
							    	
							    	$sql = "SELECT * FROM fitness_workouts where work_level='".$level."' limit 1";
							
									$result = $db->fetchRow($sql, 2);
									
									return $result;
						   }
						   
						   public function getNextWorkId($prevId,$level=1)
						   {
						      	global $db;
							    	
							    	$sql = 'SELECT id FROM fitness_workouts WHERE work_level='.$level.' and id > '.$prevId.' and isactive=1 ORDER BY id LIMIT 1';
							
									$result = $db->fetchRow($sql, 2);
									
									return $result;
						   }
   
}
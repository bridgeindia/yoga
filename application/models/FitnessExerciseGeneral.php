<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts  table fitness_exercise_general
 */
class FitnessExerciseGeneral extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_general';
    protected $_dependentTables = array(
    'FitnessExerciseGeneralMultilang',
    'FitnessExerciseRanking',
    'FitnessExerciseRepetition',
    'FitnessExerciseEquipments',
    'FitnessExerciseDiffDegree',
    'FitnessExerciseDocuments',
    'FitnessExerciseDocumentsMultilang'
);
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
		'otherside'          => $data['otherside'],
    	'workout_timeframe'          => $data['timeframe'],
        'workout_primary_muscles'          => $data['primary_muscle'],
	    'workout_secondary_muscles'         => $data['secondary_muscle'],
	    'workout_pay_status'      => $data['paystatus'],
	    'workout_rate'      => $data['workoutRate'],
	    'workout_level'     => $data['workoutLevel'],
	    'translator_check'     => $data['translator_check'],
	    'master_check'     => $data['master_check'],
	    'date_created'     => date( 'Y-m-d')
	    );

	 
	 return $db->insert('fitness_exercise_general', $data);
   
    	
    }
    
    public function selectRecords()
   {
   	        global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general  order by workout_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
   }
   
   
    public function selectInActiveRecords()
   {
   	        global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general where master_check=0  order by workout_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
   }
   
    public function selectActiveRecords()
   {
   	        global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general where master_check=1  order by date_created ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
   }
   
    public function selectRecordsWorkout()
   {
   	        global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general where master_check=1 order by workout_id DESC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
   }
   
   public function getLastWorkoutId()
   {
      	global $db;
	    	
	    	$sql = 'SELECT workout_id FROM fitness_exercise_general order by workout_id DESC limit 0,1';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
   }
   
   public function getRecord($workoutId)
   {
   	        global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general where workout_id="'.$workoutId.'"  order by workout_id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
   }
   
   public function getWorkoutTime($time)
   {
   	       global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general where workout_timeframe="'.$time.'"  order by workout_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
   }
   
    public function getExerciseTime($workoutID)
   {
   	       global $db;
	    	
	    	$sql = 'SELECT workout_timeframe FROM fitness_exercise_general where workout_id="'.$workoutID.'"  order by workout_id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
   }
   
   
   public function getWorkoutsByMuscles()
   {
   	 global $db;
   	 $sql = 'SELECT workout_id,workout_primary_muscles,workout_secondary_muscles FROM fitness_exercise_general order by workout_id ';
   	 $result = $db->fetchAll($sql, 2);
			
	 return $result;
   	 
   }
  
  public function checkExerciseStatus($exerciseID)
  {
		  	global $db;
			$sql = 'SELECT workout_id FROM fitness_exercise_general where workout_id="'.$exerciseID.'" and master_check=1 order by workout_id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	
  }
   public function getExerciseImage($exerciseID)
  {
		  	global $db;
			$sql = 'SELECT workout_image,workout_imagethumb FROM fitness_exercise_general where workout_id="'.$exerciseID.'" and master_check=1 order by workout_id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	
  }
  
   public function getOtherSide($exerciseID)
  {
		  	global $db;
			$sql = 'SELECT otherside FROM fitness_exercise_general where workout_id="'.$exerciseID.'"';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	
  }
   
   
   
}
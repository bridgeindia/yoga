<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_general_multilang
 */
class FitnessExerciseGeneralMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_general_multilang';

    protected $_referenceMap = array(
	'workout_id' => array(
	    'columns' => array('workout_id'),
	    'refTableClass' => 'FitnessExerciseGeneral',
	    'refColumns' => array('workout_id'),
	    'onDelete' => self::CASCADE
	));
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'workout_id'          => $data['workout_id'],
		'otherside'           => $data['otherside'],
	    'lang_id'             => $data['lang_id'],
	    'workout_name'        => $data['workout_name'],
	    'workout_preparation' => $data['workout_preparation'],
	    'workout_execution'   => $data['workout_execution'],
	    'workout_advice'      => $data['workout_advice']
	    
	);

    return  $db->insert('fitness_exercise_general_multilang', $data);
    	
    }
    
    
   public function getRecord($workoutId,$lang=1)
   {
   	 
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general_multilang where lang_id="'.$lang.'" and workout_id="'.$workoutId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    
   }
   
   public function selectRecords($workoutId)
   {
   	        global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_general_multilang where lang_id=1 and workout_id="'.$workoutId.'" order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
   }
   
   public function getWorkoutName($workoutId,$langId=1)
   {
   	        global $db;
	    	
	    	$sql = 'SELECT workout_name FROM fitness_exercise_general_multilang where lang_id="'.$langId.'" and workout_id="'.$workoutId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 1);
			
			return $result;
   }
   
   public function getLangRecord($workoutId,$langId)
   {
   	 
	    	global $db;
	    	
	    	$sql = 'SELECT count(*) as count FROM fitness_exercise_general_multilang where lang_id="'.$langId.'" and workout_id="'.$workoutId.'" order by id ASC';
	         
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    
   }
}
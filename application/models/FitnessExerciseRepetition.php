<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_documents
 */
class FitnessExerciseRepetition extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_repetition';
    
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
	    'exercise_level_id'    => $data['exercise_level_id'],
	    'repetitions'          => $data['repetitions']
	    );

    $db->insert('fitness_exercise_repetition', $data);
    	
    }
    
    
      public function getRecord($workoutId,$levelId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT * FROM fitness_exercise_repetition where workout_id="'.$workoutId.'" and exercise_level_id="'.$levelId.'"';
		
				$result = $db->fetchRow($sql, 2);
				
				return $result;
	   }
    
	   
	   public function checkRecord($workoutId,$levelId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT * FROM fitness_exercise_repetition where workout_id="'.$workoutId.'" and exercise_level_id="'.$levelId.'"';
		
				$result = $db->fetchAll($sql, 2);
				
				return $result;
	   }
}
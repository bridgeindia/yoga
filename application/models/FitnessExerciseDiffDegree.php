<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_diff_degree
 */
class FitnessExerciseDiffDegree extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_diff_degree';
    
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
	    'degree_beginner'          => $data['degree_beginner'],
	    'beginner_first'          => $data['beginner_first'],
	    'beginner_second'          => $data['beginner_second'],
	    'beginner_third'          => $data['beginner_third'],
	    'degree_advanced'          => $data['degree_advanced'],
	    'advanced_first'          => $data['advanced_first'],
	    'advanced_second'          => $data['advanced_second'],
	    'advanced_third'          => $data['advanced_third'],
	    'degree_professional'          => $data['degree_professional'],
	    'professional_first'          => $data['professional_first'],
	    'professional_second'          => $data['professional_second'],
	    'professional_third'          => $data['professional_third']
	    
	    );
	
    $db->insert('fitness_exercise_diff_degree', $data);
  
    }
    
    
     public function getRecord($workoutId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT * FROM fitness_exercise_diff_degree where workout_id="'.$workoutId.'"';
		
				$result = $db->fetchRow($sql, 2);
				
				return $result;
	   }
    
    
	    
   
}
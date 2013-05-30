<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_documents
 */
class FitnessExerciseDocuments extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_documents';
    
   
    
    
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
	    'workout_id'                    => $data['workout_id'],
	    'workout_photo_start'        => $data['photo_start'],
	    'workout_photo_end'          => $data['photo_end'],
	    'workout_video_file'   => $data['video_file'],
	    'poster_video'         => $data['poster_video']
	    );

   return $db->insert('fitness_exercise_documents', $data);
    	
    }
    
    
    
     public function getRecord($workoutId)
	   {
	   	        global $db;
		    	
		    	$sql = 'SELECT * FROM fitness_exercise_documents where workout_id="'.$workoutId.'"';
		
				$result = $db->fetchRow($sql, 2);
				
				return $result;
	   }
    
	   
	    public function getDocumentId($workoutId)
		   {
		   	       global $db;
			    	
			    	$sql = 'SELECT document_id FROM fitness_exercise_documents where  workout_id="'.$workoutId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		   
		    public function getPoster($workoutId)
		   {
		   	       global $db;
			    	
			    	$sql = 'SELECT poster_video FROM fitness_exercise_documents where  workout_id="'.$workoutId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
}
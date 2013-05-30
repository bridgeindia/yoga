<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_exercise_equipments
 */
class FitnessExerciseEquipments extends Zend_Db_Table
{
	
    protected $_name = 'fitness_exercise_equipments';
    
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
	    'equipment_required'  => $data['equipment_required'],
	    'equipments_home'     => $data['equipments_home'],
	    'equipments_office'   => $data['equipments_office'],
	    'equipments_nature'   => $data['equipments_nature'],
	    'equipments_hotel'    => $data['equipments_hotel']
	    
	);

    $db->insert('fitness_exercise_equipments', $data);
    	
    }
    
    
     public function getRecord($workoutId)
   {
   	 
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_exercise_equipments where  workout_id="'.$workoutId.'" ';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    
   }
   
   public function getEquipment($workoutId)
   {
   	 
	    	global $db;
	    	
	    	$sql = 'SELECT equipments_home FROM fitness_exercise_equipments where  workout_id="'.$workoutId.'" and equipment_required=1';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    
   }
   
   
    public function getExercisesByEquipments($equipments,$none=0)
   {
   	 
	    	global $db;
			
			if($none == 0)
	    	{
				$sql = 'SELECT workout_id,equipments_home,equipments_office,equipments_nature,equipments_hotel,equipment_required FROM fitness_exercise_equipments';
			}
			else
			{
				$sql = 'SELECT workout_id,equipments_home,equipments_office,equipments_nature,equipments_hotel,equipment_required FROM fitness_exercise_equipments';
			}
	    	
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    
   }
   
   public function getExercisesByNoEquip()
   {
      global $db;
   
   	  $sql = 'SELECT workout_id FROM fitness_exercise_equipments where equipment_required=0';
	  $result = $db->fetchAll($sql, 2);
			
	 return $result;
   }
    
    
   
}
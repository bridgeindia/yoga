<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_body_areas
 */
class FitnessBodyAreas extends Zend_Db_Table
{
	
    protected $_name = 'fitness_body_areas';
    protected $_dependentTables = array(
    'FitnessBodyAreasMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'area_name'          => $data['area_name']
	    );

    return $db->insert('fitness_body_areas', $data);
    	
    }
    
    
    public function getLastMuscleId()
   {
      	global $db;
	    	
	    	$sql = 'SELECT area_id FROM fitness_body_areas order by area_id DESC limit 0,1';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
   }
   
   
    public function listMuscles()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_body_areas where status=1  order by area_order ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
		
		public function checkStatus($muscleId)
		{
			
			global $db;
	    	
	    	$sql = 'SELECT status FROM fitness_body_areas where area_id="'.$muscleId.'"';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
		}
	    
    
    
   
}
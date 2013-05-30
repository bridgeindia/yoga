<?php
/* Created by : Lekha
 *  on : 23rd  April 2012
 *  Class that interacts with the  table fitness_workout_rates
 */
class FitnessWorkoutRates extends Zend_Db_Table
{
	
    protected $_name = 'fitness_workout_rates';
    
    
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'app_version'            => $data['app_version'],
	    'rate_single_workout'    => $data['rate_single_workout'],
	    'rate_total_workout'     => $data['rate_total_workout']
	    	    
	    );

    $db->insert('fitness_workout_rates', $data);
    	
    }
    
    
    
    function listRates()
    {
    	global $db;
	    	
	    	$sql = "SELECT * FROM fitness_workout_rates  order by id ASC";
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
    }
   
     function getRateDetails($rateId)
    {
    	global $db;
	    	
	    	$sql = "SELECT * FROM fitness_workout_rates  where id='".$rateId."'";
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
    }
   
    
     function getRateByVersion($version)
    {
    	global $db;
	    	
	    	$sql = "SELECT * FROM fitness_workout_rates  where app_version ='".$version."'";
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
    }
			   
		   
}
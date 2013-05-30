<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_equipments
 */
class FitnessEquipments extends Zend_Db_Table
{
	
    protected $_name = 'fitness_equipments';
    protected $_dependentTables = array(
    'FitnessEquipmentsMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'eqp_name'          => $data['eqp_name']
	    );

    $db->insert('fitness_equipments', $data);
    	
    }
    
    
     
    public function listEquipments()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_equipments  order by eqp_home_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
    
	    
	     public function getLastEquipId()
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT eqp_home_id FROM fitness_equipments order by eqp_home_id DESC limit 0,1';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
   
}
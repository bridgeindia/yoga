<?php
/* Created by : Lekha
 *  on : 29rd Feb 2012
 *  Class that interacts with the  table fitness_interests
 */
class FitnessInterests extends Zend_Db_Table
{
	
    protected $_name = 'fitness_interests';
    protected $_dependentTables = array(
    'FitnessInterestsMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'interest_name'          => $data['interest_name']
	    );

    $db->insert('fitness_interests', $data);
    	
    }
    
    
     
    public function listInterests()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_interests  order by interest_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
    
	    
	     public function getLastInterestsId()
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT interest_id FROM fitness_interests order by interest_id DESC limit 0,1';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		   
		   public function getInterest($interestId)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_interests where interest_id="'.$interestId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
   
}
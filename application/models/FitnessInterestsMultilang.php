<?php
/* Created by : Lekha
 *  on : 29 Feb 2012
 *  Class that interacts with the workouts table fitness_interests_multilang
 */
class FitnessInterestsMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_interests_multilang';
    
     protected $_referenceMap = array(
	'interests' => array(
	    'columns' => array('interest_id'),
	    'refTableClass' => 'FitnessInterests',
	    'refColumns' => array('interest_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'interest_id'       => $data['interest_id'],
	    'lang_id'                => $data['lang_id'],
	    'interest_name'          => $data['interest_name']
	    );

    $db->insert('fitness_interests_multilang', $data);
    	
    }
    
    
    
   
	    
	    
	    public function getInterests($interestId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT interest_id,interest_name FROM fitness_interests_multilang where lang_id="'.$lang.'" and interest_id="'.$interestId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($interestId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_interests_multilang where lang_id="'.$langId.'" and interest_id="'.$interestId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
				   public function getAllInterests($lang=1)
				    {
				    	global $db;
				    	$sql = 'SELECT interest_id,interest_name FROM fitness_interests_multilang where lang_id="'.$lang.'" order by id ASC';
				
						$result = $db->fetchAll($sql, 2);
						
						return $result;
				    }
		    
   
}
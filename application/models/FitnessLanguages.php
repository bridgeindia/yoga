<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts  table fitness_exercise_general
 */
class FitnessLanguages extends Zend_Db_Table
{
	
    protected $_name = 'fitness_languages';
    
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
    	'language_name'          => $data['langName'],
	    'language_code'          => $data['langCode'],
	    'language_flag'         => $data['iconName']
	    );

	 
	 return $db->insert('fitness_languages', $data);
   
    	
    }
    
    
    public function getLanguages()
    {
    	    global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_languages  order by language_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
    }
    
     public function getLanguage($langId)
    {
    	    global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_languages where language_id="'.$langId.'" ';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
    }
    public function setDefaultLanguage($langId)
    {
    	 global $db;
    	 
    	 $sql_update_all = 'UPDATE fitness_languages set default_language=0';
    	 $db->query($sql_update_all);
    	 
    	 $sql_update = 'UPDATE fitness_languages set default_language=1 where language_id="'.$langId.'"';
    	 $db->query($sql_update);
    	 
    }
    
     public function setStatusLanguage($langId,$status)
    {
    	 global $db;
    	 
    	 
    	 $sql_update = 'UPDATE fitness_languages set language_state="'.$status.'" where language_id="'.$langId.'"';
    	 $db->query($sql_update);
    	 
    }
    
     public function getDefaultLanguage()
    {
    	 global $db;
    	 
    	 
    	 $sql = 'SELECT language_id FROM fitness_languages where default_language="1" ';
    	 $result = $db->fetchRow($sql, 2);
			
		return $result;
    	 
    }
}
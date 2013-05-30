<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_website_pages
 */
class FitnessWebsitePages extends Zend_Db_Table
{
	
    protected $_name = 'fitness_website_pages';
    protected $_dependentTables = array(
    'FitnessWebsitePagesMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'page_name'          => $data['page_name']
	    );

    return $db->insert('fitness_website_pages', $data);
    	
    }
    
    
    public function getLastPageId()
   {
      	global $db;
	    	
	    	$sql = 'SELECT page_id FROM fitness_website_pages order by page_id DESC limit 0,1';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
   }
   
   
    public function listPages()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_website_pages  order by page_id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	    public function getPageByName($name)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT page_id FROM fitness_website_pages where page_name="'.$name.'"  order by page_id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
		
		 public function getPageById($id)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT page_name FROM fitness_website_pages where page_id="'.$id.'"';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
    
    
   
}
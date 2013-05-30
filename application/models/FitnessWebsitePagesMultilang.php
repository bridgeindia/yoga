<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table fitness_website_pages_multilang
 */
class FitnessWebsitePagesMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_website_pages_multilang';
    
    protected $_referenceMap = array(
	'muscle' => array(
	    'columns' => array('page_id'),
	    'refTableClass' => 'FitnessWebsitePages',
	    'refColumns' => array('page_id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'page_id'          => $data['page_id'],
	    'lang_id'          => $data['lang_id'],
	    'page_title'       => $data['page_title'],
	    'page_content'     => $data['page_content'],
		'page_content_sub'     => $data['page_content_sub'],
		'page_section1'     => $data['page_section1'],
		'page_section2'     => $data['page_section2'],
		'page_section1_title'     => $data['page_section1_title'],
		'page_section2_title'     => $data['page_section2_title']
	    );

    $db->insert('fitness_website_pages_multilang', $data);
    	
    }
    
    
    
     public function listPages($lang=1)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_website_pages_multilang where lang_id="'.$lang.'" order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
	    
	    public function getPage($pageId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT * FROM fitness_website_pages_multilang where lang_id="'.$lang.'" and page_id="'.$pageId.'" order by id ASC';
	       
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($pageId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_website_pages_multilang where lang_id="'.$langId.'" and page_id="'.$pageId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
		    public function listPageIds($lang=1)
			    {
			    	global $db;
			    	
			    	$sql = 'SELECT page_id FROM fitness_website_pages_multilang where lang_id="'.$lang.'" order by id ASC';
			
					$result = $db->fetchAll($sql, 2);
					
					return $result;
			    }
	    
	     public function AllPages()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_website_pages_multilang  order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
   
}
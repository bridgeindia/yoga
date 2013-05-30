<?php
/* Created by : Lekha
 *  on : 23rd July 2012
 *  Class that interacts with the workouts table fitness_news_category_multilang
 */
class FitnessNewsCategoryMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_news_category_multilang';
    
     protected $_referenceMap = array(
	'news' => array(
	    'columns' => array('category_id'),
	    'refTableClass' => 'FitnessNewsCategory',
	    'refColumns' => array('id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'category_id'       => $data['category_id'],
	    'lang_id'                => $data['lang_id'],
		'category_name'          => $data['name']
	   	    );

    $db->insert('fitness_news_category_multilang', $data);
    	
    }
    
    
	    
	    
	    public function getNewsCategory($catId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT * FROM fitness_news_category_multilang where lang_id="'.$lang.'" and category_id="'.$catId.'" order by id ASC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($catId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_news_category_multilang where lang_id="'.$langId.'" and category_id="'.$catId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
				   public function getAllcategory($lang=1)
				    {
				    	global $db;
				    	$sql = 'SELECT * FROM fitness_news_category_multilang where lang_id="'.$lang.'" order by id ASC';
				
						$result = $db->fetchAll($sql, 2);
						
						return $result;
				    }
		    
   
}
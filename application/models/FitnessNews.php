<?php
/* Created by : Lekha
 *  on : 23rd July 2012
 *  Class that interacts with the  table fitness_news
 */
class FitnessNews extends Zend_Db_Table
{
	
    protected $_name = 'fitness_news';
    protected $_dependentTables = array(
    'FitnessNewsMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'news'          => $data['news'],
		'news_category' => $data['category'],
		'news_date'     => $data['news_date']
	    );

    $db->insert('fitness_news', $data);
    	
    }
    
    
     
    public function listNews()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_news  order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
    
	    
	     public function getLastNewsId()
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT id FROM fitness_news order by id DESC limit 0,1';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		   
		   public function getNews($newsId)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_news where id="'.$newsId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		    public function getNewsByCategory($catId)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_news where news_category="'.$catId.'" order by id DESC';
			
					$result = $db->fetchAll($sql, 2);
					
					return $result;
		   }
		   
		   public function getNewsByDate($date)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_news where news_date like "%'.$date.'%"';
			
					$result = $db->fetchAll($sql, 2);
					
					return $result;
		   }
   
}
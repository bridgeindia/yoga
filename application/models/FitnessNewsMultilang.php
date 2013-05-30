<?php
/* Created by : Lekha
 *  on : 23rd July 2012
 *  Class that interacts with the workouts table fitness_news_multilang
 */
class FitnessNewsMultilang extends Zend_Db_Table
{
	
    protected $_name = 'fitness_news_multilang';
    
     protected $_referenceMap = array(
	'news' => array(
	    'columns' => array('news_id'),
	    'refTableClass' => 'FitnessNews',
	    'refColumns' => array('id'),
	    'onDelete' => self::CASCADE
	));
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'news_id'       => $data['news_id'],
	    'lang'                => $data['lang_id'],
		'news_title'          => $data['news_title'],
	    'news_content'          => $data['news_content']
	    );

    $db->insert('fitness_news_multilang', $data);
    	
    }
    
    
    
   
	    
	    
	    public function getNews($newsId,$lang=1)
	    {
	    	global $db;
	    	$sql = 'SELECT * FROM fitness_news_multilang where lang="'.$lang.'" and news_id="'.$newsId.'" order by id DESC';
	
			$result = $db->fetchRow($sql, 2);
			
			return $result;
	    }
	    
	    public function getLangRecord($newsId,$langId)
		   {
		   	 
			    	global $db;
			    	
			    	$sql = 'SELECT count(*) as count FROM fitness_news_multilang where lang="'.$langId.'" and news_id="'.$newsId.'" order by id ASC';
			        
					$result = $db->fetchRow($sql, 2);
					
					return $result;
			    
		   }
		   
				   public function getAllInterests($lang=1)
				    {
				    	global $db;
				    	$sql = 'SELECT news_id,news_content,news_title FROM fitness_news_multilang where lang="'.$lang.'" order by id DESC';
				
						$result = $db->fetchAll($sql, 2);
						
						return $result;
				    }
		    
   
}
<?php
/* Created by : Lekha
 *  on : 23rd July 2012
 *  Class that interacts with the  table fitness_news_category
 */
class FitnessNewsCategory extends Zend_Db_Table
{
	
    protected $_name = 'fitness_news_category';
    protected $_dependentTables = array(
    'FitnessNewsCategoryMultilang'
    );
    
    
    public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'category_name'          => $data['name']
	    );

    $db->insert('fitness_news_category', $data);
    	
    }
    
    
     
    public function listNewsCategory()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT * FROM fitness_news_category  order by id ASC';
	
			$result = $db->fetchAll($sql, 2);
			
			return $result;
	    }
	    
    
	    
	     public function getLastCategoryId()
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT id FROM fitness_news_category order by id DESC limit 0,1';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
		   
		   
		   public function getCategory($catId)
		   {
		      	   global $db;
			    	
			    	$sql = 'SELECT * FROM fitness_news_category where id="'.$catId.'"';
			
					$result = $db->fetchRow($sql, 2);
					
					return $result;
		   }
   
}
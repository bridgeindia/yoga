<?php
/* Created by : Lekha
 *  on : 29th Feb 2012
 *  Class that interacts with the  table fitness_user_general
 */
class FitnessUserGeneral extends Zend_Db_Table
{
	
    protected $_name = 'fitness_user_general';
    protected $_dependentTables = array(
    'fitness_user_membership',
    'fitness_user_settings',
    'fitness_user_types',
    'fitness_user_workouts_done',
    'fitness_user_workouts_unlocked',
    'fitness_user_custom_workouts'
    );
    
    
    
     public function addData($data)
    {
    	global $db;
    	
    	$data = array(
	    'user_first_name'   => $data['user_first_name'],
	    'user_surname'      => $data['user_surname'],
	    'user_dob'          => $data['user_dob'],
	    'user_gender'       => $data['user_gender'],
	    'user_email'        => $data['user_email'],
	    'user_username'     => $data['user_username'],
	    'user_password'     => $data['user_password'],
	    'user_workout_level'=> $data['user_level'],
	    'user_type'         => $data['user_type'],
	    'user_status'       => $data['user_status'],
	    'terms_conditions'  => 1
	    
	    );

    $db->insert('fitness_user_general', $data);
    	
    }
    
    
    public function listUsers()
    {
    	global $db;
    	
    	
    	$sql = "select * from fitness_user_general order by user_id ASC";
    	$result = $db->fetchAll($sql, 2);
    	
    	return $result;
    }
    
    
     public function getUser($userId)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_general where user_id='".$userId."' order by user_id ASC";
			
	    	$result  = $db->fetchRow($sql, 2);
	    	return $result;
	    }
	    
	     public function getUserbyUsername($username)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_general where user_username='".$username."' order by user_id ASC";
	    	$result  = $db->fetchRow($sql, 2);
	    	return $result;
	    }
	    
	    
	    
	    public function getUserbyEmail($email)
	    {
	    	global $db;
	    	
	    	
	    	$sql = "select * from fitness_user_general where user_email='".$email."' order by user_id ASC";
	    	$result  = $db->fetchRow($sql, 2);
	    	return $result;
	    }
	    
	    
	    public function getLastUserId()
			   {
			      	global $db;
				    	
				    	$sql = 'SELECT user_id FROM fitness_user_general order by user_id DESC limit 0,1';
				
						$result = $db->fetchRow($sql, 2);
						
						return $result;
			   }
			   
			    public function checkRecordExists($username)
				   {
				      	global $db;
					    	
					    	$sql = 'SELECT count(*) as count FROM fitness_user_general where user_username="'.$username.'" order by user_id DESC limit 0,1';
					
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
			   
			    public function checkEmailExists($email)
				   {
				      	global $db;
					    	
					    	$sql = 'SELECT count(*) as count FROM fitness_user_general where user_email="'.$email.'" order by user_id DESC limit 0,1';
					
							$result = $db->fetchRow($sql, 2);
							
							return $result;
				   }
			   
			   
			    public function checkUserLogin($username,$password)
				    {
				    	global $db;
				    	
				    	
				    	$sql = "SELECT count(*) as count from fitness_user_general where user_username='".$username."' and user_password='".$password."' and user_status=1 order by user_id ASC";
				    	$result  = $db->fetchRow($sql, 2);
				    	return $result;
				    }
			   
				    public function userLogin($username)
				    {
				    	 global $db;
				    	
				    	$sql = 'UPDATE fitness_user_general set user_login=user_login +1 ,last_login=CURDATE() where user_username="'.$username.'"';
				
						$db->query($sql);
				    	
				    	
				    }
				    
				     public function getLastLogin($username)
					   {
					      	global $db;
						    	
						    	$sql = 'SELECT user_login FROM fitness_user_general where user_username="'.$username.'" ';
						
								$result = $db->fetchRow($sql, 2);
								
								return $result;
					   }
					   
					   public function terminateAccount($username)
					   {
					      	global $db;
						    	
						    	$sql = 'UPDATE fitness_user_general set user_status=3  where user_username="'.$username.'"';
						
								$db->query($sql);
					   }
					   
					   public function userWorkoutPurchase($userid)
						    {
						    	 global $db;
						    	
						    	$sql = 'UPDATE fitness_user_general set workout_purchase=1 where user_id="'.$userid.'"';
						
								$db->query($sql);
						    	
						    	
						    }
							
							  public function getUserByPurchase()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT user_id FROM fitness_user_general where workout_purchase=1';
									
											$result = $db->fetchAll($sql, 2);
											
											return $result;
								   }
								   
		                         public function getUserCount()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT user_id FROM fitness_user_general where user_device=0';
									
											$result = $db->fetchAll($sql, 2);
											
											return $result;
								   }
								   
								    public function getIphoneUserCount()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT count(*) as count FROM fitness_user_general where user_device=1';
									
											$result = $db->fetchRow($sql, 2);
											
											return $result;
								   }
								   
								    public function getAndroidUserCount()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT count(*) as count FROM fitness_user_general where user_device=2';
									
											$result = $db->fetchRow($sql, 2);
											
											return $result;
								   }
								   
								    public function getIphonePurchaseCount()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT * FROM fitness_user_general where user_device=1 and workout_purchase=1';
									
											$result = $db->fetchAll($sql, 2);
											
											return $result;
								   }
								   public function getIphonePremiumUsers()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT * FROM fitness_user_general where user_device=1 and plan=1 order by user_id DESC';
									
											$result = $db->fetchAll($sql, 2);
											
											return $result;
								   }
								    public function getAndroidPremiumUsers()
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT * FROM fitness_user_general where user_device=2 and plan=1 order by user_id DESC';
									
											$result = $db->fetchAll($sql, 2);
											
											return $result;
								   }
								   
								   public function checkUserPremium($userid)
								   {
								      	     global $db;
									    	
									    	$sql = 'SELECT plan FROM fitness_user_general where user_id="'.$userid.'"';
									       
											$result = $db->fetchRow($sql, 2);
											
											return $result;
								   }
}
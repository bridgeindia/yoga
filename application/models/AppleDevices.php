<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table apns_devices
 */
class AppleDevices extends Zend_Db_Table
{
	
	
	 public function addData($data)
	    {
	    	global $db;
	    	
	    	
	    	$data = array(
	    	'user_id'          => $data['user_id'],
		    'deviceuid'        => $data['deviceuid'],
		    'devicetoken'      => $data['devicetoken']
		    
		    );
	
	    $db->insert('apple_devices', $data);
	    	
	    }
	    
	    
	    public function getDeviceName($token)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT devicename FROM apns_devices where devicetoken="'.$token.'"';
			
		    $result = $db->fetchRow($sql, 2);
					
		    return $result;
	    }
	    
       public function getAllDevices()
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT user_id,devicetoken FROM apple_devices';
			
		    $result = $db->fetchAll($sql, 2);
					
		    return $result;
	    }
		
		 public function getDeviceByUser($userid)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT devicetoken FROM apple_devices where user_id="'.$userid.'"';
			
		    $result = $db->fetchRow($sql, 2);
					
		    return $result;
	    }
}
?>
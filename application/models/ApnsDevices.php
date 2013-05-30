<?php
/* Created by : Lekha
 *  on : 23rd Feb 2012
 *  Class that interacts with the workouts table apns_devices
 */
class ApnsDevices extends Zend_Db_Table
{
	
	
	 public function addData($data)
	    {
	    	global $db;
	    	
	    	
	    	$data = array(
	    	'user_id'          => $data['user_id'],
		    'appname'          => $data['appname'],
		    'appversion'       => $data['appversion'],
		    'deviceuid'        => $data['deviceuid'],
		    'devicetoken'      => $data['devicetoken'],
		    'devicename'       => $data['devicename'],
		    'devicemodel'      => $data['devicemodel'],
		    'deviceversion'    => $data['deviceversion'],
		    'pushbadge'        => $data['pushbadge'],
		    'pushalert'        => $data['pushalert'],
		    'pushsound'        => $data['pushsound'],
		    'status'           => 'active',
		    'created'          => date('Y-m-d h:m:s'),
		    'modified'         => strtotime(date('Y-m-d'))
		    );
	
	    $db->insert('apns_devices', $data);
	    	
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
	    	
	    	$sql = 'SELECT devicetoken FROM apns_devices';
			
		    $result = $db->fetchAll($sql, 2);
					
		    return $result;
	    }
		
		 public function getDeviceByUser($userid)
	    {
	    	global $db;
	    	
	    	$sql = 'SELECT devicetoken FROM apns_devices where user_id="'.$userid.'"';
			
		    $result = $db->fetchRow($sql, 2);
					
		    return $result;
	    }
}
?>
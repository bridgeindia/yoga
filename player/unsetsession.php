<?php session_start();
if($_SESSION['selfworkout']!="")
{
 unset($_SESSION['selfworkout']);	
}
echo "1";
?>
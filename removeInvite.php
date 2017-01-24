<?php
session_start();
require 'conn.php';

if(isset($_POST['userName']))
{
	$userName=$_POST['userName'];
	$soba=$_POST['soba'];
	$sql="DELETE from invites WHERE user_name='".$userName."' AND invited_in_room='".$soba."' ";
	$conn->query($sql);
	echo "Uspesno";
}


?>
<?php
session_start();
require 'conn.php';

function getUsers()
{
	global $conn;
	
	$niza=[];
	$pod=$conn->prepare("SELECT name,surname from users");
	$pod->execute();

	while($result=$pod->fetch(PDO::FETCH_ASSOC))
	{
		$imeprezime=$result["name"].' '.$result["surname"];
		array_push($niza,$imeprezime);
	}
	return $niza;
}

echo json_encode(getUsers());


?>
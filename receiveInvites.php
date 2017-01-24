<?php
session_start();
require 'conn.php';
function receiveInvites()
{
	global $conn;

	$niza=[];
	$sql="SELECT invited_in_room FROM invites WHERE user_name='".$_SESSION['name']."' ";
	$q=$conn->prepare($sql);
	$q->execute();
	while($row=$q->fetch(PDO::FETCH_ASSOC))
	{
		$soba=$row['invited_in_room'];
		array_push($niza,$soba);
	}
	return $niza;
}

echo json_encode(receiveInvites());

?>
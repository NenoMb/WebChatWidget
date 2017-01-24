<?php
session_start();
require './conn.php';
if(isset($_POST['newRoom'])){
	$roomName = $_POST['newRoom'];

	$sql = "Select * from room where name='".$roomName."' ";
	$q = $conn->query($sql);
	if($q->fetch()){
		echo '0';
	}
	else{
		$sql = "INSERT INTO `room` (`id_room`, `name`) VALUES ('','".$roomName."')";
		$conn->query($sql);


		$sql = "Select * from room order by id_room DESC ";
		$q = $conn->query($sql);
		$row = $q->fetch();
		$id_room = $row['id_room'];

		$sql = "INSERT INTO `room_has_user` (`id_room`, `id_user`) VALUES ('".$id_room."','".$_SESSION['id_user']."')";
		$conn->query($sql);

		$fp = fopen($roomName.'.html',"w");
		fclose($fp);
	}



}


?>
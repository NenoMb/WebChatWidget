<?php
session_start();
require './conn.php';
if(isset($_POST['sendInvite'])&&$_POST['VoSobaPokanet']!="Public"){

	$imeNaPokanet=$_POST['sendInvite'];
	$imeNaSoba=$_POST['VoSobaPokanet'];

	$sql="INSERT INTO invites VALUES('','".$imeNaPokanet."','".$imeNaSoba."')";
	$conn->query($sql);
		
	$sql="SELECT id_room FROM room where name='".$imeNaSoba."' ";
	$q=$conn->query($sql);
	$row=$q->fetch(PDO::FETCH_ASSOC);
	
		$id_room=$row['id_room'];

	$delovi=explode(" ",$imeNaPokanet);

	$sql="SELECT id_user FROM users WHERE name='".$delovi[0]."' and surname='".$delovi[1]."' ";
	$q=$conn->query($sql);
	$row=$q->fetch(PDO::FETCH_ASSOC);

	$id_user=$row['id_user'];


	$sql="INSERT INTO room_has_user VALUES('".$id_room."','".$id_user."')";
	$conn->query($sql);
	echo $_SESSION['id_user'];

}
else echo "Ne moze da se dodade vo public soba";


?>
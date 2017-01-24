<?php
session_start();
require 'conn.php';

function logedUserRooms(){

	global $conn;

	$loggedUserId = $_SESSION['id_user'];
	$sql = "Select r.name from room_has_user ru,room r where ru.id_room=r.id_room and ru.id_user='".$loggedUserId."'";
	$q = $conn->query($sql);
	$str = '<select id="select" class="form-control">';
	$str .= "<option selected disabled>Select a room</option>";
	$hasRooms = false;
	while($row = $q->fetch()){
		$hasRooms = true;
		$str .= "<option>".$row['name']."</option>";
	}
	$str .= '</select>';

	return $str;

}
echo logedUserRooms();


?>
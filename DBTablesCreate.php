<?php
/**
 * Created by PhpStorm.
 * User: Neno
 * Date: 21.01.2017
 * Time: 10:31
 */
require './conn.php';
//
$sql = 'USE `webchat`;

CREATE TABLE `users` (
	`id_user` Int( 255 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`surname` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`password` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
        PRIMARY KEY ( `id_user` )

 )

';


if ($conn->query($sql)) {
    echo 'The table USERS was created<br>';
} else {
    echo 'The table was NOT created <br>';
}


$sql = 'USE `webchat`;

CREATE TABLE `room` (
	`id_room` Int( 255 ) AUTO_INCREMENT NOT NULL,
	`name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
     PRIMARY KEY ( `id_room` )

 )

';

if ($conn->query($sql)) {
    echo 'The table ROOM was created<br>';
} else {
    echo 'The table was NOT created <br>';
}


$sql = 'USE `webchat`;

CREATE TABLE `room_has_user` (
	`id_room` Int( 255 )  NOT NULL,
	`id_user` Int( 255 )  NOT NULL,
     PRIMARY KEY ( `id_room`, `id_user` ),
     FOREIGN KEY (`id_room`) REFERENCES room(id_room)
     ON DELETE CASCADE,
     FOREIGN KEY (`id_user`) REFERENCES users(id_user)

 )

';

if ($conn->query($sql)) {
    echo 'The table ROOM_HAS_USER was created<br>';
} else {
    echo 'The table was NOT created <br>';
}

$sql='USE `webchat`;

CREATE TABLE `invites` (
    `id_invite` Int( 255 )  AUTO_INCREMENT NOT NULL,
    `user_name` VarChar( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `invited_in_room` VarChar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
     PRIMARY KEY ( `id_invite`)
)

';

if ($conn->query($sql)) {
    echo 'The table invites was created<br>';
} else {
    echo 'The table was NOT created <br>';
}



?>
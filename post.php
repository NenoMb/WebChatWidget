<?php
session_start();
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
    $imeChat=$_POST['imeChat'];
     
    $fp = fopen($imeChat.".html", 'a');
    fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
}
?>
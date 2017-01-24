<?php

session_start ();
require 'conn.php';


function loginForm() {    //Login form
	echo '
		<div id="loginform">
        <form action="index.php" method="post">
            <p>Please log in to continue:</p>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"><input type="text" name="email" id="name" class="form-control" placeholder="enter your e-mail" /></div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"> <input type="password" name="password" class="form-control" placeholder="enter your password"></div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <input type="submit" name="enter" id="enter" value="Log in" class="btn btn-primary" />
        	<br><br>
        	<a href="signUp.php">Don\'t have an account yet? Sign up now </a>
    	</form>
    	</div>
    ';
}

function getUsers()  //get all registered users from database
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

function logedUserRooms(){ //get all created rooms from database

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

	echo $str;

}



function loadChatbox($imeSoba)   //load the chatbox for the room
{
	if (file_exists ($imeSoba.".html") && filesize ($imeSoba.".html") > 0) {
			$handle = fopen ( $imeSoba.".html", "r" );
			$contents = fread ( $handle, filesize ( $imeSoba.".html" ) );
			fclose ( $handle );
			
			echo $contents;
	}
}




if (isset ( $_POST ['enter'] )) {  //login validation

	$email = trim($_POST['email']);
	$pass = trim($_POST['password']);

	$sql = "Select * from `users` WHERE email='".$email."' and password='".$pass."'";
	$q = $conn->query($sql);
	if(!$q->fetch()){
		echo '<span class="error">Your email/password combination is wrong</span>';
	}
	else{
		$q = $conn->query($sql);
		$row = $q->fetch();
		$_SESSION ['name'] = $row['name'].' '.$row['surname'];
		$_SESSION ['id_user'] = $row['id_user'];
		$fp = fopen ( "Public.html", 'a' );
		fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has joined the chat session.</i><br></div>" );
		fclose ( $fp );
	}

}

if (isset ( $_GET ['logout'] )) {   //logout validation
	
	// Simple exit message
	$fp = fopen ( "Public.html", 'a' );
	fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has left the chat session.</i><br></div>" );
	fclose ( $fp );
	
	session_destroy ();
	header ( "Location: index.php" ); // Redirect the user
}
	
	

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css?<?php echo time(); ?>">
<link rel="stylesheet" href="jquery-ui.css" />
<script type="text/javascript"
		src="jquery-3.1.1.js"></script>
  <script src="jquery-ui.js"></script>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap.min.css" >

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap-theme.min.css" >

<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap-3.3.7/js/bootstrap.min.js"> </script>
<title>Chat - Customer Module</title>
</head>
<body>
	<?php
	if (! isset ( $_SESSION ['name'] )) {
		loginForm ();
	} else {
		?>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div id="wrapper">
					<form action="index.php" method="post" style="margin-left: 5%">
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8" style="margin-left: 8%">
								<input type="text" name="newRoom" id="search" />
								<input type="button" value="Add room" name="addRoom" id="addRoom" class="btn btn-primary" />
							</div>
							<div class="col-md-2"></div>
						</div>
					</form>
					<br>
					<div class="row">
						<div class="col-md-6"></div>
						<div class="col-md-6">
							<div class="dropdown" style="display:inline; margin-left: 50%; top:-51px">
								<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Invite
									<span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li></li>
									<input type="text" name="AddPerson" id="dodadi" />
								</ul>
							</div>
						</div>
					</div>
					<div style="width: 90%;margin-left: 5%"><?php logedUserRooms();?></div>
					<div id="menu">
						<p class="welcome">
							Welcome, <b><?php echo $_SESSION['name']; ?></b>
						</p>
						<p class="logout">
							<a id="exit" href="#">Exit Chat</a>
						</p>
						<div style="clear: both"></div>
					</div>
					<div id="tabs">
						<ul>
							<li><a href="#tabs-1">Public</a></li>
						</ul>
						<div id="tabs-1">
							<div id="Public" class='chatbox'><?php
								loadChatbox("Public");
								?></div>
						</div>
					</div>
					<form name="message" action="">
						<input name="usermsg" type="text" id="usermsg" size="63" />
						<input name="submitmsg" type="submit" id="submitmsg" value="Send" class="btn btn-primary"/>
					</form>
				</div>
				<div class="col-md-2"></div>
			</div>
		</div>

	<link rel="stylesheet" href="jquery-ui.css" />
<script type="text/javascript"
		src="jquery-3.1.1.js"></script>
  <script src="jquery-ui.js"></script>
	<script type="text/javascript">

//jQuery Document
$(document).ready(function(){

	//If user wants to end session
	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if(exit==true){window.location = 'index.php?logout=true';}		
	});


	//Adding a chat room
	$("#addRoom").click(function(){

		var imeSoba=$("#search").val();
		$('#select').append($('<option>', {
			text: imeSoba
		}));
		if(imeSoba!="")
		{
			$.post("addRoom.php",{newRoom: imeSoba},function(response,status){
				if(response=='0')
					alert("The room already exists");
				else
				{
					addTab(imeSoba);

				}

			});
			
		}
		
	});

var users= <?php echo json_encode(getUsers()) ?>; //getting logged users for autocomplete

$("#dodadi").autocomplete({

 	source: users


 });


//ajax call for all registered user
function getUsers()
{
	$.get("getUsers.php",function(data){
		users=data;		
	},"json");

	 $("#dodadi").autocomplete({

 	source: users


 });
}

var selectedTabName="Public";

//If user submits the form
$("#submitmsg").click(function(){
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg, imeChat: selectedTabName});				
		$("#usermsg").attr("value", "");
	
		$("#usermsg").val("");
	return false;
});

	

   	var tabs=$( "#tabs" ).tabs({activate: function(){
   		selectedTabName=$("#tabs .ui-tabs-active").text();
   		//console.log(selectedTabName);

   	}});

   	var numTabs=2;	

   	$("#dodadi").bind("keypress", {}, keypressInBox);

function keypressInBox(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) { //Enter keycode                        
        e.preventDefault();
        sendInvite($("#dodadi").val(),selectedTabName);

        $("#dodadi").val("");
    }
};

function sendInvite(imeNaPokanet,selectedTabName) //ajax call for sending an invite to a user
{	
	var name= '<?php echo $_SESSION["name"]; ?>';

	if(imeNaPokanet!=name)
	$.post("sendInvite.php",{sendInvite: imeNaPokanet,VoSobaPokanet:selectedTabName});
}

var sobi=[];
function receiveInvite(imeUser)  //if user has been invited, add him to room
{
	
	
	for(var i=0;i<sobi.length;i++)
	{
		addTab(sobi[i]);
		$.post("removeInvite.php",{userName: imeUser,soba: sobi[i]},function(response,status){
			

		});
	}

	setTimeout(getInvites,1000);


}

function getInvites() //getting invites from database
{
	$.get("receiveInvites.php",function(data){
		sobi=data;
		
	},"json");
}

function getRooms() //ajax call for created rooms
{
	$.get("getRooms.php",function(data){
		$("#select").replaceWith(data);
		
	});
	
}

$(document).on("change",'#select',function(){

	var imeSoba = $(this).val();
	addTab(imeSoba);
});




function addTab(imeSoba)  //adding a new room tab to the chat tabs with the room conversation
{
	 

        tabs.find(".ui-tabs-nav").append(
            "<li><a href='#tabs-" + numTabs + "'>" + imeSoba + "</a><span class='ui-icon ui-icon-circle-close ui-closable-tab'></span></li>"
        );
		tabs.append("<div id='tabs-"+numTabs+"'><div id='"+imeSoba+"' class='chatbox'><?php loadChatbox("+imeSoba+"); ?></div></div>");
       	tabs.tabs("refresh");
		numTabs++;

}
	
$(document).on( "click",'.ui-closable-tab', function() {  //closing a tab
       
        var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
      //  console.log(panelId);
        $( "#" + panelId ).remove();
        tabs.tabs("refresh");
        
    });

  

function loadLog(selectedTabName){		//loading the chatbox
	var oldscrollHeight = $("#"+selectedTabName).prop("scrollHeight") - 20; 
	$.ajax({
		url: selectedTabName+".html",
		cache: false,
		success: function(html){		
			$("#"+selectedTabName).html(html); 	
			
			//Auto-scroll			
			var newscrollHeight = $("#"+selectedTabName).prop("scrollHeight") - 20; 
			if(newscrollHeight > oldscrollHeight){
				$("#"+selectedTabName).animate({ scrollTop: newscrollHeight }, 'normal'); 
			}				
	  	},
	});
}

var name= '<?php echo $_SESSION["name"]; ?>';
setInterval(function(){loadLog(selectedTabName)}, 1500); //refreshing the chatbox

setInterval(function(){receiveInvite(name)},2000); //checking if a user has been invited

setInterval(function(){getUsers()},10000); //checking if a new user has registered
setInterval(function(){getRooms()},10000); //checking if a new room has been created

 });
</script>
<?php
	}
	?>
	<link rel="stylesheet" href="jquery-ui.css" />
<script type="text/javascript"
		src="jquery-3.1.1.js"></script>
  <script src="jquery-ui.js"></script>
	<script type="text/javascript">
</script>
</body>
</html>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="jquery-ui.css" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap.min.css" >

    <!-- Optional theme -->
    <link rel="stylesheet" href="bootstrap-3.3.7/css/bootstrap-theme.min.css" >

    <!-- Latest compiled and minified JavaScript -->
    <script src="bootstrap-3.3.7/js/bootstrap.min.js"> </script>
    <script type="text/javascript"
            src="jquery-3.1.1.js"></script>
    <script src="jquery-ui.js"></script>

    <title>Chat - Customer Module</title>
</head>
<body>
    <div id="loginform">
        <form action="signUp.php" method="post">
            <p>Enter your information:</p>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"><input type="text" name="name" id="name" class="form-control" placeholder="enter your name" /></div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"> <input type="text" name="surname" class="form-control" placeholder="enter your surname"></div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"><input type="text" name="email" id="name" class="form-control" placeholder="enter your email" /></div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"><input type="password" name="password" class="form-control" placeholder="enter your password" /></div>
                <div class="col-md-2"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"><input type="password" name="ConfirmPassword" class="form-control" placeholder="confirm password: " /></div>
                <div class="col-md-2"></div>
            </div>
            <br>

            <input type="submit" name="enter" id="enter" value="Sign up" class="btn btn-primary" />
        </form>
    </div>
</body>
</html>


<?php
session_start ();
require 'conn.php';

if(isset($_POST['enter'])){
    $errors = [];
    $name =  trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $conPass = trim($_POST['ConfirmPassword']);


    if(strlen(trim($name)) == 0){
        $errors[] = 'You must enter a name';
    }

    if(strlen(trim($surname)) == 0){
        $errors[] = 'You must enter a surname';
    }

    if(strlen(trim($email)) == 0){
        $errors[] = 'You must enter an email';
    }
    else if(strpos($email, "@") == false ){
        $errors[] = 'You must enter an valid email';
    }
    else{
        $sql = "Select * from `users`";
        $q = $conn->query($sql);
        $email = trim($email);
        while($row = $q->fetch()){
            if($row['email'] == $email){
                $errors[] = 'That email already exists';
                break;
            }
        }
    }

    if(strlen(trim($password)) == 0){
        $errors[] = 'You must enter a password';
    }

    if(strlen(trim($conPass)) == 0){
        $errors[] = 'You must confirm your password';
    }

    if($conPass != $password){
        $errors[] = 'The passwords doesn\'t match';
    }

    if(sizeof($errors) == 0){
        $sql = "INSERT INTO `users` (`id_user`, `name`, `surname`, `password`, `email`) VALUES ('','".$name."','".$surname."','".$password."','".$email."')";
        if($conn->query($sql)){
            $_SESSION['name'] = $name.' '.$surname;
            
        }
        $sql="SELECT `id_user` from `users` WHERE `name`='".$name."' AND `surname`='".$surname."' ";
        $q=$conn->query($sql);
        if($row=$q->fetch())
        {
            $_SESSION['id_user']=$row['id_user'];
            header('Location: index.php');
        }

        

    }


    foreach($errors as $error){
        echo "<span class='error'> $error </span><br>";
    }

}



?>
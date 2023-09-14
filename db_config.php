<?php

$server_name ="localhost";
$user_name = "root";
$password = "Jerule@499";
$db_name = "Person";

$conn = mysqli_connect($server_name, $user_name, $password, $db_name);

if(!$conn){
    die("Connection Failed: ".mysqli_connect());
}


?>
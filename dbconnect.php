<?php
$username = 'Princie';
$password = 'mimi1306';


$connection = mysqli_connect("localhost",$username, $password);
mysqli_select_db($connection, "cinema");
?>
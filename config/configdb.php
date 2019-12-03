<?php
$conn = mysqli_connect('localhost','root','');
if(!$conn) {
	die("Connection failed ". mysqli_error($conn));
}
$my_db = mysqli_select_db($conn,'lmsdb') ;
if(!$my_db) {
	die("Database Selection failed".mysqli_error($conn));
}
?>

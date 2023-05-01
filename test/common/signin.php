<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include 'config.php';
include $path.'common/function.php';
$c=conn();
 $res = mysqli_query($c, "select * from utente where username='".$_POST['email']."' and stato=1");
 if(  mysqli_num_rows($res)>0){
    $row=mysqli_fetch_assoc($res);
    if(password_verify($_POST['password'], $row['password'])) {

      caricaSession($row['id']);
      ScriviLog("LOGIN EFFETTUATO DA ".strtoupper($_POST['email']),$row['id']);
      if( $row['privacy']==""){
       header('location: ../dashboard/index.php');
      }else{

        header('location: ../dashboard/index.php');

      }

    }else{
      header('location: login.php?error=2');
    }

 }else{
     header('location: login.php?error=1');
 }

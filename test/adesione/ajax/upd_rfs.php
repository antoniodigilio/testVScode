<?php
session_start();
include '../../common/config.php';
require_once($path.'common/config.php');
require_once($path.'common/function.php');
$c=conn();
$queryUpd = "update giornata set dataInizio='" . $_POST['dal'] . "',datafine='" . $_POST['al'] . "',nOre='" . $_POST['nOre'] . "',min='" .  $_POST['nOre']*60 . "',oraInizio='" . $_POST['oraInizio'] . "',";
$queryUpd .=" oraFine='" . $_POST['oraFine']  . "',ggSettimana='" . $_POST['ggSettimana']  . "'  where id=".$_POST['id']." and idAdesione=".$_POST['idAdesione'];

$qIns = mysqli_query($c, $queryUpd);


?>
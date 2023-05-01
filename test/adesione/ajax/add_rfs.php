<?php
session_start();
include '../../common/config.php';
require_once($path.'common/config.php');
require_once($path.'common/function.php');
$c=conn();
$queryIns = "insert into giornata (idAdesione,idEsigenza,dataInizio,datafine,min,nOre, oraInizio,oraFine,dataInserimento,ggSettimana) values ";
$queryIns .= " (" . $_POST['idAdesione'] . ",122,'" . $_POST['dal'] . "','" . $_POST['al'] . "','" . $_POST['nOre']*60 . "','" . $_POST['nOre'] . "',";
$queryIns .="'" . $_POST['oraInizio'] . "','" . $_POST['oraFine']  . "',now(),'" . $_POST['ggSettimana']  . "')";

$qIns = mysqli_query($c, $queryIns);

<?php
session_start();
include 'config.php';
require_once($path . 'common/config.php');
require_once($path . 'common/function.php');
$c = conn();
//evento
$query="delete from evento where idPt not in (select id from pt)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento approval
$query="delete from evento_approval where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento file
$query="delete from evento_file where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento materiale
$query="delete from evento_materiale where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento materiale referenze
$query="delete from evento_materiale_referenze where idEventoMateriale not in (select id from evento_materiale)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento note da specificare
$query="delete from evento_noteDaSpecificare where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento ore
$query="delete from evento_ore where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//evento pdv
$query="delete from evento_pdv where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//adesione
$query="delete from adesione where idEvento not in (select id from evento)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

//giornata
$query="delete from giornata where idAdesione not in (select id from adesione)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";


$query="delete from spedizione where idAdesione not in (select id from adesione)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";


$query="delete from spedizione_referenze where idSpedizione not in (select id from spedizione)";
$qDelete=mysqli_query($c,$query);
echo $query." - Affected rows  :".mysqli_affected_rows($c)."<br/><br/>";

?>
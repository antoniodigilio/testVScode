<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();
include '../../common/config.php';
require_once($path.'common/config.php');
require_once($path.'common/function.php');
if(loginSessionAjax()) {
  $c_pdo = conn_pdo();
  if(isset($_POST['draw'])){$draw=(int)$_POST['draw'];}else{$draw=null;}
  if(isset($_POST['start'])){$start=(int)$_POST['start'];}else{$start=null;}
  if(isset($_POST['length'])){$length=(int)$_POST['length'];}else{$length=null;}
  if(isset($_POST['search']['value'])){$search=$_POST['search']['value'];}else{$search=null;}
  if(isset($_POST['order']) && count($_POST['order']) > 0){$order=$_POST['order'];}else{$order=null;}
  if(isset($_POST['columns']) && count($_POST['columns']) > 0){$columns=$_POST['columns'];}else{$columns=null;}
  if(isset($_POST['filters']) && count($_POST['filters']) > 0){$filters=$_POST['filters'];}else{$filters=null;}
  //fetch pt with files
  try {
    //limit canvas role 5 "funzionari di vendita" 
    if($_SESSION['idRuolo']==5){
      $current_quarter=0;
      $year = date("Y");
      $start_1_quarter = DateTime::createFromFormat("Y-m-d H:i:s",$year."-01-01 00:00:00");
      $end_1_quarter = DateTime::createFromFormat("Y-m-d H:i:s", $year . "-04-30 00:00:00");
      $start_2_quarter = DateTime::createFromFormat("Y-m-d H:i:s", $year . "-05-01 00:00:00");
      $end_2_quarter = DateTime::createFromFormat("Y-m-d H:i:s", $year . "-08-31 00:00:00");
      $start_3_quarter = DateTime::createFromFormat("Y-m-d H:i:s", $year . "-09-01 00:00:00");
      $end_3_quarter = DateTime::createFromFormat("Y-m-d H:i:s", $year . "-12-31 00:00:00");
      $today = new DateTime('today');
      if($today <= $end_1_quarter && $today >= $start_1_quarter){$current_quarter=1;}
      if($today <= $end_2_quarter && $today >= $start_2_quarter){$current_quarter=2;}
      if($today <= $end_3_quarter && $today >= $start_1_quarter){$current_quarter=3;}

    }
    //
    $src = (trim($search) != '' ? " AND ( 
                                          UPPER(a.id) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(e.id) LIKE CONCAT('%',:search,'%') 
                                       OR UPPER(e.nome) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(pdv.codice) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(pdv.ragioneSociale) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(azm.ragioneSociale) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(azp.ragioneSociale) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(indirizzo) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(comune) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(CONCAT(inseritaDa.cognome,' ',inseritaDa.nome)) LIKE CONCAT('%',:search,'%')
                                       OR UPPER(CONCAT(approvataDa.cognome,' ',approvataDa.nome)) LIKE CONCAT('%',:search,'%')
                                        )" : "");
    $src_columns = (trim($specific_search) != '' ? $specific_search : "");

    //filters
    $filters_str='';
 if ($_SESSION['idRuolo'] == 4 || $_SESSION['idRuolo'] == 5) {
      $filters_str .= " AND e.eventoPubblicato=1 AND e.eventoChiuso=0 ";
    }
    if ($_SESSION['idRuolo'] == 4){
      $filters_str .= " AND e.approval=1 and e.id in (select idEvento from evento_approval where idUtente=".$_SESSION['idUtente'].")";
    }
    if ( $_SESSION['idRuolo'] == 5) {
      $filters_str .= " AND inseritaDa.id=".$_SESSION['idUtente'];
    }
   /* $canvas_filter_str='';
    if ($_SESSION['idRuolo'] == 5) {
      $canvas_filter_str.= " AND ((e.canvass = :canvass_id AND e.anno = :anno) OR (e.canvass=4) OR (e.canvass IS NULL))";
    }*/
    if ($filters != null) {
      foreach ($filters as $filter) {
        $filters_str.=' AND '.$filter['col'].' '.$filter['op'].' :'.str_replace(".","",$filter['col']);
      }
    }
    
    //total
    $q_total = $c_pdo->prepare("SELECT COUNT(*) AS tot FROM adesione a 
                                                   LEFT JOIN evento as e on e.id=a.idEvento 
                                                   ".(isset($_GET['idEvento'])?" WHERE e.id = :evento_id ":" "));
    if (isset($_GET['idEvento'])) {
      $q_total->bindValue(':evento_id', (int)$_GET['idEvento'], PDO::PARAM_INT);
    }
    if ($q_total->execute()) {
      $r_total = $q_total->fetch(PDO::FETCH_ASSOC);
      $totalRecords = $r_total['tot'];
      //total filtering


      $q_filtered = $c_pdo->prepare(
        "SELECT COUNT(*) AS tot
                                               FROM adesione as a
                                               LEFT JOIN pdv on pdv.id=a.idpdv 
                                               LEFT JOIN evento as e on e.id=a.idEvento
                                               LEFT JOIN utente as inseritaDa on inseritaDa.id=a.inseritaDa 
                                               LEFT JOIN utente as approvataDa on approvataDa.id=a.approvataDa
                                               LEFT JOIN evento_canvass as ec ON ec.id = e.canvass
                                               LEFT JOIN azienda as azp ON azp.id = e.agenziaProm
                                               LEFT JOIN azienda as azm ON azm.id = e.agenziaMh
                                               LEFT JOIN evento_approval as app on app.idEvento=e.id and app.idUtente=".$_SESSION['idUtente']."
                                               
                                               WHERE 1=1 ".$filters_str." ".(isset($_GET['idEvento'])?" AND e.id = :evento_id ":" ").$src."  ".$src_columns);
                                               //".$canvas_filter_str."
      if (trim($search) != '') {
        $q_filtered->bindValue(':search', strtoupper(trim($search)), PDO::PARAM_STR);
      }
      if(isset($_GET['idEvento'])){
        $q_filtered->bindValue(':evento_id', (int)$_GET['idEvento'], PDO::PARAM_INT);
      }
      if ($filters != null) {
        foreach ($filters as  $filter) {
          $q_filtered->bindValue(':' . str_replace(".", "", $filter['col']), $filter['val'], (ctype_digit($filter['val']) ? PDO::PARAM_INT : PDO::PARAM_STR));
        }
      }
      /*
      if ($_SESSION['idRuolo'] == 5) {
        $q_filtered->bindValue(':canvass_id', (int)$current_quarter, PDO::PARAM_INT);
        $q_filtered->bindValue(':anno', (int)$year, PDO::PARAM_INT);
      }
      */
      if ($q_filtered->execute()) {
        $r_filtered = $q_filtered->fetch(PDO::FETCH_ASSOC);
        $totalRecordwithFilter = $r_filtered['tot'];
        $order_q = 'ORDER BY';
        $order_array = [];
        if($order!=null)
        {
          foreach($order as $or){
            $order_array[] = (string)$columns[$or['column']]['data'].' '.strtoupper($or['dir']);
          }
          $order_q.=' '.implode(",",$order_array);
        }
        if ($length == -1) //all data
        {
          $limit_str = '';
        } else {
          $limit_str = 'LIMIT :start, :length';
        }
        $q = $c_pdo->prepare("SELECT *,
                                     a.id as idAdesione,
                                     a.dal as _dal_,
                                     a.al as _al_,
                                     WEEKOFYEAR(a.dal) as numSettimana,
                                     case when a.dal>=now() then 1 else 0 end as annullabile,
                                     app.idUtente as utenteApproval, 
                                     e.nome as nomeEvento,
                                     e.approval as eventoApproval,
                                     a.stato as statoAdesione,
                                     stato.nome as stringStato,

                                     pdv.codice as codicesap, 
                                     pdv.ragioneSociale ragioneSocialePdv,
                                     concat(inseritaDa.cognome,' ',inseritaDa.nome) as inseritaDaUtente, 
                                     concat(approvataDa.cognome,' ',approvataDa.nome) as approvataDaUtente,
                                     e.id as idEvento,
                                     azp.ragioneSociale as eventoProm,
                                     azm.ragioneSociale as eventoMh 
                                     FROM adesione as a
                                     left join tabelle as stato on stato.id=a.stato
                                     LEFT JOIN pdv on pdv.id=a.idpdv 
                                     LEFT JOIN evento as e on e.id=a.idEvento
                                     LEFT JOIN utente as inseritaDa on inseritaDa.id=a.inseritaDa 
                                     LEFT JOIN utente as approvataDa on approvataDa.id=a.approvataDa
                                     LEFT JOIN evento_canvass as ec ON ec.id = e.canvass
                                     LEFT JOIN azienda as azp ON azp.id = e.agenziaProm
                                     LEFT JOIN azienda as azm ON azm.id = e.agenziaMh
                                     LEFT JOIN evento_approval as app on app.idEvento=e.id and app.idUtente=" . $_SESSION['idUtente'] . "
                          WHERE 1=1 ".$filters_str." ". (isset($_GET['idEvento']) ? " AND e.id = :evento_id " : " "). $src ."  ".$src_columns." ".(count($order_array) > 0 ? $order_q : '')." ".$limit_str);

                         // " . $canvas_filter_str . "
        
        if (trim($search) != '') {
          $q->bindValue(':search', strtoupper(trim($search)), PDO::PARAM_STR);
        }
        if (isset($_GET['idEvento'])) {
          $q->bindValue(':evento_id', (int)$_GET['idEvento'], PDO::PARAM_INT);
        }
        if ($length != -1) {
          $q->bindValue(':start', $start, PDO::PARAM_INT);
          $q->bindValue(':length', $length, PDO::PARAM_INT);
        }
        if ($filters != null) {
          foreach ($filters as  $filter) {
            $q->bindValue(':' . str_replace(".", "", $filter['col']), $filter['val'], (ctype_digit($filter['val']) ? PDO::PARAM_INT : PDO::PARAM_STR));
          }
        }
       /* if ($_SESSION['idRuolo'] == 5) {
          $q->bindValue(':canvass_id', (int)$current_quarter, PDO::PARAM_INT);
          $q->bindValue(':anno', (int)$year, PDO::PARAM_INT);
        }*/
        if ($q->execute()) {
          $adesioni = array();
          while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $adesioni[] = $r;
          }
          
          $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $adesioni
           
          );

          echo json_encode($response);
        } else {
          echo json_encode(array("error" => 'Impossibile caricare la lista'));
        }
      } else {
        echo json_encode(array("error" => 'Impossibile caricare la lista'));
      }
    } else {
      echo json_encode(array("error" => 'Impossibile caricare la lista'));
    }
  } catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
  }  
} else {
  echo json_encode(array("error" => 'Operazione non consentita'));
}

<?php
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "https://";
$CurPageURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$error_mex = [];
$message_mex = [];
function login($username, $password)
{
  global $error_mex, $message_mex, $link, $CurPageURL;
  $c_pdo = conn_pdo();
  if (empty($username)) {
    $error_mex[] = MESSAGE_USERNAME_EMPTY;
  } else if (empty($password)) {
    $error_mex[] = MESSAGE_PASSWORD_EMPTY;
  } else {
    // user can login with his username or his email address.
    // if user has not typed a valid email address, we try to identify him with his user_name
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      // database query, getting all the info of the selected user
      $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE username = :username AND stato=1');
      $query_user->bindValue(':username', trim($username), PDO::PARAM_STR);
      $query_user->execute();
      // get result row (as an object)
      $result_row =  $query_user->fetchObject();

      // if user has typed a valid email address, we try to identify him with his user_email
    } else {
      // database query, getting all the info of the selected user
      $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE email = :email AND stato=1');
      $query_user->bindValue(':email', trim($username), PDO::PARAM_STR);
      $query_user->execute();
      // get result row (as an object)
      $result_row = $query_user->fetchObject();
    }

    // if this user not exists
    if (!isset($result_row->id)) {
      // was MESSAGE_USER_DOES_NOT_EXIST before, but has changed to MESSAGE_LOGIN_FAILED
      // to prevent potential attackers showing if the user exists
      $error_mex[] = MESSAGE_LOGIN_FAILED;
    } else if (($result_row->user_failed_logins >= 3) && ($result_row->user_last_failed_login > (time() - 30))) {
      $error_mex[] = MESSAGE_PASSWORD_WRONG_3_TIMES;
    } else if (!password_verify($password, $result_row->password)) {
      // increment the failed login counter for that user
      $sth = $c_pdo->prepare('UPDATE utente '
        . 'SET user_failed_logins = user_failed_logins+1, user_last_failed_login = :user_last_failed_login '
        . 'WHERE username = :user_name OR email = :user_name');
      $sth->execute(array(':user_name' => $username, ':user_last_failed_login' => time()));

      $error_mex[] = MESSAGE_PASSWORD_WRONG;
      // has the user activated their account with the verification email
    } else if ($result_row->stato != 1) {
      $error_mex[] = MESSAGE_ACCOUNT_NOT_ACTIVATED;
    } else {

      caricaSession($result_row->id);
      ScriviLog("LOGIN EFFETTUATO DA " . strtoupper($username), $result_row->id);

      // reset the failed login counter for that user
      $sth = $c_pdo->prepare('UPDATE utente '
        . 'SET user_failed_logins = 0, user_last_failed_login = NULL '
        . 'WHERE id = :user_id AND user_failed_logins != 0');
      $sth->execute(array(':user_id' => $result_row->id));
      if ($_SESSION['otp']) {
        if (!isset($_SESSION['otp_passed']) || !$_SESSION['otp_passed']) {
          //GO TO OTP
          ScriviLog("REDIRECT OTP " . $_SESSION['username'], $_SESSION['idUtente']);
          $redirect_link = $link . 'common/2fa.php';
          if ($redirect_link != $CurPageURL) {
            header('location: ' . $link . 'common/2fa.php');
          }
        }
      }
      if (!$_SESSION['first_temp_pass_changed']) {
        //GO TO CHANGE FIRST PASSWORD
        ScriviLog("REDIRECT CAMBIO PRIMA PASS " . strtoupper($username), $result_row->id);
        $redirect_link = $link . 'profilo/modifica_password.php';
        if ($redirect_link != $CurPageURL && (!$_SESSION['otp'] || (isset($_SESSION['otp_passed']) && $_SESSION['otp_passed']))) {
          header('location: ' . $link . 'profilo/modifica_password.php');
        }
      }
      if ($_SESSION['password_expired']) {
        //GO TO CHANGE PASSWORD
        ScriviLog("REDIRECT CAMBIO PASS SCADUTA " . strtoupper($username), $result_row->id);
        $redirect_link = $link . 'profilo/modifica_password.php';
        if ($redirect_link != $CurPageURL && (!$_SESSION['otp'] || (isset($_SESSION['otp_passed']) && $_SESSION['otp_passed']))) {
          header('location: ' . $link . 'profilo/modifica_password.php');
        }
      }

      if ($result_row->privacy == "") {
        header('location: ../dashboard/index.php');
      } else {

        header('location: ../dashboard/index.php');
      }
    }
  }
}
function loginSessionAjax()
{
  if (
    isset($_SESSION['idUtente']) &&
    isset($_SESSION['username'])
  ) {
    caricaSession($_SESSION['idUtente']);
    if (
      isset($_SESSION['first_temp_pass_changed']) &&
      $_SESSION['first_temp_pass_changed'] == 1 &&
      isset($_SESSION['password_expired']) &&
      $_SESSION['password_expired'] == 0
    ) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
function redirect($URL)
{
  echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
  echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
}
function loginSession()
{
  global $link, $CurPageURL;
  if (
    isset($_SESSION['idUtente']) &&
    isset($_SESSION['username'])
  ) {
    caricaSession($_SESSION['idUtente']);
    if ($_SESSION['otp']) {
      if (!isset($_SESSION['otp_passed']) || !$_SESSION['otp_passed']) {
        //GO TO OTP
        ScriviLog("REDIRECT OTP " . $_SESSION['username'], $_SESSION['idUtente']);
        $redirect_link = $link . 'common/2fa.php';
        if ($redirect_link != $CurPageURL) {
          header('location: ' . $link . 'common/2fa.php');
        }
      }
    }
    if (!$_SESSION['first_temp_pass_changed']) {
      //GO TO CHANGE FIRST PASSWORD
      ScriviLog("REDIRECT CAMBIO PRIMA PASS " . $_SESSION['username'], $_SESSION['idUtente']);
      $redirect_link = $link . 'profilo/modifica_password.php';
      if ($redirect_link != $CurPageURL && (!$_SESSION['otp'] || (isset($_SESSION['otp_passed']) && $_SESSION['otp_passed'])))
        header('location: ' . $link . 'profilo/modifica_password.php');
    }
    if ($_SESSION['password_expired']) {
      //GO TO CHANGE PASSWORD
      ScriviLog("REDIRECT CAMBIO PASS SCADUTA " . $_SESSION['username'], $_SESSION['idUtente']);
      $redirect_link = $link . 'profilo/modifica_password.php';
      if ($redirect_link != $CurPageURL && (!$_SESSION['otp'] || (isset($_SESSION['otp_passed']) && $_SESSION['otp_passed']))) {
        header('location: ' . $link . 'profilo/modifica_password.php');
      }
    }

    return true;
  } else {
    return false;
  }
}
function setPasswordResetDatabaseTokenAndSendMail($username)
{
  global $error_mex, $message_mex;
  $c_pdo = conn_pdo();

  $user_name = trim($username);
  if (empty($user_name)) {
    $error_mex[] = MESSAGE_USERNAME_EMPTY;
  } else {
    // generate timestamp (to see when exactly the user (or an attacker) requested the password reset mail)
    // btw this is an integer ;)
    $temporary_timestamp = time();
    // generate random hash for email password reset verification (40 char string)
    $user_password_reset_hash = sha1(uniqid(mt_rand(), true));
    // database query, getting all the info of the selected user
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      // database query, getting all the info of the selected user
      $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE username = :username AND stato=1');
      $query_user->bindValue(':username', trim($username), PDO::PARAM_STR);
      $query_user->execute();
      // get result row (as an object)
      $result_row =  $query_user->fetchObject();

      // if user has typed a valid email address, we try to identify him with his user_email
    } else {
      // database query, getting all the info of the selected user
      $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE email = :email AND stato=1');
      $query_user->bindValue(':email', trim($username), PDO::PARAM_STR);
      $query_user->execute();
      // get result row (as an object)
      $result_row = $query_user->fetchObject();
    }

    // if this user exists
    if (isset($result_row->id)) {

      // database query:
      $query_update = $c_pdo->prepare('UPDATE utente SET user_password_reset_hash = :user_password_reset_hash,
                                                               user_password_reset_timestamp = :user_password_reset_timestamp
                                                               WHERE id = :id');
      $query_update->bindValue(':user_password_reset_hash', $user_password_reset_hash, PDO::PARAM_STR);
      $query_update->bindValue(':user_password_reset_timestamp', $temporary_timestamp, PDO::PARAM_INT);
      $query_update->bindValue(':id', $result_row->id, PDO::PARAM_INT);

      $query_update->execute();

      // check if exactly one row was successfully changed:
      if ($query_update->rowCount() == 1) {
        // send a mail to the user, containing a link with that token hash string
        sendPasswordResetMail($result_row->username, $result_row->email, $user_password_reset_hash);
        return true;
      } else {
        $error_mex[] = MESSAGE_DATABASE_ERROR;
      }
    } else {
      $error_mex[] = MESSAGE_USER_DOES_NOT_EXIST;
    }
  }
}
function sendPasswordResetMail($user_name, $user_email, $user_password_reset_hash)
{
  global $error_mex, $message_mex;
  $link_    = EMAIL_PASSWORDRESET_URL . '?user_name=' . urlencode($user_name) . '&verification_code=' . urlencode($user_password_reset_hash);
  $body = EMAIL_PASSWORDRESET_CONTENT . ' ' . $link_;


  if (!sendMail('Richiesta reset password', $body, $user_email)) {
    $error_mex[] = MESSAGE_PASSWORD_RESET_MAIL_FAILED;
    return false;
  } else {
    $message_mex[] = MESSAGE_PASSWORD_RESET_MAIL_SUCCESSFULLY_SENT;
    return true;
  }
}
function passwordResetLinkIsValid($user_name, $verification_code)
{
  global $error_mex, $message_mex;
  $user_name = trim($user_name);
  $c_pdo = conn_pdo();
  if (empty($user_name) || empty($verification_code)) {
    $error_mex[] = MESSAGE_LINK_PARAMETER_EMPTY;
    return false;
  } else {
    // database query, getting all the info of the selected user
    if (!filter_var($user_name, FILTER_VALIDATE_EMAIL)) {
      // database query, getting all the info of the selected user
      $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE username = :username AND stato=1');
      $query_user->bindValue(':username', trim($user_name), PDO::PARAM_STR);
      $query_user->execute();
      // get result row (as an object)
      $result_row =  $query_user->fetchObject();

      // if user has typed a valid email address, we try to identify him with his user_email
    } else {
      // database query, getting all the info of the selected user
      $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE email = :email AND stato=1');
      $query_user->bindValue(':email', trim($user_name), PDO::PARAM_STR);
      $query_user->execute();
      // get result row (as an object)
      $result_row = $query_user->fetchObject();
    }

    // if this user exists and have the same hash in database
    if (isset($result_row->id) && $result_row->user_password_reset_hash == $verification_code) {

      $timestamp_one_hour_ago = time() - 3600; // 3600 seconds are 1 hour

      if ($result_row->user_password_reset_timestamp > $timestamp_one_hour_ago) {
        return true;
      } else {
        $error_mex[] = MESSAGE_RESET_LINK_HAS_EXPIRED;
        return false;
      }
    } else {
      if (isset($result_row->user_id)) {
        $error_mex = MESSAGE_RESET_LINK_HAS_EXPIRED;
        return false;
      } else {
        $error_mex = MESSAGE_USER_DOES_NOT_EXIST;
        return false;
      }
    }
  }
}
function passwordResetWasSuccessful($user_name, $user_password_reset_hash, $user_password_new, $user_password_repeat)
{
  global $error_mex, $message_mex;
  $c_pdo = conn_pdo();
  // TODO: timestamp!
  $user_name = trim($user_name);

  if (empty($user_name) || empty($user_password_reset_hash) || empty($user_password_new) || empty($user_password_repeat)) {
    $error_mex[] = MESSAGE_PASSWORD_EMPTY;
    return false;
    // is the repeat password identical to password
  } else if ($user_password_new !== $user_password_repeat) {
    $error_mex[] = MESSAGE_PASSWORD_BAD_CONFIRM;
    return false;
    // password need to have a minimum length of 6 characters
  } else if (strlen($user_password_new) < 6) {

    $error_mex[] = MESSAGE_PASSWORD_TOO_SHORT;
    return false;
    // if database connection opened
  } else {
    try {
      $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);

      // write users new hash into database
      $query_update = $c_pdo->prepare('UPDATE utente SET password = :user_password_hash,
                                                       user_password_reset_hash = NULL, user_password_reset_timestamp = NULL
                                                        WHERE username = :user_name AND user_password_reset_hash = :user_password_reset_hash');
      $query_update->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
      $query_update->bindValue(':user_password_reset_hash', $user_password_reset_hash, PDO::PARAM_STR);
      $query_update->bindValue(':user_name', trim($user_name), PDO::PARAM_STR);
      $query_update->execute();

      // check if exactly one row was successfully changed:
      if ($query_update->rowCount() == 1) {
        $message_mex[] = MESSAGE_PASSWORD_CHANGED_SUCCESSFULLY;
        return true;
      } else {
        $error_mex[] = MESSAGE_PASSWORD_CHANGE_FAILED;
        return false;
      }
    } catch (Exception $e) {
      $error_mex[] = $e->getMessage();
      return false;
    }
  }
}
function logout()
{
  global $error_mex, $message_mex;
  $_SESSION = array();
  session_destroy();
  $message_mex[] = MESSAGE_LOGGED_OUT;
}
function conn()
{

  $conn_servername = DB_HOST;
  $conn_username = DB_USER;
  $conn_password = DB_PASS;
  $conn_dbname = DB_NAME;
  $c = mysqli_connect($conn_servername, $conn_username, $conn_password, $conn_dbname) or die("Errore connessione: " . mysqli_connect_error());
  if ($c->connect_error) {
    die("Connection failed: " . $c->connect_error);
  }
  return $c;
}
function conn_pdo()
{
  $conn_servername = DB_HOST;
  $conn_username = DB_USER;
  $conn_password = DB_PASS;
  $conn_dbname = DB_NAME;

  try {
    $conn = new PDO('mysql:host=' . $conn_servername . ';dbname=' . $conn_dbname, $conn_username, $conn_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $utf8_q = "set names 'utf8'";
    $utf8 = $conn->prepare($utf8_q);
    $utf8->execute();
  } catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
  }
  return $conn;
}
function caricaSession($user_id)
{
  session_start();
  $c_pdo = conn_pdo();
  $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE id = :id');
  $query_user->bindValue(':id', $user_id, PDO::PARAM_INT);
  $query_user->execute();
  $result_row =  $query_user->fetchObject();
  $_SESSION['idUtente'] = $result_row->id;
  $_SESSION['username'] = $result_row->username;
  $_SESSION['email'] = $result_row->email;
  $_SESSION['idRuolo'] = $result_row->ruolo;
  $_SESSION['sesso'] = $result_row->sesso;
  $_SESSION['otp'] = $result_row->otp;
  $_SESSION['nominativo'] = $result_row->nome . " " . $result_row->cognome;
  $_SESSION['first_temp_pass_changed'] = ($result_row->first_temp_pass_changed == 0 ? 0 : 1);
  $now = new DateTime();
  if ($result_row->last_password_changed_date == '' || $result_row->last_password_changed_date == NULL) {
    $_SESSION['password_expired'] = 1;
  } else {
    $last_mod = DateTime::createFromFormat('Y-m-d', $result_row->last_password_changed_date);
    $last_mod->add(new DateInterval('P' . PASSWORD_EXPIRATION_GG . 'D'));
    $_SESSION['password_expired'] = ($now > $last_mod ? 1 : 0);
  }
}

function aggiornaMinAllestimento($idAdesione)
{

  $c = conn();
  $qAll = mysqli_query($c, "select * from giornata where idadesione=" . $idAdesione . " and idEsigenza=19");
  if (mysqli_num_rows($qAll) > 0) {
    $rowAll = mysqli_fetch_assoc($qAll);
    $queryFindMinAll = "select case when sum(minAllestimento)>120 then sum(minAllestimento) else 120 end as minAllestimento from (select sum(m.minuti_all_altri* s.quantita)as minAllestimento 
    from materiale as m inner join spedizione as s on s.idMateriale=m.id where s.idAdesione=" .  $idAdesione . " and s.idTipologia<>4 
    union select sum(m.minuti_all_altri) minAllestimento     from materiale as m inner join spedizione as s on s.idMateriale=m.id where s.idAdesione=" . $idAdesione . " and s.idTipologia=4) as minAll";
    $qFindMinAll = mysqli_query($c, $queryFindMinAll);
    $rowFindMinAll = mysqli_fetch_assoc($qFindMinAll);
    $qUpdMinAll = mysqli_query($c, "update giornata set min=" . $rowFindMinAll['minAllestimento'] . " where id=" . $rowAll['id']);
  }
}

function ScriviLog($testo, $idUtente, $tipo = NULL)
{
  session_start();
  $c = conn();
  if ($idUtente == "") {
    $idUtente = "NULL";
  }

  $query = "INSERT INTO log (tipo,testo, idUtente, data, ip)  values (";
  if ($tipo == NULL || $tipo == '') {
    $query .= "NULL,";
  } else {
    $query .= "'" . mysqli_escape_string($c, $tipo) . "',";
  }

  $query .= "'" . mysqli_escape_string($c, $testo) . "'," . $idUtente . ",now(),'" . $_SERVER['REMOTE_ADDR'] . "')";
  //echo $query;
  $Ins = mysqli_query($c, $query);
}
function TrovaValore($id, $tabella, $campo)
{

  $c = conn();
  if ($id != '') {
    $q = mysqli_query($c, "select " . $campo . " from " . $tabella . " where id=" . $id);
    $row = mysqli_fetch_assoc($q);
    return $row[$campo];
  } else {
    return '';
  }
}
function infoProd($idProd)
{

  $c = conn();
  $query = "select * from prodotto where id=" . $idProd;
  $q = mysqli_query($c, $query);
  $row = mysqli_fetch_assoc($q);
  return $row['nome'];
}
//get tipologia_materiale select
function tipologiaMaterialeSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM tipologia_materiale ORDER BY name ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option data-showextra="' . $sel_r['show_tipologia_materiale_extra'] . '" value="' . $sel_r['id'] . '">' . $sel_r['name'] . '</option>';
    }
  }
  return $sel;
}
function anomaliaGiornataTypeSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT DISTINCT type FROM anomalie_giornata ORDER BY type ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['type'] . '">' . $sel_r['type'] . '</option>';
    }
  }
  return $sel;
}
function anomaliaSpedizioneTypeSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT DISTINCT type FROM anomalie_spedizione ORDER BY type ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['type'] . '">' . $sel_r['type'] . '</option>';
    }
  }
  return $sel;
}
function anomaliaGiornataSelect($conn, $type)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM anomalie_giornata WHERE type = :type ORDER BY name ASC");
  $sel_q->bindValue(':type', $type, PDO::PARAM_STR);

  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['name'] . '</option>';
    }
  }
  return $sel;
}
function anomaliaSpedizioneSelect($conn, $type)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM anomalie_spedizione WHERE type = :type ORDER BY name ASC");
  $sel_q->bindValue(':type', $type, PDO::PARAM_STR);
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['name'] . '</option>';
    }
  }
  return $sel;
}
//get piani tecnici select
function pianoTecnicoleSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM pt ORDER BY name ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['name'] . '</option>';
    }
  }
  return $sel;
}
function aziendaSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM azienda WHERE deleted_at IS NULL ORDER BY ragioneSociale ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['ragioneSociale'] . '</option>';
    }
  }
  return $sel;
}
//ruoli select
function ruoliSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM utente_ruolo ");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['nome'] . '</option>';
    }
  }
  return $sel;
}

//get brands select
function brandSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM referenza_brand ORDER BY brand ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['brand'] . '</option>';
    }
  }
  return $sel;
}
//get trade category select
function tradeCategorySelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM referenza_tradeCategory ORDER BY tradeCategory ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['tradeCategory'] . '</option>';
    }
  }
  return $sel;
}
//get gerarchia select
function gerarchiaSelect($conn)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM referenza_gerarchia ORDER BY gerarchia ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['gerarchia'] . '</option>';
    }
  }
  return $sel;
}
//get fornitori select
function fornitoriSelect($conn, $exlcude_del = false)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM materiale_fornitori " . ($exlcude_del ? "WHERE deleted_at IS NULL" : "") . " ORDER BY nome_fornitore ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<option value="' . $sel_r['id'] . '">' . $sel_r['nome_fornitore'] . '</option>';
    }
  }
  return $sel;
}
//get opzioni aggiuntive materiali checkbox list
function opzioniAggiuntiveCheckox($conn, $is_mod_dup)
{
  $sel = '';
  $sel_q = $conn->prepare("SELECT * FROM tipologia_materiale_extra ORDER BY name ASC");
  $sel_q->execute();
  if ($sel_q->rowCount() > 0) {
    while ($sel_r = $sel_q->fetch(PDO::FETCH_ASSOC)) {
      $sel .= '<div class="mb-2">
                                <label class="form-check custom-icon mb-0">
                                    <input type="checkbox" class="form-check-input" id="tipologia_materiale_extra_' . $sel_r['id'] . $is_mod_dup . '" name="tipologia_materiale_extra[]" value="' . $sel_r['id'] . '" />
                                    <span class="form-check-label">
                                        <span class="content">
                                            <span class="heading mb-1 d-block lh-1-25">' . $sel_r['name'] . '</span>

                                        </span>
                                    </span>
                                </label>
                            </div>';
    }
  }
  return $sel;
}
function tipiAziendaCheckox($is_mod_dup)
{
  $sel = '';
  $sel .= '<div class="mb-2">
                                <label class="form-check custom-icon mb-0">
                                    <input type="checkbox" class="form-check-input" id="tipo_azienda_agenziaProm' . $is_mod_dup . '" name="tipo_azienda[]" value="agenziaProm" />
                                    <span class="form-check-label">
                                        <span class="content">
                                            <span class="heading mb-1 d-block lh-1-25">Agenzia Promoter</span>

                                        </span>
                                    </span>
                                </label>
                            </div>';
  $sel .= '<div class="mb-2">
                                <label class="form-check custom-icon mb-0">
                                    <input type="checkbox" class="form-check-input" id="tipo_azienda_agenziaMh' . $is_mod_dup . '" name="tipo_azienda[]" value="agenziaMh" />
                                    <span class="form-check-label">
                                        <span class="content">
                                            <span class="heading mb-1 d-block lh-1-25">Agenzia Merchandising</span>

                                        </span>
                                    </span>
                                </label>
                            </div>';
  $sel .= '<div class="mb-2">
                                <label class="form-check custom-icon mb-0">
                                    <input type="checkbox" class="form-check-input" id="tipo_azienda_corriere' . $is_mod_dup . '" name="tipo_azienda[]" value="corriere" />
                                    <span class="form-check-label">
                                        <span class="content">
                                            <span class="heading mb-1 d-block lh-1-25">Corriere</span>

                                        </span>
                                    </span>
                                </label>
                            </div>';
  $sel .= '<div class="mb-2">
                                <label class="form-check custom-icon mb-0">
                                    <input type="checkbox" class="form-check-input" id="tipo_azienda_agenziaRd' . $is_mod_dup . '" name="tipo_azienda[]" value="agenziaRd" />
                                    <span class="form-check-label">
                                        <span class="content">
                                            <span class="heading mb-1 d-block lh-1-25">Agenzia Rientro Dati</span>

                                        </span>
                                    </span>
                                </label>
                            </div>';


  return $sel;
}

function checkQuantitaMateriale($idAdesione, $idTipologia, $quantita)
{
  $check = 1;

  $c = conn();
  $qAdesione = mysqli_query($c, "select * from adesione where id=" . $idAdesione);
  $rowAdesione = mysqli_fetch_assoc($qAdesione);
  if ($rowAdesione['autorizExtraBudget'] == 0) {
    $qEm = mysqli_query($c, "select sum(quantita) as qta from evento_materiale where idTipologia=" . $idTipologia . " and idEvento in (select idEvento from adesione where id=" . $idAdesione . ")");

    $rowEm = mysqli_fetch_assoc($qEm);
    if ($rowEm['qta'] != "") {

      $q = mysqli_query($c, "select sum(quantita) as qta from spedizione where idTipologia=" . $idTipologia . " and idAdesione=" . $idAdesione);
      $row = mysqli_fetch_assoc($q);
      if ($row['qta'] == "") {
        $row['qta'] = 0;
      }
      if (($row['qta'] + $quantita) > $rowEm['qta']) {
        $check = 0;
      }
    }
  }
  return $check;
}
function checkQuantitaMh($idAdesione, $min, $risorse)
{
  $str = $idAdesione . "|" . $min . "|" . $risorse;
  $check = 1;
  $c = conn();
  $qAdesione = mysqli_query($c, "select * from adesione where id=" . $idAdesione);
  $rowAdesione = mysqli_fetch_assoc($qAdesione);
  if ($rowAdesione['autorizExtraBudget'] == 0) {
    $str .= " no autorizz";
    $qEvento = mysqli_query($c, "select * from evento where id in (select idEvento from adesione where id=" . $idAdesione . ")");
    $rowEvento = mysqli_fetch_assoc($qEvento);
    if ($rowEvento['presenzaMh'] == '' || $rowEvento['presenzaMh'] == 0) {
    } else {

      $query = "select idTipologia,MhOreAdesione  as MhOreAdesione ,MhGGTotEvento ,sum(numRisorse*min) as count from evento ";
      $query .= " inner join adesione on adesione.idEvento=evento.id inner join giornata on giornata.idAdesione=adesione.id ";
      $query .= "  where giornata.idEsigenza=21 and  adesione.id=" . $idAdesione;

      $q = mysqli_query($c, $query);

      $row = mysqli_fetch_assoc($q);

      if ($row['MhOreAdesione'] == "") {
        $query = "  select adesione.idpdv,adesione.idEvento,ep.*,budgetMinMh as budgetMinMh ";
        $query .= " from evento inner join adesione on adesione.idEvento=evento.id ";
        $query .= " left join evento_pdv as ep on ep.idEvento=adesione.idEvento and ep.idPdv=adesione.idPdv ";
        $query .= " where adesione.id=" . $idAdesione;

        $q = mysqli_query($c, $query);
        $rowEp = mysqli_fetch_assoc($q);
        if (($row['count'] + ($min * $risorse)) > ($rowEp['budgetMinMh'])) {
          $check = 0;
        }
      } else {
        if (($row['count'] + ($min * $risorse)) > ($row['MhOreAdesione'] * 60)) {
          $check = 0;
        }
      }
    }
  }
  return $check;
}
function checkQuantitaProm($idAdesione, $min)
{

  $check = 1;
  $c = conn();
  $qAdesione = mysqli_query($c, "select * from adesione where id=" . $idAdesione);
  $rowAdesione = mysqli_fetch_assoc($qAdesione);
  if ($rowAdesione['autorizExtraBudget'] == 0) {
    $qEvento = mysqli_query($c, "select * from evento where id in (select idEvento from adesione where id=" . $idAdesione . ")");
    $rowEvento = mysqli_fetch_assoc($qEvento);
    if ($rowEvento['presenzaProm'] == '' || $rowEvento['presenzaProm'] == 0) {
    } else {
      $query = "SELECT idTipologia,PromOreAdesione ,PromGGTotEvento ,sum(ore.nRisorse*ore.nOre) as count FROM `giornata` inner join ore on ore.id=giornata.idOra ";
      $query .= " inner join adesione on adesione.id=giornata.idAdesione inner join evento on evento.id=adesione.idevento ";
      $query .= " where giornata.idEsigenza=20 and  adesione.id=" . $idAdesione;
      $q = mysqli_query($c, $query);
      $row = mysqli_fetch_assoc($q);


      if (($row['count'] + $min) > $row['PromOreAdesione']) {
        $check = 0;
      }
    }
  }
  return $check;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/OAuth.php';

function sendMail($ogg, $mex, $utente, $cc = null, $ccn = null, $cartellaFile = null, $file = null)
{
  $mail = new PHPMailer(true);                               // Passing `true` enables exceptions
  try {
    //Server settings
    $mail->isSMTP();                                       // Set mailer to use SMTP
    $mail->SMTPDebug   = 4;                                // Enable verbose debug output
    $mail->Debugoutput = "error_log";
    $mail->Host        = 'promomedia.email';                 // Specify main and backup SMTP servers
    $mail->Port        = 465;                              // TCP port to connect to
    $mail->SMTPAuth    = true;                             // Enable SMTP authentication
    $mail->SMTPSecure  = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Username    = 'inviologfiles@promomedia.email';        // SMTP username
    $mail->Password    = 'Incentive20%!';                         // SMTP password
    $mail->SMTPOptions = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );
    $mail->setFrom('inviologfiles@promomedia.email', 'PORTALE COCA COLA - PROMOMEDIA');
    $indirizzi = explode(";", $utente);
    foreach ($indirizzi as $indirizzo) {
      $mail->addAddress($indirizzo);
    }
    if (!is_null($cc)) {
      if (is_array($cc)) {
        foreach ($cc as $email) {
          $mail->addCC($email);
        }
      } else {
        // $mail->addCC($cc);
        $indirizziCC = explode(";", $cc);
        foreach ($indirizziCC as $indirizzoCC) {
          $mail->addCC($indirizzoCC);
        }
      }
    }
    if (!is_null($ccn)) {
      if (is_array($ccn)) {
        foreach ($ccn as $email) {
          $mail->addBCC($email);
        }
      } else {
        $mail->addBCC($ccn);
      }
    }
    $mail->addReplyTo('inviologfiles@promomedia.email', 'Information');
    if (!is_null($file)) {
      $mail->AddAttachment($cartellaFile.$file, $file);
   
    }
    $mail->addBCC('a.digilio@promomedianet.it');
   // $mail->addBCC('giardina@apps.media.it');
    $mail->isHTML(true);                                   // Set email format to HTML
    $mail->CharSet     = "UTF-8";
    $mail->Subject     = $ogg;
    $mail->Body        = $mex;
    $mail->AltBody     = htmlspecialchars($mex);
    $mail->WordWrap    = 80;
    if (!$mail->Send()) {

      return false;
    } else {
      return true;
    }
  } catch (Exception $e) {
    return false;
  }
}
function sendTempCredentialNewUser($user_id, $user_password, $c_pdo)
{
  global $error_mex, $message_mex, $link;
  $query_user = $c_pdo->prepare('SELECT * FROM utente WHERE id = :user_id');
  $query_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $query_user->execute();
  if ($query_user->rowCount() == 1) {
    $row_user = $query_user->fetch(PDO::FETCH_ASSOC);
    $username = $row_user['username'];
    $email = $row_user['email'];
    $nome = $row_user['nome'];
    $cognome = $row_user['cognome'];
    $ogg = 'Nuovo tool scheda adesione www.eventimop.it';
    $mex = "Buonasera,<br/>In riferimento alla nota di Carlo Collela del 7/12, Le inviamo di seguito i riferimenti per accedere alla nuova scheda di adesione in sostituzione di Eventi Promo di Micromatica.<br/>
            <a href='https://eventimop.promomediaweb.it/Barilla_Guida%20Portale%20EventiMOP_2.pdf'>Qui relativo link alla relativa guida</a> <br/>
            Per qualsiasi chiarimento potete contattare l'help desk a disposizione sullo stesso sito.<br/>
            ecco il <a href='http://eventimop.it'>link al portale</a>   <br/>
          Username: " . $username . " <br/>
          E-mail: " . $email . " <br/>
          Password: " . $user_password;
    if (sendMail($ogg, $mex, $email)) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
function generateRandomPassword($length, $add_dashes, $available_sets)
{
  $sets = array();
  if (strpos($available_sets, 'l') !== false)
    $sets[] = 'abcdefghjkmnpqrstuvwxyz';
  if (strpos($available_sets, 'u') !== false)
    $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
  if (strpos($available_sets, 'd') !== false)
    $sets[] = '23456789';
  if (strpos($available_sets, 's') !== false)
    $sets[] = '!@#$%&*?';

  $all = '';
  $password = '';
  foreach ($sets as $set) {
    $password .= $set[array_rand(str_split($set))];
    $all .= $set;
  }

  $all = str_split($all);
  for ($i = 0; $i < $length - count($sets); $i++)
    $password .= $all[array_rand($all)];

  $password = str_shuffle($password);

  if (!$add_dashes)
    return $password;

  $dash_len = floor(sqrt($length));
  $dash_str = '';
  while (strlen($password) > $dash_len) {
    $dash_str .= substr($password, 0, $dash_len) . '-';
    $password = substr($password, $dash_len);
  }
  $dash_str .= $password;
  return $dash_str;
}

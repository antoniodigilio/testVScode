<?php include '../common/config.php'; ?>
<?php include $path . 'common/header.php';
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>
<style type="text/css">
    input[type=number]::-webkit-inner-spin-button {
        opacity: 1
    }
</style>
<input type="hidden" id="idAdesione" value="<?= $_GET['idAdesione'] ?>">
<?php
//GIORNATE
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['new-g-anomalia']) && $_POST['new-g-anomalia'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_giornata']) || $_POST['id_giornata'] == '') {
        $error_mex[] = "Riferimento alla giornata non corretto!";
    } elseif (!isset($_POST['anomalia_giornata']) || $_POST['anomalia_giornata'] == '') {
        $error_mex[] = "È necessario selezionare una anomalia!";
    } else {
        $data = [
            "id" => (int)$_POST['id_giornata'],
            "idAnomalia" => (int)$_POST['anomalia_giornata'],
            "noteAnomalia" => $_POST['note_anomalia_giornata'],
            "fatturazioneAnomalia" => $_POST['anomalia_giornata_decurtazione'],
            "variazioneOre" => $_POST['min_anomalia'],
            "userAnomalia" => (int)$_SESSION['idUtente']
        ];

        $dataSecondoPassaggio = '';
        if ($_POST['data_secondo_passaggio'] != '') {

            $dt = explode("/", $_POST['data_secondo_passaggio']);
            $dataSecondoPassaggio = $dt[2] . "-" . $dt[1] . "-" . $dt[0];
        }

        //Duplicate Giornata with Secondo Passaggio
        if (($_POST['anomalia_giornata_decurtazione'] == 'secondo_passaggio') && ($dataSecondoPassaggio != '')) {

            $c_pdo->query("CREATE table temporary_giornata AS SELECT * FROM giornata WHERE id = '" . (int)$_POST['id_giornata'] . "'");
            $c_pdo->query("UPDATE temporary_giornata SET id = NULL, data = '" . $dataSecondoPassaggio . "'");
            $c_pdo->query("INSERT INTO giornata SELECT * FROM temporary_giornata");
            $c_pdo->query("DROP TABLE temporary_giornata");
        }

        //Update Table
        $upd = $c_pdo->prepare("UPDATE giornata SET 
                                           idAnomalia = :idAnomalia,
                                           noteAnomalia = :noteAnomalia,
                                           fatturazioneAnomalia = :fatturazioneAnomalia, 
                                           minAnomalia = :variazioneOre,
                                           dataSecondoPassaggio = '" . $dataSecondoPassaggio . "',
                                           userAnomalia = :userAnomalia,
                                           lastUpdateAnomalia = NOW()
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Segnalazione avvenuta con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio anomalia!';
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['new-g-feedback']) && $_POST['new-g-feedback'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_giornata']) || $_POST['id_giornata'] == '') {
        $error_mex[] = "Riferimento alla giornata non corretto!";
    } elseif (!isset($_POST['feedback_giornata']) || $_POST['feedback_giornata'] == '') {
        $error_mex[] = "È necessario lasciare un feedback!";
    } else {


        $data = [
            "id" => (int)$_POST['id_giornata'],
            "feedback" => (int)$_POST['feedback_giornata'],
            "noteFeedback" => $_POST['note_feedback_giornata'],
            "userFeedback" => (int)$_SESSION['idUtente']


        ];
        $upd = $c_pdo->prepare("UPDATE giornata SET 
                                           feedback = :feedback,
                                           noteFeedback = :noteFeedback,
                                           userFeedback = :userFeedback,
                                           lastUpdateFeedback = NOW()
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Feedback registrato con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio!';
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['mod-g-anomalia']) && $_POST['mod-g-anomalia'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_giornata']) || $_POST['id_giornata'] == '') {
        $error_mex[] = "Riferimento alla giornata non corretto!";
    } elseif (!isset($_POST['anomalia_giornata']) || $_POST['anomalia_giornata'] == '') {
        $error_mex[] = "È necessario selezionare una anomalia!";
    } else {
        //check user of current anomalia is the same
        $data_check = ["id" => (int)$_POST['id_giornata']];
        $get_g = $c_pdo->prepare("SELECT * FROM giornata WHERE id = :id");
        $get_g->execute($data_check);
        $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
        if ($get_g_r['userAnomalia'] != $_SESSION['idUtente']) {
            $error_mex[] = "Modifica non consentita!";
        } else {
            //
            $data = [
                "id" => (int)$_POST['id_giornata'],
                "idAnomalia" => (int)$_POST['anomalia_giornata'],
                "noteAnomalia" => $_POST['note_anomalia_giornata'],
                "fatturazioneAnomalia" => $_POST['anomalia_giornata_decurtazione'],
                "variazioneOre" => $_POST['min_anomalia']
            ];

            $dataSecondoPassaggio = '';
            if ($_POST['data_secondo_passaggio'] != '') {

                $dt = explode("/", $_POST['data_secondo_passaggio']);
                $dataSecondoPassaggio = $dt[2] . "-" . $dt[1] . "-" . $dt[0];
            }

            //Duplicate Giornata with Secondo Passaggio
            if (($_POST['anomalia_giornata_decurtazione'] == 'secondo_passaggio') && ($dataSecondoPassaggio != '')) {

                $c_pdo->query("CREATE table temporary_giornata AS SELECT * FROM giornata WHERE id = '" . (int)$_POST['id_giornata'] . "'");
                $c_pdo->query("UPDATE temporary_giornata SET id = NULL, data = '" . $dataSecondoPassaggio . "'");
                $c_pdo->query("INSERT INTO giornata SELECT * FROM temporary_giornata");
                $c_pdo->query("DROP TABLE temporary_giornata");
            }

            //Update Table
            $upd = $c_pdo->prepare("UPDATE giornata SET 
                                               idAnomalia = :idAnomalia,
                                               noteAnomalia = :noteAnomalia,
                                               fatturazioneAnomalia = :fatturazioneAnomalia, 
                                               minAnomalia = :variazioneOre,
                                               dataSecondoPassaggio = '" . $dataSecondoPassaggio . "',
                                               lastUpdateAnomalia = NOW()
                                               WHERE id = :id");

            if ($upd->execute($data)) {
                $message_mex[] = 'Anomalia modificata con successo!';
            } else {
                $error_mex[] = 'Errore generico in fase di salvataggio anomalia!';
            }
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['mod-g-feedback']) && $_POST['mod-g-feedback'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_giornata']) || $_POST['id_giornata'] == '') {
        $error_mex[] = "Riferimento alla giornata non corretto!";
    } elseif (!isset($_POST['feedback_giornata']) || $_POST['feedback_giornata'] == '') {
        $error_mex[] = "È necessario lasciare un feedback!";
    } else {
        //check user of current anomalia is the same
        $data_check = ["id" => (int)$_POST['id_giornata']];
        $get_g = $c_pdo->prepare("SELECT * FROM giornata WHERE id = :id");
        $get_g->execute($data_check);
        $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
        if ($get_g_r['userFeedback'] != $_SESSION['idUtente']) {
            $error_mex[] = "Modifica non consentita!";
        } else {
            //
            $data = [
                "id" => (int)$_POST['id_giornata'],
                "feedback" => (int)$_POST['feedback_giornata'],
                "noteFeedback" => $_POST['note_feedback_giornata']

            ];
            $upd = $c_pdo->prepare("UPDATE giornata SET 
                                           feedback = :feedback,
                                           noteFeedback = :noteFeedback,
                                           lastUpdateFeedback = NOW()
                                           WHERE id = :id");

            if ($upd->execute($data)) {
                $message_mex[] = 'Feedback modificato con successo!';
            } else {
                $error_mex[] = 'Errore generico in fase di salvataggio!';
            }
        }
    }
}

//reset
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['reset-g-anomalia']) && $_POST['reset-g-anomalia'] != '') {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];

    //check user of current anomalia is the same
    $data_check = ["id" => (int)$_POST['reset-g-anomalia']];
    $get_g = $c_pdo->prepare("SELECT * FROM giornata WHERE id = :id");
    $get_g->execute($data_check);
    $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
    if ($get_g_r['userAnomalia'] != $_SESSION['idUtente']) {
        $error_mex[] = "Modifica non consentita!";
    } else {
        //
        $data = [
            "id" => (int)$_POST['reset-g-anomalia'],
            "idAnomalia" => null,
            "noteAnomalia" => null,
            "userAnomalia" => null,
            "decurtazioneAnomalia" => null,
            "lastUpdateAnomalia" => null,

        ];
        $upd = $c_pdo->prepare("UPDATE giornata SET 
                                           idAnomalia = :idAnomalia,
                                           noteAnomalia = :noteAnomalia,
                                           userAnomalia = :userAnomalia,
                                           decurtazioneAnomalia = :decurtazioneAnomalia,
                                           lastUpdateAnomalia = :lastUpdateAnomalia
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Anomalia resettata con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio anomalia!';
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['reset-g-feedback']) && $_POST['reset-g-feedback'] != '') {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];

    //check user of current feedback is the same
    $data_check = ["id" => (int)$_POST['reset-g-feedback']];
    $get_g = $c_pdo->prepare("SELECT * FROM giornata WHERE id = :id");
    $get_g->execute($data_check);
    $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
    if ($get_g_r['userFeedback'] != $_SESSION['idUtente']) {
        $error_mex[] = "Modifica non consentita!";
    } else {
        //
        $data = [
            "id" => (int)$_POST['reset-g-feedback'],
            "feedback" => null,
            "noteFeedback" => null,
            "userFeedback" => null,
            "lastUpdateFeedback" => null,

        ];
        $upd = $c_pdo->prepare("UPDATE giornata SET 
                                           feedback = :feedback,
                                           noteFeedback = :noteFeedback,
                                           userFeedback = :userFeedback,
                                           lastUpdateFeedback = :lastUpdateFeedback
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Feedback resettato con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio!';
        }
    }
}


//SPEDIZIONI
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['new-s-anomalia']) && $_POST['new-s-anomalia'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_spedizione']) || $_POST['id_spedizione'] == '') {
        $error_mex[] = "Riferimento alla spedizione non corretto!";
    } elseif (!isset($_POST['anomalia_spedizione']) || $_POST['anomalia_spedizione'] == '') {
        $error_mex[] = "È necessario selezionare una anomalia!";
    } else {

        $data = [
            "id" => (int)$_POST['id_spedizione'],
            "idAnomalia" => (int)$_POST['anomalia_spedizione'],
            "noteAnomalia" => $_POST['note_anomalia_spedizione'],
            "fatturazioneAnomalia" => $_POST['anomalia_spedizione_decurtazione'],
            "variazioneOre" => $_POST['min_anomalia'],
            "userAnomalia" => (int)$_SESSION['idUtente']
        ];

        $dataSecondoPassaggio = '';
        if ($_POST['data_secondo_passaggio'] != '') {

            $dt = explode("/", $_POST['data_secondo_passaggio']);
            $dataSecondoPassaggio = $dt[2] . "-" . $dt[1] . "-" . $dt[0];
        }

        //Duplicate Giornata with Secondo Passaggio
        if (($_POST['anomalia_spedizione_decurtazione'] == 'secondo_passaggio') && ($dataSecondoPassaggio != '')) {

            $c_pdo->query("CREATE table temporary_giornata AS SELECT * FROM giornata WHERE id = '" . (int)$_POST['id_giornata'] . "'");
            $c_pdo->query("UPDATE temporary_giornata SET id = NULL, data = '" . $dataSecondoPassaggio . "'");
            $c_pdo->query("INSERT INTO giornata SELECT * FROM temporary_giornata");
            $c_pdo->query("DROP TABLE temporary_giornata");
        }

        //Update Table
        $upd = $c_pdo->prepare("UPDATE spedizione SET 
                                           idAnomalia = :idAnomalia,
                                           noteAnomalia = :noteAnomalia,
                                           fatturazioneAnomalia = :fatturazioneAnomalia, 
                                           minAnomalia = :variazioneOre,
                                           dataSecondoPassaggio = '" . $dataSecondoPassaggio . "',
                                           userAnomalia = :userAnomalia,
                                           lastUpdateAnomalia = NOW()
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Segnalazione avvenuta con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio anomalia!';
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['new-s-feedback']) && $_POST['new-s-feedback'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_spedizione']) || $_POST['id_spedizione'] == '') {
        $error_mex[] = "Riferimento alla spedizione non corretto!";
    } elseif (!isset($_POST['feedback_spedizione']) || $_POST['feedback_spedizione'] == '') {
        $error_mex[] = "È necessario lasciare un feedback!";
    } else {

        $data = [
            "id" => (int)$_POST['id_spedizione'],
            "feedback" => (int)$_POST['feedback_spedizione'],
            "noteFeedback" => $_POST['note_feedback_spedizione'],
            "userFeedback" => (int)$_SESSION['idUtente']


        ];
        $upd = $c_pdo->prepare("UPDATE spedizione SET 
                                           feedback = :feedback,
                                           noteFeedback = :noteFeedback,
                                           userFeedback = :userFeedback,
                                           lastUpdateFeedback = NOW()
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Feedback registrato con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio!';
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['mod-s-anomalia']) && $_POST['mod-s-anomalia'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_spedizione']) || $_POST['id_spedizione'] == '') {
        $error_mex[] = "Riferimento alla spedizione non corretto!";
    } elseif (!isset($_POST['anomalia_spedizione']) || $_POST['anomalia_spedizione'] == '') {
        $error_mex[] = "È necessario selezionare una anomalia!";
    } else {
        //check user of current anomalia is the same
        $data_check = ["id" => (int)$_POST['id_spedizione']];
        $get_g = $c_pdo->prepare("SELECT * FROM spedizione WHERE id = :id");
        $get_g->execute($data_check);
        $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
        if ($get_g_r['userAnomalia'] != $_SESSION['idUtente']) {
            $error_mex[] = "Modifica non consentita!";
        } else {
            //
            $data = [
                "id" => (int)$_POST['id_spedizione'],
                "idAnomalia" => (int)$_POST['anomalia_spedizione'],
                "noteAnomalia" => $_POST['note_anomalia_spedizione'],
                "fatturazioneAnomalia" => $_POST['anomalia_spedizione_decurtazione'],
                "variazioneOre" => $_POST['min_anomalia'],
                "userAnomalia" => (int)$_SESSION['idUtente']
            ];

            $dataSecondoPassaggio = '';
            if ($_POST['data_secondo_passaggio'] != '') {

                $dt = explode("/", $_POST['data_secondo_passaggio']);
                $dataSecondoPassaggio = $dt[2] . "-" . $dt[1] . "-" . $dt[0];
            }

            //Duplicate Giornata with Secondo Passaggio
            if (($_POST['anomalia_spedizione_decurtazione'] == 'secondo_passaggio') && ($dataSecondoPassaggio != '')) {

                $c_pdo->query("CREATE table temporary_giornata AS SELECT * FROM giornata WHERE id = '" . (int)$_POST['id_giornata'] . "'");
                $c_pdo->query("UPDATE temporary_giornata SET id = NULL, data = '" . $dataSecondoPassaggio . "'");
                $c_pdo->query("INSERT INTO giornata SELECT * FROM temporary_giornata");
                $c_pdo->query("DROP TABLE temporary_giornata");
            }

            //Update Table
            $upd = $c_pdo->prepare("UPDATE spedizione SET 
                                               idAnomalia = :idAnomalia,
                                               noteAnomalia = :noteAnomalia,
                                               fatturazioneAnomalia = :fatturazioneAnomalia, 
                                               minAnomalia = :variazioneOre,
                                               dataSecondoPassaggio = '" . $dataSecondoPassaggio . "'
                                               lastUpdateAnomalia = NOW()
                                               WHERE id = :id");

            if ($upd->execute($data)) {
                $message_mex[] = 'Anomalia modificata con successo!';
            } else {
                $error_mex[] = 'Errore generico in fase di salvataggio anomalia!';
            }
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['mod-s-feedback']) && $_POST['mod-s-feedback'] == 1) {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];
    if (!isset($_POST['id_spedizione']) || $_POST['id_spedizione'] == '') {
        $error_mex[] = "Riferimento alla spedizione non corretto!";
    } elseif (!isset($_POST['feedback_spedizione']) || $_POST['feedback_spedizione'] == '') {
        $error_mex[] = "È necessario lasciare un feedback!";
    } else {
        //check user of current anomalia is the same
        $data_check = ["id" => (int)$_POST['id_spedizione']];
        $get_g = $c_pdo->prepare("SELECT * FROM spedizione WHERE id = :id");
        $get_g->execute($data_check);
        $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
        if ($get_g_r['userFeedback'] != $_SESSION['idUtente']) {
            $error_mex[] = "Modifica non consentita!";
        } else {
            //
            $data = [
                "id" => (int)$_POST['id_spedizione'],
                "feedback" => (int)$_POST['feedback_spedizione'],
                "noteFeedback" => $_POST['note_feedback_spedizione']

            ];
            $upd = $c_pdo->prepare("UPDATE spedizione SET 
                                           feedback = :feedback,
                                           noteFeedback = :noteFeedback,
                                           lastUpdateFeedback = NOW()
                                           WHERE id = :id");

            if ($upd->execute($data)) {
                $message_mex[] = 'Feedback modificato con successo!';
            } else {
                $error_mex[] = 'Errore generico in fase di salvataggio!';
            }
        }
    }
}

//reset
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['reset-s-anomalia']) && $_POST['reset-s-anomalia'] != '') {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];

    //check user of current anomalia is the same
    $data_check = ["id" => (int)$_POST['reset-s-anomalia']];
    $get_g = $c_pdo->prepare("SELECT * FROM spedizione WHERE id = :id");
    $get_g->execute($data_check);
    $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
    if ($get_g_r['userAnomalia'] != $_SESSION['idUtente']) {
        $error_mex[] = "Modifica non consentita!";
    } else {
        //
        $data = [
            "id" => (int)$_POST['reset-s-anomalia'],
            "idAnomalia" => null,
            "noteAnomalia" => null,
            "userAnomalia" => null,
            "decurtazioneAnomalia" => null,
            "lastUpdateAnomalia" => null,

        ];
        $upd = $c_pdo->prepare("UPDATE spedizione SET 
                                           idAnomalia = :idAnomalia,
                                           noteAnomalia = :noteAnomalia,
                                           decurtazioneAnomalia = :decurtazioneAnomalia,
                                           userAnomalia = :userAnomalia,
                                           lastUpdateAnomalia = :lastUpdateAnomalia
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Anomalia resettata con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio anomalia!';
        }
    }
}
if ((!isset($_SESSION['prevent_resend']) || $_SESSION['prevent_resend'] != $_POST['prevent_resend']) && isset($_POST['reset-s-feedback']) && $_POST['reset-s-feedback'] != '') {
    $_SESSION['prevent_resend'] = $_POST['prevent_resend'];

    //check user of current feedback is the same
    $data_check = ["id" => (int)$_POST['reset-s-feedback']];
    $get_g = $c_pdo->prepare("SELECT * FROM spedizione WHERE id = :id");
    $get_g->execute($data_check);
    $get_g_r = $get_g->fetch(PDO::FETCH_ASSOC);
    if ($get_g_r['userFeedback'] != $_SESSION['idUtente']) {
        $error_mex[] = "Modifica non consentita!";
    } else {
        //
        $data = [
            "id" => (int)$_POST['reset-s-feedback'],
            "feedback" => null,
            "noteFeedback" => null,
            "userFeedback" => null,
            "lastUpdateFeedback" => null,

        ];
        $upd = $c_pdo->prepare("UPDATE spedizione SET 
                                           feedback = :feedback,
                                           noteFeedback = :noteFeedback,
                                           userFeedback = :userFeedback,
                                           lastUpdateFeedback = :lastUpdateFeedback
                                           WHERE id = :id");

        if ($upd->execute($data)) {
            $message_mex[] = 'Feedback resettato con successo!';
        } else {
            $error_mex[] = 'Errore generico in fase di salvataggio!';
        }
    }
}

if (isset($_GET['idAdesione'])) {
    $_GET['idAdesione'] = (int)$_GET['idAdesione'];


    if (is_numeric($_GET['idAdesione'])) {
        $qAdesione = mysqli_query($c, "select * from adesione where id=" . $_GET['idAdesione']);


        if (mysqli_num_rows($qAdesione) == 0) {
            redirect("list.php");
        } else {
            $rowAdesione = mysqli_fetch_assoc($qAdesione);
        }
    } else {
        redirect("list.php");
    }
}


$qEvento = mysqli_query($c, "select * from evento where id=" . $rowAdesione['idEvento']);
$rowEvento = mysqli_fetch_assoc($qEvento);

$qPdv = mysqli_query($c, "select * from pdv where id=" . $rowAdesione['idPdv']);
$rowPdv = mysqli_fetch_assoc($qPdv);

$giorniSettimana = array("", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");



?>

<main>
    <div class="container">

        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <div class="col-12 ">

                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="mb-0 pb-2" id="title">Adesione n° <strong><?= $_GET['idAdesione'] ?></strong>
                                                <?php if ($rowAdesione['stato'] == 2) {
                                                    $qLog = mysqli_query($c, "select * from log where testo like 'update adesione set stato=2,%dataModifica=now() where id=" . $_GET['idAdesione'] . "' order by id limit 1");
                                                    $rowLog = mysqli_fetch_assoc($qLog);
                                                    echo "<span  class='badge bg-primary'> Data Invio: " . date('d/m/Y H:i:s', strtotime($rowLog['data'])) . "</span>";
                                                } ?>
                                            </h3>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <a class="btn btn-sm btn-outline-primary d-none" href="form.php?idAdesione=<?= $_GET['idAdesione'] ?>&idEvento=<?= $rowAdesione['idEvento'] ?>"><i class="fa fa-pencil"></i> Modifica Adesione</a>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            $qEv = mysqli_query($c, "select * from evento where id=" . $rowAdesione['idEvento']);
                                            $rowEv = mysqli_fetch_assoc($qEv);
                                            $qPdv = mysqli_query($c, "select *, concat(pdv.codice,' - ',pdv.ragioneSociale,' - ',pdv.indirizzo,' - ',pdv.comune,' - ',COALESCE(pdv.provincia,'')) as pdv from pdv where id=" . $rowAdesione['idPdv']);
                                            $rowPdv = mysqli_fetch_assoc($qPdv);
                                            ?>

                                            <strong>Evento: </strong><?= $rowEv['nome'] ?><br />
                                            <strong>Punto Vendita: </strong><?= $rowPdv['pdv'] ?><br />

                                            <strong>Da: </strong><?= date('d/m/Y', strtotime($rowAdesione['dal'])) ?><strong> - A: </strong><?= date('d/m/Y', strtotime($rowAdesione['al'])) ?><br />
                                            <strong>Autorizzazione extra budget capo: </strong><?php if ($rowAdesione['autorizExtraBudget'] == 0) {
                                                                                                    echo "NO";
                                                                                                } else {
                                                                                                    echo "SI";
                                                                                                } ?><br />
                                            <strong>Fattibilità agenzia: </strong><?php if ($rowAdesione['fattibilitaAgenzia'] == 0) {
                                                                                        echo "NO";
                                                                                    } else {
                                                                                        echo "SI";
                                                                                    } ?><br />

                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            ?>
                                            <strong>Capo Reparto: </strong><?= $rowPdv['capoReparto'] ?> - <?= $rowPdv['telefonoCapoReparto'] ?><br />
                                            <strong>Giorno di Chiusura: </strong><?= $rowPdv['giornoChiusura'] ?> <br />
                                            <strong>Riferimenti per ritiro: </strong><?= $rowAdesione['nomeRiferimento'] ?> - <?= $rowAdesione['telefonoRiferimento'] ?><br />
                                            <strong>Note: </strong><?= $rowAdesione['note'] ?> <br />
                                            <div id="countOreAdesione"></div>
                                        </div>
                                    </div>
                                    <div class="row pt-2">
                                        <div class="col-md-6">
                                            <?php if ($rowEvento['idTipologia'] != 4 && $rowEvento['idTipologia'] != 2) {
                                            ?>
                                                <strong>Allestimento a cura di: </strong><?= strtoupper($rowAdesione['allestimentoCura']) ?>
                                                <?php
                                                $qAllestimento = mysqli_query($c, "select * from giornata where idAdesione=" . $_GET['idAdesione'] . " and idEsigenza=19 ");
                                                if (mysqli_num_rows($qAllestimento) > 0) {
                                                    $rowAllestimento = mysqli_fetch_assoc($qAllestimento);
                                                    echo " - " . date('d/m/Y', strtotime($rowAllestimento['data']));
                                                    if ($_SESSION['idRuolo'] <> 5) {
                                                        echo " (" . $rowAllestimento['min'] . " min)";
                                                    }
                                                }

                                                ?>
                                            <?php
                                            }
                                            ?>

                                        </div>

                                    </div>
                                    <div class="row pt-2">
                                        <div class="col-md-6">

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end ">

                        </div>
                    </div>
                </div>
                <!-- Title and Top Buttons End -->


            </div>
        </div>

        <h2 class="small-title">ALLESTIMENTO </h2>


        <div class="row ">
            <div class="col-md-12">
                <div class="card mb-5">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>Giorno</th>
                                            <th>Numero Risorse</th>
                                            <th>Durata Intervento</th>
                                            <th>Dalle</th>
                                            <th>Alle</th>
                                            <th>Note</th>
                                            <th>Anomalia</th>
                                            <th>Note Anomalia</th>
                                            <th>Feedback</th>
                                            <th>Note Feedback</th>

                                        </thead>
                                        <tbody id="tableAllestimento">
                                            <?php

                                            $giorniSettimana = array("", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");
                                            $qDal = mysqli_query($c, "select  data from giornata where idAdesione=" . $_GET['idAdesione'] . " and idEsigenza=19 order by data limit 1");
                                            $rowDal = mysqli_fetch_assoc($qDal);
                                            $qAl = mysqli_query($c, "select  data from giornata where idAdesione=" . $_GET['idAdesione'] . " and idEsigenza=19 order by data desc limit 1");
                                            $rowAl = mysqli_fetch_assoc($qAl);
                                            $giorno = date('Y-m-d', strtotime($rowDal['data']));
                                            $settimana = 1;
                                            $qGGTot = mysqli_query($c, "select * from giornata where idAdesione=" . $_GET['idAdesione'] . " and idEsigenza=19 order by data");
                                            while ($rowGGTot = mysqli_fetch_assoc($qGGTot)) {
                                                $giorno = date('Y-m-d', strtotime($rowGGTot['data']));
                                                $numGGSett = date('w', strtotime($giorno));


                                                // }
                                                // while (strtotime($giorno) <= strtotime($rowAl['data'])) {
                                            ?>
                                                <tr>

                                                    <?php
                                                    $numGGSett = date('w', strtotime($giorno));
                                                    if ($numGGSett == 0) {
                                                        $numGGSett = 7;
                                                    }
                                                    /*  echo  "select g.*,ag.name as anomalia, CONCAT(u.nome,' ',u.cognome) as user_anomalia,CONCAT(uf.nome,' ',uf.cognome) as user_feedback  
                                                      from giornata g
                                                      LEFT JOIN anomalie_giornata ag ON (ag.id = g.idAnomalia) 
                                                      LEFT JOIN utente u ON (g.userAnomalia = u.id)
                                                      LEFT JOIN utente uf ON (g.userFeedback = uf.id) 
                                                      where g.idAdesione=" . $_GET['idAdesione'] . " and g.idEsigenza=19  and g.data='" . $giorno . "' and g.id=".$rowGGTot['id'];*/
                                                    $qGG = mysqli_query($c, "select g.*,ag.name as anomalia, CONCAT(u.nome,' ',u.cognome) as user_anomalia,CONCAT(uf.nome,' ',uf.cognome) as user_feedback  
                                                                                 from giornata g
                                                                                 LEFT JOIN anomalie_giornata ag ON (ag.id = g.idAnomalia) 
                                                                                 LEFT JOIN utente u ON (g.userAnomalia = u.id)
                                                                                 LEFT JOIN utente uf ON (g.userFeedback = uf.id) 
                                                                                 where g.idAdesione=" . $_GET['idAdesione'] . " and g.idEsigenza=19  and g.data='" . $giorno . "'  and g.id=" . $rowGGTot['id']);
                                                    if (mysqli_num_rows($qGG) > 0) {
                                                        $rowGG = mysqli_fetch_assoc($qGG);

                                                        //anomalia
                                                        if (isset($rowGG['anomalia']) && $rowGG['anomalia'] != '') {

                                                            $anomalia = '<div class="mb-1">
                                                                      <span class="text-danger"><small><b>' . $rowGG['anomalia'] . '</b></small></span>
                                                                    </div>
                                                                    <small><b>' . $rowGG['user_anomalia'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $rowGG['lastUpdateAnomalia'])->format("d/m/Y H:i") . '</b></small><br>
                                                                    ' . /*($rowGG['decurtazioneAnomalia'] == 1 ? '<small><b>Decurtazione: </b><span class="text-danger">SI</div></small><br>' : '')*/
                                                                    '<small><b>Fatturazione: </b><span class="text-danger">'.$rowGG['fatturazioneAnomalia'].'</div></small><br>'
                                                                     . '

                                                                    ' . ($rowGG['userAnomalia'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-danger mod-g-anomalia me-1" id="mod-g-anomalia-' . $rowGG['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Segnalazione</a><br><a href="javascript:void(0);" class="badge bg-danger reset-g-anomalia" id="reset-g-anomalia-' . $rowGG['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-g-anomalia-' . $rowGG['id'] . '-form" method="POST"><input type="hidden" name="reset-g-anomalia" value="' . $rowGG['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '');
                                                        } else {
                                                            $anomalia = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-g-anomalia me-1" id="new-g-anomalia-' . $rowGG['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Segnala Anomalia"><i data-cs-icon="plus"></i></a>';
                                                        }
                                                        //feedback
                                                        if (isset($rowGG['feedback']) && $rowGG['feedback'] != '') {
                                                            $feedback = '<div class="mb-1 br-wrapper br-theme-cs-icon">
                                                                         
                                                                         <select name="rating" autocomplete="off" data-readonly="true" data-initial-rating="' . $rowGG['feedback'] . '" class="rating">
                                                                         <option value="1">1</option>
                                                                         <option value="2">2</option>
                                                                         <option value="3">3</option>
                                                                         <option value="4">4</option>
                                                                         <option value="5">5</option>
                                                                         </select>
                                                                         </div>
                                                                        <small><b>' . $rowGG['user_feedback'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $rowGG['lastUpdateFeedback'])->format("d/m/Y H:i") . '</b></small><br>
                                                                        ' . ($rowGG['userFeedback'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-primary mod-g-feedback me-1" id="mod-g-feedback-' . $rowGG['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Feedback</a><br><a href="javascript:void(0);" class="badge bg-primary reset-g-feedback" id="reset-g-feedback-' . $rowGG['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-g-feedback-' . $rowGG['id'] . '-form" method="POST"><input type="hidden" name="reset-g-feedback" value="' . $rowGG['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '') . '';
                                                        } else {
                                                            $feedback = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-g-feedback me-1" id="new-g-feedback-' . $rowGG['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Lascia Feedback"><i data-cs-icon="plus"></i></a>';
                                                        }

                                                    ?>

                                                        <td>
                                                            <?= $giorniSettimana[$numGGSett] . " - " . date('d/m', strtotime($giorno)) ?>
                                                        </td>
                                                        <td><?= $rowGG['numRisorse'] ?></td>
                                                        <td><?= $rowGG['min'] ?> min</td>
                                                        <td><?= $rowGG['oraInizio'] ?></td>
                                                        <td><?= $rowGG['oraFine'] ?></td>
                                                        <td><?= $rowGG['note'] ?></td>
                                                        <td><?= $anomalia ?></td>
                                                        <td><small><?= $rowGG['noteAnomalia'] ?></small></td>
                                                        <td><?= $feedback ?></td>
                                                        <td><small><?= $rowGG['noteFeedback'] ?></small></td>

                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                            <?php
                                                /*   $giorno = date('Y-m-d', strtotime($giorno . ' +1 day'));
                                                    if ($numGGSett == 7) {
                                                        $settimana = $settimana + 1;
                                                    }*/
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="small-title">PROMOTER <?php if ($rowEvento['presenzaProm'] == '' || $rowEvento['presenzaProm'] == 0) {
                                                echo '- NON PREVISTE';
                                            } ?></h2>

        <div class="row <?php if ($rowEvento['presenzaProm'] == '' || $rowEvento['presenzaProm'] == 0) {
                            echo 'd-none';
                        } ?>">
            <div class="col-md-12">
                <div class="card mb-5 ">
                    <div id="countPromoter"></div>
                    <div class="card-body >
                        <div class=" row">
                        <div class="col-md-12">
                            <div class="table-responsive <?php if ($rowEvento['presenzaProm'] == "" ||  $rowEvento['presenzaProm'] == "0") {
                                                                echo "d-none";
                                                            } ?>" id="tablePresenzaProm">
                                <table class="table table-striped">
                                    <thead>
                                        <th></th>
                                        <?php
                                        $giorni = array("", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");
                                        for ($i = 1; $i <= 7; $i++) {
                                        ?>
                                            <th>
                                                <center><?= $giorni[$i] ?></center>
                                            </th>
                                        <?php
                                        }
                                        ?>

                                    </thead>
                                    <?php
                                    $giorno = date('Y-m-d', strtotime($rowAdesione['dal']));

                                    for ($i = 1; $i <= 6; $i++) {
                                    ?>
                                        <tr>
                                            <td>
                                                <?= $i . " SETTIMANA" ?></td>
                                            <?php
                                            for ($gg = 1; $gg <= 7; $gg++) {
                                                $numGGSett = date('w', strtotime($giorno));
                                                if ($numGGSett == 0) {
                                                    $numGGSett = 7;
                                                }
                                            ?>
                                                <td>
                                                    <?php
                                                    if (($numGGSett == $gg) && $giorno <= date('Y-m-d', strtotime($rowAdesione['al']))) {
                                                        $queryOreProm = "select o.*, case when giornata.id is not null then 'selected' else '' end as 'select' from ore as o ";
                                                        $queryOreProm .= "inner join giornata on giornata.idOra=o.id ";
                                                        $queryOreProm .= " and giornata.idadesione=" . $_GET['idAdesione'] . "  and giornata.idEsigenza=o.idEsigenza and giornata.data='" . $giorno . "' ";
                                                        $queryOreProm .= " where 1=1  and o.idEsigenza=20 order by nRisorse, nOre desc";
                                                        $qOreProm = mysqli_query($c, $queryOreProm);
                                                        if (mysqli_num_rows($qOreProm) > 0) {
                                                            $rowOreProm = mysqli_fetch_assoc($qOreProm);

                                                            echo "<center>" . date('d/m/Y', strtotime($giorno)) . " - " . $rowOreProm['nome'] . "</center>";
                                                        }

                                                        $giorno = date('Y-m-d', strtotime($giorno . ' +1 day'));
                                                    }
                                                    if ($numGGSett == 7) {
                                                        $settimana = $settimana + 1;
                                                    }
                                                    ?>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
        <?php if ($rowEvento['idTipologia'] == 4) {    ?>
            <h2 class="small-title">CARICAMENTO <?php if ($rowEvento['presenzaMh'] == '' || $rowEvento['presenzaMh'] == 0) {
                                                    echo '- NON PREVISTE';
                                                } ?></h2>

            <div class="row ">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div id="countRFS"></div>

                        <div class="card-body <?php if ($rowEvento['presenzaMh'] == '' || $rowEvento['presenzaMh'] == 0) {
                                                    echo 'd-none';
                                                } ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <th>Giorno Settimana</th>
                                                <th>Dal</th>
                                                <th>Al</th>
                                                <th>Durata Intervento</th>
                                                <th>Dalle</th>
                                                <th>Alle</th>
                                                <th>Anomalia</th>
                                                <th>Note Anomalia</th>
                                                <th>Feedback</th>
                                                <th>Note Feedback</th>
                                            </thead>
                                            <tbody id="tableRFS">
                                                <?php
                                                $qGG = mysqli_query($c, "select  g.*,ag.name as anomalia, CONCAT(u.nome,' ',u.cognome) as user_anomalia,CONCAT(uf.nome,' ',uf.cognome) as user_feedback from giornata g
                                                                        LEFT JOIN anomalie_giornata ag ON (ag.id = g.idAnomalia) 
                                                                        LEFT JOIN utente u ON (g.userAnomalia = u.id)
                                                                        LEFT JOIN utente uf ON (g.userFeedback = uf.id) 

                                                                        where g.idAdesione=" . $_GET['idAdesione'] . " and g.idEsigenza=122 order by g.ggSettimana");

                                                $giorniSettimana = array("", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");

                                                while ($rowGG = mysqli_fetch_assoc($qGG)) {
                                                    //anomalia
                                                    if (isset($rowGG['anomalia']) && $rowGG['anomalia'] != '') {

                                                        $anomalia = '<div class="mb-1">
                                                                      <span class="text-danger"><small><b>' . $rowGG['anomalia'] . '</b></small></span>
                                                                    </div>
                                                                    <small><b>' . $rowGG['user_anomalia'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $rowGG['lastUpdateAnomalia'])->format("d/m/Y H:i") . '</b></small><br>
                                                                    ' . ($rowGG['userAnomalia'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-danger mod-g-anomalia me-1" id="mod-g-anomalia-' . $rowGG['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Segnalazione</a><br><a href="javascript:void(0);" class="badge bg-danger reset-g-anomalia" id="reset-g-anomalia-' . $rowGG['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-g-anomalia-' . $rowGG['id'] . '-form" method="POST"><input type="hidden" name="reset-g-anomalia" value="' . $rowGG['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '');
                                                    } else {
                                                        $anomalia = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-g-anomalia me-1" id="new-g-anomalia-' . $rowGG['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Segnala Anomalia"><i data-cs-icon="plus"></i></a>';
                                                    }
                                                    //feedback
                                                    if (isset($rowGG['feedback']) && $rowGG['feedback'] != '') {
                                                        $feedback = '<div class="mb-1 br-wrapper br-theme-cs-icon">
                                                                         <select name="rating" autocomplete="off" data-readonly="true" data-initial-rating="' . $rowGG['feedback'] . '" class="rating">
                                                                         <option value="1">1</option>
                                                                         <option value="2">2</option>
                                                                         <option value="3">3</option>
                                                                         <option value="4">4</option>
                                                                         <option value="5">5</option>
                                                                         </select>
                                                                         </div>
                                                                        <small><b>' . $rowGG['user_feedback'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $rowGG['lastUpdateFeedback'])->format("d/m/Y H:i") . '</b></small><br>
                                                                        ' . ($rowGG['userFeedback'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-primary mod-g-feedback me-1" id="mod-g-feedback-' . $rowGG['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Feedback</a><br><a href="javascript:void(0);" class="badge bg-primary reset-g-feedback" id="reset-g-feedback-' . $rowGG['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-g-feedback-' . $rowGG['id'] . '-form" method="POST"><input type="hidden" name="reset-g-feedback" value="' . $rowGG['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '') . '';
                                                    } else {
                                                        $feedback = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-g-feedback me-1" id="new-g-feedback-' . $rowGG['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Lascia Feedback"><i data-cs-icon="plus"></i></a>';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?= $giorniSettimana[$rowGG['ggSettimana']] ?>
                                                        </td>
                                                        <td>
                                                            <?= date('d/m/Y', strtotime($rowGG['dataInizio'])) ?>
                                                        </td>
                                                        <td>
                                                            <?= date('d/m/Y', strtotime($rowGG['dataFine'])) ?>
                                                        </td>
                                                        <td>
                                                            <?= $rowGG['min']/60 ?>
                                                        </td>
                                                        <td>
                                                            <?= $rowGG['oraInizio'] ?>
                                                        </td>
                                                        <td>
                                                            <?= $rowGG['oraFine'] ?>
                                                        </td>
                                                        <td><?= $anomalia ?></td>
                                                        <td><small><?= $rowGG['noteAnomalia'] ?></small></td>
                                                        <td><?= $feedback ?></td>
                                                        <td><small><?= $rowGG['noteFeedback'] ?></small></td>



                                                    </tr>
                                                <?php
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php  } else { ?>
            <h2 class="small-title">CARICAMENTO <?php
                                                if ($rowEvento['presenzaMh'] == '' || $rowEvento['presenzaMh'] == 0) {
                                                    echo '- NON PREVISTE';
                                                } ?></h2>


            <div class="row ">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div id="countCaricamento"></div>

                        <div class="card-body <?php if ($rowEvento['presenzaMh'] == '' || $rowEvento['presenzaMh'] == 0) {
                                                    echo 'd-none';
                                                } ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <th>Giorno</th>
                                                <th>Numero Risorse</th>
                                                <th>Durata Intervento</th>
                                                <th>Dalle</th>
                                                <th>Alle</th>
                                                <th>Note</th>
                                                <th>Anomalia</th>
                                                <th>Note Anomalia</th>
                                                <th>Feedback</th>
                                                <th>Note Feedback</th>

                                            </thead>
                                            <tbody id="tableCaricamento">
                                                <?php

                                                $giorniSettimana = array("", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato", "Domenica");
                                                $qDal = mysqli_query($c, "select  data from giornata where idAdesione=" . $_GET['idAdesione'] . " and idEsigenza=21 order by data limit 1");
                                                $rowDal = mysqli_fetch_assoc($qDal);
                                                $qAl = mysqli_query($c, "select  data from giornata where idAdesione=" . $_GET['idAdesione'] . " and idEsigenza=21 order by data desc limit 1");
                                                $rowAl = mysqli_fetch_assoc($qAl);
                                                $giorno = date('Y-m-d', strtotime($rowDal['data']));
                                                $settimana = 1;
                                                while (strtotime($giorno) <= strtotime($rowAl['data'])) {
                                                ?>
                                                    <tr>

                                                        <?php
                                                        $numGGSett = date('w', strtotime($giorno));
                                                        if ($numGGSett == 0) {
                                                            $numGGSett = 7;
                                                        }
                                                        $qGG = mysqli_query($c, "select g.*,ag.name as anomalia, CONCAT(u.nome,' ',u.cognome) as user_anomalia,CONCAT(uf.nome,' ',uf.cognome) as user_feedback  
                                                                                 from giornata g
                                                                                 LEFT JOIN anomalie_giornata ag ON (ag.id = g.idAnomalia) 
                                                                                 LEFT JOIN utente u ON (g.userAnomalia = u.id)
                                                                                 LEFT JOIN utente uf ON (g.userFeedback = uf.id) 
                                                                                 where g.idAdesione=" . $_GET['idAdesione'] . " and g.idEsigenza=21  and g.data='" . $giorno . "'");
                                                        if (mysqli_num_rows($qGG) > 0) {
                                                            $rowGG = mysqli_fetch_assoc($qGG);

                                                            //anomalia
                                                            if (isset($rowGG['anomalia']) && $rowGG['anomalia'] != '') {

                                                                $anomalia = '<div class="mb-1">
                                                                      <span class="text-danger"><small><b>' . $rowGG['anomalia'] . '</b></small></span>
                                                                    </div>
                                                                    <small><b>' . $rowGG['user_anomalia'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $rowGG['lastUpdateAnomalia'])->format("d/m/Y H:i") . '</b></small><br>
                                                                    ' . ($rowGG['decurtazioneAnomalia'] == 1 ? '<small><b>Decurtazione: </b><span class="text-danger">SI</span></small><br>' : '') . '
                                                                    <small><b>Fatturazione: </b>';
                                                                    if($rowGG['fatturazioneAnomalia']=="fatturare"){
                                                                        $anomalia .="<span class='text-success'>" . $rowGG['fatturazioneAnomalia'] . "</span></small><br>";
                                                                    }else{
                                                                        $anomalia .="<span class='text-danger'>" . $rowGG['fatturazioneAnomalia'];
                                                                        if($rowGG['fatturazioneAnomalia']=="modifica_fatturazione"){
                                                                            $anomalia .=" (".$rowGG['minAnomalia']." min)";
                                                                        }
                                                                        $anomalia .= "</span></small><br>"; 
                                                                    }
                                                                    
                                                                    $anomalia .=  ($rowGG['userAnomalia'] == $_SESSION['idUtente'] || $_SESSION['idRuolo']==1 ? '<a href="javascript:void(0);" class="badge bg-danger mod-g-anomalia me-1" id="mod-g-anomalia-' . $rowGG['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Segnalazione</a><br><a href="javascript:void(0);" class="badge bg-danger reset-g-anomalia" id="reset-g-anomalia-' . $rowGG['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-g-anomalia-' . $rowGG['id'] . '-form" method="POST"><input type="hidden" name="reset-g-anomalia" value="' . $rowGG['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '');
                                                            } else {
                                                                $anomalia = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-g-anomalia me-1" id="new-g-anomalia-' . $rowGG['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Segnala Anomalia"><i data-cs-icon="plus"></i></a>';
                                                            }
                                                            //feedback
                                                            if (isset($rowGG['feedback']) && $rowGG['feedback'] != '') {
                                                                $feedback = '<div class="mb-1 br-wrapper br-theme-cs-icon">
                                                                         
                                                                         <select name="rating" autocomplete="off" data-readonly="true" data-initial-rating="' . $rowGG['feedback'] . '" class="rating">
                                                                         <option value="1">1</option>
                                                                         <option value="2">2</option>
                                                                         <option value="3">3</option>
                                                                         <option value="4">4</option>
                                                                         <option value="5">5</option>
                                                                         </select>
                                                                         </div>
                                                                        <small><b>' . $rowGG['user_feedback'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $rowGG['lastUpdateFeedback'])->format("d/m/Y H:i") . '</b></small><br>
                                                                        ' . ($rowGG['userFeedback'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-primary mod-g-feedback me-1" id="mod-g-feedback-' . $rowGG['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Feedback</a><br><a href="javascript:void(0);" class="badge bg-primary reset-g-feedback" id="reset-g-feedback-' . $rowGG['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-g-feedback-' . $rowGG['id'] . '-form" method="POST"><input type="hidden" name="reset-g-feedback" value="' . $rowGG['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '') . '';
                                                            } else {
                                                                $feedback = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-g-feedback me-1" id="new-g-feedback-' . $rowGG['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Lascia Feedback"><i data-cs-icon="plus"></i></a>';
                                                            }
                                                            /*
                                                             
                                                            */
                                                        ?>

                                                            <td>
                                                                <?= $giorniSettimana[$numGGSett] . " - " . date('d/m', strtotime($giorno)) ?>
                                                            </td>
                                                            <td><?= $rowGG['numRisorse'] ?></td>
                                                            <td><?= $rowGG['min'] ?> min <?=" (" . number_format(($rowGG['min'] / 60), 2, ',', '') . ")"?></td>
                                                            <td><?= $rowGG['oraInizio'] ?></td>
                                                            <td><?= $rowGG['oraFine'] ?></td>
                                                            <td><?= $rowGG['note'] ?></td>
                                                            <td><?= $anomalia ?></td>
                                                            <td><small><?= $rowGG['noteAnomalia'] ?></small></td>
                                                            <td><?= $feedback ?></td>
                                                            <td><small><?= $rowGG['noteFeedback'] ?></small></td>

                                                        <?php

                                                        }
                                                        ?>
                                                    </tr>
                                                <?php
                                                    $giorno = date('Y-m-d', strtotime($giorno . ' +1 day'));
                                                    if ($numGGSett == 7) {
                                                        $settimana = $settimana + 1;
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

        <hr />
        <center>
            <h1 class="title">MATERIALE</h1>
        </center>
        <?php
        $tipologia = array(1, 6, 3, 4, 5);
        foreach ($tipologia as &$idTipologia) {
        ?>
            <h2 class="small-title"><?= TrovaValore($idTipologia, "tipologia_materiale", "name") ?>
                <?php
                $qMateriale = mysqli_query($c, "select * from evento_materiale where idEvento=" . $rowAdesione['idEvento'] . " and idTipologia=" . $idTipologia);
                if (mysqli_num_rows($qMateriale) == 0) {
                    echo " - NON PREVISTI";
                }
                ?>

            </h2>
            <div class="row <?php if (mysqli_num_rows($qMateriale) == 0) {
                                echo 'd-none';
                            } ?> ">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div id="countMat<?= $idTipologia ?>"></div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <?php
                                    $query = "SELECT  m.nome as materiale,
                                               m.codice,
                                               s.*,
                                               asp.name as anomalia, 
                                               CONCAT(u.nome,' ',u.cognome) as user_anomalia,
                                               CONCAT(uf.nome,' ',uf.cognome) as user_feedback 
                                               FROM spedizione as s 
                                               INNER JOIN materiale as m  on (m.id=s.idMateriale) 
                                               LEFT JOIN anomalie_spedizione asp ON (asp.id = s.idAnomalia) 
                                               LEFT JOIN utente u ON (s.userAnomalia = u.id)
                                               LEFT JOIN utente uf ON (s.userFeedback = uf.id) 
                                               WHERE idAdesione=" . $_GET['idAdesione'] . " and idTipologia=" . $idTipologia;

                                    $q = mysqli_query($c, $query);
                                    if (mysqli_num_rows($q) > 0) {
                                    ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="text-align:center">Codice</th>
                                                        <th scope="col" style="text-align:center;width:33%">Materiale</th>
                                                        <th scope="col" style="text-align:center">Qta</th>
                                                        <?php if ($idTipologia == 4) { ?>
                                                            <th scope="col" style="text-align:center">Numero Confezioni per Regalo</th>
                                                            <th scope="col" style="text-align:center">Quantità Fissa</th>
                                                            <th scope="col" style="text-align:center">Costo Produzione</th>
                                                            <th scope="col" style="text-align:center">Rientro Dati</th>
                                                            <th scope="col" style="text-align:center">Non Ritirare</th>
                                                            <th scope="col" style="text-align:center">Soglia minima per ritiro</th>

                                                        <?php } elseif ($idTipologia == 6) { ?>
                                                            <th scope="col" style="text-align:center; width:33%">Referenze</th>
                                                        <?php } ?>
                                                        <th>Anomalia</th>
                                                        <th>Note Anomalia</th>
                                                        <th>Feedback</th>
                                                        <th>Note Feedback</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($row = mysqli_fetch_assoc($q)) {
                                                        //anomalia
                                                        if (isset($row['anomalia']) && $row['anomalia'] != '') {

                                                            $anomalia = '<div class="mb-1">
                                                                      <span class="text-danger"><small><b>' . $row['anomalia'] . '</b></small></span>
                                                                    </div>
                                                                    <small><b>' . $row['user_anomalia'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $row['lastUpdateAnomalia'])->format("d/m/Y H:i") . '</b></small><br>
                                                                    ' . ($row['decurtazioneAnomalia'] == 1 ? '<small><b>Decurtazione: </b><span class="text-danger">SI</div></small><br>' : '') . '

                                                                    ' . ($row['userAnomalia'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-danger mod-s-anomalia me-1" id="mod-s-anomalia-' . $row['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Segnalazione</a><br><a href="javascript:void(0);" class="badge bg-danger reset-s-anomalia" id="reset-s-anomalia-' . $row['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-s-anomalia-' . $row['id'] . '-form" method="POST"><input type="hidden" name="reset-s-anomalia" value="' . $row['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '');
                                                        } else {
                                                            $anomalia = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-s-anomalia me-1" id="new-s-anomalia-' . $row['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Segnala Anomalia"><i data-cs-icon="plus"></i></a>';
                                                        }
                                                        //feedback
                                                        if (isset($row['feedback']) && $row['feedback'] != '') {
                                                            $feedback = '<div class="mb-1 br-wrapper br-theme-cs-icon">
                                                                         <select name="rating" autocomplete="off" data-readonly="true" data-initial-rating="' . $row['feedback'] . '" class="rating">
                                                                         <option value="1">1</option>
                                                                         <option value="2">2</option>
                                                                         <option value="3">3</option>
                                                                         <option value="4">4</option>
                                                                         <option value="5">5</option>
                                                                         </select>
                                                                         </div>
                                                                        <small><b>' . $row['user_feedback'] . '</b><br><b>' . DateTime::createFromFormat("Y-m-d H:i:s", $row['lastUpdateFeedback'])->format("d/m/Y H:i") . '</b></small><br>
                                                                        ' . ($row['userFeedback'] == $_SESSION['idUtente'] ? '<a href="javascript:void(0);" class="badge bg-primary mod-s-feedback me-1" id="mod-s-feedback-' . $row['id'] . '"><i class="fa fa-pencil" aria-hidden="true"></i> Modifica Feedback</a><br><a href="javascript:void(0);" class="badge bg-primary reset-s-feedback" id="reset-s-feedback-' . $row['id'] . '"><i class="fa fa-times"></i> Annulla</a><form id="reset-s-feedback-' . $row['id'] . '-form" method="POST"><input type="hidden" name="reset-s-feedback" value="' . $row['id'] . '"><input type="hidden" name="prevent_resend" value="' . uniqid() . '"></form>' : '') . '';
                                                        } else {
                                                            $feedback = '<a class="btn btn-sm btn-icon btn-icon-only btn-outline-primary new-s-feedback me-1" id="new-s-feedback-' . $row['id'] . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Lascia Feedback"><i data-cs-icon="plus"></i></a>';
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td style="vertical-align:middle;padding:0px;text-align:center;"><small><?= $row['codice'] ?></small></td>
                                                            <td style="vertical-align:middle;padding:0px;text-align:center;width:50%"><small><?= utf8_encode($row['materiale']) ?></small></td>
                                                            <td style="vertical-align:middle;padding:0px;text-align:center"><?= $row['quantita'] ?></td>
                                                            <?php if ($idTipologia == 4) {
                                                                $qEmRegali = mysqli_query($c, "select em.* from evento_materiale as em inner join adesione as a on a.idEvento=em.idEvento where a.id=" . $_GET['idAdesione'] . " and em.idMateriale=" . $row['idMateriale']);
                                                                $rowEmRegali = mysqli_fetch_assoc($qEmRegali);
                                                            ?>
                                                                <td style="vertical-align:middle;padding:0px;text-align:center"><?= $rowEmRegali['numConfezioni'] ?></td>
                                                                <td style="vertical-align:middle;padding:0px;text-align:center"><?= $rowEmRegali['quantitaFissa'] ?></td>
                                                                <td style="vertical-align:middle;padding:0px;text-align:center"><?= $rowEmRegali['costoProduzione'] ?></td>
                                                                <td style="vertical-align:middle;padding:0px;text-align:center"><?= $rowEmRegali['rd'] ?></td>
                                                                <td style="vertical-align:middle;padding:0px;text-align:center"><?= $rowEmRegali['nonRitirare'] ?></td>
                                                                <td style="vertical-align:middle;padding:0px;text-align:center"><?= $rowEmRegali['sogliaMinimaRitiro'] ?></td>

                                                            <?php  } elseif ($idTipologia == 6) { ?>
                                                                <td style="vertical-align:middle;padding:0px;text-align:left; width:50%">
                                                                    <?php
                                                                    $queryRef = "SELECT b.brand, tc.tradeCategory,g.gerarchia,r.descrizione as referenza FROM `spedizione_referenze` as mr 
                            left join referenza_brand as b on b.id=mr.idBrand
                            left join referenza_tradeCategory as tc on tc.id=mr.idtradeCategory
                            left join referenza_gerarchia as g on g.id=mr.idGerarchia
                            left join referenza as r on r.id=mr.idReferenza where mr.idSPedizione=" . $row['id'];

                                                                    $qRef = mysqli_query($c, $queryRef);
                                                                    while ($rowRef = mysqli_fetch_assoc($qRef)) {
                                                                        echo '<span class="badge bg-primary text-uppercase">' . $rowRef['brand'];
                                                                        if ($rowRef['tradeCategory'] != '') {
                                                                            echo  ' \ ' . $rowRef['tradeCategory'];
                                                                        }
                                                                        if ($rowRef['gerarchia'] != '') {
                                                                            echo  ' \ ' . $rowRef['gerarchia'];
                                                                        }
                                                                        if ($rowRef['referenza'] != '') {
                                                                            echo  ' \ ' . $rowRef['referenza'];
                                                                        }
                                                                        echo '</span><br/> ';
                                                                    }
                                                                    ?>
                                                                </td>

                                                            <?php } ?>
                                                            <td><?= $anomalia ?></td>
                                                            <td><small><?= $row['noteAnomalia'] ?></small></td>
                                                            <td><?= $feedback ?></td>
                                                            <td><small><?= $row['noteFeedback'] ?></small></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <?php $qCheckNote = mysqli_query($c, "select * from evento_noteDaSpecificare  where idEvento=" . $rowAdesione['idEvento']);
        if (mysqli_num_rows($qCheckNote) > 0) {

        ?>
            <hr />
            <center>
                <h3 class="title">NOTE DA SPECIFICARE</H3>
            </center>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12" id="TableNoteDaSpecificare">
                                    <?php
                                    $query = " SELECT adnd.*,evnd.domanda FROM `adesione_noteDaSpecificare` as adnd inner join evento_noteDaSpecificare as evnd on evnd.id=adnd.idDomanda
                                    where adnd.idAdesione=" . $_GET['idAdesione'] . " ";

                                    $q = mysqli_query($c, $query);
                                    $idPdvSelected = "";
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>

                                                    <th scope="col">Domanda</th>

                                                    <th scope="col">Risposta</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($row = mysqli_fetch_assoc($q)) {
                                                ?>
                                                    <tr>
                                                        <td style="vertical-align:middle" class="py-1"><?= $row['domanda'] ?></td>
                                                        <td style="vertical-align:middle" class="py-1"><?= $row['risposta'] ?></td>




                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>



</main>
<?php include 'modals/newAnomaliaGiornataModal.php'; ?>
<?php include 'modals/newFeedbackGiornataModal.php'; ?>
<?php include 'modals/modAnomaliaGiornataModal.php'; ?>
<?php include 'modals/modFeedbackGiornataModal.php'; ?>

<?php include 'modals/newAnomaliaSpedizioneModal.php'; ?>
<?php include 'modals/newFeedbackSpedizioneModal.php'; ?>
<?php include 'modals/modAnomaliaSpedizioneModal.php'; ?>
<?php include 'modals/modFeedbackSpedizioneModal.php'; ?>

<?php include '../common/footer.php'; ?>
<script src="<?= $link ?>assets/js/vendor/jquery.barrating.min.js"></script>
<script>
    $(document).ready(function() {
        $.post('ajax/count_ore_adesione.php', {
            idAdesione: $('#idAdesione').val(),

        }, function(response) {
            $('#countOreAdesione').html(response);
        });

        $.post('ajax/count_promoter.php', {
            idAdesione: $('#idAdesione').val(),

        }, function(response) {
            $('#countPromoter').html(response);
        });

        $.post('ajax/count_caricamento.php', {
            idAdesione: $('#idAdesione').val(),

        }, function(response) {
            $('#countCaricamento').html(response);
        });

        $.post('ajax/count_allestimento.php', {
            idAdesione: $('#idAdesione').val(),

        }, function(response) {
            $('#countAllestimento').html(response);
        });

        $.post('ajax/count_materiale.php', {
            idAdesione: $('#idAdesione').val(),
            idTipologia: 1
        }, function(response) {

            $('#countMat' + 1).html(response);
        });

        $.post('ajax/count_materiale.php', {
            idAdesione: $('#idAdesione').val(),
            idTipologia: 6
        }, function(response) {

            $('#countMat' + 6).html(response);
        });

        $.post('ajax/count_materiale.php', {
            idAdesione: $('#idAdesione').val(),
            idTipologia: 3
        }, function(response) {

            $('#countMat' + 3).html(response);
        });

        $.post('ajax/count_materiale.php', {
            idAdesione: $('#idAdesione').val(),
            idTipologia: 4
        }, function(response) {

            $('#countMat' + 4).html(response);
        });

        $.post('ajax/count_materiale.php', {
            idAdesione: $('#idAdesione').val(),
            idTipologia: 5
        }, function(response) {

            $('#countMat' + 5).html(response);
        });



        //giornata
        $(".new-g-anomalia").on("click", function() {
            var id_giornata = $(this).attr("id").split("-")[3];
            /*
            
            */
            $("#anomalia_giornata_type").on("change", function() {
                var type = $(this).val();
                //reset current value
                if ($('#anomalia_giornata').hasClass("select2-hidden-accessible")) {
                    $('#anomalia_giornata').val(null).trigger('change');
                    $("#anomalia_giornata").select2('destroy');
                }

                jQuery('#anomalia_giornata').select2({
                    allowClear: true,
                    debug: true,
                    placeholder: '',
                    dropdownParent: $('#newAnomaliaGiornataModal'),
                    ajax: {
                        url: "ajax/getAnomalieGiornataList.php",
                        dataType: "json",
                        type: "POST",
                        data: function(params) {
                            var p = {
                                search: params.term,
                                type: type
                            }
                            return p;
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.text,
                                        id: item.id,
                                        html_result: item.html_result,
                                        html_selected: item.html_selected
                                    }
                                })
                            };
                        }
                    },
                    escapeMarkup: function(m) {
                        return m;
                    },
                    templateResult: function(data) {

                        return data.html_result;
                    },
                    templateSelection: function(data) {

                        return data.html_selected || data.text;
                    },
                    minimumInputLength: 0,
                });

            })
            $("#id_giornata").val(id_giornata)
            $("#note_anomalia_giornata").val('')
            $("#anomalia_giornata_decurtazione").val(0)
            $("#anomalia_giornata_type").trigger("change")
            $("#newAnomaliaGiornataModal").modal("show")
        });
        $(".new-g-feedback").on("click", function() {
            var id_giornata = $(this).attr("id").split("-")[3];

            $("#id_giornata_f").val(id_giornata)
            $("#note_feedback_giornata").val('')
            $("#feedback_giornata").barrating({
                initialRating: $("#feedback_giornata").data('initialRating'),
                readonly: $("#feedback_giornata").data('readonly'),
                showValues: $("#feedback_giornata").data('showValues'),
                showSelectedRating: $("#feedback_giornata").data('showSelectedRating'),
            });
            $('#feedback_giornata').barrating('clear');
            $("#newFeedbackGiornataModal").modal("show")
        });
        $(".mod-g-anomalia").on("click", function() {
            var id_giornata = $(this).attr("id").split("-")[3];

            $.ajax({
                type: "POST",
                url: 'ajax/getAnomaliaGiornata.php',
                data: {
                    id_giornata: id_giornata
                },
                beforeSend: function(xhr) {

                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data['response'] == 'OK') {
                        giornata = data['payload'];
                        var type = giornata.anomalia_type
                        var anomalia_id = giornata.idAnomalia
                        var anomalia_name = giornata.anomalia
                        var decurtazione = (giornata.fatturazioneAnomalia == null ? "" : giornata.fatturazioneAnomalia)
                        var variazione_ore = giornata.variazioneOre
                        $("#anomalia_giornata_type_mod").val(type)
                        jQuery('#anomalia_giornata_mod').select2({
                            allowClear: true,
                            debug: true,
                            placeholder: '',
                            dropdownParent: $('#modAnomaliaGiornataModal'),
                            ajax: {
                                url: "ajax/getAnomalieGiornataList.php",
                                dataType: "json",
                                type: "POST",
                                data: function(params) {
                                    var p = {
                                        search: params.term,
                                        type: type
                                    }
                                    return p;
                                },
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                text: item.text,
                                                id: item.id,
                                                html_result: item.html_result,
                                                html_selected: item.html_selected
                                            }
                                        })
                                    };
                                }
                            },
                            escapeMarkup: function(m) {
                                return m;
                            },
                            templateResult: function(data) {

                                return data.html_result;
                            },
                            templateSelection: function(data) {

                                return data.html_selected || data.text;
                            },
                            minimumInputLength: 0,
                        });
                        var option = new Option(anomalia_name, anomalia_id, true, true);
                        $("#anomalia_giornata_mod").append(option).trigger('change');
                        // manually trigger the `select2:select` event
                        $("#anomalia_giornata_mod").trigger({
                            type: 'select2:select',
                            params: {
                                data: {
                                    id: anomalia_id,
                                    text: anomalia_name
                                }
                            }
                        });

                        $("#anomalia_giornata_type_mod").on("change", function() {
                            var type = $(this).val();
                            //reset current value
                            if ($('#anomalia_giornata_mod').hasClass("select2-hidden-accessible")) {
                                $('#anomalia_giornata_mod').val(null).trigger('change');
                                $("#anomalia_giornata_mod").select2('destroy');
                            }

                            jQuery('#anomalia_giornata_mod').select2({
                                allowClear: true,
                                debug: true,
                                placeholder: '',
                                dropdownParent: $('#modAnomaliaGiornataModal'),
                                ajax: {
                                    url: "ajax/getAnomalieGiornataList.php",
                                    dataType: "json",
                                    type: "POST",
                                    data: function(params) {
                                        var p = {
                                            search: params.term,
                                            type: type
                                        }
                                        return p;
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.text,
                                                    id: item.id,
                                                    html_result: item.html_result,
                                                    html_selected: item.html_selected
                                                }
                                            })
                                        };
                                    }
                                },
                                escapeMarkup: function(m) {
                                    return m;
                                },
                                templateResult: function(data) {

                                    return data.html_result;
                                },
                                templateSelection: function(data) {

                                    return data.html_selected || data.text;
                                },
                                minimumInputLength: 0,
                            });

                        })
                        $("#note_anomalia_giornata_mod").val(giornata.noteAnomalia)
                        $("#anomalia_giornata_decurtazione_mod").val(decurtazione)
                        setTimeout(function() {
                            $(".show_min_anomalia").trigger("change")
                            $("#min_anomalia_giornata_mod").val(variazione_ore)
                        }, 500)

                        $("#id_giornata_mod").val(giornata.id)
                        $("#modAnomaliaGiornataModal").modal("show");
                    } else {
                        showErrorToast(data['message'], 'Ops!');
                    }


                },
                error: function(xhr, status, errors) {
                    showErrorToast('Errore di comunicazione con il server', 'Ops!');
                }
            });

        });
        $(".reset-g-anomalia").on("click", function() {
            var id_giornata = $(this).attr("id").split("-")[3];
            if (confirm("Sei sicuro di voler annullare la segnalazione anomalia?") == true) {
                $("#reset-g-anomalia-" + id_giornata + "-form").submit();
            }

        });
        $(".mod-g-feedback").on("click", function() {
            var id_giornata = $(this).attr("id").split("-")[3];

            $.ajax({
                type: "POST",
                url: 'ajax/getFeedbackGiornata.php',
                data: {
                    id_giornata: id_giornata
                },
                beforeSend: function(xhr) {

                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data['response'] == 'OK') {
                        giornata = data['payload'];
                        $("#feedback_giornata_mod").barrating({
                            initialRating: $("#feedback_giornata").data('initialRating'),
                            readonly: $("#feedback_giornata").data('readonly'),
                            showValues: $("#feedback_giornata").data('showValues'),
                            showSelectedRating: $("#feedback_giornata").data('showSelectedRating'),
                        });
                        $("#feedback_giornata_mod").barrating('set', giornata.feedback)
                        $("#note_feedback_giornata_mod").val(giornata.noteFeedback)
                        $("#id_giornata_f_mod").val(giornata.id)
                        $("#modFeedbackGiornataModal").modal("show");
                    } else {
                        showErrorToast(data['message'], 'Ops!');
                    }


                },
                error: function(xhr, status, errors) {
                    showErrorToast('Errore di comunicazione con il server', 'Ops!');
                }
            });

        });
        $(".reset-g-feedback").on("click", function() {
            var id_giornata = $(this).attr("id").split("-")[3];
            if (confirm("Sei sicuro di voler resettare la valutazione?") == true) {
                $("#reset-g-feedback-" + id_giornata + "-form").submit();
            }

        });


        //spedizione
        $(".new-s-anomalia").on("click", function() {
            var id_spedizione = $(this).attr("id").split("-")[3];
            $("#anomalia_spedizione_type").on("change", function() {
                var type = $(this).val();
                //reset current value
                if ($('#anomalia_spedizione').hasClass("select2-hidden-accessible")) {
                    $('#anomalia_spedizione').val(null).trigger('change');
                    $("#anomalia_spedizione").select2('destroy');
                }

                jQuery('#anomalia_spedizione').select2({
                    allowClear: true,
                    debug: true,
                    placeholder: '',
                    dropdownParent: $('#newAnomaliaSpedizioneModal'),
                    ajax: {
                        url: "ajax/getAnomalieSpedizioneList.php",
                        dataType: "json",
                        type: "POST",
                        data: function(params) {
                            var p = {
                                search: params.term,
                                type: type
                            }
                            return p;
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.text,
                                        id: item.id,
                                        html_result: item.html_result,
                                        html_selected: item.html_selected
                                    }
                                })
                            };
                        }
                    },
                    escapeMarkup: function(m) {
                        return m;
                    },
                    templateResult: function(data) {

                        return data.html_result;
                    },
                    templateSelection: function(data) {

                        return data.html_selected || data.text;
                    },
                    minimumInputLength: 0,
                });

            })
            $("#id_spedizione").val(id_spedizione)
            $("#note_anomalia_spedizione").val('')
            $("#anomalia_spedizione_decurtazione").val(0)
            $("#newAnomaliaSpedizioneModal").modal("show")
        });
        $(".new-s-feedback").on("click", function() {
            var id_spedizione = $(this).attr("id").split("-")[3];

            $("#id_spedizione_f").val(id_spedizione)
            $("#note_feedback_spedizione").val('')
            $("#feedback_spedizione").barrating({
                initialRating: $("#feedback_spedizione").data('initialRating'),
                readonly: $("#feedback_spedizione").data('readonly'),
                showValues: $("#feedback_spedizione").data('showValues'),
                showSelectedRating: $("#feedback_spedizione").data('showSelectedRating'),
            });
            $('#feedback_spedizione').barrating('clear');
            $("#newFeedbackSpedizioneModal").modal("show")
        });
        $(".mod-s-anomalia").on("click", function() {
            var id_spedizione = $(this).attr("id").split("-")[3];

            $.ajax({
                type: "POST",
                url: 'ajax/getAnomaliaSpedizione.php',
                data: {
                    id_spedizione: id_spedizione
                },
                beforeSend: function(xhr) {

                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data['response'] == 'OK') {
                        spedizione = data['payload'];
                        var type = spedizione.anomalia_type
                        var anomalia_id = spedizione.idAnomalia
                        var anomalia_name = spedizione.anomalia
                        var decurtazione = (spedizione.decurtazioneAnomalia == null ? 0 : spedizione.decurtazioneAnomalia)
                        var variazione_ore = spedizione.variazioneOre
                        $("#anomalia_spedizione_type_mod").val(type)
                        jQuery('#anomalia_spedizione_mod').select2({
                            allowClear: true,
                            debug: true,
                            placeholder: '',
                            dropdownParent: $('#modAnomaliaSpedizioneModal'),
                            ajax: {
                                url: "ajax/getAnomalieSpedizioneList.php",
                                dataType: "json",
                                type: "POST",
                                data: function(params) {
                                    var p = {
                                        search: params.term,
                                        type: type
                                    }
                                    return p;
                                },
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                text: item.text,
                                                id: item.id,
                                                html_result: item.html_result,
                                                html_selected: item.html_selected
                                            }
                                        })
                                    };
                                }
                            },
                            escapeMarkup: function(m) {
                                return m;
                            },
                            templateResult: function(data) {

                                return data.html_result;
                            },
                            templateSelection: function(data) {

                                return data.html_selected || data.text;
                            },
                            minimumInputLength: 0,
                        });
                        var option = new Option(anomalia_name, anomalia_id, true, true);
                        $("#anomalia_spedizione_mod").append(option).trigger('change');
                        // manually trigger the `select2:select` event
                        $("#anomalia_spedizione_mod").trigger({
                            type: 'select2:select',
                            params: {
                                data: {
                                    id: anomalia_id,
                                    text: anomalia_name
                                }
                            }
                        });

                        $("#anomalia_spedizione_type_mod").on("change", function() {
                            var type = $(this).val();
                            //reset current value
                            if ($('#anomalia_spedizione_mod').hasClass("select2-hidden-accessible")) {
                                $('#anomalia_spedizione_mod').val(null).trigger('change');
                                $("#anomalia_spedizione_mod").select2('destroy');
                            }

                            jQuery('#anomalia_spedizione_mod').select2({
                                allowClear: true,
                                debug: true,
                                placeholder: '',
                                dropdownParent: $('#modAnomaliaSpedizioneModal'),
                                ajax: {
                                    url: "ajax/getAnomalieSpedizioneList.php",
                                    dataType: "json",
                                    type: "POST",
                                    data: function(params) {
                                        var p = {
                                            search: params.term,
                                            type: type
                                        }
                                        return p;
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.text,
                                                    id: item.id,
                                                    html_result: item.html_result,
                                                    html_selected: item.html_selected
                                                }
                                            })
                                        };
                                    }
                                },
                                escapeMarkup: function(m) {
                                    return m;
                                },
                                templateResult: function(data) {

                                    return data.html_result;
                                },
                                templateSelection: function(data) {

                                    return data.html_selected || data.text;
                                },
                                minimumInputLength: 0,
                            });

                        })
                        $("#note_anomalia_spedizione_mod").val(spedizione.noteAnomalia)
                        $("#anomalia_spedizione_decurtazione_mod").val(decurtazione)
                        setTimeout(function() {
                            $(".show_min_anomalia").trigger("change")
                            $("#min_anomalia_spedizione_mod").val(variazione_ore)
                        }, 500)

                        $("#id_spedizione_mod").val(spedizione.id)
                        $("#modAnomaliaSpedizioneModal").modal("show");
                    } else {
                        showErrorToast(data['message'], 'Ops!');
                    }


                },
                error: function(xhr, status, errors) {
                    showErrorToast('Errore di comunicazione con il server', 'Ops!');
                }
            });

        });
        $(".reset-s-anomalia").on("click", function() {
            var id_spedizione = $(this).attr("id").split("-")[3];
            if (confirm("Sei sicuro di voler annullare la segnalazione anomalia?") == true) {
                $("#reset-s-anomalia-" + id_spedizione + "-form").submit();
            }

        });
        $(".mod-s-feedback").on("click", function() {
            var id_spedizione = $(this).attr("id").split("-")[3];

            $.ajax({
                type: "POST",
                url: 'ajax/getFeedbackSpedizione.php',
                data: {
                    id_spedizione: id_spedizione
                },
                beforeSend: function(xhr) {

                },
                success: function(data) {
                    var data = $.parseJSON(data);
                    if (data['response'] == 'OK') {
                        spedizione = data['payload'];
                        $("#feedback_spedizione_mod").barrating({
                            initialRating: $("#feedback_spedizione").data('initialRating'),
                            readonly: $("#feedback_spedizione").data('readonly'),
                            showValues: $("#feedback_spedizione").data('showValues'),
                            showSelectedRating: $("#feedback_spedizione").data('showSelectedRating'),
                        });
                        $("#feedback_spedizione_mod").barrating('set', spedizione.feedback)
                        $("#note_feedback_spedizione_mod").val(spedizione.noteFeedback)
                        $("#id_spedizione_f_mod").val(spedizione.id)
                        $("#modFeedbackSpedizioneModal").modal("show");
                    } else {
                        showErrorToast(data['message'], 'Ops!');
                    }


                },
                error: function(xhr, status, errors) {
                    showErrorToast('Errore di comunicazione con il server', 'Ops!');
                }
            });

        });
        $(".reset-s-feedback").on("click", function() {
            var id_spedizione = $(this).attr("id").split("-")[3];
            if (confirm("Sei sicuro di voler resettare la valutazione?") == true) {
                $("#reset-s-feedback-" + id_spedizione + "-form").submit();
            }

        });
        $('.rating').each(function() {
            $(this).barrating({
                initialRating: $(this).data('initialRating'),
                readonly: $(this).data('readonly'),
                showValues: $(this).data('showValues'),
                showSelectedRating: $(this).data('showSelectedRating'),
            });
        });

        if ($(".min_anomalia").length > 0) {
            $(".min_anomalia").each(function() {
                if ($(this).find("input").val() == "") $(this).hide()
            })
        }
        if ($(".data_secondo_passaggio").length > 0) {
            $(".data_secondo_passaggio").each(function() {
                if ($(this).find("input").val() == "") $(this).hide()
            })
        }
        $(".show_min_anomalia").on("change", function() {

            let box = $(this).closest('.row')
            let vl = $(this).find('option:selected').text()

            box.find(".min_anomalia").hide()
            box.find(".min_anomalia").find("input").val("")
            box.find(".data_secondo_passaggio").hide()
            box.find(".data_secondo_passaggio").find("input").val("")

            if (vl.includes("ore extra")) {
                box.find(".min_anomalia").show()
            } else if (vl.includes("Passaggio")) {
                box.find(".data_secondo_passaggio").show()
            }

        })

        jQuery('.data').datepicker({
            orientation: 'bottom left',
            autoclose: true,
            language: 'it',
        });

    });
</script>
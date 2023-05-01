<?php
ini_set("session.cookie_httponly", 1);
include $path . 'common/login_messages.php';
include $path . 'common/function.php';
$c = conn();
$c_pdo = conn_pdo();
session_start();
if (!loginSession()) {
  header('Location: ' . $link . 'common/login.php');
  exit;
}
//ScriviLog($_SERVER['REQUEST_URI'], $_SESSION['idUtente'],'pagina visualizzata');
?>
<!DOCTYPE html>
<html lang="it" data-footer="true" data-override='{"attributes": {"placement": "vertical", "behaviour":"pinned", "layout": "boxed",  "navcolor": "light", "color": "light-red"}, "storagePrefix": "ecommerce-platform"}'>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Coca Cola HBC - Promomedia</title>
  <link rel="icon" sizes="57x57" href="https://it.coca-colahellenic.com/etc.clientlibs/cch/corporate/components/structure/basepage/clientlibs/resources/images/favicon.ico" type="image/x-icon" />
   <meta name="application-name" content="&nbsp;" />
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= $link ?>assets/font/CS-Interface/style.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/OverlayScrollbars.min.css" />

  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/dropzone.min.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/glide.core.min.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/baguetteBox.min.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/datatables.min.css" />

  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/select2.min.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/select2-bootstrap4.min.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/bootstrap-datepicker3.standalone.min.css" />

  <link rel="stylesheet" href="<?= $link ?>assets/css/vendor/tagify.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/styles.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/main.css" />


  <script src="<?= $link ?>assets/js/base/loader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js"></script>
  <script src="https://kit.fontawesome.com/7025f12ba1.js" crossorigin="anonymous"></script>
</head>

<body>
  <div id="root">
    <div id="nav" class="nav-container d-flex">
      <div class="nav-content d-flex">
        <!-- Logo Start -->
        <div class="logo position-relative m-0" style="width:100%">
          <a href="<?= $link ?>index.php" style="width:100%">
            <img src="https://it.coca-colahellenic.com/content/dam/cch/it/images/Logo-HBC.png" style="width:80%">
          </a>
        </div>

        <div class="user-container d-flex">
          <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php if ($_SESSION['sesso'] == "F") {  ?>
              <img class="profile" alt="profile" src="<?= $link ?>assets/img/profile/donna.svg" />
            <?php  } else { ?>
              <img class="profile" alt="profile" src="<?= $link ?>assets/img/profile/uomo.svg" />
            <?php } ?>

            <div class="name"><?= $_SESSION['nominativo'] ?></div>
          </a>
          <div class="dropdown-menu dropdown-menu-end user-menu wide">
            <div class="row mb-3 ms-0 me-0">
              <div class="col-12 ps-1 mb-2">
                <div class="text-extra-small text-primary">ACCOUNT</div>
              </div>
              <div class="col-6 ps-1 pe-1">
                <ul class="list-unstyled">
                  <li>
                    <a href="<?php echo $link ?>profilo/modifica_password.php">Sicurezza</a>
                  </li>

                </ul>
              </div>

            </div>

            <div class="row mb-1 ms-0 me-0">
              <div class="col-12 p-1 mb-3 pt-1">
                <div class="separator-light"></div>
              </div>
              <div class="col-6 ps-1 pe-1">
                <ul class="list-unstyled">
                  <li>
                    <a href="<?= $link ?>common/login.php?logout">
                      <i data-cs-icon="logout" class="me-2" data-cs-size="17"></i>
                      <span class="align-middle">Logout</span>
                    </a>
                  </li>

                </ul>
              </div>

            </div>
          </div>
        </div>
        <!-- User Menu End -->

        <!-- Icons Menu Start -->
        <ul class="list-unstyled list-inline text-center menu-icons ">

          <li class="list-inline-item">
            <a href="#" id="pinButton" class="pin-button">
              <i data-cs-icon="lock-on" class="unpin" data-cs-size="18"></i>
              <i data-cs-icon="lock-off" class="pin" data-cs-size="18"></i>
            </a>
          </li>
          <li class="list-inline-item">
            <a href="#" id="colorButton">
              <i data-cs-icon="light-on" class="light" data-cs-size="18"></i>
              <i data-cs-icon="light-off" class="dark" data-cs-size="18"></i>
            </a>
          </li>

        </ul>
        <!-- Icons Menu End -->

        <!-- Menu Start -->
        <div class="menu-container flex-grow-1">
          <ul id="menu" class="menu" style="margin-left:0">
            <?php if ($_SESSION['idRuolo'] == 1 || $_SESSION['idRuolo'] == 3) { ?>
              <li>
                <a href="<?= $link ?>dashboard/index.php">
                  <i data-cs-icon="home" class="icon" data-cs-size="18"></i>
                  <span class="label">Dashboard</span>
                </a>
              </li>
              <li>
                <a href="<?= $link ?>pt/list.php">
                  <i data-cs-icon="notification" class="icon" data-cs-size="18"></i>
                  <span class="label">Piano Tecnico</span>
                </a>
              </li>

              <li>
                <a href="<?= $link ?>evento/list.php">
                  <i data-cs-icon="notification" class="icon" data-cs-size="18"></i>
                  <span class="label">Evento</span>
                </a>
              </li>
              <li>
                <a href="<?= $link ?>adesione/list.php">
                  <i data-cs-icon="calendar" class="icon" data-cs-size="18"></i>
                  <span class="label">Adesione</span>
                </a>
              </li>
            
            <?php } else {  ?>

              <li>
                <a href="<?= $link ?>adesione/list.php">
                  <i data-cs-icon="calendar" class="icon" data-cs-size="18"></i>
                  <span class="label">Adesione</span>
                </a>
              </li>
              <li>
                <a href="<?= $link ?>evento/list.php">
                  <i data-cs-icon="notification" class="icon" data-cs-size="18"></i>
                  <span class="label">Evento</span>
                </a>
              </li>
            <?php }  ?>
            <!--
            <li>
              <a href="<?= $link ?>anomalie/list.php">
                <i data-cs-icon="warning-triangle" class="icon" data-cs-size="18"></i>
                <span class="label">Segnalazioni / Anomalie</span>
              </a>
            </li>
            <li>
              <a href="<?= $link ?>rd/evento.php">
                <i data-cs-icon="database" class="icon" data-cs-size="18"></i>
                <span class="label">Rientro Dati</span>
              </a>
            </li>
          
              <li>
                <a href="<?= $link ?>spedizioni/list.php">
                  <i data-cs-icon="send" class="icon" data-cs-size="18"></i>
                  <span class="label">Magazzino e Logistica</span>
                </a>
              </li>-->
            <?php if ($_SESSION['idRuolo'] == 1 || $_SESSION['idRuolo'] == 3) { ?>
              <hr />
              <li>
                <a href="#tabelle" data-href="tabelle">
                  <i class="icon icon-15 bi-table" style="margin-left:1px !important;"></i>
                  <span class="label">Tabelle</span>
                </a>
                <ul id="tabelle">
                  <li>
                    <a href="<?= $link ?>tabelle/azienda/list.php" data-href="tabelle/azienda/list.php">
                      <span class="label">Aziende</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/orecaricamento/list.php" data-href="tabelle/orecaricamento/list.php">
                      <span class="label">Ore Caricamento</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/orepromoter/list.php" data-href="tabelle/orepromoter/list.php">
                      <span class="label">Ore Promoter</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/festivita/list.php" data-href="tabelle/festivita/list.php">
                      <span class="label">Festività</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/categoriaevento/list.php" data-href="tabelle/categoriaevento/list.php">
                      <span class="label">Categorie Evento</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/livelloevento/list.php" data-href="tabelle/livelloevento/list.php">
                      <span class="label">Livelli Evento</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/canvasevento/list.php" data-href="tabelle/canvasevento/list.php">
                      <span class="label">Canvas Evento</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>tabelle/fornitorireferenti/list.php" data-href="tabelle/fornitorireferenti/list.php">
                      <span class="label">Fornitori e Referenti</span>
                    </a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="<?= $link ?>materiale/list.php">
                  <i data-cs-icon="boxes" class="icon " data-cs-size="18"></i>
                  <span class="label">Materiale</span>
                </a>
              </li>

              <li>
                <a href="#referenze" data-href="referenze">
                  <i data-cs-icon="boxes" class="icon " data-cs-size="18"></i>
                  <span class="label">Referenze</span>
                </a>
                <ul id="referenze">
                  <li>
                    <a href="<?= $link ?>referenze/list.php" data-href="referenze/list.php">
                      <span class="label">Lista Referenze</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>referenze/brands/list.php" data-href="referenze/brands/list.php">
                      <span class="label">Brands</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>referenze/gerarchia/list.php" data-href="referenze/gerarchia/list.php">
                      <span class="label">Gerarchie</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>referenze/tradecategory/list.php" data-href="referenze/tradecategory/list.php">
                      <span class="label">Trade Categories</span>
                    </a>
                  </li>

                </ul>
              </li>
              <!--   <li>
              <a href="<?= $link ?>materiale/list.php?tipo=2">
                <i data-cs-icon="cupcake" class="icon " data-cs-size="18"></i>
                <span class="label">Referenze</span>
              </a>
            </li>
            <li>
              <a href="<?= $link ?>materiale/list.php?tipo=3">
                <i data-cs-icon="gift" class="icon " data-cs-size="18"></i>
                <span class="label">Regali</span>
              </a>
            </li>
            -->

              <li>
                <a href="#punti_vendita" data-href="punti_vendita">
                  <i data-cs-icon="shop" class="icon " data-cs-size="18"></i>
                  <span class="label">Punti Vendita</span>
                </a>
                <ul id="punti_vendita">
                  <li>
                    <a href="<?= $link ?>punti_vendita/list.php" data-href="punti_vendita/list.php">
                      <span class="label">Lista Punti Vendita</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/storeformat/list.php" data-href="punti_vendita/storeformat/list.php">
                      <span class="label">Store Formats</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/nationalpurchasinggroup/list.php" data-href="punti_vendita/nationalpurchasinggroup/list.php">
                      <span class="label">National Purchasing Groups</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/nationalcustomer/list.php" data-href="punti_vendita/nationalcustomer/list.php">
                      <span class="label">National Customers</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/nationalcustomerdivision/list.php" data-href="punti_vendita/nationalcustomerdivision/list.php">
                      <span class="label">National Customer Divisions</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/customerplanningunit/list.php" data-href="punti_vendita/customerplanningunit/list.php">
                      <span class="label">Customer Planning Units</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/localdealpoint/list.php" data-href="punti_vendita/localdealpoint/list.php">
                      <span class="label">Local Deal Points</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/areanielsen/list.php" data-href="punti_vendita/areanielsen/list.php">
                      <span class="label">Areas Nielsen</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?= $link ?>punti_vendita/payer/list.php" data-href="punti_vendita/payer/list.php">
                      <span class="label">Payers</span>
                    </a>
                  </li>


                </ul>
              </li>
              <li>
                <a href="<?= $link ?>utente/list.php">
                  <i data-cs-icon="user" class="icon" data-cs-size="18"></i>
                  <span class="label">Utenti</span>
                </a>
              </li>
            <?php } ?>
            <hr />
            <li>
              <a href="<?= $link ?>helpdesk/">
                <i data-cs-icon="help" class="icon" data-cs-size="18"></i>
                <span class="label">Help Desk</span>
              </a>
            </li>
          </ul>
        </div>
        <!-- Menu End -->

        <!-- Mobile Buttons Start -->
        <div class="mobile-buttons-container">
          <!-- Menu Button Start -->
          <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-cs-icon="menu"></i>
          </a>
          <!-- Menu Button End -->
        </div>
        <!-- Mobile Buttons End -->
      </div>
      <div class="nav-shadow"></div>
    </div>
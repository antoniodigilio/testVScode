<?php
session_start();

if (loginSession()) {
  if (isset($_GET['logout'])) {
    logout();
  } else {
    header('Location: ' . $link . 'dashboard/index.php');
  }
}

?>
<!DOCTYPE html>
<html lang="it"  data-override='{"attributes": { "color": "light-red"}}'>

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
  <link rel="stylesheet" href="<?= $link ?>assets/css/styles.css" />
  <link rel="stylesheet" href="<?= $link ?>assets/css/main.css?v=<?php echo rand(); ?>" />
  <script src="<?= $link ?>assets/js/base/loader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.4/dist/sweetalert2.all.min.js"></script>
  <script src="https://kit.fontawesome.com/7025f12ba1.js" crossorigin="anonymous"></script>
</head>

<body class="h-100">
  <div id="root" class="h-100">
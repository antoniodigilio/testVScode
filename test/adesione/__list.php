<?php include '../common/config.php'; ?>
<?php include $path . 'common/header.php'; ?>
<?php
if (isset($_GET['idEvento'])) {
  $qEvento = mysqli_query($c, "select * from evento where id=" . $_GET['idEvento']);
  $rowEvento = mysqli_fetch_assoc($qEvento);
}

?>
<main>
  <div class="container">
    <div class="row">
      <div class="col">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
          <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
              <h1 class="mb-0 pb-0 display-4" id="title">Adesione
              </h1>

            </div>
            <!-- Title End -->

            <!-- Top Buttons Start -->
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end ">
              <!-- Add New Button Start -->
              <a class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable" href="<?php if (isset($_GET['idEvento'])) {
                                                                                                                echo 'form.php?idEvento=' . $_GET['idEvento'];
                                                                                                              } else {
                                                                                                                echo 'evento.php';
                                                                                                              } ?>">
                <strong> <i data-cs-icon="plus"></i>
                  <span>Inserisci Nuova Scheda Adesione</span></strong>
              </a>

              <!-- Add New Button End -->

              <!-- Check Button Start -->

              <!-- Check Button End -->
            </div>
            <!-- Top Buttons End -->
          </div>
          <div class="row pt-2">
            <div class="col-md-12">
              <h2>
                <center>
                  <span class="badge bg-primary"> <i class=" fa fa-cloud text-white"></i> Bozza</span>
                  <span class="badge bg-success"> <i class=" fa fa-check text-white"></i> Inviata all'agenzia</span>
                  <span class="badge bg-warning"> <i class=" fa fa-triangle-exclamation text-white"></i> Da Approvare</span>
                  <span class="badge bg-danger"> <i class=" fa fa-times text-white"></i> Annullata</span>
                </center>
              </h2>
            </div>
          </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Content Start -->
        <div class="data-table-rows slim">
          <!-- Controls Start -->
          <div class="row">
            <!-- Search Start -->
            <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
              <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                <input class="form-control datatable-search" placeholder="Search" data-datatable="#datatableRowsAdesione" />
                <span class="search-magnifier-icon">
                  <i data-cs-icon="search"></i>
                </span>
                <span class="search-delete-icon d-none">
                  <i data-cs-icon="close"></i>
                </span>
              </div>
            </div>
            <!-- Search End -->

            <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">

              <div class="d-inline-block">
                <!-- Print Button Start -->
                <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print" data-datatable="#datatableRowsAdesione" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0" title="Stampa" type="button">
                  <i data-cs-icon="print"></i>
                </button>
                <a class="btn btn-icon btn-icon-only btn-foreground-alternate shadow d-none" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0" title="Excel" href="csvEvento.php">

                  <i data-cs-icon="download"></i>
                </a>
                <!-- Print Button End -->


                <!-- Export Dropdown Start -->
                <div class="d-inline-block datatable-export" data-datatable="#datatableRowsAdesione">
                  <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                    <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown" data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Export">
                      <i data-cs-icon="download"></i>
                    </span>
                  </button>
                  <div class="dropdown-menu shadow dropdown-menu-end">
                    <button class="dropdown-item export-copy" type="button">Copy</button>
                    <button class="dropdown-item export-excel" type="button">Excel</button>
                    <button class="dropdown-item export-csv" type="button">CSV</button>
                  </div>
                </div>
                <!-- Export Dropdown End -->

                <!-- Length Start -->
                <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableRowsAdesione" data-childSelector="span">
                  <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                    <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0" title="Item Count">
                      10 Elementi
                    </span>
                  </button>
                  <div class="dropdown-menu shadow dropdown-menu-end">
                    <a class="dropdown-item" href="#">5 Elementi</a>
                    <a class="dropdown-item active" href="#">10 Elementi</a>
                    <a class="dropdown-item" href="#">20 Elementi</a>
                  </div>
                </div>
                <!-- Length End -->
              </div>
            </div>
          </div>
          <!-- Controls End -->

          <!-- Table Start -->
          <div class="data-table-responsive-wrapper">
            <table id="datatableRowsAdesione" class="data-table nowrap hover">
              <thead>
                <tr>


                  <th class="text-muted text-small text-uppercase" style="width:2%"></th>
                  <th class="text-muted text-small text-uppercase"></th>
                  <th class="text-muted text-small text-uppercase">id Adesione</th>
                  <th class="text-muted text-small text-uppercase">Codice Sap</th>
                  <th class="text-muted text-small text-uppercase">Denominazione Pdv</th>
                  <th class="text-muted text-small text-uppercase">Indirizzo Pdv</th>
                  <th class="text-muted text-small text-uppercase">Localita Pdv</th>
                  <th class="text-muted text-small text-uppercase">Evento</th>
                  <th class="text-muted text-small text-uppercase">Dal</th>
                  <th class="text-muted text-small text-uppercase">Al</th>
                  <th class="text-muted text-small text-uppercase">Inserita da</th>
                  <th class="text-muted text-small text-uppercase">Approvata da</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $query = "select *,a.id as idAdesione,app.idUtente as utenteApproval, e.nome as nomeEvento,e.approval as eventoApproval, a.stato as statoAdesione,pdv.codice as codicesap, concat(inseritaDa.cognome,' ',inseritaDa.nome) as inseritaDaUtente, concat(inseritaDa.cognome,' ',inseritaDa.nome) as approvataDaUtente,e.id as idEvento from adesione as a inner join pdv on pdv.id=a.idpdv inner join evento as e on e.id=a.idEvento  ";
                $query .= " left join utente as inseritaDa on inseritaDa.id=a.inseritaDa left join utente as approvataDa on approvataDa.id=a.approvataDa ";
                $query .= "  left join evento_approval as app on app.idEvento=e.id and app.idUtente=" . $_SESSION['idUtente'];
                $query .= " where 1=1 ";
                if (isset($_GET['idEvento'])) {
                  $query .= " and e.id=" . $_GET['idEvento'];
                }
                $query .= " order by a.id desc";

                $q = mysqli_query($c, $query);

                while ($row = mysqli_fetch_assoc($q)) {

                ?>
                  <tr>


                    <td style="width:2%">
                      <?php if ($row['statoAdesione'] == 1) {
                      ?>
                        <i class=" fa fa-cloud text-primary"></i>
                      <?php
                      } elseif ($row['statoAdesione'] == 2) {
                      ?>
                        <i class=" fa fa-check text-success"></i>
                      <?php
                      } elseif ($row['statoAdesione'] == 3) {
                      ?>
                        <i class=" fa fa-triangle-exclamation text-warning"></i>
                      <?php
                      } elseif ($row['statoAdesione'] == 4) {
                      ?>
                        <strong><i class=" fa fa-times text-danger"></i></strong>
                      <?php
                      } else {
                        echo $row['statoAdesione'];
                      }  ?>
                    </td>
                    <td style="">
                      <a href="view.php?idAdesione=<?= $row['idAdesione'] ?>" class="btn  btn-sm  btn-outline-dark " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Visualizza Scheda Adesione"><i class="fa fa-eye"></i></a>

                      <?php if ($row['statoAdesione'] != 4 && $row['inseritaDa'] == $_SESSION['idUtente']) { ?>
                        <a href="form.php?idAdesione=<?= $row['idAdesione'] ?>&idEvento=<?= $row['idEvento'] ?>" class="btn  btn-sm  btn-outline-primary " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Modifica Scheda Adesione"><i class="fa fa-pencil"></i></a>
                      <?php } ?>
                      <?php if ( $row['inseritaDa'] == $_SESSION['idUtente']) { ?>
                      <a href="duplica.php?idAdesione=<?= $row['idAdesione'] ?>" class="btn btn-sm   btn-outline-warning " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Duplica Evento"><i class="fa fa-clone"></i></a>
                      <?php } ?>
                      <?php if ($row['statoAdesione'] == 1 && $row['inseritaDa'] == $_SESSION['idUtente']) { ?>

                        <?php if ($row['eventoApproval'] == 1) { ?>
                          <button onclick="cambiaStato(<?= $row['idAdesione'] ?>,3)" class="btn  btn-sm  btn-outline-success " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Invia Per Approvazione"><i class="fa fa-send"></i></button>
                        <?php } else { ?>
                          <button onclick="cambiaStato(<?= $row['idAdesione'] ?>,2)" class="btn  btn-sm  btn-outline-success " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Invia all'agenzia"><i class="fa fa-send"></i></button>
                        <?php } ?>
                        <button onclick="elimina(<?= $row['idAdesione'] ?>)" class="btn  btn-sm  btn-outline-danger " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Elimina Bozza"><i class="fa fa-trash"></i></button>

                      <?php } elseif ($row['statoAdesione'] == 2 && $row['inseritaDa'] == $_SESSION['idUtente']) { ?>

                        <button onclick="cambiaStato(<?= $row['idAdesione'] ?>,4)" class="btn  btn-sm  btn-outline-danger " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Annulla"><i class="fa fa-times"></i></button>

                      <?php } elseif ($row['statoAdesione'] == 3) { ?>
                        <?php if ($row['utenteApproval'] == $_SESSION['idUtente']) { ?>

                          <button onclick="approva(<?= $row['idAdesione'] ?>)" class="btn  btn-sm  btn-outline-info " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Approva"><i class="fa fa-thumbs-up"></i></button>
                        <?php } ?>
                        <?php if ($row['inseritaDa'] == $_SESSION['idUtente']) { ?>
                          <button onclick="cambiaStato(<?= $row['idAdesione'] ?>,4)" class="btn  btn-sm  btn-outline-danger " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Annulla"><i class="fa fa-times"></i></button>
                        <?php } ?>
                      <?php } ?>


                    </td>

                    <td><?= $row['idAdesione'] ?></td>
                    <td><?= $row['codicesap']  ?></td>
                    <td><?= utf8_encode($row['ragioneSociale'] . "") ?></td>
                    <td><?= utf8_encode($row['indirizzo'] . "") ?></td>
                    <td><?= utf8_encode($row['comune'] . "") ?></td>
                    <td><?= sprintf('%05d', $row['idEvento']) . " - " . $row['nomeEvento']  ?></td>
                    <td><?= date_format(date_create($row['dal']), "d/m/Y") ?></td>
                    <td><?= date_format(date_create($row['al']), "d/m/Y") ?></td>
                    <td><?= utf8_encode($row['inseritaDaUtente'] . "")  ?></td>
                    <td><?= utf8_encode($row['approvataDaUtente'] . "")  ?></td>


                  </tr>
                <?php } ?>


              </tbody>
            </table>
          </div>
          <!-- Table End -->
        </div>
        <!-- Content End -->

        <!-- Add Edit Modal Start -->

        <!-- Add Edit Modal End -->
      </div>
    </div>
  </div>
</main>
<?php include '../common/footer.php'; ?>

<script src="<?= $link ?>assets/js/plugins/datatable.editablerowsAdesione.js"></script>

<script type="text/javascript">
  if (typeof EditableRowsAdesione !== 'undefined') {
    const editableRowsAdesione = new EditableRowsAdesione();
  }

  function cambiaStato(idAdesione, stato) {

    $.post('ajax/cambia_stato.php', {
      idAdesione: idAdesione,
      stato: stato,

    }, function(response) {
      if (response == "errore budget") {
        Swal.fire({
          html: "Budget superato",
          icon: 'error'
        })
      } else {

        location.reload();
      }
    });
  }

  function approva(idAdesione) {

    $.post('ajax/approva_adesione.php', {
      idAdesione: idAdesione,
    }, function(response) {
      location.reload();
    });
  }

  function elimina(idAdesione) {

    $.post('ajax/delete_adesione.php', {
      idAdesione: idAdesione,
    }, function(response) {
      location.reload();
    });
  }
  /*
  AdesioneList(1);
  AdesioneList(2);
  AdesioneList(3);
  AdesioneList(4);

  function AdesioneList(stato) {
    var idEvento = $('#idEvento').val();

    $.post('ajax/adesioneList.php', {
      idEvento: idEvento,
      stato: stato,

    }, function(response) {
      $('#divTab' + stato).html(response);
    });

  }

  function cambiaStato(id, stato) {

    $.post('ajax/cambiaStato.php', {
      id: id,
      stato: stato,

    }, function(response) {

      AdesioneList(stato);
      $('#btn1').removeClass('active');
      $('#btn2').removeClass('active');
      $('#btn3').removeClass('active');
      $('#btn4').removeClass('active');

      $('#tab1').removeClass('active show');
      $('#tab2').removeClass('active show');
      $('#tab3').removeClass('active show');
      $('#tab4').removeClass('active show');

      $('#btn' + stato).addClass('active');

      $('#tab' + stato).addClass('active show');

    });

  }*/
</script>
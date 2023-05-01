<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
?>
<?php include '../common/config.php'; ?>
<?php include $path . 'common/header.php'; ?>

<main>
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <?php
        // $c = conn();
        if (isset($_GET['idEvento'])) {
          // $_GET['idEvento'] = (int)$_GET['idEvento'];
          if (is_numeric($_GET['idEvento'])) {

            $queryEv = "select * from evento where id=" . mysqli_escape_string($c, $_GET['idEvento']);
            $qEv = mysqli_query($c, $queryEv);
            if (mysqli_num_rows($qEv) == 0) {
              redirect("list.php");
              //   echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
              // echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
            }
          } else {
            redirect("list.php");
          }
        }

        ?>
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">

          <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7 mb-3 mt-1">
              <h1 class="mb-0 pb-0 display-4" id="title">Adesioni</h1>

            </div>
            <!-- Title End -->

            <!-- Top Buttons Start -->
            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end mb-3 mt-1">
              <!-- Add New Button Start -->
              <?php if ($_SESSION['idRuolo'] == 1 || $_SESSION['idRuolo'] == 3 || $_SESSION['idRuolo'] == 5) { ?>
                <a href="<?php if (isset($_GET['idEvento'])) {
                            echo 'form.php?idEvento=' . $_GET['idEvento'];
                          } else {
                            echo 'evento.php';
                          } ?>" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                  <i data-cs-icon="plus"></i>
                  <span>Nuova Scheda Adesione</span>
                </a>
              <?php } ?>


              <!-- Add New Button End -->

              <!-- Check Button Start -->

              <!-- Check Button End -->
            </div>
            <!-- Top Buttons End -->
          </div>
          <div class="row pt-2">
            <div class="col-md-12">
              <h2>

                <span class="badge bg-primary"><i class=" fa fa-cloud text-white"></i> Bozza</span>
                <span class="badge bg-success"><i class=" fa fa-check text-white"></i> Inviata all'agenzia</span>
                <span class="badge bg-warning"><i class=" fa fa-triangle-exclamation text-white"></i> Da Approvare</span>
                <span class="badge bg-danger"><i class=" fa fa-times text-white"></i> Annullata</span>

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
                <input class="form-control datatable-search" placeholder="Cerca" data-datatable="#datatableRowsGen" />
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
                <div class="dropdown-as-select d-inline-block datatable-filter" data-datatable="#datatableRowsGen" data-childSelector="span">
                  <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                    <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0" title="stato">
                      Tutti gli stati
                    </span>
                  </button>
                  <div class="dropdown-menu shadow dropdown-menu-end">
                    <a class="dropdown-item active" datatable-filter-for="a.stato|none" href="#">Tutti gli stati</a>
                    <a class="dropdown-item" datatable-filter-for="a.stato|1|=" href="#"><i class="fa fa-cloud text-primary"></i> Bozza</a>
                    <a class="dropdown-item" datatable-filter-for="a.stato|2|=" href="#"><i class="fa fa-check text-success"></i> Inviata all'agenzia</a>
                    <a class="dropdown-item" datatable-filter-for="a.stato|3|=" href="#"><i class="fa fa-triangle-exclamation text-warning"></i> Da approvare</a>
                    <a class="dropdown-item" datatable-filter-for="a.stato|4|=" href="#"><i class="fa fa-times text-danger"></i> Annullata</a>
                  </div>
                </div>
                <!-- Print Button Start -->
                <button class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print" data-datatable="#datatableRowsGen" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0" title="Print" type="button">
                  <i data-cs-icon="print"></i>
                </button>
                <!-- Print Button End -->

                <!-- Export Dropdown Start -->
                <div class="d-inline-block datatable-export" data-datatable="#datatableRowsGen">
                  <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                    <span class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown" data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Export">
                      <i data-cs-icon="download"></i>
                    </span>
                  </button>
                  <div class="dropdown-menu shadow dropdown-menu-end">
                    <button class="dropdown-item export-copy" type="button">Copia</button>
                    <button class="dropdown-item export-excel" type="button">Excel</button>
                    <button class="dropdown-item export-csv" type="button">CSV</button>
                  </div>
                </div>
                <!-- Export Dropdown End -->

                <!-- Length Start -->
                <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#datatableRowsGen" data-childSelector="span">
                  <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                    <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-delay="0" title="Item Count">
                      10 Elementi
                    </span>
                  </button>
                  <div class="dropdown-menu shadow dropdown-menu-end">
                    <a class="dropdown-item" href="#">5 Elementi</a>
                    <a class="dropdown-item" href="#">10 Elementi</a>
                    <a class="dropdown-item active" href="#">20 Elementi</a>
                    <a class="dropdown-item" href="#">50 Elementi</a>
                    <a class="dropdown-item" href="#">100 Elementi</a>
                    <a class="dropdown-item" href="#">200 Elementi</a>
                    <a class="dropdown-item" href="#">Tutti</a>

                  </div>
                </div>
                <!-- Length End -->
              </div>
            </div>
          </div>
          <!-- Controls End -->

          <!-- Table Start -->
          <div class="data-table-responsive-wrapper">
            <table id="datatableRowsGen" class="data-table nowrap hover">
              <thead>
                <tr>
                  <th class="text-muted text-small text-uppercase" style="width:2%"></th>
                  <th class="text-muted text-small text-uppercase">id Adesione</th>
                  <th class="text-muted text-small text-uppercase">Evento</th>
                  <th class="text-muted text-small text-uppercase">Codice Sap</th>
                  <th class="text-muted text-small text-uppercase">Denominazione Pdv</th>
                  <th class="text-muted text-small text-uppercase">Indirizzo Pdv</th>
                  <th class="text-muted text-small text-uppercase">Localita Pdv</th>
                  <th class="text-muted text-small text-uppercase">Num Settimana</th>
                  <th class="text-muted text-small text-uppercase">Dal</th>
                  <th class="text-muted text-small text-uppercase">Al</th>
                  <th class="ps-0 text-muted text-small text-uppercase">Evento Prom</th>
                  <th class="ps-0 text-muted text-small text-uppercase">Evento Mh</th>
                  <th class="text-muted text-small text-uppercase">Inserita da</th>
                  <th class="text-muted text-small text-uppercase">Approvata da</th>
                  <th class="text-muted text-small text-uppercase">Stato</th>
                  <th></th>

                </tr>



              </thead>


              <tbody>



              </tbody>
            </table>
          </div>
          <!-- Table End -->
        </div>
        <!-- Content End -->


      </div>
    </div>
  </div>
</main>

<?php include $path . 'common/footer.php'; ?>
<script src="<?= $link ?>assets/js/plugins/datatable.editablerowsGen.js"></script>
<script>
  $(document).ready(function() {


    var filters = []
    d_data = $("#datatableRowsGen").DataTable({
      scrollX: true,
      buttons: ['copy', 'excel', 'csv', 'print'],
      sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
      pageLength: 20,
      lengthMenu: [5, 10, 20, 50, 100, 200, -1],
      language: {
        url: '<?= $link . 'assets/js/plugins/datatable.it.json'; ?>'
      },
      'processing': true,
      'serverSide': true,
      'stateSave': false,
      'serverMethod': 'POST',
      'orderCellsTop': true,
      'fixedColumns': {
        left: 0,
        right: 1
      },
      'ajax': {
        'url': 'ajax/datatable.php<?php if (isset($_GET['idEvento'])) {
                                    echo '?idEvento=' . $_GET['idEvento'];
                                  } ?>',
        "data": function(d) {
          d.filters = filters
        },
        "dataSrc": function(json) {
          var return_data = new Array();
          for (var i = 0; i < json.data.length; i++) {
            const date1 = new Date(json.data[i]._dal_);
            const date2 = new Date();
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));


            var actions = "";
            actions += '<a href="view.php?idAdesione=' + json.data[i].idAdesione + '" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-dark" data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Visualizza Scheda Adesione"><i class="fa fa-eye"></i></a>'
            actions += '<a target="_blank" href="downloadPdf.php?idAdesione=' + json.data[i].idAdesione + '&token=' + json.data[i].token + '" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-alternate" data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Visualizza Scheda Adesione"><i class="fa fa-file-pdf"></i></a>'
            if (json.data[i].statoAdesione != 4 && (json.data[i].inseritaDa == '<?php echo $_SESSION['idUtente'] ?>' || 1 == <?php if ($_SESSION['idRuolo'] == 1) {
                                                                                                                                echo 1;
                                                                                                                              } else {
                                                                                                                                echo 0;
                                                                                                                              } ?>)) {
              if (json.data[i].statoAdesione == 2 && diffDays<=15) {

              } else {
                actions += '<a href="form.php?idAdesione=' + json.data[i].idAdesione + '&idEvento=' + json.data[i].idEvento + '" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-primary " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Modifica Scheda Adesione"><i class="fa fa-pencil"></i></a>'

              }
            }
            if ((json.data[i].inseritaDa == '<?php echo $_SESSION['idUtente'] ?>' || 1 == <?php if ($_SESSION['idRuolo'] == 1) {
                                                                                            echo 1;
                                                                                          } else {
                                                                                            echo 0;
                                                                                          } ?>)) {
              actions += '<a href="duplica.php?idAdesione=' + json.data[i].idAdesione + '" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-warning " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Duplica Evento"><i class="fa fa-clone"></i></a>'
            }
            if (json.data[i].statoAdesione == 1 && (json.data[i].inseritaDa == '<?php echo $_SESSION['idUtente'] ?>' || 1 == <?php if ($_SESSION['idRuolo'] == 1) {
                                                                                                                                echo 1;
                                                                                                                              } else {
                                                                                                                                echo 0;
                                                                                                                              } ?>)) {
              if (json.data[i].eventoApproval == 1) {
                actions += '<a onclick="cambiaStato(' + json.data[i].idAdesione + ',3)" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-success " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Invia Per Approvazione"><i class="fa fa-send"></i></a>'
              } else {
                actions += '<a onclick="cambiaStato(' + json.data[i].idAdesione + ',2)" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-success " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Invia all \'agenzia"><i class="fa fa-send"></i></a>'
              }
              actions += '<a onclick="elimina(' + json.data[i].idAdesione + ')" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-danger " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Elimina Bozza"><i class="fa fa-trash"></i></a>'
            } else if (json.data[i].statoAdesione == 2 && (json.data[i].inseritaDa == '<?php echo $_SESSION['idUtente'] ?>' || 1 == <?php if ($_SESSION['idRuolo'] == 1) {
                                                                                                                                      echo 1;
                                                                                                                                    } else {
                                                                                                                                      echo 0;
                                                                                                                                    } ?>)) {
              if (json.data[i].annullabile == 1) {
                actions += '<a onclick="cambiaStato(' + json.data[i].idAdesione + ',4)" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-danger " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Annulla"><i class="fa fa-times"></i></a>'
              }
            } else if (json.data[i].statoAdesione == 3) {
              if (json.data[i].utenteApproval == '<?php echo $_SESSION['idUtente'] ?>' || 1 == <?php if ($_SESSION['idRuolo'] == 1) {
                                                                                                  echo 1;
                                                                                                } else {
                                                                                                  echo 0;
                                                                                                } ?>) {
                actions += '<button onclick="approva(' + json.data[i].idAdesione + ')" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-info " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Approva"><i class="fa fa-thumbs-up"></i></button>'
              }
              if (json.data[i].inseritaDa == '<?php echo $_SESSION['idUtente'] ?>' || 1 == <?php if ($_SESSION['idRuolo'] == 1) {
                                                                                              echo 1;
                                                                                            } else {
                                                                                              echo 0;
                                                                                            } ?>) {
                actions += '<button onclick="cambiaStato(' + json.data[i].idAdesione + ',4)" class="btn btn-sm btn-icon btn-icon-only me-1 btn-outline-danger " data-bs-delay="0" data-bs-placement="top" data-bs-toggle="tooltip" title="Annulla"><i class="fa fa-times"></i></button>'

              }
            }

            var stato_adesione = json.data[i].statoAdesione;
            if(stato_adesione==1){
              stato_adesione = '<i class="fa fa-cloud text-info"></i>'
            }else if(stato_adesione==1){
              stato_adesione = '<i class="fa fa-check text-success"></i>'
            }else if(stato_adesione==3){
              stato_adesione = '<i class="fa fa-triangle-exclamation text-warning"></i>'
            }else if(stato_adesione==4){
              stato_adesione = '<i class="fa fa-times text-danger"></i>'
            }
            

            
            return_data.push({
              'statoAdesione': stato_adesione,
              'idAdesione': json.data[i].idAdesione,
              'codicesap': json.data[i].codicesap,
              'ragioneSocialePdv': json.data[i].ragioneSocialePdv,
              'indirizzo': json.data[i].indirizzo,
              'comune': json.data[i].comune,
              'evento': '<small>'+json.data[i].idEvento + ' - ' + json.data[i].nomeEvento+'</small>',
              '_dal_': itDateNoTime(mysqlDatetoNoTimeJs(json.data[i]._dal_)),
              '_al_': itDateNoTime(mysqlDatetoNoTimeJs(json.data[i]._al_)),
              'numSettimana': json.data[i].numSettimana,
              'eventoProm': json.data[i].eventoProm,
              'eventoMh': json.data[i].eventoMh,
              'inseritaDaUtente': json.data[i].inseritaDaUtente,
              'approvataDaUtente': json.data[i].approvataDaUtente,
              'stringStato': json.data[i].stringStato,

              'actions': actions
            })
          }
          return return_data;
        }
      },

      drawCallback: function() {
        $('.po').popover()
      },
      columnDefs: [{
          orderable: false,
          targets: [6, 9, 10, 13]
        }

      ],
      order: [1, 'desc'],

      "columns": [{
          'data': 'statoAdesione'
        },
        {
          'data': 'idAdesione'
        },
        {
          'data': 'evento'
        },
        {
          'data': 'codicesap'
        },
        {
          'data': 'ragioneSocialePdv'
        },
        {
          'data': 'indirizzo'
        },
        {
          'data': 'comune'
        },
       
        {
          'data': 'numSettimana'
        },
        {
          'data': '_dal_'
        },
        {
          'data': '_al_'
        },
        {
          'data': 'eventoProm'
        },
        {
          'data': 'eventoMh'
        },
        {
          'data': 'inseritaDaUtente'
        },
        {
          'data': 'approvataDaUtente'
        },
        {
          'data': 'stringStato'
        },
        {
          'data': 'actions'
        },

      ]

    });

    document.querySelectorAll(".datatable-filter .dropdown-item").forEach((el) => {
      el.addEventListener("click", function() {
        var f = el.getAttribute("datatable-filter-for")
        var f_arr = f.split("|")
        var k = f_arr[0]
        if (f_arr[1] == 'none') {
          filters.forEach(function(filter, i) {
            if (filter.col == k) {
              filters.splice(i, 1);
            }
          });
        } else {
          filters.forEach(function(filter, i) {
            if (filter.col == k) {
              filters.splice(i, 1);
            }
          });
          filters.push({
            "col": k,
            "val": f_arr[1],
            "op": f_arr[2]
          })

        }
        d_data.draw()
      });
    });
    $.each($('.input-filter', d_data.table().header()), function() {
      var column = d_data.column($(this).index());

      $('input', this).on('keyup change', function() {
        if (column.search() !== this.value) {
          column
            .search(this.value)
            .draw();
        }
      });
    });

    if (typeof EditableRowsGen !== 'undefined') {
      const editableRowsGen = new EditableRowsGen(d_data);
    }



  });


  function cambiaStato(idAdesione, stato) {
    //annulla  
    if (stato == 4) {
      if (window.confirm("Sei sicuro di voler annullare l'adesione?")) {
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
    } else {
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

  }

  function approva(idAdesione) {

    $.post('ajax/approva_adesione.php', {
      idAdesione: idAdesione,
    }, function(response) {
      location.reload();
    });
  }

  function elimina(idAdesione) {
    if (window.confirm("Sei sicuro di voler eliminare l'adesione?")) {
      $.post('ajax/delete_adesione.php', {
        idAdesione: idAdesione,
      }, function(response) {
        location.reload();
      });
    }
  }
</script>
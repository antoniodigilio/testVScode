<?php include '../common/config.php'; ?>
<?php include $path . 'common/header.php';
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
                            <h1 class="mb-0 pb-0 display-4">Help Desk</h1>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-5 sh-20">
                    <div class="card-body row g-0">
                        <div class="col-12">

                            <div class="cta-3">Guida al portale</div>
                            <div class="text-muted mb-4 ">Scarica la guida al portale</div>
                            <a href="javascript:void(0);" data-file-path="Barilla_Guida Portale EventiMOP_2.pdf" class="dwn-file btn btn-icon btn-icon-start btn-outline-primary stretched-link sw-15 ">
                                <i data-cs-icon="download"></i>
                                <span>Download</span>
                            </a>
                            <form method="POST" id="dwn-file-form" target="_blank" action="">
                                <!-- files/downloadFiles.php --->
                                <input type="hidden" name="file-path" id="file-path" value="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-5 sh-20">
                    <div class="h-100 p-4 text-center align-items-center d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column justify-content-center align-items-center sh-5 sw-5 rounded-xl bg-gradient-primary mb-2">
                            <i data-cs-icon="email" class="text-white"></i>
                        </div>
                        <p class="mb-0 lh-1">Contattaci via e-mail</p>
                        <a href="mailto:_______________">
                            <p class="cta-6 mb-0 text-primary "><b>___________________</b></p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-5 sh-20">
                    <div class="h-100 p-4 text-center align-items-center d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column justify-content-center align-items-center sh-5 sw-5 rounded-xl bg-gradient-primary mb-2">
                            <i data-cs-icon="phone" class="text-white"></i>
                        </div>
                        <p class="mb-0 lh-1">Contattaci per telefono</p>
                        <a href="tel:+3900000000000">
                            <p class="cta-3 mb-0 text-primary ">+39 0000000000</p>
                        </a>
                    </div>
                </div>
            </div>


        </div>


</main>
<?php include $path . 'common/footer.php'; ?>
<script>
    $(document).ready(function() {
        $(".dwn-file").on("click", function() {
            var file_path = $(this).data('file-path')
            console.log(file_path)
            if (file_path) {
                $("#file-path").val(file_path)
                $("#dwn-file-form").submit();
            }
        })

    })
</script>
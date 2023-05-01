<div class="modal fade" id="modAnomaliaSpedizioneModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modAnomaliaSpedizioneModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modAnomaliaSpedizioneForm" novalidate="novalidate" class="needs-validation" enctype="multipart/form-data" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Aggiorna Anomalia Spedizione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="mb-1 top-label">
                                <select class="form-control" required name="anomalia_spedizione_type" id="anomalia_spedizione_type_mod">
                                    <?php echo anomaliaSpedizioneTypeSelect($c_pdo); ?>
                                </select>
                                <span>TIPOLOGIA ANOMALIA *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-12">
                            <label class="mb-1 top-label">
                                <select class="form-control" required name="anomalia_spedizione" id="anomalia_spedizione_mod">

                                </select>
                                <span>SELEZIONA ANOMALIA *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        
                        <div class="col-12">
                            <label class="mb-1 top-label">
                                <select class="form-control show_min_anomalia" required 
                                    name="anomalia_spedizione_decurtazione" id="anomalia_spedizione_decurtazione_mod" <?php if ($_SESSION['idRuolo'] == 5) { echo 'readonly'; } ?>>
                                    <!--
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    -->
                                    <option value="fatturare">Da Fatturare</option>
                                    <option value="non_fatturare">Da NON Fatturare</option>
                                    <option value="modifica_fatturazione">Modifica Fatturazione (ore extra/ore in meno)</option>
                                    <option value="secondo_passaggio">Secondo Passaggio</option>
                                </select>
                                <span>FATTURAZIONE *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-12 min_anomalia">
                            <div class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="number" <?php if ($_SESSION['idRuolo'] == 5) { echo 'readonly'; } ?>>
                                <span>MINUTI ORE EXTRA/ORE IN MENO</span>
                            </div>
                        </div>
                        <div class="col-12 data_secondo_passaggio">
                            <div class="mb-1 top-label">
                                <input name="data_secondo_passaggio" id="data_secondo_passaggio_spedizione_mod" class="form-control data" 
                                    data-date-start-date="<?php echo date("d/m/Y"); ?>" <?php if ($_SESSION['idRuolo'] == 5) { echo 'readonly'; } ?>>
                                <span>DATA SECONDO PASSAGGIO</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 top-label">
                                <textarea name="note_anomalia_spedizione" id="note_anomalia_spedizione_mod" class="form-control" rows="2"></textarea>
                                <span>NOTE ANOMALIA</span>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="prevent_resend" value="<?php echo uniqid(); ?>">
                    <input type="hidden" name="id_spedizione" id="id_spedizione_mod" value="">
                    <input type="hidden" name="mod-s-anomalia" value="1">



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon btn-icon-start btn-outline-danger" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-icon btn-icon-start btn-primary"><i class="fa fa-save"></i> Modifica</button>
                </div>
            </form>
        </div>
    </div>
</div>
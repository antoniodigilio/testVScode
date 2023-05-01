<div class="modal fade" id="newFeedbackGiornataModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="newFeedbackGiornataModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="newFeedbackGiornataForm" novalidate="novalidate" class="needs-validation" enctype="multipart/form-data" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Lascia Feedback Giornata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 d-none">
                        <div class="col-12">
                            <label>FEEDBACK *</label>
                            <label class="mb-1 top-label">
                                <div class="mb-1 br-wrapper br-theme-cs-icon">
                                    <select required name="feedback_giornata" id="feedback_giornata" autocomplete="off" data-readonly="false" data-initial-rating="0">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>


                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-12">
                            <div class="mb-1 top-label">
                                <textarea name="note_feedback_giornata" id="note_feedback_giornata" class="form-control" rows="2"></textarea>
                                <span>NOTE FEEDBACK</span>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>E' rispettato il giorno concordato per il servizio di caricamento? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>E' rispettata la fascia giornaliera prevista per il passaggio? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>Il servizio viene effettuato segnalando eventuali anomalie al personale del pdv? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>Qual è il feedback del capo reparto/direttore del pdv sul servizio? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricamento dello scaffale CSD rispetto alla capienza utile *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricamento dello scaffale TEA rispetto alla capienza utile *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricamento dello scaffale SPORT DRINK rispetto alla capienza utile *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricamento dello scaffale ENERGY DRINK rispetto alla capienza utile *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricamento dello scaffale BEVANDE VEGETALI rispetto alla capienza utile *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>E' disponibile il prodotto per il caricamento degli scaffali? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>La rotazione del prodotto viene effettuata? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>

                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-4">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricati al 100% *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-4">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% di caricati al 50% *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-4">
                            <label class="mb-1 top-label">
                                <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="text">
                                <span>% al solo velo *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>E' disponibile il prodotto per il caricamento degli Expo/ED? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>La rotazione del prodotto viene effettuata? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>Viene rispettato lo standard di caricamento preimpostato? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>


                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-6">
                            <label class="mb-1 top-label">
                                <select class="form-control ">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <span>Vieneeffettuata la manutenzione dell' Isola promozionale? *</span>
                                <div class="invalid-feedback">Valore non consentito!</div>
                            </label>
                        </div>


                    </div>


                    <div class="col-12 min_anomalia">
                        <div class="mb-1 top-label">
                            <input name="min_anomalia" id="min_anomalia_spedizione_mod" class="form-control" type="number" <?php if ($_SESSION['idRuolo'] == 5) {
                                                                                                                                echo 'readonly';
                                                                                                                            } ?>>
                            <span>MINUTI ORE EXTRA/ORE IN MENO</span>
                        </div>
                    </div>

                    <input type="hidden" name="prevent_resend" value="<?php echo uniqid(); ?>">
                    <input type="hidden" name="id_giornata" id="id_giornata_f" value="">
                    <input type="hidden" name="new-g-feedback" value="1">



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-icon btn-icon-start btn-outline-danger" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-icon btn-icon-start btn-primary"><i class="fa fa-save"></i> Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
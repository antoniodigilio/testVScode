<div class="modal fade" id="modFeedbackGiornataModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modFeedbackGiornataModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modFeedbackGiornataForm" novalidate="novalidate" class="needs-validation" enctype="multipart/form-data" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Aggiorna Feedback Giornata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label>FEEDBACK *</label>

                            <label class="mb-1 top-label">
                                <div class="mb-1 br-wrapper br-theme-cs-icon">
                                    <select required name="feedback_giornata" id="feedback_giornata_mod" autocomplete="off" data-readonly="false" data-initial-rating="0">
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
                                <textarea name="note_feedback_giornata" id="note_feedback_giornata_mod" class="form-control" rows="2"></textarea>
                                <span>NOTE FEEDBACK</span>
                            </div>
                        </div>


                    </div>
                 

                <input type="hidden" name="prevent_resend" value="<?php echo uniqid(); ?>">
                <input type="hidden" name="id_giornata" id="id_giornata_f_mod" value="">
                <input type="hidden" name="mod-g-feedback" value="1">



        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-icon btn-icon-start btn-outline-danger" data-bs-dismiss="modal">Annulla</button>
            <button type="submit" class="btn btn-icon btn-icon-start btn-primary"><i class="fa fa-save"></i> Modifica</button>
        </div>
        </form>
    </div>
</div>
</div>
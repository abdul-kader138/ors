<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang('Upload_Document'); ?></h4>
            </div>
            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo admin_form_open_multipart("players/upload_player_document/" . $players->id, $attrib); ?>
            <div class="modal-body">
                <div class="form-group">
                    <?= lang("Document", "Document") ?>
                    <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false" data-show-preview="false" class="form-control file">
                </div>
                <div class="form-group">
                    <?= lang("note", "note"); ?>
                    <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $this->sma->decode_html($inv->note)), 'class="form-control" id="note"'); ?>
                </div>
            </div>

            <div class="modal-footer">
                <?php echo form_submit('upload_document', lang('Upload_Document'), 'class="btn btn-primary"'); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_warehouse'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open_multipart("system_settings/add_warehouse", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>
                <?php echo form_input('code', '', 'class="form-control" id="code" required="required"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
                <?php echo form_input('name', '', 'class="form-control" id="name" required="required"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
                <?php echo form_input('phone', '', 'class="form-control" id="phone"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="email"><?php echo $this->lang->line("email"); ?></label>
                <?php echo form_input('email', '', 'class="form-control" id="email"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="Principal"><?php echo $this->lang->line("Principal_*"); ?></label>
                <?php echo form_input('principal', '', 'class="form-control" id="principal" required="required"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="General_Council_Representative"><?php echo $this->lang->line("General_Council_Representative_*"); ?></label>
                <?php echo form_input('gcp', '', 'class="form-control" id="gcp" required="required"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="Zonal_Representative"><?php echo $this->lang->line("Zonal_Representative_*"); ?></label>
                <?php echo form_input('zonal_representative', '', 'class="form-control" id="zonal_representative" required="required"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="Home_Ground"><?php echo $this->lang->line("Home_Ground"); ?></label>
                <?php echo form_input('home_ground', '', 'class="form-control" id="home_ground" required="required"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>
                <?php echo form_textarea('address', '', 'class="form-control" id="address" required="required"'); ?>
            </div>
            <div class="form-group">
                <?= lang("School_Logo", "image") ?>
                <input id="image" type="file" data-browse-label="<?= lang('browse'); ?>" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_warehouse', lang('add_warehouse'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>

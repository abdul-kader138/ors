<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_warehouse'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open_multipart("system_settings/edit_warehouse/" . $id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>
                <?php echo form_input('code', $warehouse->code, 'class="form-control" id="code" required="required"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
                <?php echo form_input('name', $warehouse->name, 'class="form-control" id="name" required="required"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
                <?php echo form_input('phone', $warehouse->phone, 'class="form-control" id="phone"'); ?>
            </div>


            <div class="form-group">
                <label class="control-label" for="email"><?php echo $this->lang->line("email"); ?></label>
                <?php echo form_input('email', $warehouse->email, 'class="form-control" id="email"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="Principal"><?php echo $this->lang->line("Principal_*"); ?></label>
                <?php echo form_input('principal', $warehouse->principal, 'class="form-control" id="principal" required="required"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="General_Council_Representative"><?php echo $this->lang->line("General_Council_Representative_*"); ?></label>
                <?php echo form_input('gcp', $warehouse->gcp, 'class="form-control" id="gcp" required="required"'); ?>
            </div>

            <div class="form-group">
                <label class="control-label" for="Zonal_Representative"><?php echo $this->lang->line("Zonal_Representative_*"); ?></label>
                <?php echo form_input('zonal_representative', $warehouse->zonal_representative, 'class="form-control" required="required" id="zonal_representative"'); ?>
            </div>
            <div class="form-group">
                <label class="control-label" for="Home_Ground"><?php echo $this->lang->line("Home_Ground"); ?></label>
                <?php echo form_input('home_ground', $warehouse->home_ground, 'class="form-control" id="home_ground"'); ?>
            </div>


            <div class="form-group">
                <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>
                <?php echo form_textarea('address', $warehouse->address, 'class="form-control" id="address" required="required"'); ?>
            </div>
            <div class="form-group">
                <?= lang("warehouse_map", "image") ?>
                <input id="image" type="file" data-browse-label="<?= lang('browse'); ?>" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_warehouse', lang('edit_warehouse'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
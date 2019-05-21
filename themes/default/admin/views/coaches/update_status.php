<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('update_status'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open_multipart("coaches/update_coaches_status/" . $coaches->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= lang('Coach_Details'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped table-borderless" style="margin-bottom:0;">
                        <tbody>
                        <tr>
                            <td><?= lang('ID'); ?></td>
                            <td><?= $coaches->cfl; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('Name'); ?></td>
                            <td><?= $coaches->first_name . " " . $players->last_name; ?></td>
                        </tr>

                        <tr>
                            <td><?= lang('Gender'); ?></td>
                            <td><?= $coaches->gender; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('Zone'); ?></td>
                            <td><strong><?= $coaches->zone ?></strong></td>
                        </tr>
                        <tr>
                            <td><?= lang('Created_At'); ?></td>
                            <td><?= $coaches->created_date; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <?= lang('status', 'status'); ?>
                <?php
                $opts = array('Pending' => lang('Pending'), 'Approved' => lang('Approved'), 'Rejected' => lang('Rejected'));
                ?>
                <?= form_dropdown('status', $opts, $coaches->c_status, 'class="form-control" id="status" required="required" style="width:100%;"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>

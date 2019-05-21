<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;"
                    onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang("Coach_- "); ?><?= $coach->first_name . ' ' . $coach->last_name ?></h4>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-xs-4">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . strtolower($user->gender) . '.png" class="avatar">';
                    ?>
                </div>
                <div class="col-xs-7">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped dfTable">
                            <tbody>
                            <tr>
                                <td colspan="2" style="background-color:#FFF;"></td>
                            </tr>
                            <tr>
                                <td><?= lang("ID"); ?></td>
                                <td><?= $coach->cfl; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("User_Name"); ?></td>
                                <td><?= $user->username; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Email"); ?></td>
                                <td><?= $user->email; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Phone"); ?></td>
                                <td><?= $user->phone; ?></td>
                            </tr>

                            <tr>
                                <td><?= lang("Gender"); ?></td>
                                <td><?= $coach->gender; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("School_Name"); ?></td>
                                <td><?= $warehouses->name; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Zone"); ?></td>
                                <td><?= $coach->zone; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Division"); ?></td>
                                <td><?= $category->name; ?></td>
                            </tr>

                            <tr>
                                <td><?= lang("Status"); ?></td>
                                <td><?= $coach->c_status; ?></td>
                            </tr>

                            <?php if ($updated_by) { ?>
                                <tr>
                                    <td><?= lang("Status_Updated_By"); ?></td>
                                    <td><?= $updated_by->first_name." ".$updated_by->last_name; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if ($coach->status_updated_date) { ?>
                                <tr>
                                    <td><?= lang("Status_Updated_Date"); ?></td>
                                    <td><?= $coach->status_updated_date; ?></td>
                                </tr>
                            <?php } ?>

                            <?php if ($coach->document) { ?>
                                <tr>
                                    <td><?= lang("Download_Document"); ?></td>
                                    <td><a href="<?php echo base_url() . "assets/uploads/coaches/" . $coach->document;?>" download><?=$coach->document?></a></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped dfTable">
                                <thead><th>Address</th></thead>
                                <tbody>
                                <tr>
                                    <td><?= $coach->address; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="buttons">
            <div class="btn-group btn-group-justified">
                <div class="btn-group">
                    <a href="<?= admin_url('coaches/edit/' . $coach->id) ?>" class="tip btn btn-info tip"
                       title="<?= lang('Edit_Coach') ?>">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('Edit') ?></span>
                    </a>
                </div>
                <div class="btn-group">
                    <a href="#" class="tip btn btn-danger bpo" title="<b><?= lang("Delete_Coach") ?></b>"
                       data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= admin_url('coaches/delete/' . $coach->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                       data-html="true" data-placement="top">
                        <i class="fa fa-trash-o"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                    </a>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.tip').tooltip();
            });
        </script>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.change_img').click(function (event) {
            event.preventDefault();
            var img_src = $(this).attr('href');
            $('#pr-image').attr('src', img_src);
            return false;
        });
    });
</script>

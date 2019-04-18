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
            <h4 class="modal-title" id="myModalLabel"><?= $players->first_name . ' ' . $players->last_name ?></h4>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-xs-5">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                    ?>
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped dfTable table-right-left">
                            <tbody>
                            <tr>
                                <td colspan="2" style="background-color:#FFF;"></td>
                            </tr>
                            <tr>
                                <td><?= lang("Email"); ?></td>
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
                                <td><?= lang("Date_Of_Birth"); ?></td>
                                <td><?= $this->sma->hrsd($players->dob); ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Birth_Certificate_Pin"); ?></td>
                                <td><?= $players->bcp; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Gender"); ?></td>
                                <td><?= $players->gender; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("School_Name"); ?></td>
                                <td><?= $warehouses->name; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Is_Tagged_With_Team"); ?></td>
                                <td><?= ($players->is_tagged== 1) ? 'Yes' : 'No'; ?></td>
                            </tr>

                            <tr>
                                <td><?= lang("Team_Name"); ?></td>
                                <td><?= $players->team_id; ?></td>
                            </tr>
                            <tr>
                                <td><?= lang("Year"); ?></td>
                                <td><?= $players->year; ?></td>
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
                        <a href="<?= admin_url('players/edit/' . $players->id) ?>" class="tip btn btn-info tip"
                           title="<?= lang('Edit_Player') ?>">
                            <i class="fa fa-edit"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Edit') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="#" class="tip btn btn-danger bpo" title="<b><?= lang("Delete_Player") ?></b>"
                           data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= admin_url('players/delete/' . $players->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
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

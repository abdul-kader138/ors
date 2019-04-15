<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Add_Player'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('Add_Player'); ?></p>

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo admin_form_open("players/add", $attrib);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('Name', 'Name'); ?>
                                <div class="controls">
                                    <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'class="form-control" id="name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>


                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', '', 'class="form-control" id="phone" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('email', 'email'); ?>
                                <div class="controls">
                                    <input type="email" id="email" name="email" class="form-control"
                                           required="required"/>
                                    <?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Date_Of_Birth', 'Date_Of_Birth'); ?>
                                <div class="controls">
                                    <?php echo form_input('dob', (isset($_POST['dob']) ? $_POST['dob'] : ''), 'class="form-control input-tip date" id="dob" readonly required="required"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo lang('Birth_Certificate_Pin', 'Birth_Certificate_Pin'); ?>
                                <div class="controls">
                                    <?php echo form_input('bcp', (isset($_POST['bcp']) ? $_POST['bcp'] : ''), 'class="form-control" id="bcp" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('Year', 'Year'); ?>
                                <?php
                                $opt = array("2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                echo form_dropdown('year', $opt, (isset($_POST['year']) ? $_POST['year'] : ''), 'id="year" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>

                            <div class="form-group all">
                                <?= lang("Image", "Image") ?>
                                <input id="image" type="file" data-browse-label="<?= lang('browse'); ?>" name="image" data-show-upload="false"
                                       data-show-preview="false" accept="image/*" class="form-control file">
                            </div>

                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div class="clearfix"></div>
                            <div>
                                <div class="form-group">
                                    <?php echo lang('username', 'username'); ?>
                                    <div class="controls">
                                        <input type="text" id="username" name="username" class="form-control"
                                               required="required" pattern=".{4,20}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo lang('password', 'password'); ?>
                                    <div class="controls">
                                        <?php echo form_password('password', '', 'class="form-control tip" id="password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="' . lang('pasword_hint') . '"'); ?>
                                        <span class="help-block"><?= lang('pasword_hint') ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?php echo lang('confirm_password', 'confirm_password'); ?>
                                    <div class="controls">
                                        <?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password" required="required" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= lang("warehouse", "warehouse"); ?>
                                    <?php
                                    $wh[''] = lang('select') . ' ' . lang('warehouse');
                                    foreach ($schools as $school) {
                                        $wh[$school->id] = $school->name;
                                    }
                                    echo form_dropdown('school_id', $wh, (isset($_POST['school_id']) ? $_POST['school_id'] : ''), 'id="school_id" class="form-control select" style="width:100%;" ');
                                    ?>
                                </div>

                                <div class="form-group">
                                    <?= lang("View_right", "View_right"); ?>
                                    <?php
                                    $vropts = array(1 => lang('all_records'), 0 => lang('own_records'));
                                    echo form_dropdown('view_right', $vropts, (isset($_POST['view_right']) ? $_POST['view_right'] : 1), 'id="view_right" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("edit_right", "edit_right"); ?>
                                    <?php
                                    $opts = array(1 => lang('yes'), 0 => lang('no'));
                                    echo form_dropdown('edit_right', $opts, (isset($_POST['edit_right']) ? $_POST['edit_right'] : 0), 'id="edit_right" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>

                                <div class="form-group">
                                    <?= lang('status', 'status'); ?>
                                    <?php
                                    $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                    echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" required="required" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox" name="notify" value="1" id="notify" />
                                        <?= lang('notify_user_by_email') ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <p><?php echo form_submit('add_player', lang('Add_Player'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

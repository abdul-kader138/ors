<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Edit_Player'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('Edit_Player'); ?></p>

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo admin_form_open("players/edit/" . $player->id, $attrib);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('First_Name', 'First_Name').'<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', (isset($_POST['first_name']) ? $_POST['first_name'] : $player->last_name), 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Last_Name', 'Last_Name').'<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('last_name', (isset($_POST['last_name']) ? $_POST['last_name'] : $player->last_name), 'class="form-control" id="last_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('Male' => lang('Male'), 'Female' => lang('Female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $player->gender), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>


                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', (isset($_POST['phone']) ? $_POST['phone'] : $user->phone), 'class="form-control" id="phone" required="required"'); ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div class="clearfix"></div>
                            <div>
                                <div class="form-group">
                                    <?php echo lang('Date_Of_Birth', 'Date_Of_Birth'); ?>
                                    <div class="controls">
                                        <?php echo form_input('dob', (isset($_POST['dob']) ? $_POST['dob'] : $this->sma->hrsd($player->dob)), 'class="form-control input-tip date" id="dob" readonly required="required"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo lang('Birth_Certificate_Pin', 'Birth_Certificate_Pin'); ?>
                                    <div class="controls">
                                        <?php echo form_input('bcp', (isset($_POST['bcp']) ? $_POST['bcp'] : $player->bcp), 'class="form-control" id="bcp" required="required"'); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= lang('Year', 'Year'); ?>
                                    <?php
                                    $opt = array("2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                    echo form_dropdown('year', $opt, (isset($_POST['year']) ? $_POST['year'] : $player->year), 'id="year" required="required" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("School", "School"); ?>
                                    <?php
                                    $wh[''] = lang('select') . ' ' . lang('School');
                                    foreach ($schools as $school) {
                                        $wh[$school->id] = $school->name;
                                    }
                                    echo form_dropdown('school_id', $wh, (isset($_POST['school_id']) ? $_POST['school_id'] : $player->school_id), 'id="school_id" class="form-control select" style="width:100%;" ');
                                    ?>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <p><?php echo form_submit('edit_player', lang('Edit_Player'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

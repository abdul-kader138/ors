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
                                <?= lang("Zone", "Zone") . '<b>*</b>'; ?>
                                <?php
                                foreach ($zones as $zone) {
                                    $wh12[$zone->name] = $zone->name;
                                }
                                echo form_dropdown('zone', $wh12, (isset($_POST['zone']) ? $_POST['zone'] : $player->zone), 'id="zone" class="form-control select" style="width:100%;" ');
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

                            <div class="form-group">
                                <?php echo lang('First_Name', 'First_Name') . '<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', (isset($_POST['first_name']) ? $_POST['first_name'] : $player->first_name), 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Last_Name', 'Last_Name') . '<b> *</b>'; ?>
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
                                <?php echo lang('email', 'email'); ?>
                                <div class="controls">
                                    <?php
                                    $atr = array('name' => 'email', 'id' => 'email', 'type' => 'email', 'class' => 'form-control');
                                    echo form_input($atr, (isset($_POST['email']) ? $_POST['email'] : $user->email)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', (isset($_POST['phone']) ? $_POST['phone'] : $user->phone), 'class="form-control" id="phone"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Last_School_Attended', 'Last_School_Attended') . '<b>*</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('last_attend_school', (isset($_POST['last_attend_school']) ? $_POST['last_attend_school'] : $player->last_attend_school), 'class="form-control" required="required" id="last_attend_school"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('SEA_Year', 'SEA_Year') . '<b>*</b>'; ?>
                                <?php
                                $opt = array("2012" => lang('2012'), "2013" => lang('2013'), "2014" => lang('2014'), "2015" => lang('2015'), "2016" => lang('2016'), "2017" => lang('2017'), "2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                echo form_dropdown('sea_year', $opt, (isset($_POST['sea_year']) ? $_POST['sea_year'] : $player->sea_year), 'id="sea_year" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>

                            <div class="form-group">
                                <?php echo lang('SEA_Number', 'SEA_Number'); ?>
                                <div class="controls">
                                    <?php echo form_input('sea_number', (isset($_POST['sea_number']) ? $_POST['sea_number'] : $player->sea_number), 'class="form-control" id="sea_number"'); ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div>
                                <div class="form-group">
                                    <?= lang("Division", "Division") . '<b>*</b>'; ?>
                                    <?php
                                    foreach ($categories as $category) {
                                        $gp[$category->id] = $category->name;
                                    }
                                    echo form_dropdown('division', $gp, (isset($_POST['division']) ? $_POST['division'] : $player->division), 'id="division" class="form-control select" style="width:100%;" ');
                                    ?>
                                </div>
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
                                    <?php echo lang('Jersey_Number', 'Jersey_Number'); ?>
                                    <div class="controls">
                                        <?php echo form_input('jersey_number', (isset($_POST['jersey_number']) ? $_POST['jersey_number'] : $player->jersey_number), 'class="form-control" id="jersey_number"'); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= lang('Enrolment_Year_At_Present_School', 'Enrolment_Year_At_Present_School') . '<b>*</b>'; ?>
                                    <?php
                                    $opt = array("2012" => lang('2012'), "2013" => lang('2013'), "2014" => lang('2014'), "2015" => lang('2015'), "2016" => lang('2016'), "2017" => lang('2017'), "2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                    echo form_dropdown('pey', $opt, (isset($_POST['pey']) ? $_POST['pey'] : $player->pey), 'id="pey" required="required" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>

                                <div class="form-group">
                                    <?= lang("Present_Class", "Present_Class") . '<b>*</b>'; ?>
                                    <?php
                                    $pc = array('Form 1' => lang('Form_1'), 'Form 2' => lang('Form_2'), 'Form 3' => lang('Form_3'), 'Form 4' => lang('Form_4'), 'Form 5' => lang('Form_5'), 'Upper 6' => lang('Upper_6'), 'Lower 6' => lang('Lower_6'));
                                    echo form_dropdown('pc', $pc, (isset($_POST['pc']) ? $_POST['pc'] : $player->pc), 'id="pc" class="form-control select" style="width:100%;" ');
                                    ?>
                                </div>


                                <div class="form-group">
                                    <?= lang('Transfer_/_Transfer_Repeat_/_N/A', 'Repeater_/_Transfer_Repeater_/_N/A') . '<b>*</b>'; ?>
                                    <?php
                                    $tr = array('Repeater' => lang('Repeater'), 'Transfer Repeater' => lang('Transfer_Repeater'),'N/A' => lang('N/A'));
                                    echo form_dropdown('trs', $tr, (isset($_POST['trs']) ? $_POST['trs'] : $player->trs), 'id="trs" required="required" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>


                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?= lang("Address", "Address") ?>
                                <?= form_textarea('address', (isset($_POST['address']) ? $_POST['address'] : $player->address), 'class="form-control" id="address"'); ?>
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

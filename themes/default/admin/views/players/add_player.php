<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Add_Player'); ?></h2>
    </div>
    <div class="box-content">
        <!--        <div class="row">-->
        <div class="col-md-12">
            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
            echo admin_form_open_multipart("players/add", $attrib);
            ?>
            <div class="row">
                <fieldset class="scheduler-border">
                    <legend style="text-align: center" class="scheduler-border"><i
                                class="fa-fw fa fa-user-secret"></i><b>General
                            Information</b></legend>
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?= lang("School", "School") . '<b>*</b>'; ?>
                                <?php
                                foreach ($schools as $school) {
                                    $wh[$school->id] = $school->name;
                                }
                                echo form_dropdown('school_id', $wh, (isset($_POST['school_id']) ? $_POST['school_id'] : ''), 'id="school_id" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>

                            <div class="form-group">
                                <?= lang("Zone", "Zone") . '<b>*</b>'; ?>
                                <?php
                                foreach ($zones as $zone) {
                                    $wh12[$zone->name] = $zone->name;
                                }
                                echo form_dropdown('zone', $wh12, (isset($_POST['zone']) ? $_POST['zone'] : ''), 'id="zone" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>


                            <div class="form-group">
                                <?php echo lang('First_Name', 'First_Name') . '<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', (isset($_POST['first_name']) ? $_POST['first_name'] : ''), 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Last_Name', 'Last_Name') . '<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('last_name', (isset($_POST['last_name']) ? $_POST['last_name'] : ''), 'class="form-control" id="last_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('gender', 'gender') . '<b>*</b>'; ?>
                                <?php
                                $ge[''] = array('Male' => lang('Male'), 'Female' => lang('Female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>


                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', '', 'class="form-control" id="phone"'); ?>
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
                                <?php echo lang('Date_Of_Birth', 'Date_Of_Birth') . '<b>*</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('dob', (isset($_POST['dob']) ? $_POST['dob'] : ''), 'class="form-control input-tip date" id="dob" readonly required="required"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo lang('Birth_Certificate_Pin', 'Birth_Certificate_Pin') . '<b>*</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('bcp', (isset($_POST['bcp']) ? $_POST['bcp'] : ''), 'class="form-control" id="bcp" required="required"'); ?>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                <?= lang("Division", "Division") . '<b>*</b>'; ?>
                                <?php
                                foreach ($categories as $category) {
                                    $gp[$category->id] = $category->name;
                                }
                                echo form_dropdown('division', $gp, (isset($_POST['division']) ? $_POST['division'] : ''), 'id="division" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>
                            <div class="form-group">
                                <?php echo lang('Jersey_Number', 'Jersey_Number'); ?>
                                <div class="controls">
                                    <?php echo form_input('jersey_number', (isset($_POST['jersey_number']) ? $_POST['jersey_number'] : ''), 'class="form-control" id="jersey_number"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('Enrolment_Year_At_Present_School', 'Enrolment_Year_At_Present_School') . '<b>*</b>'; ?>
                                <?php
                                $opt = array("2012" => lang('2012'), "2013" => lang('2013'), "2014" => lang('2014'), "2015" => lang('2015'), "2016" => lang('2016'), "2017" => lang('2017'), "2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                echo form_dropdown('pey', $opt, (isset($_POST['pey']) ? $_POST['pey'] : ''), 'id="pey" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>

                            <div class="form-group">
                                <?= lang("Present_Class", "Present_Class") . '<b>*</b>'; ?>
                                <?php
                                $pc = array('Form 1' => lang('Form_1'), 'Form 2' => lang('Form_2'), 'Form 3' => lang('Form_3'), 'Form 4' => lang('Form_4'), 'Form 5' => lang('Form_5'), 'Upper 6' => lang('Upper_6'), 'Lower 6' => lang('Lower_6'));
                                echo form_dropdown('pc', $pc, (isset($_POST['pc']) ? $_POST['pc'] : ''), 'id="pc" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Last_School_Attended', 'Last_School_Attended') . '<b>*</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('last_attend_school', (isset($_POST['last_attend_school']) ? $_POST['last_attend_school'] : ''), 'class="form-control" required="required" id="last_attend_school"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('SEA_Year', 'SEA_Year') . '<b>*</b>'; ?>
                                <?php
                                $opt = array("2012" => lang('2012'), "2013" => lang('2013'), "2014" => lang('2014'), "2015" => lang('2015'), "2016" => lang('2016'), "2017" => lang('2017'), "2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                echo form_dropdown('sea_year', $opt, (isset($_POST['sea_year']) ? $_POST['sea_year'] : ''), 'id="sea_year" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>

                            <div class="form-group">
                                <?php echo lang('SEA_Number', 'SEA_Number'); ?>
                                <div class="controls">
                                    <?php echo form_input('sea_number', (isset($_POST['sea_number']) ? $_POST['sea_number'] : ''), 'class="form-control" id="sea_number"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= lang('Transfer_/_Transfer_Repeat_/_N/A?', 'Repeater_/_Transfer_Repeater_/_N/A?') . '<b>*</b>'; ?>
                                <?php
                                $tr = array('Repeater' => lang('Repeater'), 'Transfer_Repeater' => lang('Transfer_Repeater'),'N/A' => lang('N/A'));
                                echo form_dropdown('trs', $tr, (isset($_POST['trs']) ? $_POST['trs'] : ''), 'id="trs" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>


                        </div>

                    </div>


                    <div class="col-md-12">
                        <div class="col-md-11 ">
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?= lang("Address", "Address") ?>
                                <?= form_textarea('address', (isset($_POST['address']) ? $_POST['address'] : ''), 'class="form-control" id="address"'); ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="row">
                <fieldset class="scheduler-border">
                    <legend style="text-align: center" class="scheduler-border"><i class="fa-fw fa fa-key"></i><b>Login
                            Information</b>
                    </legend>
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('username', 'username'); ?>
                                <div class="controls">
                                    <input type="text" id="username" name="username" class="form-control"
                                           required="required" pattern=".{4,20}"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('status', 'status'); ?>
                                <?php
                                $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
                            <div class="form-group">
                                <?= lang("Photo", "Photo"); ?>
                                <input type="file" data-browse-label="<?= lang('browse'); ?>" name="avatar"
                                       id="avatar" required="required"
                                       data-show-upload="false" data-show-preview="false" accept="image/*"
                                       class="form-control file"/>
                            </div>

                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div class="clearfix"></div>

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

                            <div class="row">
                                <div class="col-md-12>
                                                <label class=" checkbox
                                " for="notify">
                                <input type="checkbox" name="notify" value="1" id="notify"/>
                                &nbsp;&nbsp;<?= lang('notify_user_by_email') ?>
                                </label>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
            </div>
            </fieldset>
            <div class="col-md-12">
                <p><?php echo form_submit('add_player', lang('Add_Player'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!--        </div>-->

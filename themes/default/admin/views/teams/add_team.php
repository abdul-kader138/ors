<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Add_Team_Manager'); ?></h2>
    </div>
    <div class="box-content">
        <!--        <div class="row">-->
        <div class="col-md-12">
            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
            echo admin_form_open("teams/add", $attrib);
            ?>
            <div class="row">
                <fieldset class="scheduler-border">
                    <legend style="text-align: center" class="scheduler-border"><i class="fa-fw fa fa-user-secret"></i><b>General
                            Information</b></legend>
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('Name', 'Name') . '<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'class="form-control" id="name" required="required" pattern=".{3,10}"'); ?>
                                </div>
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



                        </div>
                        <div class="col-md-5 col-md-offset-1">

                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?= lang("School", "School").'<b>*</b>'; ?>
                                <?php
                                foreach ($schools as $school) {
                                    $wh[$school->id] = $school->name;
                                }
                                echo form_dropdown('school_id', $wh, (isset($_POST['school_id']) ? $_POST['school_id'] : ''), 'id="school_id" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>

                            <div class="form-group">
                                <?= lang("Zone", "Zone").'<b>*</b>'; ?>
                                <?php
                                foreach ($zones as $zone) {
                                    $wh12[$zone->name] = $zone->name;
                                } echo form_dropdown('zone', $wh12, (isset($_POST['zone']) ? $_POST['zone'] : ''), 'id="zone" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>
                            <div class="form-group">
                                <?= lang("Division", "Division").'<b>*</b>'; ?>
                                <?php
                                foreach ($categories as $category) {
                                    $gp[$category->id] = $category->name;
                                }
                                echo form_dropdown('division', $gp, (isset($_POST['division']) ? $_POST['division'] : ''), 'id="division" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>


                        </div>

                    </div>


                    <div class="col-md-12">
                        <div class="col-md-11 ">
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?= lang("Other_Information", "Other_Information") ?>
                                <?= form_textarea('other_information', (isset($_POST['other_information']) ? $_POST['other_information'] : ''), 'class="form-control" id="other_information"'); ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="row">
                <fieldset class="scheduler-border">
                    <legend style="text-align: center" class="scheduler-border"><i class="fa-fw fa fa-key"></i><b>Login Information</b>
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


                            <div class="row">
                                <div class="col-md-12>
                                                <label class="checkbox" for="notify">
                                <input type="checkbox" name="notify" value="1" id="notify"/>
                                &nbsp;&nbsp;<?= lang('notify_user_by_email') ?>
                                </label>
                            </div>
                            <div class="clearfix"></div>
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

                    </div>
            </div>
            <div class="col-md-12">
                <p><?php echo form_submit('add_team_manager', lang('Add_Team_Manager'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!--        </div>-->

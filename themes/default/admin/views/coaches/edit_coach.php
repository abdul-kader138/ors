<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Edit_Coach'); ?></h2>
    </div>
    <div class="box-content">
        <!--        <div class="row">-->
        <div class="col-md-12">
            <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
            echo admin_form_open("coaches/edit/" . $coach->id, $attrib);
            ?>
            <div class="row">
                <fieldset class="scheduler-border">
                    <legend style="text-align: center" class="scheduler-border"><i class="fa-fw fa fa-user-secret"></i><b>General
                            Information</b></legend>
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('First_Name', 'First_Name') . '<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', (isset($_POST['first_name']) ? $_POST['first_name'] : $coach->first_name), 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('Last_Name', 'Last_Name') . '<b> *</b>'; ?>
                                <div class="controls">
                                    <?php echo form_input('last_name', (isset($_POST['last_name']) ? $_POST['last_name'] : $coach->last_name), 'class="form-control" id="last_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?= lang('gender', 'gender').'<b>*</b>'; ?>
                                <?php
                                $ge[''] = array('Male' => lang('Male'), 'Female' => lang('Female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $coach->gender), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>


                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', (isset($_POST['phone']) ? $_POST['phone'] : $user->phone), 'class="form-control" id="phone"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('email', 'email'); ?>
                                <div class="controls">
                                    <?php
                                    $atr=array('name'=>'email', 'id'=>'email','type'=>'email','class'=>'form-control');
                                    echo form_input($atr, (isset($_POST['email']) ? $_POST['email'] : $user->email)); ?>
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
                                echo form_dropdown('school_id', $wh, (isset($_POST['school_id']) ? $_POST['school_id'] : $coach->school_id ), 'id="school_id" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>

                            <div class="form-group">
                                <?= lang("Zone", "Zone").'<b>*</b>'; ?>
                                <?php
                                $zones = array('East' => lang('East'), 'North' => lang('North'), 'South' => lang('South'), 'Central' => lang('Central'), 'Tobago' => lang('Tobago'));
                                echo form_dropdown('zone', $zones, (isset($_POST['zone']) ? $_POST['zone'] : $coach->zone), 'id="zone" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>
                            <div class="form-group">
                                <?= lang("Division", "Division").'<b>*</b>'; ?>
                                <?php
                                foreach ($categories as $category) {
                                    $gp[$category->id] = $category->name;
                                }
                                echo form_dropdown('division', $gp, (isset($_POST['division']) ? $_POST['division'] : $coach->division), 'id="division" class="form-control select" style="width:100%;" ');
                                ?>
                            </div>

                            <div class="form-group">
                                <?= lang('SEA_Year', 'SEA_Year').'<b>*</b>'; ?>
                                <?php
                                $opt = array("2012" => lang('2012'), "2013" => lang('2013'), "2014" => lang('2014'), "2015" => lang('2015'), "2016" => lang('2016'), "2017" => lang('2017'), "2018" => lang('2018'), "2019" => lang('2019'), "2020" => lang('2020'), "2021" => lang('2021'), "2022" => lang('2022'), "2023" => lang('2023'), "2024" => lang('2024'), "2025" => lang('2025'), "2026" => lang('2026'), "2027" => lang('2027'), "2028" => lang('2028'));
                                echo form_dropdown('sea_year', $opt, (isset($_POST['sea_year']) ? $_POST['sea_year'] : $coach->sea_year), 'id="sea_year" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-12">
                        <div class="col-md-11 ">
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?= lang("Address", "Address") ?>
                                <?= form_textarea('address', (isset($_POST['address']) ? $_POST['address'] : $coach->address), 'class="form-control" id="address"'); ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12">
                <p><?php echo form_submit('edit_coach', lang('Edit_Coach'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <!--        </div>-->
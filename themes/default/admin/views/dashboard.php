<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
function row_status($x)
{
    if ($x == null) {
        return '';
    } elseif ($x == 'pending') {
        return '<div class="text-center"><span class="label label-warning">' . lang($x) . '</span></div>';
    } elseif ($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received') {
        return '<div class="text-center"><span class="label label-success">' . lang($x) . '</span></div>';
    } elseif ($x == 'partial' || $x == 'transferring') {
        return '<div class="text-center"><span class="label label-info">' . lang($x) . '</span></div>';
    } elseif ($x == 'due') {
        return '<div class="text-center"><span class="label label-danger">' . lang($x) . '</span></div>';
    } else {
        return '<div class="text-center"><span class="label label-default">' . lang($x) . '</span></div>';
    }
}

?>
<?php if (($Owner || $Admin) && $chatData) {
    foreach ($chatData as $month_sale) {
        $months[] = date('M-Y', strtotime($month_sale->month));
        $msales[] = $month_sale->sales;
        $mtax1[] = $month_sale->tax1;
        $mtax2[] = $month_sale->tax2;
        $mpurchases[] = $month_sale->purchases;
        $mtax3[] = $month_sale->ptax;
    }
    ?>
    <div class="row" style="margin-bottom: 5px;">
        <div class="col-sm-12">
            <div class="col-sm-3">
                <div class="small-box padding1010" style="background-color: #428BCA">
                    <h2 class="bold" style="color: white"><?= lang('Total_Users') ?></h2>
                    <i class="icon fa fa-user"></i>

                    <h1 class="bold">&nbsp;&nbsp;</h1>

                    <p class="bold"><h1 style="text-align: center;color: white;"><?= $total_users->total ?></h1></p>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="small-box padding1010 bdarkGreen">
                    <h2 class="bold" style="color: white"><?= lang('Total_Players') ?></h2>
                    <i class="icon fa fa-heart"></i>

                    <h1 class="bold">&nbsp;&nbsp;</h1>

                    <p class="bold"><h1 style="text-align: center;color: white;">0</h1></p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="small-box padding1010 bpurple">
                    <h2 class="bold" style="color: white"><?= lang('Total_Coach') ?></h2>
                    <i class="icon fa fa-star"></i>

                    <h1 class="bold">&nbsp;&nbsp;</h1>

                    <p class="bold"><h1 style="text-align: center;color: white;">0</h1></p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="small-box padding1010 bred">
                    <h2 class="bold" style="color: white"><?= lang('Total_Team') ?></h2>
                    <i class="icon fa fa-plus-circle"></i>

                    <h1 class="bold">&nbsp;&nbsp;</h1>

                    <p class="bold"><h1 style="text-align: center;color: white;">0</h1></p>
                </div>

            </div>
        </div>

    </div>
<?php } ?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-tasks"></i> <?= lang('latest_five') ?></h2>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-12">

                        <ul id="dbTab" class="nav nav-tabs">
                            <?php if ($Owner || $Admin || $GP['users-index']) { ?>
                                <li class=""><a href="#sales"><?= lang('Users') ?></a></li>
                            <?php }
                            if ($Owner || $Admin || $GP['coach-index']) { ?>
                                <li class=""><a href="#coaches"><?= lang('Coaches') ?></a></li>
                            <?php }
                            if ($Owner || $Admin || $GP['player-index']) { ?>
                                <li class=""><a href="#players"><?= lang('Players') ?></a></li>
                            <?php }
                            if ($Owner || $Admin || $GP['teams-index']) { ?>
                                <li class=""><a href="#teams"><?= lang('Teams') ?></a></li>
                            <?php }
                         ?>
                        </ul>

                        <div class="tab-content">
                            <?php if ($Owner || $Admin || $GP['users-index']) { ?>

                                <div id="sales" class="tab-pane fade in">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="sales-tbl" cellpadding="0" cellspacing="0" border="0"
                                                       class="table table-bordered table-hover table-striped"
                                                       style="margin-bottom: 0;">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("Full_Name"); ?></th>
                                                        <th><?= $this->lang->line("User_Name"); ?></th>
                                                        <th><?= $this->lang->line("Gender"); ?></th>
                                                        <th><?= $this->lang->line("Email"); ?></th>
                                                        <th><?= $this->lang->line("Phone"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($users)) {
                                                        $r = 1;
                                                        foreach ($users as $user) {
                                                            echo '<tr><td>' . $r. '</td>
                                                            <td>' . $user->first_name .' '.$user->last_name. '</td>
                                                            <td>' . $user->username . '</td>
                                                            <td>' . $user->gender . '</td>
                                                            <td>' . $user->email . '</td>
                                                            <td>' . $user->phone . '</td>
                                                        </tr>';
                                                            $r++;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="6"
                                                                class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php }
                            if ($Owner || $Admin || $GP['coach-index']) { ?>

                                <div id="coaches" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="quotes-tbl" cellpadding="0" cellspacing="0" border="0"
                                                       class="table table-bordered table-hover table-striped"
                                                       style="margin-bottom: 0;">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("Full_Name"); ?></th>
                                                        <th><?= $this->lang->line("User_Name"); ?></th>
                                                        <th><?= $this->lang->line("Gender"); ?></th>
                                                        <th><?= $this->lang->line("Email"); ?></th>
                                                        <th><?= $this->lang->line("Phone"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($coachs)) {
                                                        $r = 1;
                                                        foreach ($coaches as $coach) {
                                                            echo '<tr><td>' . $r. '</td>
                                                            <td>' . $coach->first_name .' '.$coach->last_name. '</td>
                                                            <td>' . $coach->username . '</td>
                                                            <td>' . $coach->gender . '</td>
                                                            <td>' . $coach->email . '</td>
                                                            <td>' . $coach->phone . '</td>
                                                    </tr>';
                                                            $r++;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="6"
                                                                class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php }
                            if ($Owner || $Admin || $GP['purchases-index']) { ?>

                                <div id="players" class="tab-pane fade in">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="purchases-tbl" cellpadding="0" cellspacing="0" border="0"
                                                       class="table table-bordered table-hover table-striped"
                                                       style="margin-bottom: 0;">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("Full_Name"); ?></th>
                                                        <th><?= $this->lang->line("User_Name"); ?></th>
                                                        <th><?= $this->lang->line("Gender"); ?></th>
                                                        <th><?= $this->lang->line("Email"); ?></th>
                                                        <th><?= $this->lang->line("Phone"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($players)) {
                                                        $r = 1;
                                                        foreach ($players as $player) {
                                                            echo '<tr><td>' . $r. '</td>
                                                            <td>' . $player->first_name .' '.$player->last_name. '</td>
                                                            <td>' . $player->username . '</td>
                                                            <td>' . $player->gender . '</td>
                                                            <td>' . $player->email . '</td>
                                                            <td>' . $player->phone . '</td>
                                                </tr>';
                                                            $r++;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="6"
                                                                class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php }
                            if ($Owner || $Admin || $GP['transfers-index']) { ?>

                                <div id="teams" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="transfers-tbl" cellpadding="0" cellspacing="0" border="0"
                                                       class="table table-bordered table-hover table-striped"
                                                       style="margin-bottom: 0;">
                                                    <thead>
                                                    <tr>
                                                        <th style="width:30px !important;">#</th>
                                                        <th><?= $this->lang->line("Full_Name"); ?></th>
                                                        <th><?= $this->lang->line("User_Name"); ?></th>
                                                        <th><?= $this->lang->line("Gender"); ?></th>
                                                        <th><?= $this->lang->line("Email"); ?></th>
                                                        <th><?= $this->lang->line("Phone"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($teams)) {
                                                        $r = 1;
                                                        foreach ($teams as $team) {
                                                            echo '<tr><td>' . $r. '</td>
                                                            <td>' . $team->first_name .' '.$team->last_name. '</td>
                                                            <td>' . $team->username . '</td>
                                                            <td>' . $team->gender . '</td>
                                                            <td>' . $team->email . '</td>
                                                            <td>' . $team->phone . '</td>
                                            </tr>';
                                                            $r++;
                                                        }
                                                    } else { ?>
                                                        <tr>
                                                            <td colspan="6"
                                                                class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php }?>
                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>

</div>


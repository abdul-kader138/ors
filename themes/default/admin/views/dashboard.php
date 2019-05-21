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

$m_central = 0;
$m_east = 0;
$m_north = 0;
$m_south = 0;
$m_tobago = 0;

foreach ($zone_male_history as $zones) {
    if ($zones->zone == 'Central') $m_central = (int)$zones->id;
    if ($zones->zone == 'East') $m_east = (int)$zones->id;
    if ($zones->zone == 'North') $m_north = (int)$zones->id;
    if ($zones->zone == 'South') $m_south = (int)$zones->id;
    if ($zones->zone == 'Tobago') $m_tobago = (int)$zones->id;
}


$f_central = 0;
$f_east = 0;
$f_north = 0;
$f_south = 0;
$f_tobago = 0;

foreach ($zone_female_history as $zones_m) {
    if ($zones_m->zone == 'Central') $f_central = (int)$zones_m->id;
    if ($zones_m->zone == 'East') $f_east = (int)$zones_m->id;
    if ($zones_m->zone == 'North') $f_north = (int)$zones_m->id;
    if ($zones_m->zone == 'South') $f_south = (int)$zones_m->id;
    if ($zones_m->zone == 'Tobago') $f_tobago = (int)$zones_m->id;
}
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">
    $(function () {
        Highcharts.chart('bschart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Registration By Zone'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Central',
                    'East',
                    'North',
                    'South',
                    'Tobago'

                ],
                crosshair: false
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Boys',
                data: [<?php echo $m_central.",".$m_east.",".$m_north.",".$m_south.",".$m_tobago; ?>]

            }, {
                name: 'Girls',
                data: [<?php echo $f_central.",".$f_east.",".$f_north.",".$f_south.",".$f_tobago; ?>]

            }]
        });
    });

</script>

<div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-12">
        <div class="col-sm-3">
            <div class="small-box padding1010" style="background-color: #428BCA">
                <h2 class="bold" style="color: white"><?= lang('Total_Users') ?></h2>
                <i class="icon fa fa-user"></i>

                <h1 class="bold">&nbsp;&nbsp;</h1>

                <p class="bold">
                <h1 style="text-align: center;color: white;"><?= $total_users->total ?></h1></p>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="small-box padding1010 bdarkGreen">
                <h2 class="bold" style="color: white"><?= lang('Total_Players') ?></h2>
                <i class="icon fa fa-heart"></i>

                <h1 class="bold">&nbsp;&nbsp;</h1>

                <p class="bold">
                <h1 style="text-align: center;color: white;"><?= $total_players->total ? $total_players->total : 0 ?></h1></p>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box padding1010 bpurple">
                <h2 class="bold" style="color: white"><?= lang('Total_Coaches') ?></h2>
                <i class="icon fa fa-star"></i>

                <h1 class="bold">&nbsp;&nbsp;</h1>

                <p class="bold">
                <h1 style="text-align: center;color: white;"><?= $total_coaches->total ? $total_coaches->total : 0 ?></h1></p>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box padding1010 " style="background-color: #ffc582">
                <h2 class="bold" style="color: white"><?= lang('Total_Schools') ?></h2>
                <i class="icon fa fa-plus-circle"></i>

                <h1 class="bold">&nbsp;&nbsp;</h1>

                <p class="bold">
                <h1 style="text-align: center;color: white;"><?= $total_schools->total ? $total_schools->total : 0 ?></h1></p>
            </div>

        </div>
    </div>

</div>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i
                            class="fa-fw fa fa-line-chart"></i><?= lang('Registration By Zone'); ?>
                </h2>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-12">
                        <div id="bschart" style="width:100%; height:450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                            if ($Owner || $Admin || $GP['coaches-index']) { ?>
                                <li class=""><a href="#coaches"><?= lang('Coaches') ?></a></li>
                            <?php }
                            if ($Owner || $Admin || $GP['players-index']) { ?>
                                <li class=""><a href="#players"><?= lang('Players') ?></a></li>
                            <?php }
                            if ($Owner || $Admin || $GP['teams-index']) { ?>
                                <li class=""><a href="#teams"><?= lang('Schools') ?></a></li>
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
                                                            echo '<tr><td>' . $r . '</td>
                                                            <td>' . $user->first_name . ' ' . $user->last_name . '</td>
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
                            if ($Owner || $Admin || $GP['coaches-index']) { ?>

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
                                                        <th><?= $this->lang->line("School"); ?></th>
                                                        <th><?= $this->lang->line("Zone"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($coaches)) {
                                                        $r = 1;
                                                        foreach ($coaches as $coach) {
                                                            echo '<tr><td>' . $r . '</td>
                                                            <td>' . $coach->first_name . ' ' . $coach->last_name . '</td>
                                                            <td>' . $coach->username . '</td>
                                                            <td>' . $coach->gender . '</td>
                                                            <td>' . $coach->wname . '</td>
                                                            <td>' . $coach->zone . '</td>
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
                            if ($Owner || $Admin || $GP['players-index']) { ?>

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
                                                        <th><?= $this->lang->line("Gender"); ?></th>
                                                        <th><?= $this->lang->line("Date_Of_Birth"); ?></th>
                                                        <th><?= $this->lang->line("Birth_Certificate_Pin"); ?></th>
                                                        <th><?= $this->lang->line("School"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($players)) {
                                                        $r = 1;
                                                        foreach ($players as $player) {
                                                            echo '<tr><td>' . $r . '</td>
                                                            <td>' . $player->first_name . ' ' . $player->last_name . '</td>
                                                            <td>' . $player->gender . '</td>
                                                            <td>' . $this->sma->hrsd($player->dob) . '</td>
                                                            <td>' . $player->bcp . '</td>
                                                            <td>' . $player->wname . '</td>
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
                            if ($Owner || $Admin || $GP['teams-index']) { ?>

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
                                                        <th><?= $this->lang->line("Name"); ?></th>
                                                        <th><?= $this->lang->line("Email"); ?></th>
                                                        <th><?= $this->lang->line("Phone"); ?></th>
                                                        <th><?= $this->lang->line("Principal"); ?></th>
                                                        <th><?= $this->lang->line("Home_Ground"); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($schools)) {
                                                        $r = 1;
                                                        foreach ($schools as $school) {
                                                            echo '<tr><td>' . $r . '</td>
                                                            <td>' . $school->name . '</td>
                                                            <td>' . $school->email . '</td>
                                                            <td>' . $school->phone . '</td>
                                                            <td>' . $school->principal . '</td>
                                                            <td>' . $school->home_ground . '</td>
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

                            <?php } ?>
                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>

</div>


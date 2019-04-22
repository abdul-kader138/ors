<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - <?= $Settings->site_name ?></title>
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>
    <link href="<?= $assets ?>js/izitoast/css/iziToast.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->
    <noscript>
        <style type="text/css">#loading {
                display: none;
            }</style>
    </noscript>
    <?php if ($Settings->user_rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.pull-right, .pull-left').addClass('flip');
            });
        </script>
    <?php } ?>
    <script type="text/javascript">
        $(window).load(function () {
            $("#loading").fadeOut("slow");
        });
    </script>
</head>

<body>
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<div id="loading"></div>
<div id="app_wrapper">
    <header id="header" class="navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= admin_url() ?>"><span class="logo"><?= $Settings->site_name ?></span></a>

            <div class="btn-group visible-xs pull-right btn-visible-sm">
                <button class="navbar-toggle btn" type="button" data-toggle="collapse" data-target="#sidebar_menu">
                    <span class="fa fa-bars"></span>
                </button>
                <?php if (SHOP) { ?>
                    <a href="<?= site_url('/') ?>" class="btn">
                        <span class="fa fa-shopping-cart"></span>
                    </a>
                <?php } ?>
                <a href="<?= admin_url('calendar') ?>" class="btn">
                    <span class="fa fa-calendar"></span>
                </a>
                <a href="<?= admin_url('users/profile/' . $this->session->userdata('user_id')); ?>" class="btn">
                    <span class="fa fa-user"></span>
                </a>
                <a href="<?= admin_url('logout'); ?>" class="btn">
                    <span class="fa fa-sign-out"></span>
                </a>
            </div>
            <div class="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
                            <img alt=""
                                 src="<?= $this->session->userdata('avatar') ? base_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : base_url('assets/images/' . $this->session->userdata('gender') . '.png'); ?>"
                                 class="mini_avatar img-rounded">

                            <div class="user">
                                <span><?= lang('welcome') ?> <?= strtoupper($this->session->userdata('username')); ?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="<?= admin_url('users/profile/' . $this->session->userdata('user_id')); ?>">
                                    <i class="fa fa-user"></i> <?= lang('profile'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= admin_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i
                                            class="fa fa-key"></i> <?= lang('change_password'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= admin_url('logout'); ?>">
                                    <i class="fa fa-sign-out"></i> <?= lang('logout'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown hidden-xs"><a class="btn tip" title="<?= lang('dashboard') ?>"
                                                      data-placement="bottom" href="<?= admin_url('welcome') ?>"><i
                                    class="fa fa-dashboard"></i></a></li>
                    <?php if ($Owner) { ?>
                        <li class="dropdown hidden-sm">
                            <a class="btn tip" title="<?= lang('settings') ?>" data-placement="bottom"
                               href="<?= admin_url('system_settings') ?>">
                                <i class="fa fa-cogs"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="dropdown hidden-xs">
                        <a class="btn tip" title="<?= lang('calculator') ?>" data-placement="bottom" href="#"
                           data-toggle="dropdown">
                            <i class="fa fa-calculator"></i>
                        </a>
                        <ul class="dropdown-menu pull-right calc">
                            <li class="dropdown-content">
                                <span id="inlineCalc"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown hidden-sm">
                        <a class="btn tip" title="<?= lang('styles') ?>" data-placement="bottom" data-toggle="dropdown"
                           href="#">
                            <i class="fa fa-css3"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="bwhite noPadding">
                                <a href="#" id="fixed" class="">
                                    <i class="fa fa-angle-double-left"></i>
                                    <span id="fixedText">Fixed</span>
                                </a>
                                <a href="#" id="cssLight" class="grey">
                                    <i class="fa fa-stop"></i> Grey
                                </a>
                                <a href="#" id="cssBlue" class="blue">
                                    <i class="fa fa-stop"></i> Blue
                                </a>
                                <a href="#" id="cssBlack" class="black">
                                    <i class="fa fa-stop"></i> Black
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container" id="container">
        <div class="row" id="main-con">
            <table class="lt">
                <tr>
                    <td class="sidebar-con">
                        <div id="sidebar-left">
                            <div class="sidebar-nav nav-collapse collapse navbar-collapse" id="sidebar_menu">
                                <ul class="nav main-menu">
                                    <li class="mm_welcome">
                                        <a href="<?= admin_url() ?>">
                                            <i class="fa fa-dashboard"></i>
                                            <span class="text"> <?= lang('dashboard'); ?></span>
                                        </a>
                                    </li>

                                    <?php
                                    if ($Owner || $Admin) { ?>
                                        <li class="mm_auth mm_customers mm_suppliers mm_billers">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-users"></i>
                                                <span class="text"> <?= lang('people'); ?> </span>
                                                <span class="chevron closed"></span>
                                            </a>
                                            <ul>
                                                <li id="auth_users">
                                                    <a class="submenu" href="<?= admin_url('users'); ?>">
                                                        <i class="fa fa-users"></i><span
                                                                class="text"> <?= lang('list_users'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="auth_create_user">
                                                    <a class="submenu"
                                                       href="<?= admin_url('users/create_user'); ?>">
                                                        <i class="fa fa-user-plus"></i><span
                                                                class="text"> <?= lang('new_user'); ?></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="mm_players">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-user-md"></i>
                                                <span class="text"> <?= lang('Players'); ?> </span>
                                                <span class="chevron closed"></span>
                                            </a>
                                            <ul>
                                                <li id="players_index">
                                                    <a class="submenu"
                                                       href="<?= admin_url('players'); ?>">
                                                        <i class="fa fa-users"></i><span
                                                                class="text"> <?= lang('List_Players'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="players_add">
                                                    <a class="submenu"
                                                       href="<?= admin_url('players/add'); ?>">
                                                        <i class="fa fa-user-plus"></i><span
                                                                class="text"> <?= lang('Add_Player'); ?></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="mm_notifications">
                                            <a class="submenu" href="<?= admin_url('notifications'); ?>">
                                                <i class="fa fa-info-circle"></i><span
                                                        class="text"> <?= lang('notifications'); ?></span>
                                            </a>
                                        </li>
                                        <li class="mm_calendar">
                                            <a class="submenu" href="<?= admin_url('calendar'); ?>">
                                                <i class="fa fa-calendar"></i><span
                                                        class="text"> <?= lang('Event_Calender'); ?></span>
                                            </a>
                                        </li>
                                        <?php if ($Owner) { ?>
                                            <li class="mm_system_settings <?= strtolower($this->router->fetch_method()) == 'sales' ? '' : 'mm_pos' ?>">
                                                <a class="dropmenu" href="#">
                                                    <i class="fa fa-cog"></i><span
                                                            class="text"> <?= lang('settings'); ?> </span>
                                                    <span class="chevron closed"></span>
                                                </a>
                                                <ul>
                                                    <li id="system_settings_index">
                                                        <a href="<?= admin_url('system_settings') ?>">
                                                            <i class="fa fa-cogs"></i><span
                                                                    class="text"> <?= lang('system_settings'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_change_logo">
                                                        <a href="<?= admin_url('system_settings/change_logo') ?>"
                                                           data-toggle="modal" data-target="#myModal">
                                                            <i class="fa fa-upload"></i><span
                                                                    class="text"> <?= lang('change_logo'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_categories">
                                                        <a href="<?= admin_url('system_settings/categories') ?>">
                                                            <i class="fa fa-folder-open"></i><span
                                                                    class="text"> <?= lang('categories'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_warehouses">
                                                        <a href="<?= admin_url('system_settings/warehouses') ?>">
                                                            <i class="fa fa-building-o"></i><span
                                                                    class="text"> <?= lang('School'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_email_templates">
                                                        <a href="<?= admin_url('system_settings/email_templates') ?>">
                                                            <i class="fa fa-envelope"></i><span
                                                                    class="text"> <?= lang('email_templates'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_user_groups">
                                                        <a href="<?= admin_url('system_settings/user_groups') ?>">
                                                            <i class="fa fa-key"></i><span
                                                                    class="text"> <?= lang('group_permissions'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_backups">
                                                        <a href="<?= admin_url('system_settings/backups') ?>">
                                                            <i class="fa fa-database"></i><span
                                                                    class="text"> <?= lang('backups'); ?></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        <?php } ?>

                                        <li class="mm_reports">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-bar-chart-o"></i>
                                                <span class="text"> <?= lang('reports'); ?> </span>
                                                <span class="chevron closed"></span>
                                            </a>
<!--                                            <ul>-->
<!--                                                <li id="reports_index">-->
<!--                                                    <a href="--><?//= admin_url('reports') ?><!--">-->
<!--                                                        <i class="fa fa-bars"></i><span-->
<!--                                                                class="text"> --><?//= lang('overview_chart'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_warehouse_stock">-->
<!--                                                    <a href="--><?//= admin_url('reports/warehouse_stock') ?><!--">-->
<!--                                                        <i class="fa fa-building"></i><span-->
<!--                                                                class="text"> --><?//= lang('warehouse_stock'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!---->
<!--                                                <li id="reports_order_transaction_report">-->
<!--                                                    <a href="--><?//= admin_url('reports/order_transaction_report') ?><!--">-->
<!--                                                        <i class="fa fa-history"></i><span-->
<!--                                                                class="text"> --><?//= lang('Order_Details_Report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_order_summary_report">-->
<!--                                                    <a href="--><?//= admin_url('reports/order_summary_report') ?><!--">-->
<!--                                                        <i class="fa fa-history"></i><span-->
<!--                                                                class="text"> --><?//= lang('Order_Summary_Report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_products">-->
<!--                                                    <a href="--><?//= admin_url('reports/products') ?><!--">-->
<!--                                                        <i class="fa fa-barcode"></i><span-->
<!--                                                                class="text"> --><?//= lang('products_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_categories">-->
<!--                                                    <a href="--><?//= admin_url('reports/categories') ?><!--">-->
<!--                                                        <i class="fa fa-folder-open"></i><span-->
<!--                                                                class="text"> --><?//= lang('categories_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_daily_sales">-->
<!--                                                    <a href="--><?//= admin_url('reports/daily_sales') ?><!--">-->
<!--                                                        <i class="fa fa-calendar"></i><span-->
<!--                                                                class="text"> --><?//= lang('Daily_Order_Status'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_monthly_sales">-->
<!--                                                    <a href="--><?//= admin_url('reports/monthly_sales') ?><!--">-->
<!--                                                        <i class="fa fa-calendar"></i><span-->
<!--                                                                class="text"> --><?//= lang('Monthly_Order_Status'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_sales">-->
<!--                                                    <a href="--><?//= admin_url('reports/sales') ?><!--">-->
<!--                                                        <i class="fa fa-heart"></i><span-->
<!--                                                                class="text"> --><?//= lang('sales_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_payments">-->
<!--                                                    <a href="--><?//= admin_url('reports/payments') ?><!--">-->
<!--                                                        <i class="fa fa-money"></i><span-->
<!--                                                                class="text"> --><?//= lang('payments_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_tax">-->
<!--                                                    <a href="--><?//= admin_url('reports/tax') ?><!--">-->
<!--                                                        <i class="fa fa-area-chart"></i><span-->
<!--                                                                class="text"> --><?//= lang('tax_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_profit_loss">-->
<!--                                                    <a href="--><?//= admin_url('reports/profit_loss') ?><!--">-->
<!--                                                        <i class="fa fa-money"></i><span-->
<!--                                                                class="text"> --><?//= lang('profit_and_loss'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_daily_purchases">-->
<!--                                                    <a href="--><?//= admin_url('reports/daily_purchases') ?><!--">-->
<!--                                                        <i class="fa fa-calendar"></i><span-->
<!--                                                                class="text"> --><?//= lang('daily_purchases'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_monthly_purchases">-->
<!--                                                    <a href="--><?//= admin_url('reports/monthly_purchases') ?><!--">-->
<!--                                                        <i class="fa fa-calendar"></i><span-->
<!--                                                                class="text"> --><?//= lang('monthly_purchases'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_purchases">-->
<!--                                                    <a href="--><?//= admin_url('reports/purchases') ?><!--">-->
<!--                                                        <i class="fa fa-star"></i><span-->
<!--                                                                class="text"> --><?//= lang('purchases_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_expenses">-->
<!--                                                    <a href="--><?//= admin_url('reports/expenses') ?><!--">-->
<!--                                                        <i class="fa fa-star"></i><span-->
<!--                                                                class="text"> --><?//= lang('expenses_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_customer_report">-->
<!--                                                    <a href="--><?//= admin_url('reports/customers') ?><!--">-->
<!--                                                        <i class="fa fa-users"></i><span-->
<!--                                                                class="text"> --><?//= lang('customers_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_supplier_report">-->
<!--                                                    <a href="--><?//= admin_url('reports/suppliers') ?><!--">-->
<!--                                                        <i class="fa fa-users"></i><span-->
<!--                                                                class="text"> --><?//= lang('suppliers_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                                <li id="reports_staff_report">-->
<!--                                                    <a href="--><?//= admin_url('reports/users') ?><!--">-->
<!--                                                        <i class="fa fa-users"></i><span-->
<!--                                                                class="text"> --><?//= lang('staff_report'); ?><!--</span>-->
<!--                                                    </a>-->
<!--                                                </li>-->
<!--                                            </ul>-->
                                        </li>
                                        <?php
                                    } else { // not owner and not admin ?>
                                        <?php if ($GP['players-index'] || $GP['players-add']) { ?>
                                            <li class="mm_players">
                                                <a class="dropmenu" href="#">
                                                    <i class="fa fa-user-md"></i>
                                                    <span class="text"> <?= lang('Players'); ?> </span>
                                                    <span class="chevron closed"></span>
                                                </a>
                                                <ul>
                                                    <?php if ($GP['players-index']) { ?>
                                                        <li id="players_index">
                                                            <a class="submenu"
                                                               href="<?= admin_url('players'); ?>">
                                                                <i class="fa fa-users"></i><span
                                                                        class="text"> <?= lang('List_Players'); ?></span>
                                                            </a>
                                                        </li>

                                                    <?php } ?>

                                                    <?php if ($GP['players-add']) { ?>
                                                        <li id="players_add">
                                                            <a class="submenu"
                                                               href="<?= admin_url('players/add'); ?>">
                                                                <i class="fa fa-user-plus"></i><span
                                                                        class="text"> <?= lang('Add_Player'); ?></span>
                                                            </a>
                                                        </li>

                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>

                                            <li class="mm_calendar">
                                                <a href="<?= admin_url('calendar') ?>" class="btn-block link">
                                                    <i class="fa fa-calendar"></i> <?= lang('Event_Calender') ?>
                                                </a>
                                            </li>
                                        <?php if ($GP['reports-quantity_alerts'] || $GP['reports-expiry_alerts'] || $GP['reports-products'] || $GP['reports-monthly_sales'] || $GP['reports-sales'] || $GP['reports-payments'] || $GP['reports-purchases'] || $GP['reports-customers'] || $GP['reports-suppliers'] || $GP['reports-expenses'] || $GP['reports-order_transaction_report']) { ?>
                                            <li class="mm_reports">
                                                <a class="dropmenu" href="#">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                    <span class="text"> <?= lang('reports'); ?> </span>
                                                    <span class="chevron closed"></span>
                                                </a>
                                                <ul>
                                                    <?php if ($GP['reports-order_transaction_report']) { ?>
                                                        <li id="reports_order_transaction_report">
                                                            <a href="<?= admin_url('reports/order_transaction_report') ?>">
                                                                <i class="fa fa-calendar-o"></i><span
                                                                        class="text"> <?= lang('Order_Details_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($GP['reports-order_summary_report']) { ?>
                                                        <li id="reports_order_summary_report">
                                                            <a href="<?= admin_url('reports/order_summary_report') ?>">
                                                                <i class="fa fa-calendar-o"></i><span
                                                                        class="text"> <?= lang('Order_Summary_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if ($GP['reports-quantity_alerts']) { ?>
                                                        <li id="reports_quantity_alerts">
                                                            <a href="<?= admin_url('reports/quantity_alerts') ?>">
                                                                <i class="fa fa-bar-chart-o"></i><span
                                                                        class="text"> <?= lang('product_quantity_alerts'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-expiry_alerts']) { ?>
                                                        <?php if ($Settings->product_expiry) { ?>
                                                            <li id="reports_expiry_alerts">
                                                                <a href="<?= admin_url('reports/expiry_alerts') ?>">
                                                                    <i class="fa fa-bar-chart-o"></i><span
                                                                            class="text"> <?= lang('product_expiry_alerts'); ?></span>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    <?php }
                                                    if ($GP['reports-products']) { ?>
                                                        <li id="reports_products">
                                                            <a href="<?= admin_url('reports/products') ?>">
                                                                <i class="fa fa-filter"></i><span
                                                                        class="text"> <?= lang('products_report'); ?></span>
                                                            </a>
                                                        </li>
                                                        <li id="reports_adjustments">
                                                            <a href="<?= admin_url('reports/adjustments') ?>">
                                                                <i class="fa fa-barcode"></i><span
                                                                        class="text"> <?= lang('adjustments_report'); ?></span>
                                                            </a>
                                                        </li>
                                                        <li id="reports_categories">
                                                            <a href="<?= admin_url('reports/categories') ?>">
                                                                <i class="fa fa-folder-open"></i><span
                                                                        class="text"> <?= lang('categories_report'); ?></span>
                                                            </a>
                                                        </li>
                                                        <li id="reports_brands">
                                                            <a href="<?= admin_url('reports/brands') ?>">
                                                                <i class="fa fa-cubes"></i><span
                                                                        class="text"> <?= lang('brands_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-daily_sales']) { ?>
                                                        <li id="reports_daily_sales">
                                                            <a href="<?= admin_url('reports/daily_sales') ?>">
                                                                <i class="fa fa-calendar-o"></i><span
                                                                        class="text"> <?= lang('Daily_Order_Status'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-monthly_sales']) { ?>
                                                        <li id="reports_monthly_sales">
                                                            <a href="<?= admin_url('reports/monthly_sales') ?>">
                                                                <i class="fa fa-calendar-o"></i><span
                                                                        class="text"> <?= lang('Monthly_Order_Status'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-sales']) { ?>
                                                        <li id="reports_sales">
                                                            <a href="<?= admin_url('reports/sales') ?>">
                                                                <i class="fa fa-heart"></i><span
                                                                        class="text"> <?= lang('sales_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-payments']) { ?>
                                                        <li id="reports_payments">
                                                            <a href="<?= admin_url('reports/payments') ?>">
                                                                <i class="fa fa-money"></i><span
                                                                        class="text"> <?= lang('payments_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-tax']) { ?>
                                                        <li id="reports_tax">
                                                            <a href="<?= admin_url('reports/tax') ?>">
                                                                <i class="fa fa-area-chart"></i><span
                                                                        class="text"> <?= lang('tax_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-daily_purchases']) { ?>
                                                        <li id="reports_daily_purchases">
                                                            <a href="<?= admin_url('reports/daily_purchases') ?>">
                                                                <i class="fa fa-calendar-o"></i><span
                                                                        class="text"> <?= lang('daily_purchases'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-monthly_purchases']) { ?>
                                                        <li id="reports_monthly_purchases">
                                                            <a href="<?= admin_url('reports/monthly_purchases') ?>">
                                                                <i class="fa fa-calendar-o"></i><span
                                                                        class="text"> <?= lang('monthly_purchases'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-purchases']) { ?>
                                                        <li id="reports_purchases">
                                                            <a href="<?= admin_url('reports/purchases') ?>">
                                                                <i class="fa fa-star"></i><span
                                                                        class="text"> <?= lang('purchases_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-expenses']) { ?>
                                                        <li id="reports_expenses">
                                                            <a href="<?= admin_url('reports/expenses') ?>">
                                                                <i class="fa fa-star"></i><span
                                                                        class="text"> <?= lang('expenses_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-customers']) { ?>
                                                        <li id="reports_customer_report">
                                                            <a href="<?= admin_url('reports/customers') ?>">
                                                                <i class="fa fa-users"></i><span
                                                                        class="text"> <?= lang('customers_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php }
                                                    if ($GP['reports-suppliers']) { ?>
                                                        <li id="reports_supplier_report">
                                                            <a href="<?= admin_url('reports/suppliers') ?>">
                                                                <i class="fa fa-users"></i><span
                                                                        class="text"> <?= lang('suppliers_report'); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>

                                    <?php } ?>
                                </ul>
                            </div>
                            <a href="#" id="main-menu-act" class="full visible-md visible-lg">
                                <i class="fa fa-angle-double-left"></i>
                            </a>
                        </div>
                    </td>
                    <td class="content-con">
                        <div id="content">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <ul class="breadcrumb">
                                        <?php
                                        foreach ($bc as $b) {
                                            if ($b['link'] === '#') {
                                                echo '<li class="active">' . $b['page'] . '</li>';
                                            } else {
                                                echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
                                            }
                                        }
                                        ?>
                                        <li class="right_log hidden-xs">
                                            <?= lang('your_ip') . ' ' . $ip_address . " <span class='hidden-sm'>( " . lang('last_login_at') . ": " . date($dateFormats['php_ldate'], $this->session->userdata('old_last_login')) . " " . ($this->session->userdata('last_ip') != $ip_address ? lang('ip:') . ' ' . $this->session->userdata('last_ip') : '') . " )</span>" ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if ($message) { ?>
                                        <div class="alert alert-success">
                                            <button data-dismiss="alert" class="close" type="button">×</button>
                                            <?= $message; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($error) { ?>
                                        <div class="alert alert-danger">
                                            <button data-dismiss="alert" class="close" type="button">×</button>
                                            <?= $error; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($warning) { ?>
                                        <div class="alert alert-warning">
                                            <button data-dismiss="alert" class="close" type="button">×</button>
                                            <?= $warning; ?>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    if ($info) {
                                        foreach ($info as $n) {
                                            if (!$this->session->userdata('hidden' . $n->id)) {
                                                ?>
                                                <div class="alert alert-info">
                                                    <a href="#" id="<?= $n->id ?>" class="close hideComment external"
                                                       data-dismiss="alert">&times;</a>
                                                    <?= $n->comment; ?>
                                                </div>
                                            <?php }
                                        }
                                    } ?>
                                    <div class="alerts-con"></div>

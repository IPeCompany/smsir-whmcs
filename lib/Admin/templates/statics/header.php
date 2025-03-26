<?php
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="<?php echo vahab::AdminTemplateDir('assets/css/datatables.min.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo vahab::AdminTemplateDir('assets/css/vahab.css'); ?>">
<script src="<?php echo vahab::AdminTemplateDir('assets/js/datatables.min.js'); ?>"></script>
<script src="<?php echo vahab::AdminTemplateDir('assets/js/vahab.js'); ?>"></script>
<div id="vo_smsir">
    <div class="vo_smsir_header">
        <a href="https://sms.ir" target="_blank"><img src="<?php echo vahab::AdminTemplateDir('assets/img/logo.png'); ?>"></a>
        <a href="https://sms.ir" target="_blank" class="btn btn-vo-head">اطلاعات افزونه</a>
    </div>

    <div class="vo_smsir_menubar">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="https://sms.ir">اس ام اس</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class="<?php echo vahab::activemenu(['index']); ?>"><a href="<?php echo vahab::AdminUrl(); ?>">داشبورد</a></li>

                        <li class="dropdown <?php echo vahab::activemenu(['usersend','adminsend','logsmssends','installhook']); ?>">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">پیامک های یادآوری  <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="<?php echo vahab::activemenu(['usersend']); ?>"><a href="<?php echo vahab::AdminUrl('action=usersend'); ?>">پیام های کاربر</a></li>
                                <li class="<?php echo vahab::activemenu(['adminsend']); ?>"><a href="<?php echo vahab::AdminUrl('action=adminsend'); ?>">پیام های مدیر</a></li>
                                <li class="<?php echo vahab::activemenu(['logsmssends']); ?>"><a href="<?php echo vahab::AdminUrl('action=logsmssends'); ?>">گزارش های ارسال</a></li>
                                <li class="<?php echo vahab::activemenu(['installhook']); ?>"><a href="<?php echo vahab::AdminUrl('action=installhook'); ?>">نصب هوک</a></li>
                            </ul>
                        </li>

                        <li class="dropdown <?php echo vahab::activemenu(['verify_users','verify_logsend','verify_loginputs','verify_settings','mngverify']); ?>">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">وریفای شماره <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="<?php echo vahab::activemenu(['verify_users','mngverify']); ?>"><a href="<?php echo vahab::AdminUrl('action=verify_users'); ?>">مدیریت کاربران</a></li>
                                <li class="<?php echo vahab::activemenu(['verify_logsend']); ?>"><a href="<?php echo vahab::AdminUrl('action=verify_logsend'); ?>">گزارش ارسال ها</a></li>
                                <li class="<?php echo vahab::activemenu(['verify_loginputs']); ?>"><a href="<?php echo vahab::AdminUrl('action=verify_loginputs'); ?>">گزارش ورودی ها</a></li>
                                <li class="<?php echo vahab::activemenu(['verify_settings']); ?>"><a href="<?php echo vahab::AdminUrl('action=verify_settings'); ?>">تنظیمات</a></li>
                            </ul>
                        </li>

                        <li class="dropdown <?php echo vahab::activemenu(['bulkusersend','bulkusercontact','logsendpb','logsendusr']); ?>">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">ارسال هدفمند <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="<?php echo vahab::activemenu(['bulkusersend']); ?>"><a href="<?php echo vahab::AdminUrl('action=bulkusersend'); ?>">ارسال به کاربران</a></li>
                                <li class="<?php echo vahab::activemenu(['bulkusercontact']); ?>"><a href="<?php echo vahab::AdminUrl('action=bulkusercontact'); ?>">ارسال به دفتر تلفن</a></li>
                                <li class="<?php echo vahab::activemenu(['logsendpb']); ?>"><a href="<?php echo vahab::AdminUrl('action=logsendpb'); ?>">گزارش ارسال دفترتلفن</a></li>
                                <li class="<?php echo vahab::activemenu(['logsendusr']); ?>"><a href="<?php echo vahab::AdminUrl('action=logsendusr'); ?>">گزارش ارسال کاربران</a></li>
                            </ul>
                        </li>

                        <li class="dropdown <?php echo vahab::activemenu(['pbmanagement','addpb','addcontact']); ?>">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">دفتر تلفن <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="<?php echo vahab::activemenu(['pbmanagement']); ?>"><a href="<?php echo vahab::AdminUrl('action=pbmanagement'); ?>">مدیریت دفتر تلفن ها</a></li>
                                <li class="<?php echo vahab::activemenu(['addpb']); ?>"><a href="<?php echo vahab::AdminUrl('action=addpb'); ?>">افزودن دفتر تلفن</a></li>
                                <li class="<?php echo vahab::activemenu(['addcontact']); ?>"><a href="<?php echo vahab::AdminUrl('action=addcontact'); ?>">افزودن شماره</a></li>
                            </ul>
                        </li>

                        <li class="<?php echo vahab::activemenu(['settings']); ?>"><a href="<?php echo vahab::AdminUrl('action=settings'); ?>">تنظیمات</a></li>
                        <li class="<?php echo vahab::activemenu(['informations']); ?>"><a href="<?php echo vahab::AdminUrl('action=informations'); ?>">اطلاعات حساب</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="https://app.sms.ir/auth/sign-up" target="_blank"> خرید پنل</a></li>
                        <li><a href="https://sms.ir/panel/" target="_blank">ورود به پنل</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>


    <div class="vo_smsir_body">
        <?php echo vahab::Alert(); ?>
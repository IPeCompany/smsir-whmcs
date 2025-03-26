<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;


if(vahab::EoN($_REQUEST['id'])){
    vahab::Alert([
        'class' => 'warning',
        'message' => 'شناسه مورد نظر یافت نشد',
        'url' => 'addonmodules.php?module=smsir&action=verify_users'
    ]);
}

$ID = $_REQUEST['id'];

$chekAgain = false;
try {
    $chekAgain = Capsule::table('smsir_vo_verifications_users')->where("userid", $ID)->value('ID');
}catch (\Exception $e){}


if(!vahab::EoN($chekAgain)){
    vahab::toUrl('addonmodules.php?module=smsir&action=mngverify&id=' . $chekAgain);
    die();
}



//get insert id
$expTime = (time() + vahab::GS('verify_expiretime'));
try {
    $new_log_id = Capsule::table('smsir_vo_verifications_users')->insertGetId([
        "req_id" => time(),
        "expired_at" => $expTime,
        "userid" => $ID,
        "phone_number" => vahab::utell($ID),
        "verification_code" => rand(1000,99999),
        "created_at" => date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {}
vahab::toUrl('addonmodules.php?module=smsir&action=mngverify&id=' . $new_log_id);
die();
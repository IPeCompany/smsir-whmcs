<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\pb;

$backurl = 'addonmodules.php?module=smsir&action=pbmanagement';
if(vahab::EoN($_GET['id'])){
    vahab::toUrl($backurl);
}
if(vahab::EoN($_GET['type'])){
    vahab::toUrl($backurl);
}
$ID = $_GET['id'];
$type = $_GET['type'];


if($type == 'number'){
    $pb_id = Capsule::table('smsir_vo_phone')->where('ID', $ID)->value('pb_id');
    $backurl = 'addonmodules.php?module=smsir&action=pblistnumbers&id=' . $pb_id;
    try {
        Capsule::table('smsir_vo_phone')->where('ID', $ID)->delete();
        vahab::Alert([
            'class' => 'success',
            'message' => 'مخاطب با موفقیت حذف شد',
            'url' => $backurl
        ]);
        die();
    } catch (Exception $e) {}
}


if($type == 'phonebook'){
    try {
        Capsule::table('smsir_vo_phonebooks')->where('ID', $ID)->delete();
        Capsule::table('smsir_vo_phone')->where('pb_id', $ID)->delete();
        vahab::Alert([
            'class' => 'success',
            'message' => 'مخاطب با موفقیت حذف شد',
            'url' => $backurl
        ]);
        die();
    } catch (Exception $e) {}
}



vahab::toUrl($backurl);
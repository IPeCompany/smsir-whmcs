<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_AfterCronJob($params){
    $hookname = 'AfterCronJob';
    $replaces = [];
    vahab::SendMessageForHook($hookname, $replaces);
}



function smsir_vo_AfterCronJob_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'بعد از اجرای هر کرون',
            'params' => '',
            'admin_params' => '',
            'type_send' => 'default',
            'send_for' => 'admin',
            'message' => '',
            'admin_message' => 'کرون جان اجرا شد',
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
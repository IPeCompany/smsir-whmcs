<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_DailyCronJob($params){
    $hookname = 'DailyCronJob';
    $replaces = [];
    vahab::SendMessageForHook($hookname, $replaces);
}



function smsir_vo_DailyCronJob_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'نگام اجرای کرون روزانه',
            'params' => '',
            'admin_params' => '',
            'type_send' => 'default',
            'send_for' => 'admin',
            'message' => "",
            'admin_message' => "کرون روزانه اجرا شد",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
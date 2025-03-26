<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_AfterAddonUpgrade($params){
    $hookname = 'AfterAddonUpgrade';
    $replaces = [
        '{upgradeid}' => $params['upgradeid']
    ];
    vahab::SendMessageForHook($hookname, $replaces);
}



function smsir_vo_AfterAddonUpgrade_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'بعد از آپگرید افزودنی',
            'params' => '',
            'admin_params' => '{upgradeid}',
            'type_send' => 'default',
            'send_for' => 'admin',
            'message' => '',
            'admin_message' => 'درخواست شماره {upgradeid} ارتقا یافت',
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
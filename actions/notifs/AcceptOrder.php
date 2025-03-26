<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_AcceptOrder($params){
    $hookname = 'AcceptOrder';
    $orderid = $params['orderid'];
    $orderdata = hdata::orderdata($orderid);
    $uid = $orderdata->userid;
    $replaces = [
        '{orderid}' => $orderid
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_AcceptOrder_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'تایید سفارش',
            'params' => vahab::ShowUserDefaultTag('{orderid}'),
            'admin_params' => vahab::ShowUserDefaultTag('{orderid}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "عزیز\n سفارش {orderid} شما تایید شد {firstname} {lastname}",
            'admin_message' => "عزیز\n سفارش {orderid} شما تایید شد {firstname} {lastname}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
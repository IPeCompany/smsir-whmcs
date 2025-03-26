<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_OrderPaid($params){
    $hookname = 'OrderPaid';
    $orderid = $params['orderId'];
    $odata = hdata::orderdata($orderid);
    $uid = $odata->userid;
    $replaces = [
        '{orderid}' => $orderid,
        '{status}' => hdata::Lang($odata->status),
        '{invoiceid}' => $odata->invoiceid,
        '{amount}' => number_format($odata->amount),
        '{nameservers}' => $odata->nameservers,
        '{o_date}' => hdata::ShowDate($odata->date)
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_OrderPaid_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'پرداخت سفارش',
            'params' => vahab::ShowUserDefaultTag('{orderid}','{status}','{invoiceid}','{amount}','{nameservers}','{date}'),
            'admin_params' => vahab::ShowUserDefaultTag('{orderid}','{status}','{invoiceid}','{amount}','{nameservers}','{date}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nسفارش شماره {orderid} پرداخت شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nسفارش شماره {orderid} پرداخت شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
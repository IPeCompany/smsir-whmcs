<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_QuoteCreated($params){
    $hookname = 'QuoteCreated';
    $quoteid = $params['quoteid'];
    $status = $params['status'];
    $qData = hdata::quotes($quoteid);
    $uid = $qData->userid;
    $replaces = [
        '{subject}' => $qData->subject,
        '{stage}' => $qData->stage,
        '{validuntil}' => $qData->validuntil,
        '{proposal}' => $qData->proposal,
        '{subtotal}' => number_format($qData->subtotal),
        '{total}' => number_format($qData->total),
        '{customernotes}' => $qData->customernotes,
        '{datecreated}' => hdata::ShowDate($qData->datecreated),
        '{lastmodified}' => hdata::ShowDate($qData->lastmodified),
        '{datesent}' => hdata::ShowDate($qData->datesent),
        '{dateaccepted}' => hdata::ShowDate($qData->dateaccepted)
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_QuoteCreated_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'ایجاد پیش فاکتور',
            'params' => vahab::ShowUserDefaultTag('{subject}','{stage}','{validuntil}','{proposal}','{subtotal}','{tot}','{customernotes}','{datecreated}','{lastmodified}','{datesent}','{dateaccepted}'),
            'admin_params' => vahab::ShowUserDefaultTag('{subject}','{stage}','{validuntil}','{proposal}','{subtotal}','{tot}','{customernotes}','{datecreated}','{lastmodified}','{datesent}','{dateaccepted}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nپیش فاکتور {subject} برای شما صادر شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nپیش فاکتور {subject} برای شما صادر شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_InvoicePaymentReminder_secondoverdue($params){
    $hookname = 'InvoicePaymentReminder';
    $invoiceid = $params['invoiceid'];
    $type = $params['type'];
    $invData = hdata::invoice($invoiceid);
    $uid = $invData->userid;
    $replaces = [
        '{inv_id}' => $invoiceid,
        '{inv_date}' => hdata::ShowDate($invData->date),
        '{inv_duedateate}' => hdata::ShowDate($invData->duedate),
        '{inv_datepaid}' => hdata::ShowDate($invData->datepaid),
        '{inv_subtotal}' => number_format($invData->subtotal),
        '{inv_credit}' => number_format($invData->credit),
        '{inv_tax}' => number_format($invData->tax),
        '{inv_tax2}' => number_format($invData->tax2),
        '{inv_total}' => number_format($invData->total),
        '{inv_taxrate}' => number_format($invData->taxrate),
        '{inv_taxrate2}' => number_format($invData->taxrate2),
        '{inv_status}' => hdata::Lang($invData->status),
        '{inv_created_at}' => hdata::ShowDate($invData->updated_at)
    ];
    if($type == 'secondoverdue'){
        vahab::SendMessageForHook($hookname, $replaces, $uid);
    }
}



function smsir_vo_InvoicePaymentReminder_secondoverdue_install($hookname){
    $hookname = 'InvoicePaymentReminder';
    $checkFile = 0;
    try{
        $checkFile = Capsule::table('smsir_vo_hooks')->where("name", $hookname)->count();
    }catch(Exception $e){}
    if($checkFile < 4){
        try{
            Capsule::table('smsir_vo_hooks')->insert([
                'name' => 'InvoicePaymentReminder',
                'label' => 'یادآوری پرداخت فاکتور - سررسید دوم',
                'params' => vahab::ShowUserDefaultTag('{inv_id},{inv_date}','{inv_duedate}','{inv_datepaid}','{inv_subtotal}','{inv_credit}','{inv_tax}','{inv_tax2}','{inv_total}','{inv_taxrate}','{inv_taxrate2}','{inv_status}','{inv_created_at}'),
                'admin_params' => vahab::ShowUserDefaultTag('{inv_id},{inv_date}','{inv_duedate}','{inv_datepaid}','{inv_subtotal}','{inv_credit}','{inv_tax}','{inv_tax2}','{inv_total}','{inv_taxrate}','{inv_taxrate2}','{inv_status}','{inv_created_at}'),
                'type_send' => 'default',
                'send_for' => 'all',
                'message' => "{firstname} عزیز \nلطفا نسبت به پرداخت فاکتور شماره {inv_id} اقدام بفرمایید \n{signature}",
                'admin_message' => "{firstname} عزیز \nلطفا نسبت به پرداخت فاکتور شماره {inv_id} اقدام بفرمایید \n{signature}",
                'status' => 1,
                'user_status' => 0,
                'admin_status' => 0
            ]);
        }catch(Exception $e){}
    }
}
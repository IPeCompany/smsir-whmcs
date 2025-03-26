<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_AcceptQuote($params){
    $hookname = 'AcceptQuote';
    $quoteid = $params['quoteid'];
    $invoiceid = $params['invoiceid'];
    $quodata = hdata::quotes($quoteid);
    $invdata = hdata::invoice($invoiceid);
    $uid = $invdata->userid ?: $quodata->userid;

    $replaces = [
        '{quoteid}' => $quoteid,
        '{invoiceid}' => $invoiceid,
        '{q_subject}' => $quodata->subject,
        '{q_stage}' => $quodata->stage,
        '{q_validuntil}' => $quodata->validuntil,
        '{q_proposal}' => $quodata->proposal,
        '{q_customernotes}' => $quodata->customernotes,
        '{q_datecreated}' => hdata::ShowDate($quodata->datecreated),
        '{q_lastmodified}' => hdata::ShowDate($quodata->lastmodified),
        '{q_datesent}' => hdata::ShowDate($quodata->datesent),
        '{q_dateaccepted}' => hdata::ShowDate($quodata->dateaccepted),
        '{inv_date}' => hdata::ShowDate($invdata->date),
        '{inv_duedate}' => hdata::ShowDate($invdata->duedate),
        '{inv_datepaid}' => hdata::ShowDate($invdata->datepaid),
        '{inv_subtotal}' => number_format($invdata->subtotal),
        '{inv_credit}' => number_format($invdata->credit),
        '{inv_tax}' => number_format($invdata->tax),
        '{inv_tax2}' => number_format($invdata->tax2),
        '{inv_total}' => number_format($invdata->total),
        '{inv_taxrate}' => number_format($invdata->taxrate),
        '{inv_taxrate2}' => number_format($invdata->taxrate2),
        '{inv_status}' => hdata::Lang($invData->status),
        '{inv_paymentmethod}' => $invdata->paymentmethod,
        '{inv_created_at}' => hdata::ShowDate($invdata->created_at)
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_AcceptQuote_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'بعد از تایید پیش فاکتور',
            'params' => vahab::ShowUserDefaultTag('{quoteid}','{invoiceid}','{q_subject}','{q_stage}','{q_validuntil}','{q_proposal}','{q_customernotes}','{q_datecreated}','{q_lastmodified}','{q_datesent}','{q_dateaccepted}','{inv_date}','{inv_duedate}','{inv_datepaid}','{inv_subtotal}','{inv_credit}','{inv_tax}','{inv_tax2}','{inv_total}','{inv_taxrate}','{inv_taxrate2}','{inv_status}','{inv_paymentmethod}','{inv_created_at}'),
            'admin_params' => vahab::ShowUserDefaultTag('{quoteid}','{invoiceid}','{q_subject}','{q_stage}','{q_validuntil}','{q_proposal}','{q_customernotes}','{q_datecreated}','{q_lastmodified}','{q_datesent}','{q_dateaccepted}','{inv_date}','{inv_duedate}','{inv_datepaid}','{inv_subtotal}','{inv_credit}','{inv_tax}','{inv_tax2}','{inv_total}','{inv_taxrate}','{inv_taxrate2}','{inv_status}','{inv_paymentmethod}','{inv_created_at}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} {lastname} عزیز \nپیش فاکتور {quoteid} تایید شد \n{signature}",
            'admin_message' => "{firstname} {lastname} عزیز \nپیش فاکتور {quoteid} تایید شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
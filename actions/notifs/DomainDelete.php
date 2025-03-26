<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_DomainDelete($params){
    $hookname = 'DomainDelete';
    $uid = $params['userid'];
    $domainid = $params['domainid'];
    $domainData = hdata::tbldomains($domainid);
    $replaces = [
        '{type}' => $domainData->type,
        '{registrationdate}' => hdata::ShowDate( $domainData->registrationdate),
        '{domain}' => $domainData->domain,
        '{firstpaymentamount}' => number_format($domainData->firstpaymentamount),
        '{recurringamount}' => number_format($domainData->recurringamount),
        '{registrar}' => $domainData->registrar,
        '{registrationperiod}' => $domainData->registrationperiod,
        '{expirydate}' => hdata::ShowDate($domainData->expirydate),
        '{status}' => hdata::Lang($domainData->status),
        '{nextduedate}' => hdata::ShowDate($domainData->nextduedate),
        '{nextinvoicedate}' => hdata::ShowDate($domainData->nextinvoicedate)
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_DomainDelete_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'حذف دامنه',
            'params' => vahab::ShowUserDefaultTag('{type}','{registrationdate}','{domain}','{firstpaymentamount}','{recurringamount}','{registrar}','{registrationperiod}','{expirydate}','{status}','{nextduedate}','{nextinvoicedate}'),
            'admin_params' => vahab::ShowUserDefaultTag('{type}','{registrationdate}','{domain}','{firstpaymentamount}','{recurringamount}','{registrar}','{registrationperiod}','{expirydate}','{status}','{nextduedate}','{nextinvoicedate}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \n{domain} حذف شد \n{signature}",
            'admin_message' => "{firstname} عزیز \n{domain} حذف شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
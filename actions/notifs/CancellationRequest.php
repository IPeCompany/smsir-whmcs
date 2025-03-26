<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_CancellationRequest($params){
    $hookname = 'CancellationRequest';
    $uid = $params['userid'];
    $sid = $params['relid'];
    $sinfo = hdata::sinfo($sid);
    $replaces = [
        '{reason}' => $params['reason'],
        '{type}' => $params['type'],
        '{sid}' => $sid,
        '{pid}' => $sinfo->pid,
        '{server}' => $sinfo->server,
        '{regdate}' => hdata::ShowDate($sinfo->regdate),
        '{domain}' => $sinfo->domain,
        '{firstpaymentamount}' => number_format($sinfo->firstpaymentamount),
        '{amount}' => number_format($sinfo->amount),
        '{billingcycle}' => hdata::Lang($sinfo->billingcycle),
        '{nextduedate}' => hdata::ShowDate($sinfo->nextduedate),
        '{termination_date}' => hdata::ShowDate($sinfo->termination_date),
        '{domainstatus}' => hdata::Lang($sinfo->domainstatus),
        '{username}' => $sinfo->username,
        '{dedicatedip}' => $sinfo->dedicatedip,
        '{assignedips}' => $sinfo->assignedips,
        '{ns1}' => $sinfo->ns1,
        '{ns2}' => $sinfo->ns2,
        '{PortNumber}' => $sinfo->PortNumber,
        '{gid}' => $sinfo->gid,
        '{p_type}' => $sinfo->p_type,
        '{p_name}' => $sinfo->p_name,
        '{p_slug}' => $sinfo->p_slug,
        '{p_description}' => $sinfo->p_description,
        '{g_name}' => $sinfo->g_name,
        '{g_slug}' => $sinfo->g_slug,
        '{g_headline}' => $sinfo->g_headline,
        '{g_tagline}' => $sinfo->g_tagline
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_CancellationRequest_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'ثبت درخواست کنسل',
            'params' => vahab::ShowUserDefaultTag('{reason}','{type}','{sid}','{pid}','{server}','{regdate}','{domain}','{firstpaymentamount}','{amount}','{billingcycle}','{nextduedate}','{termination_date}','{domainstatus}','{username}','{dedicatedip}','{assignedips}','{ns1}','{ns2}','{PortNumber}','{gid}','{p_type}','{p_name}','{p_slug}','{p_description}','{g_name}','{g_slug}','{g_headline}','{g_tagline}'),
            'admin_params' => vahab::ShowUserDefaultTag('{reason}','{type}','{sid}','{pid}','{server}','{regdate}','{domain}','{firstpaymentamount}','{amount}','{billingcycle}','{nextduedate}','{termination_date}','{domainstatus}','{username}','{dedicatedip}','{assignedips}','{ns1}','{ns2}','{PortNumber}','{gid}','{p_type}','{p_name}','{p_slug}','{p_description}','{g_name}','{g_slug}','{g_headline}','{g_tagline}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nدرخواست کنسلی شما با موفقیت ثبت شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nدرخواست کنسلی شما با موفقیت ثبت شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
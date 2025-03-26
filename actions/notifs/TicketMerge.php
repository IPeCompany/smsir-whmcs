<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_TicketMerge($params){
    $hookname = 'TicketMerge';
    $ticketid = $params['masterTicketId'];
    $tData = hdata::tickets($ticketid);
    $uid = $tData->userid;
    $replaces = [
        '{ticketid}' => $tData->ticketid,
        '{deptname}' =>  $tData->deptname,
        '{subject}' =>  $tData->subject,
        '{message}' =>  $tData->message,
        '{priority}' => hdata::Lang($tData->urgency),
        '{status}' => hdata::Lang($tData->status),
        '{t_date}' => hdata::ShowDate($tData->date),
        '{t_lastreply}' => hdata::ShowDate($tData->lastreply),
        '{tid}' =>  $tData->tid
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_TicketMerge_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'ترکیب شدن تیکت ها',
            'params' => vahab::ShowUserDefaultTag('{ticketid}','{deptname}','{subject}','{message}','{priority}','{status}','{t_date}','{t_lastreply}','{tid}'),
            'admin_params' => vahab::ShowUserDefaultTag('{ticketid}','{deptname}','{subject}','{message}','{priority}','{status}','{t_date}','{t_lastreply}','{tid}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nتیکت {tid} با تیکت دیگری ترکیب شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nتیکت {tid} با تیکت دیگری ترکیب شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
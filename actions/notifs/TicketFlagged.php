<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_TicketFlagged($params){
    $hookname = 'TicketFlagged';
    $ticketid = $params['ticketid'];
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
        '{adminname}' =>  $params['adminname'],
        '{tid}' =>  $tData->tid
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_TicketFlagged_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'پرچم شدن تیکت',
            'params' => vahab::ShowUserDefaultTag('{ticketid}','{deptname}','{subject}','{message}','{priority}','{status}','{t_date}','{t_lastreply}','{tid}','{adminname}'),
            'admin_params' => vahab::ShowUserDefaultTag('{ticketid}','{deptname}','{subject}','{message}','{priority}','{status}','{t_date}','{t_lastreply}','{tid}','{adminname}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nتیکت {tid} به {adminname} محول شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nتیکت {tid} به {adminname} محول شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
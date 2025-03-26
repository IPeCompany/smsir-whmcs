<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_UserEdit($params){
    $hookname = 'UserEdit';
    $uid = $params['user_id'];
    $replaces = [
        '{_user_id}' => $uid,
        '{_firstname}' => $params['firstname'],
        '{_lastname}' => $params['lastname'],
        '{_email}' => $params['email']
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_UserEdit_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'ویرایش کاربر',
            'params' => vahab::ShowUserDefaultTag('{_user_id}','{_firstname}','{_lastname}','{_email}'),
            'admin_params' => vahab::ShowUserDefaultTag('{_user_id}','{_firstname}','{_lastname}','{_email}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nکاربر ویرایش شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nکاربر ویرایش شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
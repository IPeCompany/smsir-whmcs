<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_UserAdd($params){
    $hookname = 'UserAdd';
    $uid = $params['user_id'];
    $replaces = [
        '{_user_id}' => $uid,
        '{_firstname}' => $params['firstname'],
        '{_lastname}' => $params['lastname'],
        '{_email}' => $params['email'],
        '{_password}' => $params['password'],
        '{_language}' => $params['language']
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_UserAdd_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'اضافه شدن کاربر جدید',
            'params' => vahab::ShowUserDefaultTag('{_user_id}','{_firstname}','{_lastname}','{_email}','{_password}','{_language}'),
            'admin_params' => vahab::ShowUserDefaultTag('{_user_id}','{_firstname}','{_lastname}','{_email}','{_password}','{_language}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nکاربر {user_id} با موفقیت ایجاد شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nکاربر {user_id} با موفقیت ایجاد شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
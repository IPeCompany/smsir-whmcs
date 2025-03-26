<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_UserChangePassword($params){
    $hookname = 'UserChangePassword';
    $uid = $params['userid'];
    $password = $params['password'];
    $replaces = [
        '{password}' => $password
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_UserChangePassword_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'تغییر رمز عبور کاربر',
            'params' => vahab::ShowUserDefaultTag('{password}'),
            'admin_params' => vahab::ShowUserDefaultTag('{password}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nرمز عبور جدید شما {password} \n{signature}",
            'admin_message' => "{firstname} عزیز \nرمز عبور جدید شما {password} \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
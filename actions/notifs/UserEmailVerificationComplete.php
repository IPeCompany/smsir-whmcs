<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_UserEmailVerificationComplete($params){
    $hookname = 'UserEmailVerificationComplete';
    $uid = $params['userId'];
    $replaces = [];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_UserEmailVerificationComplete_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'ایمیل کاربر وریفای شد',
            'params' => vahab::ShowUserDefaultTag(),
            'admin_params' => vahab::ShowUserDefaultTag(),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nایمیل شما با موفقیت تایید شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nایمیل شما با موفقیت تایید شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
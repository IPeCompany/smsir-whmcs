<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_AdminLogout($params)
{
    $hookname = 'AdminLogout';
    $aid = $params['adminid'];
    vahab::SendMessageForHook($hookname, [], '', $aid);
}


function smsir_vo_AdminLogout_install($hookname)
{
    try {
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'بعد از خروج مدیر',
            'params' => '',
            'admin_params' => vahab::ShowAdminDefaultTag(),
            'type_send' => 'default',
            'send_for' => 'admin',
            'message' => '',
            'admin_message' => 'کاربر {username} از مدیریت خارج شد',
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    } catch (Exception $e) {
    }
}
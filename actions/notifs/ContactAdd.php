<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_ContactAdd($params){
    $hookname = 'ContactAdd';
    $uid = $params['userid'];
    $replaces = [
        '{c_firstname}' => $params['firstname'],
        '{c_lastname}' => $params['lastname'],
        '{c_companyname}' => $params['companyname'],
        '{c_email}' => $params['email'],
        '{c_address1}' => $params['address1'],
        '{c_address2}' => $params['address2'],
        '{c_city}' => $params['city'],
        '{c_state}' => $params['state'],
        '{c_postcode}' => $params['postcode'],
        '{c_country}' => $params['country'],
        '{c_phonenumber}' => $params['phonenumber']
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_ContactAdd_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'اضافه کردن اطلاعات جدید',
            'params' => vahab::ShowUserDefaultTag('{c_firstname}','{c_lastname}','{c_companyname}','{c_email}','{c_address1}','{c_address2}','{c_city}','{c_state}','{c_postcode}','{c_country}','{c_phonenumber}'),
            'admin_params' => vahab::ShowUserDefaultTag('{c_firstname}','{c_lastname}','{c_companyname}','{c_email}','{c_address1}','{c_address2}','{c_city}','{c_state}','{c_postcode}','{c_country}','{c_phonenumber}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nمخاطب جدید شما با موفقین ایجاد شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nمخاطب جدید شما با موفقین ایجاد شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_ContactDelete($params){
    $hookname = 'ContactDelete';
    $uid = $params['userid'];
    $contactid = $params['contactid'];
    $contact = hdata::contact($contactid);
    $replaces = [
        '{c_firstname}' => $contact->firstname,
        '{c_lastname}' => $contact->lastname,
        '{c_companyname}' => $contact->companyname,
        '{c_email}' => $contact->email,
        '{c_address1}' => $contact->address1,
        '{c_address2}' => $contact->address2,
        '{c_city}' => $contact->city,
        '{c_state}' => $contact->state,
        '{c_postcode}' => $contact->postcode,
        '{c_country}' => $contact->country,
        '{c_phonenumber}' => $contact->phonenumber
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_ContactDelete_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'حذف یک مخاطب',
            'params' => vahab::ShowUserDefaultTag('{c_firstname}','{c_lastname}','{c_companyname}','{c_email}','{c_address1}','{c_address2}','{c_city}','{c_state}','{c_postcode}','{c_country}','{c_phonenumber}'),
            'admin_params' => vahab::ShowUserDefaultTag('{c_firstname}','{c_lastname}','{c_companyname}','{c_email}','{c_address1}','{c_address2}','{c_city}','{c_state}','{c_postcode}','{c_country}','{c_phonenumber}'),
            'type_send' => 'default',
            'send_for' => 'all',
            'message' => "{firstname} عزیز \nمخاطب مورد نظر شما حذف شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nمخاطب مورد نظر شما حذف شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
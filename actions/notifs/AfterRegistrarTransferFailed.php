<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;

function smsir_vo_AfterRegistrarTransferFailed($params){
    $hookname = 'AfterRegistrarTransferFailed';
    $uid = $params['params']['userid'];
    $replaces = [
        '{domainid}' => $params['params']['domainid'],
        '{sld}' => $params['params']['sld'],
        '{tld}' => $params['params']['tld'],
        '{regperiod}' => $params['params']['regperiod'],
        '{eppcode}' => $params['params']['eppcode'],
        '{ns1}' => $params['params']['ns1'],
        '{ns2}' => $params['params']['ns2'],
        '{ns3}' => $params['params']['ns3'],
        '{ns4}' => $params['params']['ns4'],
        '{ns5}' => $params['params']['ns5'],
        '{contact_firstname}' => $params['params']['firstname'],
        '{contact_lastname}' => $params['params']['lastname'],
        '{contact_fullname}' => $params['params']['fullname'],
        '{contact_companyname}' => $params['params']['companyname'],
        '{contact_email}' => $params['params']['email'],
        '{contact_address1}' => $params['params']['address1'],
        '{contact_address2}' => $params['params']['address2'],
        '{contact_city}' => $params['params']['city'],
        '{contact_state}' => $params['params']['state'],
        '{contact_fullstate}' => $params['params']['fullstate'],
        '{contact_postcode}' => $params['params']['postcode'],
        '{contact_countrycode}' => $params['params']['countrycode'],
        '{contact_countryname}' => $params['params']['countryname'],
        '{contact_phonenumber}' => $params['params']['phonenumber'],
        '{contact_phonecc}' => $params['params']['phonecc'],
        '{contact_fullphonenumber}' => $params['params']['fullphonenumber'],
        '{adminfirstname}' => $params['params']['adminfirstname'],
        '{adminlastname}' => $params['params']['adminlastname'],
        '{admincompanyname}' => $params['params']['admincompanyname'],
        '{adminemail}' => $params['params']['adminemail'],
        '{adminaddress1}' => $params['params']['adminaddress1'],
        '{adminaddress2}' => $params['params']['adminaddress2'],
        '{admincity}' => $params['params']['admincity'],
        '{adminstate}' => $params['params']['adminstate'],
        '{adminfullstate}' => $params['params']['adminfullstate'],
        '{adminpostcode}' => $params['params']['adminpostcode'],
        '{admincountry}' => $params['params']['admincountry'],
        '{adminphonenumber}' => $params['params']['adminphonenumber'],
        '{adminfullphonenumber}' => $params['params']['adminfullphonenumber']
    ];
    vahab::SendMessageForHook($hookname, $replaces, $uid);
}



function smsir_vo_AfterRegistrarTransferFailed_install($hookname){
    try{
        Capsule::table('smsir_vo_hooks')->insert([
            'name' => $hookname,
            'label' => 'خطا در انتقال دامنه',
            'params' => vahab::ShowUserDefaultTag('{domainid}','{sld}','{tld}','{regperiod}','{eppcode}','{ns1}','{ns2}','{ns3}','{ns4}','{ns5}','{contact_firstname}','{contact_lastname}','{contact_fullname}','{contact_companyname}','{contact_email}','{contact_address1}','{contact_address2}','{contact_city}','{contact_state}','{contact_fullstate}','{contact_postcode}','{contact_countrycode}','{contact_countryname}','{contact_phonenumber}','{contact_phonecc}','{contact_fullphonenumber}','{adminfirstname}','{adminlastname}','{admincompanyname}','{adminemail}','{adminaddress1}','{adminaddress2}','{admincity}','{adminstate}','{adminfullstate}','{adminpostcode}','{admincountry}','{adminphonenumber}','{adminfullphonenumber}'),
            'admin_params' => vahab::ShowUserDefaultTag('{domainid}','{sld}','{tld}','{regperiod}','{eppcode}','{ns1}','{ns2}','{ns3}','{ns4}','{ns5}','{contact_firstname}','{contact_lastname}','{contact_fullname}','{contact_companyname}','{contact_email}','{contact_address1}','{contact_address2}','{contact_city}','{contact_state}','{contact_fullstate}','{contact_postcode}','{contact_countrycode}','{contact_countryname}','{contact_phonenumber}','{contact_phonecc}','{contact_fullphonenumber}','{adminfirstname}','{adminlastname}','{admincompanyname}','{adminemail}','{adminaddress1}','{adminaddress2}','{admincity}','{adminstate}','{adminfullstate}','{adminpostcode}','{admincountry}','{adminphonenumber}','{adminfullphonenumber}'),
            'type_send' => 'default',
            'send_for' => 'admin',
            'message' => "{firstname} عزیز \nانتقال دامنه {sld}.{tld} با خطا مواجه شد \n{signature}",
            'admin_message' => "{firstname} عزیز \nانتقال دامنه {sld}.{tld} با خطا مواجه شد \n{signature}",
            'status' => 1,
            'user_status' => 0,
            'admin_status' => 0
        ]);
    }catch(Exception $e){}
}
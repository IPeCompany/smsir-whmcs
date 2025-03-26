<?php
/*
 * Copyright (c) 2023-12-18
 * This file created by VahabOnline MVC Software
 * Author : Vahab Sydi
 * Email : myVahab@gmail.com
 * Website : https://vahabonline.ir && https://ivahab.ir
 * Instagram : https://instagram.com/vahab.dev
 * Telegram : https://t.me/vahabdev
*/
namespace WHMCS\Module\Addon\smsir\vahabonline;
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;


class vahab
{

    /**
     * if empty or null == true
     */
    public static function EoN($string){
        if(empty($string)){
            return true;
        }
        if(is_null($string)){
            return true;
        }
        return false;
    }


    /**
     * Get addon Directory URL
     */
    public static $ADDONURL = ROOTDIR . '/modules/addons/smsir/';

    /**
     * Get addon Directory URL by add url
     */
    public static function ADDONURL(string $other='')
    {
        if(!empty($other) && !is_null($other)){
            return ROOTDIR . '/modules/addons/smsir/' . $other;
        }
        return ROOTDIR . '/modules/addons/smsir/';
    }


    /**
     * Get admin template url by add url
     */
    public static function AdminTemplateDir($other=''){
        if(!empty($other) && !is_null($other)){
            return $GLOBALS['CONFIG']['SystemURL'] . '/modules/addons/smsir/lib/Admin/templates/' . $other;
        }
        return $GLOBALS['CONFIG']['SystemURL'] . '/modules/addons/smsir/lib/Admin/templates/';
    }


    /**
     * Get whmcs site url by add url
     */
    public static function SiteUrl($other=''){
        if(!empty($other) && !is_null($other)){
            return $GLOBALS['CONFIG']['SystemURL'] . $other;
        }
        return $GLOBALS['CONFIG']['SystemURL'];
    }

    /**
     * Get addon admin url by add url
     */
    public static function AdminUrl($other='')
    {
        $url = 'addonmodules.php?module=smsir';
        if(!empty($other) && !is_null($other)){
            return $url . '&' . $other;
        }
        return $url . '&action=index';
    }


    /**
     * Active admin menus
     */
    public static function activemenu($menu)
    {
        $action = $_GET['action'];
        if(self::EoN($menu)){
            return null;
        }
        if(self::EoN($action)){
            return null;
        }
        if(!is_array($menu)){
            return null;
        }
        if(in_array($action, $menu)){
            return 'active';
        }
    }


    /**
     * selected a select
     */
    public static function selectmenu($current,$list='')
    {
        if(self::EoN($list)){
            return null;
        }
        if(self::EoN($current)){
            return null;
        }
        if(!is_array($list)){
            return null;
        }
        if(in_array($current, $list)){
            return 'selected';
        }
    }

    /**
     * Get addon Settings
     */
    public static function GS($name)
    {
        try {
            return Capsule::table('smsir_vo_settings')->where("name", $name)->value('value');
        }catch (\Exception $e){}
        return null;
    }

    /**
     * Set addon Settings
     */
    public static function SetS($name, $value)
    {
        try {
            Capsule::table('smsir_vo_settings')->updateOrInsert(
                ['name' => $name],
                ['value' => $value]
            );
            return true;
        }catch (\Exception $e){}
        return false;
    }

    /**
     * Get all Custom Field by <option> and Show only text type
     */
    public static function OptionMobileFields($slct='')
    {
        $out = '';
        $list = '';
        try {
            $list = Capsule::table('tblcustomfields')
            ->where('fieldtype', 'text')
            ->where('type', 'client')
            ->get();
        }catch (\Exception $e){}
        if(self::EoN($list)){
            return null;
        }
        foreach ($list as $ls){
            $tsclt = '';
            if(!self::EoN($slct)){
                if($slct == $ls->id){
                    $tsclt = 'selected';
                }
            }
            $out .= '<option value="'.$ls->id.'" '.$tsclt.'>'.$ls->fieldname.'</option>';
        }
        return $out;
    }


    /**
     * Get user tellphone
     */
    public static function utell($uid)
    {
        $phoneNumber = null;
        $type = self::GS('mobilefield');
        if($type == 'default'){
            $phoneNumber = self::uinfo($uid)->phonenumber;
        }else{
            $phoneNumber = Capsule::table('tblcustomfieldsvalues')
                ->where('fieldid', $type)
                ->where('relid', $uid)
                ->value('value');
        }
        return $phoneNumber;
    }

    /**
     * Get User info
     */
    public static function uinfo($uid)
    {
        try {
            return Capsule::table('tblclients')->where("id", $uid)->first();
        }catch (\Exception $e){}
        return null;
    }


    /**
     * Set And Show Alert
     */
    public static function Alert($params='')
    {
        if(self::EoN($params) && !self::EoN($_SESSION['smsir_vo']['alert']['class'])){
            $message = '<div class="vo-alert vo-'.$_SESSION['smsir_vo']['alert']['class'].'">'.$_SESSION['smsir_vo']['alert']['message'].'</div>';
            unset($_SESSION['smsir_vo']['alert']);
            return $message;
        }
        if(!self::EoN($params)){
            $_SESSION['smsir_vo']['alert']['class'] = $params['class'];
            $_SESSION['smsir_vo']['alert']['message'] =  $params['message'];
            if(!self::EoN($params['url'])){
                self::toUrl($params['url']);
            }
        }

    }

    /**
     * Set And Show User Alert
     */
    public static function UAlert($params='')
    {
        if(self::EoN($params) && !self::EoN($_SESSION['smsir_vo']['alert']['u_class'])){
            $message = '<div class="alert alert-'.$_SESSION['smsir_vo']['alert']['u_class'].'">'.$_SESSION['smsir_vo']['alert']['u_message'].'</div>';
            unset($_SESSION['smsir_vo']['alert']);
            return $message;
        }
        if(!self::EoN($params)){
            $_SESSION['smsir_vo']['alert']['u_class'] = $params['class'];
            $_SESSION['smsir_vo']['alert']['u_message'] =  $params['message'];
            if(!self::EoN($params['url'])){
                self::toUrl($params['url']);
            }
        }

    }

    /**
     * Redirect
     */
    public static function toUrl($url)
    {
        echo '<script>document.location.href = "'.$url.'"</script>';
        die();
    }







    /**
     * Get Sms.ir Token in set addon
     */
    public static function ApiKey()
    {
        try {
            return Capsule::table('tbladdonmodules')
                ->where("module", "smsir")
                ->where("setting", "ApiKey")
                ->value('value');
        }catch (\Exception $e){}
        return null;
    }






    /** ==================================================================== */



    /**
     * Get all hooks
     */
    public static function AllHooks()
    {
        try {
            return Capsule::table('smsir_vo_hooks')
                ->select('name')
                ->where("status", 1)
                ->get();
        }catch (\Exception $e){}
        return array();
    }


    /**
     * Get the information of a hook
     */
    public static function getHookInfo($hookname='')
    {
        if(self::EoN($hookname)){
            return null;
        }
        try {
            return Capsule::table('smsir_vo_hooks')
                ->where("name", $hookname)
                ->first();
        }catch (\Exception $e){}
        return null;
    }


    /**
     * Send as a normal message
     */
    public static function SendSmsByMessage($params)
    {
        $apiKey = self::ApiKey();
        $lineNumber = self::GS('default_number');

        $smsir['lineNumber'] = $lineNumber;
        $smsir['messageText'] = $params['message'];
        $smsir['mobiles'] = $params['mobiles'];
        $smsir['sendDateTime'] = null;


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sms.ir/v1/send/bulk',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($smsir),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-API-KEY: ' . $apiKey
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        if($json['status'] != '1'){
            return $json['status'] . ' - ' . $json['message'];
        }
        return json_encode($json['data']['messageIds']);
    }


    /**
     * Sending as a service message
     */
    public static function SendSmsByPattern($params)
    {
        $msg = json_decode(html_entity_decode($params['message']), true);
        $apiKey = self::ApiKey();
        $smsir['mobile'] = $params['mobiles'][0];
        $smsir['templateId'] = $params['pattern_id'];
        $inum = 0;
        foreach ($msg as $key => $ptrn){
            $smsir['parameters'][$inum]['name'] = $key;
            $smsir['parameters'][$inum]['value'] = $ptrn;
            $inum++;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sms.ir/v1/send/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($smsir),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: text/plain',
                'x-api-key: ' . $apiKey
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        if($json['status'] != '1'){
            return $json['status'] . ' - ' . $json['message'];
        }
        return $json['data']['messageId'];
    }

    public static function GetCreditAccount($action='credit'){
        $apiKey = self::ApiKey();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sms.ir/v1/' . $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            'X-API-KEY: ' . $apiKey
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public static function UserMessageDefaultShortCodes($message='', $uid=''){
        if(!self::EoN($uid)){
            $uinfo = self::uinfo($uid);
            $message = strtr($message, [
                '{uid}' => $uinfo->id,
                '{firstname}' => $uinfo->firstname,
                '{lastname}' => $uinfo->lastname,
                '{companyname}' => $uinfo->companyname,
                '{email}' => $uinfo->email,
                '{address1}' => $uinfo->address1,
                '{address2}' => $uinfo->address2,
                '{city}' => $uinfo->city,
                '{state}' => $uinfo->state,
                '{postcode}' => $uinfo->postcode,
                '{country}' => $uinfo->country,
                '{phonenumber}' => $uinfo->phonenumber,
                '{mobile}' => self::utell($uid),
                '{startdate}' => hdata::ShowDate($uinfo->startdate),
                '{expdate}' => hdata::ShowDate($uinfo->expdate),
                '{host}' => $uinfo->host,
                '{status}' => hdata::Lang($uinfo->status),
                '{language}' => $uinfo->language,
                '{created_at}' => hdata::ShowDate($uinfo->created_at),
                '{updated_at}' => hdata::ShowDate($uinfo->updated_at),
                '{lastlogin}' => hdata::ShowDate($uinfo->lastlogin),
                '{ip}' => $uinfo->ip,
                '{sitename}' => vahab::GS('sitename'),
                '{Year}' => hdata::ShowDate(date('Y')),
                '{Month}' => hdata::ShowDate(date('m')),
                '{Day}' => hdata::ShowDate(date('d')),
                '{date}' => hdata::ShowDate(date('Y-m-d')),
                '{time}' => hdata::ShowDate(date('H:i:s'))
            ]);
        }
        return $message;
    }

    public static function UserMessagePatternShortCodes($pattern='', $uid=''){
        if(!self::EoN($uid)){
            $uinfo = self::uinfo($uid);
            $pattern = strtr($pattern, [
                '{uid}' => $uinfo->id,
                '{firstname}' => $uinfo->firstname,
                '{lastname}' => $uinfo->lastname,
                '{companyname}' => $uinfo->companyname,
                '{email}' => $uinfo->email,
                '{address1}' => $uinfo->address1,
                '{address2}' => $uinfo->address2,
                '{city}' => $uinfo->city,
                '{state}' => $uinfo->state,
                '{postcode}' => $uinfo->postcode,
                '{country}' => $uinfo->country,
                '{phonenumber}' => $uinfo->phonenumber,
                '{mobile}' => self::utell($uid),
                '{startdate}' => hdata::ShowDate($uinfo->startdate),
                '{expdate}' => hdata::ShowDate($uinfo->expdate),
                '{host}' => $uinfo->host,
                '{status}' => hdata::Lang($uinfo->status),
                '{language}' => $uinfo->language,
                '{created_at}' => hdata::ShowDate($uinfo->created_at),
                '{updated_at}' => hdata::ShowDate($uinfo->updated_at),
                '{lastlogin}' => hdata::ShowDate($uinfo->lastlogin),
                '{ip}' => $uinfo->ip,
                '{sitename}' => vahab::GS('sitename'),
                '{Year}' => hdata::ShowDate(date('Y')),
                '{Month}' => hdata::ShowDate(date('m')),
                '{Day}' => hdata::ShowDate(date('d')),
                '{date}' => hdata::ShowDate(date('Y-m-d')),
                '{time}' => hdata::ShowDate(date('H:i:s'))
            ]);
        }
        return $pattern;
    }

    public static function AdminMessageDefaultShortCodes($message='', $aid=''){
        if(!self::EoN($aid)){
            $info = hdata::tbladmins($aid);
            $message = strtr($message, [
                '{aid}' => $info->id,
                '{username}' => $info->username,
                '{firstname}' => $info->firstname,
                '{lastname}' => $info->lastname,
                '{email}' => $info->email,
                '{signature}' => $info->signature,
                '{created_at}' => hdata::ShowDate($info->created_at)
            ]);
        }
        return $message;
    }
    public static function AdminMessagePatternShortCodes($pattern='', $aid=''){
        if(!self::EoN($aid)){
            $info = hdata::tbladmins($aid);
            $pattern = strtr($pattern, [
                '{aid}' => $info->id,
                '{username}' => $info->username,
                '{firstname}' => $info->firstname,
                '{lastname}' => $info->lastname,
                '{email}' => $info->email,
                '{signature}' => $info->signature,
                '{created_at}' => hdata::ShowDate($info->created_at)
            ]);
        }
        return $pattern;
    }

    public static function ShowUserDefaultTag(...$params){
        $list = '{uid},{firstname},{lastname},{companyname},{email},{address1},{address2},{city},{state},{postcode},{country},{phonenumber},{mobile},{startdate},{expdate},{host},{status},{language},{created_at},{updated_at},{lastlogin},{ip},{sitename},{Year},{Month},{Day},{date},{time}';
        foreach ($params as $name){
            $list .= ',' . $name;
        }
        return $list;
    }
    public static function ShowAdminDefaultTag(...$params){
        $list = '{aid},{username},{firstname},{lastname},{email},{signature},{created_at}';
        foreach ($params as $name){
            $list .= ',' . $name;
        }
        return $list;
    }

    public static function ReplaceDefaults($type_send = 'default', $send_for='user', $message='', $replaces, $uid='', $aid=''){
        if(self::EoN($message)){
            return $message;
        }
        $signature = self::GS('signature');

        //defualts
        $message = strtr($message, $replaces);
        //add Signature
        $message = strtr($message, ['{signature}' => $signature]);




        if($type_send == 'default'){
            if($send_for == 'user' || $send_for == 'all'){
                return self::UserMessageDefaultShortCodes($message, $uid);
            }
            if($send_for == 'admin' || $send_for == 'all'){
                return self::AdminMessageDefaultShortCodes($message, $aid);
            }
        }
        if($type_send == 'pattern') {
            if($send_for == 'user' || $send_for == 'all'){
                return self::UserMessagePatternShortCodes($message, $uid);
            }
            if($send_for == 'admin' || $send_for == 'all'){
                return self::AdminMessagePatternShortCodes($message, $aid);
            }
        }

        return $message;
    }

    /**
     * Send messages to hooks
     */
    public static function SendMessageForHook($hookname, $replaces=array(), $uid='', $aid='')
    {
        $hookInfo = self::getHookInfo($hookname);
        if(self::EoN($hookInfo)){
            return null;
        }

        if($hookInfo->status == 0){
            return null;
        }
        if($hookInfo->admin_status == 0 && $hookInfo->user_status == 0){
            return null;
        }

        //ersal be karbar
        if($hookInfo->send_for == 'user' || $hookInfo->send_for == 'all'){
            if($hookInfo->type_send == 'default'){
                if($hookInfo->user_status){
                    $msg = self::ReplaceDefaults($hookInfo->type_send, $hookInfo->send_for, $hookInfo->message, $replaces, $uid, $aid);
                    $res = self::SendSmsByMessage([
                        'mobiles' => [self::utell($uid)],
                        'message' => $msg,
                        'form_number' => self::GS('default_number'),
                        'hook' => $hookname,
                        'type_send' => $hookInfo->type_send,
                        'sendfor' => 'user'
                    ]);
                    Capsule::table('smsir_vo_hooks_logs')->insert([
                        'hook' => $hookname,
                        'type_send' => $hookInfo->type_send,
                        'send_for' => 'user',
                        'uid' => $uid,
                        'mobile' => self::utell($uid),
                        'message' => json_encode(['message' => $msg]),
                        'result' => $res,
                        'created_at' => hdata::ShowDate(date('Y-m-d H:i:s'))
                    ]);
                }
            }
            if($hookInfo->type_send == 'pattern'){
                if($hookInfo->user_status){
                    $msg = self::ReplaceDefaults($hookInfo->type_send, $hookInfo->send_for, $hookInfo->pattern, $replaces, $uid, $aid);
                    $res = self::SendSmsByPattern([
                        'mobiles' => [self::utell($uid)],
                        'pattern_id' => $hookInfo->pattern_id,
                        'message' => $msg,
                        'form_number' => self::GS('pattern_number'),
                        'hook' => $hookname,
                        'type_send' => $hookInfo->type_send,
                        'sendfor' => 'user'
                    ]);
                    Capsule::table('smsir_vo_hooks_logs')->insert([
                        'hook' => $hookname,
                        'type_send' => $hookInfo->type_send,
                        'send_for' => 'user',
                        'uid' => $uid,
                        'mobile' => self::utell($uid),
                        'message' => json_encode(['patternID' => $hookInfo->pattern_id, 'message' => $msg]),
                        'result' => $res,
                        'created_at' => hdata::ShowDate(date('Y-m-d H:i:s'))
                    ]);
                }
            }
        }

        //ersal be admin
        if($hookInfo->send_for == 'admin' || $hookInfo->send_for == 'all'){
            if($hookInfo->admin_type_send == 'default'){
                if($hookInfo->admin_status){
                    $msg = self::ReplaceDefaults($hookInfo->admin_type_send, $hookInfo->send_for, $hookInfo->admin_message, $replaces, $uid, $aid);
                    $res = self::SendSmsByMessage([
                        'mobiles' => explode(',', $hookInfo->admin_numbers),
                        'message' => $msg,
                        'form_number' => self::GS('default_number'),
                        'hook' => $hookname,
                        'type_send' => $hookInfo->type_send,
                        'sendfor' => 'admin'
                    ]);
                    Capsule::table('smsir_vo_hooks_logs')->insert([
                        'hook' => $hookname,
                        'type_send' => $hookInfo->admin_type_send,
                        'send_for' => 'admin',
                        'uid' => $uid,
                        'mobile' => self::utell($uid),
                        'message' => json_encode(['message' => $msg]),
                        'result' => $res,
                        'created_at' => hdata::ShowDate(date('Y-m-d H:i:s'))
                    ]);
                }
            }
            if($hookInfo->admin_type_send == 'pattern'){
                if($hookInfo->admin_status){
                    $msg = self::ReplaceDefaults($hookInfo->admin_type_send, $hookInfo->send_for, $hookInfo->admin_pattern, $replaces, $uid, $aid);
                    $res = self::SendSmsByPattern([
                        'pattern_id' => $hookInfo->admin_pattern_id,
                        'mobiles' => explode(',', $hookInfo->admin_numbers),
                        'message' => $msg,
                        'form_number' => self::GS('pattern_number'),
                        'hook' => $hookname,
                        'type_send' => $hookInfo->type_send,
                        'sendfor' => 'admin'
                    ]);
                    Capsule::table('smsir_vo_hooks_logs')->insert([
                        'hook' => $hookname,
                        'type_send' => $hookInfo->admin_type_send,
                        'send_for' => 'admin',
                        'uid' => $uid,
                        'mobile' => self::utell($uid),
                        'message' => json_encode(['patternID' => $hookInfo->admin_pattern_id, 'message' => $msg]),
                        'result' => $res,
                        'created_at' => hdata::ShowDate(date('Y-m-d H:i:s'))
                    ]);
                }
            }
        }

    }



    public static function installerHooks(){
        $install_name = '';
        $FileUrl = self::ADDONURL('actions/notifs/');
        if ($handle = opendir($FileUrl)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $ext = pathinfo($entry, PATHINFO_EXTENSION);
                    $EditFileName = strtr($entry, ['.'.$ext => '']);
                    $explode = explode('_', $EditFileName);
                    $fileName = $EditFileName;
                    if($ext == 'php'){
                        $checkFile = false;
                        $functionName = 'smsir_vo_'.$fileName.'_install';
                        try{
                            $checkFile = Capsule::table('smsir_vo_hooks')->where("name", $fileName)->value('ID');
                        }catch(Exception $e){}
                        if(self::EoN($checkFile)){
                            if(!function_exists($functionName)){
                                include_once($FileUrl . $entry);
                            }
                            if(function_exists($functionName)){
                                $functionName($fileName);
                                if($explode[0] != 'InvoicePaymentReminder'){
                                    $install_name .= $fileName . '<br/>';
                                }
                            }
                        }

                    }
                }
            }
            closedir($handle);
        }
        return $install_name;
    }

}

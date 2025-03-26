<?php
/*
 * Copyright (c) 2023-12-20
 * This file created by VahabOnline MVC Software
 * Author : Vahab Sydi
 * Email : myVahab@gmail.com
 * Website : https://vahabonline.ir && https://ivahab.ir
 * Instagram : https://instagram.com/vahab.dev
 * Telegram : https://t.me/vahabdev
*/
namespace WHMCS\Module\Addon\smsir\vahabonline;
use Illuminate\Database\Capsule\Manager as Capsule;



class verify
{

    public static function checkUser($uid=''){
        if(vahab::EoN($uid)){
            return 0;
        }
        $userMobile = vahab::utell($uid);
        $status = 0;
        try {
            $status = Capsule::table('smsir_vo_verifications_users')
                ->where("userid", $uid)
                ->where("phone_number", $userMobile)
                ->first('verified');
            if(is_null($status)){
                $status = 0;
            }
        }catch (\Exception $e){}

        return $status;
    }


    public static function toLockPage(){
        vahab::toUrl('index.php?m=smsir&action=verify');
    }

    public static function post($data) {
        return htmlspecialchars($_POST[$data], ENT_QUOTES, 'UTF-8');
    }

    public static function tblVerifyCheck($uid){
        try {
            return Capsule::table('smsir_vo_verifications_users')
                ->where("userid", $uid)
                ->first();
        } catch (Exception $e) {}
        return false;
    }

    public static function SendSms($uid,$reqid, $randcode=''){
        $utell = vahab::utell($uid);
        if(vahab::GS('verify_typesend') == 'default'){
            $msg = vahab::UserMessageDefaultShortCodes(vahab::GS('verify_msg'), $uid);
            $msg = strtr($msg, ['{code}' => $randcode]);
            $res = vahab::SendSmsByMessage([
                'mobiles' => [$utell],
                'message' => $msg,
                'form_number' => vahab::GS('default_number')
            ]);
            try {
                Capsule::table('smsir_vo_verifications_smslogs')->insert([
                    'userid' => $uid,
                    'req_id' => $reqid,
                    'sent_to' => $utell,
                    'message' => $msg,
                    'sent_at' => date('Y-m-d H:i:s'),
                    'result' => $res,
                    'type_send' => 'default',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch (Exception $e) {}
        }
        if(vahab::GS('verify_typesend') == 'pattern'){
            $msg = vahab::UserMessagePatternShortCodes(vahab::GS('verify_pattern'));
            $msg = strtr($msg, ['{code}' => $randcode]);
            $pattern_id = vahab::GS('verify_patternID');
            $res = vahab::SendSmsByPattern([
                'mobiles' => [$utell],
                'pattern_id' => $pattern_id,
                'message' => $msg,
                'form_number' => vahab::GS('pattern_number'),
            ]);
            try {
                Capsule::table('smsir_vo_verifications_smslogs')->insert([
                    'userid' => $uid,
                    'req_id' => $reqid,
                    'sent_to' => $utell,
                    'pattern_id' => $pattern_id,
                    'message' => $msg,
                    'sent_at' => date('Y-m-d H:i:s'),
                    'result' => $res,
                    'type_send' => 'pattern',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch (Exception $e) {}
        }
    }

    public static function addattamp($uid,$usrCode,$userphone, $status = 0){
        try {
            Capsule::table('smsir_vo_verifications_attempts')->insert([
                'req_id' => $_SESSION['smsir_vo']['verify']['req_id'],
                'user_id' => $uid,
                'attempted_at' => date('Y-m-d H:i:s'),
                'attempt_status' => $status,
                'attempt_code' => $usrCode,
                'phonenumber' => $userphone,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {}
    }

}


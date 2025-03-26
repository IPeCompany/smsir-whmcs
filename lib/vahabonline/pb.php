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

class pb
{
    public static function post($data) {
        return htmlspecialchars($_POST[$data], ENT_QUOTES, 'UTF-8');
    }

    public static function pb_name($ID){
        try{
            return Capsule::table('smsir_vo_phonebooks')->where('ID', $ID)->value('name');
        }catch (\Exception $e){}
    }

    public static function pb_uinfo($ID){
        try{
            return Capsule::table('smsir_vo_phone')->where('ID', $ID)->first();
        }catch (\Exception $e){}
    }

    public static function CountPbNumbers($ID){
        try{
            return Capsule::table('smsir_vo_phone')->where('pb_id', $ID)->count();
        }catch (\Exception $e){}
        return 0;
    }

    public static function GetAllPb(){
        try{
            return Capsule::table('smsir_vo_phonebooks')->orderBy('ID', 'Desc')->get();
        }catch (\Exception $e){}
    }
    public static function GetAllNumberOfPb($id){
        try{
            $q = Capsule::table('smsir_vo_phone');
            $q->where('pb_id', $id);
            $q->orderBy('ID', 'Desc');
            return $q->get();
        }catch (\Exception $e){}
    }

    public static function GetAllNumsOffset($id,$limit='',$pgn=''){
        $count = self::CountPbNumbers($id);
        try{
            $q = Capsule::table('smsir_vo_phone');
            $q->where('pb_id', $id);
            if(!vahab::EoN($limit)){
                $pgn = ($pgn-1);
                $offset = ($pgn*$limit);
                $q->offset($offset);
                $q->limit($limit);
            }
            $q->orderBy('ID', 'DESC');
            return $q->get();
        }catch (\Exception $e){}
    }

    public static function Replace($id, $msg){
        $uinfo = self::pb_uinfo($id);
        $newMsg = strtr($msg, [
            '{firstname}' => $uinfo->firstname,
            '{lastname}' => $uinfo->lastname,
            '{email}' => $uinfo->email,
            '{adress}' => $uinfo->adress,
            '{tell}' => $uinfo->tell,
            '{mobile}' => $uinfo->mobile,
            '#' => ''
        ]);
        return $newMsg;
    }

    public static function timeAndsend($timesend){
        $params = array();
        if($timesend == '5sec'){
            $params['time'] = 5;
            $params['send'] = 10;
        }
        if($timesend == '10sec'){
            $params['time'] = 10;
            $params['send'] = 15;
        }
        if($timesend == '15sec'){
            $params['time'] = 15;
            $params['send'] = 20;
        }
        if($timesend == '20sec'){
            $params['time'] = 20;
            $params['send'] = 30;
        }
        if($timesend == '25sec'){
            $params['time'] = 25;
            $params['send'] = 40;
        }
        if($timesend == '30sec'){
            $params['time'] = 30;
            $params['send'] = 50;
        }
        return $params;
    }

    public static function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }


    public static function addlog_bulkpb($tell,$txt,$res, $showlog=false){
        try{
            Capsule::table('smsir_vo_pb_bulklog')
                ->insert([
                    'phonenumber' => $tell,
                    'text' => $txt,
                    'send_at' => date('Y-m-d H:i:s'),
                    'result' => $res
                ]);
            if($showlog === true){
                return "<p>ارسال به شماره {$tell} با کد پیگیری {$res} انجام شد</p>";
            }
            return true;
        }catch (\Exception $e){}
        return false;
    }

}
<?php

namespace WHMCS\Module\Addon\smsir\vahabonline;
use Illuminate\Database\Capsule\Manager as Capsule;

class sfu
{

    public static function Langs(){
        try{
            return Capsule::table('tblclients')->groupBy('language')->select('language')->get();
        }catch (\Exception $e){}
        return false;
        //return $GLOBALS['_LANG']['idnLanguage'];
    }

    public static function countries(){
        try{
            return Capsule::table('tblclients')->groupBy('country')->select('country')->get();
        }catch (\Exception $e){}
        return false;
    }

    public static function currency(){
        try{
            return Capsule::table('tblclients')
                ->join('tblcurrencies', 'tblclients.currency', '=', 'tblcurrencies.id')
                ->groupBy('tblclients.currency')
                ->select('tblclients.currency','tblcurrencies.code')
                ->get();
        }catch (\Exception $e){}
        return false;
    }

    public static function listpros(){
        try{
            return Capsule::table('tblhosting')
                ->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')
                ->groupBy('tblhosting.packageid')
                ->select('tblproducts.name','tblproducts.id')
                ->get();
        }catch (\Exception $e){}
        return false;
    }

    public static function prostatus(){
        try{
            return Capsule::table('tblhosting')
                ->groupBy('domainstatus')
                ->select('domainstatus')
                ->get();
        }catch (\Exception $e){}
        return false;
    }

    public static function probillingcycle(){
        try{
            return Capsule::table('tblhosting')
                ->groupBy('billingcycle')
                ->select('billingcycle')
                ->get();
        }catch (\Exception $e){}
        return false;
    }

    public static function domainlist(){
        try{
            return Capsule::table('tbldomainpricing')
                ->groupBy('extension')
                ->select('extension')
                ->get();
        }catch (\Exception $e){}
        return false;
    }

    public static function domainType(){
        try{
            return Capsule::table('tbldomains')
                ->groupBy('type')
                ->select('type')
                ->get();
        }catch (\Exception $e){}
        return false;
    }
    public static function domainRegisterarer(){
        try{
            return Capsule::table('tbldomains')
                ->groupBy('registrar')
                ->select('registrar')
                ->get();
        }catch (\Exception $e){}
        return false;
    }
    public static function domainStatus(){
        try{
            return Capsule::table('tbldomains')
                ->groupBy('status')
                ->select('status')
                ->get();
        }catch (\Exception $e){}
        return false;
    }


    public static function addlog($uid, $phone, $txt, $res){
        try{
            Capsule::table('smsir_vo_usr_log')->insert([
                'userid' => $uid,
                'phonenumber' => $phone,
                'text' => $txt,
                'send_at' => date('Y-m-d H:i:s'),
                'result' => $res
            ]);
            return true;
        }catch (\Exception $e){}
        return false;
    }

}
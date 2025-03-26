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



class hdata
{

    public static function quotes ($id){
        if(vahab::EoN($id)){
            return false;
        }
        try {
            return Capsule::table('tblquotes')->where("id", $id)->first();
        }catch (\Exception $e){}
        return false;
    }

    public static function tbladmins ($id){
        if(vahab::EoN($id)){
            return false;
        }
        try {
            return Capsule::table('tbladmins')->where("id", $id)->first();
        }catch (\Exception $e){}
        return false;
    }

    public static function orderdata ($id){
        if(vahab::EoN($id)){
            return false;
        }
        try {
            return Capsule::table('tblorders')->where("id", $id)->first();
        }catch (\Exception $e){}
        return false;
    }


    public static function userinfo($id){
        $data = vahab::uinfo($id);
        if(!vahab::EoN($data)){
            return $data;
        }
        return false;
    }



    /**
     * Get Domain info
     */
    public static function dinfo($id)
    {
        try {
            return Capsule::table('tbldomains')->where("id", $id)->first();
        }catch (\Exception $e){}
        return null;
    }

    public static function progroup($id='')
    {
        if(vahab::EoN($id)){
            return null;
        }
        try {
            return Capsule::table('tblproductgroups')->where("id", $id)->first();
        }catch (\Exception $e){}
        return null;
    }

    /**
     * Get all data of services
     */
    public static function sinfo($sid='')
    {
        $sinfo = self::tblhosting($sid);
        if(vahab::EoN($sinfo)){
            return null;
        }
        $pid = $sinfo->packageid;
        $uid = $sinfo->userid;
        $pinfo = self::pinfo($pid);
        $pgroup = self::progroup($pinfo->gid);
        return (object) array(
            'uid' => $uid,
            'pid' => $pid,
            'sid' => $sid,
            'server' => $sinfo->server,
            'regdate' => $sinfo->regdate,
            'domain' => $sinfo->domain,
            'firstpaymentamount' => $sinfo->firstpaymentamount,
            'amount' => $sinfo->amount,
            'billingcycle' => $sinfo->billingcycle,
            'nextduedate' => $sinfo->nextduedate,
            'termination_date' => $sinfo->termination_date,
            'domainstatus' => $sinfo->domainstatus,
            'username' => $sinfo->username,
            'dedicatedip' => $sinfo->dedicatedip,
            'assignedips' => $sinfo->assignedips,
            'ns1' => $sinfo->ns1,
            'ns2' => $sinfo->ns2,
            'PortNumber' => $sinfo->PortNumber,
            'gid' => $pinfo->gid,
            'p_type' => $pinfo->type,
            'p_name' => $pinfo->name,
            'p_slug' => $pinfo->slug,
            'p_description' => $pinfo->description,
            'g_name' => $pgroup->name,
            'g_slug' => $pgroup->slug,
            'g_headline' => $pgroup->headline,
            'g_tagline' => $pgroup->tagline,
        );
    }

    /**
     * tblproduct info
     */
    public static function pinfo($pid='')
    {
        if(vahab::EoN($pid)){
            return null;
        }
        try {
            return Capsule::table('tblproducts')->where("id", $pid)->first();
        }catch (\Exception $e){}
        return null;
    }


    /**
     * tblinvoice info
     */
    public static function invoice($invid='')
    {
        if(vahab::EoN($invid)){
            return null;
        }
        try {
            return Capsule::table('tblinvoices')->where("id", $invid)->first();
        }catch (\Exception $e){}
        return null;
    }

    /**
     * tblhosting info
     */
    public static function tblhosting($hid='')
    {
        if(vahab::EoN($hid)){
            return null;
        }
        try {
            return Capsule::table('tblhosting')->where("id", $hid)->first();
        }catch (\Exception $e){}
        return null;
    }

    public static function contact($id='')
    {
        if(vahab::EoN($id)){
            return null;
        }
        try {
            return Capsule::table('tblcontacts')->where("id", $id)->first();
        }catch (\Exception $e){}
        return null;
    }

    public static function domains($id='')
    {
        if(vahab::EoN($id)){
            return null;
        }
        try {
            return Capsule::table('tbldomains')->where("id", $id)->first();
        }catch (\Exception $e){}
        return null;
    }

    public static function addons($id='')
    {
        if(vahab::EoN($id)){
            return null;
        }
        try {
            return Capsule::table('tbladdons')->where("id", $id)->first();
        }catch (\Exception $e){}
        return null;
    }

    public static function tickets($id='')
    {
        if(vahab::EoN($id)){
            return null;
        }
        try {
            return Capsule::table('tbltickets')
                ->where("tbltickets.id", $id)
                ->join('tblticketdepartments', 'tbltickets.did', '=', 'tblticketdepartments.id')
                ->select(
                    'tbltickets.id as ticketid',
                    'tblticketdepartments.name as deptname',
                    'tbltickets.title as subject',
                    'tbltickets.userid as userid',
                    'tbltickets.lastreply as lastreply',
                    'tbltickets.urgency as urgency',
                    'tbltickets.status as status',
                    'tbltickets.message as message',
                    'tbltickets.date as date',
                    'tbltickets.lastreply as lastreply',
                    'tbltickets.tid as tid',
                )
                ->first();
        }catch (\Exception $e){}
        return null;
    }


    public static function Lang($text=''){
        $langFile = vahab::ADDONURL('/lang/farsi.php');
        $fullTxt =$text;
        if(file_exists($langFile)){
            include($langFile);
        }else{
            return $fullTxt;
        }
        $fullTxt = strtr($fullTxt, [' ' => '']);
        $fullTxt = strtolower($fullTxt);
        $fa = $_lang_vahab[$fullTxt];
        if(vahab::EoN($fa)){
            return $fullTxt;
        }
        return $fa;
    }


    public static function ShowDate($date){
        return $date;
    }


}

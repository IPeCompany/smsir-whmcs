<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result</title>
    <style>
        body{
            direction: rtl;
            font-family: tahoma;
            font-size: 13px;
            line-height: 10px;
        }
    </style>
</head>
<body>
<?php
include '../../../../../init.php';
use WHMCS\Module\Addon\smsir\vahabonline\pb;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;
use WHMCS\Module\Addon\smsir\vahabonline\sfu;
use Illuminate\Database\Capsule\Manager as Capsule;



if(vahab::EoN($_SESSION['adminid'])){
    die();
}

$token = vahab::GS('BulksecKey');
if($_REQUEST['voToken'] != $token){
    die();
}


if(vahab::EoN($token)){
    die();
}
echo '<p>ارسال شده با توکن : ' . $token . '</p>';

$Key = $_REQUEST['key'];
$POSTDATA = json_decode(base64_decode($Key), true);


    $token = $_REQUEST['voToken'];
    $msg = $POSTDATA['message'];
    $pattern_id = $POSTDATA['pattern_id'];
    $pname = $POSTDATA['pname'];
    $type_send = $POSTDATA['type_send'];
    $pvalue = $POSTDATA['pvalue'];
    $timesend = $POSTDATA['timesend'];
    $nowpage = 1;
    if(!empty($_REQUEST['page'])){
        $nowpage = $_REQUEST['page'];
    }
    $newpage = ($nowpage+1);
    $time = null;
    $newUrl = null;
    $url = vahab::SiteUrl('/modules/addons/smsir/actions/sendings/foruser.php?voToken='.$token.'&key=' . $Key . '&');

    if($POSTDATA['sendFor'] == 'users'){
        $result = null;
        try {
            $query = Capsule::table('tblclients');
            if(!vahab::EoN($POSTDATA['user']['status'])){
                $query->whereIn('status', $POSTDATA['user']['status']);
            }
            if(!vahab::EoN($POSTDATA['user']['lang'])){
                $query->whereIn('language', $POSTDATA['user']['lang']);
            }
            if(!vahab::EoN($POSTDATA['user']['countries'])){
                $query->whereIn('country', $POSTDATA['user']['countries']);
            }
            if(!vahab::EoN($POSTDATA['user']['currency'])){
                $query->whereIn('currency', $POSTDATA['user']['currency']);
            }
            $query->orderBy('id', 'desc');
            $query->select('id as userid');


            $count = $query->count();
            $time = pb::timeAndsend($timesend);
            if(!vahab::EoN($time)){
                $limit = $time['send'];
                $pgn = ($nowpage-1);
                $offset = ($pgn*$limit);
                $query->offset($offset);
                $query->limit($limit);
                $paramN['page'] = ($nowpage+1);
                $maxPage = ceil($count/$time['send']);
                $newUrl = $url . http_build_query($paramN);
            }
            $result = $query->get();


        } catch (Exception $e) {}
        $params = $result;
    }

    if($POSTDATA['sendFor'] == 'service'){
        $result = null;
        try {
            $query = Capsule::table('tblhosting');
            $query->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id');
            if(!vahab::EoN($POSTDATA['product']['list'])){
                $query->whereIn('tblhosting.packageid', $POSTDATA['product']['list']);
            }
            if(!vahab::EoN($POSTDATA['product']['status'])){
                $query->whereIn('tblhosting.domainstatus', $POSTDATA['product']['status']);
            }
            if(!vahab::EoN($POSTDATA['product']['cycle'])){
                $query->whereIn('tblhosting.billingcycle', $POSTDATA['product']['cycle']);
            }
            $query->select([
                'tblhosting.userid',
                'tblproducts.name' ,
                'tblhosting.regdate' ,
                'tblhosting.domain',
                'tblhosting.amount' ,
                'tblhosting.firstpaymentamount',
                'tblhosting.billingcycle',
                'tblhosting.nextduedate' ,
                'tblhosting.nextinvoicedate',
                'tblhosting.termination_date' ,
                'tblhosting.domainstatus' ,
                'tblhosting.username',
                'tblhosting.dedicatedip' ,
                'tblhosting.ns1' ,
                'tblhosting.ns2'
            ]);
            $query->orderBy('tblhosting.id', 'desc');

            $count = $query->count();
            $time = pb::timeAndsend($timesend);
            if(!vahab::EoN($time)){
                $limit = $time['send'];
                $pgn = ($nowpage-1);
                $offset = ($pgn*$limit);
                $query->offset($offset);
                $query->limit($limit);
                $paramN['page'] = ($nowpage+1);
                $maxPage = ceil($count/$time['send']);
                $newUrl = $url . http_build_query($paramN);
            }

            $result = $query->get();
        } catch (Exception $e) {}

        foreach ($result as $key => $sdata){
            $params[$key]['userid'] = $sdata->userid;
            $params[$key]['name'] = $sdata->name;
            $params[$key]['regdate'] = $sdata->regdate;
            $params[$key]['domain'] = $sdata->domain;
            $params[$key]['amount'] = $sdata->amount;
            $params[$key]['firstpaymentamount'] = $sdata->firstpaymentamount;
            $params[$key]['billingcycle'] = $sdata->billingcycle;
            $params[$key]['nextduedate'] = $sdata->nextduedate;
            $params[$key]['nextinvoicedate'] = $sdata->nextinvoicedate;
            $params[$key]['termination_date'] = $sdata->termination_date;
            $params[$key]['domainstatus'] = $sdata->domainstatus;
            $params[$key]['username'] = $sdata->username;
            $params[$key]['dedicatedip'] = $sdata->dedicatedip;
            $params[$key]['ns1'] = $sdata->ns1;
            $params[$key]['ns2'] = $sdata->ns2;
        }
    }

    if($POSTDATA['sendFor'] == 'domains'){
        $result = null;
        try {
            $query = Capsule::table('tbldomains');
            if(!vahab::EoN($POSTDATA['domain']['ext'])){
                foreach ($POSTDATA['domain']['ext'] as $key => $ext){
                    if($key == 0){
                        $query->where('domain', 'like', '%.' . $ext . '%');
                    }else{
                        $query->orWhere('domain', 'like', '%.' . $ext . '%');
                    }
                }
            }
            if(!vahab::EoN($POSTDATA['domain']['type'])){
                $query->whereIn('type', $POSTDATA['domain']['type']);
            }
            if(!vahab::EoN($POSTDATA['domain']['registrar'])){
                $query->whereIn('registrar', $POSTDATA['domain']['registrar']);
            }
            if(!vahab::EoN($POSTDATA['domain']['status'])){
                $query->whereIn('status', $POSTDATA['domain']['status']);
            }
            $query->orderBy('id', 'desc');

            $count = $query->count();
            $time = pb::timeAndsend($timesend);
            if(!vahab::EoN($time)){
                $limit = $time['send'];
                $pgn = ($nowpage-1);
                $offset = ($pgn*$limit);
                $query->offset($offset);
                $query->limit($limit);
                $paramN['page'] = ($nowpage+1);
                $maxPage = ceil($count/$time['send']);
                $newUrl = $url . http_build_query($paramN);
            }
            $result = $query->get();
        } catch (Exception $e) {}
    }


    echo '<div style="position: fixed;left: 0px;top: 0px;background: #F44336;padding: 10px 15px 12px;border-radius: 0 0px 6px 0;color: #fff;">صفحه '.$nowpage.' از '.$maxPage.'</div>';
    echo '<div style="position: fixed;bottom: 0px;left: 40%;background: blue;color: #fff;padding: 10px 15px;border-radius: 6px 6px 0 0;">زمان ارسال جدید '.$time['time'].' ثانیه می باشد</div>';

    if($type_send == 'default'){
        foreach ($result as $item){
            $NewReplace = [
                '{proname}' => $item->name,
                '{regdate}' => $item->regdate,
                '{domain}' => $item->domain,
                '{amount}' => number_format($item->amount),
                '{firstpaymentamount}' => number_format($item->firstpaymentamount),
                '{recurringamount}' => number_format($item->recurringamount),
                '{billingcycle}' => hdata::Lang($item->billingcycle),
                '{registrationdate}' => hdata::Lang($item->registrationdate),
                '{nextduedate}' => hdata::ShowDate($item->nextduedate),
                '{nextinvoicedate}' => hdata::ShowDate($item->nextinvoicedate),
                '{termination_date}' => hdata::ShowDate($item->termination_date),
                '{expirydate}' => hdata::ShowDate($item->expirydate),
                '{domainstatus}' => hdata::Lang($item->domainstatus),
                '{username}' => $item->username,
                '{dedicatedip}' => $item->dedicatedip,
                '{ns1}' => $item->ns1,
                '{ns2}' => $item->ns2,
                '{registrar}' => $item->registrar,
                '{registrationperiod}' => $item->registrationperiod,
                '{status}' => $item->status,
            ];
            $newMsg = strtr($POSTDATA['message'], $NewReplace);
            $Msg = vahab::UserMessageDefaultShortCodes($newMsg, $item->userid);
            $mobile = vahab::utell($item->userid);
            $res = vahab::SendSmsByMessage([
                'mobiles' => [$mobile],
                'message' => $Msg,
                'form_number' => vahab::GS('default_number'),
                'type_send' => $type_send
            ]);
            echo '<p style="">تعداد 1 پیامک با شناسه '.$res.' موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
            sfu::addlog($item->userid, $mobile, json_encode([
                'message' => $Msg
            ]), $res);
        }
    }


    if($type_send == 'pattern'){



        foreach ($result as $item){
            $NewReplace = [
                '{proname}' => $item->name,
                '{regdate}' => $item->regdate,
                '{domain}' => $item->domain,
                '{amount}' => number_format($item->amount),
                '{firstpaymentamount}' => number_format($item->firstpaymentamount),
                '{recurringamount}' => number_format($item->recurringamount),
                '{billingcycle}' => hdata::Lang($item->billingcycle),
                '{registrationdate}' => hdata::Lang($item->registrationdate),
                '{nextduedate}' => hdata::ShowDate($item->nextduedate),
                '{nextinvoicedate}' => hdata::ShowDate($item->nextinvoicedate),
                '{termination_date}' => hdata::ShowDate($item->termination_date),
                '{expirydate}' => hdata::ShowDate($item->expirydate),
                '{domainstatus}' => hdata::Lang($item->domainstatus),
                '{username}' => $item->username,
                '{dedicatedip}' => $item->dedicatedip,
                '{ns1}' => $item->ns1,
                '{ns2}' => $item->ns2,
                '{registrar}' => $item->registrar,
                '{registrationperiod}' => $item->registrationperiod,
                '{status}' => $item->status,
            ];
            $newMsg = strtr(json_encode($POSTDATA['pattern_values']), $NewReplace);
            $Msg = vahab::UserMessageDefaultShortCodes($newMsg, $item->userid);
            $mobile = vahab::utell($item->userid);
            $res = vahab::SendSmsByPattern([
                'mobiles' => [$mobile],
                'pattern_id' => $POSTDATA['pattern_id'],
                'message' => $Msg
            ]);
            echo '<p style="">تعداد 1 پیامک با شناسه '.$res.' موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
            sfu::addlog($item->userid, $mobile, json_encode([
                    'message' => $Msg,
                    'pattern_id' => $POSTDATA['pattern_id']
            ]), $res);
        }
    }


    if($timesend == 'complate' || !vahab::EoN($maxPage) && $nowpage >= $maxPage){
        vahab::SetS('BulksecKey', pb::randomPassword());
        echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد ' . $count . ' پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
    }


    if(!vahab::EoN($time) && !vahab::EoN($maxPage) && $nowpage < $maxPage){
        echo '<script type="text/javascript">   
                    function Redirect() {  window.location="'.$newUrl.'"; } 
                    setTimeout("Redirect()", '.$time['time'].'000);   
                </script>';
        die();
    }



    if(vahab::EoN(vahab::GS('BulksecKey'))){
        vahab::SetS('BulksecKey', pb::randomPassword());
    }
?>



</body>
</html>


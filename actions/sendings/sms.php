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
$Data = json_decode(base64_decode($Key), true);


$pb_id = $Data['pb_send'];
$testnumber = $Data['testnumber'];
$type_send = $Data['type_send'];
$pattern_id = $Data['pattern_id'];
$send = $Data['send'];
$timesend = $Data['timesend'];
$message = $Data['message'];

$paramN['key'] = $Key;
$paramN['page'] = $_REQUEST['page'];



if($type_send == 'default'){

    //test ersal adi
    if($send == 'test'){
        $res = vahab::SendSmsByMessage([
            'mobiles' => [$testnumber],
            'message' => $message,
            'form_number' => vahab::GS('default_number'),
            'type_send' => $type_send
        ]);
        echo pb::addlog_bulkpb($testnumber, $message, $res, true);
        echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد 1 پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
        vahab::SetS('BulksecKey', pb::randomPassword());
    }else{

        $count = pb::CountPbNumbers($pb_id);
        //ersal adi hame
        if($timesend == 'complate'){
            foreach (pb::GetAllNumberOfPb($pb_id) as $item){
                $Msg = pb::Replace($item->ID, $message);
                $res = vahab::SendSmsByMessage([
                    'mobiles' => [$item->mobile],
                    'message' => $Msg,
                    'form_number' => vahab::GS('default_number'),
                    'type_send' => $type_send
                ]);
                echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد ' . $count . ' پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
                echo pb::addlog_bulkpb($item->mobile, $Msg, $res, true);
                vahab::SetS('BulksecKey', pb::randomPassword());
            }
        }else{


            $nowpage = 1;
            if(!empty($paramN['page'])){
                $nowpage = $paramN['page'];
            }
            $time = pb::timeAndsend($timesend);
            $list = pb::GetAllNumsOffset($pb_id, $time['send'], $nowpage);
            $count = pb::CountPbNumbers($pb_id);
            $maxPage = ceil($count/$time['send']);

            foreach ($list as $item){
                $Msg = pb::Replace($item->ID, $message);
                $res = vahab::SendSmsByMessage([
                    'mobiles' => [$item->mobile],
                    'message' => $Msg,
                    'form_number' => vahab::GS('default_number'),
                    'type_send' => $type_send
                ]);
                echo pb::addlog_bulkpb($item->mobile, $Msg, $res, true);
            }

            $url = vahab::SiteUrl('/modules/addons/smsir/actions/sendings/sms.php?voToken='.$token.'&key=' . $Key . '&');
            $nowurl = $url . $_SERVER['QUERY_STRING'];


            echo '<div style="position: fixed;left: 0px;top: 0px;background: #F44336;padding: 10px 15px 12px;border-radius: 0 0px 6px 0;color: #fff;">صفحه '.$nowpage.' از '.$maxPage.'</div>';
            echo '<div style="position: fixed;bottom: 0px;left: 40%;background: blue;color: #fff;padding: 10px 15px;border-radius: 6px 6px 0 0;">زمان ارسال جدید '.$time['time'].' ثانیه می باشد</div>';


            if($paramN['page'] >= $maxPage){
                $paramN['page'] = $maxPage;
                vahab::SetS('BulksecKey', pb::randomPassword());
                echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد ' . $count . ' پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';

            }else{
                $paramN['page'] = ($nowpage+1);
                $newUrl = $url . http_build_query($paramN);
                echo '<script type="text/javascript">   
                    function Redirect() {  window.location="'.$newUrl.'"; } 
                    setTimeout("Redirect()", '.$time['time'].'000);   
                </script>';
            }


        }
    }



}




if($type_send == 'pattern'){



    $patternTxt = $Data['pattern'];
    $patternID = $Data['pattern_id'];




    //test ersal pattern
    if($send == 'test'){

        $res = vahab::SendSmsByPattern([
            'mobiles' => [$testnumber],
            'pattern_id' => $patternID,
            'message' => json_encode($patternTxt)
        ]);
        echo pb::addlog_bulkpb($testnumber, json_encode([
            'message' => $patternTxt,
            'pattern_id' => $patternID
        ]), $res, true);
        echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد 1 پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
        vahab::SetS('BulksecKey', pb::randomPassword());
    }else{

        $count = pb::CountPbNumbers($pb_id);
        //ersal adi hame
        if($timesend == 'complate'){
            foreach (pb::GetAllNumberOfPb($pb_id) as $item){
                $Msg = pb::Replace($item->ID, json_encode($patternTxt));
                $res = vahab::SendSmsByPattern([
                    'mobiles' => [$item->mobile],
                    'pattern_id' => $patternID,
                    'message' => $Msg
                ]);
                echo pb::addlog_bulkpb($item->mobile, json_encode([
                        'pattern_id' => $patternID,
                        'msg' => $Msg,
                ]), $res, true);
            }
            vahab::SetS('BulksecKey', pb::randomPassword());
            echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد ' . $count . ' پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';
        }
        else
        {


            $nowpage = 1;
            if(!empty($paramN['page'])){
                $nowpage = $paramN['page'];
            }
            $time = pb::timeAndsend($timesend);
            $list = pb::GetAllNumsOffset($pb_id, $time['send'], $nowpage);
            $maxPage = ceil($count/$time['send']);

            foreach ($list as $item){
                $Msg = pb::Replace($item->ID, json_encode($patternTxt));
                $res = vahab::SendSmsByPattern([
                    'mobiles' => [$item->mobile],
                    'pattern_id' => $patternID,
                    'message' => $Msg
                ]);
                echo pb::addlog_bulkpb($item->mobile, json_encode([
                    'pattern_id' => $patternID,
                    'msg' => $Msg,
                ]), $res, true);
            }

            $url = vahab::SiteUrl('/modules/addons/smsir/actions/sendings/sms.php?voToken='.$token.'&key=' . $Key . '&');
            $nowurl = $url . $_SERVER['QUERY_STRING'];


            echo '<div style="position: fixed;left: 0px;top: 0px;background: #F44336;padding: 10px 15px 12px;border-radius: 0 0px 6px 0;color: #fff;">صفحه '.$nowpage.' از '.$maxPage.'</div>';
            echo '<div style="position: fixed;bottom: 0px;left: 40%;background: blue;color: #fff;padding: 10px 15px;border-radius: 6px 6px 0 0;">زمان ارسال جدید '.$time['time'].' ثانیه می باشد</div>';


            if($paramN['page'] >= $maxPage){
                $paramN['page'] = $maxPage;

                vahab::SetS('BulksecKey', pb::randomPassword());
                echo '<p style="background: #4CAF50;color: #fff;padding: 40px 15px;text-align: center;position: fixed;left: 0px;right: 0px;width: 100%;top: 0px;height: 100%;margin: 0px;">تعداد ' . $count . ' پیامک با موفقیت ارسال شد . برای مشاهده نتایج به بخش گزارش ارسال مراجعه نمایید </p>';

            }else{
                $paramN['page'] = ($nowpage+1);
                $newUrl = $url . http_build_query($paramN);
                echo '<script type="text/javascript">   
                    function Redirect() {  window.location="'.$newUrl.'"; } 
                    setTimeout("Redirect()", '.$time['time'].'000);   
                </script>';
            }


        }
    }

}



//remove random code
if(vahab::EoN($maxPage)){
    vahab::SetS('BulksecKey', pb::randomPassword());
}


?>



</body>
</html>


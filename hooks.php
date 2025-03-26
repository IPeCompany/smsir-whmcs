<?php
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\verify;
use Illuminate\Database\Capsule\Manager as Capsule;


//for sms
foreach (vahab::AllHooks() as $vhook){
    //اگر فایل هوک بود
    $hookFileUrl = vahab::$ADDONURL . 'actions/notifs/' . $vhook->name . '.php';
    if(file_exists($hookFileUrl)){
        $functionName = 'smsir_vo_' . $vhook->name;
        if(!function_exists($functionName)){
            include($hookFileUrl);
        }
        add_hook($vhook->name, 1, $functionName);
    }
}


//for verify
add_hook('ClientAreaPage', 1, function($params) {
    if($params['loggedin']){
        $uid = $_SESSION['uid'];
        $theme = $params['templatefile'];

        if(vahab::GS('verify_status') == 'diabled'){
            return null;
        }
        if(!in_array($theme, json_decode(vahab::GS('verify_lockpages')))){
            return null;
        }


        if(!verify::checkUser($uid)->verified){
            verify::toLockPage();
        }
    }
});


add_hook('AdminAreaClientSummaryPage', 1, function($vars) {
    $userid = $_GET['userid'];
    $check = false;
    try {
        $udata = Capsule::table('smsir_vo_verifications_users')->where("userid", $userid)->first();
        $check = $udata->verified;
    }catch (\Exception $e){}

    $alert_msg = 'شماره کاربر تایید نشده است';
    $alert_status = 'warning';
    $btn_more = '<a href="addonmodules.php?module=smsir&action=addusermobile&id='.$userid.'" class="btn btn-info btn-sm btn-xs">تعیین تکلیف</a>';
    $usr_mobile = vahab::utell($userid);

    if($check === 1){
        $btn_more = '<a href="addonmodules.php?module=smsir&action=mngverify&id='.$udata->ID.'" class="btn btn-info btn-sm btn-xs">تغییر وضعیت</a>';
        $usr_mobile = $udata->phone_number;
        $alert_msg = 'شماره کاربر کاربر وریفای شده است';
        $alert_status = 'success';
    }

    return '<div class="alert alert-' . $alert_status . '">'.$alert_msg.' || شماره کاربر : '.$usr_mobile.' || '.$btn_more.'</div>';
});
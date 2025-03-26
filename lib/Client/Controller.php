<?php

namespace WHMCS\Module\Addon\smsir\Client;

use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\verify;

/**
 * Sample Client Area Controller
 */
class Controller {


    public function verify($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. smsirs.php?module=smsir
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables

        // Get module configuration parameters
        $configTextField = $vars['Text Field Name'];
        $configPasswordField = $vars['Password Field Name'];
        $configCheckboxField = $vars['Checkbox Field Name'];
        $configDropdownField = $vars['Dropdown Field Name'];
        $configRadioField = $vars['Radio Field Name'];
        $configTextareaField = $vars['Textarea Field Name'];


        $uid = $_SESSION['uid'];
        if(empty($_SESSION['smsir_vo']['verify']['steps'])){
            $_SESSION['smsir_vo']['verify']['steps'] = 'start';
        }
        $stepls = $_SESSION['smsir_vo']['verify']['steps'];

        $userphone = vahab::utell($uid);
        $check = verify::tblVerifyCheck($uid);
        $numofinpcode = vahab::GS('verify_attempts');
        $Reminder_Time = ($check->expired_at - time());
        if($Reminder_Time < 0){
            $Reminder_Time = 0;
        }

        if($Reminder_Time == 0){
            $_SESSION['smsir_vo']['verify']['steps'] = 'start';
            $stepls = $_SESSION['smsir_vo']['verify']['steps'];
        }




        if(!$check->verified){
            if(vahab::EoN($check) && $stepls != 'start'){
                $expTime = ($check->expired_at - time());
                if($expTime < 1){
                    $_SESSION['smsir_vo']['verify']['steps'] = 'start';
                    $_SESSION['smsir_vo']['verify']['req_id'] = $check->req_id;
                    vahab::Alert([
                        'class' => 'warning',
                        'message' => 'زمان وریفای کد سابق شما به اتمام رسیده است . میتوانید درخواست جدیدی ثبت نمایید',
                        'url' => 'index.php?m=smsir&action=verify'
                    ]);
                }
            }
        }

        //steps1
        if($stepls == 'start'){
            //check user in first time
            if(!vahab::EoN($check)){
                $expTime = ($check->expired_at - time());
                if($expTime > 1){
                    $_SESSION['smsir_vo']['verify']['steps'] = 'inputcode';
                    $_SESSION['smsir_vo']['verify']['req_id'] = $check->req_id;
                    vahab::toUrl('index.php?m=smsir&action=verify');
                }
            }


            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $randCode = rand(10000,99999);
                $req_id = time();
                $userphone = verify::post('phonenumber') ?: $userphone;


                //age ta hala user sabt nashode bood
                if(vahab::EoN($check)){
                    try {
                        Capsule::table('smsir_vo_verifications_users')
                            ->insert([
                                'req_id' => $req_id,
                                'expired_at' => (time() + vahab::GS('verify_expiretime')),
                                'userid' => $uid,
                                'phone_number' => $userphone,
                                'verification_code' => $randCode,
                                'verified' => 0,
                                'attempts' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                    } catch (Exception $e) {}
                    $_SESSION['smsir_vo']['verify']['steps'] = 'inputcode';
                    $_SESSION['smsir_vo']['verify']['req_id'] = $req_id;
                    verify::SendSms($uid, $req_id, $randCode);

                }else{


                    //age time gozashte bood
                    //miaym kod jadid generate mikonim
                    if($expTime < 1){
                        try {
                            Capsule::table('smsir_vo_verifications_users')
                                ->where('ID' , $check->ID)
                                ->update([
                                    'req_id' => $req_id,
                                    'expired_at' => (time() + vahab::GS('verify_expiretime')),
                                    'phone_number' => $userphone,
                                    'verification_code' => $randCode,
                                    'verified' => 0,
                                    'attempts' => 0,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                        } catch (Exception $e) {}
                        $_SESSION['smsir_vo']['verify']['req_id'] = $req_id;
                        verify::SendSms($uid, $req_id, $randCode);


                    }else{


                        //age code expire nashode bood mifresim baraye verify
                        $_SESSION['smsir_vo']['verify']['steps'] = 'inputcode';
                        $_SESSION['smsir_vo']['verify']['req_id'] = $check->req_id;
                    }

                }
                vahab::toUrl('index.php?m=smsir&action=verify');
            }
        }

        //steps verify
        if($stepls == 'inputcode'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $usrCode = verify::post('yourcode');

                //age empty bood
                if(vahab::EoN($usrCode)){
                    vahab::UAlert([
                        'class' => 'warning',
                        'message' => "وارد نمودن کد الزامی است",
                        'url' => 'index.php?m=smsir&action=verify'
                    ]);
                }

                //age tedad gozashte bood
                if($check->attempts >= $numofinpcode){
                    $_SESSION['smsir_vo']['verify']['steps'] = 'ban';
                    vahab::UAlert([
                        'class' => 'warning',
                        'message' => "تعداد دفعات وارد کردن کد وریفای شما بیش از حد بوده است و تا پایان زمان محدودیت باید منتظر باشید",
                        'url' => 'index.php?m=smsir&action=verify'
                    ]);
                }

                if($usrCode == $check->verification_code){
                    //verify
                    try {
                        Capsule::table('smsir_vo_verifications_users')
                            ->where("ID", $check->ID)
                            ->update([
                                'verified' => 1,
                                'verified_at' => date('Y-m-d H:i:s')
                            ]);
                    }catch (\Exception $e){}
                    $_SESSION['smsir_vo']['verify']['steps'] = 'success';
                    verify::addattamp($uid,$usrCode,$userphone, 1);
                    vahab::UAlert([
                        'class' => 'success',
                        'message' => "شماره شما وریفای شده است و هم اکنون به تمام امکانات وبسایت دسترسی خواهید داشت",
                        'url' => 'index.php?m=smsir&action=verify'
                    ]);
                }else{
                    $newAttamp = ($check->attempts + 1);
                    try {
                        Capsule::table('smsir_vo_verifications_users')
                        ->where("ID", $check->ID)
                        ->update([
                            'attempts' => $newAttamp
                        ]);
                    }catch (\Exception $e){}
                    verify::addattamp($uid,$usrCode,$userphone);
                    vahab::UAlert([
                        'class' => 'warning',
                        'message' => "شما {$newAttamp} تلاش ناموفق داشته اید . شما با هر ارسال کد تنها میتوانید {$numofinpcode} کد ثبت نمایید .",
                        'url' => 'index.php?m=smsir&action=verify'
                    ]);
                }
            }
        }


        if($stepls == 'success'){
            if(!verify::checkUser($uid)->verified){
                $_SESSION['smsir_vo']['verify']['steps'] = 'start';
                vahab::toUrl('index.php?m=smsir&action=verify');
            }
        }

        return array(
            'pagetitle' => 'تایید شماره موبایل',
            'breadcrumb' => array(
                'index.php?m=smsir&action=verify' => 'تایید شماره موبایل',
            ),
            'templatefile' => 'verify',
            'requirelogin' => true,
            'forcessl' => false,
            'vars' => array(
                'steps' => $stepls,
                'phonenumber' => $userphone,
                'modulelink' => $modulelink,
                'smsir_vo_alert' => vahab::UAlert(),
                'req_id' => $check->req_id,
                'expired_at' => $check->expired_at,
                'remaining_time' => $Reminder_Time,
                'phone_number' => $check->phone_number,
                'verified' => $check->verified,
                'attempts' => $check->attempts,
                'verified_at' => $check->verified_at,
                'created_at' => $check->created_at,
            ),
        );
    }

    /**
     * Secret action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function secret($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. smsirs.php?module=smsir
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables

        // Get module configuration parameters
        $configTextField = $vars['Text Field Name'];
        $configPasswordField = $vars['Password Field Name'];
        $configCheckboxField = $vars['Checkbox Field Name'];
        $configDropdownField = $vars['Dropdown Field Name'];
        $configRadioField = $vars['Radio Field Name'];
        $configTextareaField = $vars['Textarea Field Name'];

        return array(
            'pagetitle' => 'Sample Addon Module',
            'breadcrumb' => array(
                'index.php?m=smsir' => 'Sample Addon Module',
                'index.php?m=smsir&action=secret' => 'Secret Page',
            ),
            'templatefile' => 'secretpage',
            'requirelogin' => true, // Set true to restrict access to authenticated client users
            'forcessl' => false, // Deprecated as of Version 7.0. Requests will always use SSL if available.
            'vars' => array(
                'modulelink' => $modulelink,
                'configTextField' => $configTextField,
                'customVariable' => 'your own content goes here',
            ),
        );
    }
}

<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\verify;

if(vahab::EoN($_GET['id'])){
    vahab::toUrl('addonmodules.php?module=smsir&action=verify_users');
}
$ID = $_GET['id'];
$usr = false;
try {
    $usr = Capsule::table('smsir_vo_verifications_users')->where('ID',$ID)->orderBy("ID", "Desc")->first();
} catch (Exception $e) {}



if(vahab::EoN($usr)){
    vahab::toUrl('addonmodules.php?module=smsir&action=verify_users');
}




if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $newStatus = verify::post('optradio');
    Capsule::table('smsir_vo_verifications_users')->where('ID',$ID)->update([
            'verified' => $newStatus
    ]);
    vahab::Alert([
        'class' => 'success',
        'message' => 'وضعیت با موفقیت تغییر یافت',
        'url' => 'addonmodules.php?module=smsir&action=mngverify&id=' . $ID
    ]);
    die();
}



$ui = vahab::uinfo($usr->userid);
$fullname = $ui->firstname .' ' . $ui->lastname;
$linkuser = '<a href="clientssummary.php?userid='.$usr->userid.'" target="_blank">'.$fullname.'</a>';
$verified = '<span class="label label-default">تایید نشده</span>';
if($usr->verified == 1){
    $verified = '<span class="label label-success">تایید شده</span>';
}




$smslogs = '';
$attempts = '';
try {
    $smslogs = Capsule::table('smsir_vo_verifications_smslogs')->where('userid',$usr->userid)->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}
try {
    $attempts = Capsule::table('smsir_vo_verifications_attempts')->where('user_id',$usr->userid)->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}



?>


<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>عنوان</th>
                <th>اطلاعات</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>کاربر</td>
                <td><?php echo $linkuser; ?></td>
            </tr>
            <tr>
                <td>شناسه آخرین درخواست</td>
                <td><?php echo $usr->req_id; ?></td>
            </tr>
            <tr>
                <td>شماره تلفن</td>
                <td><?php echo $usr->phone_number; ?></td>
            </tr>
            <tr>
                <td>آخرین کد وریفای</td>
                <td><?php echo $usr->verification_code; ?></td>
            </tr>
            <tr>
                <td>وضعیت وریفای</td>
                <td><?php echo $verified; ?></td>
            </tr>
            <tr>
                <td>تلاش های ناموفق</td>
                <td><?php echo $usr->attempts; ?> بار</td>
            </tr>
            <tr>
                <td>زمان وریفای</td>
                <td><?php echo $usr->verified_at; ?></td>
            </tr>
            <tr>
                <td>شروع پروسه</td>
                <td><?php echo $usr->created_at; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-bottom: 30px">
    <form method="post">
        <div class="radio">
            <label><input type="radio" name="optradio" value="1" <?php if($usr->verified == 1){echo 'checked';} ?>>وضعیت کاربر فعال است</label>
        </div>
        <div class="radio">
            <label><input type="radio" name="optradio" value="0" <?php if($usr->verified == 0){echo 'checked';} ?>>وضعیت کاربر غیرفعال است</label>
        </div>
        <button type="submit" class="btn btn-primary">ثبت تغییرات</button>
    </form>
</div>

<div style="border: solid 1px #eee;padding: 15px;margin-bottom: 15px">
    <h2 style="font-weight: 900;color: #000;margin-bottom: 30px;font-size: 14px;">پیام های ارسال شده</h2>
    <table id="vahabtable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>شناسه درخواست</th>
            <th>شماره</th>
            <th>شناسه خدماتی</th>
            <th>متن پیام/خدماتی</th>
            <th>زمان ارسال</th>
            <th>وضعیت ارسال</th>
            <th>نوع ارسال</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($smslogs as $item): ?>
            <?php
                $type_send = 'عادی';
                if($item->type_send == 'pattern'){
                    $type_send = 'خدماتی';
                }
            ?>
        <tr>
            <td><?php echo $item->req_id; ?></td>
            <td><?php echo $item->sent_to; ?></td>
            <td><?php echo $item->pattern_id; ?></td>
            <td><?php echo $item->message; ?></td>
            <td><?php echo $item->sent_at; ?></td>
            <td><?php echo $item->result; ?></td>
            <td><?php echo $type_send; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div style="border: solid 1px #eee;padding: 15px">
    <h2 style="font-weight: 900;color: #000;margin-bottom: 30px;font-size: 14px;">گزارش تلاش های ثبت شده</h2>
    <table id="vahabtabletwo" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>شناسه درخواست</th>
            <th>زمان ثبت</th>
            <th>وضعیت کد ورودی</th>
            <th>کد ثبت شده</th>
            <th>برای شماره</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($attempts as $item): ?>
            <?php
            $attempt_status = 'اشتباه';
            if($item->attempt_status == 1){
                $attempt_status = 'صحیح';
            }
            ?>
            <tr>
                <td><?php echo $item->req_id; ?></td>
                <td><?php echo $item->attempted_at; ?></td>
                <td><?php echo $attempt_status; ?></td>
                <td><?php echo $item->attempt_code; ?></td>
                <td><?php echo $item->phonenumber; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

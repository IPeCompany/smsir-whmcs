<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;




if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $remove = Capsule::table('smsir_vo_verifications_attempts');
        if($_POST['of']){
            $remove->where('ID', '>=', $_POST['of']);
        }
        if($_POST['to']){
            $remove->where('ID', '<=', $_POST['to']);
        }
        if($_POST['phonenumber']){
            $remove->where('phonenumber', $_POST['phonenumber']);
        }
        if($_POST['userid']){
            $remove->where('user_id', $_POST['uid']);
        }
        $remove->delete();
    } catch (Exception $e) {}
    vahab::Alert([
        'class' => 'success',
        'message' => 'آیتم های مورد نظر با موفقیت حذف شدند',
        'url' => 'addonmodules.php?module=smsir&action=verify_loginputs'
    ]);
}






$lists = array();
try {
    $lists = Capsule::table('smsir_vo_verifications_attempts')->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}
?>

<form method="post">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    <label>حذف از شناسه</label>
                    <input type="number" name="of" placeholder="شناسه شروع" class="form-control">
                </td>
                <td>
                    <label>تا شناسه</label>
                    <input type="number" name="to" placeholder="شناسه پایان" class="form-control">
                </td>
                <td>
                    <label>حذف گزارش برای شماره</label>
                    <input type="text" name="phonenumber" placeholder="شماره کاربر" class="form-control">
                </td>
                <td>
                    <label>حذف گزارش برای کاربر</label>
                    <input type="text" name="uid" placeholder="آیدی کاربر" class="form-control">
                </td>
                <td style="vertical-align: bottom;"><button type="submit" class="btn btn-danger">حذف</button></td>
            </tr>
        </tbody>
    </table>
</form>

<table id="vahabtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>شناسه</th>
            <th>شناسه درخواست</th>
            <th>کاربر</th>
            <th>شماره</th>
            <th>کد ورودی</th>
            <th>وضعیت کد</th>
            <th>زمان ثبت</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
        <?php
            $ui = vahab::uinfo($item->user_id);
            $fullname = $ui->firstname .' ' . $ui->lastname;
            $linkuser = '<a href="clientssummary.php?userid='.$item->user_id.'" target="_blank">'.$fullname.'</a>';
            $type_send = 'عادی';
            $attempt_status = 'اشتباه';
            if($item->attempt_status == 1){
                $attempt_status = 'صحیح';
            }
        ?>
    <tr>
        <td><?php echo $item->ID; ?></td>
        <td><?php echo $item->req_id; ?></td>
        <td><?php echo $linkuser; ?></td>
        <td><?php echo $item->phonenumber; ?></td>
        <td><?php echo $item->attempt_code; ?></td>
        <td><?php echo $attempt_status; ?></td>
        <td><?php echo $item->attempted_at; ?></td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

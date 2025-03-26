<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;




if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $remove = Capsule::table('smsir_vo_usr_log');
        if($_POST['of']){
            $startof = $_POST['of'] ?: 1;
            $remove->where('ID', '>=', $startof);
        }
        if($_POST['to']){
            $remove->where('ID', '<=', $_POST['to']);
        }
        if($_POST['phonenumber']){
            $remove->where('phonenumber', $_POST['phonenumber']);
        }
        if($_POST['userid']){
            $remove->where('userid', $_POST['uid']);
        }
        $count = $remove->count();
        $remove->delete();
    } catch (Exception $e) {}
    vahab::Alert([
        'class' => 'success',
        'message' => 'تعداد '.$count.' مورد با موفقیت حذف شدند .',
        'url' => 'addonmodules.php?module=smsir&action=logsendusr'
    ]);
}






$lists = array();
try {
    $lists = Capsule::table('smsir_vo_usr_log')->orderBy("ID", "Desc")->get();
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
            <th>کاربر</th>
            <th>نوع ارسال</th>
            <th>شماره</th>
            <th style="width: 300px;">گزارش</th>
            <th>زمان ارسال</th>
            <th>وضعیت ارسل</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
        <?php
            $ui = vahab::uinfo($item->userid);
            $fullname = $ui->firstname .' ' . $ui->lastname;
            $linkuser = '<a href="clientssummary.php?userid='.$item->userid.'" target="_blank">'.$fullname.'</a>';
            $typSnd = 'معمولی';
            $checkType = json_decode($item->text, true);
            if(!vahab::EoN($checkType['pattern_id'])){
                $typSnd = 'خدماتی';
            }
        ?>
    <tr>
        <td style="vertical-align: middle;"><?php echo $item->ID; ?></td>
        <td style="vertical-align: middle;"><?php echo $linkuser; ?></td>
        <td style="vertical-align: middle;"><?php echo $typSnd; ?></td>
        <td style="vertical-align: middle;"><?php echo $item->phonenumber; ?></td>
        <td>
            <pre style="width: 300px;text-wrap: balance;direction: ltr;text-align: left;"><?php echo $item->text; ?></pre>
        </td>
        <td style="vertical-align: middle;"><?php echo $item->send_at; ?></td>
        <td style="vertical-align: middle;"><?php echo $item->result; ?></td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

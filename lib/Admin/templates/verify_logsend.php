<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;




if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $remove = Capsule::table('smsir_vo_verifications_smslogs');
        if($_POST['of']){
            $remove->where('ID', '>=', $_POST['of']);
        }
        if($_POST['to']){
            $remove->where('ID', '<=', $_POST['to']);
        }
        if($_POST['type_send']){
            $remove->where('type_send', $_POST['type_send']);
        }
        if($_POST['userid']){
            $remove->where('userid', $_POST['uid']);
        }
        $remove->delete();
    } catch (Exception $e) {}
    vahab::Alert([
        'class' => 'success',
        'message' => 'آیتم های مورد نظر با موفقیت حذف شدند',
        'url' => 'addonmodules.php?module=smsir&action=verify_logsend'
    ]);
}






$lists = array();
try {
    $lists = Capsule::table('smsir_vo_verifications_smslogs')->orderBy("ID", "Desc")->get();
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
                    <label>حذف نوع ارسال</label>
                    <select name="type_send" class="form-control">
                        <option value="">همه</option>
                        <option value="default">عادی</option>
                        <option value="pattern">خدماتی</option>
                    </select>
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
            <th>متن پیامک/پترن</th>
            <th>نتیجه ارسال</th>
            <th>زمان ارسال</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
        <?php
            $ui = vahab::uinfo($item->userid);
            $fullname = $ui->firstname .' ' . $ui->lastname;
            $linkuser = '<a href="clientssummary.php?userid='.$item->userid.'" target="_blank">'.$fullname.'</a>';
            $type_send = 'عادی';
            $title = 'ارسال عادی';
            if($item->type_send == 'pattern'){
                $type_send = 'خدماتی';
                $title = 'شناسه خدماتی : ' .  $item->pattern_id;
            }
        ?>
    <tr>
        <td><?php echo $item->ID; ?></td>
        <td><?php echo $item->req_id; ?></td>
        <td><?php echo $linkuser; ?></td>
        <td><?php echo $item->sent_to; ?></td>
        <td>
            <a href="#" class="btn btn-warning btn-sm btn-xs" data-toggle="popover" title="<?php echo $title; ?>" data-placement="top" data-trigger="hover" data-content="<?php echo $item->message; ?>">مشاهده</a>
        </td>
        <td><?php echo $item->result; ?></td>
        <td><?php echo $item->sent_at; ?></td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

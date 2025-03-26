<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;




if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $remove = Capsule::table('smsir_vo_hooks_logs');
        if($_POST['of']){
            $remove->where('ID', '>=', $_POST['of']);
        }
        if($_POST['to']){
            $remove->where('ID', '<=', $_POST['to']);
        }
        if($_POST['type_send']){
            $remove->where('type_send', $_POST['type_send']);
        }
        if($_POST['send_for']){
            $remove->where('send_for', $_POST['send_for']);
        }
        if($_POST['uid']){
            $remove->where('uid', $_POST['uid']);
        }
        $remove->delete();
    } catch (Exception $e) {}
    vahab::Alert([
        'class' => 'success',
        'message' => 'آیتم های مورد نظر با موفقیت حذف شدند',
        'url' => 'addonmodules.php?module=smsir&action=logsmssends'
    ]);
}






$lists = array();
try {
    $lists = Capsule::table('smsir_vo_hooks_logs')->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}
?>

<form method="post">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    <label>حذف از شناسه</label>
                    <input type="number" name="of" placeholder="شناسه شروع" class="form-control" required>
                </td>
                <td>
                    <label>تا شناسه</label>
                    <input type="number" name="to" placeholder="شناسه پایان" class="form-control" required>
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
                    <label>حذف گزارش برای</label>
                    <select name="send_for" class="form-control">
                        <option value="">همه</option>
                        <option value="user">کاربر</option>
                        <option value="admin">مدیر</option>
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
        <th>هوک</th>
        <th>نوع ارسال</th>
        <th>برای</th>
        <th>کاربر هدف</th>
        <th>شماره</th>
        <th>متن پیام</th>
        <th>نتیجه</th>
        <th>تاریخ</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
        <?php
            $ui = vahab::uinfo($item->uid);
            $fullname = $ui->firstname .' ' . $ui->lastname;
            $linkuser = '<a href="clientssummary.php?userid='.$item->uid.'" target="_blank">'.$fullname.'</a>';
            $sendfor = 'کاربر';
            $type_send = 'عادی';
            if($item->send_for == 'admin'){
                $sendfor = 'مدیر';
            }
            if($item->type_send == 'pattern'){
                $type_send = 'خدماتی';
            }
        ?>
    <tr>
        <td><?php echo $item->ID; ?></td>
        <td><?php echo $item->hook; ?></td>
        <td><?php echo $type_send; ?></td>
        <td><?php echo $sendfor; ?></td>
        <td><?php echo $linkuser; ?></td>
        <td><?php echo $item->mobile; ?></td>
        <td><?php echo $item->message; ?></td>
        <td><?php echo $item->result; ?></td>
        <td><?php echo $item->created_at; ?></td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

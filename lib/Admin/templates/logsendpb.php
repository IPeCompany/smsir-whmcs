<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;




if($_SERVER['REQUEST_METHOD'] == 'POST'){

    try {
        $remove = Capsule::table('smsir_vo_pb_bulklog');
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
        $count = $remove->count();
        $remove->delete();
    } catch (Exception $e) {}
    vahab::Alert([
        'class' => 'success',
        'message' => 'تعداد '.$count.' مورد با موفقیت حذف شدند .',
        'url' => 'addonmodules.php?module=smsir&action=logsendpb'
    ]);
}






$lists = array();
try {
    $lists = Capsule::table('smsir_vo_pb_bulklog')->orderBy("ID", "Desc")->get();
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
                <td style="vertical-align: bottom;"><button type="submit" class="btn btn-danger">حذف</button></td>
            </tr>
        </tbody>
    </table>
</form>

<table id="vahabtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>شناسه</th>
            <th>نوع ارسال</th>
            <th>شماره</th>
            <th>گزارش</th>
            <th>زمان ارسال</th>
            <th>وضعیت ارسل</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
        <?php
            $typSnd = 'معمولی';
            $checkType = json_decode($item->text, true);
            if(!vahab::EoN($checkType['pattern_id'])){
                $typSnd = 'خدماتی';
            }
        ?>
    <tr>
        <td><?php echo $item->ID; ?></td>
        <td><?php echo $typSnd; ?></td>
        <td><?php echo $item->phonenumber; ?></td>
        <td><?php echo $item->text; ?></td>
        <td><?php echo $item->send_at; ?></td>
        <td><?php echo $item->result; ?></td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

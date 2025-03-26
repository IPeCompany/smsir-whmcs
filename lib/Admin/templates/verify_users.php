<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
$lists = array();
try {
    $lists = Capsule::table('smsir_vo_verifications_users')->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}
?>

<table id="vahabtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
    <tr>
        <th>شناسه</th>
        <th>کاربر</th>
        <th>شماره</th>
        <th>وضعیت</th>
        <th>کد تایید</th>
        <th>ورود اشتباه</th>
        <th>وریفای</th>
        <th>شروع</th>
        <th>مدیریت</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
        <?php
            $ui = vahab::uinfo($item->userid);
            $fullname = $ui->firstname .' ' . $ui->lastname;
            $linkuser = '<a href="clientssummary.php?userid='.$item->userid.'" target="_blank">'.$fullname.'</a>';
            $verified = '<span class="label label-default">تایید نشده</span>';
            if($item->verified == 1){
                $verified = '<span class="label label-success">تایید شده</span>';
            }
        ?>
    <tr>
        <td><?php echo $item->req_id; ?></td>
        <td><?php echo $linkuser; ?></td>
        <td><?php echo $item->phone_number; ?></td>
        <td><?php echo $verified; ?></td>
        <td><?php echo $item->verification_code; ?></td>
        <td><?php echo $item->attempts; ?></td>
        <td><?php echo $item->verified_at; ?></td>
        <td><?php echo $item->created_at; ?></td>
        <td><a href="<?php echo vahab::AdminUrl('action=mngverify&id=' . $item->ID); ?>" class="btn btn-sm btn-xs btn-primary">مدیریت</a></td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

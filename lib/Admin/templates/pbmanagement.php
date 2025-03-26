<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
$lists = array();
try {
    $lists = Capsule::table('smsir_vo_phonebooks')->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}
?>

<h3 style="font-weight: 700;color: #333;font-size: 15px;margin-bottom: 25px;border-bottom: solid 1px #eee;padding-bottom: 15px;">مدیریت دفتر تلفن ها</h3>

<table id="vahabtable" class="table table-bordered" style="width:100%">
    <thead>
    <tr>
        <th>شناسه</th>
        <th>نام دفتر تلفن</th>
        <th>تعداد شماره ها</th>
        <th style="width: 150px">مدیریت</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($lists as $item): ?>
        <?php
        $Count_pb_id = 0;
        try {
            $Count_pb_id = Capsule::table('smsir_vo_phone')->where("pb_id", $item->ID)->count();
        } catch (Exception $e) {}
        ?>
    <tr>
        <td><?php echo $item->ID; ?></td>
        <td><?php echo $item->name; ?></td>
        <td><?php echo $Count_pb_id; ?></td>
        <td>
            <a href="<?php echo vahab::AdminUrl('action=pblistnumbers&id=' . $item->ID); ?>" class="btn btn-sm btn-xs btn-primary">مدیریت شماره ها</a>
            <a href="<?php echo vahab::AdminUrl('action=editpb&id=' . $item->ID); ?>" class="btn btn-sm btn-xs btn-info">ویرایش دفتر</a>
        </td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

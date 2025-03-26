<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\pb;


if(vahab::EoN($_GET['id'])){
    vahab::toUrl('addonmodules.php?module=smsir&action=pbmanagement');
}
$ID = $_GET['id'];


$lists = array();
try {
    $lists = Capsule::table('smsir_vo_phone')
        ->where('pb_id', $ID)
        ->orderBy("ID", "Desc")
        ->get();
} catch (Exception $e) {}


if(vahab::EoN($lists)){
    vahab::toUrl('addonmodules.php?module=smsir&action=pbmanagement');
}
?>

<h3 style="font-weight: 700;color: #333;font-size: 15px;margin-bottom: 25px;border-bottom: solid 1px #eee;padding-bottom: 15px;">مدیریت شماره های دفتر تلفن  :
    <?php echo pb::pb_name($ID); ?>  - تعداد شماره ها : <?php echo pb::CountPbNumbers($ID); ?>
    <span style="float: left">
        <a href="addonmodules.php?module=smsir&action=editpb&id=<?php echo $ID; ?>" class="btn btn-info btn-sm btn-xs">ویرایش دفتر تلفن</a>
    </span>
</h3>

<table id="vahabtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
    <tr>
        <th>نام</th>
        <th>نام خانوادگی</th>
        <th>موبایل</th>
        <th style="width: 150px">مدیریت</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $item): ?>
    <tr>
        <td><?php echo $item->firstname; ?></td>
        <td><?php echo $item->lastname; ?></td>
        <td><?php echo $item->mobile; ?></td>
        <td>
            <a href="<?php echo vahab::AdminUrl('action=editcontact&id=' . $item->ID); ?>" class="btn btn-sm btn-xs btn-primary">مدیریت شماره</a>
            <a href="<?php echo vahab::AdminUrl('action=pbremove&type=number&id=' . $item->ID); ?>" class="btn btn-danger btn-sm btn-xs">حذف شماره</a>
        </td>
    </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

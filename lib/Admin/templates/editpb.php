<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use \WHMCS\Module\Addon\smsir\vahabonline\pb;

if(vahab::EoN($_GET['id'])){
    vahab::toUrl('addonmodules.php?module=smsir&action=pbmanagement');
}
$ID = $_GET['id'];
$data = false;
try {
    $data = Capsule::table('smsir_vo_phonebooks')
        ->where('ID', $ID)
        ->first();
} catch (Exception $e) {}
if(vahab::EoN($data)){
    vahab::toUrl('addonmodules.php?module=smsir&action=pbmanagement');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = pb::post('name');
    if(vahab::EoN($name)){
        vahab::Alert([
            'class' => 'warning',
            'message' => 'وارد نمودن نام الزامی است',
            'url' => 'addonmodules.php?module=smsir&action=addpb'
        ]);
        die();
    }

    try {
        Capsule::table('smsir_vo_phonebooks')
            ->where('ID', $ID)
            ->update([
                'name' => $name,
                'updated_at' => date('Y-m-d H:i:s')
        ]);
        vahab::Alert([
            'class' => 'success',
            'message' => 'با موفقیت ویرایش شد شد',
            'url' => 'addonmodules.php?module=smsir&action=pbmanagement'
        ]);
        die();
    } catch (Exception $e) {}

    vahab::toUrl('addonmodules.php?module=smsir&action=addpb');
    die();


}


?>

<h3 style="font-weight: 700;color: #333;font-size: 15px;margin-bottom: 25px;border-bottom: solid 1px #eee;padding-bottom: 15px;">
    ویرایش دفتر تلفن : <?php echo pb::pb_name($ID); ?>
    - تعداد شماره ها : <?php echo pb::CountPbNumbers($ID); ?>
</h3>

<form method="post">
    <div class="form-group">
        <label>نام دفتر تلفن</label>
        <input type="text" class="form-control" name="name" value="<?php echo $data->name; ?>">
    </div>
    <button type="submit" class="btn btn-primary">بروزرسانی دفتر تلفن</button>
    <a href="addonmodules.php?module=smsir&action=pbremove&type=phonebook&id=<?php echo $data->ID; ?>" class="btn btn-danger">حذف دفتر تلفن</a>
</form>

<br/>
<div class="alert alert-danger">
    با حذف دفتر تلفن تمام شماره های موجود در آن نیز به صورت خودکار حذف خواهند شد
</div>

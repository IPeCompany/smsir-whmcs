<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use \WHMCS\Module\Addon\smsir\vahabonline\pb;


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
        Capsule::table('smsir_vo_phonebooks')->insert([
                'name' => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
        ]);
        vahab::Alert([
            'class' => 'success',
            'message' => 'با موفقیت اضافه شد',
            'url' => 'addonmodules.php?module=smsir&action=pbmanagement'
        ]);
        die();
    } catch (Exception $e) {}

    vahab::toUrl('addonmodules.php?module=smsir&action=addpb');
    die();


}


?>

<form method="post">
    <div class="form-group">
        <label>نام دفتر تلفن</label>
        <input type="text" class="form-control" name="name">
    </div>
    <button type="submit" class="btn btn-primary">ساخت دفتر تلفن</button>
</form>

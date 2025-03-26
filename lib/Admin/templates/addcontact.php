<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use \WHMCS\Module\Addon\smsir\vahabonline\pb;


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $mobile = pb::post('mobile');
    $pb_id = pb::post('pb_id');
    if(vahab::EoN($mobile) || vahab::EoN($pb_id)){
        vahab::Alert([
            'class' => 'warning',
            'message' => 'وارد نمودن دفترتلفن و موبایل الزامی است',
            'url' => 'addonmodules.php?module=smsir&action=addcontact'
        ]);
        die();
    }

    try {
        Capsule::table('smsir_vo_phone')->insert([
            'pb_id' => $pb_id,
            'firstname' => pb::post('firstname'),
            'lastname' => pb::post('lastname'),
            'email' => pb::post('email'),
            'adress' => pb::post('adress'),
            'tell' => pb::post('tell'),
            'mobile' => $mobile,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        vahab::Alert([
            'class' => 'success',
            'message' => 'با موفقیت اضافه شد',
            'url' => 'addonmodules.php?module=smsir&action=pblistnumbers&id=' . $pb_id
        ]);
        die();
    } catch (Exception $e) {}

    vahab::toUrl('addonmodules.php?module=smsir&action=addcontact');
    die();


}


$lists = array();
try {
    $lists = Capsule::table('smsir_vo_phonebooks')->orderBy("ID", "Desc")->get();
} catch (Exception $e) {}

?>

<form method="post">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <select name="pb_id" class="form-control">
                    <?php
                        foreach ($lists as $itm){
                            echo '<option value="'.$itm->ID.'">'.$itm->name.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>نام</label>
                <input type="text" class="form-control" name="firstname">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>نام خانوادگی</label>
                <input type="text" class="form-control" name="lastname">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>آدرس</label>
                <input type="text" class="form-control" name="adress">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>ایمیل</label>
                <input type="text" class="form-control" name="email">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>تلفن</label>
                <input type="tell" class="form-control" name="tell">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>موبایل <span style="color: #9b0000">*</span></label>
                <input type="tell" class="form-control" name="mobile">
            </div>
        </div>
    </div>



    <button type="submit" class="btn btn-primary">افزودن شماره</button>
</form>

<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\pb;


if(vahab::EoN($_GET['id'])){
    vahab::toUrl('addonmodules.php?module=smsir&action=pbmanagement');
}
$ID = $_GET['id'];


$data = false;
try {
    $data = Capsule::table('smsir_vo_phone')->where('ID', $ID)->first();
} catch (Exception $e) {}


if(vahab::EoN($data)){
    vahab::toUrl('addonmodules.php?module=smsir&action=pbmanagement');
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $mobile = pb::post('mobile');
    $pb_id = pb::post('pb_id');
    if(vahab::EoN($mobile) || vahab::EoN($pb_id)){
        vahab::Alert([
            'class' => 'warning',
            'message' => 'وارد نمودن دفترتلفن و موبایل الزامی است',
            'url' => 'addonmodules.php?module=smsir&action=editcontact&id=' . $ID
        ]);
        die();
    }

    try {
        Capsule::table('smsir_vo_phone')
            ->where('ID', $ID)
            ->update([
            'pb_id' => $pb_id,
            'firstname' => pb::post('firstname'),
            'lastname' => pb::post('lastname'),
            'email' => pb::post('email'),
            'adress' => pb::post('adress'),
            'tell' => pb::post('tell'),
            'mobile' => $mobile,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        vahab::Alert([
            'class' => 'success',
            'message' => 'با موفقیت اضافه شد',
            'url' => 'addonmodules.php?module=smsir&action=editcontact&id=' . $ID
        ]);
        die();
    } catch (Exception $e) {}

    vahab::toUrl('addonmodules.php?module=smsir&action=editcontact&id=' . $ID);
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
                            $slct = '';
                            if($data->pb_id == $itm->ID){
                                $slct = 'selected';
                            }
                            echo '<option value="'.$itm->ID.'" '.$slct.'>'.$itm->name.'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>نام</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo $data->firstname; ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>نام خانوادگی</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo $data->lastname; ?>">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>آدرس</label>
                <input type="text" class="form-control" name="adress" value="<?php echo $data->adress; ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>ایمیل</label>
                <input type="text" class="form-control" name="email" value="<?php echo $data->email; ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>تلفن</label>
                <input type="tell" class="form-control" name="tell" value="<?php echo $data->tell; ?>">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>موبایل <span style="color: #9b0000">*</span></label>
                <input type="tell" class="form-control" name="mobile" value="<?php echo $data->mobile; ?>">
            </div>
        </div>
    </div>


    <a href="addonmodules.php?module=smsir&action=pblistnumbers&id=<?php echo $data->pb_id; ?>" class="btn btn-info">« بازگشت</a>
    <button type="submit" class="btn btn-primary">بروزرسانی</button>
    <a href="addonmodules.php?module=smsir&action=pbremove&type=number&id=<?php echo $ID; ?>" class="btn btn-danger">حذف شماره</a>
</form>

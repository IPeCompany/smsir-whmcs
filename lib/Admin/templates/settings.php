<?php
 use WHMCS\Module\Addon\smsir\vahabonline\vahab;
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
     vahab::SetS('mobilefield', $_POST['mobilefield']);
     vahab::SetS('default_number', $_POST['default_number']);
     vahab::SetS('pattern_number', $_POST['pattern_number']);
     vahab::SetS('signature', $_POST['signature']);
     vahab::SetS('sitename', $_POST['sitename']);
     vahab::Alert([
             'class' => 'success',
             'message' => 'تغییرات با موفقیت اعمال شدند',
             'url' => 'addonmodules.php?module=smsir&action=settings'
     ]);
 }
?>


<form method="post">

    <div class="vo--smsir-form-group">
        <label>فیلد شماره موبایل</label>
        <select name="mobilefield" class="form-control">
            <option value="default">استفاده از فیلد پیشفرض سیستم</option>
            <?php echo vahab::OptionMobileFields(vahab::GS('mobilefield')); ?>
        </select>
    </div>

    <div class="vo--smsir-form-group">
        <label>شماره ارسال معمولی</label>
        <input type="text" name="default_number" value="<?php echo vahab::GS('default_number'); ?>" class="form-control">
    </div>

    <div class="vo--smsir-form-group">
        <label>شماره ارسال خدماتی</label>
        <input type="text" name="pattern_number" value="<?php echo vahab::GS('pattern_number'); ?>" class="form-control">
    </div>

    <div class="vo--smsir-form-group">
        <label>نام سایت یا برند</label>
        <input type="text" name="sitename" value="<?php echo vahab::GS('sitename'); ?>" class="form-control">
    </div>

    <div class="vo--smsir-form-group">
        <label>امضا پیامک</label>
        <textarea name="signature" class="form-control"><?php echo vahab::GS('signature'); ?></textarea>
    </div>


    <div class="vo-btn-submit">
        <button type="submit">ثبت تغییرات</button>
    </div>


</form>



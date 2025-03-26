<?php
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    vahab::SetS('verify_status', $_POST['verify_status'] ?: vahab::GS('verify_status'));
    if($_POST['nowStatus'] == 'enabled'){
        vahab::SetS('verify_typesend', $_POST['verify_typesend']);
        vahab::SetS('verify_msg', $_POST['verify_msg']);
        vahab::SetS('verify_patternID', $_POST['verify_patternID']);
        vahab::SetS('verify_pattern', $_POST['verify_pattern']);
        vahab::SetS('verify_attempts', $_POST['verify_attempts']);
        vahab::SetS('verify_expiretime', $_POST['verify_expiretime']);
        vahab::SetS('verify_lockpages', json_encode($_POST['verify_lockpages']));
    }
    vahab::Alert([
        'class' => 'success',
        'message' => 'تغییرات با موفقیت اعمال شدند',
        'url' => 'addonmodules.php?module=smsir&action=verify_settings'
    ]);
}
?>


<form method="post">
    <input type="hidden" name="nowStatus" value="<?php echo vahab::GS('verify_status'); ?>">
    <div class="vo--smsir-form-group">
        <label>وریفای شماره</label>
        <select name="verify_status" class="form-control" onchange="this.form.submit()">
            <option value="diabled" <?php echo vahab::selectmenu(vahab::GS('verify_status'), ['diabled']) ?>>فعال نباشد</option>
            <option value="enabled" <?php echo vahab::selectmenu(vahab::GS('verify_status'), ['enabled']) ?>>فعال باشد</option>
        </select>
    </div>

    <?php if(vahab::GS('verify_status') == 'enabled'):?>
        <div class="vo--smsir-form-group">
            <label>نوع ارسال پیامک وریفای</label>
            <select name="verify_typesend" class="form-control" onchange="ChangeVerifyStatus(this.value)">
                <option value="default" <?php echo vahab::selectmenu(vahab::GS('verify_typesend'), ['default']) ?>>ارسال معمولی</option>
                <option value="pattern" <?php echo vahab::selectmenu(vahab::GS('verify_typesend'), ['pattern']) ?>>ارسال خدماتی</option>
            </select>
        </div>

        <?php
            //nemishe
            $none_pattern = 'style="display: none"';
            $none_default = 'style="display: none"';
            if(vahab::EoN(vahab::GS('verify_typesend'))){
                $none_default = '';
            }
            if(vahab::GS('verify_typesend') == 'pattern'){
                $none_pattern = '';
            }
            if(vahab::GS('verify_typesend') == 'default'){
                $none_default = '';
            }
        ?>


        <div class="vo--smsir-form-group" id="verify_typesend_default" <?php echo $none_default; ?>>
            <pre>{code}</pre>
            <pre><?php echo vahab::ShowUserDefaultTag(); ?></pre>
            <label>متن پیامک</label>
            <textarea name="verify_msg" class="form-control"><?php echo vahab::GS('verify_msg'); ?></textarea>
        </div>

        <div id="verify_typesend_pattern" <?php echo $none_pattern; ?>>
            <div class="vo--smsir-form-group">
                <label>شناسه قالب خدماتی</label>
                <input type="text" name="verify_patternID" value="<?php echo vahab::GS('verify_patternID'); ?>" class="form-control">
            </div>
            <div class="vo--smsir-form-group">
                <label>کد خدماتی</label>
                <textarea name="verify_pattern" class="form-control vo--smsir-ltr pre"><?php echo vahab::GS('verify_pattern'); ?></textarea>
                <div class="vo--block-item">
                    <a href="javascript://" onclick="OpenTagsHook(1, 'ptrn')" class="vo--smsir-btnHookGen">ساخت کد پیام خدماتی</a>
                </div>
            </div>
        </div>


        <div class="vo--smsir-form-group">
            <label>با هر ارسال کد چند تلاش برای ثبت کد مجاز باشد</label>
            <input type="number" name="verify_attempts" value="<?php echo vahab::GS('verify_attempts'); ?>" class="form-control">
        </div>
        <div class="vo--smsir-form-group">
            <label>هر درخواست چند ثانیه اعتبار داشته باشد</label>
            <input type="number" name="verify_expiretime" value="<?php echo vahab::GS('verify_expiretime'); ?>" class="form-control">
        </div>

        <?php $lockPages = json_decode(vahab::GS('verify_lockpages')); ?>
        <div class="vo--smsir-form-group">
            <label>صفحات قفل شده سایت</label>
            <select name="verify_lockpages[]" class="form-control" multiple>
                <option value="clientareahome" <?php echo vahab::selectmenu('clientareahome', $lockPages) ?>>پنل کاربری</option>
                <option value="clientareaproducts" <?php echo vahab::selectmenu('clientareaproducts', $lockPages) ?>>سرویس ها</option>
                <option value="clientareaproductdetails" <?php echo vahab::selectmenu('clientareaproductdetails', $lockPages) ?>>مدیریت سرویس</option>
                <option value="viewcart" <?php echo vahab::selectmenu('viewcart', $lockPages) ?>>سفارش سرویس</option>
                <option value="clientareadomains" <?php echo vahab::selectmenu('clientareadomains', $lockPages) ?>>دامنه ها</option>
                <option value="clientareadomaindetails" <?php echo vahab::selectmenu('clientareadomaindetails', $lockPages) ?>>مدیریت دامنه</option>
                <option value="domainregister" <?php echo vahab::selectmenu('domainregister', $lockPages) ?>>جستوجوی دامنه</option>
                <option value="clientareainvoices" <?php echo vahab::selectmenu('clientareainvoices', $lockPages) ?>>صورت حساب ها</option>
                <option value="viewinvoice" <?php echo vahab::selectmenu('viewinvoice', $lockPages) ?>>مشاهده صورت حساب</option>
                <option value="clientareaquotes" <?php echo vahab::selectmenu('clientareaquotes', $lockPages) ?>>پیش فاکتورها</option>
                <option value="viewquote" <?php echo vahab::selectmenu('viewquote', $lockPages) ?>>مشاهده پیش فاکتورها</option>
                <option value="masspay" <?php echo vahab::selectmenu('masspay', $lockPages) ?>>پرداخت عمده</option>
                <option value="clientareaaddfunds" <?php echo vahab::selectmenu('clientareaaddfunds', $lockPages) ?>>واریز وجه</option>
                <option value="supportticketslist" <?php echo vahab::selectmenu('supportticketslist', $lockPages) ?>>تیکت ها</option>
                <option value="viewticket" <?php echo vahab::selectmenu('viewticket', $lockPages) ?>>مشاهده تیکت</option>
                <option value="supportticketsubmit-steptwo" <?php echo vahab::selectmenu('supportticketsubmit-steptwo', $lockPages) ?>>ثبت تیکت</option>
                <option value="knowledgebase" <?php echo vahab::selectmenu('knowledgebase', $lockPages) ?>>مرکز آموزش</option>
                <option value="announcements" <?php echo vahab::selectmenu('announcements', $lockPages) ?>>اخبار</option>
                <option value="downloads" <?php echo vahab::selectmenu('downloads', $lockPages) ?>>دانلود</option>
                <option value="serverstatus" <?php echo vahab::selectmenu('serverstatus', $lockPages) ?>>وضعیت شبکه</option>
                <option value="affiliates" <?php echo vahab::selectmenu('affiliates', $lockPages) ?>>بازاریابی</option>
                <option value="clientareadetails" <?php echo vahab::selectmenu('clientareadetails', $lockPages) ?>>اطلاعات حساب</option>
                <option value="account-user-management" <?php echo vahab::selectmenu('account-user-management', $lockPages) ?>>مدیریت کاربر</option>
                <option value="account-contacts-manage" <?php echo vahab::selectmenu('account-contacts-manage', $lockPages) ?>>حساب های فرعی</option>
                <option value="clientareasecurity" <?php echo vahab::selectmenu('clientareasecurity', $lockPages) ?>>امنیت حساب</option>
                <option value="clientareaemails" <?php echo vahab::selectmenu('clientareaemails', $lockPages) ?>>تاریخچه ایمیل</option>
                <option value="user-password" <?php echo vahab::selectmenu('user-password', $lockPages) ?>>تغییر رمز</option>
                <option value="user-security" <?php echo vahab::selectmenu('user-security', $lockPages) ?>>تنظیمات امنیتی</option>
            </select>
            <small>از کنترل برای انتخاب و عدم انتخاب استفاده نمایید</small>
        </div>


        <div class="vo-btn-submit">
            <button type="submit">ثبت تغییرات</button>
        </div>





<!--Start CodeGenerator-->
        <?php
            $ID = 1;
        ?>
        <div class="vo--smsir-tags" id="vo--smsir-tags<?php echo $ID; ?>" style="width: 340px !important;">
            <div class="HeaderVoSmsIRLeftSide">
                <span>مدیریت کدها</span>
                <a href="javascript://" onclick="OpenTagsHook(<?php echo $ID; ?>)" class="vo--smsir-btnHookGen">
                    X
                </a>
            </div>
            <div class="BodyVoSmsIRsidebar">
                <div class="vo--smsir-tit">تگ های قابل استفاده</div>
                <pre>{code}</pre>
                <pre><?php echo vahab::ShowUserDefaultTag(); ?></pre>
                <div id="showGen<?php echo $ID; ?>" style="display: none">
                    <div class="vo--smsir-tit">ساخت کد خدماتی</div>
                    <form id="voSmsIRPatternGenerator">
                        <div class="vosmsir-tble">
                            <table>
                                <thead>
                                <tr>
                                    <th>متغییر متن پیامک</th>
                                    <th>مقدار جایگزین</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" id="txt<?php echo $ID; ?>a" placeholder="#tag#"></td>
                                    <td><input type="text" id="vlu<?php echo $ID; ?>a" placeholder="{var}"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" id="txt<?php echo $ID; ?>b" placeholder="#tag#"></td>
                                    <td><input type="text" id="vlu<?php echo $ID; ?>b" placeholder="{var}"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" id="txt<?php echo $ID; ?>c" placeholder="#tag#"></td>
                                    <td><input type="text" id="vlu<?php echo $ID; ?>c" placeholder="{var}"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" id="txt<?php echo $ID; ?>d"></td>
                                    <td><input type="text" id="vlu<?php echo $ID; ?>d"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" id="txt<?php echo $ID; ?>e"></td>
                                    <td><input type="text" id="vlu<?php echo $ID; ?>e"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button type="button" onclick="PatternGen(<?php echo $ID; ?>)" class="codgen">ساخت</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <textarea class="showCodeGen" id="showCodeGen<?php echo $ID; ?>" style="width: 100%;"></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<!--End CodeGenerator-->







    <?php endif; ?>


</form>



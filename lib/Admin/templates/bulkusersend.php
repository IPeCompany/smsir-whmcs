<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\pb;
use WHMCS\Module\Addon\smsir\vahabonline\sfu;
use WHMCS\Module\Addon\smsir\vahabonline\hdata;



if($_SERVER['REQUEST_METHOD'] == 'POST') {



    if ($_POST['type_send'] == 'default') {
        if (vahab::EoN($_POST['message'])) {
            vahab::Alert([
                'class' => 'warning',
                'message' => 'وارد کردن متن پیام الزامی است',
                'url' => 'addonmodules.php?module=smsir&action=bulkusersend'
            ]);
            die();
        }
    }
    if ($_POST['type_send'] == 'pattern') {
        if (vahab::EoN($_POST['pattern_id'])) {
            vahab::Alert([
                'class' => 'warning',
                'message' => 'وارد نمودن شناسه پترن الزامی است',
                'url' => 'addonmodules.php?module=smsir&action=bulkusersend'
            ]);
            die();
        }
        foreach ($_POST['pname'] as $key => $pnm) {
            if (!vahab::EoN($pnm)) {
                $Pname = strtr($pnm, [
                    '#' => ''
                ]);
                $_POST['pattern_values'][$Pname] = $_POST['pvalue'][$key];
            }
        }
    }

    $POSTDATA = base64_encode(json_encode($_POST));
    $seckey = pb::randomPassword();
    vahab::SetS('BulksecKey', $seckey);
    $url = vahab::SiteUrl('/modules/addons/smsir/actions/sendings/foruser.php?voToken=' . $seckey . '&key=' . $POSTDATA);
    echo '<iframe src="' . $url . '" name="sendFrame" style="width: 100%;border: solid 1px #eee;border-radius: 6px"></iframe>';

}
?>

<div style="background: #f3f3f3;padding: 15px;border-radius: 6px;margin-bottom: 30px">
    <p>متغییر های مورد استفاده :</p>
    <pre style="direction: ltr;text-align: left;background: #fff;margin: 0px;text-wrap: pretty;"><?php echo vahab::ShowUserDefaultTag(); ?></pre>
    <br/>
    <p>کدهای قابل استفاده در ارسال های سرویس ها و دامنه ها : </p>
    <pre style="direction: ltr;text-align: left;background: #fff;margin: 0px;text-wrap: pretty;">{proname},{regdate},{domain},{amount},{firstpaymentamount},{recurringamount},{billingcycle},{registrationdate},{nextduedate},{nextinvoicedate},{termination_date},{expirydate},{domainstatus},{username},{dedicatedip},{ns1},{ns2},{registrar},{registrationperiod}</pre>
</div>



<form method="post">

    <div style="border: solid 1px #64ed88;padding: 23px;border-radius: 16px;margin-bottom: 15px;background: #eeffe3;">
        <div class="form-group">
            <label>نوع ارسال</label>
            <select class="form-control" onchange="bulkSendForUsers(this.value)" name="sendFor">
                <option value="users">کاربران</option>
                <option value="service">سرویس ها</option>
                <option value="domains">دامنه ها</option>
            </select>
        </div>
    </div>

    <div style="border: solid 1px #cbcbcb;padding: 23px;border-radius: 16px;margin-bottom: 15px;background: #f1f1f1;" id="SendForUser">
        <div class="form-group">
            <label>وضعیت کاربر</label>
            <select class="form-control" name="user[status][]" multiple="multiple">
                <option value="Active">فعال</option>
                <option value="Inactive">غیرفعال</option>
                <option value="Closed">بسته شده</option>
            </select>
        </div>
        <div class="form-group">
            <label>زبان کاربر</label>
            <select class="form-control" name="user[lang][]" multiple="multiple">
                <?php
                    foreach (sfu::Langs() as $lng){
                        $name = $lng->language ?: 'مشخص نشده';
                        echo "<option value='{$lng->language}'>{$name}</option>";
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>کشور</label>
            <select class="form-control" name="user[countries][]" multiple="multiple">
                <?php
                foreach (sfu::countries() as $cnt){
                    $name = $cnt->country ?: 'مشخص نشده';
                    echo "<option value='{$cnt->country}'>{$name}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>واحد پول</label>
            <select class="form-control" name="user[currency][]" multiple="multiple">
                <?php
                foreach (sfu::currency() as $cur){
                    $name = $cur->code ?: 'مشخص نشده';
                    echo "<option value='{$cur->currency}'>{$name}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div style="border: solid 1px #cbcbcb;padding: 23px;border-radius: 16px;margin-bottom: 15px;background: #f1f1f1;display: none" id="SendForService">
        <div class="form-group">
            <label>اگر محصولات زیر را داشت</label>
            <select class="form-control" name="product[list][]" multiple="multiple">
                <?php
                foreach (sfu::listpros() as $cur){
                    $name = $cur->name ?: 'مشخص نشده';
                    echo "<option value='{$cur->id}'>{$name}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>وضعیت محصول</label>
            <select name="product[status][]" class="form-control" multiple="multiple">
                <?php
                foreach (sfu::prostatus() as $dta){
                    echo "<option value='{$dta->domainstatus}'>{$dta->domainstatus}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>دوره صورت حساب</label>
            <select name="product[cycle][]" class="form-control" multiple="multiple">
                <?php
                foreach (sfu::probillingcycle() as $dta){
                    echo "<option value='{$dta->billingcycle}'>{$dta->billingcycle}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div style="border: solid 1px #cbcbcb;padding: 23px;border-radius: 16px;margin-bottom: 15px;background: #f1f1f1;display: none" id="SendForDomains">
        <div class="form-group">
            <label>پسوند دامنه های</label>
            <select class="form-control" name="domain[ext][]" multiple="multiple">
                <?php
                foreach (sfu::domainlist() as $dta){
                    $ext = strtr($dta->extension,['.' => '']);
                    echo "<option value='{$ext}'>{$ext}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>نوع سرویس دامنه</label>
            <select name="domain[type][]" class="form-control" multiple="multiple">
                <?php
                foreach (sfu::domainType() as $dta){
                    echo "<option value='{$dta->type}'>{$dta->type}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>رجیسترارر</label>
            <select name="domain[registrar][]" class="form-control" multiple="multiple">
                <?php
                foreach (sfu::domainRegisterarer() as $dta){
                    $name = $dta->registrar ?: "بدون رجیسترارر";
                    echo "<option value='{$dta->registrar}'>{$name}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>وضعیت</label>
            <select name="domain[status][]" class="form-control" multiple="multiple">
                <?php
                foreach (sfu::domainStatus() as $dta){
                    echo "<option value='{$dta->status}'>{$dta->status}</option>";
                }
                ?>
            </select>
        </div>
    </div>


    <div style="border: solid 1px #cbcbcb;padding: 23px;border-radius: 16px;margin-bottom: 15px;background: #f1f1f1;" id="SendOptionsVahab">

        <div class="form-group">
            <label>ارسال به صورت</label>
            <select name="type_send" class="form-control" onchange="SendBuilkSms(this.value)">
                <option value="default">معمولی</option>
                <option value="pattern">خدماتی</option>
            </select>
        </div>

        <div id="defualtsend">
            <div class="form-group">
                <label>متن پیام</label>
                <textarea class="form-control" name="message" rows="5"></textarea>
            </div>
        </div>

        <div id="patternsend" style="display: none">
            <div class="form-group">
                <label>شناسه قالب</label>
                <input type="text" name="pattern_id" class="form-control">
            </div>
            <div class="form-group">
                <div class="alert alert-info">
                    در این بخش نام متغییر همان نامی است که هنگام تعریف پیام خدماتی در سامانه وارد کرده اید . مثال : #contacts#
                    <br/>
                    مقدار متغییر نیز مقداری است که میخواهید با مقدار بالا جایگزین شود . مثلا درون پیامک تعریف کرده اید #firstname# در بخش مقدار متغییر میتوانید نام مورد نظر خود و یا از شرت کد {firstname} استفاده کنید
                    <br/>
                    به ازاری هر نام متغییر باید یک مقدار نیز برای آن تعریف نمایید
                </div>
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>نام متغییر</th>
                        <th>مقدار متغییر</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="text" name="pname[1]" placeholder="برای مثال : #firstname#" class="form-control"></td>
                        <td><input type="text" name="pvalue[1]" placeholder="برای مثال {firstname}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="pname[2]" class="form-control"></td>
                        <td><input type="text" name="pvalue[2]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="pname[3]" class="form-control"></td>
                        <td><input type="text" name="pvalue[3]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="pname[4]" class="form-control"></td>
                        <td><input type="text" name="pvalue[4]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="pname[5]" class="form-control"></td>
                        <td><input type="text" name="pvalue[5]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="pname[6]" class="form-control"></td>
                        <td><input type="text" name="pvalue[6]" class="form-control"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="form-group">
            <label>زمان ارسال</label>
            <select name="timesend" class="form-control">
                <option value="complate">یکجا</option>
                <option value="5sec">هر 5 ثانیه 10 عدد</option>
                <option value="10sec">هر 10 ثانیه 15 عدد</option>
                <option value="15sec">هر 15 ثانیه 20 عدد</option>
                <option value="20sec">هر 20 ثانیه 30 عدد</option>
                <option value="25sec">هر 25 ثانیه 40 عدد</option>
                <option value="30sec">هر 30 ثانیه 50 عدد</option>
            </select>
            <small>برای ارسال های بالای 50 عدد با توجه به زمان بر بودن ارسال پیشنهاد میکنیم حتما یکی از وقفه ها را انتخاب کنید تا سرور شما دچار مشکل نشود و تمام ارسال ها انجام شوند</small>
        </div>
    </div>



    <button type="submit" name="send" value="form" class="btn btn-primary">ارسال پیامک</button>
    <button type="submit" name="send" value="test" class="btn btn-info">ارسال تست</button>

</form>

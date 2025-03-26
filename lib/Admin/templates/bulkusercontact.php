<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
use WHMCS\Module\Addon\smsir\vahabonline\pb;


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $pb_id = $_POST['pb_send'];
    $testnumber = $_POST['testnumber'];
    $type_send = $_POST['type_send'];
    $pattern_id = $_POST['pattern_id'];
    $send = $_POST['send'];
    $timesend = $_POST['timesend'];
    $message = $_POST['message'];
    $pname = $_POST['pname'];
    $pvalue = $_POST['pvalue'];
    $BulksecKey = pb::randomPassword();


    if(
        vahab::EoN($BulksecKey) ||
        vahab::EoN($pb_id) ||
        vahab::EoN($timesend) ||
        vahab::EoN($type_send)
    ){
        vahab::Alert([
            'class' => 'warning',
            'message' => 'انتخاب و وارد نمودن فیلد های دفتر تلفن ، زمان ارسال و نوع ارسال الزامی است',
            'url' => 'addonmodules.php?module=smsir&action=bulkusercontact'
        ]);
        die();
    }

    vahab::SetS('BulksecKey', $BulksecKey);

    if($send == 'test'){
        if(vahab::EoN($testnumber)){
            vahab::Alert([
                'class' => 'warning',
                'message' => 'وارد نمودن شماره تست الزامی است',
                'url' => 'addonmodules.php?module=smsir&action=bulkusercontact'
            ]);
            die();
        }
    }

    if($type_send == 'default'){

        if(vahab::EoN($message)){
            vahab::Alert([
                'class' => 'warning',
                'message' => 'وارد نمودن متن پیام الزامی است',
                'url' => 'addonmodules.php?module=smsir&action=bulkusercontact'
            ]);
            die();
        }



        $seNdCoDe = base64_encode(json_encode([
            'pb_send' => $pb_id,
            'testnumber' => $testnumber,
            'type_send' => $type_send,
            'timesend' => $timesend,
            'send' => $send,
            'message' => $message
        ]));
        $url = vahab::SiteUrl('/modules/addons/smsir/actions/sendings/sms.php?voToken=' . $BulksecKey . '&key=' . $seNdCoDe);
        echo '<iframe src="'.$url.'" style="width: 100%;border: solid 1px #eee;border-radius: 6px"></iframe>';


    }

    if($type_send == 'pattern'){
        $newarray = array();
        foreach ($pname as $key => $pn){
            if(!vahab::EoN($pn)){
                $newarray[$pn] = $pvalue[$key];
            }
        }
        if(vahab::EoN($pattern_id)){
            vahab::Alert([
                'class' => 'warning',
                'message' => 'شناسه خدماتی باید وارد شود',
                'url' => 'addonmodules.php?module=smsir&action=bulkusercontact'
            ]);
            die();
        }
        $seNdCoDe = base64_encode(json_encode([
                'pb_send' => $pb_id,
                'testnumber' => $testnumber,
                'type_send' => $type_send,
                'pattern_id' => $pattern_id,
                'timesend' => $timesend,
                'send' => $send,
                'pattern' => $newarray
        ]));
        $url = vahab::SiteUrl('/modules/addons/smsir/actions/sendings/sms.php?voToken=' . $BulksecKey . '&key=' . $seNdCoDe);
        echo '<iframe src="'.$url.'" style="width: 100%;border: solid 1px #eee;border-radius: 6px"></iframe>';
    }





}

if(vahab::EoN(vahab::GS('BulksecKey'))){
    vahab::SetS('BulksecKey', pb::randomPassword());
}


$lists = pb::GetAllPb();
?>

<div style="background: #f3f3f3;padding: 15px;border-radius: 6px;margin-bottom: 30px">
    <p>متغییر های مورد استفاده :</p>
    <pre style="direction: ltr;text-align: left;background: #fff;margin: 0px;">{firstname},{lastname},{email},{adress},{tell},{mobile}</pre>
</div>



<form method="post">



    <div class="form-group">
        <label>ارسال به دفتر تلفن</label>
        <select name="pb_send" class="form-control">
            <?php
                foreach ($lists as $list) {
                    $num = pb::CountPbNumbers($list->ID);
                    echo '<option value="'.$list->ID.'">'.$list->name.' - '.$num.' شماره</option>';
                }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>شماره تست</label>
        <input type="number" name="testnumber" class="form-control">
    </div>

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

    <button type="submit" name="send" value="form" class="btn btn-primary">ارسال پیامک</button>
    <button type="submit" name="send" value="test" class="btn btn-info">ارسال تست</button>

</form>
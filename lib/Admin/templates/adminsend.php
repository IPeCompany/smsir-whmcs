<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $save['admin_status'] = 0;
    if($_POST['open'] == 'on'){
        $save['admin_status'] = 1;
    }

    $save['admin_type_send'] = $_POST['type_send'];
    if(!vahab::EoN($_POST['message'])){
        $save['admin_message'] = $_POST['message'];
    }
    if(!vahab::EoN($_POST['pattern_id'])){
        $save['admin_pattern_id'] = $_POST['pattern_id'];
    }
    if(!vahab::EoN($_POST['pattern'])){
        $save['admin_pattern'] = $_POST['pattern'];
    }
    if(!vahab::EoN($_POST['numbers'])){
        $save['admin_numbers'] = $_POST['numbers'];
    }


    Capsule::table('smsir_vo_hooks')->where('ID', $_POST['hookid'])->update($save);

    vahab::Alert([
        'class' => 'success',
        'message' => 'تغییرات با موفقیت اعمال شدند',
        'url' => 'addonmodules.php?module=smsir&action=adminsend'
    ]);
}


# Create a random string


?>
<div class="row">

    <?php
        $hookdata = '';
        try {
            $hookdata = Capsule::table('smsir_vo_hooks')
            ->whereIn("send_for", ['admin' , 'all'])
            ->get();
        }catch (Exception $e){}

        foreach ($hookdata as $item){
            $ID = $item->ID;
            $checked = '';
            $select = '';
            $status = $item->admin_status;
            if($status == 1){
                $checked = 'active';
                $select = 'checked';
            }
            $slct_default = vahab::selectmenu('default', [$item->admin_type_send]);
            $slct_pattern = vahab::selectmenu('pattern', [$item->admin_type_send]);

            $type_send = $item->admin_type_send;
            $message = $item->admin_message;
            $admin_numbers = $item->admin_numbers;
            $params = $item->admin_params;
            $send_for = $item->send_for;
            $pattern = $item->admin_pattern;
            $pattern_id = $item->admin_pattern_id;
            $numbers = $item->admin_numbers;
    ?>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <div class="vo--smsir-notifs">
            <div class="vo--Box-body">
                <form method="post">
                    <input type="hidden" name="hookid" value="<?php echo $ID; ?>">
                    <div class="vo--smsir-header">
                        <label>
                            <input type="checkbox" class="smschekopen" id="open<?php echo $ID; ?>" name="open" onclick="opensms(<?php echo $ID; ?>)" <?php echo $select;  ?>>
                            <?php echo $item->label; ?>
                        </label>
                    </div>
                    <div class="vo-smsir--items <?php if($status){echo 'showbox';} ?>" id="smssend<?php echo $ID; ?>">
                        <div class="vo--flx-item">
                            <label>نوع ارسال</label>
                            <select name="type_send" onchange="changesmstype(<?php echo $ID; ?>, this.value)">
                                <option value="default" <?php echo $slct_default; ?>>ارسال عادی</option>
                                <option value="pattern" <?php echo $slct_pattern; ?>>ارسال خدماتی</option>
                            </select>
                        </div>

                        <div class="vo--flx-item">
                            <div id="smstypedefault<?php echo $ID; ?>" <?php if($type_send == 'pattern'){echo 'style="display: none"';} ?>>
                                <div class="vo--block-item">
                                    <label>متن پیامک</label>
                                    <textarea name="message"><?php echo $message; ?></textarea>
                                </div>
                                <div class="vo--block-item">
                                    <a href="javascript://" onclick="OpenTagsHook(<?php echo $ID; ?>)" class="vo--smsir-btnHookGen">شرت کدها</a>
                                </div>
                            </div>

                            <div id="smstypepattern<?php echo $ID; ?>" <?php if($type_send == 'default'){echo 'style="display: none"';} ?>>
                                <div class="vo--block-item">
                                    <label>شناسه قالب</label>
                                    <input type="text" name="pattern_id" value="<?php echo $pattern_id; ?>">
                                </div>
                                <div class="vo--block-item">
                                    <label>کد ساخته شده</label>
                                    <textarea name="pattern" class="vo--smsir-ltr pre"><?php echo $pattern; ?></textarea>
                                </div>
                                <div class="vo--block-item">
                                    <a href="javascript://" onclick="OpenTagsHook(<?php echo $ID; ?>, 'ptrn')" class="vo--smsir-btnHookGen">ساخت کد پیام خدماتی</a>
                                </div>
                            </div>




                            <!--         Tag generator                   -->
                            <div class="vo--smsir-tags" id="vo--smsir-tags<?php echo $ID; ?>">
                                <div class="HeaderVoSmsIRLeftSide">
                                    <span>مدیریت کدها</span>
                                    <a href="javascript://" onclick="OpenTagsHook(<?php echo $ID; ?>)" class="vo--smsir-btnHookGen">
                                        X
                                    </a>
                                </div>
                                <div class="BodyVoSmsIRsidebar">
                                    <div class="vo--smsir-tit">تگ های قابل استفاده</div>
                                    <pre>{signature},<?php echo $params; ?></pre>

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
                                                        <td><input type="text" id="txt<?php echo $ID; ?>a"></td>
                                                        <td><input type="text" id="vlu<?php echo $ID; ?>a"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" id="txt<?php echo $ID; ?>b"></td>
                                                        <td><input type="text" id="vlu<?php echo $ID; ?>b"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" id="txt<?php echo $ID; ?>c"></td>
                                                        <td><input type="text" id="vlu<?php echo $ID; ?>c"></td>
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
                                                            <textarea class="showCodeGen" id="showCodeGen<?php echo $ID; ?>"></textarea>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




                        </div>

                        <div class="vo--flx-item">
                            <div class="vo--block-item">
                                <label for="">شماره های ادمین</label>
                                <input type="text" name="numbers" value="<?php echo $numbers; ?>" class="vo--smsir-ltr">
                            </div>
                        </div>

                        <div class="vo--flx-item btnbx">
                            <button type="submit" class="btn vo--btn">ثبت تغییرات</button>
                        </div>






                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php } ?>


</div>
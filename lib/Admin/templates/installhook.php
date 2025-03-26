<?php
use WHMCS\Module\Addon\smsir\vahabonline\vahab;


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $res = vahab::installerHooks();
    if(vahab::EoN($res)){
        vahab::Alert([
            'class' => 'info',
            'message' => 'هوک جدیدی برای نصب وجود ندارد',
            'url' => 'addonmodules.php?module=smsir&action=installhook'
        ]);
    }else{
        vahab::Alert([
            'class' => 'success',
            'message' => 'هوک های زیر با موفقیت نصب شدند : <br/>' . $res ,
            'url' => 'addonmodules.php?module=smsir&action=installhook'
        ]);
    }
}



?>
<p>
    در این بخش میتوانید هوک های جدیدی که در بخش هوک قرار داده اید را نصب بفرمایید .
    <br/>
    در صورتی که در بخش پیامک های یادآوری مدیر و کاربر هیچ ارسالی وجود ندارد حتما یکبار روی نصب هوک های جدید در زیر کلیک بفرمایید
</p>
<form method="post">
    <button type="submit" class="btn btn-primary">نصب هوک های جدید</button>
</form>

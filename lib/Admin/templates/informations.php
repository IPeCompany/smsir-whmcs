<?php
    use WHMCS\Module\Addon\smsir\vahabonline\vahab;
?>

<h2>از اینکه از سامانه sms.ir استفاده میکنید بسیار خرسندیم .</h2>


<?php
    $getCredit = vahab::GetCreditAccount();
    $listLines = vahab::GetCreditAccount('line');
    if($getCredit['status'] == '1'){
        echo '<p>اعتبار سرویس شما : '.$getCredit['data']. ' می باشد</p>';
    }else{
        echo '<p>'.$getCredit['message'].'</p>';
    }
    echo '<p><br/></p>';
    echo '<h3><strong>لیست خطوط شما : </strong></h3>';
    echo '<ul>';
    foreach($listLines['data'] as $lin){
        echo '<li>'.$lin.'</li>';
    }
    echo '</ul>';
?>
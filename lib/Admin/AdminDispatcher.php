<?php

namespace WHMCS\Module\Addon\smsir\Admin;
use WHMCS\Module\Addon\smsir\vahabonline\vahab;

/**
 * Sample Admin Area Dispatch Handler
 */
class AdminDispatcher {

    /**
     * Dispatch request.
     *
     * @param string $action
     * @param array $parameters
     *
     * @return string
     */
    public function dispatch($action, $params)
    {
        if (!$action) {
            $action = 'index';
        }

        if(vahab::EoN($_GET['action'])){
            echo '<script>document.location.href = "addonmodules.php?module=smsir&action=index"</script>';
        }


        //header
        include(vahab::ADDONURL('lib/Admin/templates/statics/header.php'));

        //body
        $FileURL = vahab::$ADDONURL . 'lib/Admin/templates/' . $action . '.php';
        if(file_exists($FileURL)){
            include($FileURL);
        }else{
            echo 'Not Found';
        }

        //footer
        include(vahab::ADDONURL('lib/Admin/templates/statics/footer.php'));

    }
}



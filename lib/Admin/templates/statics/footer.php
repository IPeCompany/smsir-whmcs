<?php
use WHMCS\Module\Addon\smsir\vahabonline\vahab;
?>
</div>
</div>
<div style="text-align: left;direction: ltr;margin-top: 15px">Designed and developed by <a href="https://vahabonline.ir" target="_blank">vahabonline.ir</a>. </div>

<script>
    $('#vahabtable').DataTable(
        {
            responsive: true,
            language: {
                url: '<?php echo vahab::AdminTemplateDir('assets/js/Persian.json'); ?>',
            },
        }
    );
    $('#vahabtabletwo').DataTable(
        {
            responsive: true,
            language: {
                url: '<?php echo vahab::AdminTemplateDir('assets/js/Persian.json'); ?>',
            },
        }
    );
</script>
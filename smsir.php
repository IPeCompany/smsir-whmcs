<?php
use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\smsir\Admin\AdminDispatcher;
use WHMCS\Module\Addon\smsir\Client\ClientDispatcher;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function smsir_config()
{
    return [
        'name' => 'sms.ir',
        'description' => 'پیام ارسال کنید و کاربران را وریفای نمایید',
        'author' => '<a href="https://vahabonline.ir" target="_blank">وهاب آنلاین</a>',
        'language' => 'farsi',
        'version' => '1.0',
        'fields' => [
            'ApiKey' => [
                'FriendlyName' => 'کلید api',
                'Type' => 'text',
                'Size' => '500'
            ]
        ]
    ];
}

function smsir_activate()
{

    // Create custom tables and schema required by your module
    try {


        if(!Capsule::schema()->hasTable('smsir_vo_hooks_logs')){
            Capsule::schema()->create('smsir_vo_hooks_logs',
                function ($table) {
                    $table->increments('ID');
                    $table->text('hook');
                    $table->enum('type_send',['default','pattern'])->default('default');
                    $table->enum('send_for',['user','admin'])->default('user');
                    $table->integer('uid')->nullable();
                    $table->text('mobile');
                    $table->text('message')->nullable();
                    $table->text('result')->nullable();
                    $table->timestamps();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_hooks')){
            Capsule::schema()->create('smsir_vo_hooks',
                function ($table) {
                    $table->increments('ID');
                    $table->string('name',200);
                    $table->text('label');
                    $table->text('params')->nullable();
                    $table->text('admin_params')->nullable();
                    $table->enum('type_send',['default','pattern']);
                    $table->enum('admin_type_send',['default','pattern']);
                    $table->enum('send_for',['user','admin','all']);
                    $table->text('pattern')->nullable();
                    $table->string('pattern_id',20)->nullable();
                    $table->text('message')->nullable();
                    $table->text('admin_message')->nullable();
                    $table->text('admin_pattern')->nullable();
                    $table->string('admin_pattern_id',20)->nullable();
                    $table->text('admin_numbers')->nullable();
                    $table->integer('status')->default('0');
                    $table->integer('admin_status')->default('0');
                    $table->integer('user_status')->default('0');
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_pb_bulklog')){
            Capsule::schema()->create('smsir_vo_pb_bulklog',
                function ($table) {
                    $table->increments('ID');
                    $table->string('phonenumber',15);
                    $table->string('text',15);
                    $table->timestamp('send_at');
                    $table->text('result')->nullable();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_phone')){
            Capsule::schema()->create('smsir_vo_phone',
                function ($table) {
                    $table->increments('ID');
                    $table->integer('pb_id');
                    $table->text('firstname')->nullable();
                    $table->text('lastname')->nullable();
                    $table->text('email')->nullable();
                    $table->text('adress')->nullable();
                    $table->string('tell',15)->nullable();
                    $table->string('mobile',15);
                    $table->timestamps();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_phonebooks')){
            Capsule::schema()->create('smsir_vo_phonebooks',
                function ($table) {
                    $table->increments('ID');
                    $table->string('name',100);
                    $table->timestamps();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_settings')){
            Capsule::schema()->create('smsir_vo_settings',
                function ($table) {
                    $table->increments('ID');
                    $table->string('name',200);
                    $table->text('value')->nullable();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_usr_log')){
            Capsule::schema()->create('smsir_vo_usr_log',
                function ($table) {
                    $table->increments('ID');
                    $table->integer('userid');
                    $table->string('phonenumber',15);
                    $table->text('text');
                    $table->timestamp('send_at');
                    $table->text('result');
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_verifications_attempts')){
            Capsule::schema()->create('smsir_vo_verifications_attempts',
                function ($table) {
                    $table->increments('ID');
                    $table->integer('req_id');
                    $table->integer('user_id');
                    $table->timestamp('attempted_at');
                    $table->integer('attempt_status')->default('0');
                    $table->string('attempt_code',8);
                    $table->string('phonenumber',15);
                    $table->timestamps();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_verifications_smslogs')){
            Capsule::schema()->create('smsir_vo_verifications_smslogs',
                function ($table) {
                    $table->increments('ID');
                    $table->integer('userid');
                    $table->integer('req_id');
                    $table->string('sent_to',15);
                    $table->string('pattern_id',15)->nullable();
                    $table->text('message');
                    $table->timestamp('sent_at');
                    $table->text('result')->nullable();
                    $table->enum('type_send',['default','pattern'])->default('default');
                    $table->timestamps();
                }
            );
        }
        if(!Capsule::schema()->hasTable('smsir_vo_verifications_users')){
            Capsule::schema()->create('smsir_vo_verifications_users',
                function ($table) {
                    $table->increments('ID');
                    $table->string('req_id',50);
                    $table->string('expired_at',50)->nullable();
                    $table->integer('userid');
                    $table->string('phone_number',15);
                    $table->string('verification_code',6);
                    $table->integer('verified')->default('0');
                    $table->integer('attempts')->default('0');
                    $table->timestamp('verified_at')->nullable();
                    $table->timestamp('created_at')->nullable();
                }
            );
        }




        return [
            'status' => 'success',
            'description' => 'افزونه با موفقیت نصب و تمام جداول آن نیز ایجاد شدند',
        ];
    } catch (\Exception $e) {
        return [
            'status' => "error",
            'description' => 'Unable to create mod_addonexample: ' . $e->getMessage(),
        ];
    }
}

function smsir_deactivate()
{
    try {
        Capsule::schema()->dropIfExists('smsir_vo_hooks_logs');
        Capsule::schema()->dropIfExists('smsir_vo_hooks');
        Capsule::schema()->dropIfExists('smsir_vo_pb_bulklog');
        Capsule::schema()->dropIfExists('smsir_vo_phone');
        Capsule::schema()->dropIfExists('smsir_vo_phonebooks');
        Capsule::schema()->dropIfExists('smsir_vo_settings');
        Capsule::schema()->dropIfExists('smsir_vo_usr_log');
        Capsule::schema()->dropIfExists('smsir_vo_verifications_attempts');
        Capsule::schema()->dropIfExists('smsir_vo_verifications_smslogs');
        Capsule::schema()->dropIfExists('smsir_vo_verifications_users');

        return [
            'status' => 'success',
            'description' => 'افزونه حذف شد و تمام جداول با موفقیت حذف شدند',
        ];
    } catch (\Exception $e) {
        return [
            "status" => "error",
            "description" => "Unable to drop mod_addonexample: {$e->getMessage()}",
        ];
    }
}



function smsir_output($vars)
{
    // Get common module parameters
    $modulelink = $vars['modulelink']; // eg. smsirs.php?module=smsir
    $version = $vars['version']; // eg. 1.0
    $_lang = $vars['_lang']; // an array of the currently loaded language variables

    // Get module configuration parameters
    $configTextField = $vars['Text Field Name'];
    $configPasswordField = $vars['Password Field Name'];
    $configCheckboxField = $vars['Checkbox Field Name'];
    $configDropdownField = $vars['Dropdown Field Name'];
    $configRadioField = $vars['Radio Field Name'];
    $configTextareaField = $vars['Textarea Field Name'];

    // Dispatch and handle request here. What follows is a demonstration of one
    // possible way of handling this using a very basic dispatcher implementation.

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action, $vars);
    echo $response;
}


function smsir_clientarea($vars)
{
    // Get common module parameters
    $modulelink = $vars['modulelink']; // eg. index.php?m=smsir
    $version = $vars['version']; // eg. 1.0
    $_lang = $vars['_lang']; // an array of the currently loaded language variables

    // Get module configuration parameters
    $configTextField = $vars['Text Field Name'];
    $configPasswordField = $vars['Password Field Name'];
    $configCheckboxField = $vars['Checkbox Field Name'];
    $configDropdownField = $vars['Dropdown Field Name'];
    $configRadioField = $vars['Radio Field Name'];
    $configTextareaField = $vars['Textarea Field Name'];

    /**
     * Dispatch and handle request here. What follows is a demonstration of one
     * possible way of handling this using a very basic dispatcher implementation.
     */

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    $dispatcher = new ClientDispatcher();
    return $dispatcher->dispatch($action, $vars);
}

<?php
/* Create Table vo_usr_log */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_usr_log');

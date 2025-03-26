<?php
/* Create Table vo_settings */
if(!Capsule::schema()->hasTable('smsir_vo_settings')){
	Capsule::schema()->create('smsir_vo_settings',
		function ($table) {
			$table->increments('ID');
			$table->string('name',200);
			$table->text('value')->nullable();
		}
	);
}



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_settings');

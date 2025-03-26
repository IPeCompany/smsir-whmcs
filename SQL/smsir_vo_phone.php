<?php
/* Create Table vo_phone */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_phone');

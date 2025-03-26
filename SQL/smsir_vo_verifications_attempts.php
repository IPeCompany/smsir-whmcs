<?php
/* Create Table vo_verifications_attempts */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_verifications_attempts');

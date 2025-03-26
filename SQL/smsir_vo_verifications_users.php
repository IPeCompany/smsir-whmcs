<?php
/* Create Table vo_verifications_users */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_verifications_users');

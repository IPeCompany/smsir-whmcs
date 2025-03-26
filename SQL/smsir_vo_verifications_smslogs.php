<?php
/* Create Table vo_verifications_smslogs */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_verifications_smslogs');

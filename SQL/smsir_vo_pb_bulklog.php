<?php
/* Create Table vo_pb_bulklog */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_pb_bulklog');

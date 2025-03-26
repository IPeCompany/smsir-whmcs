<?php
/* Create Table vo_hooks_logs */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_hooks_logs');

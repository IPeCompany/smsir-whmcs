<?php
/* Create Table vo_hooks */
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



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_hooks');

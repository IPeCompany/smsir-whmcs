<?php
/* Create Table vo_phonebooks */
if(!Capsule::schema()->hasTable('smsir_vo_phonebooks')){
	Capsule::schema()->create('smsir_vo_phonebooks',
		function ($table) {
			$table->increments('ID');
			$table->string('name',100);
			$table->timestamps();
		}
	);
}



//Remove Table Code :
Capsule::schema()->dropIfExists('smsir_vo_phonebooks');

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeProcessesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('barcode_processes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('barcode_id');
			$table->integer('shelf_id');
			$table->datetime('came_date');
			$table->datetime('exit_date');
			$table->string('description');
			$table->string('status');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('barcode_processes');
	}

}

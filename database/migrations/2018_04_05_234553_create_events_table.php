<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('events', function (Blueprint $table) {
      $table->increments('id');
      //category enum (might change to ints after if needed)
      $table->enum('category', ['Sport', 'Culture', 'Other']);
      $table->string('name');
      $table->dateTime('date_time');
      $table->string('description');
      $table->unsignedInteger('organiser_id');
      $table->string('place');
      //store picture on directory not database
      //can change to be composite if multiple images are stored
      $table->string('image_file_path');
      $table->unsignedInteger('likeness_ranking');
      $table->timestamps();

      $table->foreign('organiser_id')->references('id')->on('users');
    });

  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('events');
  }
}

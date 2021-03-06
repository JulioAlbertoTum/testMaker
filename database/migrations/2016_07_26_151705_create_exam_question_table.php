<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamQuestionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exam_question',function(Blueprint $table){
			$table->increments('id');
			$table->integer('exam_id')->unsigned();
			$table->integer('question_id')->unsigned();
			$table->integer('percent');
			$table->integer('order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exam_question');
	}

}

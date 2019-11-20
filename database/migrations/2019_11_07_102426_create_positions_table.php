<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->string('publisher_id');

            $table->string('category');
            $table->string('first_type');
            $table->string('second_type');
            $table->string('third_type');

            $table->string('name');
            $table->string('advantage');
            $table->smallInteger('salary_from');
            $table->smallInteger('salary_to');
            $table->smallInteger('work_seniority_from')->nullable(); // null=不限；0=应届毕业生；
            $table->smallInteger('work_seniority_to')->nullable(); // null=不限；0=应届毕业生；
            $table->string('job_nature');
            $table->string('degree');

            $table->string('industry_labels');
            $table->string('position_labels');
            $table->string('skill_labels');

            $table->string('city');
            $table->string('district');
            $table->string('business_zones');
            $table->string('subway_line')->nullable();
            $table->string('subway_station')->nullable();
            $table->string('subway_linestation')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

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
        Schema::dropIfExists('positions');
    }
}

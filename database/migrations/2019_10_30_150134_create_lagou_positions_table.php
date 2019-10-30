<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLagouPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lagou_positions', function (Blueprint $table) {
            $table->bigIncrements('positionId');
            $table->string('positionName')->nullable();
            $table->string('companyId')->nullable();
            $table->string('companyFullName')->nullable();
            $table->string('companyShortName')->nullable();
            $table->string('companySize')->nullable();
            $table->string('industryField')->nullable();
            $table->string('financeStage')->nullable();
            $table->string('firstType')->nullable();
            $table->string('secondType')->nullable();
            $table->string('thirdType')->nullable();
            $table->string('skillLables')->nullable();
            $table->string('positionLables')->nullable();
            $table->string('industryLables')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('businessZones')->nullable();
            $table->string('salary')->nullable();
            $table->string('workYear')->nullable();
            $table->string('jobNature')->nullable();
            $table->string('education')->nullable();
            $table->string('positionAdvantage')->nullable();
            $table->string('publisherId')->nullable();
            $table->string('subwayline')->nullable();
            $table->string('stationname')->nullable();
            $table->string('linestaion')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamp('createTime')->nullable();
            $table->timestamp('updateTime')->nullable(); // 本应用补充的字段
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lagou_positions');
    }
}

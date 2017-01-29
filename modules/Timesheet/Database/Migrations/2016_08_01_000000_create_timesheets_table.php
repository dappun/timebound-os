<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimesheetsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->integer('project_id')->default(0);
            $table->string('ticket')->nullable();
            $table->integer('user_id');
            $table->timestamp('start')->default(\DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('end')->default(NULL)->nullable();
            $table->decimal('duration', 15,2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('timesheets');
    }
}

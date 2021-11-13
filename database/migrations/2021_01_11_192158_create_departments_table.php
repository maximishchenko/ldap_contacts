<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            // название
            $table->string('name');
            // сортировка
            $table->integer('sort')->nullable();
            // статус
            $table->smallInteger('status')->nullable();
            $table->timestamps();

            $table->index('name','idx_departments_name');
            $table->index('sort','idx_departments_sort');
            $table->index('status','idx_departments_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}

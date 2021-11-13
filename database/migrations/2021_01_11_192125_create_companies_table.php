<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            // название
            $table->string('name');

            $table->integer('parent_id')->nullable();
            // slug
            $table->string('slug')->nullable();
            // сортировка
            $table->integer('sort')->nullable();
            // статус
            $table->smallInteger('status')->nullable();

            // адрес
            $table->string('address')->nullable();
            // приемная
            $table->string('reception_phone')->nullable();
            // телефон
            $table->string('phone')->nullable();
            // факс (городской)
            $table->string('fax_city')->nullable();
            // адрес электронной почты
            $table->string('email')->nullable();

            $table->timestamps();

            $table->index('name','idx_companies_name');
            $table->index('sort','idx_companies_sort');
            $table->index('status','idx_companies_status');
            $table->index('address','idx_companies_address');
            $table->index('reception_phone','idx_companies_reception_phone');
            $table->index('phone','idx_companies_phone');
            $table->index('fax_city','idx_companies_fax_city');
            $table->index('email','idx_companies_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}

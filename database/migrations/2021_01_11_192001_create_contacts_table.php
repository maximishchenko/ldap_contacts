<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            // ldap objectSid
            $table->string('sid')->nullable();
            // Полное имя
            $table->string('displayName')->nullable();
            // Адрес электронной почты
            $table->string('mail')->nullable();
            // Внутренний номер телефона
            $table->string('telephoneNumber')->nullable();
            // Внешний номер телефона
            $table->string('homePhone')->nullable();
            // Номер IP-телефона
            $table->string('ipPhone')->nullable();
            // Мобильный номер телефона
            $table->string('mobile')->nullable();
            // Организация
            $table->string('company')->nullable();
            // Подразделение
            $table->string('department')->nullable();
            // Должность
            $table->string('title')->nullable();
            // Кабинет / комната
            $table->integer('physicalDeliveryOfficeName')->nullable();
            // Время последнего изменения в LDAP (timestamp)
            $table->integer('whenChanged')->nullable();
            // Сортировка
            $table->integer('sort')->nullable();
            // Статус записи
            $table->smallInteger('status')->nullable();
            $table->timestamps();

            // индексы полей таблицы
            $table->index('sid','idx_contacts_sid');
            $table->index('displayName','idx_contacts_displayName');
            $table->index('mail','idx_contacts_mail');
            $table->index('telephoneNumber','idx_contacts_telephoneNumber');
            $table->index('homePhone','idx_contacts_homePhone');
            $table->index('company','idx_contacts_company');
            $table->index('department','idx_contacts_department');
            $table->index('title','idx_contacts_title');
            $table->index('physicalDeliveryOfficeName','idx_contacts_physicalDeliveryOfficeName');
            $table->index('whenChanged','idx_contacts_whenChanged');
            $table->index('sort','idx_contacts_sort');
            $table->index('status','idx_contacts_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}

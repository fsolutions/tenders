<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->id();
            $table->string('type_as_role')->nullable()->index()->comment("Тип организации как роль");
            $table->string('value')->nullable()->index()->comment("Имя организации");
            $table->string('phone1', 50)->nullable()->comment("Телефон 1");
            $table->string('phone2', 50)->nullable()->comment("Телефон 2");
            $table->string('email1', 50)->nullable()->comment("E-mail 1");
            $table->string('email2', 50)->nullable()->comment("E-mail 2");
            $table->string('website', 50)->nullable()->comment("Website URL");
            $table->text('description')->nullable()->comment("Описание компании");
            $table->string('coords')->nullable()->comment("Координаты точки организации");
            $table->tinyInteger('radius')->nullable()->comment("Радиус действия цифрой / 1000");
            $table->string('address_from_map')->nullable()->comment("Адрес с карты");
            $table->string('city_from_map')->nullable()->comment("Город с карты");
            $table->string('logo')->nullable()->comment("Ссылка на логотип компании");
            $table->string('inn')->nullable()->index()->unique()->comment("ИНН");
            $table->string('kpp')->nullable()->index()->comment("КПП");
            $table->string('ogrn')->nullable()->index()->comment("ОГРН");
            $table->string('ogrn_date')->nullable()->comment("Дата выдачи ОГРН");
            $table->string('hid')->nullable()->comment("Дадата ID");
            $table->string('type', 50)->nullable()->comment("Тип организации LEGAL, INDIVIDUAL");
            $table->string('name_full_with_opf')->nullable()->comment("Полное наименование с ОПФ");
            $table->string('name_short_with_opf')->nullable()->comment("Краткое наименование с ОПФ");
            $table->string('name_full')->nullable()->comment("Полное наименование без ОПФ");
            $table->string('name_short')->nullable()->comment("Краткое наименование без ОПФ");
            $table->string('opf_short')->nullable()->comment("Краткое название ОПФ");
            $table->string('management_name')->nullable()->comment("ФИО руководителя");
            $table->string('management_post')->nullable()->comment("Должность руководителя");
            $table->string('address_value')->nullable()->comment("Адрес одной строкой");
            $table->string('address_unrestricted_value')->nullable()->comment("Адрес одной строкой (полный, с индексом)");
            $table->string('state_actuality_date')->nullable()->comment("Дата актуальности сведений компании");
            $table->string('state_registration_date')->nullable()->comment("Дата регистрации компании");
            $table->string('state_liquidation_date')->nullable()->comment("Дата ликвидации компании");
            $table->string('state_status')->nullable()->comment("Статус организации");
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
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}

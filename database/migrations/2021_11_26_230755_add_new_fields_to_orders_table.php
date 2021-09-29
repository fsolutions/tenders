<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('user_web_passport')->after('user_web_users_email')->nullable()->comment('Данные паспорта, либо реквизиты компании');
            $table->integer('manager_id')->nullable()->default(1)->after('id')->comment('Менеджер gravescare на заказ');
            // $table->json('order_services')->after('ispolnitel_user_id')->nullable()->comment('Услуги заказа');
            $table->text('order_services')->after('ispolnitel_user_id')->nullable()->comment('Услуги заказа');
            $table->date('start_date_of_work')->after('order_services')->nullable()->comment('Дата начала выполнения заказа');
            $table->date('end_date_of_work')->after('start_date_of_work')->nullable()->comment('Дата завершения исполнителем заказа');
            $table->integer('oriental_days_for_work')->after('end_date_of_work')->nullable()->comment('Кол-во дней на выполнение работ по плану');
            $table->enum('type_of_order_object', ['Могила', 'Квартира', 'Помещение', 'Другое'])->nullable()->default('Могила')->comment('Тип объекта');
            $table->text('address_of_order_object')->after('type_of_order_object')->nullable()->comment('Строчный адрес, при необходимости, инструкция как пройти');
            $table->text('final_comment')->after('end_date_of_work')->nullable()->comment('Финальное описание особенностей выполнения работ');
            $table->dateTime('datetime_of_client_signed_the_start')->after('oriental_days_for_work')->nullable()->comment('Дата подписания клиентом заказ-наряда');
            $table->dateTime('datetime_of_client_signed_the_end')->after('datetime_of_client_signed_the_start')->nullable()->comment('Дата подписания клиентом акта');
            $table->dateTime('datetime_of_ispolnitel_signed_the_start')->after('datetime_of_client_signed_the_end')->nullable()->comment('Дата подписания исполнителем заказ-наряда');
            $table->dateTime('datetime_of_ispolnitel_signed_the_end')->after('datetime_of_ispolnitel_signed_the_start')->nullable()->comment('Дата подписания исполнителем акта');
            $table->string('itogsum_for_client')->after('itogsum')->nullable()->default(0)->comment('Финальная сумма для агента');
            $table->integer('skidka_for_client')->after('itogsum_for_client')->nullable()->default(0)->comment('Скидка для клиента');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('user_web_passport');
            $table->dropColumn('manager_id');
            $table->dropColumn('order_services');
            $table->dropColumn('start_date_of_work');
            $table->dropColumn('end_date_of_work');
            $table->dropColumn('oriental_days_for_work');
            $table->dropColumn('type_of_order_object');
            $table->dropColumn('address_of_order_object');
            $table->dropColumn('final_comment');
            $table->dropColumn('datetime_of_client_signed_the_start');
            $table->dropColumn('datetime_of_client_signed_the_end');
            $table->dropColumn('datetime_of_ispolnitel_signed_the_start');
            $table->dropColumn('datetime_of_ispolnitel_signed_the_end');
            $table->dropColumn('itogsum_for_client');
            $table->dropColumn('skidka_for_client');
        });
    }
}

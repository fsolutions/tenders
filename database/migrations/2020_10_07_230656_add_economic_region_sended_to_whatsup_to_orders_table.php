<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEconomicRegionSendedToWhatsupToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            //Восточно-Сибирский==0||Волго-Вятский==1||Дальневосточный==2||Западно-Сибирский==3||Калининградский==4||Поволжский==5||Северный==6||Северо-Западный==7||Северо-Кавказский==8||Уральский==9||Центрально-Чернозёмный==10||Центральный==11
            $table->tinyInteger('order_economic_region_id')->after('order_city_id')->default(11)->comment('Регион экономической принадлежности');
            $table->boolean('sended_to_whatsup')->after('sended_to_telegram')->default(0)->comment('Отправлялся ли заказ в вотсап');
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
            $table->dropColumn('order_economic_region_id');
            $table->dropColumn('sended_to_whatsup');
        });
    }
}

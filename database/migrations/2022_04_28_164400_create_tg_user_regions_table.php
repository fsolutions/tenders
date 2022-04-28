<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTgUserRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tg_user_regions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('tg_user_id')->comment('Ссылка на пользователя телеграма');
            $table->foreignId('regions_id')->comment('Ссылка на регион из базы');
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
        Schema::dropIfExists('tg_user_regions');
    }
}

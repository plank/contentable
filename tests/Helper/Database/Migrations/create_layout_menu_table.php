<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('layout_menu', function (Blueprint $table) {
            $table->foreignId('layout_id')->constrained();
            $table->foreignId('menu_id')->constrained();
            $table->string('key')->nullable();
            $table->timestamps();

            $table->primary(['layout_id', 'menu_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('layout_menu');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained();
            $table->string('title');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};

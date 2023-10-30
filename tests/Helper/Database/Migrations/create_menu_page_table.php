<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_page', function (Blueprint $table) {
            $table->foreignId('menu_id')->constrained();
            $table->foreignId('page_id')->constrained();
            $table->string('key')->nullable();
            $table->timestamps();

            $table->primary(['menu_id', 'page_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_page');
    }
};

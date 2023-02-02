<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lto-translation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status')->default(0);
            $table->string('locale');
            $table->string('group');
            $table->string('key', 500);
            $table->string('value', 1000)->nullable();
            $table->timestamps();
            $table->index('group');
            $table->index('key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lto-translation');
    }
};

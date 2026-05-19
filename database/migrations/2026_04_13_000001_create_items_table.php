<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('category', 100)->nullable();
            $table->string('unit', 50)->nullable();
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(0);
            $table->timestamps();

            $table->index('category');
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}

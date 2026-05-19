<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSraItemsTable extends Migration
{
    public function up()
    {
        Schema::create('sra_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sra_id')->constrained('sra')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->integer('quantity');

            $table->unique(['sra_id', 'item_id']);
            $table->index('sra_id');
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sra_items');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->constrained('requisitions')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->integer('quantity_requested');

            $table->unique(['requisition_id', 'item_id']);
            $table->index('requisition_id');
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requisition_items');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryLedgerTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->enum('transaction_type', ['RECEIVE', 'ISSUE']);
            $table->integer('quantity');
            $table->integer('balance_after');
            $table->enum('reference_type', ['SRA', 'ISSUE']);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('item_id');
            $table->index('transaction_type');
            $table->index('reference_type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_ledger');
    }
}

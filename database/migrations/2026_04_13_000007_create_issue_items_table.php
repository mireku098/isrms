<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueItemsTable extends Migration
{
    public function up()
    {
        Schema::create('issue_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('issues')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->integer('quantity_issued');

            $table->unique(['issue_id', 'item_id']);
            $table->index('issue_id');
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('issue_items');
    }
}

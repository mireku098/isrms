<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->nullable()->constrained('requisitions')->onDelete('restrict');
            $table->foreignId('issued_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('requisition_id');
            $table->index('issued_by');
            $table->index('received_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
}

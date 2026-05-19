<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSraTable extends Migration
{
    public function up()
    {
        Schema::create('sra', function (Blueprint $table) {
            $table->id();
            $table->string('sra_number', 50)->unique()->nullable();
            $table->text('supplier_details')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->boolean('signed_storekeeper')->default(false);
            $table->boolean('signed_auditor')->default(false);
            $table->boolean('signed_principal')->default(false);
            $table->timestamps();

            $table->index('status');
            $table->index('sra_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sra');
    }
}

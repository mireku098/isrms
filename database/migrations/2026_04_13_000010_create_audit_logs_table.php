<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 255);
            $table->string('table_name', 100);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('action');
            $table->index('table_name');
            $table->index('created_at');
            $table->index(['table_name', 'record_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}

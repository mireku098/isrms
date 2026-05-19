<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiverInfoToIssuesTable extends Migration
{
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->string('receiver_name')->nullable()->after('issued_by');
            $table->string('receiver_signature')->nullable()->after('receiver_name');
            $table->text('remarks')->nullable()->after('receiver_signature');
            $table->timestamp('received_at')->nullable()->after('updated_at');
            $table->text('comments')->nullable()->after('received_at');
        });
    }

    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn(['receiver_name', 'receiver_signature', 'remarks', 'received_at', 'comments']);
        });
    }
}

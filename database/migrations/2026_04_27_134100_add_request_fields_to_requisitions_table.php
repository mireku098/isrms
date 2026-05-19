<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestFieldsToRequisitionsTable extends Migration
{
    public function up()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->string('department', 100)->nullable()->after('approved_by');
            $table->date('request_date')->nullable()->after('department');
            $table->text('purpose')->nullable()->after('request_date');
        });
    }

    public function down()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn(['department', 'request_date', 'purpose']);
        });
    }
}

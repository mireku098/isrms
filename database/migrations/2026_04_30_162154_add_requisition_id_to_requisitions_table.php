<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequisitionIdToRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->string('requisition_id')->nullable()->unique()->after('id');
        });

        // Populate existing records
        $requisitions = \Illuminate\Support\Facades\DB::table('requisitions')->get();
        foreach ($requisitions as $req) {
            \Illuminate\Support\Facades\DB::table('requisitions')
                ->where('id', $req->id)
                ->update(['requisition_id' => 'REQ-' . str_pad($req->id, 5, '0', STR_PAD_LEFT)]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn('requisition_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCategoryNameAndCategoryIdSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name', 100)->nullable()->after('id');
                $table->unique('name');
            });
        }

        if (!Schema::hasColumn('items', 'category_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('category');
                $table->index('category_id');
            });
        }

        if (
            Schema::hasTable('categories') &&
            Schema::hasTable('items') &&
            Schema::hasColumn('items', 'category_id')
        ) {
            try {
                Schema::table('items', function (Blueprint $table) {
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                });
            } catch (\Throwable $e) {
                // Skip foreign key application if it already exists or DB engine restrictions apply.
            }
        }

        if (
            Schema::hasTable('categories') &&
            Schema::hasTable('items') &&
            Schema::hasColumn('categories', 'name') &&
            Schema::hasColumn('items', 'category') &&
            Schema::hasColumn('items', 'category_id')
        ) {
            $existingCategories = DB::table('items')
                ->select('category')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category');

            foreach ($existingCategories as $categoryName) {
                $categoryId = DB::table('categories')->where('name', $categoryName)->value('id');

                if (!$categoryId) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'name' => $categoryName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('items')
                    ->where('category', $categoryName)
                    ->whereNull('category_id')
                    ->update(['category_id' => $categoryId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('items', 'category_id')) {
            Schema::table('items', function (Blueprint $table) {
                try {
                    $table->dropForeign(['category_id']);
                } catch (\Throwable $e) {
                    // Ignore if FK does not exist.
                }

                $table->dropIndex(['category_id']);
                $table->dropColumn('category_id');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropUnique(['name']);
                $table->dropColumn('name');
            });
        }
    }
}

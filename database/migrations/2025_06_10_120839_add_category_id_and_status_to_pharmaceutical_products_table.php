<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pharmaceutical_products', function (Blueprint $table) {
              $table->unsignedBigInteger('category_id')->nullable()->after('brand_names'); // adjust position as needed
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

        $table->boolean('status')->default(1)->after('image'); // 1 = Active 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pharmaceutical_products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        $table->dropColumn(['category_id', 'status']);
        });
    }
};

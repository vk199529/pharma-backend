<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      public function up()
{
    DB::statement('ALTER DATABASE `'.env('DB_DATABASE').'` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci');
    DB::statement('ALTER TABLE `pharmaceutical_products` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    Schema::table('pharmaceutical_products', function (Blueprint $t) {
        $t->string('storage')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utf8mb', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            // Use DB raw SQL to drop the foreign key if it exists
            DB::statement('ALTER TABLE faqs DROP FOREIGN KEY IF EXISTS faqs_faq_category_id_foreign');

            // Drop column if exists
            if (Schema::hasColumn('faqs', 'faq_category_id')) {
                $table->dropColumn('faq_category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->foreignId('faq_category_id')->constrained()->onDelete('cascade');
        });
    }
};

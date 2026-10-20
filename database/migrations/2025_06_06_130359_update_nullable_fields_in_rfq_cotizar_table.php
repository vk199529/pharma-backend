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
        Schema::table('rfq_cotizar', function (Blueprint $table) {
                $table->string('sku')->nullable()->change();
            $table->string('license')->nullable()->change();
            $table->string('incoterm')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfq_cotizar', function (Blueprint $table) {
              $table->string('sku')->nullable(false)->change();
            $table->string('license')->nullable(false)->change();
            $table->string('incoterm')->nullable(false)->change();
        });
    }
};

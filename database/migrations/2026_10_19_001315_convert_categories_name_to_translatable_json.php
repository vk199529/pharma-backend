<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        // First, allow longer translations.
        Schema::table('categories', function (Blueprint $table) {
            $table->text('name')->change();
        });

        // Convert existing plain-text names to English translations.
        DB::table('categories')
            ->select('id', 'name')
            ->orderBy('id')
            ->chunkById(200, function ($categories) {
                foreach ($categories as $category) {
                    $name = $category->name;

                    if ($name === null) {
                        continue;
                    }

                    $decoded = json_decode($name, true);

                    // Keep existing locale-based translations unchanged.
                    if (
                        json_last_error() === JSON_ERROR_NONE
                        && is_array($decoded)
                        && (
                            array_key_exists('en', $decoded)
                            || array_key_exists('es', $decoded)
                        )
                    ) {
                        continue;
                    }

                    // Store the original name as English.
                    $translatedName = json_encode(
                        ['en' => $name],
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    );

                    DB::table('categories')
                        ->where('id', $category->id)
                        ->update(['name' => $translatedName]);
                }
            });
    }

    public function down(): void
    {
        // Check first so rollback cannot silently truncate category names.
        $tooLong = DB::table('categories')
            ->select('id', 'name')
            ->get()
            ->first(function ($category) {
                $decoded = json_decode($category->name, true);

                $englishName = (
                    json_last_error() === JSON_ERROR_NONE
                    && is_array($decoded)
                    && array_key_exists('en', $decoded)
                )
                    ? $decoded['en']
                    : $category->name;

                return ! is_string($englishName)
                    || strlen($englishName) > 255;
            });

        if ($tooLong) {
            throw new RuntimeException(
                'Rollback stopped: an English category name is too long for VARCHAR(255).'
            );
        }

        // Restore plain English names.
        DB::table('categories')
            ->select('id', 'name')
            ->orderBy('id')
            ->chunkById(200, function ($categories) {
                foreach ($categories as $category) {
                    $decoded = json_decode($category->name, true);

                    if (
                        json_last_error() === JSON_ERROR_NONE
                        && is_array($decoded)
                        && array_key_exists('en', $decoded)
                    ) {
                        DB::table('categories')
                            ->where('id', $category->id)
                            ->update(['name' => $decoded['en']]);
                    }
                }
            });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });
    }
};
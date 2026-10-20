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
        DB::table('pharmaceutical_products')
            ->select(
                'id',
                'generic_name',
                'brand_names',
                'product_type',
                'form',
                'therapeutic_class',
                'manufacturer_name',
                'manufacturer_country',
                'gmp_status',
                'cofepris_status',
                'free_sale_certificate',
                'shelf_life',
                'storage',
                'incoterms',
                'lead_time',
                'special_notes'
            )
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    $updates = [];

                    $fields = [
                        'generic_name',
                        'brand_names',
                        'product_type',
                        'form',
                        'therapeutic_class',
                        'manufacturer_name',
                        'manufacturer_country',
                        'gmp_status',
                        'cofepris_status',
                        'free_sale_certificate',
                        'shelf_life',
                        'storage',
                        'incoterms',
                        'lead_time',
                        'special_notes',
                    ];

                    foreach ($fields as $field) {
                        $value = $row->$field;

                        if ($value === null || $value === '') {
                            continue;
                        }

                        $decoded = json_decode($value, true);

                        if (
                            !is_array($decoded) ||
                            !array_key_exists('en', $decoded)
                        ) {
                            $updates[$field] = json_encode(
                                ['en' => $value],
                                JSON_UNESCAPED_UNICODE
                            );
                        }
                    }

                    if (!empty($updates)) {
                        DB::table('pharmaceutical_products')
                            ->where('id', $row->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        // No automatic rollback to prevent translation data loss.
    }
};

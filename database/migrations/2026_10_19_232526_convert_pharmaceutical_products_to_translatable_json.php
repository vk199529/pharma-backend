
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
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
        ];

        foreach ($columns as $column) {
            DB::statement(
                "ALTER TABLE pharmaceutical_products
                 MODIFY `$column` LONGTEXT NULL"
            );
        }

        
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

                foreach ($rows as $row) {
                    $updates = [];

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
                                | JSON_THROW_ON_ERROR
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
        // No automatic rollback to avoid losing translation data.
    }
};

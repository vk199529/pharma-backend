
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('rfq_cotizar')
            ->select('id', 'product_name', 'name', 'company')
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    $updates = [];

                    foreach (['product_name', 'name', 'company'] as $field) {
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
                        DB::table('rfq_cotizar')
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

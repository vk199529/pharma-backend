<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('shipping_faqs')
            ->select('id', 'question', 'answer')
            ->orderBy('id')
            ->chunkById(100, function ($faqs) {
                foreach ($faqs as $faq) {
                    $question = json_decode($faq->question, true);
                    $answer = json_decode($faq->answer, true);

                    if (!is_array($question) ||
                        !array_key_exists('en', $question)) {
                        $question = ['en' => $faq->question];
                    }

                    if (!is_array($answer) ||
                        !array_key_exists('en', $answer)) {
                        $answer = ['en' => $faq->answer];
                    }

                    DB::table('shipping_faqs')
                        ->where('id', $faq->id)
                        ->update([
                            'question' => json_encode(
                                $question,
                                JSON_UNESCAPED_UNICODE
                            ),
                            'answer' => json_encode(
                                $answer,
                                JSON_UNESCAPED_UNICODE
                            ),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // No automatic rollback to avoid losing translation data.
    }
};
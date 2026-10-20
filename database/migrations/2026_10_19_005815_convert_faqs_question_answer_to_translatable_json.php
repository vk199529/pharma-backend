
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('faqs')
            ->orderBy('id')
            ->chunk(100, function ($faqs) {
                foreach ($faqs as $faq) {
                    $question = $faq->question;
                    $answer = $faq->answer;

                    // Avoid wrapping already translated JSON again.
                    $decodedQuestion = json_decode($question, true);
                    $decodedAnswer = json_decode($answer, true);

                    $questionIsTranslated = is_array($decodedQuestion)
                        && isset($decodedQuestion['en']);

                    $answerIsTranslated = is_array($decodedAnswer)
                        && isset($decodedAnswer['en']);

                    DB::table('faqs')
                        ->where('id', $faq->id)
                        ->update([
                            'question' => $questionIsTranslated
                                ? $question
                                : json_encode(['en' => $question],
                                    JSON_UNESCAPED_UNICODE),

                            'answer' => $answerIsTranslated
                                ? $answer
                                : json_encode(['en' => $answer],
                                    JSON_UNESCAPED_UNICODE),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // No automatic rollback: original translations may have been added.
    }
};
